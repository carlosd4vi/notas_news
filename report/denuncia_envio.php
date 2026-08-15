<?php
 // Inicia a sessão
 session_start();
 
 // Verifica se o usuário está logado
 if (!isset($_SESSION['logado']) || $_SESSION['logado'] !== true) {
     // Se não estiver logado, redireciona para a página de login
     header("location: login.php");}

// 1. Configuração do banco de dados
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "bank_dados";

// ID do registro que você quer excluir (exemplo)
$id = $_POST["id"];

$tabela = $_POST["tabela"];

// 2. Conexão com o banco de dados
$conn = new mysqli($servername, $username, $password, $dbname);

// Verifica a conexão
if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}

$sql = "DELETE FROM " . $tabela . " WHERE id = ?";
// Prepara a declaração
$stmt = $conn->prepare($sql);

// Vincula o parâmetro (s = string, i = integer, d = double, b = blob)
$stmt->bind_param("i", $id);

// 4. Executar a consulta
if ($stmt->execute()) {
    echo "Registro excluído com sucesso!"
    . "<a href='denuncia.php'>Voltar </a>";
} else {
    echo "Erro ao excluir o registro: " . $stmt->error;
}

// 5. Fechar a declaração e a conexão
$stmt->close();
$conn->close();

?>