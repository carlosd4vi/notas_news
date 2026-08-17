<?php
session_start();

if (empty($_SESSION['logado']) || $_SESSION['logado'] !== true) {
    header("Location: ../login.php"); 
    exit; 
}

if (empty($_GET['preparo']) || empty($_GET['id'])) {
    header('Location: posts.php'); 
    exit;
}

require_once '../../db/conexao.php';

$id = trim($_GET['id']);
$acao = trim($_GET['preparo']);

if (empty($id)) {
    die("Erro: ID não pode estar vazio.");
}

$mensagem = "";
$sucesso = false;

switch ($acao) {
    case 'editar':
        $sucesso = true;
        $mensagem = "O formulário de edição para a notícia #{$id} será construído aqui em breve.";
        break;

    case 'excluir':
        try {
            $stmt = $pdo->prepare("DELETE FROM dados_noticia WHERE id = ?");
            if ($stmt->execute([$id])) {
                $sucesso = true;
                $mensagem = "A publicação #{$id} foi excluída permanentemente com sucesso!";
            } else {
                $mensagem = "Erro: Não foi possível excluir a publicação.";
            }
        } catch (PDOException $e) {
            $mensagem = "Erro no banco de dados: " . $e->getMessage();
        }
        break;

    default:
        $mensagem = "Ação inválida. O comando '{$acao}' não é reconhecido.";
        break;
}

$pdo = null;
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ação no Post</title>
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
        .titulo-aviso { color: #17a2b8; }

        p {
            color: #555;
            line-height: 1.5;
            font-size: 16px;
        }

        .btn {
            display: inline-block;
            padding: 12px 15px;
            background-color: #6c757d;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            font-size: 16px;
            font-weight: bold;
            transition: background-color 0.3s;
            cursor: pointer;
            margin-top: 10px;
        }

        .btn:hover { background-color: #5a6268; }
    </style>
</head>
<body>

    <div class="container">
        
        <?php if ($acao === 'excluir' && $sucesso): ?>
            <div class="icone">🗑️</div>
            <h2 class="titulo-sucesso">Post Excluído!</h2>
        
        <?php elseif ($acao === 'editar'): ?>
            <div class="icone">✏️</div>
            <h2 class="titulo-aviso">Modo de Edição</h2>
            
        <?php else: ?>
            <div class="icone">⚠️</div>
            <h2 class="titulo-erro">Atenção</h2>
        <?php endif; ?>
        
        <p><?= htmlspecialchars($mensagem) ?></p>
        
        <a href="posts.php" class="btn">Voltar para os Posts</a>
        
    </div>

</body>
</html>