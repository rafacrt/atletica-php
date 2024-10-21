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

// Recupera os badges do usuário
$stmt_badges = $conn->prepare("SELECT * FROM badges WHERE user_id = :user_id");
$stmt_badges->execute(['user_id' => $user['id']]);
$badges = $stmt_badges->fetchAll(PDO::FETCH_ASSOC);

// Salvando os badges selecionados
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    for ($i = 1; $i <= 4; $i++) {
        $badge_title = $_POST["badge_title_$i"];
        $badge_icon = $_POST["badge_icon_$i"];

        // Verifica se já existe um badge no banco de dados
        if (isset($badges[$i - 1])) {
            // Atualiza o badge existente
            $stmt = $conn->prepare("UPDATE badges SET title = :title, icon = :icon WHERE id = :id");
            $stmt->execute([
                'title' => $badge_title,
                'icon' => $badge_icon,
                'id' => $badges[$i - 1]['id']
            ]);
        } else {
            // Insere um novo badge
            $stmt = $conn->prepare("INSERT INTO badges (user_id, title, icon) VALUES (:user_id, :title, :icon)");
            $stmt->execute([
                'user_id' => $user['id'],
                'title' => $badge_title,
                'icon' => $badge_icon
            ]);
        }
    }
    
    header("Location: dashboard.php");
    exit();
}
?>

<?php include '../includes/header.php'; ?>

<div class="container mt-5">
    <div class="row">
        <!-- Seção de Perfil -->
        <div class="col-md-4">
            <div class="card shadow-sm">
                <div class="card-body text-center">
                    <h5 class="card-title">Perfil</h5>
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

        <!-- Seção de Badges -->
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">Escolha seus Badges</h5>
                    <form action="dashboard.php" method="POST">
                        <?php for ($i = 1; $i <= 4; $i++): ?>
                            <div class="form-group">
                                <label for="badge_title_<?= $i ?>">Título do Badge <?= $i ?></label>
                                <input type="text" name="badge_title_<?= $i ?>" class="form-control" value="<?= isset($badges[$i - 1]) ? htmlspecialchars($badges[$i - 1]['title']) : ''; ?>" placeholder="Título do Badge <?= $i ?>">
                                <label for="badge_icon_<?= $i ?>">Ícone do Badge <?= $i ?></label>
                                <select name="badge_icon_<?= $i ?>" class="form-control">
                                    <option value="fa-star" <?= isset($badges[$i - 1]) && $badges[$i - 1]['icon'] == 'fa-star' ? 'selected' : ''; ?>>Estrela</option>
                                    <option value="fa-heart" <?= isset($badges[$i - 1]) && $badges[$i - 1]['icon'] == 'fa-heart' ? 'selected' : ''; ?>>Coração</option>
                                    <!-- Adicione outros ícones conforme necessário -->
                                </select>
                            </div>
                        <?php endfor; ?>
                        <button type="submit" class="btn btn-primary">Salvar Badges</button>
                    </form>
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
