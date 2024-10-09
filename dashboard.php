<!-- dashboard.php -->
<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Consulta para obter dados do usuário
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
    
    <!-- Formulário para Atualizar Perfil -->
    <form action="update_profile.php" method="POST" enctype="multipart/form-data" class="w-50 mx-auto">
        <div class="mb-3">
            <label for="bio" class="form-label">Bio</label>
            <textarea class="form-control" id="bio" name="bio" rows="3"><?php echo $user['bio']; ?></textarea>
        </div>
        <div class="mb-3">
            <label for="profile_photo" class="form-label">Foto de Perfil</label>
            <input type="file" class="form-control" id="profile_photo" name="profile_photo">
        </div>
        <button type="submit" class="btn btn-primary w-100">Atualizar Perfil</button>
    </form>

    <!-- Formulário para Adicionar Links -->
    <form action="add_link.php" method="POST" class="w-50 mx-auto mt-5">
        <h4>Adicionar Link</h4>
        <div class="mb-3">
            <label for="title" class="form-label">Título do Link</label>
            <input type="text" class="form-control" id="title" name="title" required>
        </div>
        <div class="mb-3">
            <label for="url" class="form-label">URL</label>
            <input type="url" class="form-control" id="url" name="url" required>
        </div>
        <button type="submit" class="btn btn-success w-100">Adicionar Link</button>
    </form>
</div>
</body>
</html>
