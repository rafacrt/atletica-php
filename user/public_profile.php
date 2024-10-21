<?php
require '../includes/db_connect.php';

// Verifica se o nome de usuário foi passado na URL
if (isset($_GET['username'])) {
    $username = trim($_GET['username']);
    
    // Recupera os dados do usuário com base no nome de usuário
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = :username");
    $stmt->execute(['username' => $username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // Se o usuário não for encontrado, exibe mensagem de erro
    if (!$user) {
        echo "<h1>Usuário não encontrado!</h1>";
        exit();
    }

    // Recupera os links do usuário
    $stmt_links = $conn->prepare("SELECT * FROM links WHERE user_id = :user_id ORDER BY position ASC");
    $stmt_links->execute(['user_id' => $user['id']]);
    $links = $stmt_links->fetchAll(PDO::FETCH_ASSOC);

    // Recupera os badges do usuário
    $stmt_badges = $conn->prepare("SELECT * FROM badges WHERE user_id = :user_id");
    $stmt_badges->execute(['user_id' => $user['id']]);
    $badges = $stmt_badges->fetchAll(PDO::FETCH_ASSOC);
} else {
    echo "Nenhum nome de usuário fornecido.";
    exit();
}
?>

<?php include '../includes/header.php'; ?>

<!-- Página de Perfil Público -->
<div class="container mt-5">
    <div class="row align-items-center">
        <!-- Foto de perfil em círculo -->
        <div class="col-md-2 text-center">
            <?php if (!empty($user['profile_image'])): ?>
                <img src="../assets/img/<?= htmlspecialchars($user['profile_image']); ?>" alt="Foto de Perfil" class="img-thumbnail rounded-circle" width="150">
            <?php else: ?>
                <img src="../assets/img/default_profile.png" alt="Foto de Perfil Padrão" class="img-thumbnail rounded-circle" width="150">
            <?php endif; ?>
        </div>
        <!-- Nome de usuário -->
        <div class="col-md-10">
            <h2><?= htmlspecialchars($user['username']); ?></h2>
            <p><?= htmlspecialchars($user['description']); ?></p>
        </div>
    </div>

    <!-- Badges -->
    <div class="row mt-4">
        <?php foreach ($badges as $badge): ?>
            <div class="col-md-3">
                <div class="badge-item text-center" style="background-color: <?= htmlspecialchars($user['theme_color']); ?>; border-radius: 10px;">
                    <i class="<?= htmlspecialchars($badge['icon']); ?> fa-2x"></i>
                    <p><?= htmlspecialchars($badge['title']); ?></p>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <!-- Links -->
    <div class="row mt-5">
        <h3 class="col-12">Links</h3>
        <?php foreach ($links as $link): ?>
            <div class="col-md-12 mb-3">
                <a href="<?= htmlspecialchars($link['url']); ?>" class="btn btn-block" style="background-color: <?= htmlspecialchars($user['theme_color']); ?>;">
                    <?= htmlspecialchars($link['title']); ?>
                </a>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<?php include '../includes/footer.php'; ?>

<!-- Custom CSS -->
<style>
    .badge-item {
        padding: 20px;
        color: white;
    }

    .btn-block {
        width: 100%;
        text-align: center;
        padding: 15px;
        color: white;
        border: none;
    }
</style>
