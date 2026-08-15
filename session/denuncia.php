<!DOCTYPE html>
<html lang="pt-br">
<head>

    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notas News</title>
    <link rel="icon" href="../img/logo.jpeg" type="image/jpeg">
    <link rel="stylesheet" href="../css/estilo.css">
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
// 1. Inicia a sessão e checa o login com segurança extrema
session_start();
if (empty($_SESSION['logado']) || $_SESSION['logado'] !== true) {
    header("Location: login.php");
    exit; // OBRIGATÓRIO para impedir que invasores leiam o resto da página
}

// 2. Geração do Token de segurança (CSRF) para os botões de ação
if (empty($_SESSION['form_token'])) {
    $_SESSION['form_token'] = bin2hex(random_bytes(32));
}
$token = $_SESSION['form_token'];

// 3. Conexão PDO (Segura e padronizada)
require_once '../db/conexao.php'; // Ajuste o caminho se necessário

// Como conversamos antes, os nomes corretos no seu banco são post, fonte, texto e motivo
$sql = "SELECT id, texto, post, fonte, motivo, data FROM report ORDER BY id DESC LIMIT 1";
$report = null;

try {
    $stmt = $pdo->query($sql);
    if ($stmt) {
        $report = $stmt->fetch(PDO::FETCH_ASSOC);
    }
} catch (PDOException $e) {
    error_log("Erro na consulta de report: " . $e->getMessage());
}

$pdo = null; // Fecha a conexão
?>
    
    <style>
        /* Estilos base (reaproveitados do seu padrão) */
        body {
            background-color: #f4f4f4;
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }

        .container-form {
            display: flex;
            justify-content: center;
            align-items: center;
            margin-bottom: 40px;
            padding: 20px;
        }

        .formulario-box {
            background-color: #ffffff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.15);
            width: 100%;
            max-width: 500px;
            box-sizing: border-box;
        }

        /* Estilo dos dados do report */
        .info-report {
            display: flex;
            flex-direction: column;
            gap: 15px;
            margin-bottom: 25px;
        }

        .info-report label {
            font-weight: bold;
            color: #333;
            margin-bottom: -10px; /* Aproxima a label do texto */
        }
        
        .info-report p, .info-report a {
            margin: 0;
            padding: 10px;
            background-color: #f8f9fa;
            border-radius: 5px;
            border: 1px solid #ddd;
            word-wrap: break-word; /* Impede que links gigantes quebrem a tela */
        }

        /* Botões de Ação */
        .acoes-report {
            display: flex;
            gap: 10px;
            margin-top: 20px;
        }
        
        .acoes-report form {
            flex: 1; /* Faz os dois botões terem o mesmo tamanho */
        }

        .btn {
            width: 100%;
            color: white;
            padding: 12px 20px;
            border: none;
            border-radius: 5px;
            font-size: 1.1em;
            cursor: pointer;
            transition: transform 0.2s ease, opacity 0.3s;
        }
        .btn:hover { transform: translateY(-2px); opacity: 0.9; }
        .btn:active { transform: translateY(0); }
        
        .btn-verde { background-color: #28a745; }
        .btn-vermelho { background-color: #dc3545; }
    </style>
</head>
<body>

    <h1 style="text-align: center; color: #333; margin-top: 30px;">Painel de Reports</h1>
    
    <div class="container-form">
        <div class="formulario-box">
            
            <?php if ($report): ?>
                
                <h3 style="margin-top: 0; color: #666; border-bottom: 2px solid #eee; padding-bottom: 10px;">
                    Protocolo: #<?= htmlspecialchars($report['id']) ?> <br>
                    <small style="font-size: 0.7em; font-weight: normal;">Data: <?= htmlspecialchars($report['data']) ?></small>
                </h3>

                <div class="info-report">
                    <label>Motivo:</label>
                    <p><strong><?= htmlspecialchars($report['motivo']) ?></strong></p>

                    <label>Texto da Denúncia:</label>
                    <p><?= nl2br(htmlspecialchars($report['texto'])) ?></p>

                    <label>Link do Post (No seu site):</label>
                    <a href="<?= htmlspecialchars($report['post']) ?>" target="_blank"><?= htmlspecialchars($report['post']) ?></a>

                    <label>Link Externo (Fonte do usuário):</label>
                    <a href="<?= htmlspecialchars($report['fonte']) ?>" target="_blank"><?= htmlspecialchars($report['fonte']) ?></a>
                </div>

                <?php 
                $url_parts = parse_url($report['post']);
                parse_str($url_parts['query'] ?? '', $query_params);
                $id_noticia = $query_params['token'] ?? 0; 
                ?>

                <div class="acoes-report">
                    <!-- Form 1: Manter (Ignorar Denúncia) -->
                    <form action="denuncia_envio.php" method="post">
                        <input type="hidden" name="token" value="<?= htmlspecialchars($token) ?>">
                        <input type="hidden" name="id" value="<?= htmlspecialchars($report['id']) ?>">
                        <input type="hidden" name="tabela" value="report">
                        <button type="submit" class="btn btn-verde">Manter Post</button>
                    </form>

                    <!-- Form 2: Excluir Publicação -->
                    <form action="denuncia_envio.php" method="post">
                        <input type="hidden" name="token" value="<?= htmlspecialchars($token) ?>">
                        <input type="hidden" name="id" value="<?= htmlspecialchars($id_noticia) ?>">
                        <input type="hidden" name="tabela" value="dados">
                        <button type="submit" class="btn btn-vermelho" onclick="return confirm('Tem certeza que deseja apagar esta notícia permanentemente?');">Excluir Post</button>
                    </form>
                </div>

            <?php else: ?>
                <div style="text-align: center; padding: 30px 0;">
                    <h3 style="color: #28a745;">Tudo limpo! ✅</h3>
                    <p>Nenhuma denúncia pendente de análise.</p>
                </div>
            <?php endif; ?>

        </div>
    </div>

    <footer class="rodape">
        <p>&copy; <?= date('Y') ?> Portal de Notícias. Todos os direitos reservados.</p>
    </footer>

</body>
</html>