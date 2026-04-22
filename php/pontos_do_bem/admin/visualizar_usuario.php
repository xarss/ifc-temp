<?php
session_start();
require_once '../config/database.php';

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit;
}

if (!isset($_GET['id'])) {
    header("Location: painel.php");
    exit;
}

$database = new Database();
$db = $database->getConnection();

// Buscar informações do usuário
$stmt = $db->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$_GET['id']]);
$usuario = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$usuario) {
    header("Location: painel.php");
    exit;
}

// Buscar pagamentos do usuário
$stmt = $db->prepare("SELECT * FROM payments WHERE user_id = ? ORDER BY data_pagamento DESC");
$stmt->execute([$_GET['id']]);
$pagamentos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Visualizar Usuário - Pontos do Bem</title>
    <link href="../../css/style.css" rel="stylesheet">
    <link href="../../css/bootstrap.min.css" rel="stylesheet">
    <style>
        .user-container {
            max-width: 1000px;
            margin: 30px auto;
            padding: 20px;
        }
        .info-card {
            background: var(--white-color);
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            margin-bottom: 20px;
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
    <div class="container user-container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Visualizar Usuário</h2>
            <a href="painel.php" class="btn btn-secondary">Voltar</a>
        </div>

        <?php if(isset($_SESSION['admin_message'])): ?>
            <div class="alert alert-<?php echo $_SESSION['admin_message_type']; ?>">
                <?php 
                echo $_SESSION['admin_message']; 
                unset($_SESSION['admin_message']);
                unset($_SESSION['admin_message_type']);
                ?>
            </div>
        <?php endif; ?>

        <div class="info-card">
            <div class="row">
                <div class="col-md-6">
                    <h4>Informações Básicas</h4>
                    <p><strong>ID:</strong> <?php echo $usuario['id']; ?></p>
                    <p>
                        <strong>Status:</strong> 
                        <span class="status-badge status-<?php echo strtolower($usuario['status']); ?>">
                            <?php echo $usuario['status']; ?>
                        </span>
                    </p>
                    <p><strong>Nome:</strong> <?php echo htmlspecialchars($usuario['nome']); ?></p>
                    <p><strong>Email:</strong> <?php echo htmlspecialchars($usuario['email']); ?></p>
                    <p><strong>CPF:</strong> <?php echo $usuario['cpf'] ? substr($usuario['cpf'], 0, 3) . "." . substr($usuario['cpf'], 3, 3) . "." . substr($usuario['cpf'], 6, 3) . "-" . substr($usuario['cpf'], 9, 2) : 'Não cadastrado'; ?></p>
                    <p><strong>WhatsApp:</strong> <?php echo $usuario['whatsapp'] ? "(" . substr($usuario['whatsapp'], 0, 2) . ") " . substr($usuario['whatsapp'], 2, 5) . "-" . substr($usuario['whatsapp'], 7) : 'Não cadastrado'; ?></p>
                </div>
                <div class="col-md-6">
                    <h4>Informações do Programa</h4>
                    <p><strong>Valor Mensal:</strong> R$ <?php echo number_format($usuario['valor_mensal'], 2, ',', '.'); ?></p>
                    <p><strong>Valor Acumulado:</strong> R$ <?php echo number_format($usuario['valor_acumulado'], 2, ',', '.'); ?></p>
                    <p><strong>Pontos Acumulados:</strong> <?php echo $usuario['pontos_acumulados']; ?></p>
                </div>
            </div>

            <div class="mt-4">
                <h4>Ações</h4>
                <button class="btn btn-primary" data-toggle="modal" data-target="#editarModal">Editar Informações</button>
                <button class="btn btn-success" onclick="validarStatus('Ativo')">Validar como Ativo</button>
                <button class="btn btn-warning" onclick="validarStatus('Pendente')">Marcar como Pendente</button>
                <button class="btn btn-info" data-toggle="modal" data-target="#emailModal">Enviar E-mail</button>
            </div>
        </div>

        <div class="info-card">
            <h4>Histórico de Pagamentos</h4>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Data</th>
                        <th>Valor</th>
                        <th>Status</th>
                        <th>Pontos</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($pagamentos as $pagamento): ?>
                    <tr>
                        <td><?php echo date('d/m/Y', strtotime($pagamento['data_pagamento'])); ?></td>
                        <td>R$ <?php echo number_format($pagamento['valor'], 2, ',', '.'); ?></td>
                        <td><?php echo $pagamento['status']; ?></td>
                        <td><?php echo $pagamento['pontos_creditados']; ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal de Edição -->
    <div class="modal fade" id="editarModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Editar Informações</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <form action="atualizar_usuario.php" method="POST">
                    <div class="modal-body">
                        <input type="hidden" name="user_id" value="<?php echo $usuario['id']; ?>">
                        
                        <div class="form-group">
                            <label>Nome</label>
                            <input type="text" class="form-control" name="nome" value="<?php echo htmlspecialchars($usuario['nome']); ?>">
                        </div>

                        <div class="form-group">
                            <label>Email</label>
                            <input type="email" class="form-control" name="email" value="<?php echo htmlspecialchars($usuario['email']); ?>">
                        </div>

                        <div class="form-group">
                            <label>CPF</label>
                            <input type="text" class="form-control" name="cpf" value="<?php echo $usuario['cpf']; ?>">
                        </div>

                        <div class="form-group">
                            <label>WhatsApp</label>
                            <input type="text" class="form-control" name="whatsapp" value="<?php echo $usuario['whatsapp']; ?>">
                        </div>

                        <div class="form-group">
                            <label>Valor Mensal</label>
                            <select class="form-control" name="valor_mensal">
                                <option value="10.00" <?php echo $usuario['valor_mensal'] == 10.00 ? 'selected' : ''; ?>>R$ 10,00</option>
                                <option value="25.00" <?php echo $usuario['valor_mensal'] == 25.00 ? 'selected' : ''; ?>>R$ 25,00</option>
                                <option value="50.00" <?php echo $usuario['valor_mensal'] == 50.00 ? 'selected' : ''; ?>>R$ 50,00</option>
                                <option value="100.00" <?php echo $usuario['valor_mensal'] == 100.00 ? 'selected' : ''; ?>>R$ 100,00</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Salvar Alterações</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal de E-mail -->
    <div class="modal fade" id="emailModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Enviar E-mail</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <form action="enviar_email.php" method="POST">
                    <div class="modal-body">
                        <input type="hidden" name="user_id" value="<?php echo $usuario['id']; ?>">
                        
                        <div class="form-group">
                            <label>Tipo de E-mail</label>
                            <select class="form-control" name="tipo_email" id="tipo_email">
                                <option value="pagamento_aprovado">Pagamento Aprovado</option>
                                <option value="pagamento_pendente">Pagamento Pendente</option>
                                <option value="personalizado">Personalizado</option>
                            </select>
                        </div>

                        <div class="form-group" id="mensagem_personalizada" style="display: none;">
                            <label>Mensagem Personalizada</label>
                            <textarea class="form-control" name="mensagem" rows="5"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Enviar E-mail</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
    
    <script>
    function validarStatus(status) {
        if (confirm('Confirma a alteração do status para ' + status + '?')) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = 'atualizar_status.php';
            
            const userIdInput = document.createElement('input');
            userIdInput.type = 'hidden';
            userIdInput.name = 'user_id';
            userIdInput.value = '<?php echo $usuario['id']; ?>';
            
            const statusInput = document.createElement('input');
            statusInput.type = 'hidden';
            statusInput.name = 'status';
            statusInput.value = status;
            
            form.appendChild(userIdInput);
            form.appendChild(statusInput);
            document.body.appendChild(form);
            form.submit();
        }
    }

    document.getElementById('tipo_email').addEventListener('change', function() {
        const mensagemPersonalizada = document.getElementById('mensagem_personalizada');
        mensagemPersonalizada.style.display = this.value === 'personalizado' ? 'block' : 'none';
    });
    </script>
</body>
</html>
