<?php
session_start();
// Verifica se o usuário está logado
if (!isset($_SESSION['logado']) || $_SESSION['logado'] !== true) {
    // Se não estiver logado, redireciona para a página de login
    header("location: login.php");}
require_once 'conexao.php';

// Verifica se os parâmetros 'acao' e 'id' existem na URL
if (isset($_GET['preparo']) && isset($_GET['id'])) {
    
    // Filtra e converte o ID para um número inteiro
    $id = filter_var($_GET['id'], FILTER_VALIDATE_INT);
    $acao = $_GET['preparo'];

    // Valida se o ID é um número inteiro válido
    if ($id === false) {
        die("ID inválido.");
    }

    switch ($acao) {
        case 'editar':
            // Lógica de Edição (exemplo: mostra um formulário de edição)
            // Geralmente, você redireciona para uma página de edição
            echo "<h2>Formulário de Edição</h2>";
            echo "Editando o item com ID: " . htmlspecialchars($id);
            // Aqui você deve carregar os dados do item e exibir um formulário
            // de edição para o usuário preencher. Após o envio do formulário,
            // outra lógica de UPDATE seria executada.
            break;

        case 'excluir':
            // Lógica de Exclusão
            try {
                // Prepara a consulta SQL para exclusão
                $stmt = $pdo->prepare("DELETE FROM dados WHERE id = ?");
                
                // Executa a consulta com o ID
                if ($stmt->execute([$id])) {
                    echo "Item com ID " . htmlspecialchars($id) . " excluído com sucesso!";
                } else {
                    echo "Erro ao excluir o item.";
                }
            } catch (PDOException $e) {
                echo "Erro na exclusão: " . $e->getMessage();
            }
            break;

        default:
            echo "Ação inválida.";
            break;
    }

} else {
    // Redireciona de volta para a página principal se os parâmetros estiverem faltando
    header('Location: index.php');
    exit;
}
?>