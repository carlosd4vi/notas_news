<!DOCTYPE html>
<html lang="pt-br">
<head>

    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nota News</title>
    <link rel="icon" href="img/logo.jpeg" type="image/jpeg">
    <link rel="stylesheet" href="css/estilo.css">
</head>
<body>
<header class="cabecalho">
            <img src="img/logo.jpeg" alt="logo da página" class="img-logo"> </img>
        <div class="pesquisa">
            <input type="text" id="pesquisar" onclick="javascript:alert('indisponivel');" placeholder="Pesquisar...">
            <button>Buscar</button>
        </div>
    </header>
    <nav class="menu-navegacao">
        <ul>
            <li><a href="#">Início</a></li>
            <li><a href="session/painel.php">Painel</a></li>
        </ul>
    </nav>

    <main class="container">
        <section class="noticia-principal">
            <article class="card-noticia destaque">
                <h2>Nota News</h2>
                <p>Pequenas notas de noticias, seja tecnologia, financeiro, empresarial, entretenimento.</p>
                <a href="#">Saiba Mais</a>
            </article>
        </section>
        Mais Recentes:
        <section class="noticias-secundarias">
            <?php
require_once 'db/conexao.php';

$noticias = [];
$sql = "SELECT id, titulo, descricao, link1, link2, data FROM dados_noticia ORDER BY data DESC LIMIT 6";

try {
    $stmt = $pdo->query($sql);

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $noticias[] = $row;
    }
} catch (PDOException $e) {
    error_log("Erro na consulta de notícias: " . $e->getMessage());
}

$pdo = null;
?>

<?php if (!empty($noticias)): ?>
    <?php foreach ($noticias as $noticia): ?>
        <article class="card-noticia">
            <a href="noticia.php?token=<?= urlencode($noticia['id']) ?>">
                <h3 class="titulo"><?= htmlspecialchars($noticia['titulo']) ?></h3>
                <p class="descricao"><?= htmlspecialchars($noticia['descricao']) ?></p>
            </a>
            <br>
            <span class="data"><?= htmlspecialchars($noticia['data']) ?></span>
        </article>
    <?php endforeach; ?>
<?php else: ?>
    <p>Nenhuma notícia encontrada.</p>
<?php endif; ?>
        </section>
</main>
    <footer class="rodape">
        <p>&copy; <?= date('Y') ?> Notas News. Todos os direitos reservados.</p>
    </footer>
</body>
</html>