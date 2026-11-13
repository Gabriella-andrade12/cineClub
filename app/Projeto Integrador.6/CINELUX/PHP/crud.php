<?php

$host = "localhost";
$dbname = "lux_dados";
$username = "root";
$password = "senai123";

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

    function read($pdo, $table, $id_filme)
    {
        $sql = "SELECT * FROM $table WHERE id_filme = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$id_filme]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    } catch (PDOException $e) {
    die("Erro na conexão com o banco de dados: " . $e->getMessage());
}