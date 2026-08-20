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
        /* Estilos para centralizar a div do formulário (mantidos do exemplo anterior) */
.container-form {
  display: flex;
  justify-content: center;
  align-items: center;
  min-height: 100vh; /* Use min-height para permitir que a página role se o conteúdo for maior */
  background-color: #f4f4f4; /* Cor de fundo suave */
  font-family: Arial, sans-serif; /* Fonte padrão */
}

.formulario-box {
  background-color: #ffffff; /* Fundo branco para o box do formulário */
  padding: 30px;
  border-radius: 10px;
  box-shadow: 0 8px 16px rgba(0, 0, 0, 0.15); /* Sombra mais pronunciada */
  width: 100%; /* Ocupa toda a largura disponível */
  max-width: 400px; /* Largura máxima para o formulário */
  box-sizing: border-box; /* Garante que padding não aumente a largura total */
}

/* Estilos para o formulário */
.meu-formulario {
  display: flex; /* Transforma o formulário em um flex container */
  flex-direction: column; /* Organiza os itens em coluna (um abaixo do outro) */
  gap: 15px; /* Espaçamento entre os grupos de formulário */
}

.form-group {
  display: flex;
  flex-direction: column; /* Coloca label e input em coluna */
}

.form-group label {
  margin-bottom: 5px; /* Espaçamento entre o label e o input */
  font-weight: bold; /* Deixa o label em negrito */
  color: #333;
}

/* Estilos para os inputs de texto */
.meu-formulario input {
  padding: 12px;
  border: 1px solid #ccc;
  border-radius: 5px;
  font-size: 1em;
  width: 100%; /* Ocupa 100% da largura do seu contêiner pai */
  box-sizing: border-box; /* Inclui padding e borda na largura total */
  transition: border-color 0.3s ease; /* Transição suave na borda */
}

.meu-formulario input:focus {
  border-color: #007bff; /* Borda azul ao focar */
  outline: none; /* Remove o outline padrão do navegador */
  box-shadow: 0 0 0 3px rgba(0, 123, 255, 0.25); /* Sombra suave ao focar */
}

/* Estilos para o botão */
.meu-formulario button {
  background-color: #007bff; /* Cor de fundo azul */
  color: white; /* Texto branco */
  padding: 12px 20px;
  border: none;
  border-radius: 5px;
  font-size: 1.1em;
  cursor: pointer; /* Muda o cursor para indicar que é clicável */
  transition: background-color 0.3s ease, transform 0.2s ease; /* Transição suave */
  margin-top: 10px; /* Espaçamento acima do botão */
}

.meu-formulario button:hover {
  background-color: #0056b3; /* Cor de fundo mais escura ao passar o mouse */
  transform: translateY(-2px); /* Efeito de "levantar" ao passar o mouse */
}

.meu-formulario button:active {
  background-color: #004085; /* Cor ainda mais escura ao clicar */
  transform: translateY(0); /* Volta à posição normal ao clicar */
}
    </style>
    </style>
</head>
<body>
<header class="cabecalho">
        <div class="logo">
            <img src="../img/logo.jpeg" alt="logo da página" class="img-logo"> </img>
        </div>
        <a href="sair.php">Sair </a>
</div>
    </header>
    <nav class="menu-navegacao">
        <ul>
            <li><a href="../index.php">Início</a></li>
            <li><a href="postagem/posts.php">Postagem</a></li>
            <li><a href="cadastro.php">Cadastro</a></li>
            <li><a href="denuncia.php">Denuncia</a></li>
        </ul>
</nav>
    <?php
session_start();

if (!isset($_SESSION['logado']) || $_SESSION['logado'] !== true) {
    header("location: login.php");}
?>
<?php
$token = bin2hex(random_bytes(32));
$_SESSION['form_token'] = $token;
?>
<center>
<h1>Olá, <?php echo $_SESSION['admin_usuario']; ?></h1>
  <br>
<div class="container-form">
  <div class="formulario-box">
    <form action="envio.php" method="post" class="meu-formulario">
      <div class="form-group">
        <label for="titulo">Título:</label>
        <input type="text" placeholder="digite o titulo..." id="titulo" name="tl" required>
      </div>

      <div class="form-group">
        <label for="descricao">Descrição:</label>
        <input type="text" placeholder="digite a descrição..." id="descricao" name="des" required>
      </div>

      <div class="form-group">
        <label for="fonte1">Fonte 1:</label>
        <input type="text" name="ft" placeholder="digite a fonte..." id="fonte1" required>
      </div>

      <div class="form-group">
        <label for="fonte2">Fonte 2 (opcional):</label>
        <input type="text" name="ft2" placeholder="digite a fonte..." id="fonte2">
      </div>
      <input type="hidden" name="token" value="<?php echo htmlspecialchars($token); ?>" required>

      <button type="submit">Enviar</button>
    </form>
  </div>
</div>

</center>
<h3>Ultimas Publicações: </h3>
<section class="noticias-secundarias">
<?php
require_once '../db/conexao.php';

$noticias = [];
$sql = "SELECT id, titulo, descricao, link1, link2, data FROM dados_noticia ORDER BY data DESC LIMIT 6";

try {
    $stmt = $pdo->query($sql);
    
    if ($stmt) {
        $noticias = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
} catch (PDOException $e) {
    error_log("Erro na consulta de notícias: " . $e->getMessage());
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
        <p>&copy; <?= date('Y') ?> Portal de Notícias. Todos os direitos reservados.</p>
    </footer>
</body>
</html>