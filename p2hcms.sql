-- MySQL dump 10.13  Distrib 8.0.36, for Win64 (x86_64)
--
-- Host: 127.0.0.1    Database: p2hcms
-- ------------------------------------------------------
-- Server version	8.0.37

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

--
-- Table structure for table `admin`
--

DROP TABLE IF EXISTS `admin`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `admin` (
  `adid` int NOT NULL AUTO_INCREMENT,
  `adname` varchar(225) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `adsurname` varchar(225) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `adusername` varchar(225) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `adpass` varchar(225) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `adposition` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`adid`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `admin`
--

LOCK TABLES `admin` WRITE;
/*!40000 ALTER TABLE `admin` DISABLE KEYS */;
INSERT INTO `admin` VALUES (14,'fdfsd','fdsfsd','fd342342','$2y$10$3JWdYq6pDJb4uC1zyn41c.XKCnTYOtvS2.cW9I70/ehcEuNXSrmWW','HEALTHWORKER'),(15,'GFDG','GFDG','fd342342','$2y$10$5ZHuRTwPwxy8wN1qMARoL.xOpQxYc7HKkj75Qh7U5VreLmiJ2d85q','HEALTHWORKER'),(16,'dsada','dsadas','fd342342','$2y$10$z6s1A2BqGHICNSD3hoS/kOw0xW0XdHlUzUjB3jXl70IR.39b/0P9e','HEALTHWORKER'),(17,'dsad','dsada','123','$2y$10$Pn5qHXMLVVjAgSMNjeaLvOiSAtgBcDUNPROQKWDS0ABWhMxanqADq','HEALTHWORKER');
/*!40000 ALTER TABLE `admin` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `admin1`
--

DROP TABLE IF EXISTS `admin1`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `admin1` (
  `ad_id` int NOT NULL,
  `ad_fname` varchar(225) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `ad_lname` varchar(225) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `ad_email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `ad_pass` varchar(225) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `admin1`
--

LOCK TABLES `admin1` WRITE;
/*!40000 ALTER TABLE `admin1` DISABLE KEYS */;
INSERT INTO `admin1` VALUES (1,'little mermaid ','gaspar','administator@gmail.com','password'),(0,'administrator ','BHW','administrator','password');
/*!40000 ALTER TABLE `admin1` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `emergency_supp`
--

DROP TABLE IF EXISTS `emergency_supp`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `emergency_supp` (
  `er_supId` int NOT NULL AUTO_INCREMENT,
  `prdct_name` varchar(225) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `pstck_in` int NOT NULL,
  `pstck_out` int NOT NULL,
  `pstck_exprd` int NOT NULL,
  `pstck_avail` int NOT NULL,
  PRIMARY KEY (`er_supId`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `emergency_supp`
--

LOCK TABLES `emergency_supp` WRITE;
/*!40000 ALTER TABLE `emergency_supp` DISABLE KEYS */;
INSERT INTO `emergency_supp` VALUES (1,'oxygen tank ',5,2,0,3);
/*!40000 ALTER TABLE `emergency_supp` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `inv_meds`
--

DROP TABLE IF EXISTS `inv_meds`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `inv_meds` (
  `med_id` int NOT NULL AUTO_INCREMENT,
  `meds_name` varchar(225) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `med_dscrptn` varchar(225) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `stock_in` int NOT NULL,
  `stock_out` int NOT NULL,
  `stock_exp` int NOT NULL,
  `stock_avail` int NOT NULL,
  PRIMARY KEY (`med_id`)
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `inv_meds`
--

LOCK TABLES `inv_meds` WRITE;
/*!40000 ALTER TABLE `inv_meds` DISABLE KEYS */;
INSERT INTO `inv_meds` VALUES (19,'RERWR','432423',43242,34,3432,432342),(20,'12132141313','23',23,23,54,54),(22,'gfdgd','fdsf',34,43,43,43),(23,'dsad','dsa',31,3,3,3),(24,'dasdsa','dsasda',2,2,2,2);
/*!40000 ALTER TABLE `inv_meds` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `inv_medsup`
--

DROP TABLE IF EXISTS `inv_medsup`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `inv_medsup` (
  `med_supId` int NOT NULL AUTO_INCREMENT,
  `prod_name` varchar(225) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `stck_in` int NOT NULL,
  `stck_out` int NOT NULL,
  `stck_expired` int NOT NULL,
  `stck_avl` int NOT NULL,
  PRIMARY KEY (`med_supId`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `inv_medsup`
--

LOCK TABLES `inv_medsup` WRITE;
/*!40000 ALTER TABLE `inv_medsup` DISABLE KEYS */;
INSERT INTO `inv_medsup` VALUES (4,'dsadadas',2,2,2,2),(5,'dsadsa',2,2,2,2);
/*!40000 ALTER TABLE `inv_medsup` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `p_appointment`
--

DROP TABLE IF EXISTS `p_appointment`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `p_appointment` (
  `id` int NOT NULL AUTO_INCREMENT,
  `p_name` varchar(45) NOT NULL,
  `p_purpose` varchar(45) NOT NULL,
  `datetime` datetime DEFAULT NULL,
  `a_healthworker` varchar(45) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `p_appointment`
--

LOCK TABLES `p_appointment` WRITE;
/*!40000 ALTER TABLE `p_appointment` DISABLE KEYS */;
INSERT INTO `p_appointment` VALUES (1,'aFDSFSD','Follow-Up','2024-09-12 13:52:00','fdfsd fdsfsd'),(2,'dsadasd','Follow-Up','2024-09-12 17:56:00','fdfsd fdsfsd'),(3,'fsdfgsfds','Consultation','2024-09-19 16:14:00','fdfsd fdsfsd'),(5,'dsadasd','Follow-Up','2024-09-25 01:25:00','fdfsd fdsfsd'),(7,'fsdfgsfds','Follow-Up','2024-09-30 21:02:00','fdfsd fdsfsd'),(10,'dsadasd','Follow-Up','2024-09-30 21:20:00','fdfsd fdsfsd');
/*!40000 ALTER TABLE `p_appointment` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `patient`
--

DROP TABLE IF EXISTS `patient`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `patient` (
  `p_id` int NOT NULL AUTO_INCREMENT,
  `p_name` varchar(225) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `p_age` varchar(225) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `p_bday` varchar(225) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `p_address` varchar(225) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `p_contper` varchar(225) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `p_type` varchar(225) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`p_id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `patient`
--

LOCK TABLES `patient` WRITE;
/*!40000 ALTER TABLE `patient` DISABLE KEYS */;
INSERT INTO `patient` VALUES (8,'fsdfgsfds','3','0343-03-04','fdfs','fdsfsd','PWD'),(9,'dsadasd','21','1111-03-11','dasda','dasdsad','PWD'),(10,'dsadasd','21','1111-03-11','dasda','dasdsad','Senior'),(11,'dsdsada','2','1113-11-11','dsadad','dsadsa','Senior');
/*!40000 ALTER TABLE `patient` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `user` (
  `iduser` int NOT NULL AUTO_INCREMENT,
  `adname` varchar(255) NOT NULL,
  `adsurname` varchar(255) NOT NULL,
  `adusername` varchar(255) NOT NULL,
  `adpass` varchar(45) NOT NULL,
  PRIMARY KEY (`iduser`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user`
--

LOCK TABLES `user` WRITE;
/*!40000 ALTER TABLE `user` DISABLE KEYS */;
/*!40000 ALTER TABLE `user` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2024-09-14 21:34:57
