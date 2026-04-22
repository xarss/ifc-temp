<?php
session_start();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro - Pontos do Bem</title>
    <link href="../css/style.css" rel="stylesheet">
    <link href="../css/bootstrap.min.css" rel="stylesheet">
    <style>
        .form-container {
            max-width: 600px;
            margin: 50px auto;
            padding: 20px;
            background: var(--white-color);
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .form-group {
            margin-bottom: 20px;
        }
        .error-message {
            color: var(--secondary-color);
            font-size: 14px;
            margin-top: 5px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="form-container">
            <h2 class="text-center mb-4">Cadastro Pontos do Bem</h2>
            
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

            <form action="processa_cadastro.php" method="POST" id="cadastroForm">
                <div class="form-group">
                    <label for="nome">Nome Completo*</label>
                    <input type="text" class="form-control" id="nome" name="nome" required>
                </div>

                <div class="form-group">
                    <label for="email">E-mail*</label>
                    <input type="email" class="form-control" id="email" name="email" required>
                </div>

                <div class="form-group">
                    <label for="senha">Senha*</label>
                    <input type="password" class="form-control" id="senha" name="senha" required>
                    <small class="text-muted">A senha deve conter pelo menos 8 caracteres, incluindo números, letras e símbolos.</small>
                </div>

                <div class="form-group">
                    <label for="confirma_senha">Confirme a Senha*</label>
                    <input type="password" class="form-control" id="confirma_senha" name="confirma_senha" required>
                </div>

                <button type="submit" class="btn btn-primary w-100">Cadastrar</button>
            </form>

            <div class="text-center mt-3">
                <p>Já tem uma conta? <a href="login.php">Faça login</a></p>
            </div>
        </div>
    </div>

    <script>
    document.getElementById('cadastroForm').addEventListener('submit', function(e) {
        const senha = document.getElementById('senha').value;
        const confirma_senha = document.getElementById('confirma_senha').value;
        
        // Validação da senha
        const senhaRegex = /^(?=.*[A-Za-z])(?=.*\d)(?=.*[@$!%*#?&])[A-Za-z\d@$!%*#?&]{8,}$/;
        
        if (!senhaRegex.test(senha)) {
            e.preventDefault();
            alert('A senha deve conter pelo menos 8 caracteres, incluindo números, letras e símbolos.');
            return;
        }

        if (senha !== confirma_senha) {
            e.preventDefault();
            alert('As senhas não conferem!');
            return;
        }
    });
    </script>
</body>
</html>
