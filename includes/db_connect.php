<?php
$servername = "localhost";
$username = "wwrajo_rajo";
$password = "@Rafa3l*3387#2020";
$dbname = "wwrajo_atletica";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    // Definir o modo de erro do PDO como exceção
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
?>
