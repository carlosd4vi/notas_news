<!DOCTYPE html>
<html lang="pt-br">
<head>

    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notas News</title>
    <link rel="icon" href="../img/logo.jpeg" type="image/jpeg">
    <link rel="stylesheet" href="../css/estilo.css">
    <style>
        <style>
.container-form {
  display: flex;
  justify-content: center;
  align-items: center;
  min-height: 100vh; 
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
  margin-bottom: 5px;
  font-weight: bold; 
  color: #333;
}

.meu-formulario input {
  padding: 12px;
  border: 1px solid #ccc;
  border-radius: 5px;
  font-size: 1em;
  width: 100%; 
  box-sizing: border-box; 
  transition: border-color 0.3s ease; 
}

.meu-formulario input:focus {
  border-color: #007bff; 
  outline: none;
  box-shadow: 0 0 0 3px rgba(0, 123, 255, 0.25); 
}

.meu-formulario button {
  background-color: #007bff; 
  color: white;
  padding: 12px 20px;
  border: none;
  border-radius: 5px;
  font-size: 1.1em;
  cursor: pointer;
  transition: background-color 0.3s ease, transform 0.2s ease;
  margin-top: 10px;
}

.meu-formulario button:hover {
  background-color: #0056b3;
  transform: translateY(-2px); 
}

.meu-formulario button:active {
  background-color: #004085;
  transform: translateY(0); 
}
    </style>
    </style>
</head>
<body>
<header class="cabecalho">
        <div class="logo">
            <img src="../img/logo.jpeg" alt="logo da página" class="img-logo"> </img>
        </div>
        <div class="pesquisa">
    <input placeholder="Pesquisar..." onclick="javascript:alert('indisponivel');">
            <button>Buscar</button>
</div>
    </header>
    <nav class="menu-navegacao">
        <ul>
            <li><a href="../index.php">Início</a></li>
        </ul>
</nav>
<center>
<?php
session_start();
$token = bin2hex(random_bytes(32));
$_SESSION['form_token'] = $token;
?>
  <br>
<div class="container-form">
  <div class="formulario-box">
    <form class="meu-formulario" action="verify.php" method="post" id="form">
      <div class="form-group">
        <label for="titulo">Login:</label>
        <input type="text" id="usuario" name="usuario" required>
    </div>

      <div class="form-group">
        <label for="descricao">Senha:</label>
        <input type="password" id="senha" name="senha" required>
      </div>
      <input type="hidden" name="token" value="<?php echo htmlspecialchars($token); ?>" required>
      <button type="submit">Entrar</button>
    </form>
  </div>
</div>

</center>
<h3>Ultimas Publicações: </h3>
<section class="noticias-secundarias">
<?php
require_once 'postagem/conexao.php';

$noticias = [];
$sql = "SELECT id, titulo, descricao, link1, link2, data FROM dados_noticia ORDER BY data DESC LIMIT 6";

try {
    $stmt = $pdo->query($sql);
    
    if ($stmt) {
        $noticias = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
} catch (PDOException $e) {
    error_log("Erro na consulta: " . $e->getMessage());
}

$pdo = null;
?>

<?php if (count($noticias) > 0): ?>
    <?php foreach ($noticias as $row): ?>
        <article class="card-noticia">
            <a href="../noticia.php?token=<?= urlencode($row['id']) ?>">
                <h3 class="titulo"><?= htmlspecialchars($row['titulo']) ?></h3>
                <p class="descricao"><?= htmlspecialchars($row['descricao']) ?></p>
            </a>
            <br>
            <span><?= htmlspecialchars($row['data']) ?></span>
        </article>
    <?php endforeach; ?>
<?php else: ?>
    <p>Nenhuma notícia encontrada.</p>
<?php endif; ?>

</section>

    <footer class="rodape">
        <p>&copy; <?= date('Y') ?> Notas News. Todos os direitos reservados.</p>
    </footer>
</body>
</html>