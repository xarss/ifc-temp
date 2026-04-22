-- Criação do banco de dados
CREATE DATABASE IF NOT EXISTS pet_nao_secreto;
USE pet_nao_secreto;

-- Tabela de animais
CREATE TABLE IF NOT EXISTS animais (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    imagem_url VARCHAR(255) NOT NULL,
    historia TEXT NOT NULL,
    whatsapp VARCHAR(20) NOT NULL,
    data_cadastro TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Inserir alguns dados de exemplo
INSERT INTO animais (nome, imagem_url, historia, whatsapp) VALUES
('Rex', 'fotos_resgatados_natal/rex.jpg', 'Rex foi encontrado abandonado em um parque. Estava muito magro e assustado, mas hoje está se recuperando bem e pronto para um lar amoroso.', '5511999999999'),
('Luna', 'fotos_resgatados_natal/luna.jpg', 'Luna foi resgatada de um abrigo superlotado. Ela é muito carinhosa e adora brincar com bolinhas.', '5511999999999'),
('Thor', 'fotos_resgatados_natal/thor.jpg', 'Thor foi vítima de maus-tratos, mas hoje está se recuperando e mostrando todo o seu amor por humanos.', '5511999999999');
