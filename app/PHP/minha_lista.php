<?php

session_start();

require_once "./crud.php";

// 1. Garante que o usuário está logado
if (empty($_SESSION['id_usuario'])) {
    header('Location: login.php');
    exit;
}
$uid = (int) $_SESSION['id_usuario'];

// Busca a lista do usuário (ou cria uma se ainda não existir)
$st = $pdo->prepare('SELECT id_lista FROM lista WHERE id_usuario = ?');
$st->execute([$uid]);
$lista = $st->fetchColumn();

if (!$lista) {
    $pdo->prepare('INSERT INTO lista (id_usuario) VALUES (?)')->execute([$uid]);
    $lista = $pdo->lastInsertId();
}

// 2. Ações via POST (Salvar/Favoritar, Remover, Assistido, Esvaziar)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $acao  = $_POST['acao'] ?? '';
    $filme = (int) ($_POST['id_filme'] ?? 0);

    // Favoritar / Desfavoritar (evita duplicidade)
    if ($acao === 'favoritar' && $filme > 0) {
        $existe = $pdo->prepare('SELECT 1 FROM filme_lista WHERE id_lista = ? AND id_filme = ?');
        $existe->execute([$lista, $filme]);

        if ($existe->fetchColumn()) {
            $pdo->prepare('DELETE FROM filme_lista WHERE id_lista = ? AND id_filme = ?')->execute([$lista, $filme]);
        } else {
            $pdo->prepare('INSERT INTO filme_lista (id_lista, id_filme, data_adicao) VALUES (?, ?, NOW())')->execute([$lista, $filme]);
        }
    }

    if ($acao === 'remover') {
        $pdo->prepare('DELETE FROM filme_lista WHERE id_lista = ? AND id_filme = ?')->execute([$lista, $filme]);
    }

    if ($acao === 'assistido') {
        $pdo->prepare('UPDATE filme_lista SET assistido = NOT assistido WHERE id_lista = ? AND id_filme = ?')->execute([$lista, $filme]);
    }

    if ($acao === 'esvaziar_tudo') {
        $pdo->prepare('DELETE FROM filme_lista WHERE id_lista = ?')->execute([$lista]);
    }

    if ($acao === 'esvaziar_assistidos') {
        $pdo->prepare('DELETE FROM filme_lista WHERE id_lista = ? AND assistido = 1')->execute([$lista]);
    }
}

// 3. Filtros e Ordenação (GET)
$cat   = $_GET['cat'] ?? 'todos';
$ordem = $_GET['ordem'] ?? 'data';

$filtros = [
    'filme'     => "AND f.tipo = 'Filme'",
    'serie'     => "AND f.tipo = 'Série'",
    'continuar' => "AND fl.assistido = 0 AND fl.progresso > 0",
];
$where = $filtros[$cat] ?? '';

$ordens = [
    'nota' => 'f.nota DESC',
    'az'   => 'f.titulo ASC',
];
$orderBy = $ordens[$ordem] ?? 'fl.data_adicao DESC';

// 4. Consulta os itens da lista + verifica se está "Em Cartaz" (sessao ativa futura)
$sqlItens = "
    SELECT f.*, fl.assistido,
           EXISTS(
               SELECT 1 FROM sessao s 
               WHERE s.id_filme = f.id_filme AND s.ativa = 1 AND s.data_hora >= NOW()
           ) AS em_cartaz
      FROM filme_lista fl
      JOIN filme f ON f.id_filme = fl.id_filme
     WHERE fl.id_lista = ? $where
     ORDER BY $orderBy
";
$st = $pdo->prepare($sqlItens);
$st->execute([$lista]);
$itens = $st->fetchAll();

// 5. Recomendações ("Você também pode gostar de:") com base nos gêneros da lista
$sqlSugestoes = "
    SELECT DISTINCT f.*,
           EXISTS(
               SELECT 1 FROM sessao s 
               WHERE s.id_filme = f.id_filme AND s.ativa = 1 AND s.data_hora >= NOW()
           ) AS em_cartaz
      FROM filme f
      JOIN filme_genero fg ON fg.id_filme = f.id_filme
     WHERE fg.id_genero IN (
               SELECT fg2.id_genero 
                 FROM filme_lista fl2 
                 JOIN filme_genero fg2 ON fg2.id_filme = fl2.id_filme 
                WHERE fl2.id_lista = ?
           )
       AND f.id_filme NOT IN (SELECT id_filme FROM filme_lista WHERE id_lista = ?)
     ORDER BY em_cartaz DESC, f.nota DESC
     LIMIT 6
";
$st = $pdo->prepare($sqlSugestoes);
$st->execute([$lista, $lista]);
$sugestoes = $st->fetchAll();

?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>CINE - LUX</title>
  <link rel="stylesheet" href="./CSS/minha_lista.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link rel="shortcut icon" href="./img/Logo - LUX.png" type="image/x-icon">
</head>

<body>

  <header class="cabecalho">
    <a href="./inicio.html" class="logo">
      <img src="./img/Logo - LUX.png" alt="Logo LUX Cinema" class="logo-img">
    </a>

    <nav class="navegacao-menu">
      <a href="./inicio.html" class="navegacao-ativa">Lançamentos</a>
      <a href="./recomendados.html" class="navegacao-opcoes">Recomendados</a>
      <a href="./combos.html" class="navegacao-opcoes">Combos</a>
      <a href="./infantil.html" class="navegacao-opcoes">Infantil</a>
    </nav>

    <div class="busca">
      <input type="text" placeholder="Buscar filmes, gêneros, cinemas...">
      <i class="fa-solid fa-magnifying-glass busca-icon"></i>
    </div>

    <div class="acoes-do-usuario">
      <a href="carrinho.html" class="link"><i class="fa-solid fa-cart-shopping"></i></a>
      <a href="./minha_lista.html" class="link"><i class="fa-solid fa-bookmark"></i></a>
      <a href="./perfil.html" class="link"><i class="fa-solid fa-user"></i></a>
    </div>
  </header>

<main class="pagina">
  <section class="cabecalho-lista">
    <h1>Minha Lista</h1>

    <?php if ($itens || $cat !== ''): ?>
    <details class="confirmar">
      <summary class="btn btn-contorno">Esvaziar lista</summary>
      <div class="confirmar-caixa">
        <form method="post">
          <button class="btn btn-laranja" name="acao" value="limpar">Remover todos</button>
          <button class="btn btn-amarelo" name="acao" value="limpar_ok">
            Só os assistidos
          </button>
        </form>
      </div>
    </details>
    <?php endif; ?>
  </section>

  <section class="barra">
    <nav class="abas">
      <?php foreach (['' => 'Todos', 'filme' => 'Filmes', 'serie' => 'Séries', 'continuar' => 'Continuar'] as $k => $nome): ?>
        <a class="chip <?= $cat === $k ? 'on' : '' ?>" href="minha_lista.php?cat=<?= $k ?>&ordem=<?= htmlspecialchars($ordem) ?>">
          <?= $nome ?>
        </a>
      <?php endforeach; ?>
    </nav>
    <nav class="ordem">
      <span>Ordenar:</span>
      <?php foreach (['' => 'Data de adição', 'nota' => 'Nota', 'az' => 'A–Z'] as $k => $nome): ?>
        <a class="<?= $ordem === $k ? 'on' : '' ?>" href="minha_lista.php?cat=<?= htmlspecialchars($cat) ?>&ordem=<?= $k ?>">
          <?= $nome ?>
        </a>
      <?php endforeach; ?>
    </nav>
  </section>

  <?php if (!$itens): ?>
    <section class="vazio">
      <h2>Nada por aqui</h2>
      <p>Salve filmes e séries do catálogo para vê-los nesta lista.</p>
      <a class="btn btn-amarelo" href="index.php">Explorar catálogo</a>
    </section>
  <?php else: ?>
  <section class="grade">
    <?php foreach ($itens as $f): ?>
    <article class="card <?= $f['assistido'] ? 'assistido' : '' ?>">
      <a class="capa" href="filme.php?id=<?= (int)$f['id_filme'] ?>">
        <img src="<?= htmlspecialchars($f['poster'] ?: 'img/sem_poster.png') ?>" alt="<?= htmlspecialchars($f['titulo']) ?>">
        <?php if ($f['em_cartaz'] > 0): ?><span class="selo">Em Cartaz</span><?php endif; ?>
        <?php if ($f['assistido']): ?><span class="selo selo-visto">Assistido</span><?php endif; ?>
      </a>
      <div class="info">
        <h3><a href="filme.php?id=<?= (int)$f['id_filme'] ?>"><?= htmlspecialchars($f['titulo']) ?></a></h3>
        <p class="meta">
          <span class="estrela">★ <?= number_format((float)$f['nota'], 1, ',', '') ?></span>
          <span><?= htmlspecialchars($f['tipo']) ?></span>
        </p>

        <?php if (!empty($f['data_adicao'])): ?>
          <p class="data">Adicionado em <?= date('d/m/Y', strtotime($f['data_adicao'])) ?></p>
        <?php endif; ?>

        <?php if ($f['em_cartaz'] > 0): ?>
          <a class="btn btn-laranja btn-bloco" href="comprar.php?id_filme=<?= (int)$f['id_filme'] ?>">Comprar ingresso</a>
        <?php endif; ?>

        <div class="linha-btn">
          <a class="btn btn-contorno" href="filme.php?id=<?= (int)$f['id_filme'] ?>">Ver detalhes</a>
          <form method="post">
            <input type="hidden" name="id_filme" value="<?= (int)$f['id_filme'] ?>">
            <button class="btn btn-mini" name="acao" value="assistido" title="Marcar/desmarcar como assistido">✓</button>
            <button class="btn btn-mini btn-remover" name="acao" value="remover" title="Remover da lista">✕</button>
          </form>
        </div>
      </div>
    </article>
    <?php endforeach; ?>
  </section>
  <?php endif; ?>

  <?php if ($sugestoes): ?>
  <section class="sugestoes">
    <h2>Você também pode gostar de:</h2>
    <p class="sub">Com base nos gêneros dos títulos salvos na sua lista.</p>
    <div class="grade grade-peq">
      <?php foreach ($sugestoes as $s): ?>
      <article class="card card-peq">
        <a class="capa" href="filme.php?id=<?= (int)$s['id_filme'] ?>">
          <img src="<?= htmlspecialchars($s['poster'] ?: 'img/sem_poster.png') ?>" alt="<?= htmlspecialchars($s['titulo']) ?>">
          <?php if ($s['em_cartaz'] > 0): ?><span class="selo">Em Cartaz</span><?php endif; ?>
        </a>
        <div class="info">
          <h3><a href="filme.php?id=<?= (int)$s['id_filme'] ?>"><?= htmlspecialchars($s['titulo']) ?></a></h3>
          <p class="meta">
            <span class="estrela">★ <?= number_format((float)$s['nota'], 1, ',', '') ?></span>
            <span><?= htmlspecialchars($s['tipo']) ?></span>
          </p>
          <?php if ($s['em_cartaz'] > 0): ?>
            <a class="btn btn-laranja btn-bloco" href="comprar.php?id_filme=<?= (int)$s['id_filme'] ?>">Comprar ingresso</a>
          <?php endif; ?>
          <form class="form-fav" method="post" action="minha_lista.php">
            <input type="hidden" name="id_filme" value="<?= (int)$s['id_filme'] ?>">
            <input type="hidden" name="acao" value="favoritar">
            <button type="submit" class="btn-fav">Salvar na lista</button>
          </form>
        </div>
      </article>
      <?php endforeach; ?>
    </div>
  </section>
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
        <a href="./combos.html">Bomboniere & Combos</a>
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