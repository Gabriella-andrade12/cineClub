<?php

$host = "localhost";
$dbname = "cadastro";
$username = "root";
$password = "Desenvolvedor@123";

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    function create($pdo, $table, array $data)
    {
        $columns = implode(', ', array_keys($data));
        $placeholders = implode(', ', array_fill(0, count($data), '?'));

        $sql = "INSERT INTO $table ($columns) VALUES ($placeholders)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(array_values($data));
        return $pdo->lastInsertId();
    }

    function readByFilmeId($pdo, $table, $id_filme)
    {
        $sql = "SELECT * FROM $table WHERE id_filme = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$id_filme]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
} catch (PDOException $e) {
    die("Erro na conexão com o banco de dados: " . $e->getMessage());
}

// 1. Pega o id_filme da URL (aceita tanto ?id_filme=1 quanto ?id=1)
$id_filme = isset($_GET['id_filme']) ? (int)$_GET['id_filme'] : (isset($_GET['id']) ? (int)$_GET['id'] : 0);

if ($id_filme <= 0) {
    die("Filme não especificado.");
}

// 2. Busca os dados do filme usando id_filme (altere 'filmes' se o nome da tabela for outro)
$filme = readByFilmeId($pdo, 'filmes', $id_filme);

if (!$filme) {
    die("Filme não encontrado no catálogo.");
}

// 3. Separa os gêneros da coluna gen_filme (caso venham separados por vírgula)
$generos = !empty($filme['gen_filme']) ? array_map('trim', explode(',', $filme['gen_filme'])) : [];

// 4. Formata a data do filme (caso esteja no formato YYYY-MM-DD do banco)
$data_formatada = $filme['data_filme'];
if (strtotime($filme['data_filme'])) {
    $data_formatada = date('d/m/Y', strtotime($filme['data_filme']));
}

?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CINE - LUX</title>
    <link rel="stylesheet" href="./CSS/filmes.css">
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

    <main class="filme">
        <div class="detalhes-do-filme">
            <div class="poster-do-filme">
                <img src="" alt="" class="poster-do-filme-img">
            </div>

            <div class="info-container">
                <div class="meta-dados">
                    <span class="badge-idade"><?= htmlspecialchars($filme['class_filme']) ?></span>
                    <span><?= htmlspecialchars($filme['duracao_filme']) ?></span>
                    <span>•</span>
                    <span>Lançamento: <?= htmlspecialchars($data_formatada) ?></span>
                    <span>•</span>
                    <span class="badge-exibicao"><?= htmlspecialchars($filme['exibicao_filme']) ?></span>
                </div>