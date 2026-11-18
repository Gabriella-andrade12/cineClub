

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
    <a href="./inicio.html" class="logo">
      <img src="./img/Logo - LUX.png" alt="Logo LUX Cinema" class="logo-img">
    </a>

    <nav class="navegacao-menu">
      <span class="navegacao-opcoes">1. Ingressos</span>
      <span class="navegacao-opcoes">2. Combos</span>
      <span class="navegacao-ativa">3. Pagamento</span>
      <span class="navegacao-opcoes">4. Confirmação</span>
    </nav>

    <div class="acoes-do-usuario">
      <a href="carrinho.html" class="link"><i class="fa-solid fa-cart-shopping"></i></a>
      <a href="./minha_lista.html" class="link"><i class="fa-solid fa-bookmark"></i></a>
      <a href="./perfil.html" class="link"><i class="fa-solid fa-user"></i></a>
    </div>
  </header>

  <!-- CONTEÚDO PRINCIPAL -->
  <main class="lux-container">
    <div class="checkout-grid">

      <!-- COLUNA ESQUERDA: DADOS E PAGAMENTO -->
      <section class="card checkout-main">
        <form id="checkout-form" action="confirmacao.html" method="get">
          <span class="card-badge"><i class="fa-solid fa-shield-halved"></i> PAGAMENTO SEGURO</span>
          <h1 class="card-title">Finalize seu pedido</h1>
          <p class="card-subtitle">Garanta seus combos e ingressos antes de entrar na sala.</p>

          <h2 class="section-label">Seus dados</h2>
          <div class="form-grid">
            <div class="form-group full">
              <label for="nome">Nome completo</label>
              <input type="text" id="nome" name="nome" placeholder="Seu nome completo" autocomplete="name" required>
            </div>
            <div class="form-group half">
              <label for="email">E-mail</label>
              <input type="email" id="email" name="email" placeholder="voce@email.com" autocomplete="email" required>
            </div>
            <div class="form-group half">
              <label for="cpf">CPF</label>
              <input type="text" id="cpf" name="cpf" placeholder="000.000.000-00" inputmode="numeric" maxlength="14" pattern="\d{3}\.?\d{3}\.?\d{3}-?\d{2}" required>
            </div>
          </div>

          <h2 class="section-label">Forma de pagamento</h2>

          <!-- ABAS 100% EM HTML/CSS (SEM JAVASCRIPT) -->
          <div class="payment-selector">
            <input type="radio" name="forma_pagamento" id="radio-pix" value="pix" class="tab-radio" checked>
            <input type="radio" name="forma_pagamento" id="radio-cartao" value="cartao" class="tab-radio">
            <input type="radio" name="forma_pagamento" id="radio-boleto" value="boleto" class="tab-radio">

            <div class="payment-tabs" role="tablist" aria-label="Forma de pagamento">
              <label for="radio-pix" class="tab-btn" role="tab"><i class="fa-brands fa-pix"></i> Pix</label>
              <label for="radio-cartao" class="tab-btn" role="tab"><i class="fa-regular fa-credit-card"></i> Cartão</label>
              <label for="radio-boleto" class="tab-btn" role="tab"><i class="fa-solid fa-barcode"></i> Boleto</label>
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
                         value="00020126580014br.gov.bcb.pix0136lux-cinelux-pedido-8841">
                  <span class="pix-hint">Selecione e copie o código acima (Ctrl + C)</span>
                </div>
              </div>
            </div>

            <!-- ABA: CARTÃO -->
            <div id="tab-cartao" class="tab-pane" role="tabpanel">
              <div class="form-grid">
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
                  <label for="card-parc">Parcelamento</label>
                  <select id="card-parc" name="parcelas" class="form-select">
                    <option value="1">1x de R$ 325,00 sem juros</option>
                    <option value="2">2x de R$ 162,50 sem juros</option>
                    <option value="3">3x de R$ 108,33 sem juros</option>
                    <option value="4">4x de R$ 81,25 sem juros</option>
                    <option value="5">5x de R$ 65,00 sem juros</option>
                    <option value="6">6x de R$ 54,17 sem juros</option>
                  </select>
                </div>
              </div>
            </div>

            <!-- ABA: BOLETO -->
            <div id="tab-boleto" class="tab-pane" role="tabpanel">
              <div class="boleto-box">
                <p>O boleto bancário será gerado após clicar em pagar. A compensação ocorre em até 2 dias úteis.</p>
                <div class="notice-warning">
                  <i class="fa-solid fa-triangle-exclamation"></i>
                  Atenção: a reserva dos seus assentos e combos fica garantida por apenas 24h.
                </div>
              </div>
            </div>
          </div>

          <button type="submit" class="btn-primary" id="btn-pay">Pagar R$ 325,00</button>

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
          <li class="order-item">
            <div class="item-thumb thumb-ghost">PÂNICO</div>
            <div class="item-details">
              <span class="combo-tag">EDIÇÃO LIMITADA</span>
              <p class="item-title">COMBO GHOSTFACE PÂNICO</p>
              <p class="item-desc">2 bebidas grandes, 1 balde refil e copo colecionável · 1x</p>
            </div>
            <span class="item-price">R$ 190,00</span>
          </li>

          <li class="order-item">
            <div class="item-thumb thumb-mms">M&amp;M's</div>
            <div class="item-details">
              <span class="combo-tag">PROMOÇÃO DA SEMANA</span>
              <p class="item-title">COMBO M&amp;M'S DUO</p>
              <p class="item-desc">Pipoca grande, 2 refris G, M&amp;M's e copo · 1x</p>
            </div>
            <span class="item-price">R$ 135,00</span>
          </li>
        </ul>

        <!-- Formulário de cupom tradicional via GET -->
        <form class="coupon-group" action="" method="get">
          <input type="text" id="coupon-code" name="cupom" placeholder="Cupom (teste: LUX10)" aria-label="Cupom de desconto">
          <button type="submit" class="btn-outline" id="btn-coupon">Aplicar</button>
        </form>

        <dl class="summary-totals">
          <div class="line-row">
            <dt>Subtotal</dt>
            <dd id="subtotal">R$ 325,00</dd>
          </div>
          <div class="line-row">
            <dt>Desconto</dt>
            <dd id="discount">– R$ 0,00</dd>
          </div>
          <div class="line-row total">
            <dt>Total</dt>
            <dd id="total">R$ 325,00</dd>
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