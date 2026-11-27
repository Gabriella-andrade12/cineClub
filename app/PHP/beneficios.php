<?php

session_start();

require_once "../init.php";

if (isset($_GET['u'])) {
    $_SESSION['id_usuario'] = (int) $_GET['u'];
}
$id_usuario = $_SESSION['id_usuario'] ?? 1;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_beneficio'])) {
    $id_beneficio = (int) $_POST['id_beneficio'];

    try {
        $pdo->beginTransaction();

        $st = $pdo->prepare('SELECT qntd_pontos FROM pontos_usuario WHERE id_usuario = ? FOR UPDATE');
        $st->execute([$id_usuario]);
        $pontos = (int) $st->fetchColumn();

        $st = $pdo->prepare("SELECT pontos_beneficio, nome_beneficio FROM beneficios
                             WHERE id_beneficio = ? AND beneficio_ativo = 'SIM'");
        $st->execute([$id_beneficio]);
        $b = $st->fetch();

        if (!$b) {
            $_SESSION['msg'] = ['erro', 'Benefício indisponível.'];
        } elseif ($pontos < $b['pontos_beneficio']) {
            $_SESSION['msg'] = ['erro', 'Pontos insuficientes para este benefício.'];
        } else {
            $st = $pdo->prepare('UPDATE pontos_usuario SET qntd_pontos = qntd_pontos - ? WHERE id_usuario = ?');
            $st->execute([$b['pontos_beneficio'], $id_usuario]);

            $st = $pdo->prepare('INSERT INTO resgate_beneficio (pontos_utilizados, data_resgate, id_usuario, id_beneficio)
                                 VALUES (?, CURDATE(), ?, ?)');
            $st->execute([$b['pontos_beneficio'], $id_usuario, $id_beneficio]);

            $_SESSION['msg'] = ['ok', 'Você resgatou: ' . $b['nome_beneficio'] . '!'];
        }

        $pdo->commit();
    } catch (Exception $e) {
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }
        $_SESSION['msg'] = ['erro', 'Não foi possível resgatar. Tente novamente.'];
    }

    header('Location: index.php');
    exit;
}

$st = $pdo->prepare('SELECT qntd_pontos FROM pontos_usuario WHERE id_usuario = ?');
$st->execute([$id_usuario]);
$saldo = (int) $st->fetchColumn();

$beneficios = $pdo->query("SELECT * FROM beneficios WHERE beneficio_ativo = 'SIM' ORDER BY pontos_beneficio")->fetchAll();

$msg = $_SESSION['msg'] ?? null;
unset($_SESSION['msg']);

?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CINELUX - Benefícios</title>
    <link rel="stylesheet" href="./CSS/beneficios.css">
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
            <a href="./combos.php" class="navegacao-opcoes">Combos</a>
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

    <main class="pontos">
        <section class="saldo">
            <i class="fa-solid fa-crown saldo-icone"></i>
            <span>Seu Saldo:</span>
            <strong><?= number_format($saldo, 0, ',', '.') ?> PTS</strong>
        </section>

        <header class="recompensas">
            <span class="etiqueta-recompensas">PROGRAMA DE RECOMPENSAS</span>
            <h1>ACUMULE PONTOS & DESCUBRA MAIS BENEFÍCIOS NO <span>CLUBE LUX</span></h1>
            <p class="subtitulo-recompensas">
                Suas idas ao cinema e compras na bomboniere viram vantagens exclusivas. Troque seus pontos acumulados por ingressos grátis, combos e experiências únicas.
            </p>
        </header>

        <?php if ($msg): ?>
            <div class="aviso <?= htmlspecialchars($msg[0]) ?>">
                <i class="fa-solid <?= $msg[0] === 'ok' ? 'fa-circle-check' : 'fa-circle-exclamation' ?>"></i>
                <span><?= htmlspecialchars($msg[1]) ?></span>
            </div>
        <?php endif; ?>

        <section class="secao-como-funciona">
            <h2 class="titulo-central">COMO FUNCIONA</h2>
            <div class="grade-passos">
                <article class="passo-cartao">
                    <span class="passo-numero">1</span>
                    <h3>COMPRE & ACUMULE</h3>
                    <p>A cada R$ 1,00 em ingressos ou bomboniere você ganha 1 ponto. Válido para compras online ou na bilheteria.</p>
                </article>
                <article class="passo-cartao">
                    <span class="passo-numero">2</span>
                    <h3>ACOMPANHE NO PERFIL</h3>
                    <p>Acesse sua conta para conferir seu extrato de pontos. Assinantes de planos LUX ganham multiplicadores de até 2x.</p>
                </article>
                <article class="passo-cartao">
                    <span class="passo-numero">3</span>
                    <h3>TROQUE POR PRÊMIOS</h3>
                    <p>Resgate seus pontos por ingressos 2D, upgrades IMAX, combos da bomboniere e pré-estreias exclusivas.</p>
                </article>
            </div>
        </section>

        <section class="secao-catalogo">
            <div class="cabecalho-catalogo">
                <span class="etiqueta-recompensas">TROQUE SEUS PONTOS</span>
                <h2 class="titulo-secao">CATÁLOGO DE RECOMPENSAS</h2>
            </div>

            <?php if (empty($beneficios)): ?>
                <p class="sem-beneficios">Nenhum benefício ativo disponível no momento. Volte em breve!</p>
            <?php else: ?>
                <div class="lista">
                    <?php foreach ($beneficios as $b):
                        $custo    = (int) $b['pontos_beneficio'];
                        $liberado = $saldo >= $custo;
                    ?>
                        <article class="cartao <?= $liberado ? '' : 'bloqueado' ?>">
                            <div class="cartao-topo">
                                <div class="cartao-icone">
                                    <i class="fa-solid fa-ticket"></i>
                                </div>
                                <span class="quantidade-pontos">• <?= number_format($custo, 0, ',', '.') ?> PTS</span>
                            </div>

                            <h3><?= htmlspecialchars($b['nome_beneficio']) ?></h3>
                            <p><?= htmlspecialchars($b['descricao_beneficio']) ?></p>

                            <?php if ($liberado): ?>
                                <form method="post" class="resgate">
                                    <input type="hidden" name="id_beneficio" value="<?= (int) $b['id_beneficio'] ?>">
                                    <button type="submit" class="botao">RESGATAR</button>
                                </form>
                            <?php else: ?>
                                <button type="button" class="botao" disabled>Faltam <?= number_format($custo - $saldo, 0, ',', '.') ?> pts</button>
                            <?php endif; ?>
                        </article>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </section>

        <section class="secao-missoes">
            <div class="cabecalho-catalogo">
                <span class="etiqueta-recompensas">ACELERE SEUS GANHOS</span>
                <h2 class="titulo-secao">COMO GANHAR MAIS PONTOS</h2>
            </div>

            <div class="painel-missoes">
                <div class="missao-item">
                    <div class="missao"><i class="fa-solid fa-check"></i></div>
                    <div class="missao-informacao">
                        <div class="missao-titulo-linha">
                            <h4>Complete seu Perfil</h4>
                            <span class="missao-pontos">+50 PTS</span>
                        </div>
                        <p>Preencha todos os seus dados cadastrais de cinéfilo.</p>
                    </div>
                </div>

                <div class="missao-item">
                    <div class="missao"><i class="fa-solid fa-check"></i></div>
                    <div class="missao-informacao">
                        <div class="missao-titulo-linha">
                            <h4>Aniversário Premiado</h4>
                            <span class="missao-pontos">+250 PTS</span>
                        </div>
                        <p>Cadastre sua data de nascimento para ganhar pontos no seu dia.</p>
                    </div>
                </div>

                <div class="missao-item">
                    <div class="missao"><i class="fa-solid fa-check"></i></div>
                    <div class="missao-informacao">
                        <div class="missao-titulo-linha">
                            <h4>Avalie um Filme</h4>
                            <span class="missao-pontos">+100 PTS</span>
                        </div>
                        <p>Deixe sua opinião sobre o último filme assistido na plataforma.</p>
                    </div>
                </div>

                <div class="missao-item">
                    <div class="missao"><i class="fa-solid fa-check"></i></div>
                    <div class="missao-informacao">
                        <div class="missao-titulo-linha">
                            <h4>Indique um Amigo</h4>
                            <span class="missao-pontos">+150 PTS</span>
                        </div>
                        <p>Traga um amigo para o Clube LUX e ganhe após a primeira compra.</p>
                    </div>
                </div>
            </div>
        </section>

        <section class="faixa-de-informacoes2">
            <div class="facilidades-lista">
                <div class="facilidade-item">
                    <i class="fa-solid fa-mobile-screen-button"></i>
                    <span>Ingresso Digital</span>
                </div>
                <div class="facilidade-item">
                    <i class="fa-solid fa-bolt"></i>
                    <span>Sem Filas</span>
                </div>
                <div class="facilidade-item">
                    <i class="fa-solid fa-ticket"></i>
                    <span>Pré-vendas Exclusivas</span>
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