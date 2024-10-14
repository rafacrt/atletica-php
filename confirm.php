<?php
include 'includes/db.php';  // Incluindo a conexão com o banco de dados

if (isset($_GET['email'])) {
    $email = $_GET['email'];

    // Verificar se o email está registrado no banco de dados
    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ? AND is_confirmed = 0");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        // Confirmar o cadastro
        $update_stmt = $conn->prepare("UPDATE users SET is_confirmed = 1 WHERE email = ?");
        $update_stmt->bind_param("s", $email);

        if ($update_stmt->execute()) {
            // Redirecionar para a página inicial com uma mensagem de sucesso
            $_SESSION['confirmation_message'] = "Cadastro confirmado com sucesso! Agora você pode fazer login.";
            header("Location: index.php");
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
