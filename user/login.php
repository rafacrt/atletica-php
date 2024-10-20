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
    <div class="row justify-content-center">
        <div class="col-md-6">
            <h2 class="text-center">Login</h2>
            
            <?php if (!empty($errors)): ?>
                <div class="alert alert-danger text-center shadow-sm rounded p-3">
                    <ul class="list-unstyled">
                        <?php foreach ($errors as $error): ?>
                            <li><?= $error; ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>
            
            <form action="login.php" method="POST" class="shadow-sm p-4 bg-white rounded">
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" name="email" id="email" class="form-control form-control-lg" required>
                </div>
                <div class="form-group">
                    <label for="password">Senha</label>
                    <input type="password" name="password" id="password" class="form-control form-control-lg" required>
                </div>
                <button type="submit" class="btn btn-primary btn-lg btn-block">Login</button>
                <div class="mt-3 text-center">
                    <a href="register.php" class="text-primary">Não tem uma conta? Cadastre-se</a>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
