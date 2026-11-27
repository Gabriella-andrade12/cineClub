<?php
require_once "../init.php";   // fornece a conexão PDO ($pdo), crud() e inicia a sessão

if (session_status() === PHP_SESSION_NONE) { session_start(); }
if (empty($_SESSION['id_usuario'])) { header('Location: login.php'); exit; }
$uid  = (int)$_SESSION['id_usuario'];
$nome = $_SESSION['nome'] ?? '';

if (!function_exists('h')) {
    function h($s) { return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8'); }
}

/* =====================================================================
 * ALGORITMO DE RECOMENDAÇÃO
 * 1) Gêneros mais assistidos: historico_visualizacao JOIN filme.
 * 2) Sugere filmes desses gêneros que o usuário AINDA NÃO assistiu,
 *    ordenados pela afinidade do gênero e pela nota média.
 * ===================================================================== */
$st = $pdo->prepare(
    "SELECT f.gen_filme AS genero, COUNT(*) AS qtd
       FROM historico_visualizacao h JOIN filme f ON f.id_filme = h.id_filme
      WHERE h.id_usuario = ? AND f.gen_filme IS NOT NULL AND f.gen_filme <> ''
      GROUP BY f.gen_filme ORDER BY qtd DESC, f.gen_filme LIMIT 4"
);
$st->execute([$uid]);
$generos = $st->fetchAll();

$st = $pdo->prepare("SELECT COUNT(*) FROM historico_visualizacao WHERE id_usuario = ?");
$st->execute([$uid]);
$total_views = (int)$st->fetchColumn();

// Percentuais sobre o total assistido; "Outros" fecha os 100%
$paleta = ['#f26b3a', '#f7d86a', '#4aa3df', '#9b6bd6', '#5b6b8a'];
$itens = []; $soma = 0;
foreach ($generos as $g) {
    $pct = $total_views > 0 ? (int)round($g['qtd'] * 100 / $total_views) : 0;
    $itens[] = ['nome' => $g['genero'], 'pct' => $pct];
    $soma += $pct;
}
if ($total_views > 0 && $soma < 100) { $itens[] = ['nome' => 'Outros', 'pct' => 100 - $soma]; }
if ($total_views > 0 && $soma > 100)  { $itens[0]['pct'] -= ($soma - 100); }   // ajuste de arredondamento

// Gráfico circular (conic-gradient) montado a partir dos percentuais
$partes = []; $ini = 0;
foreach ($itens as $k => $it) {
    $fim = $ini + $it['pct'];
    $partes[] = $paleta[$k % count($paleta)] . " {$ini}% {$fim}%";
    $ini = $fim;
}
$grafico = $partes ? 'conic-gradient(' . implode(', ', $partes) . ')' : 'conic-gradient(#2a3d5e 0% 100%)';

// Texto-resumo
$principal = $itens[0]['nome'] ?? null;
$resumo = 'Assista a alguns títulos em streaming para recebermos o seu gosto e montarmos recomendações só para você.';
if ($principal && count($itens) >= 2 && $itens[1]['nome'] !== 'Outros') {
    $resumo = 'Sua afinidade por ' . $principal . ' é alta. ' . $principal . ' e ' . $itens[1]['nome']
            . ' compõem ' . ($itens[0]['pct'] + $itens[1]['pct']) . '% do seu histórico de visualização.';
} elseif ($principal) {
    $resumo = 'Sua afinidade por ' . $principal . ' é alta: ' . $itens[0]['pct'] . '% do seu histórico de visualização.';
}

// Filmes recomendados
$ranking = array_column($generos, 'genero');            // posição = afinidade
$filmes = []; $personalizado = (bool)$ranking;
$sel = "SELECT f.id_filme, f.nome_filme, f.imagem_capa, f.gen_filme,
               (SELECT AVG(a.nota_avl) FROM avaliacao a WHERE a.id_filme = f.id_filme) AS media
          FROM filme f
         WHERE f.id_filme NOT IN (SELECT id_filme FROM historico_visualizacao WHERE id_usuario = ?)";
if ($ranking) {
    $marc = implode(',', array_fill(0, count($ranking), '?'));
    $st = $pdo->prepare($sel . " AND f.gen_filme IN ($marc)");
    $st->execute(array_merge([$uid], $ranking));
    $filmes = $st->fetchAll();
    usort($filmes, function ($a, $b) use ($ranking) {
        $ra = array_search($a['gen_filme'], $ranking, true);
        $rb = array_search($b['gen_filme'], $ranking, true);
        return [$ra, -(float)$a['media'], $a['nome_filme']] <=> [$rb, -(float)$b['media'], $b['nome_filme']];
    });
} else {
    // Sem histórico: mostra os mais bem avaliados da plataforma
    $st = $pdo->prepare($sel . " ORDER BY media DESC, f.nome_filme");
    $st->execute([$uid]);
    $filmes = $st->fetchAll();
}
$filmes = array_slice($filmes, 0, 12);
$stComents = $pdo->prepare(
    "SELECT cometario_avl FROM avaliacao WHERE id_filme = ? AND cometario_avl IS NOT NULL AND cometario_avl <> '' ORDER BY id_avaliacao DESC LIMIT 2"
);

// Estrelas (escala 1 a 5): cheia / meia / vazia
function lx_estrelas($media) {
    $html = '';
    $m = round(((float)$media) * 2) / 2;
    for ($i = 1; $i <= 5; $i++) {
        if ($m >= $i)            $html .= '<i class="fa-solid fa-star"></i>';
        elseif ($m >= $i - 0.5)  $html .= '<i class="fa-solid fa-star-half-stroke"></i>';
        else                     $html .= '<i class="fa-regular fa-star"></i>';
    }
    return $html;
}
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CINELUX - Recomendados para você</title>
    <link rel="stylesheet" href="./CSS/recomendados.css">
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
            <a href="#" class="navegacao-ativa">Recomendados</a>
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

    <main class="faixa-principal">
        <div class="cabecalho-boas-vindas">
            <h2>OLÁ<?= $nome !== '' ? ', ' . trim($nome) : '' ?>, SUAS RECOMENDAÇÕES PESSOAIS ESTÃO AQUI.</h2>
        </div>
        <section class="cartao painel-recomendacoes">
            <div class="grade-recomendacoes">
                <div class="grafico-recomendacoes">
                    <div class="grafico-circulo" style="background: <?= h($grafico) ?>;">
                        <div class="grafico-centro">
                            <span class="porcentagem-destaque"><?= $principal ? (int)$itens[0]['pct'] . '%' : '--' ?></span>
                            <span class="legenda-centro"><?= $principal ? 'DE ' . $principal : 'SEM HISTÓRICO' ?></span>
                        </div>
                    </div>
                </div>
                <div class="estatisticas-genero">
                    <?php foreach ($itens as $k => $it): ?>
                        <div class="item-genero">
                            <span class="indicador-cor" style="background: <?= ($paleta[$k % count($paleta)]) ?>;"></span>
                            <span class="nome-genero"><?= h($it['nome']) ?></span>
                            <span class="valor-genero"><?= (int)$it['pct'] ?>%</span>
                        </div>
                    <?php endforeach; ?>

                    <p class="resumo-recomendacoes"><?= ($resumo) ?></p>
                </div>
            </div>
        </section>
        <section class="secao-recomendados">
            <h3 class="titulo-secao"><?= $personalizado ? 'BASEADO NO QUE VOCÊ ASSISTIU ANTES' : 'OS MAIS BEM AVALIADOS DA LUX' ?></h3>

            <div class="carrossel-filmes">
                <?php foreach ($filmes as $f):
                    $capa = !empty($f['imagem_capa']) ? $f['imagem_capa'] : './img/Logo - LUX.png';
                    $stComents->execute([(int)$f['id_filme']]);
                    $coments = $stComents->fetchAll();
                ?>
                <article class="cartao-filme">
                    <div class="cartaz-recomendacoes">
                        <a href="filmes.php?id=<?= (int)$f['id_filme'] ?>">
                            <img src="<?= h($capa) ?>" alt="<?= h($f['nome_filme']) ?>" class="cartaz-filme">
                        </a>
                    </div>
                    <div class="info-filme">
                        <h4 class="titulo-filme"><a href="filmes.php?id=<?= (int)$f['id_filme'] ?>" style="color:inherit;text-decoration:none;"><?= $f['nome_filme'] ?></a></h4>
                        <div class="nota-geral">AVALIAÇÃO GERAL: <?= $f['media'] !== null ? number_format((float)$f['media'], 1, '.', '') . '/5.0' : 'sem avaliações' ?></div>
                        <div class="estrelas"><?= lx_estrelas($f['media'] ?? 0) ?></div>
                        <div class="comentarios-curtos">
                            <?php foreach ($coments as $c): ?>
                                <p class="comentario"><i class="fa-solid"></i> "<?= h(function_exists('mb_strimwidth') ? mb_strimwidth($c['cometario_avl'], 0, 60, '…', 'UTF-8') : preg_replace('/^(.{59}).+$/us', '$1…', (string)$c['cometario_avl'])) ?>"</p>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </article>
                <?php endforeach; ?>
            </div>

            <p class="aviso-secao"><?php
                if (!$filmes)            echo 'Nenhuma recomendação disponível no momento.';
                elseif (!$personalizado) echo 'Assista a títulos em streaming para personalizarmos suas recomendações.';
            ?></p>
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