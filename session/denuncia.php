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
            <li><a href="painel.php">Painel</a></li>
            <li><a href="postagem/posts.php">Postagem</a></li>
            <li><a href="cadastro.php">Cadastro</a></li>
        </ul>
</nav>
    <?php
// Inicia a sessão
session_start();

// Verifica se o usuário está logado
if (!isset($_SESSION['logado']) || $_SESSION['logado'] !== true) {
    // Se não estiver logado, redireciona para a página de login
    header("location: login.php");}
?>
<?php
$token = bin2hex(random_bytes(32));
$_SESSION['form_token'] = $token;
?>
<center>
<h1>Painel de Reports:</h1>
<div class="container-form">
  <div class="formulario-box">
      <div class="form-group">
  <br>
      <?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "bank_dados";

// Cria a conexão
$conn = new mysqli($servername, $username, $password, $dbname);

// Checa a conexão
if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}

// Prepara a consulta para selecionar as 6 primeiras IDs
// Usamos LIMIT 6 para limitar os resultados e ORDER BY id ASC para garantir que pegamos as primeiras
$sql = "SELECT id, texto, link, link_ex, motivo, data_criada FROM report ORDER BY id DESC LIMIT 1";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    // Exibe os dados de cada linha
    while($row = $result->fetch_assoc()) {
?>
Protocolo: <?php echo htmlspecialchars($row['id']); ?> , <?php echo htmlspecialchars($row['data_criada']); ?>
<label for="titulo">Texto:</label>
          <p><?php echo htmlspecialchars($row['texto']); ?></p>
        <label for="titulo">Link do Post:</label>
        <a href="<?php echo htmlspecialchars($row['link']); ?>" target="_blank"><?php echo htmlspecialchars($row['link']); ?></a>
        <label for="titulo">Link Externo:</label>
        <a href="<?php echo htmlspecialchars($row['link_ex']); ?>" target="_blank"><?php echo htmlspecialchars($row['link_ex']); ?> </a>
        <label for="titulo">Motivo:</label>
        <p><?php echo htmlspecialchars($row['motivo']); ?> </p>

</div>
<form action="denuncia_envio.php" method="post" class="meu-formulario">
    <input type="hidden" value="<?php echo htmlspecialchars($row['id']); ?>" name="id">
    <input type="hidden" value="report" name="tabela">
      <button type="submit" style="background-color:green;">Manter</button>
    </form>
    <form action="denuncia_envio.php" method="post" class="meu-formulario">
      <input type="hidden" value="<?php
$link = htmlspecialchars($row['link']);
$ultimoCaractere = substr($link, -1);
echo $ultimoCaractere;
?>" name="id">
      <input type="hidden" value="dados" name="tabela">
      <button type="submit" style="background-color:red;">Excluir Publicação</button>
    </form>
  </div>
</div>
<?php 
    }
} else {
    echo "Nenhuma ID encontrada.";
}

$conn->close();
?>
</center>
    <footer class="rodape">
        <p>&copy; 2025 Portal de Notícias. Todos os direitos reservados.</p>
    </footer>
</body>
</html>