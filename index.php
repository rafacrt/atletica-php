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
                    <!-- Link para o perfil do usuário com o formato tradicional -->
                    <a href="/atletica/user/public_profile.php?username=<?= htmlspecialchars($user['username']); ?>">
                        <div class="profile-image-wrapper">
                            <!-- Foto de perfil redonda -->
                            <?php if (!empty($user['profile_image'])): ?>
                                <img src="assets/img/<?= htmlspecialchars($user['profile_image']); ?>" alt="Foto de Perfil" class="profile-image img-fluid rounded-circle">
                            <?php else: ?>
                                <img src="assets/img/default_profile.png" alt="Foto de Perfil Padrão" class="profile-image img-fluid rounded-circle">
                            <?php endif; ?>
                        </div>
                        <!-- Nome de usuário e nome completo -->
                        <p class="username small mt-2"><?= htmlspecialchars($user['username']); ?></p>
                        <p class="full-name h5"><?= htmlspecialchars($user['full_name']); ?></p>
                    </a>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<?php include 'includes/footer.php'; ?>

<!-- Custom CSS for styling and hover effects -->
<style>
    .profile-image-wrapper {
        width: 150px;
        height: 150px;
        margin: 0 auto;
        overflow: hidden;
        position: relative;
    }

    .profile-image {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    /* Efeito ao passar o mouse */
    .profile-image-wrapper:hover .profile-image {
        transform: scale(1.1);
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
    }

    .username {
        font-size: 14px;
        color: #555;
    }

    .full-name {
        font-size: 18px;
        color: #000;
        font-weight: bold;
    }

    /* Centraliza os itens e define o layout */
    .user-card {
        display: flex;
        flex-direction: column;
        align-items: center;
    }

    /* Remove o sublinhado dos links */
    a {
        text-decoration: none;
        color: inherit;
    }
</style>
