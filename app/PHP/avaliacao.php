<?php
require_once "../init.php";   // fornece a conexão PDO ($pdo), crud() e inicia a sessão

if (session_status() === PHP_SESSION_NONE) { session_start(); }
if (empty($_SESSION['id_usuario'])) { header('Location: login.php'); exit; }
$uid = (int)$_SESSION['id_usuario'];

/* ---------------- Helpers ---------------- */
if (!function_exists('h')) {
    function h($s) { return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8'); }
}

/* A data da avaliação só é usada se a tabela tiver a coluna data_avaliacao
 * (ALTER TABLE avaliacao ADD COLUMN data_avaliacao DATE NULL;) */
$tem_data = (bool)$pdo->query("SHOW COLUMNS FROM avaliacao LIKE 'data_avaliacao'")->fetchAll();
const PONTOS_AVALIACAO = 100;

/* ---------------- O que o usuário pode avaliar ----------------
 * Filmes: assistidos (histórico) ou com ingresso de sessão já realizada.
 * Produtos: itens de bomboniere que ele comprou. */
$st = $pdo->prepare(
    "SELECT f.id_filme, f.nome_filme, MAX(d.data) AS data
       FROM filme f
       JOIN (SELECT id_filme, DATE(data_visualizacao) AS data FROM historico_visualizacao WHERE id_usuario = ?
             UNION ALL
             SELECT s.id_filme, s.dia_sessao FROM ingressos i
               JOIN venda v ON v.id_venda = i.id_venda JOIN sessao s ON s.id_sessao = i.id_sessao
              WHERE v.id_usuario = ? AND s.dia_sessao <= CURDATE()) d ON d.id_filme = f.id_filme
      GROUP BY f.id_filme, f.nome_filme ORDER BY data DESC, f.nome_filme"
);
$st->execute([$uid, $uid]);
$filmes = $st->fetchAll();

$st = $pdo->prepare(
    "SELECT b.id_produto, b.nome_produto, MAX(v.id_venda) AS pedido
       FROM bomboniere b JOIN item_venda iv ON iv.id_produto = b.id_produto JOIN venda v ON v.id_venda = iv.id_venda
      WHERE v.id_usuario = ? GROUP BY b.id_produto, b.nome_produto ORDER BY pedido DESC"
);
$st->execute([$uid]);
$produtos = $st->fetchAll();

$ids_filmes   = array_map('intval', array_column($filmes, 'id_filme'));
$ids_produtos = array_map('intval', array_column($produtos, 'id_produto'));

/* ---------------- Publicar avaliação (POST) ---------------- */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tipo = ($_POST['tipo_avaliacao'] ?? '') === 'produto' ? 'produto' : 'filme';
    $nota = (int)($_POST['nota'] ?? 0);
    $com  = trim($_POST['comentario'] ?? '');
    $idf  = (int)($_POST['id_filme'] ?? 0);
    $idp  = (int)($_POST['id_produto'] ?? 0);
    $old  = ['tipo' => $tipo, 'id_filme' => $idf, 'id_produto' => $idp, 'nota' => $nota, 'comentario' => $com];
    $erros = [];

    // integridade: só vale item que o usuário realmente assistiu / comprou
    if ($tipo === 'filme'   && !in_array($idf, $ids_filmes, true))   $erros[] = 'Escolha um filme que você assistiu.';
    if ($tipo === 'produto' && !in_array($idp, $ids_produtos, true)) $erros[] = 'Escolha um produto que você comprou.';
    if ($nota < 1 || $nota > 10) $erros[] = 'Escolha uma nota de 1 a 10.';
    if ($com === '')             $erros[] = 'Escreva um comentário.';
    if (mb_strlen($com, 'UTF-8') > 600) $erros[] = 'O comentário deve ter no máximo 600 caracteres.';

    if ($erros) {
        $_SESSION['avaliacao_flash'] = ['tipo' => 'erro', 'msgs' => $erros, 'old' => $old];
    } else {
        $col = $tipo === 'filme' ? 'id_filme' : 'id_produto';
        $val = $tipo === 'filme' ? $idf : $idp;
        try {
            $pdo->beginTransaction();
            $ex = read($pdo, 'avaliacao', "id_usuario = ? AND $col = ?", [$uid, $val]);
            if ($ex) {
                // já avaliou este item: atualiza (sem pontos extras)
                if ($tem_data) {
                    $st = $pdo->prepare("UPDATE avaliacao SET nota_avl = ?, cometario_avl = ?, data_avaliacao = CURDATE() WHERE id_avaliacao = ?");
                    $st->execute([$nota, $com, (int)$ex['id_avaliacao']]);
                } else {
                    update($pdo, 'avaliacao', ['nota_avl' => $nota, 'cometario_avl' => $com], 'id_avaliacao = ?', [(int)$ex['id_avaliacao']]);
                }
                $msg = 'Avaliação atualizada!';
            } else {
                $idfilme = $tipo === 'filme' ? $idf : null;
                $idprod  = $tipo === 'produto' ? $idp : null;
                if ($tem_data) {
                    $st = $pdo->prepare("INSERT INTO avaliacao (nota_avl, cometario_avl, id_usuario, id_produto, id_filme, data_avaliacao) VALUES (?, ?, ?, ?, ?, CURDATE())");
                    $st->execute([$nota, $com, $uid, $idprod, $idfilme]);
                } else {
                    create($pdo, 'avaliacao', [
                        'nota_avl'      => $nota,
                        'cometario_avl' => $com,
                        'id_usuario'    => $uid,
                        'id_produto'    => $idprod,
                        'id_filme'      => $idfilme,
                    ]);
                }
                // Clube LUX: +100 pontos pela primeira avaliação do item
                $st = $pdo->prepare("SELECT qntd_pontos FROM pontos_usuario WHERE id_usuario = ? FOR UPDATE");
                $st->execute([$uid]);
                if ($st->fetch()) {
                    $stUpd = $pdo->prepare("UPDATE pontos_usuario SET qntd_pontos = qntd_pontos + ? WHERE id_usuario = ?");
                    $stUpd->execute([PONTOS_AVALIACAO, $uid]);
                } else {
                    create($pdo, 'pontos_usuario', ['qntd_pontos' => PONTOS_AVALIACAO, 'id_usuario' => $uid]);
                }
                $msg = 'Avaliação publicada! Você ganhou +' . PONTOS_AVALIACAO . ' pontos no Clube LUX.';
            }
            $pdo->commit();
            $_SESSION['avaliacao_flash'] = ['tipo' => 'ok', 'msgs' => [$msg]];
        } catch (Throwable $e) {
            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }
            $_SESSION['avaliacao_flash'] = ['tipo' => 'erro', 'msgs' => ['Não foi possível publicar agora. Tente novamente.'], 'old' => $old];
        }
    }
    header('Location: avaliacao.php');   // PRG: evita reenvio ao recarregar
    exit;
}

$flash = $_SESSION['avaliacao_flash'] ?? null;
unset($_SESSION['avaliacao_flash']);

// Valores do formulário: restaura após erro; aceita ?filme=ID ou ?produto=ID para pré-selecionar
$form = ['tipo' => 'filme', 'id_filme' => 0, 'id_produto' => 0, 'nota' => 0, 'comentario' => ''];
if ($flash && ($flash['tipo'] ?? '') === 'erro' && isset($flash['old'])) { $form = $flash['old']; }
elseif (isset($_GET['produto'])) { $form['tipo'] = 'produto'; $form['id_produto'] = (int)$_GET['produto']; }
elseif (isset($_GET['filme']))   { $form['id_filme'] = (int)$_GET['filme']; }

/* ---------------- Últimas avaliações do usuário ---------------- */
$st = $pdo->prepare(
    "SELECT a.nota_avl, a.cometario_avl, a.id_filme, a.id_produto, COALESCE(f.nome_filme, b.nome_produto) AS item"
    . ($tem_data ? ", a.data_avaliacao" : "") . "
       FROM avaliacao a LEFT JOIN filme f ON f.id_filme = a.id_filme LEFT JOIN bomboniere b ON b.id_produto = a.id_produto
      WHERE a.id_usuario = ? ORDER BY a.id_avaliacao DESC LIMIT 5"
);
$st->execute([$uid]);
$recentes = $st->fetchAll();
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>CINE - LUX</title>
  <link rel="stylesheet" href="./CSS/avaliacao.css">
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

  <main class="container">

    <!-- TÍTULO DA PÁGINA -->
    <header class="hero-avaliacao">
      <span class="etiqueta-secao">SUA OPINIÃO VALE PONTOS</span>
      <h1>AVALIE SUA <span>EXPERIÊNCIA</span></h1>
      <p class="subtitulo-hero">
        Compartilhe o que achou dos filmes que você assistiu nos cinemas LUX ou dos combos e produtos adquiridos na bomboniere.
      </p>
    </header>

    <!-- GRADE PRINCIPAL: FORMULÁRIO + HISTÓRICO RECENTE -->
    <div class="grade-avaliacao">

      <!-- CARD DO FORMULÁRIO -->
      <section class="card-formulario">

        <?php if ($flash): foreach ($flash['msgs'] as $m): ?>
          <div role="<?= $flash['tipo'] === 'erro' ? 'alert' : 'status' ?>" style="margin:0 0 12px;padding:10px 14px;border-radius:10px;<?= $flash['tipo'] === 'erro' ? 'background:#4a1f25;border:1px solid #a33a48;' : 'background:#1f3d2e;border:1px solid #2f7a52;' ?>"><?= h($m) ?></div>
        <?php endforeach; endif; ?>

        <form action="avaliacao.php" method="post" class="form-avaliacao">

          <!-- SELETOR DE CATEGORIA 100% EM CSS -->
          <div class="grupo-campo">
            <label class="rotulo-campo">O que você deseja avaliar?</label>

            <div class="seletor-tipo">
              <input type="radio" name="tipo_avaliacao" id="tipo-filme" value="filme" class="radio-oculto"<?= $form['tipo'] === 'filme' ? ' checked' : '' ?>>
              <input type="radio" name="tipo_avaliacao" id="tipo-produto" value="produto" class="radio-oculto"<?= $form['tipo'] === 'produto' ? ' checked' : '' ?>>

              <div class="abas-tipo">
                <label for="tipo-filme" class="aba-tipo">
                  <i class="fa-solid fa-film"></i> Filme Assistido
                </label>
                <label for="tipo-produto" class="aba-tipo">
                  <i class="fa-solid fa-bucket"></i> Produto / Combo Comprado
                </label>
              </div>

              <!-- SELECT DE FILMES (aparece quando 'Filme Assistido' está marcado) -->
              <div class="campo-dinamico campo-filme">
                <label for="id_filme" class="sub-rotulo">Selecione um filme do seu histórico de sessões</label>
                <div class="select-wrapper">
                  <select name="id_filme" id="id_filme" class="campo-select">
                    <option value="">-- Escolha o filme assistido --</option>
                    <?php foreach ($filmes as $f): ?>
                      <option value="<?= (int)$f['id_filme'] ?>"<?= (int)$form['id_filme'] === (int)$f['id_filme'] ? ' selected' : '' ?>><?= h($f['nome_filme']) ?> (Assistido em <?= h(date('d/m/Y', strtotime($f['data']))) ?>)</option>
                    <?php endforeach; ?>
                  </select>
                  <i class="fa-solid fa-chevron-down select-seta"></i>
                </div>
                <?php if (!$filmes): ?><span class="dica-campo">Você ainda não assistiu nenhum título pela LUX.</span><?php endif; ?>
              </div>

              <!-- SELECT DE PRODUTOS (aparece quando 'Produto Comprado' está marcado) -->
              <div class="campo-dinamico campo-produto">
                <label for="id_produto" class="sub-rotulo">Selecione um combo ou produto comprado</label>
                <div class="select-wrapper">
                  <select name="id_produto" id="id_produto" class="campo-select">
                    <option value="">-- Escolha o produto ou combo --</option>
                    <?php foreach ($produtos as $p): ?>
                      <option value="<?= (int)$p['id_produto'] ?>"<?= (int)$form['id_produto'] === (int)$p['id_produto'] ? ' selected' : '' ?>><?= h($p['nome_produto']) ?> (Pedido #<?= (int)$p['pedido'] ?>)</option>
                    <?php endforeach; ?>
                  </select>
                  <i class="fa-solid fa-chevron-down select-seta"></i>
                </div>
                <?php if (!$produtos): ?><span class="dica-campo">Você ainda não comprou itens da bomboniere.</span><?php endif; ?>
              </div>
            </div>
          </div>

          <!-- CAMPO DE NOTA DE 1 A 10 (INTERATIVO SEM JS) -->
          <div class="grupo-campo">
            <div class="linha-rotulo-nota">
              <label class="rotulo-campo">Sua Nota (de 1 a 10)</label>
              <span class="legenda-nota">1 = Ruim · 10 = Obra-prima</span>
            </div>

            <div class="escala-notas" role="radiogroup" aria-label="Nota de 1 a 10">
              <?php for ($n = 10; $n >= 1; $n--): ?>
              <input type="radio" name="nota" id="nota-<?= $n ?>" value="<?= $n ?>"<?= $n === 10 ? ' required' : '' ?><?= (int)$form['nota'] === $n ? ' checked' : '' ?>>
              <label for="nota-<?= $n ?>" title="Nota <?= $n ?>"><?= $n ?></label>

              <?php endfor; ?>
            </div>
          </div>

          <!-- COMENTÁRIO TEXTUAL -->
          <div class="grupo-campo">
            <label for="comentario" class="rotulo-campo">Seu Comentário</label>
            <textarea
              name="comentario"
              id="comentario"
              rows="5"
              maxlength="600"
              placeholder="Conte detalhes sobre o filme, a qualidade da sala, som ou o sabor do combo..."
              required><?= h($form['comentario']) ?></textarea>
            <span class="dica-campo">Máximo de 600 caracteres · Evite spoilers sem aviso.</span>
          </div>

          <button type="submit" class="btn-enviar">
            <i class="fa-solid fa-paper-plane"></i> Publicar Avaliação
          </button>
        </form>
      </section>

      <!-- COLUNA LATERAL: SUAS ÚLTIMAS AVALIAÇÕES -->
      <aside class="coluna-historico">
        <div class="card-lateral">
          <div class="cabecalho-lateral">
            <i class="fa-solid fa-star"></i>
            <h2>Suas Avaliações Recentes</h2>
          </div>

          <div class="lista-avaliacoes">
            <?php if (!$recentes): ?>
              <p>Você ainda não publicou avaliações.</p>
            <?php endif; ?>
            <?php foreach ($recentes as $a): $eh_filme = !empty($a['id_filme']); ?>
            <article class="item-avaliacao">
              <div class="topo-item">
                <span class="badge-tipo <?= $eh_filme ? 'filme' : 'produto' ?>"><?= $eh_filme ? 'FILME' : 'COMBO' ?></span>
                <span class="nota-badge"><i class="fa-solid fa-star"></i> <?= (int)$a['nota_avl'] ?>/10</span>
              </div>
              <h3><?= h($a['item']) ?></h3>
              <p>"<?= h($a['cometario_avl']) ?>"</p>
              <?php if ($tem_data && !empty($a['data_avaliacao'])): ?>
                <span class="data-avaliacao">Avaliado em <?= h(date('d/m/Y', strtotime($a['data_avaliacao']))) ?></span>
              <?php endif; ?>
            </article>
            <?php endforeach; ?>
          </div>

          <div class="aviso-pontos">
            <i class="fa-solid fa-crown"></i>
            <p>Cada avaliação válida de item comprado ou assistido rende <strong>+<?= PONTOS_AVALIACAO ?> PTS</strong> no Clube LUX.</p>
          </div>
        </div>
      </aside>

    </div>


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