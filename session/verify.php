<?php
session_start();
header('Content-Type: application/json; charset=utf-8');

if (empty($_POST['token']) || empty($_SESSION['form_token']) || !hash_equals($_SESSION['form_token'], $_POST['token'])) {
    echo json_encode([
        'sucesso' => false,
        'mensagem' => 'Sessão expirada ou token inválido. Atualize a página.'
    ]);
    exit;
}

$usuario = trim($_POST['usuario'] ?? '');
$senha = $_POST['senha'] ?? '';

if (empty($usuario) || empty($senha)) {
    echo json_encode([
        'sucesso' => false,
        'mensagem' => 'Preencha o usuário e a senha.'
    ]);
    exit;
}

require_once '../db/conexao.php';

try {
    $sql = "SELECT id, usuario, senha FROM admin WHERE usuario = ? LIMIT 1";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$usuario]);
    $admin = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($admin && password_verify($senha, $admin['senha'])) {
        $_SESSION['logado'] = true;
        $_SESSION['admin_id'] = $admin['id'];
        $_SESSION['admin_usuario'] = $admin['usuario'];
        $_SESSION['form_token'] = bin2hex(random_bytes(32));

        echo json_encode(['sucesso' => true]);
        exit;
    } else {
        echo json_encode([
            'sucesso' => false,
            'mensagem' => 'Usuário ou senha incorretos.'
        ]);
        exit;
    }

} catch (PDOException $e) {
    error_log("Erro no login: " . $e->getMessage());

    echo json_encode([
        'sucesso' => false,
        'mensagem' => 'Erro interno ao processar o login. Tente novamente mais tarde.'
    ]);
    exit;
} finally {
    $pdo = null;
}