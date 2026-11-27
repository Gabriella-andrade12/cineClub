<?php
require_once "../init.php";   // fornece a conexão PDO ($pdo), crud() e inicia a sessão

if (session_status() === PHP_SESSION_NONE) { session_start(); }
if (empty($_SESSION['id_usuario'])) { header('Location: login.php'); exit; }

/* ---------------- Helpers ---------------- */
if (!function_exists('h')) {
    function h($s) { return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8'); }
}
function lx_preco($v) { return 'R$' . number_format((float)$v, 2, ',', '.'); }
function lx_url($params) {
    $p = array_filter($params, function ($v) { return $v !== null && $v !== '' && $v !== false && $v !== 0; });
    return 'bomboniere.php' . ($p ? '?' . http_build_query($p) : '');
}
/* Imagem do produto: procura ./img/Combos/1/<nome>.(png|jpg|jpeg|webp), com e sem o prefixo "Combo " */
function lx_imagem($nome) {
    $variantes = [$nome, preg_replace('/^Combo\s+/iu', '', $nome)];
    foreach ($variantes as $v) {
        foreach (['png', 'jpg', 'jpeg', 'webp'] as $ext) {
            if (is_file(__DIR__ . '/img/Combos/1/' . $v . '.' . $ext)) return './img/Combos/1/' . $v . '.' . $ext;
        }
    }
    return './img/Logo - LUX.png';
}

/* ---------------- Carrinho: "Garantir o meu" ----------------
 * Guarda em $_SESSION['carrinho']['produtos'] (a gravação no banco só ocorre no pagamento). */
if (!isset($_SESSION['carrinho']) || !is_array($_SESSION['carrinho'])) { $_SESSION['carrinho'] = []; }
$_SESSION['carrinho'] += ['ingressos' => [], 'produtos' => [], 'cupom' => null, 'id_endereco' => null];

$categoria = trim($_REQUEST['categoria'] ?? '');
$detalhe   = (int)($_REQUEST['detalhe'] ?? 0);
$todos     = !empty($_REQUEST['todos']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $idp = (int)($_POST['id_produto'] ?? 0);
    $p = read($pdo, 'bomboniere', 'id_produto = ?', [$idp]);
    if ($p) {
        if (isset($_SESSION['carrinho']['produtos'][$idp])) { $_SESSION['carrinho']['produtos'][$idp]['qtd']++; }
        else { $_SESSION['carrinho']['produtos'][$idp] = ['nome' => $p['nome_produto'], 'valor' => (float)$p['valor_produto'], 'qtd' => 1]; }
        $_SESSION['msg_bomboniere'] = $p['nome_produto'] . ' adicionado ao carrinho.';
    }
    header('Location: ' . lx_url(['categoria' => $categoria, 'detalhe' => $detalhe, 'todos' => $todos ? 1 : null]));
    exit;
}
$msg = $_SESSION['msg_bomboniere'] ?? '';
unset($_SESSION['msg_bomboniere']);

/* ---------------- Categorias (dinâmicas, na ordem do cardápio) ---------------- */
$ordem_cat = ['Individual', 'Família', 'Casal', 'Infantil', 'Especiais', 'Premium', 'Bebidas', 'Doces'];
$no_banco  = $pdo->query("SELECT DISTINCT categoria_produto FROM bomboniere WHERE categoria_produto IS NOT NULL AND categoria_produto <> ''")->fetchAll(PDO::FETCH_COLUMN);
$categorias = array_merge(array_values(array_intersect($ordem_cat, $no_banco)), array_values(array_diff($no_banco, $ordem_cat)));
if (!in_array($categoria, $categorias, true)) { $categoria = ''; }

/* ---------------- Produtos (filtro por categoria) ---------------- */
if ($categoria !== '') {
    $st = $pdo->prepare("SELECT * FROM bomboniere WHERE categoria_produto = ? ORDER BY nome_produto");
    $st->execute([$categoria]);
    $produtos = $st->fetchAll();
} else {
    $produtos = $pdo->query("SELECT * FROM bomboniere ORDER BY categoria_produto, nome_produto")->fetchAll();
}
$limite  = 6;                                  // "ver mais" sem JavaScript: ?todos=1
$total_p = count($produtos);
$exibidos = $todos ? $produtos : array_slice($produtos, 0, $limite);

/* ---------------- Destaque: mais vendido (item_venda) ou o produto de ?detalhe= ---------------- */
$mais_vendido = $pdo->query("SELECT id_produto FROM item_venda GROUP BY id_produto ORDER BY SUM(quantidade) DESC LIMIT 1")->fetch();
$id_mais_vendido = $mais_vendido ? (int)$mais_vendido['id_produto'] : 0;

$destaque = null;
if ($detalhe > 0) {
    $destaque = read($pdo, 'bomboniere', 'id_produto = ?', [$detalhe]) ?: null;
}
if (!$destaque && $id_mais_vendido) {
    $destaque = read($pdo, 'bomboniere', 'id_produto = ?', [$id_mais_vendido]) ?: null;
}
if (!$destaque && $produtos) { $destaque = $produtos[0]; }

$aval = null;
if ($destaque) {
    $st = $pdo->prepare("SELECT AVG(nota_avl) AS m, COUNT(*) AS n FROM avaliacao WHERE id_produto = ?");
    $st->execute([(int)$destaque['id_produto']]);
    $a = $st->fetch();
    if ($a && (int)$a['n'] > 0) { $aval = $a; }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>CINELUX | Bomboniere</title>
  <link rel="stylesheet" href="./CSS/bomboniere.css">
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
      <a href="./bomboniere.php" class="navegacao-ativa">Combos</a>
      <a href="./infantil.php" class="navegacao-opcoes">Infantil</a>
    </nav>

    <div class="busca">
      <input type="text" placeholder="Buscar filmes, gêneros, cinemas...">
      <i class="fa-solid fa-magnifying-glass busca-icon"></i>
    </div>

    <div class="acoes-do-usuario">
      <a href="./carrinho.php" class="link"><i class="fa-solid fa-cart-shopping"></i></a>
      <a href="./minha_lista.php" class="link"><i class="fa-solid fa-bookmark"></i></a>
      <a href="./perfil.php" class="link"><i class="fa-solid fa-user"></i></a>
    </div>
  </header>
  <section class="localizacao">
    <div class="selecao">
      <span>Sua Localização:</span>
      <select>
        <option>São Paulo, SP</option>
        <option>Rio de Janeiro, RJ</option>
      </select>
    </div>
    <div class="selecao">
      <span>Cinema Preferencial:</span>
      <select>
        <option>Selecione o cinema preferencial...</option>
      </select>
    </div>
  </section>

  <main class="faixa-principal">

    <?php if ($msg !== ''): ?>
      <div role="status" style="margin:0 0 14px;padding:10px 14px;border-radius:10px;background:#1f3d2e;border:1px solid #2f7a52;">
        <?= h($msg) ?> <a href="./carrinho.php" style="text-decoration:underline;font-weight:700;">Ver carrinho</a>
      </div>
    <?php endif; ?>

    <?php if ($destaque): ?>
    <section class="combo-destaque-bloco" id="destaque">
      <div class="combo-destaque-informacoes">
        <h1 class="combo-destaque-titulo"><?= $destaque['nome_produto'] ?> <span class="combo-destaque-subtitulo"><?= h($destaque['categoria_produto']) ?></span></h1>
        <p class="combo-destaque-descricao"><?= h($destaque['descricao_produto']) ?></p>

        <div class="combo-destaque-precos">
          <span class="preco-atual"><?= h(lx_preco($destaque['valor_produto'])) ?></span>
        </div>

        <div class="combo-destaque-botoes">
          <form method="post" action="bomboniere.php" style="display:contents">
            <input type="hidden" name="id_produto" value="<?= (int)$destaque['id_produto'] ?>">
            <input type="hidden" name="categoria" value="<?= h($categoria) ?>">
            <input type="hidden" name="detalhe" value="<?= (int)$destaque['id_produto'] ?>">
            <button type="submit" class="botao-garantir"><i class="fa-solid fa-cart-shopping"></i> Garantir o meu</button>
          </form>
          <a href="#cardapio" class="botao-detalhes-link">Ver cardápio</a>
        </div>
      </div>

      <div class="combo-destaque-imagem-faixa">
        <div class="combo-destaque-informacoes-superiores">
          <span class="informacao-item"><i class="fa-solid fa-tag"></i> <?= h($destaque['categoria_produto']) ?></span>
          <?php if ($aval): ?>
            <span class="informacao-item avaliacao"><i class="fa-solid fa-star"></i> <?= h(number_format((float)$aval['m'], 1, '.', '')) ?> (<?= (int)$aval['n'] ?> avaliaç<?= (int)$aval['n'] === 1 ? 'ão' : 'ões' ?>)</span>
          <?php endif; ?>
        </div>
        <img src="<?= h(lx_imagem($destaque['nome_produto'])) ?>" alt="<?= h($destaque['nome_produto']) ?>"
          class="combo-destaque-imagem">
      </div>
    </section>
    <?php endif; ?>

    <!-- Filtro por categoria: clicar na categoria ativa remove o filtro -->
    <section class="selecao-categorias" id="cardapio">
      <a href="<?= h(lx_url([])) ?>">
        <button type="button" class="categoria-botao<?= $categoria === '' ? ' categoria-ativa' : '' ?>">Cardápio Completo</button>
      </a>
      <?php foreach ($categorias as $c): $ativa = ($c === $categoria); ?>
        <a href="<?= h(lx_url(['categoria' => $ativa ? null : $c])) ?>">
          <button type="button" class="categoria-botao<?= $ativa ? ' categoria-ativa' : '' ?>"><?= h($c) ?></button>
        </a>
      <?php endforeach; ?>
    </section>

    <div class="subtitulo-secao">
      <p>Escolha o combo perfeito para acompanhar o seu filme favorito</p>
    </div>

    <section class="grade-de-combos-catalogo">
      <?php if (!$exibidos): ?>
        <p>Nenhum produto encontrado.</p>
      <?php endif; ?>

      <?php foreach ($exibidos as $p):
        $mais = ((int)$p['id_produto'] === $id_mais_vendido);
      ?>
      <div class="cartao-combo-catalogo">
        <div class="combo-imagem-faixa">
          <span class="tag-destaque-combo"><?= $mais ? 'Mais Vendido' : h($p['categoria_produto']) ?></span>
          <img src="<?= h(lx_imagem($p['nome_produto'])) ?>" alt="<?= h($p['nome_produto']) ?>">
        </div>
        <div class="cartao-conteudo">
          <h3><?= h($p['nome_produto']) ?></h3>
          <p class="combo-subtexto"><?= nl2br(h($p['descricao_produto'])) ?></p>
          <div class="combo-rodape-cartao">
            <div class="cartao-valores-combo">
              <span class="preco-atual-cartao"><?= h(lx_preco($p['valor_produto'])) ?></span>
            </div>
            <a href="<?= h(lx_url(['categoria' => $categoria, 'detalhe' => (int)$p['id_produto'], 'todos' => $todos ? 1 : null])) ?>#destaque" class="link-ver-detalhes">Ver Detalhes</a>
          </div>
          <form method="post" action="bomboniere.php" style="display:contents">
            <input type="hidden" name="id_produto" value="<?= (int)$p['id_produto'] ?>">
            <input type="hidden" name="categoria" value="<?= h($categoria) ?>">
            <input type="hidden" name="todos" value="<?= $todos ? 1 : '' ?>">
            <button type="submit" class="botao-garantir-cartao"><i class="fa-solid fa-cart-shopping"></i> Garantir o Meu</button>
          </form>
        </div>
      </div>
      <?php endforeach; ?>
    </section>

    <?php if ($total_p > $limite): ?>
    <div class="expansao-de-combos">
      <a href="<?= h(lx_url(['categoria' => $categoria, 'todos' => $todos ? null : 1])) ?>#cardapio">
        <button type="button" class="botao-expansao" title="<?= $todos ? 'Ver menos combos' : 'Ver mais combos' ?>">
          <i class="fa-solid fa-chevron-<?= $todos ? 'up' : 'down' ?>"></i>
        </button>
      </a>
    </div>
    <?php endif; ?>


    <section class="faixa-de-informacoes">
      <div class="informacoes">
        <span class="faixa-titulo">CINELUX EXPERIENCE</span>
        <h2>Sua plataforma completa de cinema</h2>
        <p>Sua plataforma completa para acompanhar lançamentos de cinema, garantir ingressos sem filas, consultar
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
          <a href="tiktok.php"><i class="fa-brands fa-tiktok"></i></a>
          <a href="instagram.php"><i class="fa-brands fa-instagram"></i></a>
          <a href="facebook.php"><i class="fa-brands fa-facebook"></i></a>
        </div>
      </div>
    </div>
  </footer>

</body>

</html>