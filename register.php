<?php
// Ativar a exibição de todos os erros
error_reporting(E_ALL);
ini_set('display_errors', 1);
?>

<?php
include 'includes/db.php';  // Incluindo a conexão com o banco de dados
include 'includes/mail.php';  // Incluindo a função de envio de email
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

    // Verificar se o nome de usuário ou email já estão registrados
    $check_stmt = $conn->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
    $check_stmt->bind_param("ss", $username, $email);
    $check_stmt->execute();
    $check_stmt->store_result();

    if ($check_stmt->num_rows > 0) {
        echo "Nome de usuário ou email já em uso!";
    } else {
        // Inserir o novo usuário no banco de dados
        $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $username, $email, $password);

        if ($stmt->execute()) {
            // Enviar o email de confirmação
            if (sendConfirmationEmail($email, $username)) {
                // Login automático após registro
                $_SESSION['user_id'] = $stmt->insert_id;
                $_SESSION['username'] = $username;
                header("Location: dashboard.php");
                exit();
            } else {
                echo "Erro ao enviar o email de confirmação!";
            }
        } else {
            echo "Erro ao cadastrar!";
        }

        $stmt->close();
    }

    $check_stmt->close();
    $conn->close();
}
?>


<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
    <title>Registro</title>
    <script>
        // Validação de força da senha
        function checkPasswordStrength() {
            var password = document.getElementById("password").value;
            var strength = document.getElementById("strength");

            if (password.length < 8) {
                strength.innerHTML = "A senha deve ter pelo menos 8 caracteres.";
            } else {
                var strongPassword = /^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{8,}$/;
                if (strongPassword.test(password)) {
                    strength.innerHTML = "Senha forte.";
                } else {
                    strength.innerHTML = "A senha deve incluir letras e números.";
                }
            }
        }
    </script>
</head>
<body>
    <div class="container">
        <h2>Cadastre-se</h2>
        <form method="POST" action="register.php">
            <div class="form-group">
                <label for="username">Nome de Usuário</label>
                <input type="text" name="username" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" name="email" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="password">Senha</label>
                <input type="password" id="password" name="password" class="form-control" onkeyup="checkPasswordStrength()" required>
                <small id="strength" class="text-muted"></small>
            </div>
            <div class="form-group">
                <label for="confirm_password">Confirme a Senha</label>
                <input type="password" name="confirm_password" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary">Registrar</button>
        </form>
    </div>
</body>
</html>
