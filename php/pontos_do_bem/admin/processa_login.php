<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Configurar log local
ini_set('error_log', __DIR__ . '/debug.log');
error_log("\n------- Nova tentativa de login -------");
error_log(date('Y-m-d H:i:s'));

require_once '../config/database.php';

error_log("Iniciando processamento de login");
error_log("POST data: " . print_r($_POST, true));
error_log("SESSION data: " . print_r($_SESSION, true));

error_log("Método da requisição: " . $_SERVER['REQUEST_METHOD']);
error_log("Headers da requisição: " . print_r(getallheaders(), true));

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $database = new Database();
        $db = $database->getConnection();

        if (!$db) {
            throw new Exception('Erro de conexão com o banco de dados');
        }

        $username = trim($_POST['username']);
        $password = $_POST['password'];

        if (empty($username) || empty($password)) {
            throw new Exception('Usuário e senha são obrigatórios');
        }

        // Verificar credenciais
        $stmt = $db->prepare("SELECT id, username, password FROM admin WHERE username = ?");
        $stmt->execute([$username]);
        $admin = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$admin) {
            error_log("Tentativa de login falhou: usuário '$username' não encontrado");
            throw new Exception('Usuário ou senha inválidos');
        }

        if (!password_verify($password, $admin['password'])) {
            error_log("Tentativa de login falhou: senha incorreta para usuário '$username'");
            throw new Exception('Usuário ou senha inválidos');
        }

        $_SESSION['admin_id'] = $admin['id'];
        $_SESSION['admin_username'] = $admin['username'];
        header("Location: painel.php");
        exit;

    } catch (Exception $e) {
        $_SESSION['admin_error'] = $e->getMessage();
        error_log("Erro no login administrativo: " . $e->getMessage());
        header("Location: login.php");
        exit;
    }
} else {
    header("Location: login.php");
    exit;
}
