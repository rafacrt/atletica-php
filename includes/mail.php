<?php
// Ativar a exibição de todos os erros
error_reporting(E_ALL);
ini_set('display_errors', 1);
?>

<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'path/to/PHPMailer/src/Exception.php';
require 'path/to/PHPMailer/src/PHPMailer.php';
require 'path/to/PHPMailer/src/SMTP.php';

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
        $mail->Body    = "Olá $username, <br>Obrigado por se registrar! Por favor, confirme seu email clicando no link abaixo:<br><a href='http://projetos.rajo.com.br/atletica/confirm.php?email=$email'>Confirmar Email</a>";

        $mail->send();
        return true;
    } catch (Exception $e) {
        return false;
    }
}
?>
