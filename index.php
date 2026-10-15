<?php
require 'config.php';

// Cria a tabela cidadaos automaticamente com 11 colunas (ID + 10 campos de dados)
$pdo->query("CREATE TABLE IF NOT EXISTS cidadaos (
    id SERIAL PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    cpf VARCHAR(14) NOT NULL,
    data_nascimento DATE NOT NULL,
    nome_mae VARCHAR(100),
    email VARCHAR(100),
    telefone VARCHAR(20),
    endereco VARCHAR(150),
    bairro VARCHAR(50),
    cep VARCHAR(10),
    profissao VARCHAR(50)
)");

// Adicionar um novo cidadão (Create)
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['nome'])) {
    $stmt = $pdo->prepare("INSERT INTO cidadaos (nome, cpf, data_nascimento, nome_mae, email, telefone, endereco, bairro, cep, profissao) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([
        $_POST['nome'], $_POST['cpf'], $_POST['data_nascimento'], $_POST['nome_mae'],
        $_POST['email'], $_POST['telefone'], $_POST['endereco'], $_POST['bairro'],
        $_POST['cep'], $_POST['profissao']
    ]);
    header("Location: index.php");
    exit;
}

// Deletar um cidadão (Delete)
if (isset($_GET['delete'])) {
    $stmt = $pdo->prepare("DELETE FROM cidadaos WHERE id = ?");
    $stmt->execute([$_GET['delete']]);
    header("Location: index.php");
    exit;
}

// Buscar todos os cidadãos (Read)
$cidadaos = $pdo->query("SELECT * FROM cidadaos ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Portal do Munícipe - Dom Pedro de Alcântara</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; margin: 0; background-color: #eef2f5; color: #333; }
        .header { background-color: #004a99; color: white; padding: 25px 20px; text-align: center; border-bottom: 4px solid #00a65a; }
        .header h2 { margin: 0 0 5px 0; font-size: 24px; }
        .header p { margin: 0; font-size: 14px; opacity: 0.9; }
        .container { max-width: 1100px; margin: 30px auto; padding: 0 20px; }
        
        .form-container { background: white; padding: 30px; border-radius: 8px; box-shadow: 0 4px 10px rgba(0,0,0,0.08); margin-bottom: 30px; }
        .form-container h3 { margin-top: 0; color: #004a99; border-bottom: 2px solid #eee; padding-bottom: 10px; margin-bottom: 20px; }
        
        /* Layout em 2 colunas para os 10 campos */
        .form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 15px 25px; }
        .form-group { display: flex; flex-direction: column; }
        .form-group.full-width { grid-column: span 2; }
        
        label { margin-bottom: 6px; font-weight: 600; color: #555; font-size: 13px; text-transform: uppercase; letter-spacing: 0.5px; }
        input { padding: 10px 12px; border: 1px solid #ccd0d5; border-radius: 5px; font-size: 14px; transition: border-color 0.3s; }
        input:focus { border-color: #004a99; outline: none; }
        
        button { background-color: #00a65a; color: white; padding: 14px 20px; border: none; border-radius: 5px; cursor: pointer; font-size: 16px; font-weight: bold; margin-top: 25px; width: 100%; transition: background 0.3s; }
        button:hover { background-color: #008d4c; }
        
        /* Tabela de exibição */
        .table-container { background: white; border-radius: 8px; box-shadow: 0 4px 10px rgba(0,0,0,0.08); overflow: hidden; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border-bottom: 1px solid #eee; padding: 15px; text-align: left; }
        th { background-color: #f8f9fa; color: #333; font-weight: 600; font-size: 14px; }
        tr:hover { background-color: #f4f6f9; }
        
        .delete-btn { color: #dc3545; text-decoration: none; font-weight: bold; font-size: 13px; border: 1px solid #dc3545; padding: 5px 10px; border-radius: 4px; transition: all 0.2s; }
        .delete-btn:hover { background-color: #dc3545; color: white; }
    </style>
</head>
<body>
    <div class="header">
        <h2>Prefeitura de Dom Pedro de Alcântara</h2>
        <p>Sistema de Cadastro de Munícipes (Integração Cloud 1Doc)</p>
    </div>
    
    <div class="container">
        <div class="form-container">
            <h3>Registrar Novo Cidadão</h3>
            <form method="POST">
                <div class="form-grid">
                    <!-- Os 10 campos de dados -->
                    <div class="form-group">
                        <label>Nome Completo:</label>
                        <input type="text" name="nome" required>
                    </div>
                    <div class="form-group">
                        <label>CPF:</label>
                        <input type="text" name="cpf" placeholder="000.000.000-00" required>
                    </div>
                    <div class="form-group">
                        <label>Data de Nascimento:</label>
                        <input type="date" name="data_nascimento" required>
                    </div>
                    <div class="form-group">
                        <label>Nome da Mãe:</label>
                        <input type="text" name="nome_mae" required>
                    </div>
                    <div class="form-group full-width">
                        <label>Endereço Completo (Rua, Número, Complemento):</label>
                        <input type="text" name="endereco" required>
                    </div>
                    <div class="form-group">
                        <label>Bairro:</label>
                        <input type="text" name="bairro" required>
                    </div>
                    <div class="form-group">
                        <label>CEP:</label>
                        <input type="text" name="cep" placeholder="00000-000" required>
                    </div>
                    <div class="form-group">
                        <label>E-mail (Contato):</label>
                        <input type="email" name="email" required>
                    </div>
                    <div class="form-group">
                        <label>Telefone / WhatsApp:</label>
                        <input type="text" name="telefone" placeholder="(00) 00000-0000" required>
                    </div>
                    <div class="form-group">
                        <label>Profissão:</label>
                        <input type="text" name="profissao" required>
                    </div>
                </div>
                <button type="submit">Gravar Registro Municipal no AWS RDS</button>
            </form>
        </div>

        <div class="table-container">
            <table>
                <tr>
                    <th>Protocolo</th>
                    <th>Cidadão</th>
                    <th>Documento</th>
                    <th>Contato</th>
                    <th>Bairro</th>
                    <th>Ações</th>
                </tr>
                <?php foreach ($cidadaos as $c): ?>
                <tr>
                    <td>#<?= str_pad($c['id'], 4, '0', STR_PAD_LEFT) ?></td>
                    <td>
                        <strong><?= htmlspecialchars($c['nome']) ?></strong><br>
                        <small style="color: #777;">Mãe: <?= htmlspecialchars($c['nome_mae']) ?></small>
                    </td>
                    <td>
                        <?= htmlspecialchars($c['cpf']) ?><br>
                        <small style="color: #777;">Nasc: <?= date('d/m/Y', strtotime($c['data_nascimento'])) ?></small>
                    </td>
                    <td>
                        <?= htmlspecialchars($c['telefone']) ?><br>
                        <small style="color: #777;"><?= htmlspecialchars($c['email']) ?></small>
                    </td>
                    <td><?= htmlspecialchars($c['bairro']) ?></td>
                    <td><a class="delete-btn" href="?delete=<?= $c['id'] ?>">Apagar</a></td>
                </tr>
                <?php endforeach; ?>
            </table>
        </div>
    </div>
</body>
</html>
