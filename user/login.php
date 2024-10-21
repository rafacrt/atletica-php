<?php
require '../includes/db_connect.php';
session_start();

$errors = [];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    // Verifica se o usuário existe e se a conta está ativa
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = :email AND is_active = 1");
    $stmt->execute(['email' => $email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {
        // Armazena o nome de usuário na sessão e redireciona para o painel
        $_SESSION['username'] = $user['username'];
        header("Location: dashboard.php");
        exit();
    } else {
        $errors[] = "Email ou senha incorretos, ou conta não ativada.";
    }
}
?>

<?php include '../includes/header.php'; ?>

<div class="container mt-5">
    <h2>Login</h2>
    <?php if (!empty($errors)): ?>
        <div class="alert alert-danger">
            <ul>
                <?php foreach ($errors as $error): ?>
                    <li><?= $error; ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>
    
    <form action="login.php" method="POST">
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
        </div>
        <button type="submit" class="btn btn-primary btn-block">Login</button>
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
</script>


<?php include '../includes/footer.php'; ?>