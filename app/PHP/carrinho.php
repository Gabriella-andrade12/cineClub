<?php
require_once "../init.php";   // fornece a conexão PDO ($pdo), crud() e inicia a sessão

if (session_status() === PHP_SESSION_NONE) { session_start(); }
if (empty($_SESSION['id_usuario'])) { header('Location: login.php'); exit; }
$uid = (int)$_SESSION['id_usuario'];

/* ---------------- Helpers ---------------- */
if (!function_exists('h')) {
    function h($s) { return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8'); }
}
function lx_brl($v) { return 'R$ ' . number_format((float)$v, 2, ',', '.'); }

/* ---------------- Carrinho na sessão ----------------
 * ingressos: chave "id_sessao-assento" => [id_sessao, assento, tipo, valor, filme, dia, hora, sala]
 * produtos : id_produto => [nome, valor, qtd]
 * Nada vai ao banco aqui: a gravação só ocorre em pagamento.php (transação). */
if (!isset($_SESSION['carrinho']) || !is_array($_SESSION['carrinho'])) { $_SESSION['carrinho'] = []; }
$_SESSION['carrinho'] += ['ingressos' => [], 'produtos' => [], 'cupom' => null, 'id_endereco' => null];

function lx_subtotal() {
    $s = 0.0;
    foreach ($_SESSION['carrinho']['ingressos'] as $i) { $s += $i['valor']; }
    foreach ($_SESSION['carrinho']['produtos'] as $p)  { $s += $p['valor'] * $p['qtd']; }
    return round($s, 2);
}

/* REGRA DE CUPOM: existe, está ativo, dentro da validade e valor mínimo atingido */
function lx_cupom(PDO $pdo, $codigo, $subtotal) {
    $st = $pdo->prepare("SELECT * FROM cupom WHERE UPPER(codigo) = UPPER(?) LIMIT 1");
    $st->execute([$codigo]);
    $c = $st->fetch();
    if (!$c) return [null, 'Cupom inexistente.'];
    if (!in_array(strtoupper((string)$c['cupom_ativo']), ['SIM', '1', 'S', 'ATIVO'], true)) return [null, 'Cupom inativo.'];
    if (!empty($c['data_validade']) && $c['data_validade'] < date('Y-m-d')) return [null, 'Cupom vencido.'];
    if ($subtotal < (float)$c['valor_minimo_pedido']) return [null, 'Pedido mínimo do cupom: ' . lx_brl($c['valor_minimo_pedido']) . '.'];
    return [$c, null];
}

function lx_msg($texto, $tipo = 'ok') { $_SESSION['msg_carrinho'][] = [$texto, $tipo]; }

/* ---------------- Ações (POST) ---------------- */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $acao = $_POST['acao'] ?? 'atualizar';

    // Remover um grupo de ingressos ("id_sessao|tipo") ou um produto
    if (!empty($_POST['remover_ingressos'])) {
        list($rs, $rt) = array_pad(explode('|', $_POST['remover_ingressos'], 2), 2, '');
        foreach ($_SESSION['carrinho']['ingressos'] as $k => $i) {
            if ((string)$i['id_sessao'] === $rs && $i['tipo'] === $rt) unset($_SESSION['carrinho']['ingressos'][$k]);
        }
        lx_msg('Ingressos removidos do carrinho.');
        header('Location: carrinho.php'); exit;
    }
    if (!empty($_POST['remover_produto'])) {
        unset($_SESSION['carrinho']['produtos'][(int)$_POST['remover_produto']]);
        lx_msg('Produto removido do carrinho.');
        header('Location: carrinho.php'); exit;
    }

    // Atualiza quantidades (0 remove o item)
    foreach ((array)($_POST['qtd'] ?? []) as $idp => $q) {
        $idp = (int)$idp; $q = (int)$q;
        if (!isset($_SESSION['carrinho']['produtos'][$idp])) continue;
        if ($q <= 0) unset($_SESSION['carrinho']['produtos'][$idp]);
        else $_SESSION['carrinho']['produtos'][$idp]['qtd'] = min($q, 50);
    }

    // Cupom
    if (!empty($_POST['remover_cupom'])) {
        $_SESSION['carrinho']['cupom'] = null;
    } else {
        $cod = trim($_POST['cupom'] ?? '');
        if ($cod !== '' && strcasecmp($cod, (string)$_SESSION['carrinho']['cupom']) !== 0) {
            list($cp, $erro_cp) = lx_cupom($pdo, $cod, lx_subtotal());
            if ($cp) { $_SESSION['carrinho']['cupom'] = $cp['codigo']; lx_msg('Cupom aplicado!'); }
            else     { lx_msg($erro_cp, 'erro'); }
        }
    }

    // Avançar: produtos físicos exigem endereço de entrega
    if ($acao === 'avancar') {
        if (!$_SESSION['carrinho']['ingressos'] && !$_SESSION['carrinho']['produtos']) {
            lx_msg('Seu carrinho está vazio.', 'erro');
        } else {
            $ok = true;
            if ($_SESSION['carrinho']['produtos']) {
                $esc = $_POST['id_endereco'] ?? '';
                if ($esc === 'novo') {
                    $e = [];
                    foreach (['logradouro', 'numero', 'complemento', 'bairro', 'cidade', 'estado', 'cep'] as $k) { $e[$k] = trim($_POST[$k] ?? ''); }
                    $e['cep'] = preg_replace('/\D/', '', $e['cep']);
                    if ($e['logradouro'] === '' || $e['numero'] === '' || $e['bairro'] === '' || $e['cidade'] === ''
                        || strlen($e['estado']) !== 2 || strlen($e['cep']) !== 8) {
                        lx_msg('Preencha o endereço (UF com 2 letras e CEP com 8 dígitos).', 'erro');
                        $ok = false;
                    } else {
                        $_SESSION['carrinho']['id_endereco'] = (int)create($pdo, 'endereco_entrega', [
                            'id_usuario'  => $uid,
                            'logradouro'  => $e['logradouro'],
                            'numero'      => $e['numero'],
                            'complemento' => $e['complemento'],
                            'bairro'      => $e['bairro'],
                            'cidade'      => $e['cidade'],
                            'estado'      => strtoupper($e['estado']),
                            'cep'         => $e['cep'],
                        ]);
                    }
                } else {
                    // o endereço precisa pertencer ao usuário logado
                    $end = read($pdo, 'endereco_entrega', 'id_endereco = ? AND id_usuario = ?', [(int)$esc, $uid]);
                    if ($end) { $_SESSION['carrinho']['id_endereco'] = (int)$end['id_endereco']; }
                    else { lx_msg('Selecione ou cadastre um endereço de entrega.', 'erro'); $ok = false; }
                }
            } else {
                $_SESSION['carrinho']['id_endereco'] = null;
            }
            if ($ok) { header('Location: pagamento.php'); exit; }
        }
    }
    header('Location: carrinho.php');
    exit;
}

/* ---------------- Dados para exibição ---------------- */
$ingressos = $_SESSION['carrinho']['ingressos'];
$produtos  = $_SESSION['carrinho']['produtos'];
$vazio     = !$ingressos && !$produtos;

// Agrupa ingressos por sessão + tipo (inteira/meia)
$grupos = [];
foreach ($ingressos as $i) {
    $g = $i['id_sessao'] . '|' . $i['tipo'];
    if (!isset($grupos[$g])) { $grupos[$g] = ['info' => $i, 'assentos' => [], 'total' => 0.0]; }
    $grupos[$g]['assentos'][] = $i['assento'];
    $grupos[$g]['total'] += $i['valor'];
}

// Totais (cupom revalidado a cada carregamento)
$subtotal = lx_subtotal();
$desconto = 0.0; $cupom_ativo = null;
if (!empty($_SESSION['carrinho']['cupom'])) {
    list($cp, $e) = lx_cupom($pdo, $_SESSION['carrinho']['cupom'], $subtotal);
    if ($cp) {
        $cupom_ativo = $cp['codigo'];
        $t = strtolower((string)$cp['tipo_desconto']);
        $desconto = (strpos($t, 'porc') !== false || strpos($t, 'perc') !== false || strpos($t, '%') !== false) ? $subtotal * ((float)$cp['valor_desconto'] / 100) : (float)$cp['valor_desconto'];
        $desconto = round(min($desconto, $subtotal), 2);
    } else {
        $_SESSION['carrinho']['cupom'] = null;
    }
}
$total = round($subtotal - $desconto, 2);
$fator = $subtotal > 0 ? $total / $subtotal : 1;   // desconto proporcional por item

$enderecos = [];
if ($produtos) {
    $st = $pdo->prepare("SELECT * FROM endereco_entrega WHERE id_usuario = ? ORDER BY id_endereco DESC");
    $st->execute([$uid]);
    $enderecos = $st->fetchAll();
}
$end_sel = (int)($_SESSION['carrinho']['id_endereco'] ?? 0);

$mensagens = $_SESSION['msg_carrinho'] ?? [];
unset($_SESSION['msg_carrinho']);
$estilo_msg = ['ok' => 'background:#1f3d2e;border:1px solid #2f7a52;', 'erro' => 'background:#4a1f25;border:1px solid #a33a48;'];
$estilo_ctl = 'padding:8px 10px;border-radius:10px;border:1px solid #2a3d5e;background:#0f1a33;color:inherit;';
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CINELUX - Meu Carrinho</title>
    <link rel="stylesheet" href="./CSS/carrinho.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="shortcut icon" href="./img/Logo - LUX.png" type="image/x-icon">
</head>

<body>

    <header class="cabecalho">
        <a href="./inicio.php" class="logo">
            <img src="./img/Logo - LUX.png" alt="Logo LUX Cinema" class="logo-img">
        </a>

        <nav class="navegacao-menu">
            <a href="./inicio.php" class="navegacao-opcoes">Lançamentos</a>
            <a href="./recomendados.php" class="navegacao-opcoes">Recomendados</a>
            <a href="./bomboniere.php" class="navegacao-opcoes">Combos</a>
            <a href="./infantil.php" class="navegacao-opcoes">Infantil</a>
        </nav>

        <div class="busca">
            <input type="text" placeholder="Buscar filmes, gêneros, cinemas...">
            <i class="fa-solid fa-magnifying-glass busca-icon"></i>
        </div>

        <div class="acoes-do-usuario">
            <a href="./minha_lista.php" class="link"><i class="fa-solid fa-bookmark"></i></a>
            <a href="./perfil.php" class="link"><i class="fa-solid fa-user"></i></a>
        </div>
    </header>

    <main class="faixa-principal">
        <div class="fita-de-filme" aria-hidden="true"></div>

        <h1 class="apresentacao-titulo">Seu Carrinho</h1>
        <p class="apresentacao-texto">Confira os ingressos e combos da bomboniere do seu pedido.
        </p>

        <?php foreach ($mensagens as $m): ?>
            <div role="status" style="padding:10px 14px;border-radius:10px;margin:0 0 14px;<?= $estilo_msg[$m[1]] ?? $estilo_msg['ok'] ?>"><?= h($m[0]) ?></div>
        <?php endforeach; ?>

        <?php if ($vazio): ?>
            <article class="cartao item-carrinho" style="text-align:center;padding:30px;">
                <h3 class="item-titulo">Seu carrinho está vazio</h3>
                <p class="item-subtexto">Escolha um filme em cartaz ou monte seu combo na bomboniere.</p>
                <p><a href="./inicio.php" class="botao botao-finalizar">Ver filmes</a>
                   <a href="./bomboniere.php" class="botao">Bomboniere</a></p>
            </article>
        <?php else: ?>

        <form class="carrinho" method="post" action="carrinho.php">
            <!-- botão padrão (Enter nos campos apenas atualiza) -->
            <button type="submit" name="acao" value="atualizar" hidden></button>

            <div class="coluna-itens">

                <?php foreach ($grupos as $chave => $g): $i = $g['info']; $n = count($g['assentos']); sort($g['assentos']); ?>
                <article class="cartao item-carrinho">
                    <div class="produtos">
                        <div class="item-detalhes">
                            <h3 class="item-titulo"><i class="fa-solid fa-ticket"></i> <?= $n ?>x Ingresso<?= $n > 1 ? 's' : '' ?> <?= h(ucfirst($i['tipo'])) ?> - <?= h($i['filme']) ?></h3>
                            <p class="item-subtexto">Sessão: <?= h(date('d/m', strtotime($i['dia']))) ?> • <?= h(substr($i['hora'], 0, 5)) ?> | Sala <?= h($i['sala']) ?> | Poltronas: <?= h(implode(', ', $g['assentos'])) ?></p>
                            <button type="submit" name="remover_ingressos" value="<?= h($chave) ?>" class="botao" style="margin-top:8px;">Remover</button>
                        </div>
                        <div class="item-precos">
                            <?php if ($desconto > 0): ?><span class="preco-base"><?= lx_brl($g['total']) ?></span><?php endif; ?>
                            <span class="preco-desconto"><?= lx_brl($g['total'] * $fator) ?></span>
                        </div>
                    </div>
                </article>
                <?php endforeach; ?>

                <?php if ($produtos): ?>
                <h3 class="subtitulo-secao">Itens da Bomboniere</h3>

                <?php foreach ($produtos as $idp => $p): $linha = $p['valor'] * $p['qtd']; ?>
                <article class="cartao item-carrinho">
                    <div class="produtos">
                        <img src="./img/Logo - LUX.png" alt="<?= h($p['nome']) ?>" class="item-thumb">
                        <div class="item-detalhes">
                            <h3 class="item-titulo"><?= h($p['nome']) ?></h3>
                            <p class="item-subtexto"><?= lx_brl($p['valor']) ?> cada</p>
                            <p class="item-subtexto" style="margin-top:8px;">
                                <label>Qtd: <input type="number" name="qtd[<?= (int)$idp ?>]" value="<?= (int)$p['qtd'] ?>" min="0" max="50" style="width:70px;<?= $estilo_ctl ?>"></label>
                                <button type="submit" name="acao" value="atualizar" class="botao">Atualizar</button>
                                <button type="submit" name="remover_produto" value="<?= (int)$idp ?>" class="botao">Remover</button>
                            </p>
                        </div>
                        <div class="item-precos">
                            <?php if ($desconto > 0): ?><span class="preco-base"><?= lx_brl($linha) ?></span><?php endif; ?>
                            <span class="preco-desconto"><?= lx_brl($linha * $fator) ?></span>
                        </div>
                    </div>
                </article>
                <?php endforeach; ?>

                <h3 class="subtitulo-secao">Endereço de entrega</h3>
                <article class="cartao item-carrinho">
                    <div class="item-detalhes" style="width:100%;">
                        <?php foreach ($enderecos as $n => $e): ?>
                            <p class="item-subtexto"><label>
                                <input type="radio" name="id_endereco" value="<?= (int)$e['id_endereco'] ?>" <?= ($end_sel === (int)$e['id_endereco'] || (!$end_sel && $n === 0)) ? 'checked' : '' ?>>
                                <?= h($e['logradouro']) ?>, <?= h($e['numero']) ?> <?= h($e['complemento']) ?> — <?= h($e['bairro']) ?>, <?= h($e['cidade']) ?>/<?= h($e['estado']) ?> (CEP <?= h($e['cep']) ?>)
                            </label></p>
                        <?php endforeach; ?>
                        <p class="item-subtexto"><label><input type="radio" name="id_endereco" value="novo" <?= $enderecos ? '' : 'checked' ?>> Cadastrar novo endereço</label></p>
                        <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(150px,1fr));gap:8px;margin-top:8px;">
                            <input type="text" name="logradouro" placeholder="Logradouro" style="<?= $estilo_ctl ?>">
                            <input type="text" name="numero" placeholder="Número" style="<?= $estilo_ctl ?>">
                            <input type="text" name="complemento" placeholder="Complemento" style="<?= $estilo_ctl ?>">
                            <input type="text" name="bairro" placeholder="Bairro" style="<?= $estilo_ctl ?>">
                            <input type="text" name="cidade" placeholder="Cidade" style="<?= $estilo_ctl ?>">
                            <input type="text" name="estado" placeholder="UF" maxlength="2" style="<?= $estilo_ctl ?>">
                            <input type="text" name="cep" placeholder="CEP" maxlength="9" style="<?= $estilo_ctl ?>">
                        </div>
                    </div>
                </article>
                <?php endif; ?>

            </div>

            <aside class="coluna-resumo">
                <div class="cartao cartao-resumo">
                    <h2 class="cartao-titulo">Resumo do Pedido</h2>

                    <?php foreach ($grupos as $g): $n = count($g['assentos']); ?>
                        <div class="linha-resumo resumo-ingresso">
                            <span>Ingressos <?= h(ucfirst($g['info']['tipo'])) ?> (<?= $n ?>x)</span>
                            <span class="valor-linha"><?= lx_brl($g['total']) ?></span>
                        </div>
                    <?php endforeach; ?>
                    <?php foreach ($produtos as $p): ?>
                        <div class="linha-resumo resumo-pipoca">
                            <span><?= h($p['nome']) ?> (<?= (int)$p['qtd'] ?>x)</span>
                            <span class="valor-linha"><?= lx_brl($p['valor'] * $p['qtd']) ?></span>
                        </div>
                    <?php endforeach; ?>

                    <div class="divisor-resumo"></div>

                    <!-- Cupom -->
                    <div class="linha-total" style="gap:8px;">
                        <?php if ($cupom_ativo): ?>
                            <span>Cupom <strong><?= h($cupom_ativo) ?></strong> aplicado</span>
                            <button type="submit" name="remover_cupom" value="1" class="botao">Remover</button>
                        <?php else: ?>
                            <input type="text" name="cupom" placeholder="Código do cupom" aria-label="Cupom de desconto" style="flex:1;<?= $estilo_ctl ?>">
                            <button type="submit" name="acao" value="atualizar" class="botao">Aplicar</button>
                        <?php endif; ?>
                    </div>

                    <div class="totais-valores">
                        <div class="linha-total">
                            <span>Subtotal dos itens:</span>
                            <span class="total-dinamico-valores"><?= lx_brl($subtotal) ?></span>
                        </div>
                        <div class="linha-total">
                            <span>Desconto do cupom:</span>
                            <span>– <?= lx_brl($desconto) ?></span>
                        </div>
                        <div class="linha-total-final">
                            <span>Valor Total:</span>
                            <strong class="total-com-taxa"><?= lx_brl($total) ?></strong>
                        </div>
                    </div>

                    <button type="submit" name="acao" value="avancar" class="botao botao-finalizar">Avançar para Pagamento</button>
                </div>
            </aside>

        </form>
        <?php endif; ?>


        <section class="faixa-de-informacoes">
            <div class="informacoes">
                <span class="faixa-titulo">CINELUX EXPERIENCE</span>
                <h2>Sua plataforma completa de cinema</h2>
                <p>Sua plataforma completa para acompanhar lançamentos de cinema, garantir ingressos sem filas,
                    consultar
                    horários de exibição em tempo real e reservar combos com facilidade.</p>
            </div>
            <div class="apps-disponiveis">
                <button class="botao-app"><i class="fa-brands fa-apple"></i> App Store</button>
                <button class="botao-app"><i class="fa-brands fa-google-play"></i> Google Play</button>
            </div>
        </section>
    </main>

    <footer class="rodape">
        <div class="rodape-logo">
            <img src="./img/Logo - LUX.png" alt="LUX Rodapé Logo">
        </div>

        <div class="grade-do-rodape">
            <div class="rodape-informacoes">
                <a href="#">Filmes em Cartaz</a>
                <a href="#">Próximas Estreias</a>
                <a href="./bomboniere.php">Bomboniere & Combos</a>
            </div>
            <div class="rodape-informacoes">
                <a href="#">Cinemas Parceiros</a>
                <a href="#">Central de Ajuda</a>
                <a href="#">Termos de Uso</a>
            </div>
            <div class="rodape-informacoes">
                <a href="#">Política de Privacidade</a>
                <a href="#">Meia Entrada</a>
                <a href="#">Atendimento ao Cliente</a>
            </div>
            <div class="rodape-informacoes informacoes-contato">
                <p>Atendimento ao Cliente</p>
                <a href="mailto:contato@lux.com.br" class="email-contato">contato@lux.com.br</a>
                <div class="redes-informacoes">
                    <a href="#"><i class="fa-brands fa-tiktok"></i></a>
                    <a href="#"><i class="fa-brands fa-instagram"></i></a>
                    <a href="#"><i class="fa-brands fa-facebook"></i></a>
                </div>
            </div>
        </div>
    </footer>

</body>

</html>