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

// Recupera os links do usuário
$stmt_links = $conn->prepare("SELECT * FROM links WHERE user_id = :user_id ORDER BY position ASC");
$stmt_links->execute(['user_id' => $user['id']]);
$links = $stmt_links->fetchAll(PDO::FETCH_ASSOC);
?>

<?php include '../includes/header.php'; ?>

<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <h2>Bem-vindo, <?= htmlspecialchars($user['username']); ?></h2>
            <div class="row">
                <div class="col-md-4">
                    <div class="card shadow-sm">
                        <div class="card-body text-center">
                            <h5 class="card-title">Perfil</h5>
                            <?php if (!empty($user['profile_image'])): ?>
                                <img src="../assets/img/<?= htmlspecialchars($user['profile_image']); ?>" alt="Foto de Perfil" class="img-thumbnail rounded-circle" width="150">
                            <?php else: ?>
                                <img src="../assets/img/default_profile_icon.png" alt="Ícone de Perfil Padrão" class="img-thumbnail rounded-circle" width="150">
                            <?php endif; ?>
                            <a href="profile.php" class="btn btn-primary mt-3">Editar Perfil</a>
                            <!-- Alterado para redirecionar para o formato padrão com ?username -->
                            <a href="/atletica/user/public_profile.php?username=<?= htmlspecialchars($user['username']); ?>" class="btn btn-success mt-3">Ver Perfil</a>
                        </div>
                    </div>
                </div>

                <div class="col-md-8">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <h5 class="card-title">Seus Links</h5>
                            <a href="add_link.php" class="btn btn-primary mb-3">Adicionar Novo Link</a>

                            <?php if (count($links) > 0): ?>
                                <ul class="list-group">
                                    <?php foreach ($links as $link): ?>
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            <span><?= htmlspecialchars($link['title']); ?> - <a href="<?= htmlspecialchars($link['url']); ?>" target="_blank"><?= htmlspecialchars($link['url']); ?></a></span>
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
            </div>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
