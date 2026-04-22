<?php
session_start();
require_once '../config/database.php';

if (!isset($_SESSION['admin_id']) || $_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: painel.php");
    exit;
}

$database = new Database();
$db = $database->getConnection();

$user_id = $_POST['user_id'];

try {
    $db->beginTransaction();

    // Deletar pagamentos do usuário
    $stmt = $db->prepare("DELETE FROM payments WHERE user_id = ?");
    $stmt->execute([$user_id]);

    // Deletar usuário
    $stmt = $db->prepare("DELETE FROM users WHERE id = ?");
    $stmt->execute([$user_id]);

    $db->commit();
    
    $_SESSION['admin_message'] = "Usuário excluído com sucesso!";
    $_SESSION['admin_message_type'] = "success";
} catch (PDOException $e) {
    $db->rollBack();
    $_SESSION['admin_message'] = "Erro ao excluir usuário.";
    $_SESSION['admin_message_type'] = "danger";
}

header("Location: painel.php");
exit;
