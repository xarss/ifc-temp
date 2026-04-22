<?php
session_start();
require_once 'config/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $database = new Database();
    $db = $database->getConnection();

    $nome = trim($_POST['nome']);
    $email = trim($_POST['email']);
    $senha = $_POST['senha'];

    // Validações
    if (empty($nome) || empty($email) || empty($senha)) {
        $_SESSION['error_message'] = "Todos os campos são obrigatórios.";
        header("Location: cadastro.php");
        exit;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['error_message'] = "E-mail inválido.";
        header("Location: cadastro.php");
        exit;
    }

    // Verifica se o e-mail já existe
    $stmt = $db->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->execute([$email]);
    if ($stmt->rowCount() > 0) {
        $_SESSION['error_message'] = "Este e-mail já está cadastrado.";
        header("Location: cadastro.php");
        exit;
    }

    // Validação da senha
    if (!preg_match('/^(?=.*[A-Za-z])(?=.*\d)(?=.*[@$!%*#?&])[A-Za-z\d@$!%*#?&]{8,}$/', $senha)) {
        $_SESSION['error_message'] = "A senha deve conter pelo menos 8 caracteres, incluindo números, letras e símbolos.";
        header("Location: cadastro.php");
        exit;
    }

    // Hash da senha
    $senha_hash = password_hash($senha, PASSWORD_DEFAULT);

    try {
        $stmt = $db->prepare("INSERT INTO users (nome, email, senha) VALUES (?, ?, ?)");
        $stmt->execute([$nome, $email, $senha_hash]);

        $_SESSION['success_message'] = "Cadastro realizado com sucesso! Faça login para continuar.";
        header("Location: login.php");
        exit;
    } catch (PDOException $e) {
        $_SESSION['error_message'] = "Erro ao realizar o cadastro. Por favor, tente novamente.";
        header("Location: cadastro.php");
        exit;
    }
} else {
    header("Location: cadastro.php");
    exit;
}
