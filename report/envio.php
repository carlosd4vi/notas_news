<?php 
// Inicia a sessão no início do script
session_start();
if (!isset($_POST['token']) || !isset($_SESSION['form_token'])) {
    die('Erro Token Invalido');
}

// Verifica se os tokens são iguais
if ($_POST['token'] !== $_SESSION['form_token']) {
    die('Tentativa de reenvio do formulário. Ação não permitida.');
}

// OBRIGATÓRIO: Destruir o token para evitar que o usuário reenvie o formulário novamente
unset($_SESSION['form_token']);

// Se chegou aqui, os dados são válidos. Prossiga com o processamento.
// ...
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
            <img src="img/logo.jpeg" alt="logo da página" class="img-logo"> </img>
        </div>
</div>
    </header>
    <nav class="menu-navegacao">
        <ul>
            <li><a href="index.php">Início</a></li>
            <li><a href="autenticar/login2.php">Painel</a></li>
        </ul>
</nav>
<center>
<h1>Report:</h1>
  <br>
<div class="container-form">
  <div class="formulario-box">
      <div class="form-group">
        <?php
$host = 'localhost'; // Ou o IP do seu servidor de banco de dados
$dbname = 'bank_dados';
$usuario = 'root'; // Ou seu usuário
$senha = ''; // Ou sua senha
$charset = 'utf8';

try {
    // String de conexão
    $dsn = "mysql:host=$host;dbname=$dbname;charset=$charset";
    $pdo = new PDO($dsn, $usuario, $senha);

    // Configura o PDO para lançar exceções em caso de erro.
    // Isso facilita a depuração e o tratamento de erros.
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    // Em caso de erro, exibe uma mensagem amigável e encerra o script.
    die("Erro na conexão com o banco de dados: " . $e->getMessage());
}
$link = htmlspecialchars($_POST['link']);
$texto = htmlspecialchars($_POST['texto']);
$motivo = htmlspecialchars($_POST['motivo']);
$link_ex = htmlspecialchars($_POST['link_ex']);

if (isset($link_ex) && $link_ex == "") {
    return;
}

if (substr($link_ex, 0, 8) === 'https://') {
} else {
    echo "Erro";
    return;
}

if (isset($link) && $link == "") {
    return;
}

if (substr($link, 0, 8) === 'https://') {
} else {
    echo "Erro";
    return;
}

if (isset($texto) && $texto == "") {
    return;
}
if (isset($motivo) !== in_array($motivo, ["Nenhum", "Falso", "Desatualizado", "Erro de ortografia", "Linguagem enviesada"])) {
    return;
}
$sql = "INSERT INTO report (link_ex, link, texto , motivo) VALUES (?, ?, ?, ?)";


    try {
        // 3. Prepara a consulta para execução
        $stmt = $pdo->prepare($sql);

        // 4. Executa a consulta, passando os valores em um array
        // A ordem dos valores no array deve ser a mesma dos placeholders
        $stmt->execute([$link_ex, $link, $texto, $motivo]);

        echo '<label for="titulo">Enviado com sucesso!</label>';

    } catch (PDOException $e) {
        die("Erro ao inserir os dados: " . $e->getMessage());
    }
    ?>
      </div>
      <a href="index.php">Sair</button>
  </div>
</div>
</center>
    <footer class="rodape">
        <p>&copy; 2025 Portal de Notícias. Todos os direitos reservados.</p>
    </footer>
</body>
</html>