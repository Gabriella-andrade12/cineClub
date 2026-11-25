<?php
require_once "../init.php";   // fornece a conexão PDO ($pdo), crud() e inicia a sessão

if (session_status() === PHP_SESSION_NONE) { session_start(); }
if (empty($_SESSION['id_usuario'])) { header('Location: login.php'); exit; }
$uid = (int)$_SESSION['id_usuario'];

/* ---------------- Helpers ---------------- */
if (!function_exists('h')) {
    function h($s) { return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8'); }
}
function lx_nota($v) { return number_format((float)$v, 1, ',', ''); }

/* ---------------- "Minha Lista" = lista privada do usuário ----------------
 * Usa as tabelas lista + filme_lista. Criada automaticamente no primeiro acesso. */
$lista = read($pdo, 'lista', "id_usuario = ? AND nome_lista = 'Minha Lista'", [$uid]);
if ($lista) {
    $id_lista = (int)$lista['id_lista'];
} else {
    $st = $pdo->prepare(
        "INSERT INTO lista (nome_lista, descricao_lista, data_criacao, id_usuario, exib_lista) VALUES ('Minha Lista', 'Títulos salvos para assistir depois', CURDATE(), ?, 'Privada')"
    );
    $st->execute([$uid]);
    $id_lista = (int)$pdo->lastInsertId();
}

/* Data de adição é opcional: só é usada se filme_lista tiver a coluna data_adicao
 * (ALTER TABLE filme_lista ADD COLUMN data_adicao TIMESTAMP DEFAULT CURRENT_TIMESTAMP;) */
$tem_data = (bool)$pdo->query("SHOW COLUMNS FROM filme_lista LIKE 'data_adicao'")->fetchAll();

/* ---------------- Filtros e ordenação (via URL) ---------------- */
$filtros = ['todos' => 'Todos', 'filmes' => 'Filmes', 'series' => 'Séries', 'continuar' => 'Continuar'];
$ordens  = ['data' => 'Data de adição', 'nota' => 'Nota', 'az' => 'A–Z'];
$filtro = $_REQUEST['filtro'] ?? 'todos'; if (!isset($filtros[$filtro])) $filtro = 'todos';
$ordem  = $_REQUEST['ordem']  ?? 'data';  if (!isset($ordens[$ordem]))   $ordem  = 'data';
function lx_url($filtro, $ordem) { return 'minha_lista.php?' . http_build_query(['filtro' => $filtro, 'ordem' => $ordem]); }

/* ---------------- Ações (POST, sem JavaScript) ---------------- */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $acao = $_POST['acao'] ?? '';
    $idf  = (int)($_POST['id_filme'] ?? 0);

    if ($acao === 'salvar') {
        $existe = read($pdo, 'filme', 'id_filme = ?', [$idf]);
        $ja     = read($pdo, 'filme_lista', 'id_lista = ? AND id_filme = ?', [$id_lista, $idf]);
        if ($existe && !$ja) {
            create($pdo, 'filme_lista', ['id_filme' => $idf, 'id_lista' => $id_lista]);
        }

    } elseif ($acao === 'remover') {
        delete($pdo, 'filme_lista', 'id_lista = ? AND id_filme = ?', [$id_lista, $idf]);

    } elseif ($acao === 'assistido') {          // marcar como assistido = registrar no histórico
        $ja = read($pdo, 'historico_visualizacao', 'id_usuario = ? AND id_filme = ?', [$uid, $idf]);
        if (!$ja) {
            $st = $pdo->prepare("INSERT INTO historico_visualizacao (data_visualizacao, id_usuario, id_filme) VALUES (NOW(), ?, ?)");
            $st->execute([$uid, $idf]);
        }

    } elseif ($acao === 'desmarcar') {
        delete($pdo, 'historico_visualizacao', 'id_usuario = ? AND id_filme = ?', [$uid, $idf]);

    } elseif ($acao === 'esvaziar_todos') {
        delete($pdo, 'filme_lista', 'id_lista = ?', [$id_lista]);

    } elseif ($acao === 'esvaziar_assistidos') {
        delete(
            $pdo,
            'filme_lista',
            'id_lista = ? AND id_filme IN (SELECT id_filme FROM historico_visualizacao WHERE id_usuario = ?)',
            [$id_lista, $uid]
        );
    }
    header('Location: ' . lx_url($filtro, $ordem));   // PRG: evita reenvio ao recarregar
    exit;
}

/* ---------------- Títulos salvos ---------------- */
$sql = "SELECT f.id_filme, f.nome_filme, f.imagem_capa, f.class_filme, f.gen_filme, f.exibicao_filme, f.tipo_conteudo,
               (SELECT AVG(a.nota_avl) FROM avaliacao a WHERE a.id_filme = f.id_filme) AS media,
               EXISTS(SELECT 1 FROM historico_visualizacao h WHERE h.id_usuario = ? AND h.id_filme = f.id_filme) AS assistido"
     . ($tem_data ? ", fl.data_adicao" : "")
     . " FROM filme_lista fl JOIN filme f ON f.id_filme = fl.id_filme WHERE fl.id_lista = ?";
$params = [$uid, $id_lista];
if ($filtro === 'filmes')    { $sql .= " AND f.tipo_conteudo = 'Filme'"; }
if ($filtro === 'series')    { $sql .= " AND f.tipo_conteudo = 'Serie'"; }
if ($filtro === 'continuar') {   // streaming ainda não assistido
    $sql .= " AND f.exibicao_filme = 'Streaming' AND NOT EXISTS(SELECT 1 FROM historico_visualizacao h2 WHERE h2.id_usuario = ? AND h2.id_filme = f.id_filme)";
    $params[] = $uid;
}
$sql .= ($ordem === 'nota') ? " ORDER BY media IS NULL, media DESC, f.nome_filme"
      : (($ordem === 'az')  ? " ORDER BY f.nome_filme"
      : ($tem_data ? " ORDER BY fl.data_adicao DESC, f.nome_filme" : " ORDER BY f.id_filme DESC"));
$st = $pdo->prepare($sql);
$st->execute($params);
$titulos = $st->fetchAll();

$st = $pdo->prepare("SELECT COUNT(*) FROM filme_lista WHERE id_lista = ?");
$st->execute([$id_lista]);
$total_lista = (int)$st->fetchColumn();

// Gêneros predominantes da lista
$st = $pdo->prepare(
    "SELECT f.gen_filme AS genero, COUNT(*) AS qtd FROM filme_lista fl JOIN filme f ON f.id_filme = fl.id_filme
      WHERE fl.id_lista = ? AND f.gen_filme IS NOT NULL AND f.gen_filme <> ''
      GROUP BY f.gen_filme ORDER BY qtd DESC, f.gen_filme LIMIT 3"
);
$st->execute([$id_lista]);
$favoritos = $st->fetchAll();

/* ---------------- Sugestões: mesmos gêneros, ainda fora da lista ---------------- */
$sug_base = "SELECT f.id_filme, f.nome_filme, f.imagem_capa, f.exibicao_filme, f.tipo_conteudo,
                    (SELECT AVG(a.nota_avl) FROM avaliacao a WHERE a.id_filme = f.id_filme) AS media
               FROM filme f
              WHERE f.id_filme NOT IN (SELECT id_filme FROM filme_lista WHERE id_lista = ?)";
if ($favoritos) {
    $gens = array_column($favoritos, 'genero');
    $marc = implode(',', array_fill(0, count($gens), '?'));
    $st = $pdo->prepare($sug_base . " AND f.gen_filme IN ($marc) ORDER BY media IS NULL, media DESC, f.nome_filme LIMIT 4");
    $st->execute(array_merge([$id_lista], $gens));
    $sugestoes = $st->fetchAll();
    $sug_texto = 'Com base em ' . implode(', ', $gens) . '.';
} else {
    $st = $pdo->prepare($sug_base . " ORDER BY media IS NULL, media DESC, f.nome_filme LIMIT 4");
    $st->execute([$id_lista]);
    $sugestoes = $st->fetchAll();
    $sug_texto = 'Os títulos mais bem avaliados da LUX.';
}

function lx_capa($f) { return !empty($f['imagem_capa']) ? $f['imagem_capa'] : './img/Logo - LUX.png'; }
function lx_classif($c) { $n = preg_replace('/\D/', '', (string)$c); return $n !== '' ? $n : (string)$c; }
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
    <a href="./inicio.php" class="logo">
      <img src="./img/Logo - LUX.png" alt="Logo LUX Cinema" class="logo-img">
    </a>

    <nav class="navegacao-menu">
      <a href="./inicio.php" class="navegacao-ativa">Lançamentos</a>
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
      <a href="./minha_lista.php" class="link"><i class="fa-solid fa-bookmark"></i></a>
      <a href="./perfil.php" class="link"><i class="fa-solid fa-user"></i></a>
    </div>
  </header>

  <main class="principal">

    <!-- Título e botão de esvaziar -->
    <section class="lista-topo">
      <div>
        <h1 class="lista-titulo">Minha Lista</h1>
        <p class="lista-subtitulo">Os filmes e séries que você salvou para assistir depois.</p>
      </div>

      <?php if ($total_lista > 0): ?>
      <details class="esvaziar">
        <summary class="botao botao-contorno"><i class="fa-solid fa-trash"></i> Esvaziar lista</summary>
        <div class="esvaziar-caixa">
          <p>O que você quer remover?</p>
          <form method="post" action="minha_lista.php" style="display:contents">
            <input type="hidden" name="filtro" value="<?= h($filtro) ?>">
            <input type="hidden" name="ordem" value="<?= h($ordem) ?>">
            <button type="submit" name="acao" value="esvaziar_todos" class="botao botao-laranja">Todos os títulos</button>
            <button type="submit" name="acao" value="esvaziar_assistidos" class="botao botao-amarelo">Só os assistidos</button>
          </form>
        </div>
      </details>
      <?php endif; ?>
    </section>

    <?php if ($favoritos): ?>
    <!-- Gêneros predominantes -->
    <section class="generos-favoritos">
      <span class="generos-rotulo">Seus gêneros favoritos:</span>
      <?php foreach ($favoritos as $g): ?>
        <span class="genero-tag"><?= h($g['genero']) ?> <b><?= (int)$g['qtd'] ?></b></span>
      <?php endforeach; ?>
    </section>
    <?php endif; ?>

    <!-- Filtros e ordenação -->
    <section class="barra-filtros">
      <nav class="filtro-categorias">
        <?php foreach ($filtros as $chave => $rotulo): ?>
          <a href="<?= h(lx_url($chave, $ordem)) ?>" class="filtro-chip<?= $chave === $filtro ? ' filtro-ativo' : '' ?>"><?= h($rotulo) ?></a>
        <?php endforeach; ?>
      </nav>

      <nav class="filtro-ordem">
        <span>Ordenar por:</span>
        <?php foreach ($ordens as $chave => $rotulo): ?>
          <a href="<?= h(lx_url($filtro, $chave)) ?>"<?= $chave === $ordem ? ' class="ordem-ativa"' : '' ?>><?= h($rotulo) ?></a>
        <?php endforeach; ?>
      </nav>
    </section>

    <?php if ($titulos): ?>
    <!-- Títulos salvos -->
    <section class="grade-titulos">

      <?php foreach ($titulos as $f):
        $assistido = (bool)$f['assistido'];
        $cartaz    = ($f['exibicao_filme'] === 'Cinema');
        $url       = 'detalhes_filme.php?id=' . (int)$f['id_filme'];
      ?>
      <article class="cartao<?= $assistido ? ' cartao-assistido' : '' ?>">
        <a href="<?= h($url) ?>" class="cartao-capa">
          <img src="<?= h(lx_capa($f)) ?>" alt="Pôster de <?= h($f['nome_filme']) ?>">
          <?php if ($assistido): ?><span class="selo selo-assistido">Assistido</span>
          <?php elseif ($cartaz): ?><span class="selo selo-cartaz">Em Cartaz</span><?php endif; ?>
        </a>
        <div class="cartao-info">
          <h3 class="cartao-titulo"><a href="<?= h($url) ?>"><?= h($f['nome_filme']) ?></a></h3>
          <p class="cartao-meta">
            <?php if ($f['media'] !== null): ?><span class="nota"><i class="fa-solid fa-star"></i> <?= h(lx_nota($f['media'])) ?></span><?php endif; ?>
            <span><?= $f['tipo_conteudo'] === 'Serie' ? 'Série' : 'Filme' ?></span>
            <?php if (lx_classif($f['class_filme']) !== ''): ?><span class="classificacao"><?= h(lx_classif($f['class_filme'])) ?></span><?php endif; ?>
          </p>
          <?php if ($tem_data && !empty($f['data_adicao'])): ?>
            <p class="cartao-data">Adicionado em <?= h(date('d/m/Y', strtotime($f['data_adicao']))) ?></p>
          <?php endif; ?>
          <?php if ($cartaz): ?>
            <a href="ingressos.php?id_filme=<?= (int)$f['id_filme'] ?>" class="botao botao-laranja botao-bloco"><i class="fa-solid fa-ticket"></i> Comprar ingresso</a>
          <?php endif; ?>
          <div class="cartao-acoes">
            <a href="<?= h($url) ?>" class="botao botao-contorno">Ver detalhes</a>
            <form method="post" action="minha_lista.php" style="display:contents">
              <input type="hidden" name="id_filme" value="<?= (int)$f['id_filme'] ?>">
              <input type="hidden" name="filtro" value="<?= h($filtro) ?>">
              <input type="hidden" name="ordem" value="<?= h($ordem) ?>">
              <?php if ($assistido): ?>
                <button type="submit" name="acao" value="desmarcar" class="botao-icone" title="Desmarcar como assistido"><i class="fa-solid fa-rotate-left"></i></button>
              <?php else: ?>
                <button type="submit" name="acao" value="assistido" class="botao-icone" title="Marcar como assistido"><i class="fa-solid fa-check"></i></button>
              <?php endif; ?>
              <button type="submit" name="acao" value="remover" class="botao-icone botao-remover" title="Remover da lista"><i class="fa-solid fa-xmark"></i></button>
            </form>
          </div>
        </div>
      </article>
      <?php endforeach; ?>

    </section>
    <?php else: ?>
    <!-- Estado vazio -->
    <section class="lista-vazia">
      <i class="fa-regular fa-bookmark"></i>
      <?php if ($total_lista === 0): ?>
        <h2>Sua lista está vazia</h2>
        <p>Use o botão “Salvar” em qualquer filme ou série do catálogo.</p>
      <?php else: ?>
        <h2>Nenhum título encontrado</h2>
        <p>Nenhum título da sua lista corresponde a este filtro.</p>
      <?php endif; ?>
      <a href="./inicio.php" class="botao botao-amarelo">Explorar catálogo</a>
    </section>
    <?php endif; ?>

    <?php if ($sugestoes): ?>
    <!-- Você também pode gostar de -->
    <section class="sugestoes">
      <h2 class="sugestoes-titulo">Você também pode gostar de:</h2>
      <p class="sugestoes-sub"><?= h($sug_texto) ?></p>

      <div class="grade-titulos grade-pequena">
        <?php foreach ($sugestoes as $f):
          $url = 'detalhes_filme.php?id=' . (int)$f['id_filme'];
        ?>
        <article class="cartao cartao-pequeno">
          <a href="<?= h($url) ?>" class="cartao-capa">
            <img src="<?= h(lx_capa($f)) ?>" alt="Pôster de <?= h($f['nome_filme']) ?>">
            <?php if ($f['exibicao_filme'] === 'Cinema'): ?><span class="selo selo-cartaz">Em Cartaz</span><?php endif; ?>
          </a>
          <div class="cartao-info">
            <h3 class="cartao-titulo"><a href="<?= h($url) ?>"><?= h($f['nome_filme']) ?></a></h3>
            <p class="cartao-meta">
              <?php if ($f['media'] !== null): ?><span class="nota"><i class="fa-solid fa-star"></i> <?= h(lx_nota($f['media'])) ?></span><?php endif; ?>
              <span><?= $f['tipo_conteudo'] === 'Serie' ? 'Série' : 'Filme' ?></span>
            </p>
            <?php if ($f['exibicao_filme'] === 'Cinema'): ?>
              <a href="ingressos.php?id_filme=<?= (int)$f['id_filme'] ?>" class="botao botao-laranja botao-bloco">Comprar ingresso</a>
            <?php endif; ?>
            <form method="post" action="minha_lista.php" style="display:contents">
              <input type="hidden" name="id_filme" value="<?= (int)$f['id_filme'] ?>">
              <input type="hidden" name="filtro" value="<?= h($filtro) ?>">
              <input type="hidden" name="ordem" value="<?= h($ordem) ?>">
              <button type="submit" name="acao" value="salvar" class="botao-salvar"><i class="fa-regular fa-bookmark"></i> Salvar</button>
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
