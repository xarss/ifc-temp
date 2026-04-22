<?php
session_start();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Pontos do Bem</title>
    <link href="../css/style.css" rel="stylesheet">
    <link href="../css/bootstrap.min.css" rel="stylesheet">
    <style>
        .form-container {
            max-width: 500px;
            margin: 50px auto;
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
        <div class="form-container">
            <h2 class="text-center mb-4">Login Pontos do Bem</h2>
            
            <?php if(isset($_SESSION['error_message'])): ?>
                <div class="alert alert-danger">
                    <?php 
                    echo $_SESSION['error_message']; 
                    unset($_SESSION['error_message']);
                    ?>
                </div>
            <?php endif; ?>

            <?php if(isset($_SESSION['success_message'])): ?>
                <div class="alert alert-success">
                    <?php 
                    echo $_SESSION['success_message']; 
                    unset($_SESSION['success_message']);
                    ?>
                </div>
            <?php endif; ?>

            <form action="processa_login.php" method="POST">
                <div class="form-group">
                    <label for="email">E-mail</label>
                    <input type="email" class="form-control" id="email" name="email" required>
                </div>

                <div class="form-group">
                    <label for="senha">Senha</label>
                    <input type="password" class="form-control" id="senha" name="senha" required>
                </div>

                <button type="submit" class="btn btn-primary w-100">Entrar</button>
            </form>

            <div class="text-center mt-3">
                <p>Esqueceu sua senha? <a href="recuperar_senha.php">Clique aqui</a></p>
                <p>Ainda não tem uma conta? <a href="cadastro.php">Cadastre-se</a></p>
            </div>
        </div>
    </div>
</body>
</html>
