<?php
require_once "../init.php";   // deve fornecer a conexão mysqli ($conn) e iniciar a sessão

if (session_status() === PHP_SESSION_NONE) { session_start(); }
if (empty($_SESSION['id_usuario'])) { header('Location: login.php'); exit; }
$uid = (int)$_SESSION['id_usuario'];

/* ---------------- Helpers (Prepared Statements) ---------------- */
if (!function_exists('h')) {
    function h($s) { return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8'); }
}
function lx_stmt($conn, $sql, $types = '', $params = []) {
    $st = mysqli_prepare($conn, $sql);
    if ($types !== '') { mysqli_stmt_bind_param($st, $types, ...$params); }
    mysqli_stmt_execute($st);
    return $st;
}
function lx_todos($conn, $sql, $types = '', $params = []) {
    $st = lx_stmt($conn, $sql, $types, $params);
    $r = mysqli_stmt_get_result($st);
    $rows = $r ? mysqli_fetch_all($r, MYSQLI_ASSOC) : [];
    mysqli_stmt_close($st);
    return $rows;
}
function lx_insert($conn, $sql, $types = '', $params = []) {
    $st = lx_stmt($conn, $sql, $types, $params);
    $id = mysqli_stmt_insert_id($st);
    mysqli_stmt_close($st);
    return $id;
}
function lx_duracao($d) {
    if (!is_numeric($d)) return (string)$d;
    $m = (int)$d; return intdiv($m, 60) . 'h ' . str_pad($m % 60, 2, '0', STR_PAD_LEFT) . 'm';
}

/* Horários oferecidos para sessão privada */
$horarios = [
    '10:30' => '10:30 (Sessão Matinê)',
    '14:00' => '14:00 (Tarde)',
    '16:45' => '16:45 (Tarde / Fim de tarde)',
    '19:30' => '19:30 (Noite)',
    '22:15' => '22:15 (Corujão Exclusivo)',
];

/* Observações só são gravadas se a tabela reserva tiver a coluna "observacoes"
 * (ALTER TABLE reserva ADD COLUMN observacoes VARCHAR(255) NULL;) */
$tem_obs = (bool)lx_todos($conn, "SHOW COLUMNS FROM reserva LIKE 'observacoes'");

/* ---------------- Processa a reserva (POST) ----------------
 * REGRA CRÍTICA: a sala não pode ter sessão (sessao) nem outra reserva (reserva)
 * no mesmo dia e horário. A verificação e a gravação ocorrem numa transação. */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $old = [
        'id_cinema' => (int)($_POST['id_cinema'] ?? 0),
        'id_sala'   => (int)($_POST['id_sala'] ?? 0),
        'data'      => trim($_POST['data_reserva'] ?? ''),
        'hora'      => trim($_POST['horario_reserva'] ?? ''),
        'id_filme'  => (int)($_POST['id_filme'] ?? 0),
        'obs'       => trim($_POST['observacoes'] ?? ''),
    ];
    $res = ['estado' => 'erro', 'erros' => [], 'old' => $old];

    $sala  = lx_todos($conn, "SELECT sa.id_sala, sa.numero_sala, c.endereco_cinema FROM sala sa JOIN cinema c ON c.id_cinema = sa.id_cinema WHERE sa.id_sala = ? AND sa.id_cinema = ?", 'ii', [$old['id_sala'], $old['id_cinema']]);
    $filme = lx_todos($conn, "SELECT id_filme, nome_filme FROM filme WHERE id_filme = ? AND exibicao_filme = 'Cinema'", 'i', [$old['id_filme']]);
    $d = DateTime::createFromFormat('Y-m-d', $old['data']);
    $dok = $d && $d->format('Y-m-d') === $old['data'];

    if (!$old['id_cinema'])  $res['erros'][] = 'Selecione o cinema.';
    elseif (!$sala)          $res['erros'][] = 'A sala escolhida não pertence ao cinema selecionado.';
    if (!$filme)             $res['erros'][] = 'Escolha um filme em cartaz.';
    if (!$dok || $old['data'] < date('Y-m-d')) $res['erros'][] = 'Escolha uma data a partir de hoje.';
    if (!isset($horarios[$old['hora']]))       $res['erros'][] = 'Escolha um horário válido.';
    elseif ($dok && $old['data'] === date('Y-m-d') && $old['hora'] <= date('H:i')) $res['erros'][] = 'Este horário de hoje já passou.';

    if (!$res['erros']) {
        $hora_bd = $old['hora'] . ':00';
        try {
            mysqli_begin_transaction($conn);
            $sess = lx_todos($conn, "SELECT 1 FROM sessao WHERE id_sala = ? AND dia_sessao = ? AND hora_sessao = ?", 'iss', [$old['id_sala'], $old['data'], $hora_bd]);
            $resv = lx_todos($conn, "SELECT 1 FROM reserva WHERE id_sala = ? AND dia_reserva = ? AND hora_reserva = ? AND status_reserva <> 'Cancelada' FOR UPDATE", 'iss', [$old['id_sala'], $old['data'], $hora_bd]);
            if ($sess) {
                $res['estado'] = 'conflito_sessao';
            } elseif ($resv) {
                $res['estado'] = 'conflito_reserva';
            } else {
                if ($tem_obs) {
                    $id = lx_insert($conn, "INSERT INTO reserva (dia_reserva, hora_reserva, status_reserva, id_usuario, id_sala, id_filme, observacoes) VALUES (?, ?, 'Agendada', ?, ?, ?, ?)",
                                    'ssiiis', [$old['data'], $hora_bd, $uid, $old['id_sala'], $old['id_filme'], $old['obs']]);
                } else {
                    $id = lx_insert($conn, "INSERT INTO reserva (dia_reserva, hora_reserva, status_reserva, id_usuario, id_sala, id_filme) VALUES (?, ?, 'Agendada', ?, ?, ?)",
                                    'ssiii', [$old['data'], $hora_bd, $uid, $old['id_sala'], $old['id_filme']]);
                }
                $res['estado'] = 'agendada';
                $res['resumo'] = ['id' => $id, 'cinema' => $sala[0]['endereco_cinema'], 'sala' => $sala[0]['numero_sala'],
                                  'data' => $old['data'], 'hora' => $old['hora'], 'filme' => $filme[0]['nome_filme'], 'obs_salva' => $tem_obs, 'obs' => $old['obs']];
            }
            mysqli_commit($conn);
        } catch (Throwable $e) {
            mysqli_rollback($conn);
            $res = ['estado' => 'erro', 'erros' => ['Não foi possível concluir a reserva. Tente novamente.'], 'old' => $old];
        }
    }
    $_SESSION['reserva_resultado'] = $res;
    header('Location: reserva.php');   // PRG: evita reenvio do formulário ao recarregar
    exit;
}

$res = $_SESSION['reserva_resultado'] ?? null;
unset($_SESSION['reserva_resultado']);
$estado = $res['estado'] ?? '';
$old = ($res && $estado !== 'agendada') ? $res['old'] : ['id_cinema' => 0, 'id_sala' => 0, 'data' => '', 'hora' => '', 'id_filme' => 0, 'obs' => ''];

/* ---------------- Dados dos selects ---------------- */
$cinemas = lx_todos($conn, "SELECT id_cinema, endereco_cinema FROM cinema ORDER BY endereco_cinema");
$salas   = lx_todos($conn, "SELECT id_sala, numero_sala, capacidade, id_cinema FROM sala ORDER BY id_cinema, numero_sala");
$salas_por_cinema = [];
foreach ($salas as $s) { $salas_por_cinema[$s['id_cinema']][] = $s; }
$filmes = lx_todos($conn, "SELECT id_filme, nome_filme, gen_filme, duracao_filme FROM filme WHERE exibicao_filme = 'Cinema' AND tipo_conteudo = 'Filme' ORDER BY nome_filme");

$minhas = lx_todos(
    $conn,
    "SELECT r.dia_reserva, r.hora_reserva, r.status_reserva, sa.numero_sala, c.endereco_cinema, f.nome_filme
       FROM reserva r JOIN sala sa ON sa.id_sala = r.id_sala JOIN cinema c ON c.id_cinema = sa.id_cinema JOIN filme f ON f.id_filme = r.id_filme
      WHERE r.id_usuario = ? ORDER BY r.dia_reserva DESC, r.hora_reserva DESC LIMIT 5",
    'i',
    [$uid]
);
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>CINE - LUX</title>
  <link rel="stylesheet" href="./CSS/reserva.css">
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
      <a href="./infantil.html" class="navegacao-opcoes">Infantil</a>
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

<main class="container">

    <!-- TÍTULO DA PÁGINA -->
    <header class="hero-reserva">
      <span class="etiqueta-secao">EXCLUSIVIDADE CINELUX</span>
      <h1>RESERVE UMA <span>SALA DE CINEMA</span> SÓ PARA VOCÊ</h1>
      <p class="subtitulo-hero">
        Organize sessões privadas para aniversários, eventos corporativos ou encontros entre amigos. Escolha o cinema, a sala, o filme em cartaz e verifique a disponibilidade na hora.
      </p>
    </header>

    <div class="grade-reserva">

      <!-- COLUNA ESQUERDA: FORMULÁRIO DE RESERVA -->
      <section class="card-formulario">
        <div class="card-cabecalho">
          <h2><i class="fa-solid fa-film"></i> Configurar Sessão Privada</h2>
          <p>Preencha todos os campos abaixo para consultar a grade e agendar.</p>
        </div>

        <?php if ($estado === 'erro'): foreach ($res['erros'] as $e): ?>
          <div role="alert" style="margin:0 0 12px;padding:10px 14px;border-radius:10px;background:#4a1f25;border:1px solid #a33a48;"><?= h($e) ?></div>
        <?php endforeach; endif; ?>

        <form action="reserva.php" method="post" class="form-reserva">

          <!-- Seleção de Cinema -->
          <div class="campo-grupo">
            <label for="id_cinema"><i class="fa-solid fa-building"></i> Cinema LUX</label>
            <select id="id_cinema" name="id_cinema" required>
              <option value="" disabled<?= $old['id_cinema'] ? '' : ' selected' ?>>Selecione a unidade desejada...</option>
              <?php foreach ($cinemas as $c): ?>
                <option value="<?= (int)$c['id_cinema'] ?>"<?= $old['id_cinema'] === (int)$c['id_cinema'] ? ' selected' : '' ?>><?= h($c['endereco_cinema']) ?></option>
              <?php endforeach; ?>
            </select>
          </div>

          <!-- Seleção de Sala (agrupada por cinema; o servidor confere se a sala é do cinema escolhido) -->
          <div class="campo-grupo">
            <label for="id_sala"><i class="fa-solid fa-couch"></i> Sala de Exibição</label>
            <select id="id_sala" name="id_sala" required>
              <option value="" disabled<?= $old['id_sala'] ? '' : ' selected' ?>>Selecione a sala...</option>
              <?php foreach ($cinemas as $c): if (empty($salas_por_cinema[$c['id_cinema']])) continue; ?>
                <optgroup label="<?= h($c['endereco_cinema']) ?>">
                  <?php foreach ($salas_por_cinema[$c['id_cinema']] as $s): ?>
                    <option value="<?= (int)$s['id_sala'] ?>"<?= $old['id_sala'] === (int)$s['id_sala'] ? ' selected' : '' ?>>Sala <?= h(str_pad($s['numero_sala'], 2, '0', STR_PAD_LEFT)) ?> (<?= (int)$s['capacidade'] ?> lugares)</option>
                  <?php endforeach; ?>
                </optgroup>
              <?php endforeach; ?>
            </select>
          </div>

          <!-- Data e Horário lado a lado -->
          <div class="linha-dupla">
            <div class="campo-grupo">
              <label for="data_reserva"><i class="fa-regular fa-calendar"></i> Data da Reserva</label>
              <input type="date" id="data_reserva" name="data_reserva" min="<?= date('Y-m-d') ?>" value="<?= h($old['data']) ?>" required>
            </div>

            <div class="campo-grupo">
              <label for="horario_reserva"><i class="fa-regular fa-clock"></i> Horário Desejado</label>
              <select id="horario_reserva" name="horario_reserva" required>
                <option value="" disabled<?= $old['hora'] !== '' ? '' : ' selected' ?>>Escolha o horário...</option>
                <?php foreach ($horarios as $v => $rotulo): ?>
                  <option value="<?= h($v) ?>"<?= $old['hora'] === $v ? ' selected' : '' ?>><?= h($rotulo) ?></option>
                <?php endforeach; ?>
              </select>
            </div>
          </div>

          <!-- Seleção de Filme em Cartaz -->
          <div class="campo-grupo">
            <label for="id_filme"><i class="fa-solid fa-clapperboard"></i> Filme em Cartaz Desejado</label>
            <select id="id_filme" name="id_filme" required>
              <option value="" disabled<?= $old['id_filme'] ? '' : ' selected' ?>>Escolha um título em cartaz...</option>
              <?php foreach ($filmes as $f): ?>
                <option value="<?= (int)$f['id_filme'] ?>"<?= $old['id_filme'] === (int)$f['id_filme'] ? ' selected' : '' ?>><?= h($f['nome_filme']) ?> — <?= h($f['gen_filme']) ?> (<?= h(lx_duracao($f['duracao_filme'])) ?>)</option>
              <?php endforeach; ?>
            </select>
          </div>

          <!-- Observações opcionais -->
          <div class="campo-grupo">
            <label for="observacoes"><i class="fa-regular fa-comment-dots"></i> Observações para o Evento (Opcional)</label>
            <textarea id="observacoes" name="observacoes" rows="3" maxlength="255" placeholder="Ex.: Exibição de vídeo de parabéns antes do filme, necessidade de microfone ou reserva de combos..."><?= h($old['obs']) ?></textarea>
          </div>

          <!-- Botão Principal -->
          <button type="submit" class="btn-confirmar">
            <i class="fa-solid fa-calendar-check"></i> VERIFICAR DISPONIBILIDADE E AGENDAR
          </button>
        </form>
      </section>

      <!-- COLUNA DIREITA: VALIDAÇÃO DE CONFLITO, STATUS E RESUMO -->
      <aside class="coluna-lateral">

        <!-- CARD DE STATUS: mostra o resultado real da validação (sessao x reserva) -->
        <div class="card-status">
          <div class="status-topo">
            <span class="badge-sistema"><i class="fa-solid fa-shield-halved"></i> VALIDAÇÃO EM TEMPO REAL</span>
            <h3>Status da Sala & Agenda</h3>
            <p>O sistema cruza automaticamente as tabelas <code>sessao</code> e <code>reserva</code> para impedir conflitos no mesmo dia e horário.</p>
          </div>

          <div class="simulador-estados">
            <input type="radio" name="estado_validacao" id="estado-agendada" class="radio-estado"<?= $estado === 'agendada' ? ' checked' : '' ?> disabled>
            <input type="radio" name="estado_validacao" id="estado-conflito-sessao" class="radio-estado"<?= $estado === 'conflito_sessao' ? ' checked' : '' ?> disabled>
            <input type="radio" name="estado_validacao" id="estado-conflito-reserva" class="radio-estado"<?= $estado === 'conflito_reserva' ? ' checked' : '' ?> disabled>

            <!-- Estado 1: Disponível -> Status Agendada -->
            <div class="box-resultado resultado-ok">
              <div class="resultado-icone">
                <i class="fa-solid fa-circle-check"></i>
              </div>
              <div class="resultado-texto">
                <span class="tag-status status-agendada">STATUS: AGENDADA</span>
                <h4>Sala Livre para Reserva!</h4>
                <p>Nenhuma sessão comercial (<code>sessao</code>) ou outra reserva privada (<code>reserva</code>) encontrada para esta data e horário. Sua reserva será gravada como <strong>Agendada</strong>.</p>
              </div>
            </div>

            <!-- Estado 2: Conflito com Sessão em Cartaz -->
            <div class="box-resultado resultado-erro">
              <div class="resultado-icone">
                <i class="fa-solid fa-triangle-exclamation"></i>
              </div>
              <div class="resultado-texto">
                <span class="tag-status status-indisponivel">INDISPONÍVEL · SESSÃO ATIVA</span>
                <h4>Conflito com Programação Regular</h4>
                <p>Esta sala já possui uma exibição regular cadastrada (<code>sessao</code>) neste dia e horário. Escolha outro horário ou outra sala LUX.</p>
              </div>
            </div>

            <!-- Estado 3: Conflito com Outra Reserva -->
            <div class="box-resultado resultado-erro">
              <div class="resultado-icone">
                <i class="fa-solid fa-ban"></i>
              </div>
              <div class="resultado-texto">
                <span class="tag-status status-indisponivel">INDISPONÍVEL · JÁ RESERVADA</span>
                <h4>Sala Reservada por Outro Cliente</h4>
                <p>Já existe uma reserva com status <strong>Agendada</strong> (<code>reserva</code>) para esta sala no mesmo dia e horário.</p>
              </div>
            </div>

            <?php if ($estado === ''): ?>
              <p style="margin:12px 0 0;opacity:.8;">Preencha o formulário e clique em verificar para consultar a disponibilidade da sala.</p>
            <?php elseif ($estado === 'agendada'): $r = $res['resumo']; ?>
              <p style="margin:12px 0 0;">
                <strong>Reserva nº <?= (int)$r['id'] ?></strong><br>
                <?= h($r['cinema']) ?> — Sala <?= h(str_pad($r['sala'], 2, '0', STR_PAD_LEFT)) ?><br>
                <?= h(date('d/m/Y', strtotime($r['data']))) ?> às <?= h($r['hora']) ?> · <?= h($r['filme']) ?>
                <?php if ($r['obs'] !== '' && !$r['obs_salva']): ?><br><small>Observações não foram gravadas (a tabela de reservas não possui esse campo).</small><?php endif; ?>
              </p>
            <?php endif; ?>
          </div>
        </div>


        <!-- CARD DE BENEFÍCIOS DA SALA PRIVADA -->
        <div class="card-info-vip">
          <h3>O que está incluso na reserva?</h3>
          <ul class="lista-vantagens">
            <li>
              <i class="fa-solid fa-check"></i>
              <span><strong>Projeção Exclusiva:</strong> Exibição fechada apenas para os seus convidados.</span>
            </li>
            <li>
              <i class="fa-solid fa-check"></i>
              <span><strong>Bloqueio Imediato:</strong> Ao confirmar, o horário é bloqueado automaticamente na grade.</span>
            </li>
            <li>
              <i class="fa-solid fa-check"></i>
              <span><strong>Combos Especiais:</strong> Desconto exclusivo na bomboniere para grupos fechados.</span>
            </li>
          </ul>
          <a href="./bomboniere.php" class="btn-combos">Ver Combos para Grupos</a>
        </div>

        <?php if ($minhas): ?>
        <!-- MINHAS RESERVAS -->
        <div class="card-info-vip">
          <h3>Minhas reservas</h3>
          <ul class="lista-vantagens">
            <?php foreach ($minhas as $m): ?>
            <li>
              <i class="fa-solid fa-calendar-check"></i>
              <span><strong><?= h(date('d/m/Y', strtotime($m['dia_reserva']))) ?> às <?= h(substr($m['hora_reserva'], 0, 5)) ?></strong> — Sala <?= h(str_pad($m['numero_sala'], 2, '0', STR_PAD_LEFT)) ?>, <?= h($m['endereco_cinema']) ?> · <?= h($m['nome_filme']) ?> (<?= h($m['status_reserva']) ?>)</span>
            </li>
            <?php endforeach; ?>
          </ul>
        </div>
        <?php endif; ?>


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