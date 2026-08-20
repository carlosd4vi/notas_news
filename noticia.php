<?php
require_once 'db/conexao.php';

$url = $_GET['token'] ?? null;
$dados = false;

if ($url) {
    $sql = "SELECT * FROM dados_noticia WHERE id = ?";
    
    try {
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$url]);
        $dados = $stmt->fetch(PDO::FETCH_ASSOC);
        
    } catch (PDOException $e) {
        error_log("Erro na consulta da notícia: " . $e->getMessage());
    }
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
    <link rel="icon" href="img/logo.jpeg" type="image/jpeg">
    <link rel="stylesheet" href="css/estilo.css">
</head>
<body>
    <header class="cabecalho">
        <div class="logo">
            <img src="img/logo.jpeg" alt="logo da página" class="img-logo">
        </div>
        <form class="pesquisa" action="index.php" method="GET">
            <input type="text" name="busca" placeholder="Pesquisar..." required>
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
        
        <?php if ($dados): ?>
            <section class="noticia-principal">
                <article class="card-noticia destaque">
                    <h2 id="titulo"><?= htmlspecialchars($dados['titulo']) ?></h2>
                    <span id="descricao"><?= htmlspecialchars($dados['descricao']) ?></span>
                    <p id="data">Publicado: <?= htmlspecialchars($dados['data']) ?></p>
                    
                    <a href="<?= htmlspecialchars($dados['link1']) ?>" id="link1" target="_blank"><?= htmlspecialchars($dados['link1']) ?></a>
                    <br>
                    <?php if (!empty($dados['link2'])): ?>
                        <a href="<?= htmlspecialchars($dados['link2']) ?>" id="link2" target="_blank"><?= htmlspecialchars($dados['link2']) ?></a>
                        <br>
                    <?php endif; ?>                    <a href="report.php?token=<?= htmlspecialchars($dados['id']) ?>" style="color:red; display:inline-block; margin-top:15px;">Reportar</a>
                </article>
            </section>
            
        <?php else: ?>
            <section class="noticia-principal">
                <article class="card-noticia destaque" style="text-align: center; padding: 50px 20px;">
                    <div style="font-size: 50px; margin-bottom: 10px;">😕</div>
                    <h2 style="color: #dc3545; margin-bottom: 15px;">Conteúdo não encontrado!</h2>
                    <p style="color: #555; font-size: 16px;">A nota que você está tentando acessar não existe, tem um ID inválido ou foi removida do sistema.</p>
                    
                    <a href="index.php" style="display: inline-block; margin-top: 25px; padding: 10px 20px; background-color: #007bff; color: white; text-decoration: none; border-radius: 5px; font-weight: bold;">
                        Voltar para a Página Inicial
                    </a>
                </article>
            </section>
        <?php endif; ?>

    </main>

    <footer class="rodape">
        <p>&copy; <?= date('Y') ?> Notas News. Todos os direitos reservados.</p>
    </footer>
</body>
</html>