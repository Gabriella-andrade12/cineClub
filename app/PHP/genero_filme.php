<?php
require_once "../init.php";   // fornece a conexão PDO ($pdo) e inicia a sessão
require_once "./generos_lib.php";

if (session_status() === PHP_SESSION_NONE) { session_start(); }
if (empty($_SESSION['id_usuario'])) { header('Location: login.php'); exit; }

/* Filtros cumulativos: ?tipo=...&genero=... */
$tipo          = lx_tipo($_GET['tipo'] ?? 'cartaz');
$lista_generos = lx_generos($pdo);
$genero        = lx_genero($_GET['genero'] ?? '', $lista_generos);

/* Sem gênero válido: volta para a página inicial (mantendo a aba) */
if ($genero === '') { header('Location: ' . lx_url_genero($tipo, '')); exit; }

$filmes = lx_buscar_filmes($pdo, $tipo, $genero);
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= h($genero) ?> - LUX</title>
  <link rel="stylesheet" href="./CSS/inicio.css">
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

  <main class="faixa-principal">

    <section class="generos">
      <div class="genero">
        <i class="fa-solid fa-sliders"></i>
        <i class="fa-solid fa-list-ul"></i>
      </div>

<?php lx_barra_generos($lista_generos, $tipo, $genero); ?>
    </section>

    <section class="movies-container">
<?php lx_abas_e_grade($filmes, $tipo, $genero); ?>
    </section>

  </main>

  <footer class="rodape">
    <div class="rodape-logo">
      <img src="./img/Logo - LUX.png" alt="LUX Rodapé Logo">
    </div>

    <div class="grade-do-rodape">
      <div class="rodape-informacoes">
        <a href="./inicio.php">Filmes em Cartaz</a>
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
