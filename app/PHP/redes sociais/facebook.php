<?php

$foto_avatar_padrao = 'img/Logo - LUX.png';
$foto_capa_padrao = 'img/cinema.jpg';

$empresa = [
    'nome'        => 'LUX Cinema & Streaming',
    'verificado'  => true,
    'seguidores'  => '1.2M seguidores',
    'bio'         => 'Bringing the best of movies & streaming closer together.',
    'categoria'   => 'Empresa de Cinema e Entretenimento',
    'site_texto'  => 'lux.link/cinemas',
    'site_url'    => 'https://lux.com.br'
];

?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($empresa['nome']); ?> | Facebook</title>
    <link rel="stylesheet" href="./CSS/facebook.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="shortcut icon" href="./img/Facebook - Logo.png" type="image/x-icon">
</head>

<body>

    <header class="cabecalho">
        <div class="faixa-cabecalho">
            <div class="logo">facebook</div>

            <div class="login">
                <div class="informacoes">
                    <input type="text" placeholder="Email ou telefone">
                </div>
                <div class="informacoes">
                    <input type="password" placeholder="Senha">
                </div>
                <button class="botao-login">Entrar</button>
                <a href="#" class="senha">Esqueceu a conta?</a>
            </div>
        </div>
    </header>

    <div class="capa-do-perfil">
        <div class="capa-perfil">

            <div class="imagem-da-capa">
                <img src="<?php echo htmlspecialchars($foto_capa_padrao); ?>" alt="Capa LUX" class="capa-LUX">
                <form action="facebook.php" method="POST" enctype="multipart/form-data" class="form-capa">
                    <input type="file" name="foto_capa" id="capa-input" accept="image/*" onchange="this.form.submit()" style="display: none;">
                    <label for="capa-input" class="botao-capa"><i class="fa-solid fa-camera"></i> Editar Capa</label>
                </form>
                <div class="foto-de-perfil">
                    <img src="<?php echo htmlspecialchars($foto_avatar_padrao); ?>" alt="Avatar LUX" class="perfil-img">
                    <form action="facebook.php" method="POST" enctype="multipart/form-data" class="form-perfil">
                        <input type="file" name="foto_avatar" id="perfil-input" accept="image/*" onchange="this.form.submit()" style="display: none;">
                        <label for="perfil-input" class="botao-perfil"><i class="fa-solid fa-camera"></i></label>
                    </form>
                </div>
            </div>

            <div class="verificado-nome">
                <div class="nome">
                    <h1><?php echo htmlspecialchars($empresa['nome']); ?></h1>
                    <?php if ($empresa['verificado']): ?>
                        <i class="fa-solid fa-circle-check verificado"></i>
                    <?php endif; ?>
                </div>
                <p class="seguidores"><?php echo htmlspecialchars($empresa['seguidores']); ?></p>
            </div>

            <div class="navegacao">
                <div class="abas">
                    <a href="#" class="aba ativa">Posts</a>
                    <a href="#" class="aba">Sobre</a>
                    <a href="#" class="aba">Reels</a>
                    <a href="#" class="aba">Fotos</a>
                </div>
                <div class="abas">
                    <button class="botao"><i class="fa-solid fa-ellipsis"></i></button>
                </div>
            </div>
        </div>
    </div>

    <main class="faixa">
        <div class="grade">
            <aside class="apresentacao">
                <div class="bloco-apredentacao">
                    <h3>Apresentação</h3>
                    <p class="bio"><?php echo htmlspecialchars($empresa['bio']); ?></p>

                    <div class="detalhes">
                        <div class="perfil">
                            <i class="fa-solid fa-address-card"></i>
                            <span>Perfil • <?php echo htmlspecialchars($empresa['categoria']); ?></span>
                        </div>
                        <div class="perfil">
                            <i class="fa-solid fa-briefcase"></i>
                            <span>Cinema & Streaming na empresa <strong>LUX Cinemas</strong></span>
                        </div>
                        <div class="perfil">
                            <i class="fa-solid fa-globe"></i>
                            <a href="<?php echo htmlspecialchars($empresa['site_url']); ?>" target="_blank"><?php echo htmlspecialchars($empresa['site_texto']); ?></a>
                        </div>
                        <div class="perfil">
                            <i class="fa-solid fa-location-dot"></i>
                            <span>Salas de Cinema & Plataforma Digital</span>
                        </div>
                    </div>
                </div>
            </aside>

            <section class="posts">
                <article class="grade-postagem">
                    <div class="usuario-cabecalho">
                        <img src="<?php echo htmlspecialchars($foto_avatar_padrao); ?>" alt="LUX" class="perfil-pequeno">
                        <div class="usuario-info">
                            <h4><?php echo htmlspecialchars($empresa['nome']); ?> <?php if ($empresa['verificado']): ?><i class="fa-solid fa-circle-check verificado"></i><?php endif; ?></h4>
                            <span class="tempo">1 de novembro às 14:27 • <i class="fa-solid fa-earth-americas"></i></span>
                        </div>
                    </div>

                    <div class="pergunta">
                        <p>🍿 As melhores estreias nas salas IMAX e no streaming LUX! Qual é a sua escolha para hoje?</p>
                        <div class="grade-posts">
                            <img src="https://images.unsplash.com/photo-1536440136628-849c177e76a1?auto=format&fit=crop&w=800&q=80" alt="Filme 1">
                            <img src="https://images.unsplash.com/photo-1517604931442-7e0c8ed2963c?auto=format&fit=crop&w=800&q=80" alt="Filme 2">
                        </div>
                    </div>
                </article>
            </section>

        </div>
    </main>

</body>

</html>