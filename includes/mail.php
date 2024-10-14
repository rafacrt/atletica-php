<?php
// Incluindo os arquivos do PHPMailer, subindo dois níveis
require '../../PHPMailer/src/Exception.php';
require '../../PHPMailer/src/PHPMailer.php';
require '../../PHPMailer/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

function sendConfirmationEmail($email, $username) {
    $mail = new PHPMailer(true);

    try {
        // Configurações do servidor SMTP
        $mail->isSMTP();
        $mail->Host = 'mail.rajo.com.br';
        $mail->SMTPAuth = true;
        $mail->Username = 'webmaster@rajo.com.br';
        $mail->Password = '@Rafa253325';
        $mail->SMTPSecure = 'ssl';
        $mail->Port = 465;

        // Remetente e destinatário
        $mail->setFrom('webmaster@rajo.com.br', 'Link Manager');
        $mail->addAddress($email);

        // Conteúdo do email
        $mail->isHTML(true);
        $mail->Subject = 'Confirmação de Cadastro';
        $mail->Body    = "Olá $username, <br>Obrigado por se registrar! Por favor, confirme seu email clicando no link abaixo:<br><a href='http://seusite.com/confirm.php?email=$email'>Confirmar Email</a>";

        $mail->send();
        return true;
    } catch (Exception $e) {
        echo "Erro ao enviar email: {$mail->ErrorInfo}";
        return false;
    }
}
?>
