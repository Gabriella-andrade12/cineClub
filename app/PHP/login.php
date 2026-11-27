<?php
require_once "../init.php";   // fornece a conexão PDO ($pdo), crud() e inicia a sessão

if (session_status() === PHP_SESSION_NONE) { session_start(); }

// Já autenticado: vai direto para o catálogo
if (!empty($_SESSION['id_usuario'])) { header('Location: inicio.php'); exit; }

if (!function_exists('h')) {
  function h($s) { return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8'); }
}

$erro  = '';
$email = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $email = trim($_POST['email'] ?? '');
  $senha = $_POST['senha'] ?? '';
  if ($email === '' || $senha === '') {
    $erro = 'Informe e-mail e senha.';
    } else {
      
      // Busca o usuário pelo e-mail
      $usuario = read($pdo, 'usuario', 'email = ?', [$email]);
    // Aceita tanto senha com password_hash() (cadastro) quanto texto puro (seeds)
    if ($usuario && (password_verify($senha, $usuario['senha']) || $senha === $usuario['senha'])) {
      session_regenerate_id(true);
      $_SESSION['id_usuario'] = (int)$usuario['id_usuario'];
      $_SESSION['nome']       = $usuario['nome'];
      header('Location: inicio.php');
      exit;
    }
    $erro = 'E-mail ou senha incorretos.';
  }
}

$aviso_estilo = 'padding:10px 14px;border-radius:10px;margin-bottom:14px;font-size:.9rem;';
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>CINELUX | Login</title>
  <link rel="stylesheet" href="./CSS/login.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link rel="shortcut icon" href="./img/Logo - LUX.png" type="image/x-icon">
</head>

<body>

  <!-- Ícones usados pelos campos (<use href="#icone-...">) -->
  <svg width="0" height="0" style="position:absolute" aria-hidden="true">
    <symbol id="icone-email" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
      <rect x="3" y="5" width="18" height="14" rx="2"></rect>
      <path d="M3 7l9 6 9-6"></path>
    </symbol>
    <symbol id="icone-cadeado" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
      <rect x="5" y="11" width="14" height="10" rx="2"></rect>
      <path d="M8 11V7a4 4 0 0 1 8 0v4"></path>
    </symbol>
  </svg>

  <header class="cabecalho">
    <a href="./inicio.php" class="logo">
      <img src="./img/Logo - LUX.png" alt="Logo LUX Cinema" class="logo-img">
    </a>
  </header>

  <main id="conteudo" class="faixa-principal">
    <div class="fita-de-filme" aria-hidden="true"></div>

    <section class="apresentacao">
      <h1 class="apresentacao-titulo">
        Sua próxima<br>história começa<br>
        <span class="titulo-destaque">aqui.</span>
      </h1>
      <p class="apresentacao-texto">
        Entre para descobrir filmes, sessões<br>
        e experiências inesquecíveis
      </p>
      <span class="apresentacao-linha" aria-hidden="true"></span>
    </section>

    <section class="cartao-login" aria-labelledby="login-titulo">
      <h2 id="login-titulo" class="login-titulo">Bem-vindo de volta!</h2>
      <p class="login-subtitulo">Acesse sua conta</p>

      <?php if (($_GET['cadastro'] ?? '') === 'ok'): ?>
        <div role="status" style="<?= $aviso_estilo ?>background:#1f3d2e;border:1px solid #2f7a52;">Cadastro realizado! Faça login.</div>
      <?php endif; ?>
      <?php if (($_GET['saiu'] ?? '') === '1'): ?>
        <div role="status" style="<?= $aviso_estilo ?>background:#1f3d2e;border:1px solid #2f7a52;">Você saiu da sua conta.</div>
      <?php endif; ?>
      <?php if ($erro !== ''): ?>
        <div role="alert" style="<?= $aviso_estilo ?>background:#4a1f25;border:1px solid #a33a48;"><?= h($erro) ?></div>
      <?php endif; ?>

      <form class="formulario" action="login.php" method="post" novalidate>

        <div class="campo">
          <label for="email">E-mail</label>
          <div class="campo-entrada">
            <svg class="campo-icone" aria-hidden="true">
              <use href="#icone-email" />
            </svg>
            <input id="email" name="email" type="email" placeholder="Digite seu e-mail" autocomplete="email"
              inputmode="email" value="<?= h($email) ?>" required>
          </div>
        </div>

        <div class="campo">
          <label for="senha">Senha</label>
          <div class="campo-entrada">
            <svg class="campo-icone" aria-hidden="true">
              <use href="#icone-cadeado" />
            </svg>
            <input id="senha" name="senha" type="password" placeholder="Digite sua senha"
              autocomplete="current-password" required>
          </div>
          <a class="esqueceu-senha" href="#recuperar-senha">Esqueceu sua senha?</a>
        </div>

        <button class="botao botao-mustard" type="submit">Entrar</button>

        <div class="divisor" role="separator"><span>Ou</span></div>

        <a class="botao botao-contorno" href="./cadastro.php">Criar Conta</a>
      </form>
    </section>
  </main>

  <footer class="rodape">
    <a href="./inicio.php" class="logo">
      <img src="./img/Logo - LUX.png" alt="Logo LUX Cinema" class="logo-img">
    </a>
    <nav class="rodape-links" aria-label="Links legais">
      <a href="#termos">Termos de Uso</a>
      <a href="#privacidade">Privacidade</a>
      <a href="#trocas">Política de Trocas</a>
    </nav>
    <p class="rodape-direitos">LUX Media® 2026. Todos os direitos reservados.</p>
  </footer>

</body>

</html>
