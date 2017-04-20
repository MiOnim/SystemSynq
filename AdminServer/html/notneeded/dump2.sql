-- MySQL dump 10.13  Distrib 5.5.54, for debian-linux-gnu (x86_64)
--
-- Host: localhost    Database: systemsynq
-- ------------------------------------------------------
-- Server version	5.5.54-0ubuntu0.14.04.1

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Current Database: `systemsynq`
--

CREATE DATABASE /*!32312 IF NOT EXISTS*/ `systemsynq` /*!40100 DEFAULT CHARACTER SET latin1 */;

USE `systemsynq`;

--
-- Table structure for table `HardwareSoftware`
--

DROP TABLE IF EXISTS `HardwareSoftware`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `HardwareSoftware` (
  `ID` varchar(255) DEFAULT NULL,
  `Name` varchar(255) DEFAULT NULL,
  `CPU_Usage` varchar(255) DEFAULT NULL,
  `RAM_Free` varchar(255) DEFAULT NULL,
  `DISK_Free` varchar(255) DEFAULT NULL,
  `Processes_Total` varchar(255) DEFAULT NULL,
  `Processes_Unusual` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `HardwareSoftware`
--

LOCK TABLES `HardwareSoftware` WRITE;
/*!40000 ALTER TABLE `HardwareSoftware` DISABLE KEYS */;
/*!40000 ALTER TABLE `HardwareSoftware` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `MachineInfo`
--

DROP TABLE IF EXISTS `MachineInfo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `MachineInfo` (
  `ID` varchar(255) DEFAULT NULL,
  `Name` varchar(255) DEFAULT NULL,
  `CPU_Cores` varchar(255) DEFAULT NULL,
  `Clock_Speed` varchar(255) DEFAULT NULL,
  `RAM_Total` varchar(255) DEFAULT NULL,
  `DISK_Total` varchar(255) DEFAULT NULL,
  `On_Off_Flag` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `MachineInfo`
--

LOCK TABLES `MachineInfo` WRITE;
/*!40000 ALTER TABLE `MachineInfo` DISABLE KEYS */;
/*!40000 ALTER TABLE `MachineInfo` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Notifications`
--

DROP TABLE IF EXISTS `Notifications`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Notifications` (
  `ID` varchar(255) DEFAULT NULL,
  `Notifications` varchar(255) DEFAULT NULL,
  `Uptime` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Notifications`
--

LOCK TABLES `Notifications` WRITE;
/*!40000 ALTER TABLE `Notifications` DISABLE KEYS */;
INSERT INTO `Notifications` VALUES ('1','Offline, Unusual process, Disconnect from Network, Abnormally high RAM usage, Low DISK space, Abnormally high CPU Usage','6 hours'),('2','Unusual process','5 hours'),('3','Disconnect from Network','2.5 hours'),('4','Abnormally high RAM usage','0.5 hours'),('5','Low DISK space','23 hours'),('6','Abnormally high CPU Usage','127 hours');
/*!40000 ALTER TABLE `Notifications` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sessions`
--

DROP TABLE IF EXISTS `sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sessions` (
  `sid` varchar(255) DEFAULT NULL,
  `user` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sessions`
--

LOCK TABLES `sessions` WRITE;
/*!40000 ALTER TABLE `sessions` DISABLE KEYS */;
INSERT INTO `sessions` VALUES ('1095239199','admin'),('1556387894','admin'),('673517808','admin'),('937939447','admin'),('425547398','admin'),('1151843643','admin'),('613802138','admin'),('504071283','admin'),('774892333','admin'),('333347737','admin'),('1976641442','admin'),('315860743','admin'),('466621425','hello'),('1521487634','another test'),('646353394','admin'),('2101208410','admin'),('1906089579','admin'),('1778586434','admin'),('2013379738','admin'),('1777217575','admin'),('705795292','admin'),('1416427282','admin'),('1786905478','admin'),('408795248','admin'),('756185972','admin'),('987467062','test'),('1673412384','admin'),('666043228','admin'),('2037398695','admin'),('269647326','admin'),('889286584','admin'),('517831161','admin'),('1445152892','admin'),('1438309548','admin'),('460596229','admin'),('1976850414','admin'),('2001018801','admin'),('1036661173','admin'),('1821380217','admin'),('1707221856','admin'),('1184349155','admin'),('1615760213','admin'),('334726198','admin'),('1615483204','admin'),('728495583','admin'),('1354030369','admin'),('816099471','admin'),('1433227361','admin'),('552913185','admin'),('249508022','admin'),('1418861983','admin'),('511356034','admin'),('1747282487','admin'),('1925042876','admin'),('1471118429','admin'),('1528428051','admin'),('481837254','admin');
/*!40000 ALTER TABLE `sessions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `user` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `privileges` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES ('admin','admin','all'),('hello','hi there','hello'),('test','$2y$10$kFV6z4PcwYgrqW6FgW2sk.cWjzcfEp4q6YpbI0TZBaenq/7flY6PG','test'),('another test','$2y$10$arwkPd0XIzZODROlTKrhOuNesn2uVYiNLFSOeqYguP/IlHMW35LBy','another test'),('one more test','$2y$10$anficB4eIf68asiaYsxkpu7sSQ6XcOs8buOgV.3FzsdyMQYP4g5xy','one more test');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2017-04-19 23:09:14
