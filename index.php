<?php
require 'includes/db_connect.php';

// Recupera os usuários cadastrados
$stmt = $conn->prepare("SELECT username, full_name, profile_image FROM users WHERE is_active = 1");
$stmt->execute();
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<?php include 'includes/header.php'; ?>

<div class="container mt-5">
    <h2 class="text-center">Usuários Cadastrados</h2>
    <div class="row justify-content-center">
        <?php foreach ($users as $user): ?>
            <div class="col-md-4 text-center mb-5">
                <div class="user-card">
                    <!-- Foto de perfil redonda -->
                    <div class="profile-image-wrapper">
                        <?php if (!empty($user['profile_image'])): ?>
                            <img src="assets/img/<?= htmlspecialchars($user['profile_image']); ?>" alt="Foto de Perfil" class="profile-image img-fluid rounded-circle">
                        <?php else: ?>
                            <img src="assets/img/default_profile.png" alt="Foto de Perfil Padrão" class="profile-image img-fluid rounded-circle">
                        <?php endif; ?>
                    </div>
                    <!-- Nome de usuário e nome completo -->
                    <p class="username small mt-2"><?= htmlspecialchars($user['username']); ?></p>
                    <p class="full-name h5"><?= htmlspecialchars($user['full_name']); ?></p>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<?php include 'includes/footer.php'; ?>