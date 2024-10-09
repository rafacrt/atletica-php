<!-- dashboard.php -->

<?php
session_start();
include 'includes/db.php'; // Incluir a conexão com o banco de dados

// Verificar se o usuário está autenticado
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel - Atletica</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h2 class="text-center mb-4">Painel do Usuário</h2>
    <p>Bem-vindo, <?php echo htmlspecialchars($user['username']); ?>!</p>
    <!-- Adicione aqui os formulários e funcionalidades do painel -->
</div>
</body>
</html>
