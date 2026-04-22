<?php
session_start();
require_once '../config/database.php';

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit;
}

$database = new Database();
$db = $database->getConnection();

// Buscar todos os usuários
$stmt = $db->query("SELECT id, status, nome, valor_acumulado, pontos_acumulados FROM users ORDER BY id DESC");
$usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel Administrativo - Pontos do Bem</title>
    <link href="../../../css/style.css" rel="stylesheet">
    <link href="../../../css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.10.24/css/dataTables.bootstrap4.min.css" rel="stylesheet">
    <style>
        .admin-container {
            padding: 20px;
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
    <div class="container-fluid admin-container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Painel Administrativo - Pontos do Bem</h2>
            <div>
                <span class="mr-3">Bem-vindo(a), <?php echo htmlspecialchars($_SESSION['admin_username']); ?></span>
                <a href="logout.php" class="btn btn-outline-danger">Sair</a>
            </div>
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

        <div class="card">
            <div class="card-body">
                <table id="usersTable" class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Status</th>
                            <th>Nome</th>
                            <th>Valor Acumulado</th>
                            <th>Pontos</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($usuarios as $usuario): ?>
                        <tr>
                            <td><?php echo $usuario['id']; ?></td>
                            <td>
                                <span class="status-badge status-<?php echo strtolower($usuario['status']); ?>">
                                    <?php echo $usuario['status']; ?>
                                </span>
                            </td>
                            <td><?php echo htmlspecialchars($usuario['nome']); ?></td>
                            <td>R$ <?php echo number_format($usuario['valor_acumulado'], 2, ',', '.'); ?></td>
                            <td><?php echo $usuario['pontos_acumulados']; ?></td>
                            <td>
                                <a href="visualizar_usuario.php?id=<?php echo $usuario['id']; ?>" class="btn btn-info btn-sm">Visualizar</a>
                                <button class="btn btn-danger btn-sm" onclick="confirmarDelete(<?php echo $usuario['id']; ?>)">Delete</button>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal de Confirmação -->
    <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Confirmar Exclusão</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Tem certeza que deseja excluir este usuário?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <form id="deleteForm" action="delete_usuario.php" method="POST" style="display: inline;">
                        <input type="hidden" name="user_id" id="deleteUserId">
                        <button type="submit" class="btn btn-danger">Confirmar Exclusão</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.24/js/dataTables.bootstrap4.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
    
    <script>
    $(document).ready(function() {
        $('#usersTable').DataTable({
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.10.24/i18n/Portuguese-Brasil.json"
            }
        });
    });

    function confirmarDelete(userId) {
        $('#deleteUserId').val(userId);
        $('#deleteModal').modal('show');
    }
    </script>
</body>
</html>
