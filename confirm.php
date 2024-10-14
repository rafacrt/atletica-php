<?php
include 'includes/db.php';  // Incluindo a conexão com o banco de dados
session_start();

if (isset($_GET['email'])) {
    $email = $_GET['email'];

    // Verificar se o email está registrado no banco de dados e não está confirmado
    $stmt = $conn->prepare("SELECT id, username FROM users WHERE email = ? AND is_confirmed = 0");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($user_id, $username);
        $stmt->fetch();

        // Confirmar o cadastro
        $update_stmt = $conn->prepare("UPDATE users SET is_confirmed = 1 WHERE email = ?");
        $update_stmt->bind_param("s", $email);

        if ($update_stmt->execute()) {
            // Login automático e redirecionar para o dashboard
            $_SESSION['user_id'] = $user_id;
            $_SESSION['username'] = $username;
            header("Location: dashboard.php");
            exit();
        } else {
            echo "Erro ao confirmar o cadastro.";
        }

        $update_stmt->close();
    } else {
        echo "Email já confirmado ou inválido.";
    }

    $stmt->close();
    $conn->close();
} else {
    echo "Parâmetro inválido.";
}
?>
