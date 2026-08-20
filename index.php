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
        <img src="img/logo.jpeg" alt="logo da página" class="img-logo">
        <form class="pesquisa" action="index.php" method="GET">
            <input type="text" name="busca" id="pesquisar" placeholder="Pesquisar..." value="<?= htmlspecialchars($_GET['busca'] ?? '') ?>">
            <button type="submit">Buscar</button>
        </form>
    </header>
    
    <nav class="menu-navegacao">
        <ul>
            <li><a href="index.php">Início</a></li>
            <li><a href="session/painel.php">Painel</a></li>
        </ul>
    </nav>

    <main class="container">
        
        <?php
        $termo_busca = trim($_GET['busca'] ?? '');
        if (empty($termo_busca)): 
        ?>
        <section class="noticia-principal">
            <article class="card-noticia destaque">
                <h2>Nota News</h2>
                <p>Pequenas notas de noticias, seja tecnologia, financeiro, empresarial, entretenimento.</p>
                <a href="#">Saiba Mais</a>
            </article>
        </section>
        <?php endif; ?>
        <h3 style="margin-top: 20px;">
            <?= !empty($termo_busca) ? "Resultados para: '" . htmlspecialchars($termo_busca) . "'" : "Mais Recentes:" ?>
        </h3>
        
        <section class="noticias-secundarias">
            <?php
            require_once 'db/conexao.php';

            $noticias = [];

            try {
                if (!empty($termo_busca)) {
                    $sql = "SELECT id, titulo, descricao, link1, link2, data FROM dados_noticia WHERE titulo LIKE ? OR descricao LIKE ? ORDER BY data DESC LIMIT 20";
                    $stmt = $pdo->prepare($sql);
                    
                    $busca_sql = "%" . $termo_busca . "%";
                    $stmt->execute([$busca_sql, $busca_sql]);
                } else {
                    $sql = "SELECT id, titulo, descricao, link1, link2, data FROM dados_noticia ORDER BY data DESC LIMIT 6";
                    $stmt = $pdo->query($sql);
                }

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
                <div style="text-align: center; width: 100%; padding: 30px;">
                    <p style="font-size: 1.2em; color: #555;">Nenhuma notícia encontrada com esse termo.</p>
                    <a href="index.php" style="color: #007bff; text-decoration: none; font-weight: bold;">Limpar pesquisa</a>
                </div>
            <?php endif; ?>
        </section>
    </main>

    <footer class="rodape">
        <p>&copy; <?= date('Y') ?> Notas News. Todos os direitos reservados.</p>
    </footer>
</body>
</html>