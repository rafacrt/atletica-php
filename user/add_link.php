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

$errors = [];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = trim($_POST['title']);
    $url = trim($_POST['url']);

    if (empty($title) || empty($url)) {
        $errors[] = "O título e o URL são obrigatórios.";
    } else {
        // Inserir novo link no banco de dados
        $stmt = $conn->prepare("INSERT INTO links (user_id, title, url) VALUES (:user_id, :title, :url)");
        $stmt->execute([
            'user_id' => $user['id'],
            'title' => $title,
            'url' => $url
        ]);

        header("Location: dashboard.php");
        exit();
    }
}
?>

<?php include '../includes/header.php'; ?>

<div class="container mt-5">
    <h2>Adicionar Novo Link</h2>

    <?php if (!empty($errors)): ?>
        <div class="alert alert-danger">
            <ul>
                <?php foreach ($errors as $error): ?>
                    <li><?= $error; ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <form action="add_link.php" method="POST">
        <div class="form-group">
            <label for="title">Título do Link</label>
            <input type="text" name="title" id="title" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="url">URL</label>
            <input type="url" name="url" id="url" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-success">Adicionar Link</button>
    </form>
</div>

<?php include '../includes/footer.php'; ?>
