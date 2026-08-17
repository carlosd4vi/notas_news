<?php
session_start();

if (empty($_SESSION['logado'])) {
    header("Location: login.php");
    exit;
}

if (empty($_POST['token']) || empty($_SESSION['form_token']) || !hash_equals($_SESSION['form_token'], $_POST['token'])) {
    die('Token inválido ou expirado. Tente enviar novamente.');
}
unset($_SESSION['form_token']);

$titulo = trim($_POST['tl'] ?? '');
$descricao = trim($_POST['des'] ?? '');
$fonte1 = trim($_POST['ft'] ?? '');
$fonte2 = trim($_POST['ft2'] ?? '');

if (empty($titulo) || empty($descricao) || empty($fonte1)) {
    die("Por favor, preencha todos os campos obrigatórios (Título, Descrição e Fonte 1).");
}

require_once '../db/conexao.php';

$sql = "INSERT INTO dados_noticia (titulo, descricao, link1, link2) VALUES (?, ?, ?, ?)";

try {
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$titulo, $descricao, $fonte1, $fonte2]);
    
    $id_noticia = $pdo->lastInsertId();

} catch (PDOException $e) {
    die("Erro ao inserir os dados: " . $e->getMessage());
}

$pdo = null;

$url_noticia = "noticia.php?token=" . urlencode($id_noticia);
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Postagem Enviada</title>
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
            max-width: 400px;
            padding: 30px 20px;
            border-radius: 8px;
            background-color: #fff;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        .icone-sucesso {
            font-size: 50px;
            margin-bottom: -10px;
        }

        h2 {
            color: #28a745;
            margin: 0;
        }

        p {
            color: #555;
            line-height: 1.5;
        }

        .btn {
            display: inline-block;
            padding: 12px 15px;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            font-size: 16px;
            font-weight: bold;
            transition: background-color 0.3s;
            cursor: pointer;
        }

        .btn-primario {
            background-color: #007bff;
        }

        .btn-primario:hover {
            background-color: #0056b3;
        }

        .btn-secundario {
            background-color: #6c757d;
            margin-top: 5px;
        }

        .btn-secundario:hover {
            background-color: #5a6268;
        }
    </style>
</head>
<body>

    <div class="container">
        <div class="icone-sucesso">✅</div>
        <h2>Enviado com Sucesso!</h2>
        
        <p>A sua notícia foi publicada e já está disponível para leitura.</p>

        <a href="painel.php" class="btn btn-secundario">
            Voltar ao Painel
        </a>
    </div>

</body>
</html>