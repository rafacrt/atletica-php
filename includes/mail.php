<?php
require '../../PHPMailer/src/Exception.php';
require '../../PHPMailer/src/PHPMailer.php';
require '../../PHPMailer/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

function sendConfirmationEmail($email, $username) {
    $mail = new PHPMailer(true);

    try {
        // Configurações do servidor SMTP
        $mail->isSMTP();                                 // Definir para usar SMTP
        $mail->Host = 'mail.rajo.com.br';                // Defina o host SMTP
        $mail->SMTPAuth = true;                          // Ativar autenticação SMTP
        $mail->Username = 'webmaster@rajo.com.br';       // Insira seu email completo
        $mail->Password = '@Rafa253325';                 // Insira sua senha de email
        $mail->SMTPSecure = 'ssl';                       // Usar SSL para conexão segura
        $mail->Port = 465;                               // Porta para SSL

        // Definindo o remetente e destinatário
        $mail->setFrom('webmaster@rajo.com.br', 'Link Manager');   // Definir o remetente
        $mail->addAddress($email);                                // Adicionar o destinatário

        // Configurar o conteúdo do email
        $mail->isHTML(true);                                       // Definir que o email será enviado em formato HTML
        $mail->Subject = 'Confirmação de Cadastro';                // Assunto do email
        $mail->Body    = "Olá $username, <br>Obrigado por se registrar! Por favor, confirme seu email clicando no link abaixo:<br><a href='http://seusite.com/confirm.php?email=$email'>Confirmar Email</a>";

        // Enviar email
        $mail->send();
        return true;
    } catch (Exception $e) {
        // Exibir mensagem de erro, se houver
        echo "Erro ao enviar email: {$mail->ErrorInfo}";
        return false;
    }
}
?>
