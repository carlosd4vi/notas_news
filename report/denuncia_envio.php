<?php
session_start();

if (empty($_SESSION['logado']) || $_SESSION['logado'] !== true) {
    header("Location: ../login.php");
    exit;
}

if (empty($_POST['token']) || empty($_SESSION['form_token']) || !hash_equals($_SESSION['form_token'], $_POST['token'])) {
    die("Erro: Token de segurança inválido ou expirado. Volte e tente novamente.");
}

// 3. Recebe os dados
$id = trim($_POST["id"] ?? '');
$tabela = trim($_POST["tabela"] ?? '');

if (empty($id) || empty($tabela)) {
    die("Erro: Faltam informações (ID ou Tabela).");
}

require_once '../db/conexao.php';

$mensagem = "";
$sucesso = false;

try {
    if ($tabela === 'report') {
        $sql = "DELETE FROM report WHERE id = ?";
        $stmt = $pdo->prepare($sql);
        
        if ($stmt->execute([$id])) {
            $sucesso = true;
            $mensagem = "A denúncia foi REMOVIDA da lista e a publicação foi MANTIDA no site.";
        }
    } elseif ($tabela === 'dados' || $tabela === 'dados_noticia') {
        $sql_post = "DELETE FROM dados_noticia WHERE id = ?";
        $stmt_post = $pdo->prepare($sql_post);
        $stmt_post->execute([$id]);

        $sql_report = "DELETE FROM report WHERE post LIKE ?";
        $stmt_report = $pdo->prepare($sql_report);
        $termo_busca = "%token=" . $id; 
        $stmt_report->execute([$termo_busca]);

        $sucesso = true;
        $mensagem = "A publicação excluida com sucesso!";
        
    } else {
        die("Erro Crítico: Ação não autorizada. Tabela desconhecida.");
    }

} catch (PDOException $e) {
    $mensagem = "Erro no banco de dados: " . $e->getMessage();
}

$pdo = null; // Fecha a conexão
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ação Realizada</title>
    
    <style>
        body {
            background-color: #f4f4f4;
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
        }

        .container {
            display: flex;
            flex-direction: column;
            gap: 15px;
            width: 100%;
            max-width: 450px;
            padding: 30px 20px;
            border-radius: 8px;
            background-color: #fff;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        .icone {
            font-size: 50px;
            margin-bottom: -10px;
        }

        h2 {
            margin: 0;
        }
        
        .titulo-sucesso { color: #28a745; }
        .titulo-erro { color: #dc3545; }

        p {
            color: #555;
            line-height: 1.5;
            font-size: 16px;
        }

        .btn {
            display: inline-block;
            padding: 12px 15px;
            background-color: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            font-size: 16px;
            font-weight: bold;
            transition: background-color 0.3s;
            cursor: pointer;
            margin-top: 10px;
        }

        .btn:hover { background-color: #0056b3; }
    </style>
</head>
<body>

    <div class="container">
        
        <?php if ($sucesso): ?>
            <div class="icone">✅</div>
            <h2 class="titulo-sucesso">Ação Concluída!</h2>
        <?php else: ?>
            <div class="icone">❌</div>
            <h2 class="titulo-erro">Falha na Ação</h2>
        <?php endif; ?>
        
        <p><?= htmlspecialchars($mensagem) ?></p>
        
        <a href="../session/denuncia.php" class="btn">Voltar para Denúncias</a>
        
    </div>

</body>
</html>