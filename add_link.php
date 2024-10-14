<?php
include 'db.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_SESSION['user_id'];
    $link_title = $_POST['link_title'];
    $link_url = $_POST['link_url'];

    $stmt = $conn->prepare("INSERT INTO user_links (user_id, link_title, link_url) VALUES (?, ?, ?)");
    $stmt->bind_param("iss", $user_id, $link_title, $link_url);

    if ($stmt->execute()) {
        header("Location: dashboard.php");
        exit();
    } else {
        echo "Erro ao adicionar link!";
    }

    $stmt->close();
    $conn->close();
}
?>
