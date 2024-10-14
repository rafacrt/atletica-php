<?php
function sendConfirmationEmail($email, $username) {
    // Cabeçalhos do email
    $to = $email;  // Destinatário
    $subject = 'Confirmação de Cadastro';  // Assunto do email
    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
    $headers .= "From: Link Manager <webmaster@rajo.com.br>" . "\r\n";  // Cabeçalho "From"

    // Corpo do email em HTML
    $message = "
    <html>
    <head>
        <title>Confirmação de Cadastro</title>
    </head>
    <body>
        <p>Olá, $username</p>
        <p>Obrigado por se registrar! Por favor, confirme seu email clicando no link abaixo:</p>
        <p><a href='http://projetos.rajo.com.br/atletica/confirm.php?email=$email'>Confirmar Email</a></p>
    </body>
    </html>";

    // Enviando o email
    if (mail($to, $subject, $message, $headers)) {
        return true;  // Email enviado com sucesso
    } else {
        return false;  // Falha no envio do email
    }
}
?>
