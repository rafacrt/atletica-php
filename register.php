
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
        <div class="mb-3">
            <label for="full_name" class="form-label">Nome Completo</label>
            <input type="text" class="form-control" id="full_name" name="full_name" required>
        </div>
        <div class="mb-3">
            <label for="cpf" class="form-label">CPF</label>
            <input type="text" class="form-control" id="cpf" name="cpf" required>
        </div>
        <div class="mb-3">
            <label for="birthdate" class="form-label">Data de Nascimento</label>
            <input type="date" class="form-control" id="birthdate" name="birthdate" required>
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">E-mail</label>
            <input type="email" class="form-control" id="email" name="email" required>
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Senha</label>
            <input type="password" class="form-control" id="password" name="password" required>
        </div>
        <div class="mb-3">
            <label for="confirm_password" class="form-label">Confirmar Senha</label>
            <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
        </div>
        <div class="mb-3">
            <label for="terms" class="form-label">Aceita os Termos de Uso?</label>
            <select class="form-select" id="terms" name="terms" required>
                <option value="">Selecione...</option>
                <option value="yes">Sim</option>
                <option value="no">Não</option>
            </select>
        </div>
        <h4 class="mt-4">Dados da Atlética</h4>
        <div class="mb-3">
            <label for="social_media" class="form-label">Link para Mídia Social da Atlética</label>
            <input type="url" class="form-control" id="social_media" name="social_media" required>
        </div>
        <button type="submit" class="btn btn-primary w-100">Registrar</button>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
include 'includes/db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $full_name = $_POST['full_name'];
    $cpf = $_POST['cpf'];
    $birthdate = $_POST['birthdate'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $terms = $_POST['terms'];
    $social_media = $_POST['social_media'];

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
        $stmt = $pdo->prepare("INSERT INTO users (full_name, cpf, birthdate, email, password, social_media) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$full_name, $cpf, $birthdate, $email, $hashed_password, $social_media]);
        header('Location: login.php');
    } catch (Exception $e) {
        echo "Erro ao registrar: " . $e->getMessage();
    }
}
?>
