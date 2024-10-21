<?php
session_start();
require '../includes/db_connect.php';

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Recupera as informações do usuário logado
$stmt = $conn->prepare("SELECT * FROM users WHERE username = :username");
$stmt->execute(['username' => $_SESSION['username']]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

$errors = [];
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $full_name = trim($_POST['full_name']);
    $theme_color = trim($_POST['theme_color']);
    $profile_image = null;

    // Verificar se uma nova imagem de perfil foi enviada
    if (!empty($_FILES['profile_image']['name'])) {
        $target_dir = "../assets/img/";
        $profile_image = $target_dir . basename($_FILES["profile_image"]["name"]);
        $uploadOk = true;

        if (move_uploaded_file($_FILES["profile_image"]["tmp_name"], $profile_image)) {
            // Atualiza a imagem de perfil no banco de dados
            $stmt = $conn->prepare("UPDATE users SET profile_image = :profile_image WHERE id = :id");
            $stmt->execute(['profile_image' => $profile_image, 'id' => $user['id']]);
        } else {
            $errors[] = "Erro ao fazer upload da imagem.";
        }
    }

    // Atualiza nome completo e cor de tema
    $stmt = $conn->prepare("UPDATE users SET full_name = :full_name, theme_color = :theme_color WHERE id = :id");
    $stmt->execute([
        'full_name' => $full_name,
        'theme_color' => $theme_color,
        'id' => $user['id']
    ]);

    $success = "Perfil atualizado com sucesso!";
}
?>

<?php include '../includes/header.php'; ?>

<div class="container mt-5">
    <h2>Editar Perfil</h2>

    <?php if (!empty($success)): ?>
        <div class="alert alert-success">
            <?= $success; ?>
        </div>
    <?php endif; ?>

    <form action="profile.php" method="POST" enctype="multipart/form-data">
        <div class="form-group">
            <label for="full_name">Nome Completo</label>
            <input type="text" name="full_name" id="full_name" class="form-control" value="<?= htmlspecialchars($user['full_name']); ?>">
        </div>

        <div class="form-group">
            <label for="theme_color">Cor do Tema</label>
            <input type="color" name="theme_color" id="theme_color" class="form-control" value="<?= htmlspecialchars($user['theme_color']); ?>">
        </div>

        <div class="form-group">
            <label for="profile_image">Foto de Perfil</label>
            <?php if (!empty($user['profile_image'])): ?>
                <button type="button" class="btn btn-secondary" data-toggle="modal" data-target="#profileImageModal">Mudar Foto de Perfil</button>
            <?php else: ?>
                <input type="file" name="profile_image" id="profile_image" class="form-control">
            <?php endif; ?>
        </div>

        <button type="submit" class="btn btn-primary">Salvar Alterações</button>
    </form>
</div>

<!-- Modal para mudar a foto de perfil -->
<div class="modal fade" id="profileImageModal" tabindex="-1" role="dialog" aria-labelledby="profileImageModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="profileImageModalLabel">Mudar Foto de Perfil</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="profile.php" method="POST" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="profile_image">Nova Foto de Perfil</label>
                        <input type="file" name="profile_image" id="profile_image" class="form-control">
                    </div>
                    <button type="submit" class="btn btn-primary">Atualizar Foto</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>