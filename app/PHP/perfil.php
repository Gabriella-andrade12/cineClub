<?php
require_once "./init.php";   // deve fornecer a conexão mysqli ($conn) e iniciar a sessão

if (session_status() === PHP_SESSION_NONE) { session_start(); }
if (empty($_SESSION['id_usuario'])) { header('Location: login.php'); exit; }
$uid = (int)$_SESSION['id_usuario'];

if (!function_exists('h')) {
    function h($s) { return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8'); }
}
/* SELECT com Prepared Statement: devolve todas as linhas */
function lx_todos($conn, $sql, $types = '', $params = []) {
    $st = mysqli_prepare($conn, $sql);
    if ($types !== '') { mysqli_stmt_bind_param($st, $types, ...$params); }
    mysqli_stmt_execute($st);
    $res = mysqli_stmt_get_result($st);
    $rows = $res ? mysqli_fetch_all($res, MYSQLI_ASSOC) : [];
    mysqli_stmt_close($st);
    return $rows;
}
function lx_brl($v) { return 'R$ ' . number_format((float)$v, 2, ',', '.'); }

/* ---------- Dados cadastrais ---------- */
$u = lx_todos($conn, "SELECT nome, nascimento, cpf, email, telefone FROM usuario WHERE id_usuario = ?", 'i', [$uid]);
if (!$u) { header('Location: logout.php'); exit; }
$u = $u[0];

// CPF mascarado: ***.***.789-**
$cpf = preg_replace('/\D/', '', $u['cpf']);
$cpf_mascarado = strlen($cpf) === 11 ? '***.***.' . substr($cpf, 6, 3) . '-**' : '***.***.***-**';

// Telefone formatado (opcional no cadastro)
$tel = preg_replace('/\D/', '', (string)$u['telefone']);
if (strlen($tel) === 11)      { $tel_fmt = '(' . substr($tel, 0, 2) . ') ' . substr($tel, 2, 5) . '-' . substr($tel, 7); }
elseif (strlen($tel) === 10)  { $tel_fmt = '(' . substr($tel, 0, 2) . ') ' . substr($tel, 2, 4) . '-' . substr($tel, 6); }
else                          { $tel_fmt = $tel !== '' ? $tel : 'Não informado'; }

$nascimento = $u['nascimento'] ? date('d/m/Y', strtotime($u['nascimento'])) : '-';

/* ---------- Plano de streaming ativo ---------- */
$plano = lx_todos(
    $conn,
    "SELECT a.periodo, a.data_inicio, a.data_fim, p.nome_plano, p.descricao_plano
       FROM assinatura a JOIN plano p ON p.id_plano = a.id_plano
      WHERE a.id_usuario = ? AND a.status_assinatura = 'Ativo' AND a.data_fim >= CURDATE()
      ORDER BY a.data_fim DESC LIMIT 1",
    'i',
    [$uid]
);
$plano = $plano ? $plano[0] : null;

// Benefícios: a descrição do plano pode listar itens separados por quebra de linha, ';' ou '|'
$beneficios = [];
if ($plano && trim((string)$plano['descricao_plano']) !== '') {
    $beneficios = array_values(array_filter(array_map('trim', preg_split('/[\r\n;|]+/', $plano['descricao_plano']))));
}

/* ---------- Compras recentes (venda + pagamento) ---------- */
$compras = lx_todos(
    $conn,
    "SELECT v.id_venda, v.data_pedido, v.valor_pedido, p.forma_pagamento, p.status_pagamento
       FROM venda v LEFT JOIN pagamento p ON p.id_venda = v.id_venda
      WHERE v.id_usuario = ? ORDER BY v.data_pedido DESC LIMIT 5",
    'i',
    [$uid]
);
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CINELUX - Meu Perfil</title>
    <link rel="stylesheet" href="./CSS/perfil.css">
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
            <a href="./infantil.html" class="navegacao-opcoes">Infantil</a>
        </nav>

        <div class="busca">
            <input type="text" placeholder="Buscar filmes, gêneros, cinemas...">
            <i class="fa-solid fa-magnifying-glass busca-icon"></i>
        </div>

        <div class="acoes-do-usuario">
            <a href="carrinho.php" class="link"><i class="fa-solid fa-cart-shopping"></i></a>
            <a href="./listas.php" class="link"><i class="fa-solid fa-bookmark"></i></a>
        </div>
    </header>

    <main class="faixa-principal">
        <div class="fita-de-filme" aria-hidden="true"></div>

        <section class="conteudo-perfil">

            <div class="perfil-cabecalho">
                <div class="perfil-avatar">
                    <i class="fa-solid fa-user"></i>
                </div>
                <div class="perfil-info-basica">
                    <h1 class="perfil-nome"><?= h($u['nome']) ?></h1>
                    <p class="perfil-email"><?= h($u['email']) ?></p>
                </div>
            </div>

            <div class="perfil-informacoes">

                <article class="cartao cartao-perfil">
                    <h2 class="cartao-titulo">Dados Pessoais</h2>
                    <p class="cartao-subtitulo">Informações cadastradas na sua conta</p>

                    <div class="formulario">
                        <div class="campo">
                            <label>Nome Completo</label>
                            <div class="campo-entrada">
                                <i class="fa-regular fa-user campo-icone"></i>
                                <input type="text" value="<?= h($u['nome']) ?>" readonly>
                            </div>
                        </div>

                        <div class="campo">
                            <label>CPF</label>
                            <div class="campo-entrada">
                                <i class="fa-regular fa-id-card campo-icone"></i>
                                <input type="text" value="<?= h($cpf_mascarado) ?>" readonly>
                            </div>
                        </div>

                        <div class="campo">
                            <label>Data de Nascimento</label>
                            <div class="campo-entrada">
                                <i class="fa-regular fa-calendar campo-icone"></i>
                                <input type="text" value="<?= h($nascimento) ?>" readonly>
                            </div>
                        </div>

                        <div class="campo">
                            <label>Telefone</label>
                            <div class="campo-entrada">
                                <i class="fa-solid fa-phone campo-icone"></i>
                                <input type="tel" value="<?= h($tel_fmt) ?>" readonly>
                            </div>
                        </div>
                    </div>
                </article>

                <article class="cartao cartao-perfil">
                    <h2 class="cartao-titulo">Sua Assinatura</h2>
                    <p class="cartao-subtitulo">Detalhes do seu plano atual</p>

                    <?php if ($plano): ?>
                        <div class="caixa-plano-selecionado">
                            <span class="rotulo-resumo">Plano Ativo</span>
                            <h3 class="titulo-resumo"><?= h($plano['nome_plano']) ?> (<?= h($plano['periodo']) ?>)</h3>
                            <p class="detalhe-plano">Renova em: <strong><?= h(date('d/m/Y', strtotime($plano['data_fim']))) ?></strong></p>
                        </div>

                        <?php if ($beneficios): ?>
                            <div class="bloco-beneficios-resumo">
                                <h4 class="subtitulo-beneficios">Seus benefícios inclusos:</h4>
                                <ul class="lista-beneficios-perfil">
                                    <?php foreach ($beneficios as $b): ?>
                                        <li><i class="fa-solid fa-check icone-sim"></i> <?= h($b) ?></li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        <?php endif; ?>
                    <?php else: ?>
                        <div class="caixa-plano-selecionado">
                            <span class="rotulo-resumo">Sem plano ativo</span>
                            <h3 class="titulo-resumo">Você ainda não é assinante</h3>
                            <p class="detalhe-plano">Assine e tenha acesso ao catálogo de streaming.</p>
                        </div>
                    <?php endif; ?>

                    <div class="perfil-acoes">
                        <a href="./planos.php" class="botao botao-mudar"><?= $plano ? 'Mudar de Plano' : 'Assinar um Plano' ?></a>
                        <a href="./logout.php" class="botao botao-sair">Sair da Conta</a>
                    </div>
                </article>

                <article class="cartao cartao-perfil">
                    <h2 class="cartao-titulo">Compras Recentes</h2>
                    <p class="cartao-subtitulo">Suas últimas 5 compras</p>

                    <?php if (!$compras): ?>
                        <p class="detalhe-plano">Você ainda não realizou compras.</p>
                    <?php else: ?>
                        <ul class="lista-beneficios-perfil">
                            <?php foreach ($compras as $c): ?>
                                <li>
                                    <i class="fa-solid fa-ticket icone-sim"></i>
                                    <a href="./confirmacao.php?id=<?= (int)$c['id_venda'] ?>">Pedido #<?= (int)$c['id_venda'] ?></a>
                                    — <?= h(date('d/m/Y H:i', strtotime($c['data_pedido']))) ?>
                                    · <?= h(lx_brl($c['valor_pedido'])) ?>
                                    · <?= h($c['forma_pagamento'] ?? '-') ?> (<?= h($c['status_pagamento'] ?? 'Pendente') ?>)
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>
                </article>

            </div>

        </section>

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
                <a href="./inicio.php">Filmes em Cartaz</a>
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
