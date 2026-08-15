<?php
session_start();

// Verifica se o usuário está logado
if (!isset($_SESSION['logado']) || $_SESSION['logado'] !== true) {
    // Se não estiver logado, redireciona para a página de login
    header("location: login.php");}

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

    echo "Enviado com sucesso e conexão bem sucedida!";

} catch (PDOException $e) {
    // Em caso de erro, exibe uma mensagem amigável e encerra o script.
    die("Erro na conexão com o banco de dados: " . $e->getMessage());
}
$username = $_POST['usuario'];
$password = $_POST['senha'];

$senha_hash = password_hash($password, PASSWORD_DEFAULT);


$sql = "INSERT INTO usuario (usuario,senha) VALUES (?, ?)";


    try {
        // 3. Prepara a consulta para execução
        $stmt = $pdo->prepare($sql);

        // 4. Executa a consulta, passando os valores em um array
        // A ordem dos valores no array deve ser a mesma dos placeholders
        $stmt->execute([$username, $senha_hash]);

    } catch (PDOException $e) {
        die("Erro ao inserir os dados: " . $e->getMessage());
    }
?>