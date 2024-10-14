<?php
// Inclua sua conexão com o banco de dados
include 'db.php';
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

echo "<h2>Seus Links:</h2>";
while ($stmt->fetch()) {
    echo "<p>{$link_title}: <a href='{$link_url}' target='_blank'>{$link_url}</a></p>";
}
$stmt->close();

// Formulário para adicionar novo link
?>
<h2>Adicionar Novo Link:</h2>
<form method="POST" action="add_link.php">
    <input type="text" name="link_title" placeholder="Título do Link" required>
    <input type="url" name="link_url" placeholder="URL do Link" required>
    <button type="submit">Adicionar</button>
</form>
<?php
$conn->close();
?>
