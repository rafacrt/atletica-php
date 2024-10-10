<!-- index.php -->
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Atletica</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-3">
    <!-- Header -->
    <header class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <img src="assets/logo.png" alt="Logo" style="height: 50px;">
        </div>
        <div>
            <a href="register.php" class="btn btn-outline-primary me-2">Cadastro</a>
            <a href="login.php" class="btn btn-primary">Login</a>
        </div>
    </header>

    <!-- Galeria de Atléticas Recentes -->
    <h2 class="text-center mb-4">Atléticas Recentes</h2>
    <div class="row">
        <?php
        include 'includes/db.php';

        $stmt = $pdo->query("SELECT username, profile_photo FROM users ORDER BY created_at DESC LIMIT 10");
        $users = $stmt->fetchAll();

        foreach ($users as $user) {
            ?>
            <div class="col-md-4 col-sm-6 mb-4">
                <div class="card">
                    <img src="<?php echo $user['profile_photo'] ?: 'assets/default-profile.png'; ?>" class="card-img-top" alt="<?php echo htmlspecialchars($user['username']); ?>" style="height: 200px; object-fit: cover;">
                    <div class="card-body text-center">
                        <h5 class="card-title"><?php echo htmlspecialchars($user['username']); ?></h5>
                        <a href="/users/<?php echo urlencode($user['username']); ?>" class="btn btn-primary">Ver Perfil</a>
                    </div>
                </div>
            </div>
            <?php
        }
        ?>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
