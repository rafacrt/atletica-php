<?php
include 'includes/db.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Exibir links do usuário
$stmt = $conn->prepare("SELECT id, link_title, link_url FROM user_links WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($link_id, $link_title, $link_url);

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
    <title>Dashboard</title>
</head>
<body>
    <div class="container">
        <h2>Seus Links</h2>
        <?php while ($stmt->fetch()): ?>
            <p><?php echo htmlspecialchars($link_title); ?>: 
            <a href="<?php echo htmlspecialchars($link_url); ?>" target="_blank">
                <?php echo htmlspecialchars($link_url); ?>
            </a></p>
        <?php endwhile; ?>
        <h2>Adicionar Novo Link</h2>
        <form method="POST" action="add_link.php">
            <div class="form-group">
                <label for="link_title">Título do Link</label>
                <input type="text" name="link_title" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="link_url">URL do Link</label>
                <input type="url" name="link_url" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary">Adicionar</button>
        </form>

        <h2>Alterar Username e Senha</h2>
        <form method="POST" action="profile.php">
            <div class="form-group">
                <label for="username">Nome de Usuário</label>
                <input type="text" name="username" class="form-control" value="<?php echo htmlspecialchars($_SESSION['username']); ?>" required>
            </div>
            <div class="form-group">
                <label for="password">Nova Senha</label>
                <input type="password" name="password" class="form-control">
            </div>
            <button type="submit" class="btn btn-primary">Salvar Alterações</button>
        </form>
    </div>
</body>
</html>

<?php
$stmt->close();
$conn->close();
?>
