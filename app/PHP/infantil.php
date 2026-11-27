<?php
require_once "../init.php";   // fornece a conexão PDO ($pdo), crud() e inicia a sessão

if (session_status() === PHP_SESSION_NONE) { session_start(); }
if (empty($_SESSION['id_usuario'])) { header('Location: login.php'); exit; }

/* ---------------- Helpers ---------------- */
if (!function_exists('h')) {
    function h($s) { return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8'); }
}
function lx_capa($f) { return !empty($f['imagem_capa']) ? $f['imagem_capa'] : './img/Logo - LUX.png'; }

/* Classificação indicativa -> idade mínima ("Livre" = 0; "7 anos" = 7; sem dado = null) */
function lx_idade($c) {
    if (preg_match('/\d+/', (string)$c, $m)) return (int)$m[0];
    if (stripos((string)$c, 'livre') !== false) return 0;
    return null;
}
/* Duração em minutos -> "1 hora e 31 minutos" */
function lx_duracao($d) {
    if (!is_numeric($d)) return (string)$d;
    $m = (int)$d; $hh = intdiv($m, 60); $mm = $m % 60; $t = [];
    if ($hh) $t[] = $hh . ' hora' . ($hh > 1 ? 's' : '');
    if ($mm) $t[] = $mm . ' minuto' . ($mm > 1 ? 's' : '');
    return implode(' e ', $t);
}
/* Data por extenso: "5 de novembro de 2026" */
function lx_data_extenso($d) {
    if (!$d) return '-';
    $meses = ['janeiro','fevereiro','março','abril','maio','junho','julho','agosto','setembro','outubro','novembro','dezembro'];
    $t = strtotime($d);
    return (int)date('j', $t) . ' de ' . $meses[(int)date('n', $t) - 1] . ' de ' . date('Y', $t);
}
/* Monta URL desta página preservando os filtros (vazios saem da URL) */
function lx_url($p) {
    $p = array_filter($p, function ($v) { return $v !== null && $v !== '' && $v !== false; });
    return 'infantil.php' . ($p ? '?' . http_build_query($p) : '');
}

/* ---------------- Carrinho: botão "Adicionar" dos combos ---------------- */
if (!isset($_SESSION['carrinho']) || !is_array($_SESSION['carrinho'])) { $_SESSION['carrinho'] = []; }
$_SESSION['carrinho'] += ['ingressos' => [], 'produtos' => [], 'cupom' => null, 'id_endereco' => null];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $idp = (int)($_POST['id_produto'] ?? 0);
    $p = read($pdo, 'bomboniere', 'id_produto = ?', [$idp]);
    if ($p) {
        if (isset($_SESSION['carrinho']['produtos'][$idp])) { $_SESSION['carrinho']['produtos'][$idp]['qtd']++; }
        else { $_SESSION['carrinho']['produtos'][$idp] = ['nome' => $p['nome_produto'], 'valor' => (float)$p['valor_produto'], 'qtd' => 1]; }
        $_SESSION['msg_infantil'] = $p['nome_produto'] . ' adicionado ao carrinho.';
    }
    header('Location: ' . lx_url(['tipo' => $_POST['tipo'] ?? '', 'idade' => $_POST['idade'] ?? '', 'busca' => $_POST['busca'] ?? '']) . '#bomboniere');
    exit;
}
$msg = $_SESSION['msg_infantil'] ?? '';
unset($_SESSION['msg_infantil']);

/* ---------------- Filtros (via URL): aba + faixa etária + busca ---------------- */
$abas = ['cartaz' => 'Filmes em Cartaz', 'populares' => 'Filmes Populares', 'series' => 'Séries'];
$tipo = $_GET['tipo'] ?? 'cartaz';  if (!isset($abas[$tipo])) $tipo = 'cartaz';

/* Página infantil: títulos até 14 anos. Faixas: "5-10 anos" = Livre a 10; "10-14 anos" = 11 a 14 */
$faixas = ['5-10' => ['5-10 anos', 0, 10], '10-14' => ['10-14 anos', 11, 14]];
$idade = $_GET['idade'] ?? '';      if (!isset($faixas[$idade])) $idade = '';
$busca = trim($_GET['busca'] ?? '');

if ($tipo === 'cartaz') {
    $where = "exibicao_filme = 'Cinema' AND tipo_conteudo = 'Filme'";
    $ordem = "nome_filme";
} elseif ($tipo === 'populares') {   // streaming ordenado por visualizações
    $where = "exibicao_filme = 'Streaming' AND tipo_conteudo = 'Filme'";
    $ordem = "(SELECT COUNT(*) FROM historico_visualizacao h WHERE h.id_filme = filme.id_filme) DESC, nome_filme";
} else {
    $where = "tipo_conteudo = 'Serie'";
    $ordem = "nome_filme";
}
$todos = $pdo->query("SELECT id_filme, nome_filme, imagem_capa, class_filme FROM filme WHERE $where ORDER BY $ordem")->fetchAll();

$filmes = [];
foreach ($todos as $f) {
    $i = lx_idade($f['class_filme']);
    if ($i === null || $i > 14) continue;                                      // só conteúdo infantil/juvenil
    if ($idade !== '' && ($i < $faixas[$idade][1] || $i > $faixas[$idade][2])) continue;
    if ($busca !== '' && mb_stripos($f['nome_filme'], $busca, 0, 'UTF-8') === false) continue;
    $filmes[] = $f;
}

/* ---------------- Destaque (carrossel por links): estreias infantis em cartaz ---------------- */
$cand = $pdo->query("SELECT id_filme, nome_filme, sinope, imagem_capa, data_filme, duracao_filme, class_filme FROM filme
                     WHERE exibicao_filme = 'Cinema' AND tipo_conteudo = 'Filme' ORDER BY data_filme DESC")->fetchAll();
$destaques = [];
foreach ($cand as $f) {
    $i = lx_idade($f['class_filme']);
    if ($i !== null && $i <= 14) $destaques[] = $f;
    if (count($destaques) === 5) break;
}
$n_dest = count($destaques);
$pos = $n_dest ? max(0, min($n_dest - 1, (int)($_GET['destaque'] ?? 0))) : 0;
$hero = $n_dest ? $destaques[$pos] : null;
$base = ['tipo' => $tipo === 'cartaz' ? '' : $tipo, 'idade' => $idade, 'busca' => $busca];
function lx_url_dest($base, $n) { return lx_url($base + ['destaque' => $n ?: '']) . '#topo'; }

/* ---------------- Clássicos: títulos mais bem avaliados (completa com os mais antigos) ---------------- */
$classicos = $pdo->query(
    "SELECT f.id_filme, f.nome_filme, f.imagem_capa, f.data_filme, f.duracao_filme, AVG(a.nota_avl) AS media
       FROM filme f JOIN avaliacao a ON a.id_filme = f.id_filme
      GROUP BY f.id_filme, f.nome_filme, f.imagem_capa, f.data_filme, f.duracao_filme
      ORDER BY media DESC, f.nome_filme LIMIT 3"
)->fetchAll();
if (count($classicos) < 3) {
    $ja = array_map('intval', array_column($classicos, 'id_filme')) ?: [0];
    $marc = implode(',', array_fill(0, count($ja), '?'));
    $limite = 3 - count($classicos);
    $st = $pdo->prepare(
        "SELECT id_filme, nome_filme, imagem_capa, data_filme, duracao_filme, NULL AS media
           FROM filme WHERE id_filme NOT IN ($marc) ORDER BY data_filme ASC LIMIT $limite"
    );
    $st->execute($ja);
    $classicos = array_merge($classicos, $st->fetchAll());
}

/* ---------------- Bomboniere: combos infantis primeiro ---------------- */
$combos = $pdo->query(
    "SELECT id_produto, nome_produto, valor_produto, descricao_produto, categoria_produto FROM bomboniere
      ORDER BY (categoria_produto = 'Infantil') DESC, nome_produto LIMIT 2"
)->fetchAll();
/* Imagem do combo: ./img/Combos/1/<nome>.(png|jpg|jpeg|webp), com e sem o prefixo "Combo " */
function lx_imagem($nome) {
    foreach ([$nome, preg_replace('/^Combo\s+/iu', '', $nome)] as $v) {
        foreach (['png', 'jpg', 'jpeg', 'webp'] as $ext) {
            if (is_file(__DIR__ . '/img/Combos/1/' . $v . '.' . $ext)) return './img/Combos/1/' . $v . '.' . $ext;
        }
    }
    return './img/Logo - LUX.png';
}
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>CINE - LUX</title>
  <link rel="stylesheet" href="./CSS/infantil.css">
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
      <a href="./combos.php" class="navegacao-opcoes">Combos</a>
      <a href="./infantil.php" class="navegacao-opcoes">Infantil</a>
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

  <?php if ($hero): ?>
  <!-- BANNER HERO / DESTAQUE PRINCIPAL -->
  <section class="hero-section">
    <div class="container hero-grid">
      <div class="hero-info">
        <div class="badges">
          <span class="badge badge-yellow">Diversão para todas as idades</span>
          <?php if ($hero['duracao_filme']): ?><span class="meta-item"><i class="fa-regular fa-clock"></i> <?= h(lx_duracao($hero['duracao_filme'])) ?></span><?php endif; ?>
          <span class="meta-item"><i class="fa-regular fa-calendar"></i> <?= h(lx_data_extenso($hero['data_filme'])) ?></span>
          <span class="badge badge-orange"><?= h($hero['class_filme']) ?></span>
        </div>

        <h1 class="hero-title"><?= h($hero['nome_filme']) ?></h1>

        <p class="hero-synopsis"><?= $hero['sinope'] ?></p>

        <div class="hero-buttons">
          <a href="ingressos.php?id_filme=<?= (int)$hero['id_filme'] ?>"><button type="button" class="btn-orange"><i class="fa-solid fa-ticket"></i> Comprar Ingressos</button></a>
          <a href="filmes.php?id=<?= (int)$hero['id_filme'] ?>"><button type="button" class="btn-dark">Ver Detalhes</button></a>
        </div>
      </div>

      <div class="hero-poster">
        <img src="<?= h(lx_capa($hero)) ?>" alt="Pôster de <?= h($hero['nome_filme']) ?>">
      </div>
    </div>

    <?php if ($n_dest > 1): ?>
    <!-- Paginação do carrossel (links, sem JavaScript) -->
    <div class="carousel-controls">
      <a href="<?= h(lx_url_dest($base, ($pos - 1 + $n_dest) % $n_dest)) ?>"><button type="button" class="arrow-btn"><i class="fa-solid fa-chevron-left"></i></button></a>
      <div class="dots">
        <?php for ($i = 0; $i < $n_dest; $i++): ?>
          <a href="<?= h(lx_url_dest($base, $i)) ?>" style="text-decoration:none;"><span class="dot<?= $i === $pos ? ' active' : '' ?>"></span></a>
        <?php endfor; ?>
      </div>
      <a href="<?= h(lx_url_dest($base, ($pos + 1) % $n_dest)) ?>"><button type="button" class="arrow-btn"><i class="fa-solid fa-chevron-right"></i></button></a>
    </div>
    <?php endif; ?>
  </section>
  <?php endif; ?>

  <!-- FILTROS E CATÁLOGO EM CARTAZ -->
  <section class="catalog-section">
    <div class="container">
      <!-- Linha de Filtros de Idade (clicar na faixa ativa remove o filtro) -->
      <div class="filter-row">
        <button type="button" class="filter-icon"><i class="fa-solid fa-magnifying-glass"></i></button>
        <button type="button" class="filter-icon"><i class="fa-solid fa-sliders"></i></button>
        <a href="<?= h(lx_url(['tipo' => $tipo === 'cartaz' ? '' : $tipo, 'busca' => $busca])) ?>"><button type="button" class="pill-filter<?= $idade === '' ? ' active-dark' : ' yellow' ?>">Todos</button></a>
        <?php foreach ($faixas as $chave => $fx): $ativa = ($chave === $idade); ?>
          <a href="<?= h(lx_url(['tipo' => $tipo === 'cartaz' ? '' : $tipo, 'idade' => $ativa ? '' : $chave, 'busca' => $busca])) ?>"><button type="button" class="pill-filter <?= $ativa ? 'active-dark' : 'yellow' ?>"><?= h($fx[0]) ?></button></a>
        <?php endforeach; ?>
      </div>

      <!-- Abas de Categoria (preservam a faixa etária) -->
      <div class="category-tabs">
        <?php foreach ($abas as $chave => $rotulo): ?>
          <a href="<?= h(lx_url(['tipo' => $chave === 'cartaz' ? '' : $chave, 'idade' => $idade, 'busca' => $busca])) ?>"><button type="button" class="tab-btn<?= $chave === $tipo ? ' active' : '' ?>"><?= h($rotulo) ?></button></a>
        <?php endforeach; ?>
      </div>

      <!-- Grid de Filmes -->
      <?php if (!$filmes): ?>
        <p style="text-align:center;padding:40px 0;">Nenhum título encontrado.</p>
      <?php else: ?>
      <div class="movies-grid">
        <?php foreach ($filmes as $f): ?>
        <div class="movie-card">
          <img src="<?= h(lx_capa($f)) ?>" alt="<?= h($f['nome_filme']) ?>">
          <h3><?= h($f['nome_filme']) ?></h3>
          <a href="filmes.php?id=<?= (int)$f['id_filme'] ?>" class="details-link">Ver Detalhes</a>
        </div>
        <?php endforeach; ?>
      </div>
      <?php endif; ?>
    </div>
  </section>

  <?php if ($classicos): ?>
  <!-- SEÇÃO CLÁSSICOS / AVALIAÇÕES -->
  <section class="classics-section">
    <div class="container">
      <div class="classics-grid">
        <?php foreach ($classicos as $f): ?>
        <div class="classic-card">
          <img src="<?= h(lx_capa($f)) ?>" alt="<?= h($f['nome_filme']) ?>">
          <div class="classic-meta">
            <div>
              <small>Lançamento: <?= h(lx_data_extenso($f['data_filme'])) ?></small>
              <small>Duração: <?= h(lx_duracao($f['duracao_filme'])) ?>.</small>
            </div>
            <?php if ($f['media'] !== null): ?>
              <span class="rating"><i class="fa-solid fa-star"></i> <?= h(number_format((float)$f['media'], 1, ',', '')) ?>/10</span>
            <?php endif; ?>
          </div>
          <a href="filmes.php?id=<?= (int)$f['id_filme'] ?>" class="details-link">Ver Detalhes</a>
        </div>
        <?php endforeach; ?>
      </div>
    </div>
  </section>
  <?php endif; ?>

  <!-- BOMBONIERE EXCLUSIVA -->
  <section class="bomboniere-section" id="bomboniere">
    <div class="container">
      <div class="bomboniere-header">
        <div>
          <span class="badge badge-orange">BOMBONIERE EXCLUSIVA</span>
          <h2>Combos Especiais & Exclusivos</h2>
          <p>Garanta seus petiscos e itens exclusivos antes de entrar na sala</p>
        </div>
        <a href="./bomboniere.php"><button type="button" class="btn-yellow"><i class="fa-solid fa-cart-shopping"></i> Ver Cardápio Completo</button></a>
      </div>

      <div class="combos-grid">
        <?php foreach ($combos as $c): ?>
        <div class="combo-card">
          <img src="<?= h(lx_imagem($c['nome_produto'])) ?>" alt="<?= h($c['nome_produto']) ?>">
          <div class="combo-content">
            <div class="combo-top">
              <h4><?= $c['nome_produto'] ?></h4>
              <span class="combo-tag"><?= $c['categoria_produto'] ?></span>
            </div>
            <p><?= h($c['descricao_produto']) ?></p>
            <div class="combo-bottom">
              <span class="price"><?= h(number_format((float)$c['valor_produto'], 2, ',', '.')) ?></span>
              <form method="post" action="infantil.php" style="display:contents">
                <input type="hidden" name="id_produto" value="<?= (int)$c['id_produto'] ?>">
                <input type="hidden" name="tipo" value="<?= h($tipo === 'cartaz' ? '' : $tipo) ?>">
                <input type="hidden" name="idade" value="<?= h($idade) ?>">
                <input type="hidden" name="busca" value="<?= h($busca) ?>">
                <button type="submit" class="btn-orange-sm">Adicionar</button>
              </form>
            </div>
          </div>
        </div>
        <?php endforeach; ?>
        <?php if (!$combos): ?><p>Nenhum combo disponível no momento.</p><?php endif; ?>
      </div>
    </div>
  </section>

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
        <a href="./combos.php">Bomboniere & Combos</a>
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