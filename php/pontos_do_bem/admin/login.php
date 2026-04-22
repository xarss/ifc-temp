<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Debug das variáveis de sessão
error_log("Sessão atual: " . print_r($_SESSION, true));
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - Pontos do Bem</title>
    <link href="../../css/style.css" rel="stylesheet">
    <link href="../../css/bootstrap.min.css" rel="stylesheet">
    <style>
        .admin-login-container {
            max-width: 400px;
            margin: 100px auto;
            padding: 20px;
            background: var(--white-color);
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .form-group {
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="admin-login-container">
            <h2 class="text-center mb-4">Administração</h2>
            
            <?php if(isset($_SESSION['admin_error'])): ?>
                <div class="alert alert-danger">
                    <?php 
                    echo $_SESSION['admin_error']; 
                    unset($_SESSION['admin_error']);
                    ?>
                </div>
            <?php endif; ?>

            <form action="processa_login.php" method="POST" id="loginForm" onsubmit="return handleSubmit(event)">
                <div class="form-group">
                    <label for="username">Usuário</label>
                    <input type="text" class="form-control" id="username" name="username" required>
                </div>

                <div class="form-group">
                    <label for="password">Senha</label>
                    <input type="password" class="form-control" id="password" name="password" required>
                </div>

                <button type="submit" class="btn btn-primary w-100">Entrar</button>
            </form>
        </div>
    </div>
    <script>
    function handleSubmit(event) {
        // Debug no console
        console.log('Formulário sendo enviado...');
        
        const formData = new FormData(document.getElementById('loginForm'));
        console.log('Dados do formulário:');
        for (let pair of formData.entries()) {
            console.log(pair[0] + ': ' + (pair[0] === 'password' ? '****' : pair[1]));
        }
        
        return true; // permite o envio normal do formulário
    }
    </script>
</body>
</html>
