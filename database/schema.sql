CREATE DATABASE  IF NOT EXISTS `lux_bcd` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `lux_bcd`;
-- MySQL dump 10.13  Distrib 8.0.45, for Win64 (x86_64)
--
-- Host: 127.0.0.1    Database: lux_bcd
-- ------------------------------------------------------
-- Server version	26.10.0

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
SET @MYSQLDUMP_TEMP_LOG_BIN = @@SESSION.SQL_LOG_BIN;
SET @@SESSION.SQL_LOG_BIN= 0;

--
-- GTID state at the beginning of the backup 
--

SET @@GLOBAL.GTID_PURGED=/*!80000 '+'*/ '78b2b9a5-d9ed-11f1-b537-c2b49adf1294:1-179';

--
-- Table structure for table `assinatura`
--

DROP TABLE IF EXISTS `assinatura`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `assinatura` (
  `id_assinatura` int NOT NULL AUTO_INCREMENT,
  `periodo` varchar(10) NOT NULL,
  `data_inicio` date NOT NULL,
  `data_fim` date NOT NULL,
  `status_assinatura` varchar(20) NOT NULL,
  `id_usuario` int NOT NULL,
  `id_plano` int NOT NULL,
  PRIMARY KEY (`id_assinatura`),
  KEY `id_usuario` (`id_usuario`),
  KEY `id_plano` (`id_plano`),
  CONSTRAINT `assinatura_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id_usuario`),
  CONSTRAINT `assinatura_ibfk_2` FOREIGN KEY (`id_plano`) REFERENCES `plano` (`id_plano`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `assinatura`
--

LOCK TABLES `assinatura` WRITE;
/*!40000 ALTER TABLE `assinatura` DISABLE KEYS */;
INSERT INTO `assinatura` VALUES (1,'Mensal','2026-07-01','2026-08-01','Expirado',1,2),(2,'Anual','2026-06-16','2027-06-16','Ativo',2,3),(3,'Mensal','2026-03-19','2026-04-19','Cancelado',3,1),(4,'Mensal','2026-11-01','2026-12-01','Ativo',4,1),(5,'Anual','2026-01-10','2027-01-10','Ativo',5,2),(6,'Mensal','2026-10-15','2026-11-15','Expirado',6,3),(7,'Anual','2025-09-01','2026-09-01','Expirado',7,1),(8,'Mensal','2026-11-10','2026-12-10','Ativo',8,2),(9,'Mensal','2026-05-05','2026-06-05','Cancelado',9,3),(10,'Anual','2026-08-20','2027-08-20','Ativo',10,3);
/*!40000 ALTER TABLE `assinatura` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `avaliacao`
--

DROP TABLE IF EXISTS `avaliacao`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `avaliacao` (
  `id_avaliacao` int NOT NULL AUTO_INCREMENT,
  `nota_avl` int NOT NULL,
  `cometario_avl` varchar(250) DEFAULT NULL,
  `id_usuario` int NOT NULL,
  `id_produto` int DEFAULT NULL,
  `id_filme` int DEFAULT NULL,
  PRIMARY KEY (`id_avaliacao`),
  KEY `id_usuario` (`id_usuario`),
  KEY `id_produto` (`id_produto`),
  KEY `id_filme` (`id_filme`),
  CONSTRAINT `avaliacao_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id_usuario`),
  CONSTRAINT `avaliacao_ibfk_2` FOREIGN KEY (`id_produto`) REFERENCES `bomboniere` (`id_produto`),
  CONSTRAINT `avaliacao_ibfk_3` FOREIGN KEY (`id_filme`) REFERENCES `filme` (`id_filme`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `avaliacao`
--

LOCK TABLES `avaliacao` WRITE;
/*!40000 ALTER TABLE `avaliacao` DISABLE KEYS */;
INSERT INTO `avaliacao` VALUES (1,8,'Gostei do combo! A pipoca veio quentinha e a porção foi suficiente para acompanhar o filme. Só achei que o refrigerante poderia ser um pouco maior.',1,3,NULL),(2,9,'A história me prendeu do começo ao fim. As cenas no espaço são impressionantes e a trilha sonora deixa tudo ainda mais emocionante.',2,NULL,6),(3,10,'Cenas de ação incríveis e trilha sonora marcante.',3,NULL,1),(4,7,'Divertido para ver em família, mas achei um pouco longo.',4,NULL,2),(5,6,'Pipoca doce muito boa, mas a porção é pequena.',5,4,NULL),(6,9,'Emocionante do início ao fim, recomendo levar lenços.',6,NULL,4),(7,8,'O Quarteto Fantástico rendeu para a família toda.',7,2,NULL),(8,5,'Suspense bom, mas o final é previsível.',8,NULL,8),(9,10,'Combo perfeito para casal, a pipoca meio a meio é ótima.',9,3,NULL),(10,7,'Terror bem construído, deu sustos de verdade.',10,NULL,9);
/*!40000 ALTER TABLE `avaliacao` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `beneficios`
--

DROP TABLE IF EXISTS `beneficios`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `beneficios` (
  `id_beneficio` int NOT NULL AUTO_INCREMENT,
  `nome_beneficio` varchar(50) NOT NULL,
  `pontos_beneficio` int NOT NULL,
  `descricao_beneficio` varchar(120) NOT NULL,
  `beneficio_ativo` varchar(10) NOT NULL,
  PRIMARY KEY (`id_beneficio`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `beneficios`
--

LOCK TABLES `beneficios` WRITE;
/*!40000 ALTER TABLE `beneficios` DISABLE KEYS */;
INSERT INTO `beneficios` VALUES (1,'Pipoca pequena Grátis',50,'Retirada na bomboniere do cinema','SIM'),(2,'Refrigerante Grátis (lata)',70,'Refrigerante de lata, retirar no cinema','SIM'),(3,'Desconto no ingresso',90,'Ganhe R$10 de desconto no seu próximo ingresso','SIM'),(4,'Pipoca média Grátis',120,'Pipoca salgada ou doce tamanho M, retirar no cinema','SIM'),(5,'Combo Solo Grátis',200,'Um Combo Solo completo, retirar no cinema','SIM'),(6,'Desconto maior no ingresso',160,'Ganhe R$20 de desconto no seu próximo ingresso','SIM'),(7,'Ingresso Grátis',300,'Um ingresso grátis para qualquer sessão','SIM'),(8,'Chocolate Grátis',60,'Um chocolate à escolha, retirar no cinema','SIM'),(9,'Upgrade de Pipoca',100,'Upgrade da pipoca para o tamanho grande','SIM'),(10,'Desconto na bomboniere',130,'Ganhe 15% de desconto em qualquer produto da bomboniere','NÃO');
/*!40000 ALTER TABLE `beneficios` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `bomboniere`
--

DROP TABLE IF EXISTS `bomboniere`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `bomboniere` (
  `id_produto` int NOT NULL AUTO_INCREMENT,
  `nome_produto` varchar(50) NOT NULL,
  `valor_produto` decimal(10,2) NOT NULL,
  `descricao_produto` varchar(250) NOT NULL,
  `categoria_produto` varchar(50) NOT NULL,
  PRIMARY KEY (`id_produto`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `bomboniere`
--

LOCK TABLES `bomboniere` WRITE;
/*!40000 ALTER TABLE `bomboniere` DISABLE KEYS */;
INSERT INTO `bomboniere` VALUES (1,'Combo Solo',23.90,'Pipoca salgada tamanho M + 1 bebida lata + 1 chocolate','Individual'),(2,'Quarteto Fantástico',69.90,'Caixa com 4 porções estilizadas (2 salgadas + 2 doces) + 4 bebidas lata + 2 doces','Família'),(3,'Romance Misto',38.90,'Balde meio a meio tamanho G (metade salgada e metade doce) + 2 bebidas lata + 1 chocolate','Casal'),(4,'Doce Magia',19.90,'Pipoca doce colorida ou de Leite ninho tamanho P + 1 achocolatado/suco + 1 pacote de fini','Infantil'),(5,'Vinho a Dois',98.90,'Pipoca artesanal de azeite de trufas e parmesão tamanho G + 1 garrafa de vinho de 750ml + 1 tábua de frios','Especiais'),(6,'Combo Nachos Supremos',34.90,'Porção generosa de nachos crocantes com molho de queijo cheddar quente, guacamole e chilli com carne + 1 bebida artesanal ou lata','Premium'),(7,'Pipoca Grande Salgada',18.90,'Pipoca salgada tamanho G','Individual'),(8,'Refrigerante 700ml',12.90,'Refrigerante gelado de 700ml, sabores variados','Bebidas'),(9,'Chocolate Sortido',9.90,'Barra de chocolate ao leite ou meio amargo','Doces'),(10,'Combo Dupla',44.90,'Pipoca tamanho G + 2 refrigerantes de 500ml','Casal');
/*!40000 ALTER TABLE `bomboniere` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cinema`
--

DROP TABLE IF EXISTS `cinema`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cinema` (
  `id_cinema` int NOT NULL AUTO_INCREMENT,
  `endereco_cinema` varchar(250) NOT NULL,
  PRIMARY KEY (`id_cinema`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cinema`
--

LOCK TABLES `cinema` WRITE;
/*!40000 ALTER TABLE `cinema` DISABLE KEYS */;
INSERT INTO `cinema` VALUES (1,'Rua Beira Mar N 100, SAO CAETANO DO SUL - SP'),(2,'Avenida Europa N 207, BARRA DA TIJUCA - RJ'),(3,'Rua Atlântica N 340, SAO BERNARDO DO CAMPO - SP'),(4,'Avenida Paulista N 1500, BELA VISTA - SP'),(5,'Rua das Flores N 55, CENTRO - CURITIBA - PR'),(6,'Avenida Brasil N 980, CENTRO - BELO HORIZONTE - MG'),(7,'Rua do Porto N 12, CENTRO - SANTOS - SP'),(8,'Avenida Beira Rio N 700, CENTRO - PORTO ALEGRE - RS'),(9,'Rua das Palmeiras N 245, BOA VIAGEM - RECIFE - PE'),(10,'Avenida Sete de Setembro N 410, CENTRO - SALVADOR - BA');
/*!40000 ALTER TABLE `cinema` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cupom`
--

DROP TABLE IF EXISTS `cupom`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cupom` (
  `id_cupom` int NOT NULL AUTO_INCREMENT,
  `codigo` varchar(30) NOT NULL,
  `tipo_desconto` enum('Porcentagem','Fixo') NOT NULL,
  `valor_desconto` decimal(10,2) NOT NULL,
  `valor_minimo_pedido` decimal(10,2) NOT NULL DEFAULT '0.00',
  `data_validade` date NOT NULL,
  `cupom_ativo` char(3) NOT NULL DEFAULT 'Sim',
  PRIMARY KEY (`id_cupom`),
  UNIQUE KEY `codigo` (`codigo`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cupom`
--

LOCK TABLES `cupom` WRITE;
/*!40000 ALTER TABLE `cupom` DISABLE KEYS */;
INSERT INTO `cupom` VALUES (1,'BEMVINDO10','Porcentagem',10.00,30.00,'2027-12-31','Sim'),(2,'LUX5','Fixo',5.00,20.00,'2027-06-30','Sim'),(3,'CINEMA15','Porcentagem',15.00,50.00,'2027-03-31','Sim'),(4,'COMBO10','Fixo',10.00,60.00,'2027-08-31','Sim'),(5,'FAMILIA20','Porcentagem',20.00,100.00,'2027-07-31','Sim'),(6,'LUX25','Fixo',25.00,150.00,'2027-05-31','Sim'),(7,'VOLTE10','Porcentagem',10.00,0.00,'2027-09-30','Sim'),(8,'ESPECIAL30','Fixo',30.00,200.00,'2027-12-31','Não'),(9,'PROMO2025','Porcentagem',5.00,25.00,'2025-12-31','Sim'),(10,'ANTIGO15','Fixo',15.00,80.00,'2025-06-30','Não');
/*!40000 ALTER TABLE `cupom` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `endereco_entrega`
--

DROP TABLE IF EXISTS `endereco_entrega`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `endereco_entrega` (
  `id_endereco` int NOT NULL AUTO_INCREMENT,
  `id_usuario` int NOT NULL,
  `logradouro` varchar(150) NOT NULL,
  `numero` varchar(20) NOT NULL,
  `complemento` varchar(50) DEFAULT NULL,
  `bairro` varchar(60) NOT NULL,
  `cidade` varchar(60) NOT NULL,
  `estado` char(2) NOT NULL,
  `cep` varchar(8) NOT NULL,
  PRIMARY KEY (`id_endereco`),
  KEY `id_usuario` (`id_usuario`),
  CONSTRAINT `endereco_entrega_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id_usuario`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `endereco_entrega`
--

LOCK TABLES `endereco_entrega` WRITE;
/*!40000 ALTER TABLE `endereco_entrega` DISABLE KEYS */;
INSERT INTO `endereco_entrega` VALUES (1,1,'Rua das Acácias','120','Apto 31','Centro','São Caetano do Sul','SP','09510100'),(2,2,'Avenida das Américas','3500','Bloco B','Barra da Tijuca','Rio de Janeiro','RJ','22640102'),(3,3,'Rua Atlântica','45',NULL,'Jardim do Mar','São Bernardo do Campo','SP','09750000'),(4,4,'Avenida Paulista','1578','Sala 12','Bela Vista','São Paulo','SP','01310200'),(5,5,'Rua XV de Novembro','230',NULL,'Centro','Curitiba','PR','80020310'),(6,6,'Avenida Afonso Pena','980','Apto 504','Funcionários','Belo Horizonte','MG','30130003'),(7,7,'Rua do Comércio','77',NULL,'Vila Mathias','Santos','SP','11015020'),(8,8,'Avenida Ipiranga','1200','Casa 2','Praia de Belas','Porto Alegre','RS','90160090'),(9,9,'Rua da Aurora','415','Apto 12','Boa Vista','Recife','PE','50050000'),(10,10,'Avenida Sete de Setembro','2100',NULL,'Barra','Salvador','BA','40140110');
/*!40000 ALTER TABLE `endereco_entrega` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `filme`
--

DROP TABLE IF EXISTS `filme`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `filme` (
  `id_filme` int NOT NULL AUTO_INCREMENT,
  `nome_filme` varchar(100) NOT NULL,
  `data_filme` date NOT NULL,
  `duracao_filme` int NOT NULL,
  `class_filme` varchar(10) NOT NULL,
  `gen_filme` varchar(50) NOT NULL,
  `exibicao_filme` varchar(10) NOT NULL,
  `sinope` varchar(255) NOT NULL,
  `imagem_capa` varchar(250) NOT NULL,
  `tipo_conteudo` enum('Filme','Serie') NOT NULL,
  PRIMARY KEY (`id_filme`)
) ENGINE=InnoDB AUTO_INCREMENT=98 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `filme`
--

LOCK TABLES `filme` WRITE;
/*!40000 ALTER TABLE `filme` DISABLE KEYS */;
INSERT INTO `filme` VALUES (1,'O Exterminador do Futuro','1984-10-26',107,'16','ação','Cinema','','','Filme'),(2,'Como Treinar o Seu Dragão - Live Action','2025-06-05',98,'livre','aventura','Cinema','','','Filme'),(3,'Um Maluco no Golfe 2','2025-07-25',114,'14','comédia','Streaming','','','Filme'),(4,'Extraordinário','2017-11-07',113,'10 anos','drama','Cinema','','','Filme'),(5,'A Fantástica Fábrica de Chocolate','2005-07-22',115,'livre','fantasia','Cinema','','','Filme'),(6,'Interestelar','2014-11-06',169,'10','ficção científica','Cinema','','','Filme'),(7,'O Último Nascer do Sol','2026-08-26',106,'16','romance','Cinema','','','Filme'),(8,'A Empregada','2025-12-19',131,'16','suspense','Cinema','','','Filme'),(9,'Invocação do Mal','2013-07-19',112,'16','terror','Cinema','','','Filme'),(10,'Divertida Mente 2','2024-06-20',96,'livre','animação','Streaming','','','Filme'),(11,'Duna: Parte Dois','2024-02-29',166,'14','ficção científica','Cinema','','','Filme'),(12,'O Auto da Compadecida 2','2024-12-25',112,'12','comédia','Streaming','','','Filme'),(13,'Como Perder um Homem em 10 Dias','2003-02-07',116,'12','romance','Streaming','Uma jornalista e um publicitário começam um romance por apostas secretas.','Imagens/p-romance/como perder um homem em 10 dias.jpg','Filme'),(14,'Como Eu Era Antes de Você','2016-06-03',110,'12','romance','Streaming','Uma cuidadora muda sua visão de mundo ao conhecer um jovem tetraplégico.','Imagens/p-romance/comom eu era antes de voce.jpg','Filme'),(15,'De Repente 30','2004-04-23',98,'12','romance','Streaming','Uma adolescente acorda no corpo de sua versão adulta e reconsidera os sonhos que tinha.','Imagens/p-romance/de repente 30.jpg','Filme'),(16,'Paixão e Velocidade','2023-06-16',100,'12','romance','Streaming','Uma mecânica e um piloto fingem ser um casal e acabam aproximando-se de verdade.','Imagens/p-romance/paixao e velocidade.jpg','Filme'),(17,'Seven: Os Sete Crimes Capitais','1995-12-15',127,'16','suspense','Streaming','Dois detetives investigam uma sequência de assassinatos inspirados nos sete pecados capitais.','Imagens/p-suspense/Seven - Os Sete Crimes Capitais.jpg','Filme'),(18,'A Mulher Secreta','2024-03-15',100,'14','suspense','Streaming','Uma mulher com amnésia descobre um passado familiar escondido e busca entender sua verdadeira identidade.','Imagens/p-suspense/a mulher secreta.jpg','Filme'),(19,'Garota Exemplar','2014-10-03',149,'16','suspense','Streaming','O desaparecimento de uma mulher torna seu marido suspeito e revela um casamento cheio de segredos.','Imagens/p-suspense/garota exemplar.jpg','Filme'),(20,'Ilha do Medo','2010-02-19',138,'16','suspense','Streaming','Dois agentes investigam um desaparecimento em um hospital psiquiátrico isolado.','Imagens/p-suspense/ilha do medo.jpg','Filme'),(21,'O Sexto Sentido','1999-08-06',107,'14','suspense','Streaming','Um psicólogo atende um menino que afirma enxergar pessoas mortas.','Imagens/p-suspense/o sexto sentido.jpg','Filme'),(22,'Observadores','2024-06-07',102,'14','suspense','Streaming','Uma jovem fica presa em uma floresta onde criaturas misteriosas vigiam desconhecidos durante a noite.','Imagens/p-suspense/observadores.jpg','Filme'),(23,'Socorro','2026-01-30',100,'16','suspense','Streaming','Uma funcionária e seu chefe ficam isolados e precisam sobreviver a uma situação extrema.','Imagens/p-suspense/socorro.jpg','Filme'),(24,'Um Lugar Silencioso','2018-04-06',90,'14','suspense','Streaming','Uma família vive em silêncio para não atrair criaturas que caçam pelo som.','Imagens/p-suspense/um lugar silencioso.jpg','Filme'),(25,'O Chamado 3','2017-02-03',102,'14','terror','Streaming','Uma gravação amaldiçoada volta a espalhar medo entre pessoas que assistem ao vídeo.','Imagens/p-terror/o chamado 3.jpg','Filme'),(26,'Pânico 1','1996-12-20',111,'16','terror','Streaming','Um assassino mascarado passa a perseguir adolescentes em uma pequena cidade.','Imagens/p-terror/panico 1.jpg','Filme'),(27,'Poltergeist','1982-06-04',114,'14','terror','Streaming','Uma família enfrenta fenômenos sobrenaturais após forças misteriosas invadirem sua casa.','Imagens/p-terror/potergaist.jpg','Filme'),(28,'The Boys','2019-07-26',60,'18','ação','Streaming','Um grupo tenta deter super-heróis poderosos que escondem corrupção e violência.','Imagens/s.acao/The Boys.jpg','Serie'),(29,'Até o Último Homem','2025-01-10',50,'16','ação','Streaming','Em meio a conflitos intensos, personagens lutam para sobreviver e proteger seus companheiros.','Imagens/s.acao/até o último homem.jpg','Serie'),(30,'Cobra Kai','2018-05-02',30,'14','ação','Streaming','Velhas rivalidades do caratê retornam quando antigos adversários passam a treinar novos alunos.','Imagens/s.acao/cobra kai.jpg','Serie'),(31,'Demolidor (Daredevil)','2015-04-10',55,'16','ação','Streaming','Um advogado cego combate o crime nas ruas de Nova York como vigilante mascarado.','Imagens/s.acao/daredevil.jpg','Serie'),(32,'Gangs of London','2020-04-23',60,'18','ação','Streaming','A morte de um chefe criminoso provoca uma guerra entre facções em Londres.','Imagens/s.acao/gangs of london.jpg','Serie'),(33,'La Casa de Papel','2017-05-02',50,'16','ação','Streaming','Criminosos organizam um grande assalto sob a liderança de um estrategista conhecido como Professor.','Imagens/s.acao/la casa de papel.jpg','Serie'),(34,'Lupin','2021-01-08',45,'14','ação','Streaming','Um ladrão elegante usa disfarces e planos engenhosos para vingar uma injustiça contra seu pai.','Imagens/s.acao/lupin.jpg','Serie'),(35,'Reacher','2022-02-04',50,'16','ação','Streaming','Um ex-policial militar investiga crimes e enfrenta conspirações ao viajar pelos Estados Unidos.','Imagens/s.acao/reacher.jpg','Serie'),(36,'Percy Jackson e os Olimpianos','2023-12-20',45,'12','aventura','Streaming','Um jovem semideus embarca em missões para evitar conflitos entre os deuses do Olimpo.','Imagens/s.aventura/Percy Jackson e os Olimpianos.jpg','Serie'),(37,'Fallout','2024-04-10',60,'18','aventura','Streaming','Sobreviventes de um mundo nuclear enfrentam facções e perigos em uma sociedade pós-apocalíptica.','Imagens/s.aventura/fallout.jpg','Serie'),(38,'Game of Thrones','2011-04-17',55,'18','aventura','Streaming','Famílias nobres disputam o poder sobre Westeros enquanto uma ameaça cresce ao norte.','Imagens/s.aventura/game of thrones.jpg','Serie'),(39,'Lost','2004-09-22',45,'14','aventura','Streaming','Sobreviventes de um acidente aéreo descobrem mistérios em uma ilha aparentemente deserta.','Imagens/s.aventura/lost.jpg','Serie'),(40,'Star Wars: The Mandalorian','2019-11-12',40,'12','aventura','Streaming','Um caçador de recompensas atravessa a galáxia protegendo uma criança muito especial.','Imagens/s.aventura/star wars mandalorian.jpg','Serie'),(41,'The Last of Us','2023-01-15',55,'18','aventura','Streaming','Um homem e uma adolescente atravessam um mundo devastado por uma infecção.','Imagens/s.aventura/the last of us.jpg','Serie'),(42,'The Witcher','2019-12-20',60,'18','aventura','Streaming','Um caçador de monstros enfrenta conflitos políticos e destinos ligados à magia.','Imagens/s.aventura/the witcher.jpg','Serie'),(43,'Vikings','2013-03-03',45,'18','aventura','Streaming','O guerreiro Ragnar Lothbrok lidera expedições que transformam o destino de seu povo.','Imagens/s.aventura/vikings.jpg','Serie'),(44,'Scrubs','2001-10-02',22,'14','comédia','Streaming','Jovens médicos enfrentam os desafios da profissão entre amizade, humor e emoção.','Imagens/s.comedia/Scrubs.jpg','Serie'),(45,'Brooklyn Nine-Nine','2013-09-17',22,'12','comédia','Streaming','Detetives de uma delegacia do Brooklyn resolvem crimes em meio a situações cômicas.','Imagens/s.comedia/brooklyn nine-nine.jpg','Serie'),(46,'Como Eu Conheci Sua Mãe','2005-09-19',22,'14','comédia','Streaming','Um grupo de amigos vive romances e confusões enquanto Ted procura o amor.','Imagens/s.comedia/como eu conheci sua mae.jpg','Serie'),(47,'Friends','1994-09-22',22,'12','comédia','Streaming','Seis amigos compartilham amores, trabalho e dilemas cotidianos em Nova York.','Imagens/s.comedia/friends.jpg','Serie'),(48,'Modern Family','2009-09-23',22,'12','comédia','Streaming','Três famílias ligadas por parentesco vivem situações divertidas e emocionantes.','Imagens/s.comedia/modern family.jpg','Serie'),(49,'The Big Bang Theory','2007-09-24',22,'12','comédia','Streaming','Cientistas e seus amigos enfrentam relacionamentos e situações cômicas do dia a dia.','Imagens/s.comedia/the big bang theory.jpg','Serie'),(50,'The Office','2005-03-24',22,'14','comédia','Streaming','Funcionários de uma empresa de papel transformam a rotina do escritório em uma comédia.','Imagens/s.comedia/the office.jpg','Serie'),(51,'Todo Mundo Odeia o Chris','2005-09-22',22,'12','comédia','Streaming','Um adolescente enfrenta a escola, a família e as dificuldades de crescer no Brooklyn.','Imagens/s.comedia/todo mundo odeia o chris.jpg','Serie'),(52,'Anne with an E','2017-05-12',45,'10','drama','Streaming','Uma órfã criativa transforma a vida de uma família e de uma pequena comunidade.','Imagens/s.drama/Anne with an E.jpg','Serie'),(53,'Breaking Bad','2008-01-20',47,'18','drama','Streaming','Um professor de química passa a fabricar drogas e se envolve com o crime organizado.','Imagens/s.drama/breaking bad.jpg','Serie'),(54,'Gossip Girl','2007-09-19',42,'14','drama','Streaming','A vida de jovens ricos de Manhattan é exposta por uma blogueira anônima.','Imagens/s.drama/gossip girl.jpg','Serie'),(55,'O Gambito da Rainha','2020-10-23',60,'16','drama','Streaming','Uma jovem prodígio busca reconhecimento no xadrez enquanto enfrenta conflitos pessoais.','Imagens/s.drama/o gambito da rainha.jpg','Serie'),(56,'Sons of Anarchy','2008-09-03',55,'18','drama','Streaming','Membros de um motoclube criminoso enfrentam disputas de poder e lealdade familiar.','Imagens/s.drama/sons of anarchy.jpg','Serie'),(57,'Sterling Point','2025-06-20',45,'14','drama','Streaming','Uma jovem herda com o irmão uma ilha misteriosa e tenta desvendar segredos de família.','Imagens/s.drama/sterling point.jpg','Serie'),(58,'Succession','2018-06-03',60,'16','drama','Streaming','Herdeiros de um conglomerado de mídia disputam o controle dos negócios da família.','Imagens/s.drama/succession.jpg','Serie'),(59,'The Chosen','2019-04-21',45,'12','drama','Streaming','A trajetória de Jesus é apresentada a partir das pessoas que o encontram e passam a segui-lo.','Imagens/s.drama/the chosen.jpg','Serie'),(60,'Cidade Invisível','2021-02-05',40,'14','fantasia','Streaming','Um policial investiga mortes misteriosas e descobre criaturas do folclore brasileiro.','Imagens/s.fantasia/Cidade Invisível.jpg','Serie'),(61,'Fate: A Saga Winx','2021-01-22',50,'14','fantasia','Streaming','Jovens fadas aprendem magia enquanto enfrentam perigos em um internato sobrenatural.','Imagens/s.fantasia/fate.jpg','Serie'),(62,'Lucifer','2016-01-25',45,'16','fantasia','Streaming','O senhor do inferno muda-se para Los Angeles e ajuda a polícia a solucionar crimes.','Imagens/s.fantasia/lucifer.jpg','Serie'),(63,'O Último Mestre do Ar','2005-02-21',23,'livre','fantasia','Streaming','Um menino capaz de dominar os elementos tenta restaurar o equilíbrio entre as nações.','Imagens/s.fantasia/o último mestre do ar.jpg','Serie'),(64,'Supernatural','2005-09-13',43,'16','fantasia','Streaming','Dois irmãos caçam criaturas sobrenaturais e investigam mistérios por todo o país.','Imagens/s.fantasia/supernatural.jpg','Serie'),(65,'Teen Wolf','2011-06-05',45,'14','fantasia','Streaming','Um adolescente transformado em lobisomem precisa equilibrar escola, amizades e novos perigos.','Imagens/s.fantasia/teen wolf.jpg','Serie'),(66,'Wandinha','2022-11-23',50,'14','fantasia','Streaming','Wandinha Addams investiga crimes e mistérios enquanto estuda em uma escola peculiar.','Imagens/s.fantasia/wandinha.jpg','Serie'),(67,'Doctor Who','2005-03-26',45,'12','ficção científica','Streaming','Um viajante do tempo alienígena explora o universo e resolve crises com diferentes companheiros.','Imagens/s.ficcao/Doctor Who.jpg','Serie'),(68,'Black Mirror','2011-12-04',60,'16','ficção científica','Streaming','Histórias independentes exploram os efeitos inquietantes da tecnologia sobre a sociedade.','Imagens/s.ficcao/black mirror.jpg','Serie'),(69,'Dark','2017-12-01',55,'16','ficção científica','Streaming','O desaparecimento de crianças revela conexões entre famílias e viagens no tempo.','Imagens/s.ficcao/dark.jpg','Serie'),(70,'Fringe','2008-09-09',45,'14','ficção científica','Streaming','Uma equipe investiga fenômenos científicos inexplicáveis e realidades paralelas.','Imagens/s.ficcao/fringe.jpg','Serie'),(71,'Westworld','2016-10-02',60,'18','ficção científica','Streaming','Andróides de um parque temático começam a questionar sua existência e os humanos que os controlam.','Imagens/s.ficcao/o caos domina westworld.jpg','Serie'),(72,'Severance','2022-02-18',50,'16','ficção científica','Streaming','Funcionários dividem suas memórias profissionais e pessoais e descobrem segredos perturbadores.','Imagens/s.ficcao/severance.jpg','Serie'),(73,'Stranger Things','2016-07-15',50,'14','ficção científica','Streaming','Amigos enfrentam experiências secretas e forças sobrenaturais em uma pequena cidade.','Imagens/s.ficcao/stranger things.jpg','Serie'),(74,'The X-Files','1993-09-10',45,'14','ficção científica','Streaming','Agentes do FBI investigam fenômenos paranormais e possíveis conspirações extraterrestres.','Imagens/s.ficcao/the x files.jpg','Serie'),(75,'Heartstopper','2022-04-22',35,'14','romance','Streaming','Dois adolescentes desenvolvem um romance enquanto descobrem a própria identidade e enfrentam desafios escolares.','Imagens/s.romance/Heartstopper.jpg','Serie'),(76,'Maxton Hall: O Mundo Entre Nós','2024-05-09',45,'16','romance','Streaming','Uma estudante bolsista e um herdeiro rico se aproximam em uma escola de elite.','Imagens/s.romance/Maxton Hall - O Mundo Entre Nós.jpg','Serie'),(77,'Pousando no Amor','2019-12-14',70,'12','romance','Streaming','Uma empresária sul-coreana cai por acidente na Coreia do Norte e conhece um oficial do exército.','Imagens/s.romance/Pousando no Amor.jpg','Serie'),(78,'Bridgerton','2020-12-25',60,'18','romance','Streaming','Jovens da alta sociedade londrina vivem romances e disputas durante a temporada de casamentos.','Imagens/s.romance/bridgerton.jpg','Serie'),(79,'Minha Vida com a Família Walter','2024-10-09',40,'12','romance','Streaming','Uma adolescente muda-se para uma casa cheia de irmãos e tenta reconstruir sua vida.','Imagens/s.romance/minha vida com a família walter.jpg','Serie'),(80,'O Tempo Traz Você pra Mim','2022-09-23',40,'12','romance','Streaming','Uma mulher enlutada viaja misteriosamente ao passado e reencontra um rosto conhecido.','Imagens/s.romance/o tempo traz você pra mim.jpg','Serie'),(81,'Off Campus','2026-05-13',45,'16','romance','Streaming','Uma estudante de música e um astro do hóquei fingem uma parceria que acaba virando romance.','Imagens/s.romance/off campus.jpg','Serie'),(82,'Ransom Canyon','2025-04-17',50,'16','romance','Streaming','Famílias de uma comunidade rural do Texas enfrentam rivalidades, paixões e segredos.','Imagens/s.romance/ransom canyon.jpg','Serie'),(83,'O Agente Noturno','2023-03-23',50,'14','suspense','Streaming','Um agente do FBI atende uma chamada inesperada e se envolve em uma conspiração nacional.','Imagens/s.suspense/O Agente Noturno.jpg','Serie'),(84,'O Enfermeiro da Noite','2025-09-11',45,'16','suspense','Streaming','Suspeitas envolvendo mortes em um hospital colocam profissionais de saúde diante de um mistério perturbador.','Imagens/s.suspense/O Enfermeiro da Noite.jpg','Serie'),(85,'The Residence','2025-03-20',50,'14','suspense','Streaming','Uma detetive investiga uma morte misteriosa durante uma recepção na Casa Branca.','Imagens/s.suspense/The Residence.jpg','Serie'),(86,'Diários de um Vampiro','2009-09-10',43,'14','suspense','Streaming','Uma adolescente se envolve com dois irmãos vampiros em uma cidade cheia de segredos.','Imagens/s.suspense/diarios de um vampiro.jpg','Serie'),(87,'Hannibal','2013-04-04',45,'18','suspense','Streaming','Um investigador do FBI aproxima-se de um psiquiatra brilhante e perigosamente manipulador.','Imagens/s.suspense/hannibal.jpg','Serie'),(88,'Prison Break: Em Busca da Verdade','2005-08-29',45,'16','suspense','Streaming','Um engenheiro arma um plano para tirar o irmão de uma prisão de segurança máxima.','Imagens/s.suspense/prison break- em busca da verdade.jpg','Serie'),(89,'Yellowjackets','2021-11-14',55,'18','suspense','Streaming','Sobreviventes de um acidente aéreo lidam com traumas e segredos anos depois.','Imagens/s.suspense/yellowjackets.jpg','Serie'),(90,'You','2018-09-09',45,'18','suspense','Streaming','Um homem obsessivo usa manipulação e vigilância para se aproximar das pessoas que deseja.','Imagens/s.suspense/you.jpg','Serie'),(91,'American Horror Stories','2021-07-15',45,'18','terror','Streaming','Episódios independentes apresentam histórias macabras com fenômenos e personagens aterrorizantes.','Imagens/s.terror/American Horror Stories 10.jpg','Serie'),(92,'Clube do Terror','2022-10-07',45,'16','terror','Streaming','Jovens reunidos para compartilhar histórias assustadoras veem seus medos ganhar vida.','Imagens/s.terror/clube-do-terror.jpg','Serie'),(93,'Creeped Out','2017-10-01',25,'12','terror','Streaming','Uma figura misteriosa apresenta contos sombrios sobre escolhas e acontecimentos sobrenaturais.','Imagens/s.terror/creeped out.jpg','Serie'),(94,'Diário de Horrores','2025-10-15',45,'16','terror','Streaming','Histórias independentes colocam jovens diante de eventos estranhos e assustadores.','Imagens/s.terror/diario de horrores.jpg','Serie'),(95,'It: Bem-Vindo à Derry','2025-10-26',55,'18','terror','Streaming','Uma cidade marcada pelo mal enfrenta o retorno de eventos aterrorizantes ligados a uma entidade antiga.','Imagens/s.terror/it a.jpg','Serie'),(96,'Missa da Meia-Noite','2021-09-24',50,'16','terror','Streaming','A chegada de um padre a uma ilha isolada desencadeia milagres e acontecimentos inquietantes.','Imagens/s.terror/missa-meia-noite.jpg','Serie'),(97,'Origem','2022-02-20',50,'16','terror','Streaming','Moradores de uma cidade misteriosa tentam sobreviver a criaturas que aparecem durante a noite.','Imagens/s.terror/origem.jpg','Serie');
/*!40000 ALTER TABLE `filme` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `filme_lista`
--

DROP TABLE IF EXISTS `filme_lista`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `filme_lista` (
  `id_filme` int NOT NULL,
  `id_lista` int NOT NULL,
  KEY `id_filme` (`id_filme`),
  KEY `id_lista` (`id_lista`),
  CONSTRAINT `filme_lista_ibfk_1` FOREIGN KEY (`id_filme`) REFERENCES `filme` (`id_filme`),
  CONSTRAINT `filme_lista_ibfk_2` FOREIGN KEY (`id_lista`) REFERENCES `lista` (`id_lista`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `filme_lista`
--

LOCK TABLES `filme_lista` WRITE;
/*!40000 ALTER TABLE `filme_lista` DISABLE KEYS */;
INSERT INTO `filme_lista` VALUES (2,1),(3,2),(5,3),(6,4),(11,4),(1,5),(3,5),(4,6),(1,7),(5,7),(2,8),(10,8),(8,9),(9,9),(6,10),(11,10);
/*!40000 ALTER TABLE `filme_lista` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `historico_visualizacao`
--

DROP TABLE IF EXISTS `historico_visualizacao`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `historico_visualizacao` (
  `id_visualizacao` int NOT NULL AUTO_INCREMENT,
  `data_visualizacao` date NOT NULL,
  `id_usuario` int NOT NULL,
  `id_filme` int NOT NULL,
  PRIMARY KEY (`id_visualizacao`),
  KEY `id_usuario` (`id_usuario`),
  KEY `id_filme` (`id_filme`),
  CONSTRAINT `historico_visualizacao_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id_usuario`),
  CONSTRAINT `historico_visualizacao_ibfk_2` FOREIGN KEY (`id_filme`) REFERENCES `filme` (`id_filme`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `historico_visualizacao`
--

LOCK TABLES `historico_visualizacao` WRITE;
/*!40000 ALTER TABLE `historico_visualizacao` DISABLE KEYS */;
INSERT INTO `historico_visualizacao` VALUES (1,'2026-09-16',2,5),(2,'2026-04-15',3,6),(3,'2026-07-28',1,9),(4,'2026-10-02',1,6),(5,'2026-10-20',4,11),(6,'2026-08-12',5,3),(7,'2026-09-30',6,10),(8,'2026-11-01',7,12),(9,'2026-11-05',8,10),(10,'2026-10-11',9,3),(11,'2026-11-08',10,12),(12,'2026-10-25',2,3),(13,'2026-11-10',4,10);
/*!40000 ALTER TABLE `historico_visualizacao` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ingressos`
--

DROP TABLE IF EXISTS `ingressos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ingressos` (
  `id_ingresso` int NOT NULL AUTO_INCREMENT,
  `assento` varchar(10) NOT NULL,
  `valor_ingresso` decimal(10,2) NOT NULL,
  `id_venda` int NOT NULL,
  `id_sessao` int NOT NULL,
  PRIMARY KEY (`id_ingresso`),
  UNIQUE KEY `id_sessao` (`id_sessao`,`assento`),
  KEY `id_venda` (`id_venda`),
  CONSTRAINT `ingressos_ibfk_1` FOREIGN KEY (`id_venda`) REFERENCES `venda` (`id_venda`),
  CONSTRAINT `ingressos_ibfk_2` FOREIGN KEY (`id_sessao`) REFERENCES `sessao` (`id_sessao`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ingressos`
--

LOCK TABLES `ingressos` WRITE;
/*!40000 ALTER TABLE `ingressos` DISABLE KEYS */;
INSERT INTO `ingressos` VALUES (1,'N5',30.00,1,1),(2,'N6',30.00,1,1),(3,'C4',20.00,2,2),(4,'C5',20.00,2,2),(5,'F7',20.00,3,3),(6,'A1',25.00,4,4),(7,'A2',25.00,4,4),(8,'B3',25.00,5,5),(9,'D7',22.50,6,6),(10,'D8',22.50,6,6),(11,'D9',22.50,6,6),(12,'E1',28.00,7,7),(13,'E2',28.00,7,7),(14,'G4',25.00,9,8),(15,'G5',25.00,9,8),(16,'H10',28.00,10,9);
/*!40000 ALTER TABLE `ingressos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `item_venda`
--

DROP TABLE IF EXISTS `item_venda`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `item_venda` (
  `id_item` int NOT NULL AUTO_INCREMENT,
  `id_venda` int NOT NULL,
  `id_produto` int NOT NULL,
  `quantidade` int NOT NULL DEFAULT '1',
  `valor_unitario` decimal(10,2) NOT NULL,
  PRIMARY KEY (`id_item`),
  KEY `id_venda` (`id_venda`),
  KEY `id_produto` (`id_produto`),
  CONSTRAINT `item_venda_ibfk_1` FOREIGN KEY (`id_venda`) REFERENCES `venda` (`id_venda`),
  CONSTRAINT `item_venda_ibfk_2` FOREIGN KEY (`id_produto`) REFERENCES `bomboniere` (`id_produto`)
) ENGINE=InnoDB AUTO_INCREMENT=52 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `item_venda`
--

LOCK TABLES `item_venda` WRITE;
/*!40000 ALTER TABLE `item_venda` DISABLE KEYS */;
INSERT INTO `item_venda` VALUES (11,4,1,1,23.90),(12,1,2,1,69.90),(13,2,3,1,38.90),(14,7,4,1,19.90),(15,8,5,1,98.90),(16,5,6,1,34.90),(17,9,7,1,18.90),(18,10,10,1,44.90);
/*!40000 ALTER TABLE `item_venda` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `lista`
--

DROP TABLE IF EXISTS `lista`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `lista` (
  `id_lista` int NOT NULL AUTO_INCREMENT,
  `nome_lista` varchar(100) NOT NULL,
  `descricao_lista` varchar(100) DEFAULT NULL,
  `data_criacao` date NOT NULL,
  `id_usuario` int NOT NULL,
  `exib_lista` varchar(10) NOT NULL,
  PRIMARY KEY (`id_lista`),
  KEY `id_usuario` (`id_usuario`),
  CONSTRAINT `lista_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id_usuario`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `lista`
--

LOCK TABLES `lista` WRITE;
/*!40000 ALTER TABLE `lista` DISABLE KEYS */;
INSERT INTO `lista` VALUES (1,'Assistir mais tarde',NULL,'2026-08-20',2,'Privado'),(2,'Assistir com a Lu','NÃO ASSISTIR SEM A LUNA, SE NÃO ELA FICA BRAVA','2026-02-27',1,'Privado'),(3,'Para não pensar','Momentos difíceis da vida onde só queremos assistir sem precisar raciocinar','2026-04-15',3,'Público'),(4,'Favoritos','Meus filmes favoritos de todos os tempos','2026-03-10',4,'Privado'),(5,'Fim de semana','Filmes para maratonar no sábado','2026-05-22',5,'Público'),(6,'Para chorar',NULL,'2026-06-30',6,'Privado'),(7,'Clássicos','Filmes antigos que preciso ver','2026-07-12',7,'Público'),(8,'Em família','Filmes para ver com as crianças','2026-09-01',8,'Público'),(9,'Terror e suspense',NULL,'2026-10-05',9,'Público'),(10,'Ficção científica','Viagens no espaço e no tempo','2026-10-28',10,'Público');
/*!40000 ALTER TABLE `lista` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pagamento`
--

DROP TABLE IF EXISTS `pagamento`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `pagamento` (
  `id_pagamento` int NOT NULL AUTO_INCREMENT,
  `forma_pagamento` varchar(30) NOT NULL,
  `status_pagamento` varchar(20) NOT NULL,
  `data_pagamento` date NOT NULL,
  `id_venda` int NOT NULL,
  PRIMARY KEY (`id_pagamento`),
  KEY `id_venda` (`id_venda`),
  CONSTRAINT `pagamento_ibfk_1` FOREIGN KEY (`id_venda`) REFERENCES `venda` (`id_venda`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pagamento`
--

LOCK TABLES `pagamento` WRITE;
/*!40000 ALTER TABLE `pagamento` DISABLE KEYS */;
INSERT INTO `pagamento` VALUES (1,'Pix','Concluído','2026-11-09',1),(2,'Cartão de Crédito','Concluído','2026-11-10',2),(3,'Pix','Concluído','2026-11-12',3),(4,'Cartão de Débito','Concluído','2026-11-13',4),(5,'Cartão de Crédito','Concluído','2026-11-14',5),(6,'Pix','Concluído','2026-11-15',6),(7,'Cartão de Crédito','Concluído','2026-11-16',7),(8,'Pix','Pendente','2026-11-16',8),(9,'Cartão de Débito','Concluído','2026-11-17',9),(10,'Cartão de Crédito','Recusado','2026-11-17',10);
/*!40000 ALTER TABLE `pagamento` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `plano`
--

DROP TABLE IF EXISTS `plano`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `plano` (
  `id_plano` int NOT NULL AUTO_INCREMENT,
  `descricao_plano` varchar(120) NOT NULL,
  `nome_plano` varchar(50) NOT NULL,
  `plano_ativo` varchar(30) NOT NULL,
  `valor_mensal` decimal(10,2) NOT NULL,
  `valor_anual` decimal(10,2) NOT NULL,
  PRIMARY KEY (`id_plano`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `plano`
--

LOCK TABLES `plano` WRITE;
/*!40000 ALTER TABLE `plano` DISABLE KEYS */;
INSERT INTO `plano` VALUES (1,'Assista ao catálogo de filmes disponíveis em casa e receba 5% de desconto em ingressos comprados pela LUX','Lux Play','Sim',19.90,199.00),(2,'Aproveite o catálogo de streaming, 10% de desconto em ingressos e 5% de desconto na bomboniere','Lux Plus','Sim',29.90,299.00),(3,'Tenha acesso ao catálogo de streaming, 15% de desconto em ingressos, 10% de desconto na bomboniere','Lux Prime','Sim',39.90,399.00),(4,'Plano descontinuado com desconto para estudantes em ingressos','Lux Estudante','Não',14.90,149.00),(5,'Plano descontinuado para até 4 pessoas com benefícios no cinema','Lux Família','Não',49.90,499.00),(6,'Plano descontinuado com vantagens em sessões noturnas','Lux Night','Não',24.90,249.00),(7,'Plano descontinuado com vantagens em sessões de fim de semana','Lux Weekend','Não',22.90,229.00),(8,'Plano descontinuado com acesso ao streaming e pontos em dobro','Lux Gold','Não',44.90,449.00),(9,'Plano descontinuado com acesso ao catálogo infantil','Lux Mini','Não',12.90,129.00),(10,'Plano descontinuado com todos os benefícios e ingressos extras','Lux Max','Não',59.90,599.00);
/*!40000 ALTER TABLE `plano` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pontos_usuario`
--

DROP TABLE IF EXISTS `pontos_usuario`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `pontos_usuario` (
  `qntd_pontos` int NOT NULL,
  `id_usuario` int NOT NULL,
  KEY `id_usuario` (`id_usuario`),
  CONSTRAINT `pontos_usuario_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id_usuario`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pontos_usuario`
--

LOCK TABLES `pontos_usuario` WRITE;
/*!40000 ALTER TABLE `pontos_usuario` DISABLE KEYS */;
INSERT INTO `pontos_usuario` VALUES (1700,1),(70,2),(180,3),(320,4),(950,5),(40,6),(610,7),(260,8),(85,9),(1200,10);
/*!40000 ALTER TABLE `pontos_usuario` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `reserva`
--

DROP TABLE IF EXISTS `reserva`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `reserva` (
  `id_reserva` int NOT NULL AUTO_INCREMENT,
  `dia_reserva` date NOT NULL,
  `hora_reserva` time NOT NULL,
  `status_reserva` varchar(50) NOT NULL,
  `id_usuario` int NOT NULL,
  `id_sala` int NOT NULL,
  `id_filme` int NOT NULL,
  PRIMARY KEY (`id_reserva`),
  KEY `id_usuario` (`id_usuario`),
  KEY `id_sala` (`id_sala`),
  KEY `id_filme` (`id_filme`),
  CONSTRAINT `reserva_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id_usuario`),
  CONSTRAINT `reserva_ibfk_2` FOREIGN KEY (`id_sala`) REFERENCES `sala` (`id_sala`),
  CONSTRAINT `reserva_ibfk_3` FOREIGN KEY (`id_filme`) REFERENCES `filme` (`id_filme`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `reserva`
--

LOCK TABLES `reserva` WRITE;
/*!40000 ALTER TABLE `reserva` DISABLE KEYS */;
INSERT INTO `reserva` VALUES (1,'2026-07-07','14:10:00','Concluída',2,1,4),(2,'2026-02-20','10:15:00','Concluída',1,4,2),(3,'2026-07-05','15:30:00','Cancelada',2,2,5),(4,'2026-11-21','18:00:00','Agendada',3,4,6),(5,'2026-12-11','20:45:00','Agendada',1,2,7),(6,'2026-12-05','16:00:00','Agendada',4,5,1),(7,'2026-12-12','19:00:00','Agendada',5,6,11),(8,'2026-10-10','13:00:00','Concluída',6,7,9),(9,'2026-12-18','21:00:00','Agendada',7,8,8),(10,'2026-09-03','11:30:00','Cancelada',8,10,2);
/*!40000 ALTER TABLE `reserva` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `resgate_beneficio`
--

DROP TABLE IF EXISTS `resgate_beneficio`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `resgate_beneficio` (
  `id_resgate` int NOT NULL AUTO_INCREMENT,
  `pontos_utilizados` int NOT NULL,
  `data_resgate` date DEFAULT NULL,
  `id_usuario` int NOT NULL,
  `id_beneficio` int NOT NULL,
  `id_venda` int DEFAULT NULL,
  PRIMARY KEY (`id_resgate`),
  KEY `id_usuario` (`id_usuario`),
  KEY `id_beneficio` (`id_beneficio`),
  KEY `fk_resgate_venda` (`id_venda`),
  CONSTRAINT `fk_resgate_venda` FOREIGN KEY (`id_venda`) REFERENCES `venda` (`id_venda`),
  CONSTRAINT `resgate_beneficio_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id_usuario`),
  CONSTRAINT `resgate_beneficio_ibfk_2` FOREIGN KEY (`id_beneficio`) REFERENCES `beneficios` (`id_beneficio`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `resgate_beneficio`
--

LOCK TABLES `resgate_beneficio` WRITE;
/*!40000 ALTER TABLE `resgate_beneficio` DISABLE KEYS */;
INSERT INTO `resgate_beneficio` VALUES (1,50,'2026-08-07',1,1,NULL),(2,70,'2026-05-08',2,2,NULL),(3,90,'2026-01-22',3,3,NULL),(4,120,'2026-09-12',4,4,NULL),(5,200,'2026-10-03',5,5,NULL),(6,160,'2026-10-21',6,6,NULL),(7,60,'2026-06-14',7,8,NULL),(8,100,'2026-07-30',8,9,NULL),(9,50,'2026-11-02',9,1,NULL),(10,300,'2026-11-10',1,7,NULL);
/*!40000 ALTER TABLE `resgate_beneficio` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sala`
--

DROP TABLE IF EXISTS `sala`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sala` (
  `id_sala` int NOT NULL AUTO_INCREMENT,
  `numero_sala` int NOT NULL,
  `capacidade` int NOT NULL,
  `id_cinema` int NOT NULL,
  PRIMARY KEY (`id_sala`),
  KEY `id_cinema` (`id_cinema`),
  CONSTRAINT `sala_ibfk_1` FOREIGN KEY (`id_cinema`) REFERENCES `cinema` (`id_cinema`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sala`
--

LOCK TABLES `sala` WRITE;
/*!40000 ALTER TABLE `sala` DISABLE KEYS */;
INSERT INTO `sala` VALUES (1,1,70,1),(2,2,70,1),(3,3,55,1),(4,1,80,2),(5,2,75,2),(6,1,85,3),(7,2,70,3),(8,3,60,2),(9,3,65,3),(10,1,90,4),(11,2,80,4),(12,1,70,5),(13,1,75,6),(14,1,60,7);
/*!40000 ALTER TABLE `sala` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sessao`
--

DROP TABLE IF EXISTS `sessao`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sessao` (
  `id_sessao` int NOT NULL AUTO_INCREMENT,
  `dia_sessao` date NOT NULL,
  `hora_sessao` time NOT NULL,
  `id_filme` int NOT NULL,
  `id_sala` int NOT NULL,
  PRIMARY KEY (`id_sessao`),
  KEY `id_filme` (`id_filme`),
  KEY `id_sala` (`id_sala`),
  CONSTRAINT `sessao_ibfk_1` FOREIGN KEY (`id_filme`) REFERENCES `filme` (`id_filme`),
  CONSTRAINT `sessao_ibfk_2` FOREIGN KEY (`id_sala`) REFERENCES `sala` (`id_sala`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sessao`
--

LOCK TABLES `sessao` WRITE;
/*!40000 ALTER TABLE `sessao` DISABLE KEYS */;
INSERT INTO `sessao` VALUES (1,'2026-11-19','14:00:00',2,1),(2,'2026-11-19','16:30:00',6,2),(3,'2026-11-19','19:00:00',11,3),(4,'2026-11-20','15:00:00',5,4),(5,'2026-11-20','18:00:00',8,5),(6,'2026-11-20','21:00:00',9,6),(7,'2026-11-21','14:00:00',2,7),(8,'2026-11-21','17:30:00',4,1),(9,'2026-11-22','16:00:00',7,2),(10,'2026-11-22','20:00:00',1,3),(11,'2026-11-23','19:30:00',11,8),(12,'2026-11-24','15:30:00',6,10);
/*!40000 ALTER TABLE `sessao` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `usuario`
--

DROP TABLE IF EXISTS `usuario`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `usuario` (
  `id_usuario` int NOT NULL AUTO_INCREMENT,
  `nome` varchar(100) NOT NULL,
  `nascimento` date NOT NULL,
  `cpf` varchar(11) NOT NULL,
  `email` varchar(100) NOT NULL,
  `telefone` varchar(11) DEFAULT NULL,
  `senha` varchar(11) NOT NULL,
  PRIMARY KEY (`id_usuario`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `usuario`
--

LOCK TABLES `usuario` WRITE;
/*!40000 ALTER TABLE `usuario` DISABLE KEYS */;
INSERT INTO `usuario` VALUES (1,'Alessandra Silva','2003-09-27','12345678901','alessandrasilva@gmail.com','11900000000','123456789'),(2,'Bernardo Rodrigues','1997-09-01','12234567890','bernard0ro@gmail.com','12911110000','123456789'),(3,'Manuel Castelari','1995-09-10','12334567891','manuelcas@gmail.com','80100001000','123456789'),(4,'Camila Torres','1999-03-14','45678912305','camilatorres@gmail.com','11988887777','123456789'),(5,'Diego Almeida','1988-12-02','32165498700','diegoalmeida@gmail.com','21977776666','123456789'),(6,'Fernanda Lima','2001-06-21','78912345611','fernandalima@gmail.com',NULL,'123456789'),(7,'Gabriel Souza','1993-01-30','14725836900','gabrielsouza@gmail.com','11966665555','123456789'),(8,'Helena Martins','2005-11-08','96385274122','helenamartins@gmail.com','13955554444','123456789'),(9,'Igor Pereira','1990-04-17','85274196333','igorpereira@gmail.com','11944443333','123456789'),(10,'Julia Ribeiro','1985-08-25','25836914744','juliaribeiro@gmail.com',NULL,'123456789');
/*!40000 ALTER TABLE `usuario` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `venda`
--

DROP TABLE IF EXISTS `venda`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `venda` (
  `id_venda` int NOT NULL AUTO_INCREMENT,
  `data_pedido` date DEFAULT NULL,
  `valor_pedido` decimal(10,2) NOT NULL,
  `id_usuario` int NOT NULL,
  `id_reserva` int DEFAULT NULL,
  `id_produto` int DEFAULT NULL,
  `id_endereco_entrega` int DEFAULT NULL,
  `id_cupom` int DEFAULT NULL,
  PRIMARY KEY (`id_venda`),
  KEY `id_usuario` (`id_usuario`),
  KEY `id_reserva` (`id_reserva`),
  KEY `id_produto` (`id_produto`),
  KEY `fk_venda_endereco` (`id_endereco_entrega`),
  KEY `fk_venda_cupom` (`id_cupom`),
  CONSTRAINT `fk_venda_cupom` FOREIGN KEY (`id_cupom`) REFERENCES `cupom` (`id_cupom`),
  CONSTRAINT `fk_venda_endereco` FOREIGN KEY (`id_endereco_entrega`) REFERENCES `endereco_entrega` (`id_endereco`),
  CONSTRAINT `venda_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id_usuario`),
  CONSTRAINT `venda_ibfk_2` FOREIGN KEY (`id_reserva`) REFERENCES `reserva` (`id_reserva`),
  CONSTRAINT `venda_ibfk_3` FOREIGN KEY (`id_produto`) REFERENCES `bomboniere` (`id_produto`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `venda`
--

LOCK TABLES `venda` WRITE;
/*!40000 ALTER TABLE `venda` DISABLE KEYS */;
INSERT INTO `venda` VALUES (1,'2026-11-09',129.90,1,NULL,2,NULL,NULL),(2,'2026-11-10',78.90,3,NULL,3,NULL,NULL),(3,'2026-11-12',20.00,2,NULL,NULL,NULL,NULL),(4,'2026-11-13',73.90,4,6,1,NULL,NULL),(5,'2026-11-14',59.90,5,7,6,NULL,NULL),(6,'2026-11-15',67.50,6,NULL,NULL,NULL,NULL),(7,'2026-11-16',75.90,7,NULL,4,NULL,NULL),(8,'2026-11-16',98.90,8,NULL,5,NULL,NULL),(9,'2026-11-17',68.90,9,NULL,7,NULL,NULL),(10,'2026-11-17',72.90,10,NULL,10,NULL,NULL);
/*!40000 ALTER TABLE `venda` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping events for database 'lux_bcd'
--

--
-- Dumping routines for database 'lux_bcd'
--
SET @@SESSION.SQL_LOG_BIN = @MYSQLDUMP_TEMP_LOG_BIN;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-11-18 16:22:09
