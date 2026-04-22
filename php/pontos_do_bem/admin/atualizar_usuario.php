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
$nome = trim($_POST['nome']);
$email = trim($_POST['email']);
$cpf = preg_replace('/[^0-9]/', '', $_POST['cpf']);
$whatsapp = preg_replace('/[^0-9]/', '', $_POST['whatsapp']);
$valor_mensal = floatval($_POST['valor_mensal']);

// Validações
if (empty($nome) || empty($email)) {
    $_SESSION['admin_message'] = "Nome e e-mail são obrigatórios.";
    $_SESSION['admin_message_type'] = "danger";
    header("Location: visualizar_usuario.php?id=" . $user_id);
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $_SESSION['admin_message'] = "E-mail inválido.";
    $_SESSION['admin_message_type'] = "danger";
    header("Location: visualizar_usuario.php?id=" . $user_id);
    exit;
}

// Verificar se o e-mail já existe para outro usuário
$stmt = $db->prepare("SELECT id FROM users WHERE email = ? AND id != ?");
$stmt->execute([$email, $user_id]);
if ($stmt->rowCount() > 0) {
    $_SESSION['admin_message'] = "Este e-mail já está cadastrado para outro usuário.";
    $_SESSION['admin_message_type'] = "danger";
    header("Location: visualizar_usuario.php?id=" . $user_id);
    exit;
}

if (!empty($cpf) && strlen($cpf) !== 11) {
    $_SESSION['admin_message'] = "CPF inválido.";
    $_SESSION['admin_message_type'] = "danger";
    header("Location: visualizar_usuario.php?id=" . $user_id);
    exit;
}

if (!empty($whatsapp) && (strlen($whatsapp) < 10 || strlen($whatsapp) > 11)) {
    $_SESSION['admin_message'] = "Número de WhatsApp inválido.";
    $_SESSION['admin_message_type'] = "danger";
    header("Location: visualizar_usuario.php?id=" . $user_id);
    exit;
}

try {
    $stmt = $db->prepare("
        UPDATE users 
        SET nome = ?, 
            email = ?, 
            cpf = ?, 
            whatsapp = ?, 
            valor_mensal = ?
        WHERE id = ?
    ");
    
    $stmt->execute([
        $nome,
        $email,
        $cpf,
        $whatsapp,
        $valor_mensal,
        $user_id
    ]);

    $_SESSION['admin_message'] = "Informações atualizadas com sucesso!";
    $_SESSION['admin_message_type'] = "success";
} catch (PDOException $e) {
    $_SESSION['admin_message'] = "Erro ao atualizar informações.";
    $_SESSION['admin_message_type'] = "danger";
}

header("Location: visualizar_usuario.php?id=" . $user_id);
exit;
