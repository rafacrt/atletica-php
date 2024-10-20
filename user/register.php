<?php
require '../includes/db_connect.php';

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
        // Verificação da força da senha
        if (strlen($password) < 8 || !preg_match('/[A-Z]/', $password) || !preg_match('/[a-z]/', $password) || !preg_match('/[0-9]/', $password) || !preg_match('/[\W]/', $password)) {
            $errors[] = "A senha deve ter pelo menos 8 caracteres, incluindo letras maiúsculas, minúsculas, números e símbolos.";
        } else {
            // Verificar se o email ou nome de usuário já estão registrados
            $stmt = $conn->prepare("SELECT id FROM users WHERE email = :email OR username = :username");
            $stmt->execute(['email' => $email, 'username' => $username]);

            if ($stmt->rowCount() > 0) {
                $existing_user = $stmt->fetch(PDO::FETCH_ASSOC);
                if ($existing_user['email'] == $email) {
                    $errors[] = "O email já está registrado. Tente usar outro.";
                }
                if ($existing_user['username'] == $username) {
                    $errors[] = "O nome de usuário já está registrado. Tente usar outro.";
                }
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
                $subject = "Confirme sua conta";
                $message = "Olá $username,\n\n";
                $message .= "Por favor, clique no link abaixo para ativar sua conta:\n\n";
                $message .= "https://projetos.rajo.com.br/atletica/user/activate.php?code=$activation_code\n\n";
                $message .= "Obrigado!";
                $headers = "From: Meu Sistema <no-reply@projetos.rajo.com.br>\r\n";
                $headers .= "Reply-To: no-reply@projetos.rajo.com.br\r\n";
                $headers .= "X-Mailer: PHP/" . phpversion();

                if (mail($email, $subject, $message, $headers)) {
                    // Exibe mensagem de sucesso e redireciona o usuário
                    $success = "Cadastro realizado com sucesso. Por favor, verifique seu email para ativar sua conta.";
                    header("Location: /index.php?success=1");
                    exit();
                } else {
                    error_log("Falha no envio de email para: $email");
                    $errors[] = "Ocorreu um erro ao enviar o email de confirmação. Verifique se o servidor suporta o envio de emails.";
                }
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
                <input type="text" name="username" id="username" class="form-control form-control-lg" required>
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" name="email" id="email" class="form-control form-control-lg" required>
            </div>
            <div class="form-group">
                <label for="password">Senha</label>
                <input type="password" name="password" id="password" class="form-control form-control-lg" required>
                <small id="strengthMessage" class="form-text"></small>
            </div>
            <div class="form-group">
                <label for="confirm_password">Confirme sua Senha</label>
                <input type="password" name="confirm_password" id="confirm_password" class="form-control form-control-lg" required>
            </div>
            <button type="submit" class="btn btn-primary btn-lg btn-block">Cadastrar</button>
        </form>
    <?php endif; ?>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const passwordInput = document.getElementById('password');
        const strengthMessage = document.getElementById('strengthMessage');

        passwordInput.addEventListener('input', function() {
            const password = passwordInput.value;
            let strength = 0;

            if (password.length >= 8) strength += 1;
            if (/[A-Z]/.test(password)) strength += 1;
            if (/[a-z]/.test(password)) strength += 1;
            if (/[0-9]/.test(password)) strength += 1;
            if (/[\W]/.test(password)) strength += 1;

            switch (strength) {
                case 1:
                case 2:
                    strengthMessage.textContent = 'Senha Fraca';
                    strengthMessage.style.color = 'red';
                    break;
                case 3:
                    strengthMessage.textContent = 'Senha Média';
                    strengthMessage.style.color = 'orange';
                    break;
                case 4:
                case 5:
                    strengthMessage.textContent = 'Senha Forte';
                    strengthMessage.style.color = 'green';
                    break;
                default:
                    strengthMessage.textContent = '';
            }
        });
    });
</script>

<?php include '../includes/footer.php'; ?>