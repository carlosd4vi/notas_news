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
            <li><a href="session/painel.php">Painel</a></li>
        </ul>
</nav>
<?php
session_start();
if (empty($_SESSION['form_token'])) {
    $_SESSION['form_token'] = bin2hex(random_bytes(32));
}
$token = $_SESSION['form_token'];
?>
<center>
<h1>Report:</h1>
  <br>
<div class="container-form">
  <div class="formulario-box">
    <form action="report/envio.php" method="post" class="meu-formulario">
      <div class="form-group">
        <label for="link_post">Link do Post:</label>
        <input type="url" placeholder="Insira o link do post..." id="link_post" name="link" required maxlength="255">
        
        <label>Motivo: </label>
        <select id="motivo">
            <option>Nenhum</option>
            <option>Falso</option>
            <option>Desatualizado</option>
            <option>Erro de ortografia</option>
            <option>Linguagem enviesada</option>
        </select>
        
        <label for="link_externa">Fonte Externa:</label>
        <input type="url" placeholder="Insira sua fonte..." id="link_externa" name="link_ex" required maxlength="255">
        
        <input type="hidden" value="" id="envio_select" name="motivo">
        
        <label for="texto_post">Texto (max:255 linhas):</label>
        <input type="text" placeholder="Insira seu texto..." id="texto_post" name="texto" required maxlength="255">
        
        <input type="hidden" name="token" value="<?php echo htmlspecialchars($token); ?>" required>
      </div>
      <button type="submit" onclick="pegarMotivo();">Enviar</button>
    </form>
  </div>
</div>

<script>
var linkAnterior = document.referrer;
var pegarlink_anterior = document.getElementById("link_post");

if (pegarlink_anterior) {
    pegarlink_anterior.value = linkAnterior;
}

function pegarMotivo() {
    const selectElement = document.getElementById('motivo');
    const tag = document.getElementById('envio_select');
    const valorSelecionado = selectElement.value;
    tag.value = valorSelecionado;
}
</script>

</center>
<h3>Ultimas Publicações: </h3>
<section class="noticias-secundarias">
<?php
require_once 'db/conexao.php';

$sql = "SELECT id, titulo, descricao, link1, link2, data FROM dados_noticia ORDER BY data DESC LIMIT 6";

try {
    $stmt = $pdo->query($sql);
    $noticias = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (count($noticias) > 0) {
        foreach($noticias as $row) {
?>
            <article class="card-noticia">
                <a href="noticia.php?token=<?php echo urlencode($row['id']); ?>">
                    <h3 class="titulo"><?php echo htmlspecialchars($row['titulo']); ?></h3>
                    <p class="descricao"><?php echo htmlspecialchars($row['descricao']); ?></p>
                </a>
                
                <br>
                <span><?php echo htmlspecialchars($row['data']); ?></span>
            </article>
<?php 
        }
    } else {
        echo "Nenhuma ID encontrada.";
    }
} catch (PDOException $e) {
    echo "Erro na consulta: " . $e->getMessage();
}

$pdo = null;
?>
</section>
    <footer class="rodape">
        <p>&copy; <?= date('Y') ?> Notas News. Todos os direitos reservados.</p>
    </footer>
</body>
</html>