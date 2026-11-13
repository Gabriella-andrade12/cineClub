CREATE DATABASE lux_dados; 

USE lux_dados;
-- teste
-- USUÁRIO

CREATE TABLE usuario (
    id_usuario INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    nascimento DATE,
    cpf VARCHAR(11) NOT NULL,
    email VARCHAR(100) NOT NULL,
    telefone VARCHAR(11) NULL,
    senha_hash VARCHAR(70) NOT NULL
);
-- Não inserir senhas em texto puro nesta coluna.
INSERT INTO usuario (nome, nascimento, cpf, email, telefone, senha_hash)
VALUES
    ('Alessandra Silva', '2003-09-27', '12345678901', 'alessandrasilva@gmail.com', '11900000000',  'PENDENTE_HASH_ALESSANDRA'),
    ('Bernardo Rodrigues', '1997-09-01', '12234567890', 'bernard0ro@gmail.com', '12911110000', 'PENDENTE_HASH_BERNARDO'),
    ('Manuel Castelari', '1995-09-10', '12334567891', 'manuelcas@gmail.com', '80100001000', 'PENDENTE_HASH_MANUEL');

-- FILME

CREATE TABLE filme (
    id_filme INT AUTO_INCREMENT PRIMARY KEY,
    nome_filme VARCHAR(100) NOT NULL,
    data_filme DATE NOT NULL,
    duracao_filme INT NOT NULL,
    class_filme VARCHAR(10) NOT NULL,
    gen_filme VARCHAR(50) NOT NULL,
    exibicao_filme VARCHAR(10) NOT NULL
);

INSERT INTO filme(nome_filme, data_filme, duracao_filme, class_filme, gen_filme, exibicao_filme)
VALUES
    ('O Exterminador do Futuro', '1984-10-26', 107,  '16', 'ação', 'Cinema'),
    ('Como Treinar o Seu Dragão - Live Action', '2025-06-05', 98, 'livre', 'aventura', 'Cinema'),
    ('Um Maluco no Golfe 2', '2025-07-25', 114, '14', 'comédia', 'Streaming'),
    ('Extraordinário', '2017-11-07', 113, '10 anos', 'drama', 'Cinema'),
    ('A Fantástica Fábrica de Chocolate', '2005-07-22', 115, 'livre', 'fantasia', 'Cinema'),
    ('Interestelar', '2014-11-06', 169, '10', 'ficção científica', 'Cinema'),
    ('O Último Nascer do Sol', '2026-08-26', 106, '16', 'romance', 'Cinema'),
    ('A Empregada', '2025-12-19', 131, '16', 'suspense', 'Cinema'),
    ('Invocação do Mal', '2013-07-19', 112, '16', 'terror', 'Cinema');

-- HISTÓRICO DE VISUALIZAÇÃO

CREATE TABLE historico_visualizacao(
id_visualizacao INT AUTO_INCREMENT PRIMARY KEY,
data_visualizacao DATE NOT NULL, 
id_usuario INT NOT NULL, 
FOREIGN KEY (id_usuario) REFERENCES usuario (id_usuario),
id_filme INT NOT NULL,
FOREIGN KEY (id_filme) REFERENCES filme (id_filme)
);

INSERT INTO historico_visualizacao (data_visualizacao, id_usuario, id_filme)
VALUES 
('2026-09-16', 2, 5),
('2026-04-15', 3, 7),
('2026-07-28', 1, 9);

-- BOMBONIERE

CREATE TABLE bomboniere (
    id_produto INT AUTO_INCREMENT PRIMARY KEY,
    nome_produto VARCHAR(50) NOT NULL,
    valor_produto DECIMAL(10,2) NOT NULL,
    descricao_produto VARCHAR(250) NOT NULL,
    categoria_produto VARCHAR(50) NOT NULL
);

INSERT INTO bomboniere(nome_produto, valor_produto, descricao_produto, categoria_produto)
VALUES
    ('Combo Solo', 23.90, 'Pipoca salgada tamanho M + 1 bebida lata + 1 chocolate', 'Individual'),
    ('Quarteto Fantástico', 69.90, 'Caixa com 4 porções estilizadas (2 salgadas + 2 doces) + 4 bebidas lata + 2 doces', 'Família'),
    ('Romance Misto', 38.90, 'Balde meio a meio tamanho G (metade salgada e metade doce) + 2 bebidas lata + 1 chocolate', 'Casal'),
    ('Doce Magia', 19.90, 'Pipoca doce colorida ou de Leite ninho tamanho P + 
1 achocolatado/suco + 1 pacote de fini', 'Infantil'),
    ('Vinho a Dois', 98.90, 'Pipoca artesanal de azeite de trufas e parmesão tamanho G + 1 garrafa de vinho de 750ml + 1 tábua de frios', 'Especiais'),
    ('Combo Nachos Supremos', 34.90, 'Porção generosa de nachos crocantes com molho de queijo cheddar quente, guacamole e chilli com carne + 1 bebida artesanal ou lata', 'Premium');

-- PLANO

CREATE TABLE plano (
    id_plano INT AUTO_INCREMENT PRIMARY KEY,
    descricao_plano VARCHAR(120) NOT NULL,
    nome_plano VARCHAR(50) NOT NULL,
    plano_ativo VARCHAR(30) NOT NULL,
    valor_mensal DECIMAL(10,2) NOT NULL,
    valor_anual DECIMAL(10,2) NOT NULL
);

INSERT INTO plano(nome_plano, descricao_plano, plano_ativo, valor_mensal, valor_anual)
VALUES
    ('Lux Play', 'Assista ao catálogo de filmes disponíveis em casa e receba 5% de desconto em ingressos comprados pela LUX', 'Sim', 19.90, 199.00),
    ('Lux Plus', 'Aproveite o catálogo de streaming, 10% de desconto em ingressos e 5% de desconto na bomboniere', 'Sim', 29.90, 299.00),
    ('Lux Prime', 'Tenha acesso ao catálogo de streaming, 15% de desconto em ingressos, 10% de desconto na bomboniere', 'Sim', 39.90, 399.00);

-- CINEMA

CREATE TABLE cinema (
    id_cinema INT AUTO_INCREMENT PRIMARY KEY,
    endereco_cinema VARCHAR(250) NOT NULL
);

INSERT INTO cinema (endereco_cinema)
VALUES
    ('Rua Beira Mar N 100, SAO CAETANO DO SUL - SP'),
    ('Avenida Europa N 207, BARRA DA TIJUCA - RJ'),
    ('Rua Atlântica N 340, SAO BERNARDO DO CAMPO - SP');

CREATE TABLE sala (
    id_sala INT AUTO_INCREMENT PRIMARY KEY,
    numero_sala INT NOT NULL,
    capacidade INT NOT NULL,
    id_cinema INT NOT NULL,
    FOREIGN KEY (id_cinema) REFERENCES cinema(id_cinema)
);

INSERT INTO sala (numero_sala, capacidade, id_cinema)
VALUES
    (1, 70, 1),
    (2, 70, 1),
    (3, 55, 1),
    (1, 80, 2),
    (2, 75, 2),
    (1, 85, 3),
    (2, 70, 3);

-- SESSÃO

CREATE TABLE sessao (
    id_sessao INT AUTO_INCREMENT PRIMARY KEY,
    dia_sessao DATE NOT NULL,
    hora_sessao TIME NOT NULL,
    id_filme INT NOT NULL,
    FOREIGN KEY (id_filme) REFERENCES filme(id_filme),
    id_sala INT NOT NULL,
    FOREIGN KEY (id_sala) REFERENCES sala(id_sala)
);

INSERT INTO sessao (dia_sessao, hora_sessao, id_filme, id_sala)
VALUES
    ('2026-11-12', '18:00:00', 2, 2),
    ('2026-11-11', '15:00:00', 1, 2),
    ('2026-11-11', '20:00:00', 4, 4),
    ('2026-11-29', '14:00:00', 3, 3),
    ('2026-11-18', '17:00:00', 6, 3);

-- RESERVA

CREATE TABLE reserva (
    id_reserva INT AUTO_INCREMENT PRIMARY KEY,
    dia_reserva DATE NOT NULL,
    hora_reserva TIME NOT NULL,
    status_reserva VARCHAR(50) NOT NULL,
    id_usuario INT NOT NULL,
    FOREIGN KEY (id_usuario) REFERENCES usuario(id_usuario),
    id_sala INT NOT NULL,
    FOREIGN KEY (id_sala) REFERENCES sala(id_sala),
    id_filme INT NOT NULL,
    FOREIGN KEY (id_filme) REFERENCES filme(id_filme)
);

INSERT INTO reserva(dia_reserva, hora_reserva, status_reserva, id_usuario, id_sala, id_filme)
VALUES
    ('2026-07-07', '14:10:00', 'Concluída', 2, 1, 3),
    ('2026-02-20', '10:15:00', 'Concluída', 1, 4, 3),
    ('2026-07-05', '15:30:00', 'Cancelada', 2, 2, 3),
    ('2026-11-21', '18:00:00', 'Agendada', 3, 4, 6),
    ('2026-12-11', '20:45:00', 'Agendada', 1, 2, 7);

-- ASSINATURA

CREATE TABLE assinatura (
    id_assinatura INT AUTO_INCREMENT PRIMARY KEY,
    periodo VARCHAR(10) NOT NULL,
    data_inicio DATE NOT NULL,
    data_fim DATE NOT NULL,
    status_assinatura VARCHAR(20) NOT NULL,
    id_usuario INT NOT NULL,
    FOREIGN KEY (id_usuario) REFERENCES usuario(id_usuario),
    id_plano INT NOT NULL,
    FOREIGN KEY (id_plano) REFERENCES plano(id_plano)
);

INSERT INTO assinatura(periodo, data_inicio, data_fim, status_assinatura, id_usuario, id_plano)
VALUES
    ('Mensal', '2026-07-01', '2026-08-01', 'Expirado', 1, 2),
    ('Anual', '2026-06-16', '2027-06-16', 'Ativo', 2, 3),
    ('Mensal', '2026-03-19', '2026-04-19', 'Cancelado', 3, 1);

-- VENDA
-- valor_pedido considera, nestes exemplos, o produto e todos os
-- ingressos vinculados à venda.

CREATE TABLE venda (
    id_venda INT AUTO_INCREMENT PRIMARY KEY,
    data_pedido DATE,
    valor_pedido DECIMAL(10,2) NOT NULL,
    id_usuario INT NOT NULL,
    FOREIGN KEY (id_usuario) REFERENCES usuario(id_usuario),
    id_reserva INT NULL,
    FOREIGN KEY (id_reserva) REFERENCES reserva(id_reserva),
    id_produto INT NULL,
    FOREIGN KEY (id_produto) REFERENCES bomboniere(id_produto)
);

INSERT INTO venda(data_pedido, valor_pedido, id_usuario, id_reserva, id_produto)
VALUES
('2026-11-09', 60.00, 1, 5, 2),
('2026-09-10', 40.00, 3, 4, 3 ),
('2026-01-08', 20.00, 2, null, 6);

CREATE TABLE pagamento(
	id_pagamento INT AUTO_INCREMENT PRIMARY KEY,
    forma_pagamento VARCHAR(30) NOT NULL, 
    status_pagamento VARCHAR(20) NOT NULL, 
    data_pagamento DATE NOT NULL,
    id_venda INT NOT NULL, 
    FOREIGN KEY (id_venda) REFERENCES venda(id_venda)
    );

INSERT INTO pagamento(forma_pagamento, status_pagamento, data_pagamento, id_venda)
VALUES
('Pix', 'Concluído', '2026-11-09', 1),
('Cartão', 'Concluído', '2026-09-10', 2), 
('Pix', 'Concluído', '2026-01-08', 3);

CREATE TABLE ingressos (
    id_ingresso INT AUTO_INCREMENT PRIMARY KEY,
    assento VARCHAR(10) NOT NULL,
    valor_ingresso DECIMAL(10,2) NOT NULL,
    id_venda INT NOT NULL,
    FOREIGN KEY (id_venda) REFERENCES venda(id_venda),
    id_sessao INT NOT NULL,
    FOREIGN KEY (id_sessao) REFERENCES sessao(id_sessao),
    UNIQUE (id_sessao, assento)
);

INSERT INTO ingressos (assento, valor_ingresso, id_venda, id_sessao)
VALUES
    -- N5 e N6: mesma sessão e mesma venda.
    ('N5', 30.00, 1, 2),
    ('N6', 30.00, 1, 2),
    ('C4', 20.00, 3, 2),
    ('C5', 20.00, 2, 3),
    ('F7', 20.00, 1, 1);

-- BENEFÍCIOS E PONTOS

CREATE TABLE beneficios (
    id_beneficio INT AUTO_INCREMENT PRIMARY KEY,
    nome_beneficio VARCHAR(50) NOT NULL,
    pontos_beneficio INT NOT NULL,
    descricao_beneficio VARCHAR(120) NOT NULL,
    beneficio_ativo VARCHAR(10) NOT NULL
);

INSERT INTO beneficios (nome_beneficio, pontos_beneficio, descricao_beneficio, beneficio_ativo)
VALUES
    ('Pipoca pequena Grátis', 50, 'Retirada na bomboniere do cinema', 'SIM'),
    ('Refrigerante Grátis (lata)', 70, 'Refrigerante de lata, retirar no cinema', 'SIM'),
    ('Desconto no ingresso', 90, 'Ganhe R$10 de desconto no seu próximo ingresso', 'SIM');

CREATE TABLE pontos_usuario (
    qntd_pontos INT NOT NULL,
    id_usuario INT NOT NULL,
    FOREIGN KEY (id_usuario) REFERENCES usuario(id_usuario)
);

INSERT INTO pontos_usuario (qntd_pontos, id_usuario)
VALUES
    (1700, 1),
    (70, 2),
    (180, 3);

CREATE TABLE resgate_beneficio (
    id_resgate INT AUTO_INCREMENT PRIMARY KEY,
    pontos_utilizados INT NOT NULL,
    data_resgate DATE NULL,
    id_usuario INT NOT NULL,
    FOREIGN KEY (id_usuario) REFERENCES usuario(id_usuario),
    id_beneficio INT NOT NULL,
    FOREIGN KEY (id_beneficio) REFERENCES beneficios(id_beneficio)
);

INSERT INTO resgate_beneficio(pontos_utilizados, data_resgate, id_usuario, id_beneficio)
VALUES
    (50, '2026-08-07', 1, 1),
    (70, '2026-05-08', 2, 2),
    (90, '2026-01-22', 3, 3);

-- AVALIAÇÃO

CREATE TABLE avaliacao (
    id_avaliacao INT AUTO_INCREMENT PRIMARY KEY,
    nota_avl INT NOT NULL,
    cometario_avl VARCHAR(250) NULL,
    id_usuario INT NOT NULL,
    FOREIGN KEY (id_usuario) REFERENCES usuario(id_usuario),
    id_produto INT NULL,
    FOREIGN KEY (id_produto) REFERENCES bomboniere(id_produto),
    id_filme INT NULL,
    FOREIGN KEY (id_filme) REFERENCES filme(id_filme)
);

INSERT INTO avaliacao(nota_avl, cometario_avl, id_usuario, id_produto, id_filme)
VALUES
    (8,'Gostei do combo! A pipoca veio quentinha e a porção foi suficiente para acompanhar o filme. Só achei que o refrigerante poderia ser um pouco maior.', 1, 3, NULL),
    (9, 'A história me prendeu do começo ao fim. As cenas no espaço são impressionantes e a trilha sonora deixa tudo ainda mais emocionante.', 2, NULL, 6);

-- LISTA

CREATE TABLE lista (
    id_lista INT AUTO_INCREMENT PRIMARY KEY,
    nome_lista VARCHAR(100) NOT NULL,
    descricao_lista VARCHAR(100) NULL,
    data_criacao DATE NOT NULL,
    id_usuario INT NOT NULL,
    FOREIGN KEY (id_usuario) REFERENCES usuario(id_usuario)
);

INSERT INTO lista
    (nome_lista, descricao_lista, data_criacao, id_usuario)
VALUES
    ('Assistir mais tarde', null, '2026-08-20', 2),
    ('Assistir com a Lu', 'NÃO ASSISTIR SEM A LUNA, SE NÃO ELA FICA BRAVA', '2026-02-27', 1),
    ('Para não pensar', 'Momentos difíceis da vida onde só queremos assistir sem precisar raciocinar', '2026-04-15', 3);

CREATE TABLE filme_lista (
    id_filme INT NOT NULL,
    FOREIGN KEY (id_filme) REFERENCES filme(id_filme),
    id_lista INT NOT NULL,
    FOREIGN KEY (id_lista) REFERENCES lista(id_lista)
);

INSERT INTO filme_lista (id_filme, id_lista)
VALUES
    (2, 1),
    (3, 2),
    (5, 3);
