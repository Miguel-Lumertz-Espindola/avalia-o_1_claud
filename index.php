<?php
require 'config.php';

// Cria a tabela automaticamente se ela não existir
$pdo->query("CREATE TABLE IF NOT EXISTS usuarios (
    id SERIAL PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL
)");

// Adicionar um novo usuário (Create)
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['nome'])) {
    $stmt = $pdo->prepare("INSERT INTO usuarios (nome, email) VALUES (?, ?)");
    $stmt->execute([$_POST['nome'], $_POST['email']]);
    header("Location: index.php");
    exit;
}

// Deletar um usuário (Delete)
if (isset($_GET['delete'])) {
    $stmt = $pdo->prepare("DELETE FROM usuarios WHERE id = ?");
    $stmt->execute([$_GET['delete']]);
    header("Location: index.php");
    exit;
}

// Buscar todos os usuários (Read)
$usuarios = $pdo->query("SELECT * FROM usuarios ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Trabalho Cloud - CRUD PHP</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 40px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ccc; padding: 10px; text-align: left; }
        th { background-color: #f4f4f4; }
        .form-container { margin-bottom: 30px; padding: 15px; border: 1px solid #ddd; background: #f9f9f9; }
    </style>
</head>
<body>
    <h2>Cadastro de Usuários na Nuvem</h2>
    
    <div class="form-container">
        <form method="POST">
            <label>Nome:</label>
            <input type="text" name="nome" required>
            <label>E-mail:</label>
            <input type="email" name="email" required>
            <button type="submit">Salvar no Banco</button>
        </form>
    </div>

    <table>
        <tr>
            <th>ID</th>
            <th>Nome</th>
            <th>E-mail</th>
            <th>Ações</th>
        </tr>
        <?php foreach ($usuarios as $u): ?>
        <tr>
            <td><?= $u['id'] ?></td>
            <td><?= htmlspecialchars($u['nome']) ?></td>
            <td><?= htmlspecialchars($u['email']) ?></td>
            <td><a href="?delete=<?= $u['id'] ?>" style="color: red;">Excluir</a></td>
        </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>
