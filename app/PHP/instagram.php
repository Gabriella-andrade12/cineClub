<?php

$utilizador = [
    'username'    => 'luxcinemas',
    'nome'        => 'LUX Cinema & Streaming',
    'verificado'  => true,
    'publicacoes' => '9.000',
    'seguidores'  => '1M',
    'a_seguir'    => '268',
    'bio_linhas'  => [
        'A experiência completa de cinema: nas salas ou na sua tela.',
        '🍿 Partilhe a sua sessão de cinema com #LUXCinemas para ser destacado!',
        '📌 link do CINELUX 👇'
    ],
    'site_texto'  => 'lux.link/cinemas',
    'site_url'    => 'https://lux.com.br'
];

$postagens = [
    '',
    '',
    '',
    '',
    ''
];

$destaques = [
    ['icone' => '🍿', 'titulo' => 'Em Cartaz'],
    ['icone' => '💻', 'titulo' => 'Streaming'],
    ['icone' => '🥤', 'titulo' => 'Combos'],
    ['icone' => '⭐', 'titulo' => 'Dicas']
];

?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $utilizador['nome']; ?> (@<?php echo $utilizador['username']; ?>)</title>
    <link rel="stylesheet" href="./CSS/instagram.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="shortcut icon" href="./img/Instagram - Logo" type="image/x-icon">
</head>

<body>
    <aside class="icones-do-insta">
        <div class="icones">
            <nav class="navegacao">
                <a href="#" class="links-dos-icones active"><i class="fa-solid fa-house"></i></a>
                <a href="#" class="links-dos-icones"><i class="fa-solid fa-magnifying-glass"></i></a>
                <a href="#" class="links-dos-icones"><i class="fa-regular fa-compass"></i></a>
                <a href="#" class="links-dos-icones"><i class="fa-solid fa-clapperboard"></i></a>
                <a href="#" class="links-dos-icones"><i class="fa-regular fa-paper-plane"></i></a>
                <a href="#" class="links-dos-icones"><i class="fa-regular fa-heart"></i></a>
                <a href="#" class="links-dos-icones"><i class="fa-regular fa-square-plus"></i></a>
                <a href="#" class="links-dos-icones logo-da-lux">
                    <img src="./img/Logo - LUX.png" alt="Perfil">
                </a>
            </nav>
        </div>

        <div class="botoes">
            <a href="#" class="links-dos-icones"><i class="fa-solid fa-threads"></i></a>
            <a href="#" class="links-dos-icones"><i class="fa-solid fa-bars"></i></a>
        </div>
    </aside>

    <main class="principal-perfil">
        <div class="perfil">

            <header class="faixa">
                <div class="foto-perfil">
                    <div class="perfil-img">
                        <img src="./img/Logo - LUX.png" alt="Foto Perfil LUX">
                    </div>
                </div>

                <div class="perfil-detalhes">
                    <div class="nome-usuario">
                        <h1><?php echo htmlspecialchars($utilizador['username']); ?></h1>
                        <?php if ($utilizador['verificado']): ?>
                            <i class="fa-solid fa-circle-check verificado"></i>
                        <?php endif; ?>
                    </div>

                    <ul class="informacoes-segundarias">
                        <li><strong><?php echo $utilizador['publicacoes']; ?></strong> publicações</li>
                        <li><strong><?php echo $utilizador['seguidores']; ?></strong> seguidores</li>
                        <li><strong><?php echo $utilizador['a_seguir']; ?></strong> a seguir</li>
                    </ul>

                    <div class="bio">
                        <h2><?php echo htmlspecialchars($utilizador['nome']); ?></h2>
                        <?php foreach ($utilizador['bio_linhas'] as $linha): ?>
                            <p><?php echo htmlspecialchars($linha); ?></p>
                        <?php endforeach ?>

                        <a href="<?php echo htmlspecialchars($utilizador['site_url']); ?>" target="_blank" class="link-site">
                            🔗 <?php echo htmlspecialchars($utilizador['site_texto']); ?>
                        </a>

                        <div class="seguidores">
                            <span class="usuarios"><img src="./img/Logo - LUX.png" alt="User"></span>
                            <span>Seguido/a por <strong>kiarafurlanetto</strong></span>
                        </div>
                    </div>
                </div>

            </header>

            <div class="acoes">
                <button class="botao botao-azul">Seguir</button>
                <button class="botao botao-preto">Enviar mensagem</button>
                <button class="botao botao-preto botao-icone"><i class="fa-solid fa-user-plus"></i></button>
            </div>

            <section class="destaques">
                <?php foreach ($destaques as $destaque): ?>
                    <div class="destaques-itens">
                        <div class="destaque-stories">
                            <span><?php echo $destaque['icone']; ?></span>
                        </div>
                        <span class="destaques-titulo"><?php echo htmlspecialchars($destaque['titulo']); ?></span>
                    </div>
                <?php endforeach; ?>
            </section>

            <nav class="abas">
                <a href="#" class="aba ativa"><i class="fa-solid fa-table-cells"></i></a>
                <a href="#" class="aba"><i class="fa-solid fa-clapperboard"></i></a>
                <a href="#" class="aba"><i class="fa-solid fa-repeat"></i></a>
                <a href="#" class="aba"><i class="fa-regular fa-id-badge"></i></a>
            </nav>

            <section class="postagens">
                <?php foreach ($postagens as $img_post): ?>
                    <div class="postagem-itens">
                        <img src="<?php echo htmlspecialchars($img_post); ?>" alt="Posts">
                    </div>
                <?php endforeach; ?>

            </section>

        </div>
    </main>

    <div class="chat">
        <i class="fa-regular fa-paper-plane"></i>
        <span>Mensagens</span>
    </div>

</body>

</html>