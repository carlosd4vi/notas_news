<?php 
session_start();
if (empty($_POST['token']) || empty($_SESSION['form_token']) || $_POST['token'] !== $_SESSION['form_token']) {
    die('Erro: Token inválido ou tentativa de reenvio do formulário.');
}
unset($_SESSION['form_token']);

$link = trim($_POST['link'] ?? '');
$texto = trim($_POST['texto'] ?? '');
$motivo = trim($_POST['motivo'] ?? '');
$link_ex = trim($_POST['link_ex'] ?? ''); 

$mensagem = "";
$sucesso = false;

$motivos_permitidos = ["Nenhum", "Falso", "Desatualizado", "Erro de ortografia", "Linguagem enviesada"];

if (empty($link) || empty($texto) || empty($link_ex) || empty($motivo)) {
    $mensagem = "Erro: Todos os campos são obrigatórios.";
} elseif (substr($link, 0, 8) !== 'https://' || substr($link_ex, 0, 8) !== 'https://') {
    $mensagem = "Erro: Os links devem obrigatoriamente começar com https://";
} elseif (!in_array($motivo, $motivos_permitidos)) {
    $mensagem = "Erro: Motivo selecionado é inválido.";
} else {
    require_once '../db/conexao.php'; 

    $sql = "INSERT INTO report (post, motivo, fonte, texto) VALUES (?, ?, ?, ?)";
    
    try {
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$link, $motivo, $link_ex, $texto]);
        
        $sucesso = true;
        $mensagem = "Report enviado com sucesso! Agradecemos a contribuição.";
        
    } catch (PDOException $e) {
        $mensagem = "Erro ao inserir os dados no banco: " . $e->getMessage();
    }
    
    $pdo = null;
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notas News - Status do Report</title>
    <link rel="icon" href="img/logo.jpeg" type="image/jpeg">
    <link rel="stylesheet" href="../css/estilo.css">

    <style>
        .container-form {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 70vh;
            background-color: #f4f4f4; 
            font-family: Arial, sans-serif;
        }

        .formulario-box {
            background-color: #ffffff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.15);
            width: 100%;
            max-width: 400px; 
            box-sizing: border-box;
            text-align: center;
        }

        .msg-alerta {
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 5px;
            font-size: 1.1em;
        }
        .msg-sucesso {
            color: #155724;
            background-color: #d4edda;
            border: 1px solid #c3e6cb;
        }
        .msg-erro {
            color: #721c24;
            background-color: #f8d7da;
            border: 1px solid #f5c6cb;
        }

        .btn-sair {
            display: inline-block;
            background-color: #007bff;
            color: white;
            padding: 12px 20px;
            border: none;
            border-radius: 5px;
            font-size: 1.1em;
            text-decoration: none;
            cursor: pointer;
            transition: background-color 0.3s ease, transform 0.2s ease;
            margin-top: 10px;
        }

        .btn-sair:hover {
            background-color: #0056b3;
            transform: translateY(-2px);
        }

        .btn-sair:active {
            background-color: #004085;
            transform: translateY(0);
        }
    </style>
</head>
<body>
    <header class="cabecalho">
        <div class="logo">
            <img src="../img/logo.jpeg" alt="logo da página" class="img-logo">
        </div>
    </header>
    
    <nav class="menu-navegacao">
        <ul>
            <li><a href="../index.php">Início</a></li>
            <li><a href="../session/painel.php">Painel</a></li>
        </ul>
    </nav>
        <div class="container-form">
            <div class="formulario-box">
                <div class="msg-alerta <?= $sucesso ? 'msg-sucesso' : 'msg-erro' ?>">
                    <?= $mensagem ?>
                </div>

                <a href="../index.php" class="btn-sair">Voltar ao Início</a>
                
            </div>
        </div>

    <footer class="rodape">
        <p>&copy; <?= date('Y') ?> Portal de Notícias. Todos os direitos reservados.</p>
    </footer>
</body>
</html>