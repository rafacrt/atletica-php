<?php
require '../includes/db_connect.php';
require '../../PHPMailer/PHPMailerAutoload.php';  // PHPMailer está dois níveis acima

$errors = [];
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm_password']);

    // Validação básica
    if (empty($username) || empty($email) || empty($password) || empty($confirm_password)) {
        $errors[] = "Todos os campos são obrigatórios.";
    } elseif ($password !== $confirm_password) {
        $errors[] = "As senhas não coincidem.";
    } else {
        // Verificar se o email já está registrado
        $stmt = $conn->prepare("SELECT id FROM users WHERE email = :email OR username = :username");
        $stmt->execute(['email' => $email, 'username' => $username]);
        if ($stmt->rowCount() > 0) {
            $errors[] = "O nome de usuário ou email já estão registrados.";
        } else {
            // Gerar código de ativação e hash da senha
            $activation_code = md5(rand());
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // Inserir novo usuário no banco de dados
            $stmt = $conn->prepare("INSERT INTO users (username, email, password, activation_code) VALUES (:username, :email, :password, :activation_code)");
            $stmt->execute([
                'username' => $username,
                'email' => $email,
                'password' => $hashed_password,
                'activation_code' => $activation_code
            ]);

            // Enviar email de ativação
            $mail = new PHPMailer();
            $mail->isSMTP();
            $mail->Host = 'smtp.example.com';  // Insira o host SMTP
            $mail->SMTPAuth = true;
            $mail->Username = 'seu-email@example.com';  // Insira o email SMTP
            $mail->Password = 'sua-senha';  // Senha do email
            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;

            $mail->setFrom('seu-email@example.com', 'Meu Sistema');
            $mail->addAddress($email);
            $mail->isHTML(true);
            $mail->Subject = 'Confirme sua conta';
            $mail->Body = "Olá $username,<br><br>Por favor, clique no link abaixo para ativar sua conta:<br><br><a href='http://localhost/user/activate.php?code=$activation_code'>Ativar Conta</a>";

            if ($mail->send()) {
                $success = "Um email de confirmação foi enviado. Por favor, verifique sua caixa de entrada.";
            } else {
                $errors[] = "Ocorreu um erro ao enviar o email de confirmação.";
            }
        }
    }
}
?>

<?php include '../includes/header.php'; ?>

<div class="container mt-5">
    <h2>Cadastre-se</h2>
    <?php if (!empty($errors)): ?>
        <div class="alert alert-danger">
            <ul>
                <?php foreach ($errors as $error): ?>
                    <li><?= $error; ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <?php if (!empty($success)): ?>
        <div class="alert alert-success">
            <?= $success; ?>
        </div>
    <?php else: ?>
        <form action="register.php" method="POST">
            <div class="form-group">
                <label for="username">Nome de Usuário</label>
                <input type="text" name="username" id="username" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" name="email" id="email" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="password">Senha</label>
                <input type="password" name="password" id="password" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="confirm_password">Confirme sua Senha</label>
                <input type="password" name="confirm_password" id="confirm_password" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary">Cadastrar</button>
        </form>
    <?php endif; ?>
</div>

<?php include '../includes/footer.php'; ?>
