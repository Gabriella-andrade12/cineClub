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
INSERT INTO filme (nome_filme, sinopse, imagem_capa, data_filme, duracao_filme, class_filme, gen_filme, exibicao_filme, tipo_conteudo) VALUES
('Como Perder um Homem em 10 Dias', 'Uma jornalista e um publicitário começam um romance por apostas secretas.', 'Imagens/p-romance/como perder um homem em 10 dias.jpg', '2003-02-07', 116, '12', 'romance', 'Streaming', 'Filme'),
('Como Eu Era Antes de Você', 'Uma cuidadora muda sua visão de mundo ao conhecer um jovem tetraplégico.', 'Imagens/p-romance/comom eu era antes de voce.jpg', '2016-06-03', 110, '12', 'romance', 'Streaming', 'Filme'),
('De Repente 30', 'Uma adolescente acorda no corpo de sua versão adulta e reconsidera os sonhos que tinha.', 'Imagens/p-romance/de repente 30.jpg', '2004-04-23', 98, '12', 'romance', 'Streaming', 'Filme'),
('Paixão e Velocidade', 'Uma mecânica e um piloto fingem ser um casal e acabam aproximando-se de verdade.', 'Imagens/p-romance/paixao e velocidade.jpg', '2023-06-16', 100, '12', 'romance', 'Streaming', 'Filme'),


-- STREAMING - Suspense
('Seven: Os Sete Crimes Capitais', 'Dois detetives investigam uma sequência de assassinatos inspirados nos sete pecados capitais.', 'Imagens/p-suspense/Seven - Os Sete Crimes Capitais.jpg', '1995-12-15', 127, '16', 'suspense', 'Streaming', 'Filme'),
('A Mulher Secreta', 'Uma mulher com amnésia descobre um passado familiar escondido e busca entender sua verdadeira identidade.', 'Imagens/p-suspense/a mulher secreta.jpg', '2024-03-15', 100, '14', 'suspense', 'Streaming', 'Filme'),
('Garota Exemplar', 'O desaparecimento de uma mulher torna seu marido suspeito e revela um casamento cheio de segredos.', 'Imagens/p-suspense/garota exemplar.jpg', '2014-10-03', 149, '16', 'suspense', 'Streaming', 'Filme'),
('Ilha do Medo', 'Dois agentes investigam um desaparecimento em um hospital psiquiátrico isolado.', 'Imagens/p-suspense/ilha do medo.jpg', '2010-02-19', 138, '16', 'suspense', 'Streaming', 'Filme'),
('O Sexto Sentido', 'Um psicólogo atende um menino que afirma enxergar pessoas mortas.', 'Imagens/p-suspense/o sexto sentido.jpg', '1999-08-06', 107, '14', 'suspense', 'Streaming', 'Filme'),
('Observadores', 'Uma jovem fica presa em uma floresta onde criaturas misteriosas vigiam desconhecidos durante a noite.', 'Imagens/p-suspense/observadores.jpg', '2024-06-07', 102, '14', 'suspense', 'Streaming', 'Filme'),
('Socorro', 'Uma funcionária e seu chefe ficam isolados e precisam sobreviver a uma situação extrema.', 'Imagens/p-suspense/socorro.jpg', '2026-01-30', 100, '16', 'suspense', 'Streaming', 'Filme'),
('Um Lugar Silencioso', 'Uma família vive em silêncio para não atrair criaturas que caçam pelo som.', 'Imagens/p-suspense/um lugar silencioso.jpg', '2018-04-06', 90, '14', 'suspense', 'Streaming', 'Filme'),

-- STREAMING - Terror
('O Chamado 3', 'Uma gravação amaldiçoada volta a espalhar medo entre pessoas que assistem ao vídeo.', 'Imagens/p-terror/o chamado 3.jpg', '2017-02-03', 102, '14', 'terror', 'Streaming', 'Filme'),
('Pânico 1', 'Um assassino mascarado passa a perseguir adolescentes em uma pequena cidade.', 'Imagens/p-terror/panico 1.jpg', '1996-12-20', 111, '16', 'terror', 'Streaming', 'Filme'),
('Poltergeist', 'Uma família enfrenta fenômenos sobrenaturais após forças misteriosas invadirem sua casa.', 'Imagens/p-terror/potergaist.jpg', '1982-06-04', 114, '14', 'terror', 'Streaming', 'Filme'),


-- SÉRIES - Ação
('The Boys', 'Um grupo tenta deter super-heróis poderosos que escondem corrupção e violência.', 'Imagens/s.acao/The Boys.jpg', '2019-07-26', 60, '18', 'ação', 'Streaming', 'Serie'),
('Até o Último Homem', 'Em meio a conflitos intensos, personagens lutam para sobreviver e proteger seus companheiros.', 'Imagens/s.acao/até o último homem.jpg', '2025-01-10', 50, '16', 'ação', 'Streaming', 'Serie'),
('Cobra Kai', 'Velhas rivalidades do caratê retornam quando antigos adversários passam a treinar novos alunos.', 'Imagens/s.acao/cobra kai.jpg', '2018-05-02', 30, '14', 'ação', 'Streaming', 'Serie'),
('Demolidor (Daredevil)', 'Um advogado cego combate o crime nas ruas de Nova York como vigilante mascarado.', 'Imagens/s.acao/daredevil.jpg', '2015-04-10', 55, '16', 'ação', 'Streaming', 'Serie'),
('Gangs of London', 'A morte de um chefe criminoso provoca uma guerra entre facções em Londres.', 'Imagens/s.acao/gangs of london.jpg', '2020-04-23', 60, '18', 'ação', 'Streaming', 'Serie'),
('La Casa de Papel', 'Criminosos organizam um grande assalto sob a liderança de um estrategista conhecido como Professor.', 'Imagens/s.acao/la casa de papel.jpg', '2017-05-02', 50, '16', 'ação', 'Streaming', 'Serie'),
('Lupin', 'Um ladrão elegante usa disfarces e planos engenhosos para vingar uma injustiça contra seu pai.', 'Imagens/s.acao/lupin.jpg', '2021-01-08', 45, '14', 'ação', 'Streaming', 'Serie'),
('Reacher', 'Um ex-policial militar investiga crimes e enfrenta conspirações ao viajar pelos Estados Unidos.', 'Imagens/s.acao/reacher.jpg', '2022-02-04', 50, '16', 'ação', 'Streaming', 'Serie'),


-- SÉRIES - Aventura
('Percy Jackson e os Olimpianos', 'Um jovem semideus embarca em missões para evitar conflitos entre os deuses do Olimpo.', 'Imagens/s.aventura/Percy Jackson e os Olimpianos.jpg', '2023-12-20', 45, '12', 'aventura', 'Streaming', 'Serie'),
('Fallout', 'Sobreviventes de um mundo nuclear enfrentam facções e perigos em uma sociedade pós-apocalíptica.', 'Imagens/s.aventura/fallout.jpg', '2024-04-10', 60, '18', 'aventura', 'Streaming', 'Serie'),
('Game of Thrones', 'Famílias nobres disputam o poder sobre Westeros enquanto uma ameaça cresce ao norte.', 'Imagens/s.aventura/game of thrones.jpg', '2011-04-17', 55, '18', 'aventura', 'Streaming', 'Serie'),
('Lost', 'Sobreviventes de um acidente aéreo descobrem mistérios em uma ilha aparentemente deserta.', 'Imagens/s.aventura/lost.jpg', '2004-09-22', 45, '14', 'aventura', 'Streaming', 'Serie'),
('Star Wars: The Mandalorian', 'Um caçador de recompensas atravessa a galáxia protegendo uma criança muito especial.', 'Imagens/s.aventura/star wars mandalorian.jpg', '2019-11-12', 40, '12', 'aventura', 'Streaming', 'Serie'),
('The Last of Us', 'Um homem e uma adolescente atravessam um mundo devastado por uma infecção.', 'Imagens/s.aventura/the last of us.jpg', '2023-01-15', 55, '18', 'aventura', 'Streaming', 'Serie'),
('The Witcher', 'Um caçador de monstros enfrenta conflitos políticos e destinos ligados à magia.', 'Imagens/s.aventura/the witcher.jpg', '2019-12-20', 60, '18', 'aventura', 'Streaming', 'Serie'),
('Vikings', 'O guerreiro Ragnar Lothbrok lidera expedições que transformam o destino de seu povo.', 'Imagens/s.aventura/vikings.jpg', '2013-03-03', 45, '18', 'aventura', 'Streaming', 'Serie'),


-- SÉRIES - Comédia
('Scrubs', 'Jovens médicos enfrentam os desafios da profissão entre amizade, humor e emoção.', 'Imagens/s.comedia/Scrubs.jpg', '2001-10-02', 22, '14', 'comédia', 'Streaming', 'Serie'),
('Brooklyn Nine-Nine', 'Detetives de uma delegacia do Brooklyn resolvem crimes em meio a situações cômicas.', 'Imagens/s.comedia/brooklyn nine-nine.jpg', '2013-09-17', 22, '12', 'comédia', 'Streaming', 'Serie'),
('Como Eu Conheci Sua Mãe', 'Um grupo de amigos vive romances e confusões enquanto Ted procura o amor.', 'Imagens/s.comedia/como eu conheci sua mae.jpg', '2005-09-19', 22, '14', 'comédia', 'Streaming', 'Serie'),
('Friends', 'Seis amigos compartilham amores, trabalho e dilemas cotidianos em Nova York.', 'Imagens/s.comedia/friends.jpg', '1994-09-22', 22, '12', 'comédia', 'Streaming', 'Serie'),
('Modern Family', 'Três famílias ligadas por parentesco vivem situações divertidas e emocionantes.', 'Imagens/s.comedia/modern family.jpg', '2009-09-23', 22, '12', 'comédia', 'Streaming', 'Serie'),
('The Big Bang Theory', 'Cientistas e seus amigos enfrentam relacionamentos e situações cômicas do dia a dia.', 'Imagens/s.comedia/the big bang theory.jpg', '2007-09-24', 22, '12', 'comédia', 'Streaming', 'Serie'),
('The Office', 'Funcionários de uma empresa de papel transformam a rotina do escritório em uma comédia.', 'Imagens/s.comedia/the office.jpg', '2005-03-24', 22, '14', 'comédia', 'Streaming', 'Serie'),
('Todo Mundo Odeia o Chris', 'Um adolescente enfrenta a escola, a família e as dificuldades de crescer no Brooklyn.', 'Imagens/s.comedia/todo mundo odeia o chris.jpg', '2005-09-22', 22, '12', 'comédia', 'Streaming', 'Serie'),


-- SÉRIES - Drama
('Anne with an E', 'Uma órfã criativa transforma a vida de uma família e de uma pequena comunidade.', 'Imagens/s.drama/Anne with an E.jpg', '2017-05-12', 45, '10', 'drama', 'Streaming', 'Serie'),
('Breaking Bad', 'Um professor de química passa a fabricar drogas e se envolve com o crime organizado.', 'Imagens/s.drama/breaking bad.jpg', '2008-01-20', 47, '18', 'drama', 'Streaming', 'Serie'),
('Gossip Girl', 'A vida de jovens ricos de Manhattan é exposta por uma blogueira anônima.', 'Imagens/s.drama/gossip girl.jpg', '2007-09-19', 42, '14', 'drama', 'Streaming', 'Serie'),
('O Gambito da Rainha', 'Uma jovem prodígio busca reconhecimento no xadrez enquanto enfrenta conflitos pessoais.', 'Imagens/s.drama/o gambito da rainha.jpg', '2020-10-23', 60, '16', 'drama', 'Streaming', 'Serie'),
('Sons of Anarchy', 'Membros de um motoclube criminoso enfrentam disputas de poder e lealdade familiar.', 'Imagens/s.drama/sons of anarchy.jpg', '2008-09-03', 55, '18', 'drama', 'Streaming', 'Serie'),
('Sterling Point', 'Uma jovem herda com o irmão uma ilha misteriosa e tenta desvendar segredos de família.', 'Imagens/s.drama/sterling point.jpg', '2025-06-20', 45, '14', 'drama', 'Streaming', 'Serie'),
('Succession', 'Herdeiros de um conglomerado de mídia disputam o controle dos negócios da família.', 'Imagens/s.drama/succession.jpg', '2018-06-03', 60, '16', 'drama', 'Streaming', 'Serie'),
('The Chosen', 'A trajetória de Jesus é apresentada a partir das pessoas que o encontram e passam a segui-lo.', 'Imagens/s.drama/the chosen.jpg', '2019-04-21', 45, '12', 'drama', 'Streaming', 'Serie'),


-- SÉRIES - Fantasia
('Cidade Invisível', 'Um policial investiga mortes misteriosas e descobre criaturas do folclore brasileiro.', 'Imagens/s.fantasia/Cidade Invisível.jpg', '2021-02-05', 40, '14', 'fantasia', 'Streaming', 'Serie'),
('Fate: A Saga Winx', 'Jovens fadas aprendem magia enquanto enfrentam perigos em um internato sobrenatural.', 'Imagens/s.fantasia/fate.jpg', '2021-01-22', 50, '14', 'fantasia', 'Streaming', 'Serie'),
('Lucifer', 'O senhor do inferno muda-se para Los Angeles e ajuda a polícia a solucionar crimes.', 'Imagens/s.fantasia/lucifer.jpg', '2016-01-25', 45, '16', 'fantasia', 'Streaming', 'Serie'),
('O Último Mestre do Ar', 'Um menino capaz de dominar os elementos tenta restaurar o equilíbrio entre as nações.', 'Imagens/s.fantasia/o último mestre do ar.jpg', '2005-02-21', 23, 'livre', 'fantasia', 'Streaming', 'Serie'),
('Supernatural', 'Dois irmãos caçam criaturas sobrenaturais e investigam mistérios por todo o país.', 'Imagens/s.fantasia/supernatural.jpg', '2005-09-13', 43, '16', 'fantasia', 'Streaming', 'Serie'),
('Teen Wolf', 'Um adolescente transformado em lobisomem precisa equilibrar escola, amizades e novos perigos.', 'Imagens/s.fantasia/teen wolf.jpg', '2011-06-05', 45, '14', 'fantasia', 'Streaming', 'Serie'),
('Wandinha', 'Wandinha Addams investiga crimes e mistérios enquanto estuda em uma escola peculiar.', 'Imagens/s.fantasia/wandinha.jpg', '2022-11-23', 50, '14', 'fantasia', 'Streaming', 'Serie'),


-- SÉRIES - Ficção
('Doctor Who', 'Um viajante do tempo alienígena explora o universo e resolve crises com diferentes companheiros.', 'Imagens/s.ficcao/Doctor Who.jpg', '2005-03-26', 45, '12', 'ficção científica', 'Streaming', 'Serie'),
('Black Mirror', 'Histórias independentes exploram os efeitos inquietantes da tecnologia sobre a sociedade.', 'Imagens/s.ficcao/black mirror.jpg', '2011-12-04', 60, '16', 'ficção científica', 'Streaming', 'Serie'),
('Dark', 'O desaparecimento de crianças revela conexões entre famílias e viagens no tempo.', 'Imagens/s.ficcao/dark.jpg', '2017-12-01', 55, '16', 'ficção científica', 'Streaming', 'Serie'),
('Fringe', 'Uma equipe investiga fenômenos científicos inexplicáveis e realidades paralelas.', 'Imagens/s.ficcao/fringe.jpg', '2008-09-09', 45, '14', 'ficção científica', 'Streaming', 'Serie'),
('Westworld', 'Andróides de um parque temático começam a questionar sua existência e os humanos que os controlam.', 'Imagens/s.ficcao/o caos domina westworld.jpg', '2016-10-02', 60, '18', 'ficção científica', 'Streaming', 'Serie'),
('Severance', 'Funcionários dividem suas memórias profissionais e pessoais e descobrem segredos perturbadores.', 'Imagens/s.ficcao/severance.jpg', '2022-02-18', 50, '16', 'ficção científica', 'Streaming', 'Serie'),
('Stranger Things', 'Amigos enfrentam experiências secretas e forças sobrenaturais em uma pequena cidade.', 'Imagens/s.ficcao/stranger things.jpg', '2016-07-15', 50, '14', 'ficção científica', 'Streaming', 'Serie'),
('The X-Files', 'Agentes do FBI investigam fenômenos paranormais e possíveis conspirações extraterrestres.', 'Imagens/s.ficcao/the x files.jpg', '1993-09-10', 45, '14', 'ficção científica', 'Streaming', 'Serie'),


-- SÉRIES - Romance
('Heartstopper', 'Dois adolescentes desenvolvem um romance enquanto descobrem a própria identidade e enfrentam desafios escolares.', 'Imagens/s.romance/Heartstopper.jpg', '2022-04-22', 35, '14', 'romance', 'Streaming', 'Serie'),
('Maxton Hall: O Mundo Entre Nós', 'Uma estudante bolsista e um herdeiro rico se aproximam em uma escola de elite.', 'Imagens/s.romance/Maxton Hall - O Mundo Entre Nós.jpg', '2024-05-09', 45, '16', 'romance', 'Streaming', 'Serie'),
('Pousando no Amor', 'Uma empresária sul-coreana cai por acidente na Coreia do Norte e conhece um oficial do exército.', 'Imagens/s.romance/Pousando no Amor.jpg', '2019-12-14', 70, '12', 'romance', 'Streaming', 'Serie'),
('Bridgerton', 'Jovens da alta sociedade londrina vivem romances e disputas durante a temporada de casamentos.', 'Imagens/s.romance/bridgerton.jpg', '2020-12-25', 60, '18', 'romance', 'Streaming', 'Serie'),
('Minha Vida com a Família Walter', 'Uma adolescente muda-se para uma casa cheia de irmãos e tenta reconstruir sua vida.', 'Imagens/s.romance/minha vida com a família walter.jpg', '2024-10-09', 40, '12', 'romance', 'Streaming', 'Serie'),
('O Tempo Traz Você pra Mim', 'Uma mulher enlutada viaja misteriosamente ao passado e reencontra um rosto conhecido.', 'Imagens/s.romance/o tempo traz você pra mim.jpg', '2022-09-23', 40, '12', 'romance', 'Streaming', 'Serie'),
('Off Campus', 'Uma estudante de música e um astro do hóquei fingem uma parceria que acaba virando romance.', 'Imagens/s.romance/off campus.jpg', '2026-05-13', 45, '16', 'romance', 'Streaming', 'Serie'),
('Ransom Canyon', 'Famílias de uma comunidade rural do Texas enfrentam rivalidades, paixões e segredos.', 'Imagens/s.romance/ransom canyon.jpg', '2025-04-17', 50, '16', 'romance', 'Streaming', 'Serie'),


-- SÉRIES - Suspense
('O Agente Noturno', 'Um agente do FBI atende uma chamada inesperada e se envolve em uma conspiração nacional.', 'Imagens/s.suspense/O Agente Noturno.jpg', '2023-03-23', 50, '14', 'suspense', 'Streaming', 'Serie'),
('O Enfermeiro da Noite', 'Suspeitas envolvendo mortes em um hospital colocam profissionais de saúde diante de um mistério perturbador.', 'Imagens/s.suspense/O Enfermeiro da Noite.jpg', '2025-09-11', 45, '16', 'suspense', 'Streaming', 'Serie'),
('The Residence', 'Uma detetive investiga uma morte misteriosa durante uma recepção na Casa Branca.', 'Imagens/s.suspense/The Residence.jpg', '2025-03-20', 50, '14', 'suspense', 'Streaming', 'Serie'),
('Diários de um Vampiro', 'Uma adolescente se envolve com dois irmãos vampiros em uma cidade cheia de segredos.', 'Imagens/s.suspense/diarios de um vampiro.jpg', '2009-09-10', 43, '14', 'suspense', 'Streaming', 'Serie'),
('Hannibal', 'Um investigador do FBI aproxima-se de um psiquiatra brilhante e perigosamente manipulador.', 'Imagens/s.suspense/hannibal.jpg', '2013-04-04', 45, '18', 'suspense', 'Streaming', 'Serie'),
('Prison Break: Em Busca da Verdade', 'Um engenheiro arma um plano para tirar o irmão de uma prisão de segurança máxima.', 'Imagens/s.suspense/prison break- em busca da verdade.jpg', '2005-08-29', 45, '16', 'suspense', 'Streaming', 'Serie'),
('Yellowjackets', 'Sobreviventes de um acidente aéreo lidam com traumas e segredos anos depois.', 'Imagens/s.suspense/yellowjackets.jpg', '2021-11-14', 55, '18', 'suspense', 'Streaming', 'Serie'),
('You', 'Um homem obsessivo usa manipulação e vigilância para se aproximar das pessoas que deseja.', 'Imagens/s.suspense/you.jpg', '2018-09-09', 45, '18', 'suspense', 'Streaming', 'Serie'),


-- SÉRIES - Terror
('American Horror Stories', 'Episódios independentes apresentam histórias macabras com fenômenos e personagens aterrorizantes.', 'Imagens/s.terror/American Horror Stories 10.jpg', '2021-07-15', 45, '18', 'terror', 'Streaming', 'Serie'),
('Clube do Terror', 'Jovens reunidos para compartilhar histórias assustadoras veem seus medos ganhar vida.', 'Imagens/s.terror/clube-do-terror.jpg', '2022-10-07', 45, '16', 'terror', 'Streaming', 'Serie'),
('Creeped Out', 'Uma figura misteriosa apresenta contos sombrios sobre escolhas e acontecimentos sobrenaturais.', 'Imagens/s.terror/creeped out.jpg', '2017-10-01', 25, '12', 'terror', 'Streaming', 'Serie'),
('Diário de Horrores', 'Histórias independentes colocam jovens diante de eventos estranhos e assustadores.', 'Imagens/s.terror/diario de horrores.jpg', '2025-10-15', 45, '16', 'terror', 'Streaming', 'Serie'),
('It: Bem-Vindo à Derry', 'Uma cidade marcada pelo mal enfrenta o retorno de eventos aterrorizantes ligados a uma entidade antiga.', 'Imagens/s.terror/it a.jpg', '2025-10-26', 55, '18', 'terror', 'Streaming', 'Serie'),
('Missa da Meia-Noite', 'A chegada de um padre a uma ilha isolada desencadeia milagres e acontecimentos inquietantes.', 'Imagens/s.terror/missa-meia-noite.jpg', '2021-09-24', 50, '16', 'terror', 'Streaming', 'Serie'),
('Origem', 'Moradores de uma cidade misteriosa tentam sobreviver a criaturas que aparecem durante a noite.', 'Imagens/s.terror/origem.jpg', '2022-02-20', 50, '16', 'terror', 'Streaming', 'Serie');


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

INSERT INTO cupom(codigo, tipo_desconto, valor_desconto, valor_minimo_pedido, data_validade, cupom_ativo)VALUES
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


INSERT INTO item_venda (id_venda, id_produto, quantidade, valor_unitario) VALUES
(1, 2, 1, 69.90),   -- Quarteto Fantástico
(2, 3, 1, 38.90),   -- Romance Misto
(4, 1, 1, 23.90),   -- Combo Solo
(5, 6, 1, 34.90),   -- Combo Nachos Supremos
(7, 4, 1, 19.90),   -- Doce Magia
(8, 5, 1, 98.90),   -- Vinho a Dois
(9, 7, 1, 18.90),   -- Pipoca Grande Salgada
(10, 10, 1, 44.90); -- Combo Dupla

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
INSERT INTO lista (nome_lista, descricao_lista, data_criacao, id_usuario, exib_lista) VALUES
('Assistir mais tarde', NULL, '2026-08-20', 2, 'Privado'),
('Assistir com a Lu', 'NÃO ASSISTIR SEM A LUNA, SE NÃO ELA FICA BRAVA', '2026-02-27', 1, 'Privado'),
('Para não pensar', 'Momentos difíceis da vida onde só queremos assistir sem precisar raciocinar', '2026-04-15', 3, 'Público'),
('Favoritos', 'Meus filmes favoritos de todos os tempos', '2026-03-10', 4, 'Privado'),
('Fim de semana', 'Filmes para maratonar no sábado', '2026-05-22', 5, 'Público'),
('Para chorar', NULL, '2026-06-30', 6, 'Privado'),
('Clássicos', 'Filmes antigos que preciso ver', '2026-07-12', 7, 'Público'),
('Em família', 'Filmes para ver com as crianças', '2026-09-01', 8, 'Público'),
('Terror e suspense', NULL, '2026-10-05', 9, 'Público'),
('Ficção científica', 'Viagens no espaço e no tempo', '2026-10-28', 10, 'Público');

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
