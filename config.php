<?php
// Estas variáveis serão preenchidas com os dados do seu AWS RDS depois
$host = 'ENDERECO_DO_SEU_RDS.amazonaws.com'; 
$db = 'postgres';
$user = 'postgres';
$pass = 'sua_senha_aqui';
$port = '5432';

try {
    $pdo = new PDO("pgsql:host=$host;port=$port;dbname=$db", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erro na conexão com o banco de dados: " . $e->getMessage());
}
?>
