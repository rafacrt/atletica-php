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
    $uploadOk = true;

    // Verificar se o upload foi bem-sucedido
    if (move_uploaded_file($_FILES["profile_image"]["tmp_name"], $profile_image)) {
        $stmt = $conn->prepare("UPDATE users SET profile_image = :profile_image, theme_color = :theme_color WHERE id = :id");
        $stmt->execute([
            'profile_image' => basename($_FILES["profile_image"]["name"]),
            'theme_color' => $_POST['theme_color'],
            'id' => $user['id']
        ]);
        
        // Redirecionar para o perfil público
        header("Location: ../public_profile.php?username=" . $_SESSION['username']);
        exit();
    } else {
        $errors[] = "Erro ao fazer upload da imagem.";
    }
}
?>

<?php include '../includes/header.php'; ?>

<div class="container mt-5">
    <h2 class="text-center">Editar Perfil</h2>

    <?php if (!empty($errors)): ?>
        <div class="alert alert-danger text-center shadow-sm rounded p-4">
            <ul class="list-unstyled">
                <?php foreach ($errors as $error): ?>
                    <li><?= $error; ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <form action="profile.php" method="POST" enctype="multipart/form-data" class="shadow-sm p-4 bg-white rounded">
        <div class="form-group">
            <label for="theme_color">Cor do Tema</label>
            <input type="color" name="theme_color" id="theme_color" class="form-control" value="<?= htmlspecialchars($user['theme_color']); ?>">
        </div>
        <div class="form-group">
            <label for="profile_image">Foto de Perfil</label>
            <input type="file" name="profile_image" id="profile_image" class="form-control form-control-lg">
        </div>
        <button type="submit" class="btn btn-primary btn-lg btn-block">Salvar Alterações</button>
    </form>
</div>

<?php include '../includes/footer.php'; ?>
