<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "site";

try {
    // Cria a conexão PDO
    $pdo = new PDO("mysql:host=$servername;dbname=$dbname;charset=utf8", $username, $password);
    
    // Define o modo de erro do PDO para exceções
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    //echo "Conexão bem-sucedida!";
}
catch(PDOException $e) {
    echo "Erro ao estabelecer ligação ao MySQL: " . $e->getMessage();
}
?>
