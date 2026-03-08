CREATE DATABASE CHANNEL45609588;
USE CHANNEL45609588;

--
-- Table structure for table `DISPONIBILITAT`
--

DROP TABLE IF EXISTS `DISPONIBILITAT`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `DISPONIBILITAT` (
  `codiDisponibilitat` int NOT NULL AUTO_INCREMENT,
  `codiHotel` int NOT NULL,
  `codiTipusHabitacio` varchar(10) NOT NULL,
  `data` date NOT NULL,
  `cupo` int NOT NULL,
  `preu` decimal(10,2) NOT NULL,
  `actiu` tinyint(1) NOT NULL DEFAULT '1',
  `reservesChannel` int NOT NULL DEFAULT '0',
  PRIMARY KEY (`codiDisponibilitat`),
  UNIQUE KEY `codiHotel` (`codiHotel`,`codiTipusHabitacio`,`data`),
  KEY `codiTipusHabitacio` (`codiTipusHabitacio`),
  CONSTRAINT `disponibilitat_ibfk_1` FOREIGN KEY (`codiHotel`) REFERENCES `HOTEL` (`codiHotel`),
  CONSTRAINT `disponibilitat_ibfk_2` FOREIGN KEY (`codiTipusHabitacio`) REFERENCES `TIPUS_HABITACIO` (`codiTipusHabitacio`)
) ENGINE=InnoDB AUTO_INCREMENT=57 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `DISPONIBILITAT`
--

LOCK TABLES `DISPONIBILITAT` WRITE;
/*!40000 ALTER TABLE `DISPONIBILITAT` DISABLE KEYS */;
INSERT INTO `DISPONIBILITAT` VALUES (1,1,'DBL','2025-12-01',5,100.00,1,0),(2,1,'DBL','2025-12-02',5,100.00,1,0),(3,1,'DBL','2025-12-03',5,110.00,1,1),(4,1,'DBL','2025-12-04',4,120.00,1,1),(5,1,'DBL','2025-12-05',4,120.00,1,2),(6,1,'TPL','2025-12-01',3,150.00,1,0),(7,1,'TPL','2025-12-02',3,150.00,1,0),(8,1,'TPL','2025-12-03',3,160.00,1,1),(9,1,'TPL','2025-12-04',2,160.00,1,1),(10,1,'TPL','2025-12-05',2,170.00,1,2),(11,1,'SUI','2025-12-01',2,250.00,1,0),(12,1,'SUI','2025-12-02',2,260.00,1,0),(13,1,'SUI','2025-12-03',2,260.00,1,1),(14,1,'SUI','2025-12-04',1,270.00,1,1),(15,1,'SUI','2025-12-05',1,280.00,1,2),(16,2,'DBL','2025-12-01',4,90.00,1,0),(17,2,'DBL','2025-12-02',4,90.00,1,1),(18,2,'DBL','2025-12-03',3,95.00,1,1),(19,2,'DBL','2025-12-04',3,95.00,1,2),(20,2,'DBL','2025-12-05',2,100.00,1,2),(21,2,'TPL','2025-12-01',2,130.00,1,0),(22,2,'TPL','2025-12-02',2,130.00,1,1),(23,2,'TPL','2025-12-03',2,140.00,1,1),(24,2,'TPL','2025-12-04',1,140.00,1,2),(25,2,'TPL','2025-12-05',1,150.00,1,2),(26,2,'SUI','2025-12-01',1,220.00,1,0),(27,2,'SUI','2025-12-02',1,230.00,1,1),(28,2,'SUI','2025-12-03',1,230.00,1,1),(29,2,'SUI','2025-12-04',1,240.00,1,2),(30,2,'SUI','2025-12-05',1,250.00,1,2),(31,3,'DBL','2025-12-01',6,80.00,1,0),(32,3,'DBL','2025-12-02',6,85.00,1,0),(33,3,'DBL','2025-12-03',6,85.00,1,1),(34,3,'DBL','2025-12-04',5,90.00,1,1),(35,3,'DBL','2025-12-05',5,95.00,1,1),(36,3,'TPL','2025-12-01',4,140.00,1,0),(37,3,'TPL','2025-12-02',4,140.00,1,1),(38,3,'TPL','2025-12-03',3,145.00,1,1),(39,3,'TPL','2025-12-04',3,150.00,1,2),(40,3,'TPL','2025-12-05',3,150.00,1,2),(41,3,'SUI','2025-12-01',2,240.00,1,0),(42,3,'SUI','2025-12-02',2,240.00,1,1),(43,3,'SUI','2025-12-03',2,250.00,1,1),(44,3,'SUI','2025-12-04',1,260.00,1,2),(45,3,'SUI','2025-12-05',1,270.00,1,2),(46,1,'DBL','2025-12-25',5,0.00,0,0),(47,1,'DBL','2025-12-08',0,0.00,1,0),(48,2,'DBL','2025-12-23',19,445.30,0,0),(50,1,'DBL','2025-12-19',3,400.00,1,0),(51,1,'SUI','2025-12-19',0,100.00,1,0),(52,1,'TPL','2025-12-19',3,60.70,1,0),(53,1,'TPL','2025-12-20',0,120.00,1,0),(54,1,'DBL','2025-12-20',6,0.00,0,0),(55,1,'SUI','2025-12-20',1,200.00,0,0);
/*!40000 ALTER TABLE `DISPONIBILITAT` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `HOTEL`
--

DROP TABLE IF EXISTS `HOTEL`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `HOTEL` (
  `codiHotel` int NOT NULL AUTO_INCREMENT,
  `nomHotel` varchar(100) NOT NULL,
  `ciutatHotel` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`codiHotel`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `HOTEL`
--

LOCK TABLES `HOTEL` WRITE;
/*!40000 ALTER TABLE `HOTEL` DISABLE KEYS */;
INSERT INTO `HOTEL` VALUES (1,'Hotel Sol i Platja','Palma'),(2,'Hotel Serra Marina','Sóller'),(3,'Hotel Blau Mediterrani','Alcúdia');
/*!40000 ALTER TABLE `HOTEL` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `TIPUS_HABITACIO`
--

DROP TABLE IF EXISTS `TIPUS_HABITACIO`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `TIPUS_HABITACIO` (
  `codiTipusHabitacio` varchar(10) NOT NULL,
  `denominacio` varchar(100) NOT NULL,
  `pax` int NOT NULL,
  PRIMARY KEY (`codiTipusHabitacio`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `TIPUS_HABITACIO`
--

LOCK TABLES `TIPUS_HABITACIO` WRITE;
/*!40000 ALTER TABLE `TIPUS_HABITACIO` DISABLE KEYS */;
INSERT INTO `TIPUS_HABITACIO` VALUES ('DBL','Habitació Doble',2),('SUI','Suite',4),('TPL','Habitació Triple',3);
/*!40000 ALTER TABLE `TIPUS_HABITACIO` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `USUARI`
--

DROP TABLE IF EXISTS `USUARI`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `USUARI` (
  `codiUsuari` int NOT NULL AUTO_INCREMENT,
  `email` varchar(100) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `nomUsuari` varchar(100) NOT NULL,
  PRIMARY KEY (`codiUsuari`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `USUARI`
--

LOCK TABLES `USUARI` WRITE;
/*!40000 ALTER TABLE `USUARI` DISABLE KEYS */;
INSERT INTO `USUARI` VALUES (1,'gestor1@hotel.com','123456','Gestor 1'),(2,'gestor2@hotel.com','hash456','Gestor 2');
/*!40000 ALTER TABLE `USUARI` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `USUARI_HOTEL`
--

DROP TABLE IF EXISTS `USUARI_HOTEL`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `USUARI_HOTEL` (
  `codiUsuari` int NOT NULL,
  `codiHotel` int NOT NULL,
  PRIMARY KEY (`codiUsuari`,`codiHotel`),
  KEY `codiHotel` (`codiHotel`),
  CONSTRAINT `usuari_hotel_ibfk_1` FOREIGN KEY (`codiUsuari`) REFERENCES `USUARI` (`codiUsuari`),
  CONSTRAINT `usuari_hotel_ibfk_2` FOREIGN KEY (`codiHotel`) REFERENCES `HOTEL` (`codiHotel`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `USUARI_HOTEL`
--

LOCK TABLES `USUARI_HOTEL` WRITE;
/*!40000 ALTER TABLE `USUARI_HOTEL` DISABLE KEYS */;
INSERT INTO `USUARI_HOTEL` VALUES (1,1),(2,1),(1,2),(2,2),(1,3);
/*!40000 ALTER TABLE `USUARI_HOTEL` ENABLE KEYS */;
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

-- Dump completed on 2025-12-09 22:46:50
