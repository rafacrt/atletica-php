<?php
require '../includes/db_connect.php';

// Obtém o nome de usuário da URL
$username = isset($_GET['username']) ? trim($_GET['username']) : '';

// Recupera os dados do usuário
$stmt = $conn->prepare("SELECT * FROM users WHERE username = :username");
$stmt->execute(['username' => $username]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    echo "Usuário não encontrado!";
    exit();
}

// Recupera os links do usuário
$stmt_links = $conn->prepare("SELECT * FROM links WHERE user_id = :user_id ORDER BY position ASC");
$stmt_links->execute(['user_id' => $user['id']]);
$links = $stmt_links->fetchAll(PDO::FETCH_ASSOC);
?>

<?php include '../includes/header.php'; ?>

<div class="container text-center mt-5">
    <h2><?= htmlspecialchars($user['username']); ?></h2>
    
    <?php if (!empty($user['profile_image'])): ?>
        <img src="../assets/img/<?= htmlspecialchars($user['profile_image']); ?>" alt="Foto de Perfil" class="img-thumbnail rounded-circle" width="150">
    <?php else: ?>
        <img src="../assets/img/default_profile.png" alt="Foto de Perfil Padrão" class="img-thumbnail rounded-circle" width="150">
    <?php endif; ?>
    
    <div class="mt-4">
        <?php if (count($links) > 0): ?>
            <ul class="list-group">
                <?php foreach ($links as $link): ?>
                    <li class="list-group-item">
                        <a href="<?= htmlspecialchars($link['url']); ?>" target="_blank"><?= htmlspecialchars($link['title']); ?></a>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <p>Este usuário ainda não adicionou links.</p>
        <?php endif; ?>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
