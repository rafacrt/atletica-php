<?php
// Ativar a exibição de todos os erros
error_reporting(E_ALL);
ini_set('display_errors', 1);


if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


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
        // Exibir um alert em JavaScript quando o nome de usuário ou email já estão em uso
        echo "<script>alert('Nome de usuário ou email já em uso!'); window.history.back();</script>";
        exit();
    } else {
        // Inserir o novo usuário no banco de dados
        $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $username, $email, $password);

        if ($stmt->execute()) {
            // Enviar o email de confirmação
            if (sendConfirmationEmail($email, $username)) {
                // Redirecionar para a home com uma mensagem para confirmar o email
                $_SESSION['confirmation_message'] = "Um email de confirmação foi enviado para $email. Por favor, verifique sua caixa de entrada.";
                header("Location: index.php");
                exit();
            } else {
                echo "Erro ao enviar o email de confirmação!";
            }
        } else {
            echo "Erro ao cadastrar!";
        }


// Certifique-se de que este código é chamado apenas uma vez para fechar o statement
if (isset($stmt) && $stmt instanceof mysqli_stmt) {
    $stmt->close();  // Fechar apenas uma vez
}


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

    <style>
        body {
            background-color: #f2f2f2;
        }
        .register-container {
            margin-top: 50px;
            max-width: 500px;
            background-color: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        #password-strength {
            font-size: 0.9em;
        }
    </style>

    <!-- Script para validar a força da senha -->
    <script>
        function checkPasswordStrength() {
            var password = document.getElementById("password").value;
            var strengthText = document.getElementById("password-strength");
            var strength = 0;

            if (password.length >= 8) strength += 1;
            if (password.match(/[A-Z]/)) strength += 1;
            if (password.match(/[a-z]/)) strength += 1;
            if (password.match(/[0-9]/)) strength += 1;
            if (password.match(/[\W]/)) strength += 1;

            switch (strength) {
                case 0:
                case 1:
                    strengthText.innerHTML = "Força da senha: Muito fraca";
                    strengthText.style.color = "red";
                    break;
                case 2:
                    strengthText.innerHTML = "Força da senha: Fraca";
                    strengthText.style.color = "orange";
                    break;
                case 3:
                    strengthText.innerHTML = "Força da senha: Média";
                    strengthText.style.color = "yellow";
                    break;
                case 4:
                    strengthText.innerHTML = "Força da senha: Forte";
                    strengthText.style.color = "green";
                    break;
                case 5:
                    strengthText.innerHTML = "Força da senha: Muito forte";
                    strengthText.style.color = "green";
                    break;
            }
        }
    </script>
</head>
<body>
    <div class="container register-container">
        <h2 class="text-center">Registre-se</h2>
        <form method="POST" action="register.php">
            <div class="form-group">
                <label for="username">Nome de Usuário</label>
                <input type="text" name="username" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="password">Senha</label>
                <input type="password" id="password" name="password" class="form-control" onkeyup="checkPasswordStrength()" required>
                <small id="password-strength">Força da senha: </small>
            </div>
            <button type="submit" class="btn btn-primary btn-block">Registrar</button>
        </form>
    </div>


<!-- Script de validação de email -->
<script>
    function validateEmail() {
        var email = document.getElementById("email").value;
        var re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!re.test(email)) {
            alert("Por favor, insira um email válido.");
            return false;
        }
        return true;
    }
</script>

</body>
</html>


