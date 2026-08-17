<?php
session_start();

if (empty($_SESSION['logado']) || $_SESSION['logado'] !== true) {
    header("Location: login.php");
    exit;
}

if (empty($_POST['token']) || empty($_SESSION['form_token']) || !hash_equals($_SESSION['form_token'], $_POST['token'])) {
    die('Token inválido ou expirado. Volte e tente novamente.');
}
unset($_SESSION['form_token']); 

$username = trim($_POST['usuario'] ?? '');
$password = trim($_POST['senha'] ?? '');


if (empty($username) || empty($password)) {
    die("Erro: Por favor, preencha o nome de usuário e a senha.");
}

require_once '../db/conexao.php';

$senha_hash = password_hash($_POST['senha'], PASSWORD_DEFAULT);

$sql = "INSERT INTO admin (usuario, senha) VALUES (?, ?)";

try {
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$username, $senha_hash]);

} catch (PDOException $e) {
    if ($e->getCode() == 23000) {
        die("<div style='text-align:center; margin-top:50px; font-family:Arial;'>
                <h2 style='color:red;'>Erro: Esse nome de usuário já existe!</h2>
                <a href='painel.php'>Voltar</a>
             </div>");
    }
    
    die("Erro ao inserir os dados: " . $e->getMessage());
}

$pdo = null;
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Usuário Cadastrado</title>
    
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

        .btn:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>

    <div class="container">
        <div class="icone-sucesso">✅</div>
        <h2>Cadastro feito com sucesso!</h2>
        
        <p>O usuário <strong><?= htmlspecialchars($username) ?></strong> foi adicionado ao sistema e já pode fazer login.</p>
        
        <a href="painel.php" class="btn">Voltar ao Painel</a>
    </div>

</body>
</html>