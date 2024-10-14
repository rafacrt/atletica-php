<?php
session_start();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
    <title>Bem-vindo</title>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <a class="navbar-brand" href="#">Link Manager</a>
        <div class="collapse navbar-collapse">
            <ul class="navbar-nav ml-auto">
                <?php if (isset($_SESSION['user_id'])): ?>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Olá, <?php echo htmlspecialchars($_SESSION['username']); ?></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="dashboard.php">Editar Perfil</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="logout.php">Logout</a>
                    </li>
                <?php else: ?>
                    <li class="nav-item">
                        <a class="nav-link" href="register.php">Cadastre-se</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="login.php">Login</a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </nav>

    <div class="container">
        <h2 class="text-center">Bem-vindo ao Link Manager</h2>

        <!-- Exibir a mensagem de confirmação -->
        <?php if (isset($_SESSION['confirmation_message'])): ?>
            <div class="alert alert-success text-center">
                <?php
                echo $_SESSION['confirmation_message'];
                unset($_SESSION['confirmation_message']);  // Remover a mensagem da sessão após exibi-la
                ?>
            </div>
        <?php endif; ?>

        <div class="text-center">
            <a href="register.php" class="btn btn-primary">Cadastre-se</a>
            <a href="login.php" class="btn btn-secondary">Login</a>
        </div>
    </div>
</body>
</html>
