-- schema de banco de dados
CREATE DATABASE IF NOT EXISTS lux_bcd; 

USE lux_bcd; 

CREATE TABLE IF NOT EXISTS usuario ( 
	id_usuario INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    nascimento DATE NOT NULL,
    cpf VARCHAR(11) NOT NULL,
    email VARCHAR(100) NOT NULL,
    telefone VARCHAR(11) NULL,
    senha VARCHAR(11) NOT NULL
);  

USE lux_bcd; 

CREATE TABLE IF NOT EXISTS filme (
    id_filme INT AUTO_INCREMENT PRIMARY KEY,
    nome_filme VARCHAR(100) NOT NULL,
    sinopse VARCHAR(250) NOT NULL, 
    imagem_capa VARCHAR(200) NOT NULL,
    data_filme DATE NOT NULL,
    duracao_filme INT NOT NULL,
    class_filme VARCHAR(10) NOT NULL,
    gen_filme VARCHAR(50) NOT NULL,
    exibicao_filme VARCHAR(10) NOT NULL,
    tipo_conteudo ENUM('Filme', 'Série')NOT NULL 
); 

USE lux_bcd; 

CREATE TABLE IF NOT EXISTS historico_visualizacao(
id_visualizacao INT AUTO_INCREMENT PRIMARY KEY,
data_visualizacao DATE NOT NULL, 
id_usuario INT NOT NULL, 
FOREIGN KEY (id_usuario) REFERENCES usuario (id_usuario),
id_filme INT NOT NULL,
FOREIGN KEY (id_filme) REFERENCES filme (id_filme)
); 

USE lux_bcd; 

CREATE TABLE IF NOT EXISTS bomboniere (
    id_produto INT AUTO_INCREMENT PRIMARY KEY,
    nome_produto VARCHAR(50) NOT NULL,
    valor_produto DECIMAL(10,2) NOT NULL,
    descricao_produto VARCHAR(250) NOT NULL,
    categoria_produto VARCHAR(50) NOT NULL
); 

USE lux_bcd;

CREATE TABLE IF NOT EXISTS plano (
    id_plano INT AUTO_INCREMENT PRIMARY KEY,
    descricao_plano VARCHAR(120) NOT NULL,
    nome_plano VARCHAR(50) NOT NULL,
    plano_ativo VARCHAR(30) NOT NULL,
    valor_mensal DECIMAL(10,2) NOT NULL,
    valor_anual DECIMAL(10,2) NOT NULL
);

USE lux_bcd;

CREATE TABLE IF NOT EXISTS cinema (
    id_cinema INT AUTO_INCREMENT PRIMARY KEY,
    endereco_cinema VARCHAR(250) NOT NULL
);


USE lux_bcd;

CREATE TABLE IF NOT EXISTS sala (
    id_sala INT AUTO_INCREMENT PRIMARY KEY,
    numero_sala INT NOT NULL,
    capacidade INT NOT NULL,
    id_cinema INT NOT NULL,
    FOREIGN KEY (id_cinema) REFERENCES cinema(id_cinema)
);

USE lux_bcd;

CREATE TABLE IF NOT EXISTS  sessao (
    id_sessao INT AUTO_INCREMENT PRIMARY KEY,
    dia_sessao DATE NOT NULL,
    hora_sessao TIME NOT NULL,
    id_filme INT NOT NULL,
    FOREIGN KEY (id_filme) REFERENCES filme(id_filme),
    id_sala INT NOT NULL,
    FOREIGN KEY (id_sala) REFERENCES sala(id_sala)
);

USE lux_bcd;

CREATE TABLE IF NOT EXISTS reserva (
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

USE lux_bcd;

CREATE TABLE IF NOT EXISTS assinatura (
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

USE lux_bcd;

CREATE TABLE IF NOT EXISTS venda (
    id_venda INT AUTO_INCREMENT PRIMARY KEY,
    data_pedido DATE,
    valor_pedido DECIMAL(10,2) NOT NULL,
    id_usuario INT NOT NULL,
    FOREIGN KEY (id_usuario) REFERENCES usuario(id_usuario),
    id_reserva INT NULL,
    FOREIGN KEY (id_reserva) REFERENCES reserva(id_reserva),
    id_produto INT NULL,
    FOREIGN KEY (id_produto) REFERENCES bomboniere(id_produto),
    id_endereco INT NOT NULL, 
    FOREIGN KEY (id_endereco) REFERENCES endereco_entrega(id_endereco)
);

USE lux_bcd;
CREATE TABLE IF NOT EXISTS endereco_entrega (
    id_endereco INT AUTO_INCREMENT PRIMARY KEY,
    id_usuario INT NOT NULL,
    logradouro VARCHAR(150) NOT NULL,
    numero VARCHAR(20) NOT NULL,
    complemento VARCHAR(50) NULL,
    bairro VARCHAR(60) NOT NULL,
    cidade VARCHAR(60) NOT NULL,
    estado CHAR(2) NOT NULL,
    cep VARCHAR(8) NOT NULL,
    FOREIGN KEY (id_usuario) REFERENCES usuario(id_usuario)
);

USE lux_bcd;

CREATE TABLE IF NOT EXISTS  pagamento(
	id_pagamento INT AUTO_INCREMENT PRIMARY KEY,
    forma_pagamento VARCHAR(30) NOT NULL, 
    status_pagamento VARCHAR(20) NOT NULL, 
    data_pagamento DATE NOT NULL,
    id_venda INT NOT NULL, 
    FOREIGN KEY (id_venda) REFERENCES venda(id_venda)
    );

USE lux_bcd;

CREATE TABLE IF NOT EXISTS ingressos (
    id_ingresso INT AUTO_INCREMENT PRIMARY KEY,
    assento VARCHAR(10) NOT NULL,
    valor_ingresso DECIMAL(10,2) NOT NULL,
    id_venda INT NOT NULL,
    FOREIGN KEY (id_venda) REFERENCES venda(id_venda),
    id_sessao INT NOT NULL,
    FOREIGN KEY (id_sessao) REFERENCES sessao(id_sessao),
    UNIQUE (id_sessao, assento)
);

USE lux_bcd;

CREATE TABLE IF NOT EXISTS beneficios (
    id_beneficio INT AUTO_INCREMENT PRIMARY KEY,
    nome_beneficio VARCHAR(50) NOT NULL,
    pontos_beneficio INT NOT NULL,
    descricao_beneficio VARCHAR(120) NOT NULL,
    beneficio_ativo VARCHAR(10) NOT NULL
);


USE lux_bcd;

CREATE TABLE IF NOT EXISTS pontos_usuario (
    qntd_pontos INT NOT NULL,
    id_usuario INT NOT NULL,
    FOREIGN KEY (id_usuario) REFERENCES usuario(id_usuario)
);

USE lux_bcd;

CREATE TABLE IF NOT EXISTS resgate_beneficio (
    id_resgate INT AUTO_INCREMENT PRIMARY KEY,
    pontos_utilizados INT NOT NULL,
    data_resgate DATE NULL,
    id_usuario INT NOT NULL,
    FOREIGN KEY (id_usuario) REFERENCES usuario(id_usuario),
    id_beneficio INT NOT NULL,
    FOREIGN KEY (id_beneficio) REFERENCES beneficios(id_beneficio)
);

CREATE TABLE IF NOT EXISTS cupom (
    id_cupom INT AUTO_INCREMENT PRIMARY KEY,
    codigo VARCHAR(30) NOT NULL UNIQUE,
    tipo_desconto ENUM('Porcentagem', 'Fixo') NOT NULL,
    valor_desconto DECIMAL(10,2) NOT NULL,
    valor_minimo_pedido DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    data_validade DATE NOT NULL,
    cupom_ativo CHAR(3) NOT NULL DEFAULT 'Sim'
);

USE lux_bcd;

CREATE TABLE IF NOT EXISTS avaliacao (
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

USE lux_bcd;

CREATE TABLE IF NOT EXISTS lista (
    id_lista INT AUTO_INCREMENT PRIMARY KEY,
    nome_lista VARCHAR(100) NOT NULL,
    descricao_lista VARCHAR(100) NULL,
    data_criacao DATE NOT NULL,
    id_usuario INT NOT NULL,
    FOREIGN KEY (id_usuario) REFERENCES usuario(id_usuario)
);

USE lux_bcd;

CREATE TABLE IF NOT EXISTS filme_lista (
    id_filme INT NOT NULL,
    FOREIGN KEY (id_filme) REFERENCES filme(id_filme),
    id_lista INT NOT NULL,
    FOREIGN KEY (id_lista) REFERENCES lista(id_lista)
);





 