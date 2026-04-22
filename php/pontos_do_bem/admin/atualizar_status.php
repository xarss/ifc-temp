<?php
session_start();
require_once '../config/database.php';
require_once '../php/PHPMailer/PHPMailer.php';
require_once '../php/PHPMailer/SMTP.php';
require_once '../php/PHPMailer/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

if (!isset($_SESSION['admin_id']) || $_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: painel.php");
    exit;
}

$database = new Database();
$db = $database->getConnection();

$user_id = $_POST['user_id'];
$status = $_POST['status'];

try {
    $db->beginTransaction();

    // Atualizar status do usuário
    $stmt = $db->prepare("UPDATE users SET status = ? WHERE id = ?");
    $stmt->execute([$status, $user_id]);

    // Se o status for Ativo, adicionar um ponto
    if ($status === 'Ativo') {
        // Verificar se já existe um pagamento para o mês atual
        $stmt = $db->prepare("
            SELECT id FROM payments 
            WHERE user_id = ? 
            AND MONTH(data_pagamento) = MONTH(CURRENT_DATE())
            AND YEAR(data_pagamento) = YEAR(CURRENT_DATE())
        ");
        $stmt->execute([$user_id]);
        
        if ($stmt->rowCount() === 0) {
            // Buscar valor mensal do usuário
            $stmt = $db->prepare("SELECT valor_mensal FROM users WHERE id = ?");
            $stmt->execute([$user_id]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            
            // Registrar novo pagamento
            $stmt = $db->prepare("
                INSERT INTO payments (user_id, valor, data_pagamento, status, pontos_creditados) 
                VALUES (?, ?, CURRENT_DATE(), 'Pago', 1)
            ");
            $stmt->execute([$user_id, $user['valor_mensal']]);

            // Atualizar pontos e valor acumulado do usuário
            $stmt = $db->prepare("
                UPDATE users 
                SET pontos_acumulados = pontos_acumulados + 1,
                    valor_acumulado = valor_acumulado + ?
                WHERE id = ?
            ");
            $stmt->execute([$user['valor_mensal'], $user_id]);

            // Enviar e-mail de confirmação
            $stmt = $db->prepare("SELECT nome, email FROM users WHERE id = ?");
            $stmt->execute([$user_id]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            $mail = new PHPMailer(true);

            try {
                // Configurações do servidor
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com'; // Ajuste conforme necessário
                $mail->SMTPAuth = true;
                $mail->Username = 'seu-email@gmail.com'; // Ajuste conforme necessário
                $mail->Password = 'sua-senha'; // Ajuste conforme necessário
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port = 587;
                $mail->CharSet = 'UTF-8';

                // Destinatários
                $mail->setFrom('noreply@institutoficacomigo.com.br', 'Instituto Fica Comigo');
                $mail->addAddress($user['email'], $user['nome']);

                // Conteúdo
                $mail->isHTML(true);
                $mail->Subject = 'Pagamento Aprovado - Pontos do Bem';
                $mail->Body = "
                    <h2>Olá, {$user['nome']}!</h2>
                    <p>Seu pagamento foi aprovado com sucesso!</p>
                    <p>Você recebeu +1 ponto em seu programa Pontos do Bem.</p>
                    <p>Agradecemos sua participação!</p>
                    <br>
                    <p>Atenciosamente,<br>Instituto Fica Comigo</p>
                ";

                $mail->send();
            } catch (Exception $e) {
                // Log do erro de e-mail, mas não interrompe o processo
                error_log("Erro ao enviar e-mail: {$mail->ErrorInfo}");
            }
        }
    }

    $db->commit();
    
    $_SESSION['admin_message'] = "Status atualizado com sucesso!";
    $_SESSION['admin_message_type'] = "success";
} catch (Exception $e) {
    $db->rollBack();
    $_SESSION['admin_message'] = "Erro ao atualizar status.";
    $_SESSION['admin_message_type'] = "danger";
}

header("Location: visualizar_usuario.php?id=" . $user_id);
exit;
