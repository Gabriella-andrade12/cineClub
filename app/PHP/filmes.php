<?php

require_once "./crud.php";

$id_filme = isset($_GET['id_filme']) ? (int)$_GET['id_filme'] : 0;

if (isset($filme[$id_filme])) {
    $tituloPagina = $filme[$id_filme] . " - Meu Site de Filmes";
} else {
    $tituloPagina = "Catálogo - CINE de Filmes";
}

if ($id_filme <= 0) {
    die("Filme não especificado.");
}

$filme = read($pdo, 'filme', $id_filme);

if (!$filme) {
    die("Filme não encontrado no catálogo.");
}

$generos = !empty($filme['gen_filme']) ? array_map('trim', explode(',', $filme['gen_filme'])) : [];

$data_formatada = $filme['data_filme'];
if (strtotime($filme['data_filme'])) {
    $data_formatada = date('d/m/Y', strtotime($filme['data_filme']));
}

?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($tituloPagina); ?></title>
    <link rel="stylesheet" href="./CSS/filmes.css">
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

    <main class="filme">
        <div class="detalhes-do-filme">
            <div class="poster-do-filme">
                <img src="<?= htmlspecialchars($filme['img_filme'] ?? './img/poster.png') ?>"
                    alt="Pôster do filme <?= htmlspecialchars($filme['nome_filme']) ?>" alt="" class="poster-do-filme-img">
            </div>

            <div class="informacaoes">
                <div class="dados-do-filme">
                    <span class="classificacao"><?= htmlspecialchars($filme['class_filme']) ?></span>
                    <span><?= htmlspecialchars($filme['duracao_filme']) ?></span>
                    <span>•</span>
                    <span>Lançamento: <?= htmlspecialchars($data_formatada) ?></span>
                    <span>•</span>
                    <span class="exibicao"><?= htmlspecialchars($filme['exibicao_filme']) ?></span>
                </div>

                <h1 class="titulo-filme"><?= htmlspecialchars($filme['nome_filme']) ?></h1>

                <div class="lista-generos">
                    <?php foreach ($generos as $genero): ?>
                        <span class="genero"><?= htmlspecialchars($genero) ?></span>
                    <?php endforeach; ?>
                </div>

                <div class="sobre-o-filme">
                    <p><strong>Código do Filme:</strong> #<?= htmlspecialchars($filme['id_filme']) ?></p>
                    <p><strong>Data de Estreia:</strong> <?= htmlspecialchars($data_formatada) ?></p>
                    <p><strong>Duração:</strong> <?= htmlspecialchars($filme['duracao_filme']) ?></p>
                    <p><strong>Classificação Indicativa:</strong> <?= htmlspecialchars($filme['class_filme']) ?></p>
                    <p><strong>Formato de Exibição:</strong> <?= htmlspecialchars($filme['exibicao_filme']) ?></p>
                </div>

                <div class="botoes-acao">
                    <?php if (str_contains(strtolower(trim($filme['exibicao_filme'] ?? '')), 'cinema')): ?>
                        <a href="#sessoes" class="botao">Ver Sessões no Cinema</a>
                    <?php else: ?>
                        <a href="#assistir" class="botao">Assistir Agora</a>
                    <?php endif; ?>
                    <a href="index.php" class="botao-foco">Explorar Mais Títulos</a>
                </div>
            </div>

        </div>

        <?php if (strtolower(trim($filme['exibicao_filme'] ?? '')) === 'cinema'): ?>
            <section id="sessoes" class="caixa-de-sessoes">
                <h2>Sessões Disponíveis - <span class="texto">São Paulo, SP</span></h2>
                <p class="aviso-sessao">Selecione um horário abaixo para escolher seus assentos:</p>

                <div class="lista-horarios">
                    <a href="#" class="botao-h">14:30 <small>Dublado • 2D</small></a>
                    <a href="#" class="botao-h">17:15 <small>Legendado • 2D</small></a>
                    <a href="#" class="botao-h">20:00 <small>Legendado • IMAX</small></a>
                    <a href="#" class="botao-h">22:30 <small>Dublado • VIP</small></a>
                </div>
            </section>
        <?php else: ?>
            <section id="assistir" class="caixa-de-sessoes">
                <h2>Disponível no Catálogo Online - <span class="texto"><?= htmlspecialchars($filme['exibicao_filme']) ?></span></h2>
                <p class="aviso-sessao">Este título está disponível para reprodução imediata na plataforma.</p>
            </section>
        <?php endif; ?>
    </main>

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