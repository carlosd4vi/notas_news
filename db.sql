-- ========================================================
-- Banco de Dados: Notas News
-- ========================================================

-- ========================================================
-- Tabela: admin
-- Objetivo: Acesso ao portal login.
-- ========================================================
CREATE TABLE IF NOT EXISTS admin (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario VARCHAR(50) NOT NULL UNIQUE,
    senha VARCHAR(255) NOT NULL,
    data_criacao DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ========================================================
-- Tabela: dados_noticia
-- Objetivo: Armazenar as postagens
-- ========================================================
CREATE TABLE IF NOT EXISTS dados_noticia (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titulo VARCHAR(150) NOT NULL,
    descricao VARCHAR(500) NOT NULL,
    link1 VARCHAR(255) NOT NULL,
    link2 VARCHAR(255) DEFAULT NULL,
    data DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ========================================================
-- Tabela: report
-- Objetivo: Armazenar denúncias feitas pelos usuários
-- ========================================================
CREATE TABLE IF NOT EXISTS report (
    id INT AUTO_INCREMENT PRIMARY KEY,
    post VARCHAR(255) NOT NULL,
    motivo VARCHAR(100) NOT NULL,
    fonte VARCHAR(255) NOT NULL,
    texto VARCHAR(255) NOT NULL,
    data_criada DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;