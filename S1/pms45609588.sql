-- MySQL dump 10.13  Distrib 9.5.0, for macos26.1 (arm64)
--
-- Host: localhost    Database: PMS45609588
-- ------------------------------------------------------
-- Server version	9.5.0

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8mb4 */;
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

SET @@GLOBAL.GTID_PURGED=/*!80000 '+'*/ '447e7c90-cc71-11f0-b732-abfdf2c505c7:1-57';

--
-- Table structure for table `ACOMPANYANT`
--

DROP TABLE IF EXISTS `ACOMPANYANT`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ACOMPANYANT` (
  `codiAcompanyant` int NOT NULL AUTO_INCREMENT,
  `nomAcompanyant` varchar(100) DEFAULT NULL,
  `dniAcompanyant` varchar(20) DEFAULT NULL,
  `codiClient` int DEFAULT NULL,
  PRIMARY KEY (`codiAcompanyant`),
  KEY `codiClient` (`codiClient`),
  CONSTRAINT `acompanyant_ibfk_1` FOREIGN KEY (`codiClient`) REFERENCES `CLIENT` (`codiClient`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ACOMPANYANT`
--

LOCK TABLES `ACOMPANYANT` WRITE;
/*!40000 ALTER TABLE `ACOMPANYANT` DISABLE KEYS */;
INSERT INTO `ACOMPANYANT` VALUES (1,'Ana Pons','87654321Z',1),(2,'Clara Ribes','98765432T',2),(3,'Tom Harris','A1234567',3),(4,'Joan Costa','B2345678',4),(5,'Laura Pérez','C3456789',5),(6,'Lara Martín','D4567890',6),(7,'Peter Doe','E5678901',7),(8,'Julia Torres','F6789012',8),(9,'Marta Palma','G7890123',9),(10,'Eric Vidal','H8901234',10),(11,'Bea Mora','I9012345',11),(12,'Claudia Font','J0123456',12),(13,'Xavi Mir','K1234567',13),(14,'Paula Rey','L2345678',14),(15,'Leo Serra','M3456789',15),(16,'Nora Puig','H5432109',5),(17,'Aina Prats','D9081726',3),(18,'Maria Nadal','Z7263521',12),(19,'Joan Moll','F1144227',11),(20,'Helena Serra','G8877665',7);
/*!40000 ALTER TABLE `ACOMPANYANT` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `CHECK_IN`
--

DROP TABLE IF EXISTS `CHECK_IN`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `CHECK_IN` (
  `idCheckIn` int NOT NULL AUTO_INCREMENT,
  `horaCheckIn` time DEFAULT NULL,
  `codiReserva` int DEFAULT NULL,
  PRIMARY KEY (`idCheckIn`),
  KEY `codiReserva` (`codiReserva`),
  CONSTRAINT `check_in_ibfk_1` FOREIGN KEY (`codiReserva`) REFERENCES `RESERVA` (`codiReserva`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `CHECK_IN`
--

LOCK TABLES `CHECK_IN` WRITE;
/*!40000 ALTER TABLE `CHECK_IN` DISABLE KEYS */;
INSERT INTO `CHECK_IN` VALUES (1,'14:30:00',1),(2,'15:00:00',2),(3,'16:10:00',4),(4,'13:50:00',6),(5,'15:45:00',7),(6,'14:20:00',8),(7,'16:35:00',10),(8,'14:05:00',11),(9,'17:10:00',12),(10,'15:25:00',15);
/*!40000 ALTER TABLE `CHECK_IN` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `CLIENT`
--

DROP TABLE IF EXISTS `CLIENT`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `CLIENT` (
  `codiClient` int NOT NULL AUTO_INCREMENT,
  `nomClient` varchar(100) DEFAULT NULL,
  `llinatges` varchar(150) DEFAULT NULL,
  `emailClient` varchar(100) DEFAULT NULL,
  `telefonClient` varchar(20) DEFAULT NULL,
  `dni` varchar(20) DEFAULT NULL,
  `nacionalitat` varchar(50) DEFAULT NULL,
  `dataNaixement` date DEFAULT NULL,
  `genere` varchar(20) DEFAULT NULL,
  `vip` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`codiClient`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `CLIENT`
--

LOCK TABLES `CLIENT` WRITE;
/*!40000 ALTER TABLE `CLIENT` DISABLE KEYS */;
INSERT INTO `CLIENT` VALUES (1,'Marc','Pons Vidal','marc@mail.com','666111222','12345678A','España','1995-04-12','Home',0),(2,'Laura','Soler Campos','laura@mail.com','666222333','23456789B','España','1990-09-08','Dona',1),(3,'James','Wilson','james@mail.com','666333444','Y1234567C','Reino Unido','1985-02-25','Home',0),(4,'Maria','Garcia','maria@mail.com','666444555','34567890D','España','1988-12-10','Dona',0),(5,'Pere','Riera','pere@mail.com','666555666','45678901E','España','1993-03-03','Home',0),(6,'Andrea','López','andrea@mail.com','666666777','56789012F','España','1991-07-14','Dona',1),(7,'John','Smith','smith@mail.com','666777888','X8901234G','EEUU','1982-01-30','Home',0),(8,'Helena','Moreno','helena@mail.com','666888999','67890123H','España','1997-05-16','Dona',0),(9,'Oscar','Marti','oscar@mail.com','677111222','78901234I','España','1994-09-19','Home',0),(10,'Ana','Costa','ana@mail.com','611222333','89012345J','España','2000-11-01','Dona',0),(11,'Sara','Jimenez','sara@mail.com','611333444','90123456K','España','1992-02-02','Dona',1),(12,'David','Ruiz','david@mail.com','611444555','01234567L','España','1989-08-12','Home',0),(13,'Luca','Bianchi','luca@mail.com','611555666','Z2345678M','Italia','1987-06-21','Home',0),(14,'Emma','Thompson','emma@mail.com','611666777','T3456789N','Reino Unido','1996-04-09','Dona',0),(15,'Aitor','Ferrer','aitor@mail.com','611777888','89076543P','España','1998-10-14','Home',1);
/*!40000 ALTER TABLE `CLIENT` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `HABITACIO`
--

DROP TABLE IF EXISTS `HABITACIO`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `HABITACIO` (
  `numeroHabitacio` int NOT NULL AUTO_INCREMENT,
  `descripcioHabitacio` varchar(200) DEFAULT NULL,
  `codiTipusHabitacio` int DEFAULT NULL,
  `codiHotel` int DEFAULT NULL,
  PRIMARY KEY (`numeroHabitacio`),
  KEY `codiTipusHabitacio` (`codiTipusHabitacio`),
  KEY `codiHotel` (`codiHotel`),
  CONSTRAINT `habitacio_ibfk_1` FOREIGN KEY (`codiTipusHabitacio`) REFERENCES `TIPUS_HABITACIO` (`codiTipusHabitacio`),
  CONSTRAINT `habitacio_ibfk_2` FOREIGN KEY (`codiHotel`) REFERENCES `HOTEL` (`codiHotel`)
) ENGINE=InnoDB AUTO_INCREMENT=321 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `HABITACIO`
--

LOCK TABLES `HABITACIO` WRITE;
/*!40000 ALTER TABLE `HABITACIO` DISABLE KEYS */;
INSERT INTO `HABITACIO` VALUES (101,'Individual interior',1,1),(102,'Doble estándar',2,1),(103,'Doble balcón',2,1),(104,'Suite familiar',3,1),(105,'Suite premium',4,1),(106,'Doble planta baja',2,1),(107,'Doble vistas mar',2,1),(108,'Premium jacuzzi',4,1),(109,'Suite familiar deluxe',3,1),(110,'Individual económica',1,1),(111,'Doble económica',2,1),(112,'Doble superior',2,1),(113,'Suite premium superior',4,1),(114,'Individual business',1,1),(115,'Doble terraza',2,1),(116,'Suite familiar vista mar',3,1),(117,'Doble interior',2,1),(118,'Premium ático',4,1),(119,'Individual ático',1,1),(120,'Doble ejecutiva',2,1),(201,'Individual sencilla',1,2),(202,'Doble estándar',2,2),(203,'Doble balcón',2,2),(204,'Suite familiar jardín',3,2),(205,'Suite premium',4,2),(206,'Doble económica',2,2),(207,'Doble piscina',2,2),(208,'Premium terraza',4,2),(209,'Familiar económica',3,2),(210,'Individual económica',1,2),(211,'Doble interior',2,2),(212,'Doble superior',2,2),(213,'Suite premium vista lago',4,2),(214,'Individual business',1,2),(215,'Doble terraza',2,2),(216,'Suite familiar superior',3,2),(217,'Doble estándar',2,2),(218,'Premium ático',4,2),(219,'Individual ático',1,2),(220,'Doble ejecutiva',2,2),(301,'Individual montaña',1,3),(302,'Doble estándar',2,3),(303,'Doble deluxe',2,3),(304,'Suite familiar',3,3),(305,'Suite premium panorámica',4,3),(306,'Doble económica',2,3),(307,'Doble terraza',2,3),(308,'Premium panorámica',4,3),(309,'Familiar superior',3,3),(310,'Individual económica',1,3),(311,'Doble interior',2,3),(312,'Doble superior',2,3),(313,'Suite premium lujo',4,3),(314,'Individual business',1,3),(315,'Doble terraza',2,3),(316,'Suite familiar ático',3,3),(317,'Doble estándar',2,3),(318,'Premium ático',4,3),(319,'Individual ático',1,3),(320,'Doble ejecutiva',2,3);
/*!40000 ALTER TABLE `HABITACIO` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `HOTEL`
--

DROP TABLE IF EXISTS `HOTEL`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `HOTEL` (
  `codiHotel` int NOT NULL AUTO_INCREMENT,
  `nomHotel` varchar(100) DEFAULT NULL,
  `direccio` varchar(150) DEFAULT NULL,
  `telefonHotel` varchar(20) DEFAULT NULL,
  `emailHotel` varchar(100) DEFAULT NULL,
  `estrelles` int DEFAULT NULL,
  `ciutat` varchar(100) DEFAULT NULL,
  `provincia` varchar(100) DEFAULT NULL,
  `comunitatAutonoma` varchar(100) DEFAULT NULL,
  `pais` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`codiHotel`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `HOTEL`
--

LOCK TABLES `HOTEL` WRITE;
/*!40000 ALTER TABLE `HOTEL` DISABLE KEYS */;
INSERT INTO `HOTEL` VALUES (1,'Hotel Mar Blau','Passeig Marítim 12','971111111','info@marblau.com',4,'Palma','Illes Balears','Illes Balears','España'),(2,'Hotel Sol Romántic','Carrer de la Platja 45','971222222','contacte@solromantic.com',3,'Alcúdia','Illes Balears','Illes Balears','España'),(3,'Hotel Serra Mediterrània','Avinguda dels Pins 88','971333333','hola@serramed.com',5,'Sóller','Illes Balears','Illes Balears','España');
/*!40000 ALTER TABLE `HOTEL` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `PREU_REGIM`
--

DROP TABLE IF EXISTS `PREU_REGIM`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `PREU_REGIM` (
  `idPreuRegim` int NOT NULL AUTO_INCREMENT,
  `preuRegim` decimal(8,2) DEFAULT NULL,
  `codiRegim` int DEFAULT NULL,
  `codiHotel` int DEFAULT NULL,
  PRIMARY KEY (`idPreuRegim`),
  KEY `codiRegim` (`codiRegim`),
  KEY `codiHotel` (`codiHotel`),
  CONSTRAINT `preu_regim_ibfk_1` FOREIGN KEY (`codiRegim`) REFERENCES `REGIM` (`codiRegim`),
  CONSTRAINT `preu_regim_ibfk_2` FOREIGN KEY (`codiHotel`) REFERENCES `HOTEL` (`codiHotel`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `PREU_REGIM`
--

LOCK TABLES `PREU_REGIM` WRITE;
/*!40000 ALTER TABLE `PREU_REGIM` DISABLE KEYS */;
INSERT INTO `PREU_REGIM` VALUES (1,60.00,1,1),(2,80.00,2,1),(3,120.00,3,1),(4,160.00,4,1),(5,40.00,1,2),(6,55.00,2,2),(7,90.00,3,2),(8,120.00,4,2),(9,90.00,1,3),(10,120.00,2,3),(11,180.00,3,3),(12,240.00,4,3);
/*!40000 ALTER TABLE `PREU_REGIM` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `REGIM`
--

DROP TABLE IF EXISTS `REGIM`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `REGIM` (
  `codiRegim` int NOT NULL AUTO_INCREMENT,
  `descripcioRegim` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`codiRegim`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `REGIM`
--

LOCK TABLES `REGIM` WRITE;
/*!40000 ALTER TABLE `REGIM` DISABLE KEYS */;
INSERT INTO `REGIM` VALUES (1,'Sólo alojamiento'),(2,'Alojamiento y desayuno'),(3,'Media pensión'),(4,'Todo incluido');
/*!40000 ALTER TABLE `REGIM` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `RESERVA`
--

DROP TABLE IF EXISTS `RESERVA`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `RESERVA` (
  `codiReserva` int NOT NULL AUTO_INCREMENT,
  `dataReserva` date DEFAULT NULL,
  `dataEntrada` date DEFAULT NULL,
  `dataSortida` date DEFAULT NULL,
  `estatReserva` varchar(50) DEFAULT NULL,
  `codiClient` int DEFAULT NULL,
  `numeroHabitacio` int DEFAULT NULL,
  `codiRegim` int DEFAULT NULL,
  `codiHotel` int DEFAULT NULL,
  PRIMARY KEY (`codiReserva`),
  KEY `codiClient` (`codiClient`),
  KEY `numeroHabitacio` (`numeroHabitacio`),
  KEY `codiRegim` (`codiRegim`),
  KEY `codiHotel` (`codiHotel`),
  CONSTRAINT `reserva_ibfk_1` FOREIGN KEY (`codiClient`) REFERENCES `CLIENT` (`codiClient`),
  CONSTRAINT `reserva_ibfk_2` FOREIGN KEY (`numeroHabitacio`) REFERENCES `HABITACIO` (`numeroHabitacio`),
  CONSTRAINT `reserva_ibfk_3` FOREIGN KEY (`codiRegim`) REFERENCES `REGIM` (`codiRegim`),
  CONSTRAINT `reserva_ibfk_4` FOREIGN KEY (`codiHotel`) REFERENCES `HOTEL` (`codiHotel`)
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `RESERVA`
--

LOCK TABLES `RESERVA` WRITE;
/*!40000 ALTER TABLE `RESERVA` DISABLE KEYS */;
INSERT INTO `RESERVA` VALUES (1,'2025-01-10','2025-02-01','2025-02-07','Confirmada',1,103,2,1),(2,'2025-01-15','2025-03-10','2025-03-15','Confirmada',2,205,4,2),(3,'2025-01-20','2025-04-05','2025-04-12','Pendent',3,301,1,3),(4,'2025-02-01','2025-02-15','2025-02-20','Confirmada',4,112,2,1),(5,'2025-02-03','2025-03-01','2025-03-05','Cancel·lada',5,207,3,2),(6,'2025-02-10','2025-02-25','2025-03-03','Confirmada',6,317,2,3),(7,'2025-02-18','2025-02-28','2025-03-02','Confirmada',7,210,1,2),(8,'2025-03-02','2025-03-20','2025-03-27','Confirmada',8,119,4,1),(9,'2025-03-05','2025-04-10','2025-04-18','Pendent',9,312,2,3),(10,'2025-03-07','2025-04-02','2025-04-06','Confirmada',10,115,2,1),(11,'2025-03-09','2025-05-01','2025-05-07','Confirmada',11,218,4,2),(12,'2025-03-11','2025-04-12','2025-04-18','Confirmada',12,303,2,3),(13,'2025-03-12','2025-06-01','2025-06-05','Confirmada',13,104,3,1),(14,'2025-03-14','2025-06-10','2025-06-15','Pendent',14,214,2,2),(15,'2025-03-17','2025-07-01','2025-07-07','Confirmada',15,305,4,3),(16,'2025-03-18','2025-05-02','2025-05-10','Confirmada',2,111,2,1),(17,'2025-03-19','2025-06-20','2025-06-27','Confirmada',3,202,2,2),(18,'2025-03-22','2025-07-12','2025-07-19','Pendent',4,316,3,3),(19,'2025-03-25','2025-05-10','2025-05-15','Confirmada',5,102,2,1),(20,'2025-03-27','2025-06-14','2025-06-20','Confirmada',6,206,3,2),(21,'2025-03-29','2025-08-01','2025-08-08','Confirmada',7,318,4,3),(22,'2025-03-30','2025-04-15','2025-04-20','Confirmada',8,108,4,1),(23,'2025-03-31','2025-07-15','2025-07-22','Confirmada',9,212,2,2),(24,'2025-04-01','2025-08-03','2025-08-09','Confirmada',10,309,3,3),(25,'2025-04-03','2025-05-22','2025-05-29','Confirmada',15,120,2,1);
/*!40000 ALTER TABLE `RESERVA` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `TARIFA`
--

DROP TABLE IF EXISTS `TARIFA`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `TARIFA` (
  `codiTarifa` int NOT NULL AUTO_INCREMENT,
  `nomTarifa` varchar(100) DEFAULT NULL,
  `preuTipusHabitacio` decimal(8,2) DEFAULT NULL,
  `codiTipusHabitacio` int DEFAULT NULL,
  PRIMARY KEY (`codiTarifa`),
  KEY `codiTipusHabitacio` (`codiTipusHabitacio`),
  CONSTRAINT `tarifa_ibfk_1` FOREIGN KEY (`codiTipusHabitacio`) REFERENCES `TIPUS_HABITACIO` (`codiTipusHabitacio`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `TARIFA`
--

LOCK TABLES `TARIFA` WRITE;
/*!40000 ALTER TABLE `TARIFA` DISABLE KEYS */;
INSERT INTO `TARIFA` VALUES (1,'Básica',60.00,1),(2,'Estandard',90.00,2),(3,'Familiar',140.00,3),(4,'Premium Vista Mar',200.00,4);
/*!40000 ALTER TABLE `TARIFA` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `TIPUS_HABITACIO`
--

DROP TABLE IF EXISTS `TIPUS_HABITACIO`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `TIPUS_HABITACIO` (
  `codiTipusHabitacio` int NOT NULL AUTO_INCREMENT,
  `denominacio` varchar(100) DEFAULT NULL,
  `pax` int DEFAULT NULL,
  `llits` int DEFAULT NULL,
  PRIMARY KEY (`codiTipusHabitacio`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `TIPUS_HABITACIO`
--

LOCK TABLES `TIPUS_HABITACIO` WRITE;
/*!40000 ALTER TABLE `TIPUS_HABITACIO` DISABLE KEYS */;
INSERT INTO `TIPUS_HABITACIO` VALUES (1,'Individual',1,1),(2,'Doble',2,2),(3,'Suite Familiar',4,3),(4,'Suite Premium',2,1);
/*!40000 ALTER TABLE `TIPUS_HABITACIO` ENABLE KEYS */;
UNLOCK TABLES;
SET @@SESSION.SQL_LOG_BIN = @MYSQLDUMP_TEMP_LOG_BIN;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-11-30 20:10:01
