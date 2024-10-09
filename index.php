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
<div class="container mt-5">
    <h2 class="text-center mb-4">Atleticas Recentes</h2>
    <div class="row">
    <?php
include 'includes/db.php';
?>
        <?php
        $stmt = $pdo->query("SELECT username, profile_photo FROM users ORDER BY created_at DESC LIMIT 10");
        $users = $stmt->fetchAll();

        foreach ($users as $user) {
            echo '<div class="col-md-4 col-sm-6 mb-4">';
            echo '  <div class="card">';
            echo '      <img src="' . ($user['profile_photo'] ?: 'assets/default-profile.png') . '" class="card-img-top" alt="' . $user['username'] . '" style="height: 200px; object-fit: cover;">';
            echo '      <div class="card-body text-center">';
            echo '          <h5 class="card-title">' . $user['username'] . '</h5>';
            echo '          <a href="/users/' . $user['username'] . '" class="btn btn-primary">Ver Perfil</a>';
            echo '      </div>';
            echo '  </div>';
            echo '</div>';
        }
        ?>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>