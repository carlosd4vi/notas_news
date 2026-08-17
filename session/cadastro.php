    <?php
session_start();

if (!isset($_SESSION['logado']) || $_SESSION['logado'] !== true) {
    header("location: login.php");}
?>
<?php
$token = bin2hex(random_bytes(32));
$_SESSION['form_token'] = $token;
?>
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
  max-width: 400px;/
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
        <a href="sair.php">Sair </a>
</div>
    </header>
    <nav class="menu-navegacao">
        <ul>
            <li><a href="../index.php">Início</a></li>
            <li><a href="painel.php">Painel</a></li>
            <li><a href="postagem/posts.php">Postagem</a></li>
            <li><a href="denuncia.php">Denuncia</a></li>
        </ul>
</nav>
<center>
<h1>Painel de cadastro:</h1>
  <br>
<div class="container-form">
  <div class="formulario-box">
    <form action="register.php" method="post" class="meu-formulario">
      <div class="form-group">
        <label for="titulo">Usuário:</label>
        <input type="text" placeholder="Usuario de cadastro..." name="usuario" required autocomplete="off">
        <label for="senha">Senha:</label>
        <input type="password" placeholder="Senha de cadastro..." name="senha" required>
        <input type="hidden" name="token" value="<?php echo htmlspecialchars($token); ?>" required>
      </div>
      <button type="submit">Cadastrar</button>
    </form>
  </div>
</div>

</center>
</section>
    <footer class="rodape">
        <p>&copy; 2025 Portal de Notícias. Todos os direitos reservados.</p>
    </footer>
</body>
</html>