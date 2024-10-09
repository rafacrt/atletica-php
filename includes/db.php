// includes/db.php
<?php
$host = 'localhost';  // Nome do host
$dbname = 'wwrajo_atletica'; // Nome do banco de dados
$username = 'wwrajo_rajo';   // Nome de usuário do banco de dados
$password = '@Rafa3l*3387#2020';       // Senha do banco de dados

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erro ao conectar ao banco de dados: " . $e->getMessage());
}
?>
