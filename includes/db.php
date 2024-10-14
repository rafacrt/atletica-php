
<?php
$host = 'localhost';  // Nome do host
$dbname = 'wwrajo_atletica'; // Nome do banco de dados
$username = 'wwrajo_rajo';   // Nome de usuário do banco de dados
$password = '@Rafa3l*3387#2020';       // Senha do banco de dados

// Criando a conexão
$conn = new mysqli($host, $username, $password, $dbname);

// Checando a conexão
if ($conn->connect_error) {
    die("Erro na conexão com o banco de dados: " . $conn->connect_error);
}
?>

