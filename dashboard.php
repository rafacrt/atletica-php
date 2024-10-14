<?php
// Ativar a exibição de todos os erros
error_reporting(E_ALL);
ini_set('display_errors', 1);
?>
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

    <!-- Estilos personalizados -->
    <style>
        body {
            background-color: #f8f9fa;
        }
        .dashboard-container {
            margin-top: 50px;
        }
        .side-buttons {
            position: fixed;
            right: 30px;
            top: 150px;
        }
        .side-buttons button {
            width: 200px;
            margin-bottom: 15px;
        }
        .links-container {
            margin-top: 20px;
        }
        .card {
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="container dashboard-container">
        <div class="row">
            <div class="col-md-8">
                <h2>Seus Links</h2>
                <div class="links-container">
                    <?php
                    include 'includes/db.php';

                    // Verificar se a sessão já foi iniciada
                    if (session_status() === PHP_SESSION_NONE) {
                        session_start();
                    }

                    if (!isset($_SESSION['user_id'])) {
                        header("Location: login.php");
                        exit();
                    }

                    $user_id = $_SESSION['user_id'];

                    // Exibir links do usuário
                    $stmt = $conn->prepare("SELECT link_title, link_url FROM user_links WHERE user_id = ?");
                    $stmt->bind_param("i", $user_id);
                    $stmt->execute();
                    $stmt->bind_result($link_title, $link_url);

                    while ($stmt->fetch()): ?>
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title"><?php echo htmlspecialchars($link_title); ?></h5>
                                <a href="<?php echo htmlspecialchars($link_url); ?>" target="_blank" class="btn btn-primary">Visitar</a>
                            </div>
                        </div>
                    <?php endwhile;

                    // Fechar o statement corretamente apenas uma vez
                    if (isset($stmt) && $stmt instanceof mysqli_stmt) {
                        $stmt->close();
                    }

                    $conn->close();
                    ?>
                </div>
            </div>

            <div class="col-md-4 side-buttons">
                <!-- Botão para abrir o modal de adicionar link -->
                <button type="button" class="btn btn-success" data-toggle="modal" data-target="#addLinkModal">Adicionar Novo Link</button>

                <!-- Botão para abrir o modal de alterar perfil -->
                <button type="button" class="btn btn-info" data-toggle="modal" data-target="#editProfileModal">Alterar Perfil</button>
            </div>
        </div>
    </div>

    <!-- Modal para adicionar novo link -->
    <div class="modal fade" id="addLinkModal" tabindex="-1" aria-labelledby="addLinkModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addLinkModalLabel">Adicionar Novo Link</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form method="POST" action="add_link.php">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="link_title">Título do Link</label>
                            <input type="text" name="link_title" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="link_url">URL do Link</label>
                            <input type="url" name="link_url" class="form-control" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                        <button type="submit" class="btn btn-primary">Salvar Link</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal para alterar nome de usuário e senha -->
    <div class="modal fade" id="editProfileModal" tabindex="-1" aria-labelledby="editProfileModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editProfileModalLabel">Alterar Perfil</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form method="POST" action="profile.php">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="username">Nome de Usuário</label>
                            <input type="text" name="username" class="form-control" value="<?php echo htmlspecialchars($_SESSION['username']); ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="password">Nova Senha</label>
                            <input type="password" name="password" class="form-control">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                        <button type="submit" class="btn btn-primary">Salvar Alterações</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Scripts do Bootstrap -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>


<?php
$stmt->close();
$conn->close();
?>
