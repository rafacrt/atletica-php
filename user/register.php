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
        // Validação de força da senha menos rigorosa
        if (strlen($password) < 6 || !preg_match('/[A-Z]/', $password) || !preg_match('/[0-9]/', $password)) {
            $errors[] = "A senha deve ter pelo menos 6 caracteres, incluindo letras maiúsculas e números.";
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
                $message = "Olá $username,\n\nPor favor, clique no link abaixo para ativar sua conta:\n\n";
                $message .= "https://projetos.rajo.com.br/atletica/user/activate.php?code=$activation_code\n\nObrigado!";
                $headers = "From: Meu Sistema <no-reply@projetos.rajo.com.br>\r\n";
                $headers .= "Reply-To: no-reply@projetos.rajo.com.br\r\n";
                $headers .= "X-Mailer: PHP/" . phpversion();

                if (mail($email, $subject, $message, $headers)) {
                    $success = "Um email de confirmação foi enviado. Por favor, verifique sua caixa de entrada.";
                    header("Location: https://projetos.rajo.com.br/atletica/index.php?success=1");
                    exit();
                } else {
                    error_log("Falha no envio de email para: $email");
                    $errors[] = "Ocorreu um erro ao enviar o email de confirmação.";
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
            <div class="input-group">
                <input type="password" name="password" id="password" class="form-control" required>
                <div class="input-group-append">
                    <span class="input-group-text">
                        <i class="fas fa-eye" id="togglePassword" style="cursor: pointer;"></i>
                    </span>
                </div>
            </div>
            <small id="strengthMessage" class="form-text text-muted"></small>
            <div class="progress mt-2">
                <div class="progress-bar" id="passwordStrength" role="progressbar" style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
            </div>
        </div>
        <div class="form-group">
            <label for="confirm_password">Confirme sua Senha</label>
            <input type="password" name="confirm_password" id="confirm_password" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary btn-block">Cadastrar</button>
    </form>
</div>

<?php include '../includes/footer.php'; ?>

<script>
    // Mostra/Oculta a senha
    document.getElementById('togglePassword').addEventListener('click', function () {
        const passwordInput = document.getElementById('password');
        const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
        passwordInput.setAttribute('type', type);
        this.classList.toggle('fa-eye-slash');
    });

    // Validação e medidor de força de senha
    document.getElementById('password').addEventListener('input', function () {
        const password = this.value;
        const strengthMessage = document.getElementById('strengthMessage');
        const progressBar = document.getElementById('passwordStrength');
        let strength = 0;

        if (password.length >= 6) strength += 1;
        if (/[A-Z]/.test(password)) strength += 1;
        if (/[a-z]/.test(password)) strength += 1;
        if (/[0-9]/.test(password)) strength += 1;
        if (/[\W]/.test(password)) strength += 1;

        switch (strength) {
            case 1:
            case 2:
                strengthMessage.textContent = 'Senha Fraca';
                progressBar.style.width = '25%';
                progressBar.classList.add('bg-danger');
                progressBar.classList.remove('bg-warning', 'bg-success');
                break;
            case 3:
                strengthMessage.textContent = 'Senha Média';
                progressBar.style.width = '50%';
                progressBar.classList.add('bg-warning');
                progressBar.classList.remove('bg-danger', 'bg-success');
                break;
            case 4:
            case 5:
                strengthMessage.textContent = 'Senha Forte';
                progressBar.style.width = '100%';
                progressBar.classList.add('bg-success');
                progressBar.classList.remove('bg-danger', 'bg-warning');
                break;
            default:
                strengthMessage.textContent = '';
                progressBar.style.width = '0%';
                break;
        }
    });
</script>


<?php include '../includes/footer.php'; ?>
