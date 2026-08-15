<!DOCTYPE html>
<html lang="pt-br">
<head>

    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notas News</title>
    <link rel="icon" href="../img/logo.jpeg" type="image/jpeg">
    <link rel="stylesheet" href="http://localhost/davi/notas_news/notas_news_beta/css/estilo.css">
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
        <a href="../sair.php">Sair </a>
</div>
    </header>
    <nav class="menu-navegacao">
        <ul>
            <li><a href="../index.php">Início</a></li>
            <li><a href="../painel.php">Painel</a></li>
            <li><a href="../cadastro.php">Cadastro</a></li>
            <li><a href="../denuncia.php">Denuncia</a></li>
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
<center>
<h1>Painel de Posts:</h1>
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
$sql = "SELECT id, titulo, descricao, link, link_2, data FROM dados ORDER BY id DESC LIMIT 1";

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Exibe os dados de cada linha
    while($row = $result->fetch_assoc()) {
        ?>
<div class="container-form">
  <div class="formulario-box">
    <form class="meu-formulario">
      <div class="form-group">
      <label for="titulo">ID:</label>
        <span><?php echo htmlspecialchars($row['id']); ?> </span>
        <label for="titulo">Titulo:</label>
        <span><?php echo htmlspecialchars($row['titulo']); ?> </span>
        <label for="titulo">Descrição:</label>
        <span><?php echo htmlspecialchars($row['descricao']); ?> </span>
        <label for="titulo">Link:</label>
        <span><?php echo htmlspecialchars($row['link']); ?> </span>
        <label for="titulo">Link 2:</label>
        <span><?php echo htmlspecialchars($row['link_2']); ?> </span>
        <label for="titulo">Data:</label>
        <span> <?php echo htmlspecialchars($row['data']); ?></span>
        <button onclick="copyText()" style="background-color:green;">Copiar</button>
    <textarea style="display:none;" id="texto" placeholder="Exemplo de texto para copiar..."><?php echo htmlspecialchars($row['descricao']); ?>&#10;noticia.php?token=<?php echo htmlspecialchars($row['id']); ?></textarea>
        <a href="preparo.php?preparo=editar&id=<?php echo htmlspecialchars($row['id']); ?>">Editar</a>
        <a href='preparo.php?preparo=excluir&id=<?php echo htmlspecialchars($row['id']); ?>'>Excluir</a>
        
      </div>
    </form>
  </div>
</div>
<script>
    function copyText() {
        // Seleciona o elemento textarea pelo ID
        const textarea = document.getElementById('texto');
        // Usa a API do navegador para copiar o texto
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
<?php 
    }
} else {
    echo "Nenhuma ID encontrada.";
}

$conn->close();
?>
</section>
    <footer class="rodape">
        <p>&copy; 2025 Portal de Notícias. Todos os direitos reservados.</p>
    </footer>
</body>
</html>