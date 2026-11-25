<?php
require_once "../init.php";   // fornece a conexão PDO ($pdo), crud() e inicia a sessão

if (session_status() === PHP_SESSION_NONE) { session_start(); }
if (empty($_SESSION['id_usuario'])) { header('Location: login.php'); exit; }
$uid = (int)$_SESSION['id_usuario'];

/* ---------------- Helpers ---------------- */
if (!function_exists('h')) {
  function h($s) { return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8'); }
}
function brl($v) { return 'R$ ' . number_format((float)$v, 2, ',', '.'); }

/* ---------------- Carrinho (sessão) ----------------
 * $_SESSION['carrinho'] = [
 *   'ingressos'   => [ chave => ['id_sessao','assento','tipo','valor','filme','dia','hora','sala'] ],
 *   'produtos'    => [ id_produto => ['nome','valor','qtd'] ],
 *   'cupom'       => código ou null,
 *   'id_endereco' => id ou null ]
 */
$c = $_SESSION['carrinho'] ?? [];
$ingressos = $c['ingressos'] ?? [];
$produtos  = $c['produtos']  ?? [];
if (!$ingressos && !$produtos) { header('Location: carrinho.php'); exit; }

// Produtos da bomboniere exigem endereço de entrega escolhido no carrinho
if ($produtos && empty($c['id_endereco'])) { header('Location: carrinho.php'); exit; }

$subtotal = 0.0;
foreach ($ingressos as $i) { $subtotal += $i['valor']; }
foreach ($produtos as $p)  { $subtotal += $p['valor'] * $p['qtd']; }
$subtotal = round($subtotal, 2);

/* ---------------- Cupom (campo GET do resumo) ----------------
 * REGRA: cupom existente, ativo, dentro da validade e com valor mínimo atingido. */
function lx_cupom(PDO $pdo, $codigo, $subtotal) {
  $st = $pdo->prepare("SELECT * FROM cupom WHERE UPPER(codigo) = UPPER(?) LIMIT 1");
  $st->execute([$codigo]);
  $cp = $st->fetch();
  if (!$cp) return [null, 'Cupom inexistente.'];
  if (!in_array(strtoupper((string)$cp['cupom_ativo']), ['SIM', '1', 'S', 'ATIVO'], true)) return [null, 'Cupom inativo.'];
  if (!empty($cp['data_validade']) && $cp['data_validade'] < date('Y-m-d')) return [null, 'Cupom vencido.'];
  if ($subtotal < (float)$cp['valor_minimo_pedido']) return [null, 'Pedido mínimo do cupom: ' . brl($cp['valor_minimo_pedido']) . '.'];
  return [$cp, null];
}

$msg_cupom = ''; $ok_cupom = false;
if (isset($_GET['cupom']) && trim($_GET['cupom']) !== '') {
  list($cp, $erro_cp) = lx_cupom($pdo, trim($_GET['cupom']), $subtotal);
  if ($cp) { $_SESSION['carrinho']['cupom'] = $cp['codigo']; $msg_cupom = 'Cupom aplicado!'; $ok_cupom = true; }
  else     { $_SESSION['carrinho']['cupom'] = null;           $msg_cupom = $erro_cp; }
}

// Totais sempre recalculados no servidor (cupom revalidado a cada carregamento)
$desconto = 0.0;
$cupom_ativo = null;
if (!empty($_SESSION['carrinho']['cupom'])) {
  list($cp, $erro_cp) = lx_cupom($pdo, $_SESSION['carrinho']['cupom'], $subtotal);
  if ($cp) {
    $cupom_ativo = $cp['codigo'];
    $tipo_cp = strtolower((string)$cp['tipo_desconto']);
    $desconto = (strpos($tipo_cp, 'perc') !== false || strpos($tipo_cp, '%') !== false)
      ? $subtotal * ((float)$cp['valor_desconto'] / 100)
      : (float)$cp['valor_desconto'];
    $desconto = round(min($desconto, $subtotal), 2);
  } else {
    $_SESSION['carrinho']['cupom'] = null;
  }
}
$total = round($subtotal - $desconto, 2);

/* ---------------- Dados do usuário (somente leitura) ---------------- */
$st = $pdo->prepare("SELECT nome, email, cpf FROM usuario WHERE id_usuario = ?");
$st->execute([$uid]);
$usuario = $st->fetch() ?: [];
$cpf = preg_replace('/\D/', '', $usuario['cpf'] ?? '');
$cpf_fmt = strlen($cpf) === 11 ? substr($cpf, 0, 3) . '.' . substr($cpf, 3, 3) . '.' . substr($cpf, 6, 3) . '-' . substr($cpf, 9) : $cpf;

/* ---------------- Confirmação do pagamento (POST) ---------------- */
$erro = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $escolha = $_POST['forma_pagamento'] ?? 'pix';
  if ($escolha === 'pix') {
    $forma = 'Pix';
  } elseif ($escolha === 'cartao') {
    $forma = (($_POST['tipo_cartao'] ?? '') === 'debito') ? 'Cartão de Débito' : 'Cartão de Crédito';
    // Validação básica dos campos do cartão (nada é gravado: apenas simulação acadêmica)
    $num = preg_replace('/\D/', '', $_POST['card_num'] ?? '');
    $exp = trim($_POST['card_exp'] ?? '');
    $cvv = preg_replace('/\D/', '', $_POST['card_cvv'] ?? '');
    if (strlen($num) < 13 || strlen($num) > 19)                       $erro = 'Número do cartão inválido.';
    elseif (trim($_POST['card_name'] ?? '') === '')                    $erro = 'Informe o nome impresso no cartão.';
    elseif (!preg_match('/^(0[1-9]|1[0-2])\/(\d{2})$/', $exp, $m)
            || ((int)('20' . $m[2]) . $m[1]) < (int)date('Ym'))        $erro = 'Validade inválida ou cartão vencido.';
    elseif (strlen($cvv) < 3 || strlen($cvv) > 4)                      $erro = 'CVV inválido.';
  } else {
    $erro = 'Selecione a forma de pagamento.';
  }

  if ($erro === '') {
    /*
     * REGRA CRÍTICA: venda, item_venda, ingressos e pagamento só são gravados aqui,
     * dentro de uma transação (tudo ou nada).
     */
    try {
      $pdo->beginTransaction();

      // Reconfere os assentos dentro da transação (evita venda duplicada do mesmo lugar)
      $stAssento = $pdo->prepare("SELECT id_ingresso FROM ingressos WHERE id_sessao = ? AND assento = ? FOR UPDATE");
      foreach ($ingressos as $i) {
        $stAssento->execute([$i['id_sessao'], $i['assento']]);
        if ($stAssento->fetch()) throw new RuntimeException('O assento ' . $i['assento'] . ' acabou de ser ocupado. Remova-o do carrinho.');
      }

      $id_endereco = $produtos ? (int)$_SESSION['carrinho']['id_endereco'] : null;
      $id_produto  = $produtos ? (int)array_key_first($produtos) : null;

      // 2. venda
      $stVenda = $pdo->prepare("INSERT INTO venda (id_usuario, data_pedido, valor_pedido, id_endereco, id_produto) VALUES (?, NOW(), ?, ?, ?)");
      $stVenda->execute([$uid, $total, $id_endereco, $id_produto]);
      $id_venda = (int)$pdo->lastInsertId();

      // 3. item_venda
      foreach ($produtos as $idp => $p) {
        create($pdo, 'item_venda', [
          'id_venda'       => $id_venda,
          'id_produto'     => (int)$idp,
          'quantidade'     => (int)$p['qtd'],
          'valor_unitario' => $p['valor'],
        ]);
      }

      // 4. ingressos
      foreach ($ingressos as $i) {
        create($pdo, 'ingressos', [
          'assento'        => $i['assento'],
          'valor_ingresso' => $i['valor'],
          'id_venda'       => $id_venda,
          'id_sessao'      => $i['id_sessao'],
        ]);
      }

      // 5. pagamento
      $stPag = $pdo->prepare("INSERT INTO pagamento (forma_pagamento, status_pagamento, data_pagamento, id_venda) VALUES (?, 'Concluído', NOW(), ?)");
      $stPag->execute([$forma, $id_venda]);

      // Fidelidade: 1 ponto por R$ 1 pago (usado em beneficios.php)
      $pts = (int)floor($total);
      if ($pts > 0) {
        $stPts = $pdo->prepare("SELECT qntd_pontos FROM pontos_usuario WHERE id_usuario = ? FOR UPDATE");
        $stPts->execute([$uid]);
        if ($stPts->fetch()) {
          $stUpdPts = $pdo->prepare("UPDATE pontos_usuario SET qntd_pontos = qntd_pontos + ? WHERE id_usuario = ?");
          $stUpdPts->execute([$pts, $uid]);
        } else {
          create($pdo, 'pontos_usuario', ['qntd_pontos' => $pts, 'id_usuario' => $uid]);
        }
      }

      // 6. commit, limpa o carrinho e segue para a confirmação
      $pdo->commit();
      unset($_SESSION['carrinho']);
      header('Location: confirmacao.php?id=' . $id_venda);
      exit;
    } catch (Throwable $e) {
      if ($pdo->inTransaction()) {
        $pdo->rollBack();
      }
      $erro = ($e instanceof RuntimeException) ? $e->getMessage() : 'Não foi possível concluir o pagamento. Tente novamente.';
    }
  }
}

// Código Pix de demonstração (varia conforme o total)
$pix_codigo = '00020126580014br.gov.bcb.pix0136lux-cinelux-' . strtoupper(substr(md5(session_id() . $total), 0, 12));

// Iniciais para a miniatura de cada item
function lx_iniciais($t) { return mb_strtoupper(mb_substr(trim($t), 0, 3, 'UTF-8'), 'UTF-8'); }
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>CINELUX - Pagamento</title>
  <link rel="stylesheet" href="./CSS/pagamento.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link rel="shortcut icon" href="./img/Logo - LUX.png" type="image/x-icon">
</head>

<body>

  <header class="cabecalho">
    <a href="./inicio.php" class="logo">
      <img src="./img/Logo - LUX.png" alt="Logo LUX Cinema" class="logo-img">
    </a>

    <nav class="navegacao-menu">
      <span class="navegacao-opcoes">1. Ingressos</span>
      <span class="navegacao-opcoes">2. Combos</span>
      <span class="navegacao-ativa">3. Pagamento</span>
      <span class="navegacao-opcoes">4. Confirmação</span>
    </nav>

    <div class="acoes-do-usuario">
      <a href="carrinho.php" class="link"><i class="fa-solid fa-cart-shopping"></i></a>
      <a href="./listas.php" class="link"><i class="fa-solid fa-bookmark"></i></a>
      <a href="./perfil.php" class="link"><i class="fa-solid fa-user"></i></a>
    </div>
  </header>

  <!-- CONTEÚDO PRINCIPAL -->
  <main class="lux-container">
    <div class="checkout-grid">

      <!-- COLUNA ESQUERDA: DADOS E PAGAMENTO -->
      <section class="card checkout-main">
        <form id="checkout-form" action="pagamento.php" method="post">
          <span class="card-badge"><i class="fa-solid fa-shield-halved"></i> PAGAMENTO SEGURO</span>
          <h1 class="card-title">Finalize seu pedido</h1>
          <p class="card-subtitle">Garanta seus combos e ingressos antes de entrar na sala.</p>

          <?php if ($erro !== ''): ?>
            <p role="alert" style="background:#4a1f25;border:1px solid #a33a48;padding:10px 14px;border-radius:10px;"><?= h($erro) ?></p>
          <?php endif; ?>

          <h2 class="section-label">Seus dados</h2>
          <div class="form-grid">
            <div class="form-group full">
              <label for="nome">Nome completo</label>
              <input type="text" id="nome" value="<?= h($usuario['nome'] ?? '') ?>" autocomplete="name" readonly>
            </div>
            <div class="form-group half">
              <label for="email">E-mail</label>
              <input type="email" id="email" value="<?= h($usuario['email'] ?? '') ?>" autocomplete="email" readonly>
            </div>
            <div class="form-group half">
              <label for="cpf">CPF</label>
              <input type="text" id="cpf" value="<?= h($cpf_fmt) ?>" inputmode="numeric" readonly>
            </div>
          </div>

          <h2 class="section-label">Forma de pagamento</h2>

          <!-- ABAS 100% EM HTML/CSS (SEM JAVASCRIPT) -->
          <div class="payment-selector">
            <input type="radio" name="forma_pagamento" id="radio-pix" value="pix" class="tab-radio" <?= (($_POST['forma_pagamento'] ?? 'pix') !== 'cartao') ? 'checked' : '' ?>>
            <input type="radio" name="forma_pagamento" id="radio-cartao" value="cartao" class="tab-radio" <?= (($_POST['forma_pagamento'] ?? '') === 'cartao') ? 'checked' : '' ?>>

            <div class="payment-tabs" role="tablist" aria-label="Forma de pagamento">
              <label for="radio-pix" class="tab-btn" role="tab"><i class="fa-brands fa-pix"></i> Pix</label>
              <label for="radio-cartao" class="tab-btn" role="tab"><i class="fa-regular fa-credit-card"></i> Cartão</label>
            </div>

            <!-- ABA: PIX -->
            <div id="tab-pix" class="tab-pane" role="tabpanel">
              <div class="pix-box">
                <div class="qr-mock" aria-hidden="true">
                  <svg viewBox="0 0 100 100" width="96" height="96">
                    <rect class="qr-light" width="100" height="100" rx="6"/>
                    <rect class="qr-dark" x="8" y="8" width="26" height="26"/>
                    <rect class="qr-light" x="12" y="12" width="18" height="18"/>
                    <rect class="qr-dark" x="16" y="16" width="10" height="10"/>
                    <rect class="qr-dark" x="66" y="8" width="26" height="26"/>
                    <rect class="qr-light" x="70" y="12" width="18" height="18"/>
                    <rect class="qr-dark" x="74" y="16" width="10" height="10"/>
                    <rect class="qr-dark" x="8" y="66" width="26" height="26"/>
                    <rect class="qr-light" x="12" y="70" width="18" height="18"/>
                    <rect class="qr-dark" x="16" y="74" width="10" height="10"/>
                    <rect class="qr-dark" x="42" y="10" width="12" height="8"/>
                    <rect class="qr-dark" x="42" y="26" width="12" height="12"/>
                    <rect class="qr-dark" x="10" y="42" width="12" height="10"/>
                    <rect class="qr-dark" x="28" y="44" width="10" height="12"/>
                    <rect class="qr-dark" x="64" y="42" width="18" height="8"/>
                    <rect class="qr-dark" x="44" y="54" width="8" height="20"/>
                    <rect class="qr-dark" x="60" y="58" width="20" height="10"/>
                    <rect class="qr-dark" x="70" y="74" width="16" height="12"/>
                    <rect class="qr-dark" x="54" y="80" width="10" height="10"/>
                  </svg>
                </div>
                <div class="pix-info">
                  <p class="pix-title">Pague com Pix em segundos</p>
                  <input type="text" id="pixCode" class="pix-code" readonly aria-label="Código Pix copia e cola"
                         value="<?= h($pix_codigo) ?>">
                  <span class="pix-hint">Selecione e copie o código acima (Ctrl + C)</span>
                </div>
              </div>
            </div>


            <!-- ABA: CARTÃO (dados usados só para validar; nada é armazenado) -->
            <div id="tab-cartao" class="tab-pane" role="tabpanel">
              <div class="form-grid">
                <div class="form-group full">
                  <label for="card-tipo">Tipo do cartão</label>
                  <select id="card-tipo" name="tipo_cartao" class="form-select">
                    <option value="credito" <?= (($_POST['tipo_cartao'] ?? '') !== 'debito') ? 'selected' : '' ?>>Crédito</option>
                    <option value="debito" <?= (($_POST['tipo_cartao'] ?? '') === 'debito') ? 'selected' : '' ?>>Débito (à vista)</option>
                  </select>
                </div>
                <div class="form-group full">
                  <label for="card-num">Número do cartão</label>
                  <input type="text" id="card-num" name="card_num" placeholder="0000 0000 0000 0000" inputmode="numeric" maxlength="19" autocomplete="cc-number">
                </div>
                <div class="form-group full">
                  <label for="card-name">Nome impresso no cartão</label>
                  <input type="text" id="card-name" name="card_name" placeholder="Como consta no cartão" autocomplete="cc-name">
                </div>
                <div class="form-group half">
                  <label for="card-exp">Validade (MM/AA)</label>
                  <input type="text" id="card-exp" name="card_exp" placeholder="MM/AA" maxlength="5" autocomplete="cc-exp">
                </div>
                <div class="form-group half">
                  <label for="card-cvv">CVV</label>
                  <input type="text" id="card-cvv" name="card_cvv" placeholder="123" inputmode="numeric" maxlength="4" autocomplete="cc-csc">
                </div>
                <div class="form-group full">
                  <label for="card-parc">Parcelamento (somente crédito)</label>
                  <select id="card-parc" name="parcelas" class="form-select">
                    <?php for ($n = 1; $n <= 6; $n++): ?>
                      <option value="<?= $n ?>"><?= $n ?>x de <?= brl($total / $n) ?> sem juros</option>
                    <?php endfor; ?>
                  </select>
                </div>
              </div>
            </div>
          </div><!-- /.payment-selector -->

          <button type="submit" class="btn-primary" id="btn-pay">Pagar <?= brl($total) ?></button>

          <p class="security-guarantee">
            <i class="fa-solid fa-lock"></i>
            Ambiente criptografado · Seus dados estão protegidos
          </p>
        </form>
      </section>


      <!-- COLUNA DIREITA: RESUMO DO PEDIDO -->
      <aside class="card summary-card" aria-label="Resumo do pedido">
        <h2 class="section-label">Resumo do pedido</h2>

        <ul class="order-items">
          <?php foreach ($ingressos as $i): ?>
            <li class="order-item">
              <div class="item-thumb"><?= h(lx_iniciais($i['filme'])) ?></div>
              <div class="item-details">
                <span class="combo-tag">INGRESSO <?= h(strtoupper($i['tipo'])) ?></span>
                <p class="item-title"><?= h(mb_strtoupper($i['filme'], 'UTF-8')) ?></p>
                <p class="item-desc"><?= h(date('d/m/Y', strtotime($i['dia']))) ?> às <?= h(substr($i['hora'], 0, 5)) ?> · Sala <?= h($i['sala']) ?> · Assento <?= h($i['assento']) ?></p>
              </div>
              <span class="item-price"><?= brl($i['valor']) ?></span>
            </li>
          <?php endforeach; ?>

          <?php foreach ($produtos as $p): ?>
            <li class="order-item">
              <div class="item-thumb"><?= h(lx_iniciais($p['nome'])) ?></div>
              <div class="item-details">
                <span class="combo-tag">BOMBONIERE</span>
                <p class="item-title"><?= h(mb_strtoupper($p['nome'], 'UTF-8')) ?></p>
                <p class="item-desc"><?= (int)$p['qtd'] ?>x <?= brl($p['valor']) ?></p>
              </div>
              <span class="item-price"><?= brl($p['valor'] * $p['qtd']) ?></span>
            </li>
          <?php endforeach; ?>
        </ul>

        <!-- Cupom via GET (validado no servidor) -->
        <form class="coupon-group" action="pagamento.php" method="get">
          <input type="text" id="coupon-code" name="cupom" placeholder="Código do cupom" value="<?= h($cupom_ativo ?? '') ?>" aria-label="Cupom de desconto">
          <button type="submit" class="btn-outline" id="btn-coupon">Aplicar</button>
        </form>
        <?php if ($msg_cupom !== ''): ?>
          <p role="status" style="margin:8px 0;font-size:.85rem;color:<?= $ok_cupom ? '#7bd9a0' : '#ff8f9b' ?>;"><?= h($msg_cupom) ?></p>
        <?php endif; ?>

        <dl class="summary-totals">
          <div class="line-row">
            <dt>Subtotal</dt>
            <dd id="subtotal"><?= brl($subtotal) ?></dd>
          </div>
          <div class="line-row">
            <dt>Desconto</dt>
            <dd id="discount">– <?= brl($desconto) ?></dd>
          </div>
          <div class="line-row total">
            <dt>Total</dt>
            <dd id="total"><?= brl($total) ?></dd>
          </div>
        </dl>
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