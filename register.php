<!-- register.php -->
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro - Atletica</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h2 class="text-center mb-4">Cadastro de Usuário</h2>
    <form action="register.php" method="POST" class="w-50 mx-auto">
        <!-- Campos do formulário -->
        <!-- Os campos são os mesmos do exemplo anterior -->
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
include 'includes/db.php';
require_once '/path/to/PHPMailer/class.phpmailer.php'; // Ajuste o caminho para a localização do PHPMailer

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Dados do formulário
    $full_name = $_POST['full_name'];
    $cpf = $_POST['cpf'];
    $birthdate = $_POST['birthdate'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $terms = $_POST['terms'];
    $social_media = $_POST['social_media'];
    $username = strtolower(preg_replace('/\s+/', '-', $full_name)); // Criar o nome de usuário a partir do nome completo

    // Validações
    if ($password !== $confirm_password) {
        echo "As senhas não coincidem!";
        exit;
    }
    if ($terms !== 'yes') {
        echo "Você deve aceitar os termos de uso.";
        exit;
    }

    try {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $verification_code = md5(rand());

        // Inserir no banco de dados
        $stmt = $pdo->prepare("INSERT INTO users (full_name, cpf, birthdate, email, password, social_media, username, verification_code) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$full_name, $cpf, $birthdate, $email, $hashed_password, $social_media, $username, $verification_code]);

        // Enviar email de confirmação
        $mail = new PHPMailer();
        $mail->isSMTP();
        $mail->Host = 'smtp.seuprovedor.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'seuemail@seuprovedor.com';
        $mail->Password = 'suasenha';
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;

        $mail->setFrom('seuemail@seuprovedor.com', 'Atletica');
        $mail->addAddress($email, $full_name);

        $mail->isHTML(true);
        $mail->Subject = 'Confirme seu cadastro';
        $mail->Body    = "Clique no link para confirmar seu cadastro: <a href='http://seusite.com/verify.php?code=$verification_code'>Confirmar Email</a>";

        if ($mail->send()) {
            echo "Cadastro realizado com sucesso. Verifique seu email para confirmação.";
        } else {
            echo "Erro ao enviar email: " . $mail->ErrorInfo;
        }
    } catch (Exception $e) {
        echo "Erro ao registrar: " . $e->getMessage();
    }
}
?>
