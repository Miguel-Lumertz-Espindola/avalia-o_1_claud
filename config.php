<?php
// Estas variáveis serão preenchidas com os dados do seu AWS RDS depois
$host = 'database-2.c4dqgokcsz7g.us-east-1.rds.amazonaws.com'; 
$db = 'postgres';
$user = 'postgres';
$pass = '1304051227';
$port = '5432';

try {
    $pdo = new PDO("pgsql:host=$host;port=$port;dbname=$db", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erro na conexão com o banco de dados: " . $e->getMessage());
}
?>
