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
} else {
    echo "Nenhum nome de usuário fornecido.";
    exit();
}
?>

<?php include '../includes/header.php'; ?>

<!-- Página de Perfil Público Estilo Linktree -->
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
                    <li class="list-group-item" style="margin-bottom: 10px;">
                        <a href="<?= htmlspecialchars($link['url']); ?>" target="_blank" class="btn btn-primary btn-block"><?= htmlspecialchars($link['title']); ?></a>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <p>Este usuário ainda não adicionou links.</p>
        <?php endif; ?>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
