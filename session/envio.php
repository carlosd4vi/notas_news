<style>
        .container {
            display: flex;
            flex-direction: column;
            gap: 10px;
            width: 400px;
            padding: 20px;
            border-radius: 8px;
            background-color: #fff;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        textarea {
            width: 100%;
            height: 150px;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 16px;
            resize: none; /* Impede o redimensionamento da textarea pelo usuário */
        }

        button {
            padding: 10px 15px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }

        button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<p>
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

require_once 'postagem/conexao.php';

$sql = "INSERT INTO dados (titulo, descricao, link, link_2) VALUES (?, ?, ?, ?)";

try {
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$titulo, $descricao, $fonte1, $fonte2]);
    $id_noticia = $pdo->lastInsertId();

    echo "<p style='color: green; text-align: center;'>Enviado com sucesso!</p>";

} catch (PDOException $e) {
    die("Erro ao inserir os dados: " . $e->getMessage());
}

$pdo = null;

$url_base = "noticia.php?token=";
$texto_compartilhar = $descricao . " - Leia mais em: " . $url_base . $id_noticia;
?>

<div class="container" style="text-align: center; margin-top: 30px;">
    <label for="texto">Texto gerado para compartilhamento:</label><br><br>
    
    <textarea id="texto" rows="5" cols="60" style="padding: 10px;"><?= htmlspecialchars($texto_compartilhar) ?></textarea><br><br>
    
    <button onclick="copyText()" style="padding: 10px 20px; cursor: pointer;">Copiar Texto</button>
</div>

<script>
    function copyText() {
        const textarea = document.getElementById('texto');
        
        textarea.select();
        textarea.setSelectionRange(0, 99999);
        
        navigator.clipboard.writeText(textarea.value)
            .then(() => {
                alert("Texto copiado para a área de transferência!");
            })
            .catch(err => {
                console.error("Erro ao copiar o texto:", err);
                alert("Ocorreu um erro ao copiar o texto.");
            });
    }
</script>