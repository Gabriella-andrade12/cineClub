<?php

$foto_perfil_padrao = 'img/Logo - LUX.png';

$perfil = [
    'username'    => 'luxcinemas',
    'nome'        => 'LUX Cinema & Streaming',
    'verificado'  => true,
    'seguindo'    => '878',
    'seguidores'  => '14.6M',
    'curtidas'    => '106.7M',
    'bio'         => 'Sua próxima grande estreia te espera na LUX. Descubra novidades do cinema e streaming todos os dias. 🍿🎬',
    'site_texto'  => 'lux.link/cinemas',
    'site_url'    => 'https://lux.com.br'
];

$reproducoes = [
    [
        'titulo' => 'Em Cartaz nas Salas',
        'quantidade'    => '10 publicações',
        'capa'  => 'https://images.unsplash.com/photo-1536440136628-849c177e76a1?auto=format&fit=crop&w=300&q=80'
    ],
    [
        'titulo' => 'Exclusivos Streaming',
        'quantidade'    => '8 publicações',
        'capa'  => 'https://images.unsplash.com/photo-1478720568477-152d9b164e26?auto=format&fit=crop&w=300&q=80'
    ],
    [
        'titulo' => 'LUX Awards 2026',
        'quantidade'    => '20 publicações',
        'capa'  => 'https://images.unsplash.com/photo-1517604931442-7e0c8ed2963c?auto=format&fit=crop&w=300&q=80'
    ]
];

$videos = [
    [
        'fixado' => true,
        'texto'  => 'Seus assuntos de interesse chegarem até você. Descubra na LUX Cinema...',
        'visualizacao'  => '16.7M',
        'capa'  => 'https://images.unsplash.com/photo-1536440136628-849c177e76a1?auto=format&fit=crop&w=500&q=80'
    ],
    [
        'fixado' => true,
        'texto'  => 'Deixe seus interesses virem até você. O melhor do cinema na LUX...',
        'visualizacao'  => '14.9M',
        'capa'  => 'https://images.unsplash.com/photo-1478720568477-152d9b164e26?auto=format&fit=crop&w=500&q=80'
    ],
    [
        'fixado' => true,
        'texto'  => 'Uma nova descoberta pode fazer uma nova ideia florescer. Assista agora...',
        'visualizacao'  => '17.6M',
        'capa'  => 'https://images.unsplash.com/photo-1517604931442-7e0c8ed2963c?auto=format&fit=crop&w=500&q=80'
    ],
    [
        'fixado' => false,
        'texto'  => 'Seus favoritos já estão na disputa. Vote no LUX Awards 2026! 🏆',
        'visualizacao'  => '2424',
        'capa'  => 'https://images.unsplash.com/photo-1585829365295-ab7cd400c167?auto=format&fit=crop&w=500&q=80'
    ],
    [
        'fixado' => false,
        'texto'  => 'Você abriu o TikTok e descobriu um novo item pra sua lista de desejos 🛍️',
        'visualizacao'  => '5506',
        'capa'  => 'https://images.unsplash.com/photo-1485846234645-a62644f84728?auto=format&fit=crop&w=500&q=80'
    ],
    [
        'fixado' => false,
        'texto'  => 'Você acompanhou, compartilhou e agora é hora de torcer! Seus favoritos...',
        'visualizacao'  => '17.5K',
        'capa'  => 'https://images.unsplash.com/photo-1518173946687-a4c8a383392e?auto=format&fit=crop&w=500&q=80'
    ]
];

?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $perfil['nome']; ?> (@<?php echo $perfil['username']; ?>)</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="./CSS/tiktok.css">
    <link rel="shortcut icon" href="./img/TikTok - Logo.png" type="image/x-icon">
</head>

<body>

    <main class="faixa-principal">
        <header class="faixa">

            <div class="foto-de-perfil">
                <img src="<?php echo htmlspecialchars($foto_perfil_padrao); ?>" alt="<?php echo htmlspecialchars($perfil['nome']); ?>">
                <label for="foto-de-perfil" class="camera-foto"><i class="fa-solid fa-camera"></i></label>
            </div>

            <div class="informacoes-perfil">

                <div class="nome-de-usuario">
                    <h1><?php echo htmlspecialchars($perfil['username']); ?></h1>
                    <?php if ($perfil['verificado']): ?>
                        <i class="fa-solid fa-circle-check verificado"></i>
                    <?php endif; ?></h1>
                </div>

                <p class="nome"><?php echo htmlspecialchars($perfil['nome']); ?></p>

                <div class="informacoes-seguidores">
                    <span><strong><?php echo $perfil['seguindo']; ?></strong> Seguindo</span>
                    <span><strong><?php echo $perfil['seguidores']; ?></strong> Seguidores</span>
                    <span><strong><?php echo $perfil['curtidas']; ?></strong> Curtidas</span>
                </div>

                <div class="botoes">
                    <button class="botao botao-acionado">Seguir</button>
                    <button class="botao botao-nao-acionado">Mensagem</button>
                    <button class="botao botao-nao-acionado icone"><i class="fa-solid fa-user-plus"></i></button>
                    <button class="botao botao-nao-acionado icone"><i class="fa-solid fa-share-nodes"></i></button>
                    <button class="botao botao-nao-acionado icone"><i class="fa-solid fa-ellipsis"></i></button>
                </div>


                <p class="biografia">
                    <?php echo htmlspecialchars($perfil['bio']); ?>
                </p>


                <a href="<?php echo htmlspecialchars($perfil['site_url']); ?>" target="_blank" class="link">
                    <i class="fa-solid fa-link"></i> <?php echo htmlspecialchars($perfil['site_texto']); ?>
                </a>

            </div>

        </header>


        <div class="abas-esquerda">
            <div class="abas">
                <a href="#" class="aba ativo"><i class="fa-solid fa-bars-staggered"></i> Vídeos</a>
                <a href="#" class="aba"><i class="fa-solid fa-repeat"></i> Republicações</a>
                <a href="#" class="aba"><i class="fa-regular fa-heart"></i> Curtido</a>
            </div>

            <div class="abas-direita">
                <button class="abas-botao ativo">Mais recentes</button>
                <button class="abas-botao">Popular</button>
                <button class="abas-botao">Mais antigos</button>
            </div>
        </div>

        <section class="reproducoes">
            <h2>Listas de reprodução</h2>

            <div class="grade-de-reproducoes">
                <?php foreach ($reproducoes as $reproducao): ?>

                    <div class="video">
                        <div class="video-capa">
                            <img src="<?php echo htmlspecialchars($reproducao['capa']); ?>" alt="<?php echo htmlspecialchars($reproducao['titulo']); ?>1">
                        </div>
                        <div class="video-info">
                            <h4><?php echo htmlspecialchars($reproducao['titulo']); ?></h4>
                            <p><?php echo htmlspecialchars($reproducao['quantidade']); ?></p>
                        </div>
                    </div>
                <?php endforeach ?>

            </div>
        </section>

        <section class="videos">
            <h2>Vídeos</h2>

            <div class="grade-de-videos">
                <?php foreach ($videos as $video): ?>

                    <div class="f-videos">
                        <div class="capa">
                            <img src="<?php echo htmlspecialchars($video['capa']); ?>" alt="Vídeo TikTok">
                            <?php if ($video['fixado']): ?>
                                <span class="fixado">Fixado</span>
                            <?php endif; ?>
                            <div class="info">
                                <p class="info-texto"><?php echo htmlspecialchars($video['texto']); ?></p>
                                <div class="visualizacao"><i class="fa-regular fa-play"></i> <?php echo htmlspecialchars($video['visualizacao']); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
        </section>

    </main>

</body>

</html>