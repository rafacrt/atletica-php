<?php
include 'includes/db.php';

if (isset($_GET['user'])) {
    $username = $_GET['user'];

    // Buscar o ID do usuário
    $stmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->bind_result($user_id);
    $stmt->fetch();
    $stmt->close();

    if ($user_id) {
        // Buscar os links do usuário
        $stmt = $conn->prepare("SELECT link_title, link_url FROM user_links WHERE user_id = ?");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $stmt->bind_result($link_title, $link_url);

        ?>

        <!DOCTYPE html>
        <html lang="pt-br">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
            <link rel="stylesheet" href="css/style.css">
            <title>Perfil de <?php echo htmlspecialchars($username); ?></title>
        </head>
        <body>
            <div class="container">
                <h2>Links de <?php echo htmlspecialchars($username); ?></h2>
                <?php while ($stmt->fetch()): ?>
                    <p><?php echo htmlspecialchars($link_title); ?>: 
                    <a href="<?php echo htmlspecialchars($link_url); ?>" target="_blank">
                        <?php echo htmlspecialchars($link_url); ?>
                    </a></p>
                <?php endwhile; ?>
            </div>
        </body>
        </html>

        <?php
        $stmt->close();
    } else {
        echo "Usuário não encontrado!";
    }

    $conn->close();
}
?>
