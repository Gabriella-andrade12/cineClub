<?php

declare(strict_types=1);

function database(): PDO
{
    static $connection = null;

    if ($connection instanceof PDO) {
        return $connection;
    }

    $host = getenv('DB_HOST') ?: '127.0.0.1';
    $port = getenv('DB_PORT') ?: '3306';
<<<<<<< HEAD
    $database = getenv('DB_DATABASE') ?: 'cineClub';
=======
    $database = getenv('DB_DATABASE') ?: 'lux_dados';
>>>>>>> ea5101fa99119c464bbe00d672228a05ed17ca49
    $username = getenv('DB_USERNAME') ?: 'root';
    $password = getenv('DB_PASSWORD') ?: 'senai123';

    $dsn = "mysql:host={$host};port={$port};dbname={$database};charset=utf8mb4";

    $connection = new PDO($dsn, $username, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ]);

    return $connection;
}

