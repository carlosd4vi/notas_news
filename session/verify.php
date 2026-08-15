<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    die("Acesso inválido.");
}

if (empty($_POST['token']) || empty($_SESSION['form_token']) || !hash_equals($_SESSION['form_token'], $_POST['token'])) {
    die('Token inválido ou expirado. Atualize a página e tente novamente.');
}

unset($_SESSION['form_token']);

$usuario = trim($_POST['usuario'] ?? '');
$senha = $_POST['senha'] ?? '';

if (empty($usuario) || empty($senha)) {
    die("Por favor, preencha usuário e senha.");
}

require_once 'postagem/conexao.php'; 

try {
    $sql = "SELECT id, usuario, senha FROM admin WHERE usuario = :usuario LIMIT 1";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':usuario' => $usuario]);
    $usuarioBanco = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($usuarioBanco && password_verify($senha, $usuarioBanco['senha'])) {
        
        session_regenerate_id(true);

        $_SESSION['logado'] = true;
        $_SESSION['id'] = $usuarioBanco['id'];
        $_SESSION['usuario'] = $usuarioBanco['usuario'];

        header("Location: painel.php");
        exit;
        
    } else {
        echo "Usuário ou senha incorretos.";
    }

} catch (PDOException $e) {
    error_log("Erro no login: " . $e->getMessage());
    echo "Erro interno no servidor. Tente novamente mais tarde.";
}

$pdo = null;
?>