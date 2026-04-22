<?php
define('DB_HOST', 'REDACTED');
define('DB_USER', 'REDACTED'); // Altere para o usuário do seu banco de dados
define('DB_PASS', 'REDACTED');     // Altere para a senha do seu banco de dados
define('DB_NAME', 'pet_nao_secreto');

try {
    $pdo = new PDO("mysql:host=".DB_HOST.";dbname=".DB_NAME.";charset=utf8mb4", DB_USER, DB_PASS, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci"
    ]);
} catch(PDOException $e) {
    die("ERRO: Não foi possível conectar. " . $e->getMessage());
}
?>
