-- seeds 
USE lux_bcd;

-- 1. USUARIO (senha de todos: 123456789)
INSERT INTO usuario (nome, nascimento, cpf, email, telefone, senha) VALUES
('Alessandra Silva',  '2003-09-27', '12345678901', 'alessandrasilva@gmail.com', '11900000000', '123456789'),
('Bernardo Rodrigues','1997-09-01', '12234567890', 'bernard0ro@gmail.com',       '12911110000', '123456789'),
('Manuel Castelari',  '1995-09-10', '12334567891', 'manuelcas@gmail.com',        '80100001000', '123456789'),
('Camila Torres',     '1999-03-14', '45678912305', 'camilatorres@gmail.com',     '11988887777', '123456789'),
('Diego Almeida',     '1988-12-02', '32165498700', 'diegoalmeida@gmail.com',     '21977776666', '123456789'),
('Fernanda Lima',     '2001-06-21', '78912345611', 'fernandalima@gmail.com',     NULL,          '123456789'),
('Gabriel Souza',     '1993-01-30', '14725836900', 'gabrielsouza@gmail.com',     '11966665555', '123456789'),
('Helena Martins',    '2005-11-08', '96385274122', 'helenamartins@gmail.com',    '13955554444', '123456789'),
('Igor Pereira',      '1990-04-17', '85274196333', 'igorpereira@gmail.com',      '11944443333', '123456789'),
('Julia Ribeiro',     '1985-08-25', '25836914744', 'juliaribeiro@gmail.com',     NULL,          '123456789');

-- 2. FILME
INSERT INTO filme (nome_filme, data_filme, duracao_filme, class_filme, gen_filme, exibicao_filme, tipo_conteudo) VALUES
('O Exterminador do Futuro',              '1984-10-26', 107, '16',      'ação',              'Cinema', 'Filme'),
('Como Treinar o Seu Dragão - Live Action','2025-06-05',  98, 'livre',   'aventura',          'Cinema', 'Filme'),
('Um Maluco no Golfe 2',                  '2025-07-25', 114, '14',      'comédia',           'Streaming', 'Filme'),
('Extraordinário',                        '2017-11-07', 113, '10 anos', 'drama',             'Cinema', 'Filme'),
('A Fantástica Fábrica de Chocolate',     '2005-07-22', 115, 'livre',   'fantasia',          'Cinema', 'Filme'),
('Interestelar',                          '2014-11-06', 169, '10',      'ficção científica', 'Cinema', 'Filme'),
('O Último Nascer do Sol',                '2026-08-26', 106, '16',      'romance',           'Cinema', 'Filme'),
('A Empregada',                           '2025-12-19', 131, '16',      'suspense',          'Cinema', 'Filme'),
('Invocação do Mal',                      '2013-07-19', 112, '16',      'terror',            'Cinema', 'Filme'),
('Divertida Mente 2',                     '2024-06-20',  96, 'livre',   'animação',          'Streaming', 'Filme'),
('Duna: Parte Dois',                      '2024-02-29', 166, '14',      'ficção científica', 'Cinema', 'Filme'),
('O Auto da Compadecida 2',               '2024-12-25', 112, '12',      'comédia',           'Streaming', 'Filme'),
('O Exterminador do Futuro','1984-10-26',107,'16','ação','Cinema','Filme'),
('Como Treinar o Seu Dragão - Live Action','2025-06-05',98,'livre','aventura','Cinema','Filme'),
('Um Maluco no Golfe 2','2025-07-25',114,'14','comédia','Streaming','Filme'),
('Extraordinário','2017-11-07',113,'10 anos','drama','Cinema','Filme'),
('A Fantástica Fábrica de Chocolate','2005-07-22',115,'livre','fantasia','Cinema','Filme'),
('Interestelar','2014-11-06',169,'10','ficção científica','Cinema','Filme'),
('O Último Nascer do Sol','2026-08-26',106,'16','romance','Cinema','Filme'),
('A Empregada','2025-12-19',131,'16','suspense','Cinema','Filme'),
('Invocação do Mal','2013-07-19',112,'16','terror','Cinema','Filme'),
('Divertida Mente 2','2024-06-20',96,'livre','animação','Streaming','Filme'),
('Duna: Parte Dois','2024-02-29',166,'14','ficção científica','Cinema','Filme'),
('O Auto da Compadecida 2','2024-12-25',112,'12','comédia','Streaming','Filme'),

-- ===== CINEMA =====
-- Ação
('Bailarina','2025-06-05',125,'16','ação','Cinema','Filme'),
('Mad Max: Estrada da Fúria','2015-05-14',120,'16','ação','Cinema','Filme'),
('Extraction 2','2023-06-16',123,'16','ação','Cinema','Filme'),
('Homem-Aranha: Um Novo Dia','2026-07-31',130,'12','ação','Cinema','Filme'),
('Na Zona Cinzenta','2025-08-01',110,'16','ação','Cinema','Filme'),
('Velozes e Furiosos 9','2021-06-24',143,'12','ação','Cinema','Filme'),
('Top Gun','1986-05-16',110,'12','ação','Cinema','Filme'),
-- Aventura
('Avatar: O Caminho da Água','2022-12-16',192,'12','aventura','Cinema','Filme'),
('A Odisseia','2026-07-17',150,'14','aventura','Cinema','Filme'),
('Jumanji','2017-12-21',119,'12','aventura','Cinema','Filme'),
('Jungle Cruise','2021-07-30',127,'12','aventura','Cinema','Filme'),
('Piratas do Caribe','2003-07-09',143,'12','aventura','Cinema','Filme'),
-- Comédia
('Debi & Loide','1994-12-16',107,'12','comédia','Cinema','Filme'),
('Corre que a Polícia Vem Aí','2025-08-01',85,'14','comédia','Cinema','Filme'),
('Mickey 17','2025-03-07',137,'14','comédia','Cinema','Filme'),
('Minha Melhor Amiga','2026-02-13',100,'14','comédia','Cinema','Filme'),
('Norbit','2007-02-09',102,'14','comédia','Cinema','Filme'),
('O Durão','2025-06-13',105,'14','comédia','Cinema','Filme'),
-- Drama
('Central do Brasil','1998-04-03',110,'14','drama','Cinema','Filme'),
('O Poderoso Chefão','1972-03-24',175,'18','drama','Cinema','Filme'),
('Um Sonho de Liberdade','1994-09-23',142,'16','drama','Cinema','Filme'),
('Hamnet','2025-11-26',125,'14','drama','Cinema','Filme'),
('Intocáveis','2011-11-02',112,'14','drama','Cinema','Filme'),
('Sempre ao Seu Lado','2009-08-13',93,'livre','drama','Cinema','Filme'),
('Clube da Luta','1999-10-15',139,'18','drama','Cinema','Filme'),
-- Fantasia
('A Maravilhosa Árvore Encantada','2026-03-20',100,'livre','fantasia','Cinema','Filme'),
('A Morte de um Unicórnio','2025-03-28',107,'16','fantasia','Cinema','Filme'),
('Donzela','2024-03-08',108,'14','fantasia','Cinema','Filme'),
('Frankenstein','2025-10-17',149,'16','fantasia','Cinema','Filme'),
('Uma Grande e Corajosa Viagem','2025-09-18',93,'10','fantasia','Cinema','Filme'),
('Werewolf','2026-01-16',105,'16','fantasia','Cinema','Filme'),
('Wicked','2024-11-21',160,'10','fantasia','Cinema','Filme'),
-- Ficção
('De Volta para o Futuro','1985-07-03',116,'10','ficção científica','Cinema','Filme'),
('E.T. - O Extraterrestre','1982-06-11',115,'livre','ficção científica','Cinema','Filme'),
('Guardiões da Galáxia','2014-08-01',121,'12','ficção científica','Cinema','Filme'),
('Jurassic Park','1993-06-11',127,'12','ficção científica','Cinema','Filme'),
('Matrix','1999-03-31',136,'16','ficção científica','Cinema','Filme'),
('Maze Runner: Correr ou Morrer','2014-09-19',113,'12','ficção científica','Cinema','Filme'),
('O Último Refúgio','2026-01-23',105,'14','ficção científica','Cinema','Filme'),
-- Romance
('A Lista da Minha Vida','2025-01-24',100,'12','romance','Cinema','Filme'),
('Amores Materialistas','2025-06-13',116,'14','romance','Cinema','Filme'),
('Cartas para Julieta','2010-05-14',105,'10','romance','Cinema','Filme'),
('Diário de uma Paixão','2004-06-25',123,'12','romance','Cinema','Filme'),
('Dolce Villa','2025-09-12',100,'12','romance','Cinema','Filme'),
('Simplesmente Acontece','2014-10-17',102,'16','romance','Cinema','Filme'),
('Tudo Menos Você','2023-12-22',103,'16','romance','Cinema','Filme'),
('A Hipótese do Amor','2026-03-06',105,'14','romance','Cinema','Filme'),
('Continência ao Amor','2026-02-13',100,'12','romance','Cinema','Filme'),
('Uma Ideia de Você','2024-05-02',115,'16','romance','Cinema','Filme'),
-- Suspense
('A Hora do Mal','2025-08-08',128,'16','suspense','Cinema','Filme'),
('Anniversary','2025-10-10',100,'16','suspense','Cinema','Filme'),
('Carrie, a Estranha','1976-11-03',98,'16','suspense','Cinema','Filme'),
('Crime 101','2026-02-13',140,'14','suspense','Cinema','Filme'),
('O Homem que Sussurra','2025-10-24',100,'16','suspense','Cinema','Filme'),
('O Roubo','2025-09-05',105,'14','suspense','Cinema','Filme'),
('The Back','2025-11-07',100,'16','suspense','Cinema','Filme'),
('Verity','2026-10-02',115,'16','suspense','Cinema','Filme'),
-- Terror
('A Hora do Pesadelo','1984-11-09',91,'18','terror','Cinema','Filme'),
('Halloween: A Noite do Terror','1978-10-25',91,'16','terror','Cinema','Filme'),
('Resident Evil','2002-03-15',100,'16','terror','Cinema','Filme'),
('Sexta-Feira 13','1980-05-09',95,'18','terror','Cinema','Filme'),
('O Exorcista','1973-12-26',122,'18','terror','Cinema','Filme'),

-- ===== FILMES (STREAMING) =====
-- Ação
('Divergente','2014-03-21',139,'12','ação','Streaming','Filme'),
('Em Chamas','2013-11-22',146,'12','ação','Streaming','Filme'),
('Missão Impossível','1996-05-22',110,'14','ação','Streaming','Filme'),
('O Protetor','2014-09-26',132,'16','ação','Streaming','Filme'),
('Velozes e Furiosos 1','2001-06-22',106,'12','ação','Streaming','Filme'),
('Vingadores','2012-04-27',143,'12','ação','Streaming','Filme'),
('Viúva Negra','2021-07-09',134,'12','ação','Streaming','Filme'),
-- Aventura
('A Múmia','1999-05-07',125,'14','aventura','Streaming','Filme'),
('A Vida Secreta de Walter Mitty','2013-12-25',114,'10','aventura','Streaming','Filme'),
('Amor e Monstros','2020-04-17',109,'12','aventura','Streaming','Filme'),
('As Aventuras de Tintim','2011-10-26',107,'livre','aventura','Streaming','Filme'),
('Aventura Perigosa','2024-06-14',100,'12','aventura','Streaming','Filme'),
('Devoradores de Estrelas','2025-05-30',100,'12','aventura','Streaming','Filme'),
('Duna: Parte Três','2026-12-18',160,'14','aventura','Streaming','Filme'),
-- Comédia
('Dennis, o Pimentinha','1993-06-25',95,'livre','comédia','Streaming','Filme'),
('Mamma Mia','2008-07-18',108,'livre','comédia','Streaming','Filme'),
('Quanto Mais Quente Melhor','1959-03-29',120,'12','comédia','Streaming','Filme'),
('Agentes Muito Especiais','2016-03-18',83,'14','comédia','Streaming','Filme'),
('Não Vamos Pagar Nada','2020-01-30',100,'12','comédia','Streaming','Filme'),
('Os Farofeiros','2018-01-18',90,'12','comédia','Streaming','Filme'),
('RED: Aposentados e Perigosos','2010-10-15',111,'12','comédia','Streaming','Filme'),
-- Drama
('A Onda','2008-03-13',107,'14','drama','Streaming','Filme'),
('Até a Última Gota','2025-02-14',100,'14','drama','Streaming','Filme'),
('Desafie a Escuridão','2024-05-10',100,'14','drama','Streaming','Filme'),
('Som da Esperança','2024-09-27',100,'12','drama','Streaming','Filme'),
('Son-Mother','2025-03-14',100,'14','drama','Streaming','Filme'),
('Sonhos Roubados','2009-10-09',100,'14','drama','Streaming','Filme'),
('Vitória','2025-01-24',100,'12','drama','Streaming','Filme'),
-- Fantasia
('Harry Potter e o Enigma do Príncipe','2009-07-17',153,'12','fantasia','Streaming','Filme'),
('A Viagem de Chihiro','2001-07-20',125,'livre','fantasia','Streaming','Filme'),
('O Hobbit','2012-12-14',169,'12','fantasia','Streaming','Filme'),
('Nárnia','2005-12-09',143,'10','fantasia','Streaming','Filme'),
('O Baú da Morte','2006-07-07',151,'12','fantasia','Streaming','Filme'),
('O Labirinto do Fauno','2006-10-11',118,'16','fantasia','Streaming','Filme'),
('Percy Jackson e o Ladrão de Raios','2010-02-12',118,'12','fantasia','Streaming','Filme'),
('O Senhor dos Anéis','2001-12-19',178,'12','fantasia','Streaming','Filme'),
-- Ficção
('Bird Box','2018-12-21',124,'16','ficção científica','Streaming','Filme'),
('Jogador Nº 1','2018-03-29',140,'12','ficção científica','Streaming','Filme'),
('O Demolidor','1993-10-08',115,'16','ficção científica','Streaming','Filme'),
('O Refúgio','2024-05-17',100,'14','ficção científica','Streaming','Filme'),
('Perdido em Marte','2015-10-01',144,'12','ficção científica','Streaming','Filme'),
-- Romance
('10 Coisas que Eu Odeio em Você','1999-03-31',97,'12','romance','Streaming','Filme'),
('A Culpa é das Estrelas','2014-06-06',126,'12','romance','Streaming','Filme'),
('Como Perder um Homem em 10 Dias','2003-02-07',116,'12','romance','Streaming','Filme'),
('Como Eu Era Antes de Você','2016-06-03',110,'12','romance','Streaming','Filme'),
('De Repente 30','2004-04-23',98,'12','romance','Streaming','Filme'),
('Paixão e Velocidade','2023-06-16',100,'12','romance','Streaming','Filme'),
-- Suspense
('Seven: Os Sete Crimes Capitais','1995-12-15',127,'16','suspense','Streaming','Filme'),
('A Mulher Secreta','2024-03-15',100,'14','suspense','Streaming','Filme'),
('Garota Exemplar','2014-10-03',149,'16','suspense','Streaming','Filme'),
('Ilha do Medo','2010-02-19',138,'16','suspense','Streaming','Filme'),
('O Sexto Sentido','1999-08-06',107,'14','suspense','Streaming','Filme'),
('Observadores','2024-06-07',102,'14','suspense','Streaming','Filme'),
('Socorro','2026-01-30',100,'16','suspense','Streaming','Filme'),
('Um Lugar Silencioso','2018-04-06',90,'14','suspense','Streaming','Filme'),
-- Terror
('O Chamado 3','2017-02-03',102,'14','terror','Streaming','Filme'),
('Pânico 1','1996-12-20',111,'16','terror','Streaming','Filme'),
('Poltergeist','1982-06-04',114,'14','terror','Streaming','Filme'),

-- ===== SÉRIES (duração = minutos por episódio) =====
-- Ação
('The Boys','2019-07-26',60,'18','ação','Streaming','Serie'),
('Até o Último Homem','2025-01-10',50,'16','ação','Streaming','Serie'),
('Cobra Kai','2018-05-02',30,'14','ação','Streaming','Serie'),
('Demolidor (Daredevil)','2015-04-10',55,'16','ação','Streaming','Serie'),
('Gangs of London','2020-04-23',60,'18','ação','Streaming','Serie'),
('La Casa de Papel','2017-05-02',50,'16','ação','Streaming','Serie'),
('Lupin','2021-01-08',45,'14','ação','Streaming','Serie'),
('Reacher','2022-02-04',50,'16','ação','Streaming','Serie'),
-- Aventura
('Percy Jackson e os Olimpianos','2023-12-20',45,'12','aventura','Streaming','Serie'),
('Fallout','2024-04-10',60,'18','aventura','Streaming','Serie'),
('Game of Thrones','2011-04-17',55,'18','aventura','Streaming','Serie'),
('Lost','2004-09-22',45,'14','aventura','Streaming','Serie'),
('Star Wars: The Mandalorian','2019-11-12',40,'12','aventura','Streaming','Serie'),
('The Last of Us','2023-01-15',55,'18','aventura','Streaming','Serie'),
('The Witcher','2019-12-20',60,'18','aventura','Streaming','Serie'),
('Vikings','2013-03-03',45,'18','aventura','Streaming','Serie'),
-- Comédia
('Scrubs','2001-10-02',22,'14','comédia','Streaming','Serie'),
('Brooklyn Nine-Nine','2013-09-17',22,'12','comédia','Streaming','Serie'),
('Como Eu Conheci Sua Mãe','2005-09-19',22,'14','comédia','Streaming','Serie'),
('Friends','1994-09-22',22,'12','comédia','Streaming','Serie'),
('Modern Family','2009-09-23',22,'12','comédia','Streaming','Serie'),
('The Big Bang Theory','2007-09-24',22,'12','comédia','Streaming','Serie'),
('The Office','2005-03-24',22,'14','comédia','Streaming','Serie'),
('Todo Mundo Odeia o Chris','2005-09-22',22,'12','comédia','Streaming','Serie'),
-- Drama
('Anne with an E','2017-05-12',45,'10','drama','Streaming','Serie'),
('Breaking Bad','2008-01-20',47,'18','drama','Streaming','Serie'),
('Gossip Girl','2007-09-19',42,'14','drama','Streaming','Serie'),
('O Gambito da Rainha','2020-10-23',60,'16','drama','Streaming','Serie'),
('Sons of Anarchy','2008-09-03',55,'18','drama','Streaming','Serie'),
('Sterling Point','2025-06-20',45,'14','drama','Streaming','Serie'),
('Succession','2018-06-03',60,'16','drama','Streaming','Serie'),
('The Chosen','2019-04-21',45,'12','drama','Streaming','Serie'),
-- Fantasia
('Cidade Invisível','2021-02-05',40,'14','fantasia','Streaming','Serie'),
('Fate: A Saga Winx','2021-01-22',50,'14','fantasia','Streaming','Serie'),
('Lucifer','2016-01-25',45,'16','fantasia','Streaming','Serie'),
('O Último Mestre do Ar','2005-02-21',23,'livre','fantasia','Streaming','Serie'),
('Supernatural','2005-09-13',43,'16','fantasia','Streaming','Serie'),
('Teen Wolf','2011-06-05',45,'14','fantasia','Streaming','Serie'),
('Wandinha','2022-11-23',50,'14','fantasia','Streaming','Serie'),
-- Ficção
('Doctor Who','2005-03-26',45,'12','ficção científica','Streaming','Serie'),
('Black Mirror','2011-12-04',60,'16','ficção científica','Streaming','Serie'),
('Dark','2017-12-01',55,'16','ficção científica','Streaming','Serie'),
('Fringe','2008-09-09',45,'14','ficção científica','Streaming','Serie'),
('Westworld','2016-10-02',60,'18','ficção científica','Streaming','Serie'),
('Severance','2022-02-18',50,'16','ficção científica','Streaming','Serie'),
('Stranger Things','2016-07-15',50,'14','ficção científica','Streaming','Serie'),
('The X-Files','1993-09-10',45,'14','ficção científica','Streaming','Serie'),
-- Romance
('Heartstopper','2022-04-22',35,'14','romance','Streaming','Serie'),
('Maxton Hall: O Mundo Entre Nós','2024-05-09',45,'16','romance','Streaming','Serie'),
('Pousando no Amor','2019-12-14',70,'12','romance','Streaming','Serie'),
('Bridgerton','2020-12-25',60,'18','romance','Streaming','Serie'),
('Minha Vida com a Família Walter','2024-10-09',40,'12','romance','Streaming','Serie'),
('O Tempo Traz Você pra Mim','2022-09-23',40,'12','romance','Streaming','Serie'),
('Off Campus','2026-05-13',45,'16','romance','Streaming','Serie'),
('Ransom Canyon','2025-04-17',50,'16','romance','Streaming','Serie'),
-- Suspense
('O Agente Noturno','2023-03-23',50,'14','suspense','Streaming','Serie'),
('O Enfermeiro da Noite','2025-09-11',45,'16','suspense','Streaming','Serie'),
('The Residence','2025-03-20',50,'14','suspense','Streaming','Serie'),
('Diários de um Vampiro','2009-09-10',43,'14','suspense','Streaming','Serie'),
('Hannibal','2013-04-04',45,'18','suspense','Streaming','Serie'),
('Prison Break: Em Busca da Verdade','2005-08-29',45,'16','suspense','Streaming','Serie'),
('Yellowjackets','2021-11-14',55,'18','suspense','Streaming','Serie'),
('You','2018-09-09',45,'18','suspense','Streaming','Serie'),
-- Terror
('American Horror Stories','2021-07-15',45,'18','terror','Streaming','Serie'),
('Clube do Terror','2022-10-07',45,'16','terror','Streaming','Serie'),
('Creeped Out','2017-10-01',25,'12','terror','Streaming','Serie'),
('Diário de Horrores','2025-10-15',45,'16','terror','Streaming','Serie'),
('It: Bem-Vindo à Derry','2025-10-26',55,'18','terror','Streaming','Serie'),
('Missa da Meia-Noite','2021-09-24',50,'16','terror','Streaming','Serie'),
('Origem','2022-02-20',50,'16','terror','Streaming','Serie');


-- 3. HISTORICO_VISUALIZACAO
INSERT INTO historico_visualizacao (data_visualizacao, id_usuario, id_filme) VALUES
('2026-09-16', 2, 5),
('2026-04-15', 3, 6),
('2026-07-28', 1, 9),
('2026-10-02', 1, 6),
('2026-10-20', 4, 11),
('2026-08-12', 5, 3),
('2026-09-30', 6, 10),
('2026-11-01', 7, 12),
('2026-11-05', 8, 10),
('2026-10-11', 9, 3),
('2026-11-08', 10, 12),
('2026-10-25', 2, 3),
('2026-11-10', 4, 10);

-- 4. BOMBONIERE
INSERT INTO bomboniere (nome_produto, valor_produto, descricao_produto, categoria_produto) VALUES
('Combo Solo',          23.90, 'Pipoca salgada tamanho M + 1 bebida lata + 1 chocolate', 'Individual'),
('Quarteto Fantástico', 69.90, 'Caixa com 4 porções estilizadas (2 salgadas + 2 doces) + 4 bebidas lata + 2 doces', 'Família'),
('Romance Misto',       38.90, 'Balde meio a meio tamanho G (metade salgada e metade doce) + 2 bebidas lata + 1 chocolate', 'Casal'),
('Doce Magia',          19.90, 'Pipoca doce colorida ou de Leite ninho tamanho P + 1 achocolatado/suco + 1 pacote de fini', 'Infantil'),
('Vinho a Dois',        98.90, 'Pipoca artesanal de azeite de trufas e parmesão tamanho G + 1 garrafa de vinho de 750ml + 1 tábua de frios', 'Especiais'),
('Combo Nachos Supremos',34.90,'Porção generosa de nachos crocantes com molho de queijo cheddar quente, guacamole e chilli com carne + 1 bebida artesanal ou lata', 'Premium'),
('Pipoca Grande Salgada',18.90,'Pipoca salgada tamanho G', 'Individual'),
('Refrigerante 700ml',  12.90, 'Refrigerante gelado de 700ml, sabores variados', 'Bebidas'),
('Chocolate Sortido',    9.90, 'Barra de chocolate ao leite ou meio amargo', 'Doces'),
('Combo Dupla',         44.90, 'Pipoca tamanho G + 2 refrigerantes de 500ml', 'Casal');

-- 5. PLANO (os 3 primeiros são os reais; os demais estão inativos)
INSERT INTO plano (nome_plano, descricao_plano, plano_ativo, valor_mensal, valor_anual) VALUES
('Lux Play',  'Assista ao catálogo de filmes disponíveis em casa e receba 5% de desconto em ingressos comprados pela LUX', 'Sim', 19.90, 199.00),
('Lux Plus',  'Aproveite o catálogo de streaming, 10% de desconto em ingressos e 5% de desconto na bomboniere', 'Sim', 29.90, 299.00),
('Lux Prime', 'Tenha acesso ao catálogo de streaming, 15% de desconto em ingressos, 10% de desconto na bomboniere', 'Sim', 39.90, 399.00),
('Lux Estudante','Plano descontinuado com desconto para estudantes em ingressos', 'Não', 14.90, 149.00),
('Lux Família', 'Plano descontinuado para até 4 pessoas com benefícios no cinema', 'Não', 49.90, 499.00),
('Lux Night',  'Plano descontinuado com vantagens em sessões noturnas', 'Não', 24.90, 249.00),
('Lux Weekend','Plano descontinuado com vantagens em sessões de fim de semana', 'Não', 22.90, 229.00),
('Lux Gold',   'Plano descontinuado com acesso ao streaming e pontos em dobro', 'Não', 44.90, 449.00),
('Lux Mini',   'Plano descontinuado com acesso ao catálogo infantil', 'Não', 12.90, 129.00),
('Lux Max',    'Plano descontinuado com todos os benefícios e ingressos extras', 'Não', 59.90, 599.00);

-- 6. CINEMA
INSERT INTO cinema (endereco_cinema) VALUES
('Rua Beira Mar N 100, SAO CAETANO DO SUL - SP'),
('Avenida Europa N 207, BARRA DA TIJUCA - RJ'),
('Rua Atlântica N 340, SAO BERNARDO DO CAMPO - SP'),
('Avenida Paulista N 1500, BELA VISTA - SP'),
('Rua das Flores N 55, CENTRO - CURITIBA - PR'),
('Avenida Brasil N 980, CENTRO - BELO HORIZONTE - MG'),
('Rua do Porto N 12, CENTRO - SANTOS - SP'),
('Avenida Beira Rio N 700, CENTRO - PORTO ALEGRE - RS'),
('Rua das Palmeiras N 245, BOA VIAGEM - RECIFE - PE'),
('Avenida Sete de Setembro N 410, CENTRO - SALVADOR - BA');

-- 7. SALA
INSERT INTO sala (numero_sala, capacidade, id_cinema) VALUES
(1, 70, 1), (2, 70, 1), (3, 55, 1),
(1, 80, 2), (2, 75, 2),
(1, 85, 3), (2, 70, 3),
(3, 60, 2), (3, 65, 3),
(1, 90, 4), (2, 80, 4),
(1, 70, 5), (1, 75, 6), (1, 60, 7);

-- 8. SESSAO (somente filmes com exibição 'Cinema')
INSERT INTO sessao (dia_sessao, hora_sessao, id_filme, id_sala) VALUES
('2026-11-19', '14:00:00', 2,  1),
('2026-11-19', '16:30:00', 6,  2),
('2026-11-19', '19:00:00', 11, 3),
('2026-11-20', '15:00:00', 5,  4),
('2026-11-20', '18:00:00', 8,  5),
('2026-11-20', '21:00:00', 9,  6),
('2026-11-21', '14:00:00', 2,  7),
('2026-11-21', '17:30:00', 4,  1),
('2026-11-22', '16:00:00', 7,  2),
('2026-11-22', '20:00:00', 1,  3),
('2026-11-23', '19:30:00', 11, 8),
('2026-11-24', '15:30:00', 6, 10);

-- 9. RESERVA
INSERT INTO reserva (dia_reserva, hora_reserva, status_reserva, id_usuario, id_sala, id_filme) VALUES
('2026-07-07', '14:10:00', 'Concluída', 2, 1,  4),
('2026-02-20', '10:15:00', 'Concluída', 1, 4,  2),
('2026-07-05', '15:30:00', 'Cancelada', 2, 2,  5),
('2026-11-21', '18:00:00', 'Agendada',  3, 4,  6),
('2026-12-11', '20:45:00', 'Agendada',  1, 2,  7),
('2026-12-05', '16:00:00', 'Agendada',  4, 5,  1),
('2026-12-12', '19:00:00', 'Agendada',  5, 6, 11),
('2026-10-10', '13:00:00', 'Concluída', 6, 7,  9),
('2026-12-18', '21:00:00', 'Agendada',  7, 8,  8),
('2026-09-03', '11:30:00', 'Cancelada', 8, 10, 2);

-- 10. ASSINATURA
INSERT INTO assinatura (periodo, data_inicio, data_fim, status_assinatura, id_usuario, id_plano) VALUES
('Mensal', '2026-07-01', '2026-08-01', 'Expirado',  1, 2),
('Anual',  '2026-06-16', '2027-06-16', 'Ativo',     2, 3),
('Mensal', '2026-03-19', '2026-04-19', 'Cancelado', 3, 1),
('Mensal', '2026-11-01', '2026-12-01', 'Ativo',     4, 1),
('Anual',  '2026-01-10', '2027-01-10', 'Ativo',     5, 2),
('Mensal', '2026-10-15', '2026-11-15', 'Expirado',  6, 3),
('Anual',  '2025-09-01', '2026-09-01', 'Expirado',  7, 1),
('Mensal', '2026-11-10', '2026-12-10', 'Ativo',     8, 2),
('Mensal', '2026-05-05', '2026-06-05', 'Cancelado', 9, 3),
('Anual',  '2026-08-20', '2027-08-20', 'Ativo',    10, 3);

-- 11. VENDA (valor_pedido = produto + ingressos da venda)
INSERT INTO venda (data_pedido, valor_pedido, id_usuario, id_reserva, id_produto) VALUES
('2026-11-09', 129.90,  1, NULL, 2),   -- 69.90 + 2x30.00
('2026-11-10',  78.90,  3, NULL, 3),   -- 38.90 + 2x20.00
('2026-11-12',  20.00,  2, NULL, NULL),-- 1x20.00
('2026-11-13',  73.90,  4, 6,    1),   -- 23.90 + 2x25.00
('2026-11-14',  59.90,  5, 7,    6),   -- 34.90 + 1x25.00
('2026-11-15',  67.50,  6, NULL, NULL),-- 3x22.50
('2026-11-16',  75.90,  7, NULL, 4),   -- 19.90 + 2x28.00
('2026-11-16',  98.90,  8, NULL, 5),   -- somente produto
('2026-11-17',  68.90,  9, NULL, 7),   -- 18.90 + 2x25.00
('2026-11-17',  72.90, 10, NULL, 10);  -- 44.90 + 1x28.00

-- ENDEREÇO

USE lux_bcd;

INSERT INTO endereco_entrega (id_usuario, logradouro, numero, complemento, bairro, cidade, estado, cep)
VALUES
(1,  'Rua das Acácias',        '120', 'Apto 31',  'Centro',        'São Caetano do Sul', 'SP', '09510100'),
(2,  'Avenida das Américas',   '3500', 'Bloco B', 'Barra da Tijuca','Rio de Janeiro',    'RJ', '22640102'),
(3,  'Rua Atlântica',          '45',  NULL,       'Jardim do Mar', 'São Bernardo do Campo','SP','09750000'),
(4,  'Avenida Paulista',       '1578','Sala 12',  'Bela Vista',    'São Paulo',          'SP', '01310200'),
(5,  'Rua XV de Novembro',     '230', NULL,       'Centro',        'Curitiba',           'PR', '80020310'),
(6,  'Avenida Afonso Pena',    '980', 'Apto 504', 'Funcionários',  'Belo Horizonte',     'MG', '30130003'),
(7,  'Rua do Comércio',        '77',  NULL,       'Vila Mathias',  'Santos',             'SP', '11015020'),
(8,  'Avenida Ipiranga',       '1200','Casa 2',   'Praia de Belas','Porto Alegre',       'RS', '90160090'),
(9,  'Rua da Aurora',          '415', 'Apto 12',  'Boa Vista',     'Recife',             'PE', '50050000'),
(10, 'Avenida Sete de Setembro','2100',NULL,      'Barra',         'Salvador',           'BA', '40140110');


-- 12. PAGAMENTO
INSERT INTO pagamento (forma_pagamento, status_pagamento, data_pagamento, id_venda) VALUES
('Pix',               'Concluído', '2026-11-09', 1),
('Cartão de Crédito', 'Concluído', '2026-11-10', 2),
('Pix',               'Concluído', '2026-11-12', 3),
('Cartão de Débito',  'Concluído', '2026-11-13', 4),
('Cartão de Crédito', 'Concluído', '2026-11-14', 5),
('Pix',               'Concluído', '2026-11-15', 6),
('Cartão de Crédito', 'Concluído', '2026-11-16', 7),
('Pix',               'Pendente',  '2026-11-16', 8),
('Cartão de Débito',  'Concluído', '2026-11-17', 9),
('Cartão de Crédito', 'Recusado',  '2026-11-17', 10);

-- 13. INGRESSOS
INSERT INTO ingressos (assento, valor_ingresso, id_venda, id_sessao) VALUES
('N5', 30.00, 1, 1),
('N6', 30.00, 1, 1),
('C4', 20.00, 2, 2),
('C5', 20.00, 2, 2),
('F7', 20.00, 3, 3),
('A1', 25.00, 4, 4),
('A2', 25.00, 4, 4),
('B3', 25.00, 5, 5),
('D7', 22.50, 6, 6),
('D8', 22.50, 6, 6),
('D9', 22.50, 6, 6),
('E1', 28.00, 7, 7),
('E2', 28.00, 7, 7),
('G4', 25.00, 9, 8),
('G5', 25.00, 9, 8),
('H10',28.00,10, 9);

-- 14. BENEFICIOS
INSERT INTO beneficios (nome_beneficio, pontos_beneficio, descricao_beneficio, beneficio_ativo) VALUES
('Pipoca pequena Grátis',      50, 'Retirada na bomboniere do cinema', 'SIM'),
('Refrigerante Grátis (lata)', 70, 'Refrigerante de lata, retirar no cinema', 'SIM'),
('Desconto no ingresso',       90, 'Ganhe R$10 de desconto no seu próximo ingresso', 'SIM'),
('Pipoca média Grátis',       120, 'Pipoca salgada ou doce tamanho M, retirar no cinema', 'SIM'),
('Combo Solo Grátis',         200, 'Um Combo Solo completo, retirar no cinema', 'SIM'),
('Desconto maior no ingresso',160, 'Ganhe R$20 de desconto no seu próximo ingresso', 'SIM'),
('Ingresso Grátis',           300, 'Um ingresso grátis para qualquer sessão', 'SIM'),
('Chocolate Grátis',           60, 'Um chocolate à escolha, retirar no cinema', 'SIM'),
('Upgrade de Pipoca',         100, 'Upgrade da pipoca para o tamanho grande', 'SIM'),
('Desconto na bomboniere',    130, 'Ganhe 15% de desconto em qualquer produto da bomboniere', 'NÃO');

-- 15. PONTOS_USUARIO
INSERT INTO pontos_usuario (qntd_pontos, id_usuario) VALUES
(1700, 1), (70, 2), (180, 3), (320, 4), (950, 5),
(40, 6), (610, 7), (260, 8), (85, 9), (1200, 10);

-- 16. RESGATE_BENEFICIO
INSERT INTO resgate_beneficio (pontos_utilizados, data_resgate, id_usuario, id_beneficio) VALUES
(50,  '2026-08-07', 1, 1),
(70,  '2026-05-08', 2, 2),
(90,  '2026-01-22', 3, 3),
(120, '2026-09-12', 4, 4),
(200, '2026-10-03', 5, 5),
(160, '2026-10-21', 6, 6),
(60,  '2026-06-14', 7, 8),
(100, '2026-07-30', 8, 9),
(50,  '2026-11-02', 9, 1),
(300, '2026-11-10', 1, 7);

INSERT INTO cupom(codigo, tipo_desconto, valor_desconto, valor_minimo_pedido, data_validade, cupom_ativo)
VALUES
('BEMVINDO10',  'Porcentagem', 10.00,  30.00, '2027-12-31', 'Sim'),
('LUX5',       'Fixo',         5.00,  20.00, '2027-06-30', 'Sim'),
('CINEMA15',   'Porcentagem', 15.00,  50.00, '2027-03-31', 'Sim'),
('COMBO10',    'Fixo',        10.00,  60.00, '2027-08-31', 'Sim'),
('FAMILIA20',  'Porcentagem', 20.00, 100.00, '2027-07-31', 'Sim'),
('LUX25',      'Fixo',        25.00, 150.00, '2027-05-31', 'Sim'),
('VOLTE10',    'Porcentagem', 10.00,   0.00, '2027-09-30', 'Sim'),
('ESPECIAL30', 'Fixo',        30.00, 200.00, '2027-12-31', 'Não'),
('PROMO2025',  'Porcentagem',  5.00,  25.00, '2025-12-31', 'Sim'),
('ANTIGO15',   'Fixo',        15.00,  80.00, '2025-06-30', 'Não');

-- 17. AVALIACAO (cada avaliação aponta para produto OU filme)
INSERT INTO avaliacao (nota_avl, cometario_avl, id_usuario, id_produto, id_filme) VALUES
(8,  'Gostei do combo! A pipoca veio quentinha e a porção foi suficiente para acompanhar o filme. Só achei que o refrigerante poderia ser um pouco maior.', 1, 3, NULL),
(9,  'A história me prendeu do começo ao fim. As cenas no espaço são impressionantes e a trilha sonora deixa tudo ainda mais emocionante.', 2, NULL, 6),
(10, 'Cenas de ação incríveis e trilha sonora marcante.', 3, NULL, 1),
(7,  'Divertido para ver em família, mas achei um pouco longo.', 4, NULL, 2),
(6,  'Pipoca doce muito boa, mas a porção é pequena.', 5, 4, NULL),
(9,  'Emocionante do início ao fim, recomendo levar lenços.', 6, NULL, 4),
(8,  'O Quarteto Fantástico rendeu para a família toda.', 7, 2, NULL),
(5,  'Suspense bom, mas o final é previsível.', 8, NULL, 8),
(10, 'Combo perfeito para casal, a pipoca meio a meio é ótima.', 9, 3, NULL),
(7,  'Terror bem construído, deu sustos de verdade.', 10, NULL, 9);

-- 18. LISTA
INSERT INTO lista (nome_lista, descricao_lista, data_criacao, id_usuario) VALUES
('Assistir mais tarde', NULL, '2026-08-20', 2),
('Assistir com a Lu', 'NÃO ASSISTIR SEM A LUNA, SE NÃO ELA FICA BRAVA', '2026-02-27', 1),
('Para não pensar', 'Momentos difíceis da vida onde só queremos assistir sem precisar raciocinar', '2026-04-15', 3),
('Favoritos', 'Meus filmes favoritos de todos os tempos', '2026-03-10', 4),
('Fim de semana', 'Filmes para maratonar no sábado', '2026-05-22', 5),
('Para chorar', NULL, '2026-06-30', 6),
('Clássicos', 'Filmes antigos que preciso ver', '2026-07-12', 7),
('Em família', 'Filmes para ver com as crianças', '2026-09-01', 8),
('Terror e suspense', NULL, '2026-10-05', 9),
('Ficção científica', 'Viagens no espaço e no tempo', '2026-10-28', 10);

-- 19. FILME_LISTA
INSERT INTO filme_lista (id_filme, id_lista) VALUES
(2, 1), (3, 2), (5, 3),
(6, 4), (11, 4),
(1, 5), (3, 5),
(4, 6),
(1, 7), (5, 7),
(2, 8), (10, 8),
(8, 9), (9, 9),
(6, 10), (11, 10);
