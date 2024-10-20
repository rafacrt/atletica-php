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

// Recupera os links do usuário
$stmt_links = $conn->prepare("SELECT * FROM links WHERE user_id = :user_id ORDER BY position ASC");
$stmt_links->execute(['user_id' => $user['id']]);
$links = $stmt_links->fetchAll(PDO::FETCH_ASSOC);
?>

<?php include '../includes/header.php'; ?>

<div class="container mt-5">
    <h2>Bem-vindo ao Painel, <?= $user['username']; ?></h2>

    <div class="row">
        <!-- Gerenciamento do perfil -->
        <div class="col-md-4">
            <h3>Perfil</h3>
            <p><strong>Nome Completo:</strong> <?= $user['full_name'] ?: 'Não informado'; ?></p>
            <p><strong>Biografia:</strong> <?= $user['bio'] ?: 'Nenhuma biografia adicionada.'; ?></p>
            <a href="profile.php" class="btn btn-info">Editar Perfil</a>
        </div>

        <!-- Gerenciamento de links -->
        <div class="col-md-8">
            <h3>Seus Links</h3>
            <a href="add_link.php" class="btn btn-success mb-3">Adicionar Novo Link</a>

            <?php if (count($links) > 0): ?>
                <ul class="list-group">
                    <?php foreach ($links as $link): ?>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span><?= $link['title']; ?> - <a href="<?= $link['url']; ?>" target="_blank"><?= $link['url']; ?></a></span>
                            <span>
                                <a href="edit_link.php?id=<?= $link['id']; ?>" class="btn btn-sm btn-warning">Editar</a>
                                <a href="delete_link.php?id=<?= $link['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Tem certeza que deseja excluir este link?');">Excluir</a>
                            </span>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php else: ?>
                <p>Você ainda não adicionou nenhum link.</p>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
