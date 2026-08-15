<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notas News</title>
    <link rel="icon" href="img/logo.jpeg" type="image/jpeg">
    <link rel="stylesheet" href="css/estilo.css">
</head>
<body>
<header class="cabecalho">
        <div class="logo">
            <img src="img/logo.jpeg" alt="logo da página" class="img-logo"> </img>
        </div>
        <div class="pesquisa">
            <input type="text" placeholder="Pesquisar..." onclick="javascript:alert('Insdisponivel');">
            <button>Buscar</button>
        </div>
    </header>

    <nav class="menu-navegacao">
        <ul>
            <li><a href="index.php">Início</a></li>
            <li><a href="session/painel.php">Painel</a></li>
        </ul>
    </nav>
    <?php
require_once 'db/conexao.php';

$url = $_GET['token'];

$url_ok = htmlspecialchars($url, ENT_QUOTES, 'UTF-8');


$sql = "SELECT * FROM dados_noticia WHERE id = ?";

try {
    $stmt = $pdo->prepare($sql);

    $stmt->execute([$url_ok]);

    $dados = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($dados) {

    } else {
        echo "Conteúdo não encontrado!";
    }
} catch (PDOException $e) {
    die("Erro na consulta: " . $e->getMessage());
}

$pdo = null;



?>
    <main class="container">
        <section class="noticia-principal">
            <article class="card-noticia destaque">
                <h2 id="titulo"> <?php echo $dados['titulo']; ?> </h2>
                <span id="descricao"> <?php echo $dados['descricao']; ?> </span>
                <p id="data"><?php echo "Publicado: " . $dados['data']; ?> </p>
                <a href="<?php echo $dados['link1']; ?>" id="link1" target="_blank"><?php echo $dados['link1']; ?></a>
                <br>
                <a href="<?php echo $dados['link2']; ?>" id="link2" target="_blank" > <?php echo $dados['link2']; ?></a>
                <br>
                <a href="report.php" style="color:red;">Reportar </a>
            </article>
        </section>
        
    <footer class="rodape">
        <p>&copy; <?= date('Y') ?> Notas News. Todos os direitos reservados.</p>
    </footer>
</body>
</html>