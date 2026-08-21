<?php

function getConexao(): PDO {
    $socketPath = __DIR__ . '/../database/mysql.sock';
    $dbname = 'lojinha';
    $dbuser = 'root';
    $dbpass = '';

    $dsn = "mysql:unix_socket=$socketPath;dbname=$dbname;charset=utf8mb4";

    try {
        $pdo = new PDO($dsn, $dbuser, $dbpass, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]);
    } catch (PDOException $e) {
        gravarLog("Erro na conexão com MariaDB/MySQL: " . $e->getMessage());
        die("Erro de conexão: " . $e->getMessage());
    }

    return $pdo;
}

?>
