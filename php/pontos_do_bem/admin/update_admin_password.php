<?php
require_once '../config/database.php';

try {
    $database = new Database();
    $db = $database->getConnection();

    if (!$db) {
        throw new Exception('Erro de conexão com o banco de dados');
    }

    $username = 'admin_ifc';
    $password = 'REDACTED';
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    $stmt = $db->prepare("UPDATE admin SET password = ? WHERE username = ?");
    $result = $stmt->execute([$hashed_password, $username]);

    if ($result) {
        echo "Senha do administrador atualizada com sucesso!";
    } else {
        echo "Erro ao atualizar a senha do administrador.";
    }

} catch (Exception $e) {
    echo "Erro: " . $e->getMessage();
}
