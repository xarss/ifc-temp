<?php
session_start();
require_once 'config/database.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$database = new Database();
$db = $database->getConnection();

// Verificar se o usuário já está cadastrado no programa
$stmt = $db->prepare("SELECT cpf FROM users WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!empty($user['cpf'])) {
    header("Location: painel_usuario.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro no Programa - Pontos do Bem</title>
    <link href="../css/style.css" rel="stylesheet">
    <link href="../css/bootstrap.min.css" rel="stylesheet">
    <style>
        .form-container {
            max-width: 800px;
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
            <h2 class="text-center mb-4">Cadastro no Programa Pontos do Bem</h2>
            
            <?php if(isset($_SESSION['error_message'])): ?>
                <div class="alert alert-danger">
                    <?php 
                    echo $_SESSION['error_message']; 
                    unset($_SESSION['error_message']);
                    ?>
                </div>
            <?php endif; ?>

            <form action="processa_cadastro_programa.php" method="POST" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="cpf">CPF*</label>
                    <input type="text" class="form-control" id="cpf" name="cpf" required>
                </div>

                <div class="form-group">
                    <label for="whatsapp">WhatsApp*</label>
                    <input type="text" class="form-control" id="whatsapp" name="whatsapp" required>
                </div>

                <div class="form-group">
                    <label for="valor">Valor Mensal*</label>
                    <select class="form-control" id="valor" name="valor" required>
                        <option value="10.00">R$ 10,00</option>
                        <option value="25.00">R$ 25,00</option>
                        <option value="50.00">R$ 50,00</option>
                        <option value="100.00">R$ 100,00</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="comprovante">Comprovante de Pagamento*</label>
                    <input type="file" class="form-control" id="comprovante" name="comprovante" required>
                    <small class="text-muted">Formatos aceitos: PDF, JPG, PNG (máx. 5MB)</small>
                </div>

                <button type="submit" class="btn btn-primary w-100">Cadastrar no Programa</button>
            </form>

            <div class="text-center mt-3">
                <a href="painel_usuario.php" class="btn btn-outline-secondary">Voltar ao Painel</a>
            </div>
        </div>
    </div>

    <script>
    // Máscara para CPF
    document.getElementById('cpf').addEventListener('input', function(e) {
        let value = e.target.value.replace(/\D/g, '');
        if (value.length <= 11) {
            value = value.replace(/(\d{3})(\d{3})(\d{3})(\d{2})/, "$1.$2.$3-$4");
            e.target.value = value;
        }
    });

    // Máscara para WhatsApp
    document.getElementById('whatsapp').addEventListener('input', function(e) {
        let value = e.target.value.replace(/\D/g, '');
        if (value.length <= 11) {
            value = value.replace(/(\d{2})(\d{5})(\d{4})/, "($1) $2-$3");
            e.target.value = value;
        }
    });
    </script>
</body>
</html>
