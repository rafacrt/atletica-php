<?php
session_start();
require '../includes/db_connect.php';

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Recupera informações do usuário logado
$stmt = $conn->prepare("SELECT * FROM users WHERE username = :username");
$stmt->execute(['username' => $_SESSION['username']]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

$errors = [];
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Lógica para tratar uploads de imagem
    $target_dir = "../assets/img/";
    $profile_image = $target_dir . basename($_FILES["profile_image"]["name"]);
    $cover_image = $target_dir . basename($_FILES["cover_image"]["name"]);
    $uploadOk = true;

    // Verificar se o upload foi bem-sucedido
    if (move_uploaded_file($_FILES["profile_image"]["tmp_name"], $profile_image)) {
        $stmt = $conn->prepare("UPDATE users SET profile_image = :profile_image WHERE id = :id");
        $stmt->execute(['profile_image' => $profile_image, 'id' => $user['id']]);
        $success = "Foto de perfil atualizada com sucesso!";
    }

    if (move_uploaded_file($_FILES["cover_image"]["tmp_name"], $cover_image)) {
        $stmt = $conn->prepare("UPDATE users SET cover_image = :cover_image WHERE id = :id");
        $stmt->execute(['cover_image' => $cover_image]);
        $success = "Foto de capa atualizada com sucesso!";
    }
}

?>

<?php include '../includes/header.php'; ?>

<div class="container mt-5">
    <h2 class="text-center">Editar Perfil</h2>

    <?php if (!empty($success)): ?>
        <div class="alert alert-success text-center shadow-sm rounded p-4">
            <?= $success; ?>
        </div>
    <?php endif; ?>

    <form action="profile.php" method="POST" enctype="multipart/form-data" class="shadow-sm p-4 bg-white rounded">
        <div class="form-group">
            <label for="full_name">Nome Completo</label>
            <input type="text" name="full_name" id="full_name" class="form-control form-control-lg" value="<?= htmlspecialchars($user['full_name']); ?>">
        </div>
        <div class="form-group">
            <label for="bio">Biografia</label>
            <textarea name="bio" id="bio" class="form-control form-control-lg"><?= htmlspecialchars($user['bio']); ?></textarea>
        </div>
        <div class="form-group">
            <label for="theme_color">Cor do Tema</label>
            <input type="color" name="theme_color" id="theme_color" class="form-control" value="<?= htmlspecialchars($user['theme_color']); ?>">
        </div>
        <div class="form-group">
            <label for="profile_image">Foto de Perfil</label>
            <input type="file" name="profile_image" id="profile_image" class="form-control form-control-lg">
        </div>
        <div class="form-group">
            <label for="cover_image">Foto de Capa</label>
            <input type="file" name="cover_image" id="cover_image" class="form-control form-control-lg">
        </div>

        <button type="submit" class="btn btn-primary btn-lg btn-block">Salvar Alterações</button>
    </form>
</div>

<?php include '../includes/footer.php'; ?>
