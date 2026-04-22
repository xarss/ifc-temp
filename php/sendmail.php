<?php

$nome = $_POST['name'];
$email = $_POST['email'];
$message = $_POST['message'];

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

$smtpUsername = "resgatesifc@gmail.com";
$smtpPassword = "REDACTED";
$emailFrom = "resgatesifc@gmail.com";
$emailTo = 'resgatesifc@gmail.com';     

$mail = new PHPMailer;
$mail->isSMTP(); 
$mail->SMTPDebug = 0; // 0 = off (for production use) - 1 = client messages - 2 = client and server messages
$mail->Host = "mail.institutoficacomigo.org.br"; // use $mail->Host = gethostbyname('smtp.gmail.com'); // if your network does not support SMTP over IPv6 smtp locaweb: email-ssl.com.br
$mail->Port = 465; // TLS only
$mail->SMTPSecure = 'ssl'; // ssl is depracated
$mail->SMTPAuth = true;
$mail->Username = $smtpUsername;
$mail->Password = $smtpPassword;
$mail->setFrom($emailFrom);
$mail->addAddress($emailTo);
$mail->addAddress(''); //enviar outros endereços
$mail->CharSet = 'UTF-8';
$mail->Subject = 'Formulário de contato Site Instituto Fica Comigo';
$mail->msgHTML("Nome: $nome <br> Email:  $email <br> Mensagem: $message");  


//$mail->msgHTML(file_get_contents('email.php'), __DIR__); //Read an HTML message body from an external file, convert referenced images to embedded,
$mail->AltBody = ''; //HTML messaging not supported
//$mail->addAttachment('arquivo.xlsx'); //Attach an image file

if(!$mail->send()){
    echo "Mailer Error: " . $mail->ErrorInfo;
}else{
    header('Location: contato.php');
    //echo "Confira a sua caixa de entrada de email!<br>Caso você não tenha recebido o email na sua caixa de entrada, verifique a sua caixa de SPAM!";
}

?>