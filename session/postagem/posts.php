<?php
session_start();

if (empty($_SESSION['logado']) || $_SESSION['logado'] !== true) {
    header("Location: ../login.php"); 
    exit;
}

require_once '../../db/conexao.php';

$post = null;
$sql = "SELECT id, titulo, descricao, link1, link2, data FROM dados_noticia ORDER BY data DESC LIMIT 1";

try {
    $stmt = $pdo->query($sql);
    if ($stmt) {
        $post = $stmt->fetch(PDO::FETCH_ASSOC);
    }
} catch (PDOException $e) {
    error_log("Erro na consulta do post: " . $e->getMessage());
}

$pdo = null;
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notas News</title>
    <link rel="icon" href="../../img/logo.jpeg" type="image/jpeg">
    <link rel="stylesheet" href="../../css/estilo.css">
    
    <style>
        .container-form {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 70vh;
            background-color: #f4f4f4;
            font-family: Arial, sans-serif;
            padding: 20px;
        }

        .formulario-box {
            background-color: #ffffff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.15);
            width: 100%;
            max-width: 500px;
            box-sizing: border-box;
        }

        .meu-formulario {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .form-group {
            display: flex;
            flex-direction: column;
        }

        .form-group label {
            margin-top: 10px;
            margin-bottom: 2px;
            font-weight: bold;
            color: #333;
        }

        .form-group span {
            background-color: #f8f9fa;
            padding: 10px;
            border-radius: 5px;
            border: 1px solid #ddd;
            word-wrap: break-word;
        }

        .btn {
            display: inline-block;
            text-align: center;
            color: white;
            padding: 12px 15px;
            border: none;
            border-radius: 5px;
            font-size: 1.1em;
            text-decoration: none;
            cursor: pointer;
            transition: transform 0.2s ease, opacity 0.3s;
            margin-top: 10px;
            font-weight: bold;
        }

        .btn:hover { opacity: 0.9; transform: translateY(-2px); }
        .btn:active { transform: translateY(0); }

        /* Cores dos botões */
        .btn-verde { background-color: #28a745; }
        .btn-azul { background-color: #007bff; }
        .btn-vermelho { background-color: #dc3545; }

        .botoes-acao {
            display: flex;
            gap: 10px;
            margin-top: 15px;
        }
        .botoes-acao .btn {
            flex: 1;
            margin-top: 0;
        }
    </style>
</head>
<body>
    <header class="cabecalho">
        <div class="logo">
            <img src="../../img/logo.jpeg" alt="logo da página" class="img-logo">
        </div>
        <a href="../sair.php" style="margin-right: 20px; font-weight: bold; color: #333; text-decoration: none;">Sair</a>
    </header>
    
    <nav class="menu-navegacao">
        <ul>
            <li><a href="../../index.php">Início</a></li>
            <li><a href="../painel.php">Painel</a></li>
            <li><a href="../cadastro.php">Cadastro</a></li>
            <li><a href="../denuncia.php">Denuncia</a></li>
        </ul>
    </nav>

    <div style="text-align: center; margin-top: 30px;">
        <h1 style="color: #333;">Painel de Posts:</h1>
        
        <?php if ($post): ?>
            <div class="container-form">
                <div class="formulario-box">
                    <div class="meu-formulario">
                        <div class="form-group">
                            <label>ID:</label>
                            <span><?= htmlspecialchars($post['id']) ?></span>
                            
                            <label>Titulo:</label>
                            <span><?= htmlspecialchars($post['titulo']) ?></span>
                            
                            <label>Descrição:</label>
                            <span><?= htmlspecialchars($post['descricao']) ?></span>
                            
                            <label>Link Primário:</label>
                            <span><?= htmlspecialchars($post['link1']) ?></span>
                            
                            <label>Link Secundário:</label>
                            <span><?= htmlspecialchars($post['link2'] ?? 'Nenhum') ?></span>
                            
                            <label>Data:</label>
                            <span><?= htmlspecialchars($post['data']) ?></span>
                        </div>
                        <div class="botoes-acao">
                            <a href="preparo.php?preparo=editar&id=<?php echo $post['id']; ?>" class="btn btn-azul">Editar</a>
                            <a href="preparo.php?preparo=excluir&id=<?= urlencode($post['id']) ?>" class="btn btn-vermelho" onclick="return confirm('Tem certeza que deseja excluir esta notícia?');">Excluir</a>
                        </div>
                    </div>

                </div>
            </div>
        <?php else: ?>
            <div class="container-form">
                <p>Nenhum post encontrado no banco de dados.</p>
            </div>
        <?php endif; ?>
    </div>

    <footer class="rodape">
        <p>&copy; <?= date('Y') ?> Portal de Notícias. Todos os direitos reservados.</p>
    </footer>
</body>
</html>