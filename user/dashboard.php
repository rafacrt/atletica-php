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

<div class="container mt-5">
    <div class="row">
        <!-- Seção de Perfil -->
        <div class="col-md-4">
            <div class="card shadow-sm">
                <div class="card-body text-center">
                    <h5 class="card-title">Perfil</h5>
                    <!-- Foto de perfil circular -->
                    <?php if (!empty($user['profile_image'])): ?>
                        <img src="../assets/img/<?= htmlspecialchars($user['profile_image']); ?>" alt="Foto de Perfil" class="img-thumbnail rounded-circle" width="150">
                    <?php else: ?>
                        <img src="../assets/img/default_profile_icon.png" alt="Ícone de Perfil Padrão" class="img-thumbnail rounded-circle" width="150">
                    <?php endif; ?>
                    <p class="mt-3"><?= htmlspecialchars($user['full_name']); ?></p>
                    <a href="profile.php" class="btn btn-outline-primary mt-3">Editar Perfil</a>
                    <a href="/atletica/user/public_profile.php?username=<?= htmlspecialchars($user['username']); ?>" class="btn btn-success mt-3">Ver Perfil</a>
                </div>
            </div>
        </div>

        <!-- Seção de Links -->
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

<?php include '../includes/footer.php'; ?>

<!-- Custom CSS -->
<style>
    .card {
        border-radius: 10px;
    }

    .img-thumbnail {
        border-radius: 50%;
        width: 150px;
        height: 150px;
        object-fit: cover;
    }

    /* Botões estilizados */
    .btn-outline-primary {
        border-color: #007bff;
        color: #007bff;
        transition: all 0.3s ease;
    }

    .btn-outline-primary:hover {
        background-color: #007bff;
        color: #fff;
    }

    .btn-success {
        transition: all 0.3s ease;
    }

    .btn-success:hover {
        background-color: #28a745;
        color: #fff;
    }

    /* Layout melhorado para o dashboard */
    .card-body {
        padding: 20px;
    }

    .row {
        margin-bottom: 30px;
    }
</style>
