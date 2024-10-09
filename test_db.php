<?php
include 'includes/db.php';

// Testando a conexão
if ($pdo) {
    echo "Conexão com o banco de dados estabelecida com sucesso!";
} else {
    echo "Falha na conexão com o banco de dados.";
}
?>
