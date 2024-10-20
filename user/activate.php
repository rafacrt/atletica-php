<?php
require '../includes/db_connect.php';

$activation_success = false;
$error_message = '';

if (isset($_GET['code'])) {
    $activation_code = $_GET['code'];

    // Verifica se o código de ativação existe no banco de dados
    $stmt = $conn->prepare("SELECT id FROM users WHERE activation_code = :activation_code AND is_active = 0");
    $stmt->execute(['activation_code' => $activation_code]);

    if ($stmt->rowCount() > 0) {
        // Ativa a conta do usuário
        $stmt = $conn->prepare("UPDATE users SET is_active = 1, activation_code = NULL WHERE activation_code = :activation_code");
        $stmt->execute(['activation_code' => $activation_code]);
        $activation_success = true;
    } else {
        $error_message = "Código de ativação inválido ou conta já ativada.";
    }
} else {
    $error_message = "Nenhum código de ativação fornecido.";
}
?>

<?php include '../includes/header.php'; ?>

<div class="container mt-5">
    <?php if ($activation_success): ?>
        <div class="alert alert-success text-center shadow-sm rounded p-4">
            <h4 class="font-weight-bold">Conta Ativada com Sucesso!</h4>
            <p>Sua conta foi ativada. Agora você pode <a href="login.php" class="text-primary font-weight-bold">fazer login</a>.</p>
        </div>
    <?php else: ?>
        <div class="alert alert-danger text-center shadow-sm rounded p-4">
            <h4 class="font-weight-bold">Erro na Ativação</h4>
            <p><?= $error_message; ?></p>
        </div>
    <?php endif; ?>
</div>

<?php include '../includes/footer.php'; ?>
