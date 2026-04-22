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
$tipo_email = $_POST['tipo_email'];
$mensagem_personalizada = isset($_POST['mensagem']) ? trim($_POST['mensagem']) : '';

// Buscar informações do usuário
$stmt = $db->prepare("SELECT nome, email FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    $_SESSION['admin_message'] = "Usuário não encontrado.";
    $_SESSION['admin_message_type'] = "danger";
    header("Location: visualizar_usuario.php?id=" . $user_id);
    exit;
}

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

    switch ($tipo_email) {
        case 'pagamento_aprovado':
            $mail->Subject = 'Pagamento Aprovado - Pontos do Bem';
            $mail->Body = "
                <h2>Olá, {$user['nome']}!</h2>
                <p>Seu pagamento foi aprovado com sucesso!</p>
                <p>Você recebeu +1 ponto em seu programa Pontos do Bem.</p>
                <p>Agradecemos sua participação!</p>
                <br>
                <p>Atenciosamente,<br>Instituto Fica Comigo</p>
            ";
            break;

        case 'pagamento_pendente':
            $mail->Subject = 'Pagamento Pendente - Pontos do Bem';
            $mail->Body = "
                <h2>Olá, {$user['nome']}!</h2>
                <p>Ainda não consta o pagamento referente ao mês " . date('m/Y') . ".</p>
                <p>Por favor, efetue o pagamento para continuar participando do programa Pontos do Bem.</p>
                <p>Qualquer dúvida, entre em contato conosco.</p>
                <br>
                <p>Atenciosamente,<br>Instituto Fica Comigo</p>
            ";
            break;

        case 'personalizado':
            if (empty($mensagem_personalizada)) {
                throw new Exception('Mensagem personalizada não pode estar vazia.');
            }
            $mail->Subject = 'Mensagem do Instituto Fica Comigo';
            $mail->Body = "
                <h2>Olá, {$user['nome']}!</h2>
                " . nl2br(htmlspecialchars($mensagem_personalizada)) . "
                <br><br>
                <p>Atenciosamente,<br>Instituto Fica Comigo</p>
            ";
            break;

        default:
            throw new Exception('Tipo de e-mail inválido.');
    }

    $mail->send();
    
    $_SESSION['admin_message'] = "E-mail enviado com sucesso!";
    $_SESSION['admin_message_type'] = "success";
} catch (Exception $e) {
    $_SESSION['admin_message'] = "Erro ao enviar e-mail: " . $mail->ErrorInfo;
    $_SESSION['admin_message_type'] = "danger";
}

header("Location: visualizar_usuario.php?id=" . $user_id);
exit;
