-- MySQL dump 10.16  Distrib 10.2.12-MariaDB, for Linux (x86_64)
--
-- Host: localhost    Database: TecWeb
-- ------------------------------------------------------
-- Server version	10.2.12-MariaDB-log

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Dumping data for table `Noleggio`
--

LOCK TABLES `Noleggio` WRITE;
/*!40000 ALTER TABLE `Noleggio` DISABLE KEYS */;
/*!40000 ALTER TABLE `Noleggio` ENABLE KEYS */;
UNLOCK TABLES;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,ERROR_FOR_DIVISION_BY_ZERO,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER EliminaInattivo
BEFORE DELETE ON Noleggio
FOR EACH ROW

BEGIN

declare X int;

select count(*) from Noleggio where Cliente= old.Cliente into X;

IF X = 1
	then
	delete from Utenti where Username = old.Cliente and Attivo = false;

END IF;

END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

--
-- Dumping data for table `Prenotazioni`
--

LOCK TABLES `Prenotazioni` WRITE;
/*!40000 ALTER TABLE `Prenotazioni` DISABLE KEYS */;
/*!40000 ALTER TABLE `Prenotazioni` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `Sale`
--

LOCK TABLES `Sale` WRITE;
/*!40000 ALTER TABLE `Sale` DISABLE KEYS */;
INSERT INTO `Sale` VALUES ('Stanza Blu','Registrazione','Blue Room','Recording',15),('Stanza Oro','Registrazione','Gold Room','Recording',16),('Stanza Oro','Registrazione Continua','Gold Room','Continuous Recording',20),('Stanza Rossa','Missaggio','Red Room','Mixing',12);
/*!40000 ALTER TABLE `Sale` ENABLE KEYS */;
UNLOCK TABLES;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,ERROR_FOR_DIVISION_BY_ZERO,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER ControllaInsert
BEFORE INSERT ON Sale
FOR EACH ROW

BEGIN

IF exists(select * from Sale where Name=new.Name and Function=new.Function)
	then
	signal sqlstate '45000'
	set message_text = '7';
END IF;

END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,ERROR_FOR_DIVISION_BY_ZERO,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER ControllaUpdate
BEFORE UPDATE ON Sale
FOR EACH ROW

BEGIN

IF exists(select * from Sale where Name=new.Name and Function=new.Function)
	then
	signal sqlstate '45000'
	set message_text = '7';
END IF;

END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

--
-- Dumping data for table `Strumentazione`
--

LOCK TABLES `Strumentazione` WRITE;
/*!40000 ALTER TABLE `Strumentazione` DISABLE KEYS */;
INSERT INTO `Strumentazione` VALUES ('Mackie 450w',30,'Questi speaker attivi Mackie da 450w ti permettono di concentrarti sul tuo spettacolo piuttosto che perdere tempo con equalizzazioni, grazie alla loro tecnologia di monitoring automatico dei livelli sonori. La dispersione del suono ed i bassi profondi rendono questa cassa una delle migliori per qualunque tipo di esigenza.','These active 450w speakers let you focus on your show rather than loosing time with equalizations, thanks to their automatic monitoring technology of sounds levels. Sound spreading and deep bass make this speaker one of the best for every need.','cassa_noleggio.jpg','Foto Casse Mackie 450w','Mackie 450w Speakers Picture',4),('Par inc 64 1000W 500W',10,'Potente faro basato sulla moderna tecnologia LED, dotato di commutatore a 2 potenze per adattarsi ad ogni esigenza di illuminazione ambientale con un occhio al risparmio energetico.','Powerful flood light based on the modern LED technology, featured with a 2-power switch that allows the product to fit any kind of ambiance lighting necessity, while being green.','led1.jpg','Immagine Par inc 64 1000W 500W','Par inc 64 1000W 500W Picture',6),('Pioneer cdj 2000',120,'Il nuovo lettore professionale dell’era digitale: questo modello è in grado di riprodurre la musica da sorgenti multiple, tra cui CD, DVD, dispositivi USB e schede di memoria SD. I DJ possono esportare dati su dispositivi USB o SD per accedere a vaste librerie di file musicali ed eseguire performance dal vivo. Consente inoltre la condivisione simultanea dei dati nei database e dei file di musica tra un massimo di quattro lettori, collegati con un cavo di rete LAN.','The new professional player of digital era: this model can play music from multiple sources as CD, DVD, USB devices and SD memory cards. DJs can export data on USB devices or SD cards to access large libraries of music files and play live on stage. This device also allows simultaneous sharing of database data and music between a maximum of 4 devices, linked by Lan cable.','cdj.jpg','Foto Pioneer cdj 2000','Pioneer cdj 2000 Picture',2),('Stairville Par Led 64',8,'Queste magnifiche luci composte da 183 led RGB da 10mm ad alta potenza, sono l\'ideale per i tuoi spettacoli sul palco; a basso consumo energetico, basso surriscaldamento, senza ventole e con un solido braccio metallico per essere facilmente appese. Inoltre l\'alternanza dei colori dei led pu&ograve; essere gestita da un programma autonomo o dalla funzione sound-to-light con 7 canali DMX.','These marvellous lights made by 183 pieces of 10mm high-powered leds are perfect for your shows on stage; low energy consumption, low heating, fanless and with firm iron hook to be simply hung. The RGB color change could be managed by a stand alone program or by sound-to-light function with 7 DMX channels.','led.jpg','Immagine Stairville Par Led 64','Stairville Par Led 64 Picture',20),('Strobo 1500w',30,'Potente luce strobo da 1500w con lampada xenon, regolazione percentuale del dimmer da 0 a 100, ventilazione forzata, 5kg di peso.','Powerful 1500w strobo light with xenon lamp, per cent dimmer adjustment from 0 to 100, forced ventilation, 5kg weight.','strobo.jpg','Immagine Strobo 1500w','Strobo 1500w Picture',1);
/*!40000 ALTER TABLE `Strumentazione` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `Utenti`
--

LOCK TABLES `Utenti` WRITE;
/*!40000 ALTER TABLE `Utenti` DISABLE KEYS */;
INSERT INTO `Utenti` VALUES ('admin','admin@admin.ad','12345679','admin',1,1),('user','user@usermail.us','987654321','user',0,1);
/*!40000 ALTER TABLE `Utenti` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2018-02-10 21:54:27
