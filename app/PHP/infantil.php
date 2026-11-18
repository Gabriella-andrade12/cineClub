<!DOCTYPE html>
<html lang="pt-BR">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>CINE - LUX</title>
  <link rel="stylesheet" href="./CSS/inicio.css">
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



  <section class="combos">
      <div class="cabecalho-de-combos">
        <div>
          <span class="secao-combos">BOMBONIERE EXCLUSIVA</span>
          <h3>Combos Especiais & Exclusivos</h3>
          <p>Garanta seus petiscos e itens exclusivos antes de entrar na sala</p>
        </div>
        <a href="./combos.html">
          <button class="botao botao-mustard"><i class="fa-solid fa-utensils"></i> Ver Cardápio Completo</button>
        </a>
      </div>

      <div class="grade-de-combos">
        <div class="cartao-combo">
          <img src="./img/Combos/1/Cena de Crime.png" alt="Combo Ghostface">
          <div class="combo-informacoes">
            <span class="combo-titulo">CENAS DO CRIME</span>
            <h4>COMBO GHOSTFACE PÂNICO</h4>
            <p><b>Composição do combo:</b> 2 bebidas grandes, 1 balde refil e 1 copo colecionável do Ghostface (replica
              a máscara do vilão).</p>
            <div class="combo-valores">
              <span class="valor-do-combo">75,00</span>
              <button class="botao botao-adicionar">Adicionar</button>
            </div>
          </div>
        </div>

        <div class="cartao-combo">
          <img src="./img/Combos/1/Salvação da vizinhança.png" alt="Combo M&M">
          <div class="combo-informacoes">
            <span class="combo-titulo">SALVAÇÃO DA VIZINHANÇA</span>
            <h4>COMBO M&M'S DUO</h4>
            <p><b>Combo Completo:</b> Composto por pipoca grande, dois copos de refrigerante tamanho G, um pacote de
              M&M's e o copo colecionável.</p>
            <div class="combo-valores">
              <span class="valor-do-combo">58,00</span>
              <button class="botao botao-adicionar">Adicionar</button>
            </div>
          </div>
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