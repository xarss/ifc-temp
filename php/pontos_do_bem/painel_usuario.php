<?php
session_start();
require_once 'config/database.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$database = new Database();
$db = $database->getConnection();

// Buscar informações do usuário
$stmt = $db->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel do Usuário - Pontos do Bem</title>
    <link href="../../css/style.css" rel="stylesheet">
    <link href="../../css/bootstrap.min.css" rel="stylesheet">
    <style>
        .dashboard-container {
            max-width: 1200px;
            margin: 30px auto;
            padding: 20px;
        }
        .user-info {
            background: var(--white-color);
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            margin-bottom: 20px;
        }
        .points-display {
            font-size: 24px;
            font-weight: bold;
            color: var(--primary-color);
        }
        .status-badge {
            padding: 5px 10px;
            border-radius: 5px;
            font-weight: bold;
        }
        .status-ativo {
            background-color: #28a745;
            color: white;
        }
        .status-pendente {
            background-color: #ffc107;
            color: black;
        }
    </style>
</head>
<body>
    <div class="container dashboard-container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Bem-vindo(a), <?php echo htmlspecialchars($user['nome']); ?>!</h2>
            <a href="logout.php" class="btn btn-outline-danger">Sair</a>
        </div>

        <div class="user-info">
            <div class="row">
                <div class="col-md-6">
                    <h4>Informações do Usuário</h4>
                    <p><strong>Status:</strong> 
                        <span class="status-badge status-<?php echo strtolower($user['status']); ?>">
                            <?php echo $user['status']; ?>
                        </span>
                    </p>
                    <p><strong>ID:</strong> <?php echo $user['id']; ?></p>
                    <p><strong>Nome:</strong> <?php echo htmlspecialchars($user['nome']); ?></p>
                    <p><strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?></p>
                    <p><strong>Pontos Acumulados:</strong> 
                        <span class="points-display"><?php echo $user['pontos_acumulados']; ?></span>
                    </p>
                </div>
                <div class="col-md-6">
                    <h4>Ações</h4>
                    <a href="alterar_senha.php" class="btn btn-primary mb-2 w-100">Alterar Senha</a>
                    <?php if (empty($user['cpf'])): ?>
                        <a href="cadastro_programa.php" class="btn btn-success w-100">Participar do Programa Pontos do Bem</a>
                    <?php else: ?>
                        <a href="enviar_comprovante.php" class="btn btn-success w-100">Enviar Comprovante de Pagamento</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <?php if (!empty($user['cpf'])): ?>
        <div class="user-info">
            <h4>Informações do Programa</h4>
            <div class="row">
                <div class="col-md-6">
                    <p><strong>CPF:</strong> <?php echo htmlspecialchars($user['cpf']); ?></p>
                    <p><strong>WhatsApp:</strong> <?php echo htmlspecialchars($user['whatsapp']); ?></p>
                    <p><strong>Valor Mensal:</strong> R$ <?php echo number_format($user['valor_mensal'], 2, ',', '.'); ?></p>
                    <p><strong>Valor Acumulado:</strong> R$ <?php echo number_format($user['valor_acumulado'], 2, ',', '.'); ?></p>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </div>
</body>
</html>
