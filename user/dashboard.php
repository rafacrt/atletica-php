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

// Recupera os links do usuário
$stmt_links = $conn->prepare("SELECT * FROM links WHERE user_id = :user_id ORDER BY position ASC");
$stmt_links->execute(['user_id' => $user['id']]);
$links = $stmt_links->fetchAll(PDO::FETCH_ASSOC);

// Salvando os badges e redes sociais
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Salvando os badges
    for ($i = 1; $i <= 4; $i++) {
        $badge_title = $_POST["badge_title_$i"];
        $badge_icon = isset($_POST["badge_icon_$i"]) ? $_POST["badge_icon_$i"] : null; 

        if (!empty($badge_title) && !empty($badge_icon)) {
            if (isset($badges[$i - 1])) {
                $stmt = $conn->prepare("UPDATE badges SET title = :title, icon = :icon WHERE id = :id");
                $stmt->execute([
                    'title' => $badge_title,
                    'icon' => $badge_icon,
                    'id' => $badges[$i - 1]['id']
                ]);
            } else {
                $stmt = $conn->prepare("INSERT INTO badges (user_id, title, icon) VALUES (:user_id, :title, :icon)");
                $stmt->execute([
                    'user_id' => $user['id'],
                    'title' => $badge_title,
                    'icon' => $badge_icon
                ]);
            }
        }
    }

    // Salvando redes sociais
    $social_links = $_POST['social_links'] ?? [];
    foreach ($social_links as $network => $url) {
        $stmt = $conn->prepare("INSERT INTO social_links (user_id, network, url) VALUES (:user_id, :network, :url) ON DUPLICATE KEY UPDATE url = :url");
        $stmt->execute([
            'user_id' => $user['id'],
            'network' => $network,
            'url' => $url
        ]);
    }

    header("Location: dashboard.php");
    exit();
}

// Recupera as redes sociais do usuário
$stmt_social = $conn->prepare("SELECT * FROM social_links WHERE user_id = :user_id");
$stmt_social->execute(['user_id' => $user['id']]);
$social_links = $stmt_social->fetchAll(PDO::FETCH_ASSOC);
?>

<?php include '../includes/header.php'; ?>

<div class="container mt-5">
    <div class="row">
        <!-- Seção de Perfil à Direita -->
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

        <!-- Seção de Links à Esquerda -->
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

    <!-- Seção de Redes Sociais -->
    <div class="row mt-5">
        <div class="col-md-12">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">Redes Sociais</h5>
                    <form action="dashboard.php" method="POST">
                        <div class="form-group">
                            <label for="facebook">Facebook</label>
                            <input type="url" name="social_links[facebook]" value="<?= $social_links['facebook'] ?? ''; ?>" class="form-control" placeholder="URL do Facebook">
                        </div>
                        <div class="form-group">
                            <label for="instagram">Instagram</label>
                            <input type="url" name="social_links[instagram]" value="<?= $social_links['instagram'] ?? ''; ?>" class="form-control" placeholder="URL do Instagram">
                        </div>
                        <div class="form-group">
                            <label for="twitter">Twitter</label>
                            <input type="url" name="social_links[twitter]" value="<?= $social_links['twitter'] ?? ''; ?>" class="form-control" placeholder="URL do Twitter">
                        </div>
                        <button type="submit" class="btn btn-primary">Salvar Redes Sociais</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Seção de Badges -->
    <div class="row mt-5">
        <div class="col-md-12">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">Escolha seus Badges</h5>
                    <form action="dashboard.php" method="POST">
                        <div class="row">
                            <?php for ($i = 1; $i <= 4; $i++): ?>
                                <div class="col-md-6 mb-4">
                                    <div class="form-group">
                                        <label for="badge_title_<?= $i ?>">Título do Badge <?= $i ?></label>
                                        <input type="text" name="badge_title_<?= $i ?>" class="form-control" value="<?= isset($badges[$i - 1]) ? htmlspecialchars($badges[$i - 1]['title']) : ''; ?>" placeholder="Título do Badge <?= $i ?>">
                                    </div>
                                    <div class="form-group">
                                        <label for="badge_icon_<?= $i ?>">Ícone do Badge <?= $i ?></label>
                                        <button type="button" class="btn btn-secondary toggle-icon-picker" data-target="#icon-picker-<?= $i ?>">Selecionar Ícone</button>
                                        <div id="icon-picker-<?= $i ?>" class="icon-picker mt-3" style="display: none;">
                                            <?php
                                            $icons = [/* Lista de ícones conforme mostrado anteriormente */];
                                            foreach ($icons as $icon): ?>
                                                <label class="icon-label">
                                                    <input type="radio" name="badge_icon_<?= $i ?>" value="<?= $icon ?>" <?= isset($badges[$i - 1]) && $badges[$i - 1]['icon'] == $icon ? 'checked' : ''; ?>>
                                                    <i class="fas <?= $icon ?> fa-2x"></i>
                                                </label>
                                            <?php endforeach; ?>
                                        </div>
                                    </div>
                                </div>
                            <?php endfor; ?>
                        </div>
                        <button type="submit" class="btn btn-primary">Salvar Badges</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>

<!-- Custom CSS para melhorar o layout -->
<style>
    .img-thumbnail {
        border-radius: 50%;
        width: 150px;
        height: 150px;
        object-fit: cover;
    }

    .icon-picker {
        display: grid;
        grid-template-columns: repeat(8, 1fr); /* 8 ícones por linha */
        gap: 0px;
    }

    .icon-label {
        text-align: center;
        cursor: pointer;
        padding: 5px;
        border: 2px solid transparent;
        transition: border-color 0.3s;
    }

    .icon-label input {
        display: none;
    }

    .icon-label i {
        color: #333;
        font-size: 20px;
        transition: color 0.3s;
    }

    .icon-label input:checked + i {
        color: #007bff;
    }

    .icon-label:hover {
        border-color: #007bff;
    }
</style>

<!-- JavaScript para mostrar/ocultar a seleção de badges individualmente -->
<script>
    document.querySelectorAll('.toggle-icon-picker').forEach(function(button) {
        button.addEventListener('click', function() {
            const target = document.querySelector(button.getAttribute('data-target'));
            if (target.style.display === 'none') {
                target.style.display = 'grid'; // Mostrar como grid
            } else {
                target.style.display = 'none'; // Esconder
            }
        });
    });
</script>
