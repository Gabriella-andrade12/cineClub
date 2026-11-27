<?php
require_once "../init.php";   // fornece a conexão PDO ($pdo), crud() e inicia a sessão

if (session_status() === PHP_SESSION_NONE) { session_start(); }
if (empty($_SESSION['id_usuario'])) { header('Location: login.php'); exit; }

// Helper de escape (caso o init.php ainda não tenha um)
if (!function_exists('h')) {
  function h($s) { return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8'); }
}

/* ---------- Filtros cumulativos: ?tipo=...&genero=... ---------- */
$abas = [
  'cartaz'    => 'Filmes em Cartaz',
  'streaming' => 'Filmes em Streaming',
  'series'    => 'Séries',
];
$tipo = $_GET['tipo'] ?? 'cartaz';                 // inicia em cartaz
if (!isset($abas[$tipo])) { $tipo = 'cartaz'; }

// Gêneros dinâmicos (SELECT DISTINCT), na ordem preferida da interface
$ordem = ['Suspense','Aventura','Romance','Terror','Drama','Ação','Comédia','Ficção','Animação','Fantasia','Crime','Musical','Documentário'];
$no_banco = $pdo->query("SELECT DISTINCT gen_filme FROM filme WHERE gen_filme IS NOT NULL AND gen_filme <> ''")->fetchAll(PDO::FETCH_COLUMN);
$lista_generos = array_merge(
  array_values(array_intersect($ordem, $no_banco)),
  array_values(array_diff($no_banco, $ordem))
);

$genero = trim($_GET['genero'] ?? '');
if (!in_array($genero, $lista_generos, true)) { $genero = ''; }
$busca = trim($_GET['busca'] ?? '');

/* Monta URL preservando o outro filtro (valores nulos/vazios saem da URL) */
function link_filtro($tipo, $genero, $busca = '') {
  $p = array_filter(['tipo' => $tipo, 'genero' => $genero, 'busca' => $busca], function ($v) { return $v !== null && $v !== ''; });
  return 'inicio.php' . ($p ? '?' . http_build_query($p) : '');
}

/* Consulta dos títulos conforme a aba + gênero + busca */
$sql = "SELECT id_filme, nome_filme, imagem_capa FROM filme WHERE ";
if ($tipo === 'cartaz')         { $sql .= "exibicao_filme = 'Cinema' AND tipo_conteudo = 'Filme'"; }
elseif ($tipo === 'streaming')  { $sql .= "exibicao_filme = 'Streaming' AND tipo_conteudo = 'Filme'"; }
else                            { $sql .= "tipo_conteudo = 'Serie'"; }

$params = [];
if ($genero !== '') { $sql .= " AND gen_filme = ?"; $params[] = $genero; }
if ($busca !== '')  { $sql .= " AND (nome_filme LIKE ? OR gen_filme LIKE ?)"; $params[] = "%$busca%"; $params[] = "%$busca%"; }
$sql .= " ORDER BY nome_filme";

$st = $pdo->prepare($sql);
$st->execute($params);
$filmes  = $st->fetchAll();
$linhas  = array_chunk($filmes, 4);               // 4 cartões por linha (.grade-de-filmes)
$grupo1  = array_slice($lista_generos, 0, 6);     // "Todos" + 6 = 7 pílulas visíveis
$grupo2  = array_slice($lista_generos, 6);        // restante (botão de seta alterna)
?>

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
    <a href="./inicio.php" class="logo">
      <img src="./img/Logo - LUX.png" alt="Logo LUX Cinema" class="logo-img">
    </a>

    <nav class="navegacao-menu">
      <a href="./inicio.php" class="navegacao-ativa">Lançamentos</a>
      <a href="./recomendados.php" class="navegacao-opcoes">Recomendados</a>
      <a href="./bomboniere.php" class="navegacao-opcoes">Combos</a>
      <a href="./infantil.php" class="navegacao-opcoes">Infantil</a>
    </nav>

    <form class="busca" method="get" action="inicio.php">
      <input type="hidden" name="tipo" value="<?= h($tipo) ?>">
      <?php if ($genero !== ''): ?><input type="hidden" name="genero" value="<?= h($genero) ?>"><?php endif; ?>
      <input type="text" name="busca" value="<?= h($busca) ?>" placeholder="Buscar filmes, gêneros, cinemas...">
      <i class="fa-solid fa-magnifying-glass busca-icon"></i>
    </form>

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

    <section class="faixa-escolher">
      <button class="faixa-navegacao-botao faixa-navegacao-esquerda">
        <i class="fa-solid fa-chevron-left"></i>
      </button>

      <div class="filmes-destaque">
        <div class="filmes-destaque-faixa">

          <div class="filmes-destaque-slides filme-destaque-1">
            <div class="filmes-destaque-informacao">
              <div class="filmes-destaque-caracteristicas">
                <span class="caracteristicas">TENSÃO & SOBREVIVÊNCIA</span>
                <span class="informacoes"><i class="fa-regular fa-clock"></i> 1 hora e 26 minutos</span>
                <span class="informacoes"><i class="fa-regular fa-calendar"></i> 1 de Novembro de 2013</span>
                <span class="idade">18 anos</span>
              </div>
              <h1 class="filme-destaque-titulo">UMA NOITE DE CRIME <span class="filme-destaque-subtitulo">THE
                  PURGE</span></h1>
              <p class="filme-destaque-sinopse">Quando o governo norte-americano constata que suas prisões estão cheias
                demais para receberem novos detentos, uma nova lei é criada, permitindo todas as atividades ilegais
                durante 12 horas. Este período, chamado de Noite do Crime, é marcado por milhares de assassinatos,
                linchamentos e outros atos de violência por todo o país...</p>
              <p class="filme-destaque-creditos"><strong>Direção e Roteiro:</strong> James DeMonaco</p>
              <p class="filme-destaque-creditos"><strong>Gêneros:</strong> Suspense, Terror</p>
              <div class="filme-destaque-opcoes">
                <button class="botao primeiro-botao">Ver Trailer</button>
                <button class="botao segundo-botao">Ver Detalhes</button>
              </div>
            </div>
            <div class="filme-destaque-cartaz">
              <img src="./img/Filmes Destaque/Uma Noite de Crime.png" alt="Uma Noite de Crime">
            </div>
          </div>

          <div class="filmes-destaque-slides filme-destaque-2">
            <div class="filmes-destaque-informacao">
              <div class="filmes-destaque-caracteristicas">
                <span class="caracteristicas">AÇÃO & RITMO</span>
                <span class="informacoes"><i class="fa-regular fa-clock"></i> 2 horas e 53 minutos</span>
                <span class="informacoes"><i class="fa-regular fa-calendar"></i> 27 de Julho de 2017</span>
                <span class="idade">14 anos</span>
              </div>
              <h1 class="filme-destaque-titulo">BABY DRIVER <span class="filme-destaque-subtitulo">EM RITMO DE
                  FUGA</span></h1>
              <p class="filme-destaque-sinopse">Em Ritmo de Fuga, o jovem Baby (Ansel Elgort) tem uma mania curiosa:
                precisa ouvir músicas o tempo todo para silenciar o zumbido que perturba seus ouvidos desde um acidente
                na infância...</p>
              <p class="filme-destaque-creditos"><strong>Direção e Roteiro:</strong> Edgar Wright</p>
              <p class="filme-destaque-creditos"><strong>Gêneros:</strong> Ação, Policial, Musical</p>
              <div class="filme-destaque-opcoes">
                <button class="botao primeiro-botao">Ver Trailer</button>
                <button class="botao segundo-botao">Ver Detalhes</button>
              </div>
            </div>
            <div class="filme-destaque-cartaz">
              <img src="./img/Filmes Destaque/Baby Driver.png" alt="Baby Driver">
            </div>
          </div>

          <div class="filmes-destaque-slides filme-destaque-3">
            <div class="filmes-destaque-informacao">
              <div class="filmes-destaque-caracteristicas">
                <span class="caracteristicas">CAÓTICO & GUITARRAS</span>
                <span class="informacoes"><i class="fa-regular fa-clock"></i> 1 hora e 52 minutos</span>
                <span class="informacoes"><i class="fa-regular fa-calendar"></i> 24 de Dezembro de 2010</span>
                <span class="idade">12 anos</span>
              </div>
              <h1 class="filme-destaque-titulo">SCOTT PILGRIM <span class="filme-destaque-subtitulo">CONTRA O
                  MUNDO</span></h1>
              <p class="filme-destaque-sinopse">Scott Pilgrim (Michael Cera) tem 23 anos, integra uma banda de colégio,
                vive trocando de emprego e tem um namoro firme...</p>
              <p class="filme-destaque-creditos"><strong>Direção e Roteiro:</strong> Edgar Wright e Michael Bacall</p>
              <p class="filme-destaque-creditos"><strong>Gêneros:</strong> Aventura, Comédia, Fantasia</p>
              <div class="filme-destaque-opcoes">
                <button class="botao primeiro-botao">Ver Trailer</button>
                <button class="botao segundo-botao">Ver Detalhes</button>
              </div>
            </div>
            <div class="filme-destaque-cartaz">
              <img src="./img/Filmes Destaque/Scott Pilgrim.png" alt="Scott Pilgrim">
            </div>
          </div>

          <div class="filmes-destaque-slides filme-destaque-4">
            <div class="filmes-destaque-informacao">
              <div class="filmes-destaque-caracteristicas">
                <span class="caracteristicas">CICLO MORTAL & TERROR CÔMICO</span>
                <span class="informacoes"><i class="fa-regular fa-clock"></i> 1 hora e 36 minutos</span>
                <span class="informacoes"><i class="fa-regular fa-calendar"></i> 12 de Outubro de 2017</span>
                <span class="idade">14 anos</span>
              </div>
              <h1 class="filme-destaque-titulo">A MORTE TE DÁ PARABÉNS <span class="filme-destaque-subtitulo">ACORDE.
                  VIVA SEU DIA. MORRA. TUDO DENOVO</span></h1>
              <p class="filme-destaque-sinopse">Tree (Jessica Rothe) é uma jovem estudante que trata mal os meninos,
                desdenha das amigas e não parece estar muito disposta a atender as ligações do pai...</p>
              <p class="filme-destaque-creditos"><strong>Direção e Roteiro:</strong> Christopher Landon e Scott Lobdell
              </p>
              <p class="filme-destaque-creditos"><strong>Gêneros:</strong> Suspense, Terror, Comédia</p>
              <div class="filme-destaque-opcoes">
                <button class="botao primeiro-botao">Ver Trailer</button>
                <button class="botao segundo-botao">Ver Detalhes</button>
              </div>
            </div>
            <div class="filme-destaque-cartaz">
              <img src="./img/Filmes Destaque/A Morte Te Dá Parabéns.png" alt="A morte te dá parabéns">
            </div>
          </div>

          <div class="filmes-destaque-slides filme-destaque-5">
            <div class="filmes-destaque-informacao">
              <div class="filmes-destaque-caracteristicas">
                <span class="caracteristicas">PERTUBADOR & MELANCÓLICO</span>
                <span class="informacoes"><i class="fa-regular fa-clock"></i> 2 horas e 02 minutos</span>
                <span class="informacoes"><i class="fa-regular fa-calendar"></i> 3 de Outubro de 2019</span>
                <span class="idade">16 anos</span>
              </div>
              <h1 class="filme-destaque-titulo">CORINGA <span class="filme-destaque-subtitulo">JOKER</span></h1>
              <p class="filme-destaque-sinopse">Em Coringa, Arthur Fleck (Joaquin Phoenix) trabalha como palhaço para
                uma agência de talentos e, toda semana, precisa comparecer a uma agente social...</p>
              <p class="filme-destaque-creditos"><strong>Direção e Roteiro:</strong> Todd Phillips e Scott Silver</p>
              <p class="filme-destaque-creditos"><strong>Gêneros:</strong> Drama</p>
              <div class="filme-destaque-opcoes">
                <button class="botao primeiro-botao">Ver Trailer</button>
                <button class="botao segundo-botao">Ver Detalhes</button>
              </div>
            </div>
            <div class="filme-destaque-cartaz">
              <img src="./img/Filmes Destaque/Coringa.png" alt="Coringa">
            </div>
          </div>

        </div>

        <div class="indicador-de-pagina">
          <span class="pagina pagina-1"></span>
          <span class="pagina pagina-2"></span>
          <span class="pagina pagina-3"></span>
          <span class="pagina pagina-4"></span>
          <span class="pagina pagina-5"></span>
        </div>
      </div>

      <button class="faixa-navegacao-botao faixa-navegacao-direita">
        <i class="fa-solid fa-chevron-right"></i>
      </button>
    </section>

    <section class="generos">
      <div class="genero">
        <i class="fa-solid fa-sliders"></i>
        <i class="fa-solid fa-list-ul"></i>
      </div>

      <input type="checkbox" id="troca-de-generos" hidden<?= in_array($genero, $grupo2, true) ? ' checked' : '' ?> />

      <div class="lista-generos grupo-1">
        <a href="<?= h(link_filtro($tipo, '', $busca)) ?>">
          <button type="button" class="<?= $genero === '' ? 'genero-ativo' : 'genero-opcoes' ?>">Todos</button>
        </a>
        <?php foreach ($grupo1 as $g): ?>
          <a href="<?= h(link_filtro($tipo, $g === $genero ? '' : $g, $busca)) ?>">
            <button type="button" class="<?= $g === $genero ? 'genero-ativo' : 'genero-opcoes' ?>"><?= h(ucfirst($g)) ?></button>
          </a>
        <?php endforeach; ?>
      </div>

      <?php if ($grupo2): ?>
      <div class="lista-generos grupo-2">
        <a href="<?= h(link_filtro($tipo, '', $busca)) ?>">
          <button type="button" class="<?= $genero === '' ? 'genero-ativo' : 'genero-opcoes' ?>">Todos</button>
        </a>
        <?php foreach ($grupo2 as $g): ?>
          <a href="<?= h(link_filtro($tipo, $g === $genero ? '' : $g, $busca)) ?>">
            <button type="button" class="<?= $g === $genero ? 'genero-ativo' : 'genero-opcoes' ?>"><?= h(ucfirst($g)) ?></button>
          </a>
        <?php endforeach; ?>
      </div>

      <label for="troca-de-generos" class="navegacao-generos">
        <i class="fa-solid fa-chevron-right"></i>
      </label>
      <?php endif; ?>
    </section>

      <section class="movies-container">
      <div class="selecione-filmes">
        <?php foreach ($abas as $chave => $rotulo): ?>
          <!-- Trocar de aba preserva o gênero ativo na URL -->
          <a href="<?= h(link_filtro($chave, $genero, $busca)) ?>">
            <button type="button" class="<?= $chave === $tipo ? 'filmes-ativo' : 'filmes-opcoes' ?>"><?= h($rotulo) ?></button>
          </a>
        <?php endforeach; ?>
      </div>

      <?php if (!$filmes): ?>
        <p class="sem-resultados" style="text-align:center;padding:40px 0;">Nenhum título encontrado.</p>
      <?php endif; ?>

      <?php foreach ($linhas as $linha): ?>
        <div class="grade-de-filmes">
          <?php foreach ($linha as $f):
            $capa = !empty($f['imagem_capa']) ? $f['imagem_capa'] : './img/Logo - LUX.png'; ?>
            <div class="cartao-filme">
              <div class="cartaz-filme">
                <img src="<?= h($capa) ?>" alt="<?= h($f['nome_filme']) ?>">
                <div class="filme-titulo"><?= h($f['nome_filme']) ?></div>
              </div>
              <a href="filmes.php?id=<?= (int)$f['id_filme'] ?>">
                <button class="botao-de-detalhes">Ver Detalhes</button>
              </a>
            </div>
          <?php endforeach; ?>
        </div>
      <?php endforeach; ?>

      <div class="expansao-de-filmes">
        <button class="botao-expansao" title="Ver mais filmes">
          <i class="fa-solid fa-chevron-down"></i>
        </button>
      </div>
    </section>

    <section class="filmes-recomendados">
      <div class="filmes-recomendados-detalhes">
        <div class="cartao-de-detalhes">
          <img src="./img/Filmes Recomandados/Batman.png" alt="Batman">
          <div class="cartao-body">
            <div>
              <span class="filme-recomendado-informacoes">Lançado no Brasil: 18 de Julho de 2008</span>
              <h4>BATMAN</h4>
              <span class="filme-recomendado-avaliacao"><i class="fa-solid fa-star"></i> 9.1/10</span>
            </div>
            <p class="filme-recomendado-informacoes">Duração: 2 horas e 32 minutos</p>
            <button class="botao-de-detalhes">Ver Detalhes</button>
          </div>
        </div>

        <div class="cartao-de-detalhes">
          <img src="./img/Filmes Recomandados/O Poderoso Chefão.png" alt="O Poderoso Chefão">
          <div class="cartao-body">
            <div>
              <span class="filme-recomendado-informacoes">Estreia Mundial / Pré-Estreia: 14 de Março de 1972</span>
              <h4>O PODEROSO CHEFÃO</h4>
              <span class="filme-recomendado-avaliacao"><i class="fa-solid fa-star"></i> 9.1/10</span>
            </div>
            <p class="filme-recomendado-informacoes">Duração: 2 horas e 55 minutos</p>
            <button class="botao-de-detalhes">Ver Detalhes</button>
          </div>
        </div>

        <div class="cartao-de-detalhes">
          <img src="./img/Filmes Recomandados/Psicose.png" alt="Psicose">
          <div class="cartao-body">
            <div>
              <span class="filme-recomendado-informacoes">Estreia mundial em Nova York: 16 de Junho de 1960</span>
              <h4>PSICOSE</h4>
              <span class="filme-recomendado-avaliacao"><i class="fa-solid fa-star"></i> 8.5/10</span>
            </div>
            <p class="filme-recomendado-informacoes">Duração: 1 hora e 49 minutos</p>
            <button class="botao-de-detalhes">Ver Detalhes</button>
          </div>
        </div>
      </div>
      <button class="faixa-navegacao-botao faixa-navegacao-direita">
        <i class="fa-solid fa-chevron-right"></i>
      </button>
    </section>

    <section class="combos">
      <div class="cabecalho-de-combos">
        <div>
          <span class="secao-combos">BOMBONIERE EXCLUSIVA</span>
          <h3>Combos Especiais & Exclusivos</h3>
          <p>Garanta seus petiscos e itens exclusivos antes de entrar na sala</p>
        </div>
        <a href="./bomboniere.php">
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
          <a href="tiktok.php"><i class="fa-brands fa-tiktok"></i></a>
          <a href="instagram.php"><i class="fa-brands fa-instagram"></i></a>
          <a href="facebook.php"><i class="fa-brands fa-facebook"></i></a>
        </div>
      </div>
    </div>
  </footer>

</body>

</html>