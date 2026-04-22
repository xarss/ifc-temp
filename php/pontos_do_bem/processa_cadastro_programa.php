<?php
session_start();
require_once 'config/database.php';
require_once '../php/PHPMailer/PHPMailer.php';
require_once '../php/PHPMailer/SMTP.php';
require_once '../php/PHPMailer/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $database = new Database();
    $db = $database->getConnection();

    $cpf = preg_replace('/[^0-9]/', '', $_POST['cpf']);
    $whatsapp = preg_replace('/[^0-9]/', '', $_POST['whatsapp']);
    $valor = floatval($_POST['valor']);

    // Validações
    if (strlen($cpf) !== 11) {
        $_SESSION['error_message'] = "CPF inválido.";
        header("Location: cadastro_programa.php");
        exit;
    }

    if (strlen($whatsapp) < 10 || strlen($whatsapp) > 11) {
        $_SESSION['error_message'] = "Número de WhatsApp inválido.";
        header("Location: cadastro_programa.php");
        exit;
    }

    // Validar arquivo
    if (!isset($_FILES['comprovante']) || $_FILES['comprovante']['error'] !== UPLOAD_ERR_OK) {
        $_SESSION['error_message'] = "Erro no upload do comprovante.";
        header("Location: cadastro_programa.php");
        exit;
    }

    $file = $_FILES['comprovante'];
    $allowed = ['pdf', 'jpg', 'jpeg', 'png'];
    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

    if (!in_array($ext, $allowed)) {
        $_SESSION['error_message'] = "Formato de arquivo não permitido.";
        header("Location: cadastro_programa.php");
        exit;
    }

    if ($file['size'] > 5 * 1024 * 1024) { // 5MB
        $_SESSION['error_message'] = "Arquivo muito grande. Máximo: 5MB";
        header("Location: cadastro_programa.php");
        exit;
    }

    // Buscar informações do usuário
    $stmt = $db->prepare("SELECT nome, email FROM users WHERE id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // Enviar e-mail com comprovante
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
        $mail->setFrom($user['email'], $user['nome']);
        $mail->addAddress('cadastrodeapadrinhamento@institutoficacomigo.com.br');

        // Anexo
        $mail->addAttachment($file['tmp_name'], $file['name']);

        // Conteúdo
        $mail->isHTML(true);
        $mail->Subject = 'Novo Cadastro - Programa Pontos do Bem';
        $mail->Body = "
            <h2>Novo Cadastro no Programa Pontos do Bem</h2>
            <p><strong>Nome:</strong> {$user['nome']}</p>
            <p><strong>Email:</strong> {$user['email']}</p>
            <p><strong>CPF:</strong> " . substr($cpf, 0, 3) . "." . substr($cpf, 3, 3) . "." . substr($cpf, 6, 3) . "-" . substr($cpf, 9, 2) . "</p>
            <p><strong>WhatsApp:</strong> (" . substr($whatsapp, 0, 2) . ") " . substr($whatsapp, 2, 5) . "-" . substr($whatsapp, 7) . "</p>
            <p><strong>Valor Mensal:</strong> R$ " . number_format($valor, 2, ',', '.') . "</p>
        ";

        $mail->send();

        // Atualizar dados do usuário
        $stmt = $db->prepare("UPDATE users SET cpf = ?, whatsapp = ?, valor_mensal = ? WHERE id = ?");
        $stmt->execute([$cpf, $whatsapp, $valor, $_SESSION['user_id']]);

        $_SESSION['success_message'] = "Cadastro realizado com sucesso! Aguarde a confirmação do pagamento.";
        header("Location: painel_usuario.php");
        exit;

    } catch (Exception $e) {
        $_SESSION['error_message'] = "Erro ao enviar o comprovante. Por favor, tente novamente.";
        header("Location: cadastro_programa.php");
        exit;
    }
} else {
    header("Location: cadastro_programa.php");
    exit;
}
