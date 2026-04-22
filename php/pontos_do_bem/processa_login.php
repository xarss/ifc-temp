<?php
session_start();
require_once 'config/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $database = new Database();
    $db = $database->getConnection();

    $email = trim($_POST['email']);
    $senha = $_POST['senha'];

    if (empty($email) || empty($senha)) {
        $_SESSION['error_message'] = "Por favor, preencha todos os campos.";
        header("Location: login.php");
        exit;
    }

    try {
        $stmt = $db->prepare("SELECT id, nome, senha FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($senha, $user['senha'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_nome'] = $user['nome'];
            header("Location: painel_usuario.php");
            exit;
        } else {
            $_SESSION['error_message'] = "Login ou senha não conferem, tente novamente ou altere a sua senha!";
            header("Location: login.php");
            exit;
        }
    } catch (PDOException $e) {
        $_SESSION['error_message'] = "Erro ao realizar login. Por favor, tente novamente.";
        header("Location: login.php");
        exit;
    }
} else {
    header("Location: login.php");
    exit;
}
