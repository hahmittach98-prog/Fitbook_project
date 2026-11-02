-- MySQL dump 10.13  Distrib 9.1.0, for Win64 (x86_64)
--
-- Host: localhost    Database: fitbook
-- ------------------------------------------------------
-- Server version	9.1.0

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

--
-- Table structure for table `avis`
--

DROP TABLE IF EXISTS `avis`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `avis` (
  `id` int NOT NULL AUTO_INCREMENT,
  `note` int NOT NULL,
  `commentaire` longtext,
  `date_avis` datetime NOT NULL,
  `statut_moderation` varchar(50) NOT NULL,
  `user_id` int NOT NULL,
  `coach_id` int DEFAULT NULL,
  `salle_id` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_8F91ABF0A76ED395` (`user_id`),
  KEY `IDX_8F91ABF03C105691` (`coach_id`),
  KEY `IDX_8F91ABF0DC304035` (`salle_id`),
  CONSTRAINT `FK_8F91ABF03C105691` FOREIGN KEY (`coach_id`) REFERENCES `coachs` (`id`),
  CONSTRAINT `FK_8F91ABF0A76ED395` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  CONSTRAINT `FK_8F91ABF0DC304035` FOREIGN KEY (`salle_id`) REFERENCES `salles` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `avis`
--

LOCK TABLES `avis` WRITE;
/*!40000 ALTER TABLE `avis` DISABLE KEYS */;
INSERT INTO `avis` VALUES (3,4,'pue la merde','2026-11-02 13:08:25','masque',18,NULL,6);
/*!40000 ALTER TABLE `avis` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `coachs`
--

DROP TABLE IF EXISTS `coachs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `coachs` (
  `id` int NOT NULL AUTO_INCREMENT,
  `specialite` varchar(255) NOT NULL,
  `description` longtext,
  `tarif_horaire` double DEFAULT NULL,
  `photo` varchar(255) DEFAULT NULL,
  `user_id` int NOT NULL,
  `image_name` varchar(255) DEFAULT NULL,
  `image_size` int DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_89E318FDA76ED395` (`user_id`),
  CONSTRAINT `FK_89E318FDA76ED395` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `coachs`
--

LOCK TABLES `coachs` WRITE;
/*!40000 ALTER TABLE `coachs` DISABLE KEYS */;
INSERT INTO `coachs` VALUES (8,'Zumba','En tant que coach de Zumba, ma mission est de transmettre mon énergie, ma passion et ma bonne humeur à travers la danse. Chaque cours est un moment de partage, de plaisir et de dépassement de soi.\n\nMon objectif est de faire bouger les participants, de leur permettre de se défouler, de se dépenser physiquement tout en s\'amusant. À travers des rythmes latinos, afro, reggaeton et bien d\'autres styles musicaux, je crée une ambiance conviviale et motivante où chacun peut évoluer à son propre rythme.',14,NULL,19,NULL,NULL,'2026-11-02 01:27:20','2026-11-02 01:27:20'),(9,'Pilates,zumba','Je suis une coach sportive passionnée par le sport, la danse et le bien-être. J\'encadre des cours de Pilates et de Zumba adaptés à tous les niveaux.\n\nLors de mes séances de Pilates, j\'aide les participants à renforcer leurs muscles, améliorer leur posture, développer leur souplesse et maîtriser leur respiration.\n\nPendant mes cours de Zumba, je partage mon énergie et ma bonne humeur à travers des chorégraphies rythmées et accessibles à tous.\n\nMon objectif est d\'accompagner chaque participant dans sa progression, de lui donner confiance en lui et surtout de lui faire prendre plaisir à faire du sport dans une ambiance conviviale et motivante ! ',26,NULL,22,NULL,NULL,'2026-11-02 20:00:22','2026-11-02 20:18:05');
/*!40000 ALTER TABLE `coachs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cours`
--

DROP TABLE IF EXISTS `cours`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cours` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nom` varchar(50) NOT NULL,
  `description` longtext NOT NULL,
  `niveau` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cours`
--

LOCK TABLES `cours` WRITE;
/*!40000 ALTER TABLE `cours` DISABLE KEYS */;
INSERT INTO `cours` VALUES (1,'Yoga','Notre espace Yoga est un lieu dédié à la détente, à l\'équilibre et au bien-être du corps et de l\'esprit. Dans une ambiance calme et apaisante, nos séances permettent de travailler la souplesse, la respiration, la posture et la relaxation.\n\nQue vous soyez débutant ou pratiquant confirmé, nos cours sont adaptés à tous les niveaux afin de vous aider à évacuer le stress, à retrouver votre énergie et à renforcer votre harmonie intérieure.\n\nNotre objectif : vous offrir un moment pour vous, pour vous recentrer, vous détendre et prendre soin de votre corps et de votre esprit. ✨🌸','debutant'),(4,'zumba','La Zumba est une activité sportive qui combine la danse et le fitness sur des rythmes musicaux entraînants comme la salsa, le merengue, le reggaeton et les musiques afro-latines. Elle permet de faire travailler tout le corps, d\'améliorer son endurance, de brûler des calories et de renforcer sa condition physique.\n\nAccessible à tous les niveaux, la Zumba se pratique dans une ambiance festive et conviviale où chacun peut danser à son rythme tout en prenant du plaisir.','debutant'),(5,'pilates','Le Pilates est une activité douce qui permet de renforcer les muscles profonds, d\'améliorer la posture et de développer la souplesse. Les exercices sont basés sur la respiration, le contrôle des mouvements et l\'équilibre du corps.','difficile'),(6,'Body Pump','Le Body Pump est un cours collectif de renforcement musculaire qui utilise des barres et des poids légers à modérés. Il permet de travailler l\'ensemble du corps, de développer la force et d\'améliorer l\'endurance musculaire au rythme de la musique.','moyen'),(7,'Cycling','Le Cycling est un cours de vélo en salle accompagné de musique motivante. Il permet de travailler le cardio, de renforcer les jambes et de brûler des calories. Les participants adaptent leur intensité selon leur niveau et leurs objectifs.','facile');
/*!40000 ALTER TABLE `cours` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `disponibilites`
--

DROP TABLE IF EXISTS `disponibilites`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `disponibilites` (
  `id` int NOT NULL AUTO_INCREMENT,
  `jour_semaine` varchar(50) NOT NULL,
  `heure_debut` time NOT NULL,
  `heure_fin` time NOT NULL,
  `coach_id` int DEFAULT NULL,
  `salle_id` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_B0F3489C3C105691` (`coach_id`),
  KEY `IDX_B0F3489CDC304035` (`salle_id`),
  CONSTRAINT `FK_B0F3489C3C105691` FOREIGN KEY (`coach_id`) REFERENCES `coachs` (`id`),
  CONSTRAINT `FK_B0F3489CDC304035` FOREIGN KEY (`salle_id`) REFERENCES `salles` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `disponibilites`
--

LOCK TABLES `disponibilites` WRITE;
/*!40000 ALTER TABLE `disponibilites` DISABLE KEYS */;
INSERT INTO `disponibilites` VALUES (1,'Mardi','10:00:00','12:00:00',8,NULL),(2,'Jeudi','14:00:00','17:00:00',8,NULL),(3,'Lundi','09:00:00','17:00:00',NULL,11),(4,'Mardi','09:00:00','17:00:00',NULL,11),(5,'Mercredi','09:00:00','18:00:00',NULL,11);
/*!40000 ALTER TABLE `disponibilites` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `doctrine_migration_versions`
--

DROP TABLE IF EXISTS `doctrine_migration_versions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `doctrine_migration_versions` (
  `version` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `executed_at` datetime DEFAULT NULL,
  `execution_time` int DEFAULT NULL,
  PRIMARY KEY (`version`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `doctrine_migration_versions`
--

LOCK TABLES `doctrine_migration_versions` WRITE;
/*!40000 ALTER TABLE `doctrine_migration_versions` DISABLE KEYS */;
INSERT INTO `doctrine_migration_versions` VALUES ('DoctrineMigrations\\Version20261029204206','2026-10-29 20:42:28',1211),('DoctrineMigrations\\Version20261030002821','2026-10-30 00:29:12',482);
/*!40000 ALTER TABLE `doctrine_migration_versions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `messenger_messages`
--

DROP TABLE IF EXISTS `messenger_messages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `messenger_messages` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `body` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `headers` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue_name` varchar(190) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL,
  `available_at` datetime NOT NULL,
  `delivered_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_75EA56E0FB7336F0E3BD61CE16BA31DBBF396750` (`queue_name`,`available_at`,`delivered_at`,`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `messenger_messages`
--

LOCK TABLES `messenger_messages` WRITE;
/*!40000 ALTER TABLE `messenger_messages` DISABLE KEYS */;
INSERT INTO `messenger_messages` VALUES (11,'O:36:\\\"Symfony\\\\Component\\\\Messenger\\\\Envelope\\\":2:{s:44:\\\"\\0Symfony\\\\Component\\\\Messenger\\\\Envelope\\0stamps\\\";a:1:{s:46:\\\"Symfony\\\\Component\\\\Messenger\\\\Stamp\\\\BusNameStamp\\\";a:1:{i:0;O:46:\\\"Symfony\\\\Component\\\\Messenger\\\\Stamp\\\\BusNameStamp\\\":1:{s:55:\\\"\\0Symfony\\\\Component\\\\Messenger\\\\Stamp\\\\BusNameStamp\\0busName\\\";s:21:\\\"messenger.bus.default\\\";}}}s:45:\\\"\\0Symfony\\\\Component\\\\Messenger\\\\Envelope\\0message\\\";O:51:\\\"Symfony\\\\Component\\\\Mailer\\\\Messenger\\\\SendEmailMessage\\\":2:{s:60:\\\"\\0Symfony\\\\Component\\\\Mailer\\\\Messenger\\\\SendEmailMessage\\0message\\\";O:28:\\\"Symfony\\\\Component\\\\Mime\\\\Email\\\":6:{i:0;s:64:\\\"Votre réservation pour la séance du 26/11/2026 est confirmée.\\\";i:1;s:5:\\\"utf-8\\\";i:2;N;i:3;N;i:4;a:0:{}i:5;a:2:{i:0;O:37:\\\"Symfony\\\\Component\\\\Mime\\\\Header\\\\Headers\\\":2:{s:46:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\Headers\\0headers\\\";a:3:{s:4:\\\"from\\\";a:1:{i:0;O:47:\\\"Symfony\\\\Component\\\\Mime\\\\Header\\\\MailboxListHeader\\\":5:{s:50:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0name\\\";s:4:\\\"From\\\";s:56:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0lineLength\\\";i:76;s:50:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0lang\\\";N;s:53:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0charset\\\";s:5:\\\"utf-8\\\";s:58:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\MailboxListHeader\\0addresses\\\";a:1:{i:0;O:30:\\\"Symfony\\\\Component\\\\Mime\\\\Address\\\":2:{s:39:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Address\\0address\\\";s:22:\\\"no-reply@fitbook.local\\\";s:36:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Address\\0name\\\";s:0:\\\"\\\";}}}}s:2:\\\"to\\\";a:1:{i:0;O:47:\\\"Symfony\\\\Component\\\\Mime\\\\Header\\\\MailboxListHeader\\\":5:{s:50:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0name\\\";s:2:\\\"To\\\";s:56:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0lineLength\\\";i:76;s:50:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0lang\\\";N;s:53:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0charset\\\";s:5:\\\"utf-8\\\";s:58:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\MailboxListHeader\\0addresses\\\";a:1:{i:0;O:30:\\\"Symfony\\\\Component\\\\Mime\\\\Address\\\":2:{s:39:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Address\\0address\\\";s:17:\\\"admin@fitbook.com\\\";s:36:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Address\\0name\\\";s:0:\\\"\\\";}}}}s:7:\\\"subject\\\";a:1:{i:0;O:48:\\\"Symfony\\\\Component\\\\Mime\\\\Header\\\\UnstructuredHeader\\\":5:{s:50:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0name\\\";s:7:\\\"Subject\\\";s:56:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0lineLength\\\";i:76;s:50:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0lang\\\";N;s:53:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0charset\\\";s:5:\\\"utf-8\\\";s:55:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\UnstructuredHeader\\0value\\\";s:33:\\\"FitBook : réservation confirmée\\\";}}}s:49:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\Headers\\0lineLength\\\";i:76;}i:1;N;}}s:61:\\\"\\0Symfony\\\\Component\\\\Mailer\\\\Messenger\\\\SendEmailMessage\\0envelope\\\";N;}}','[]','default','2026-11-02 13:14:42','2026-11-02 13:14:42',NULL);
/*!40000 ALTER TABLE `messenger_messages` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `reservations`
--

DROP TABLE IF EXISTS `reservations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `reservations` (
  `id` int NOT NULL AUTO_INCREMENT,
  `date_reservation` datetime NOT NULL,
  `statut` varchar(50) NOT NULL,
  `date_annulation` datetime DEFAULT NULL,
  `user_id` int NOT NULL,
  `seance_id` int NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_4DA239A76ED395` (`user_id`),
  KEY `IDX_4DA239E3797A94` (`seance_id`),
  CONSTRAINT `FK_4DA239A76ED395` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  CONSTRAINT `FK_4DA239E3797A94` FOREIGN KEY (`seance_id`) REFERENCES `seances` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `reservations`
--

LOCK TABLES `reservations` WRITE;
/*!40000 ALTER TABLE `reservations` DISABLE KEYS */;
INSERT INTO `reservations` VALUES (8,'2026-11-02 13:14:38','annulee','2026-11-02 19:17:07',3,10,'2026-11-02 13:14:38','2026-11-02 19:17:07'),(9,'2026-11-02 18:31:14','confirmee',NULL,18,12,'2026-11-02 18:31:14','2026-11-02 18:31:14'),(10,'2026-11-02 18:56:56','confirmee',NULL,18,10,'2026-11-02 18:56:56','2026-11-02 18:56:56'),(11,'2026-11-02 19:04:39','annulee','2026-11-02 19:17:06',3,12,'2026-11-02 19:04:39','2026-11-02 19:17:06'),(12,'2026-11-02 19:22:07','confirmee',NULL,3,13,'2026-11-02 19:22:07','2026-11-02 19:22:07'),(13,'2026-11-02 19:39:02','confirmee',NULL,21,15,'2026-11-02 19:39:02','2026-11-02 19:39:02');
/*!40000 ALTER TABLE `reservations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `salle_coach`
--

DROP TABLE IF EXISTS `salle_coach`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `salle_coach` (
  `salle_id` int NOT NULL,
  `coach_id` int NOT NULL,
  PRIMARY KEY (`salle_id`,`coach_id`),
  KEY `IDX_D25C6EA5DC304035` (`salle_id`),
  KEY `IDX_D25C6EA53C105691` (`coach_id`),
  CONSTRAINT `FK_D25C6EA53C105691` FOREIGN KEY (`coach_id`) REFERENCES `coachs` (`id`) ON DELETE CASCADE,
  CONSTRAINT `FK_D25C6EA5DC304035` FOREIGN KEY (`salle_id`) REFERENCES `salles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `salle_coach`
--

LOCK TABLES `salle_coach` WRITE;
/*!40000 ALTER TABLE `salle_coach` DISABLE KEYS */;
/*!40000 ALTER TABLE `salle_coach` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `salle_cours`
--

DROP TABLE IF EXISTS `salle_cours`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `salle_cours` (
  `salle_id` int NOT NULL,
  `cours_id` int NOT NULL,
  PRIMARY KEY (`salle_id`,`cours_id`),
  KEY `IDX_10CF8FF5DC304035` (`salle_id`),
  KEY `IDX_10CF8FF57ECF78B0` (`cours_id`),
  CONSTRAINT `FK_10CF8FF57ECF78B0` FOREIGN KEY (`cours_id`) REFERENCES `cours` (`id`) ON DELETE CASCADE,
  CONSTRAINT `FK_10CF8FF5DC304035` FOREIGN KEY (`salle_id`) REFERENCES `salles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `salle_cours`
--

LOCK TABLES `salle_cours` WRITE;
/*!40000 ALTER TABLE `salle_cours` DISABLE KEYS */;
INSERT INTO `salle_cours` VALUES (6,6),(6,7),(11,4),(11,5),(12,1),(12,5),(12,6);
/*!40000 ALTER TABLE `salle_cours` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `salles`
--

DROP TABLE IF EXISTS `salles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `salles` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nom` varchar(255) NOT NULL,
  `ville` varchar(255) NOT NULL,
  `adresse` varchar(255) NOT NULL,
  `capacite` int NOT NULL,
  `telephone` varchar(255) DEFAULT NULL,
  `description` longtext,
  `photo` varchar(255) DEFAULT NULL,
  `gestionnaire_id` int NOT NULL,
  `image_name` varchar(255) DEFAULT NULL,
  `image_size` int DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_799D45AA6885AC1B` (`gestionnaire_id`),
  CONSTRAINT `FK_799D45AA6885AC1B` FOREIGN KEY (`gestionnaire_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `salles`
--

LOCK TABLES `salles` WRITE;
/*!40000 ALTER TABLE `salles` DISABLE KEYS */;
INSERT INTO `salles` VALUES (5,'MagicDanse','Namur','avenue des troupes 34',7,'0489196101','Notre salle de sport est un espace dédié au bien-être, à la remise en forme et au dépassement de soi. Elle offre un environnement convivial, motivant et dynamique où chacun peut pratiquer une activité physique selon ses objectifs et son niveau.\n\nÉquipée de matériel adapté et proposant différentes activités sportives, notamment des cours collectifs de Zumba, notre salle permet à chacun de bouger, de se dépenser et de prendre soin de sa santé dans une ambiance chaleureuse et positive.',NULL,17,'dance-6ae893d34edbf887609000.jpg',13087,'2026-11-02 01:21:36','2026-11-02 13:44:51'),(6,'BasicFit','Bruxelles','rue des pavées 897',55,'0489196102','Basic-Fit est une chaîne de salles de sport qui propose un espace moderne, accessible et convivial, dédié à la remise en forme et au bien-être. Elle met à disposition de ses membres différents équipements de fitness et de musculation, des appareils de cardio-training ainsi que des espaces d\'entraînement adaptés à tous les niveaux.',NULL,17,'basicfit-6ae8918484975904081302.jpg',19551,'2026-11-02 01:23:10','2026-11-02 18:50:30'),(7,'Fit Energy Club','Liege','Avenue des Arts, 45',150,'02 456 78 91',': Fit Energy Club est une salle de sport moderne et dynamique qui propose des équipements de musculation, du cardio-training et des cours collectifs comme la Zumba, le yoga et le fitness. Elle offre un environnement convivial pour tous les niveaux.',NULL,17,'fitclub-6ae8941187c15474950077.jpg',9151,'2026-11-02 01:24:33','2026-11-02 13:45:53'),(8,'Power Fitness Center','Dinant','Rue Saint-Gilles, 28',120,'04 321 56 89','Power Fitness Center est un espace dédié à la remise en forme et au dépassement de soi. La salle dispose de machines de musculation, d\'appareils de cardio et d\'un espace réservé aux entraînements personnalisés.',NULL,17,'power-6ae8919db554b119057019.jpg',11637,'2026-11-02 01:25:17','2026-11-02 13:35:25'),(9,'Wellness & Move','Namur','Rue de Bruxelles, 62',80,'081 234 567','Wellness & Move est une salle de sport axée sur le bien-être et l\'équilibre du corps et de l\'esprit. Elle propose des cours de yoga, de Pilates, de stretching et de relaxation dans une ambiance chaleureuse et apaisante.',NULL,17,'welness-6ae89358333ed418647284.jpg',10939,'2026-11-02 01:25:58','2026-11-02 13:42:48'),(10,'PowerFit Arena','Charleroi','Rue de la Montagne, 37',100,'071 456 789','PowerFit Arena est une salle de sport moderne et conviviale dédiée au fitness, à la musculation et au bien-être. Elle propose différents équipements sportifs ainsi que des cours collectifs comme la Zumba, le yoga, le Pilates et le Body Pump. Accessible aux débutants comme aux sportifs confirmés, elle offre un environnement motivant pour atteindre ses objectifs et améliorer sa condition physique.',NULL,17,'arena-6ae89b0319b55851894272.jpg',26303,'2026-11-02 14:13:36','2026-11-02 14:15:31'),(11,'Fit & Fun Studio','Gand','Rue de la liberté 23',140,'02 567 89 34','Fit & Fun Studio est une salle de sport moderne qui met l\'accent sur le fitness, le plaisir et le bien-être. Elle propose des espaces de musculation, de cardio-training ainsi que des cours collectifs de Zumba, de Pilates et de stretching. Son ambiance chaleureuse permet à chacun de s\'entraîner à son rythme et d\'atteindre ses objectifs sportifs.',NULL,20,'fun-6ae8e64e93983004756473.jpg',4329,'2026-11-02 19:35:46','2026-11-02 19:36:46'),(12,'Vitality Fitness Club','Liège','Avenue des Combattants 56',60,'081 345 678','Vitality Fitness Club est un centre sportif dédié à la santé, à la remise en forme et au dépassement de soi. Il dispose d\'équipements modernes pour la musculation et le cardio, ainsi que de plusieurs cours collectifs comme le yoga, le Body Pump et le Cycling. L\'établissement accueille tous les niveaux dans un cadre dynamique et motivant.',NULL,20,'vitality-6ae8e9f8d32ef833925717.jpg',51524,'2026-11-02 19:50:56','2026-11-02 19:52:24');
/*!40000 ALTER TABLE `salles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `seances`
--

DROP TABLE IF EXISTS `seances`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `seances` (
  `id` int NOT NULL AUTO_INCREMENT,
  `date_seance` date NOT NULL,
  `heure_debut` time NOT NULL,
  `heure_fin` time NOT NULL,
  `capacite_max` int NOT NULL,
  `prix` double NOT NULL,
  `statut` varchar(50) NOT NULL,
  `coach_id` int NOT NULL,
  `salle_id` int NOT NULL,
  `cours_id` int NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_FC699FF13C105691` (`coach_id`),
  KEY `IDX_FC699FF1DC304035` (`salle_id`),
  KEY `IDX_FC699FF17ECF78B0` (`cours_id`),
  CONSTRAINT `FK_FC699FF13C105691` FOREIGN KEY (`coach_id`) REFERENCES `coachs` (`id`),
  CONSTRAINT `FK_FC699FF17ECF78B0` FOREIGN KEY (`cours_id`) REFERENCES `cours` (`id`),
  CONSTRAINT `FK_FC699FF1DC304035` FOREIGN KEY (`salle_id`) REFERENCES `salles` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `seances`
--

LOCK TABLES `seances` WRITE;
/*!40000 ALTER TABLE `seances` DISABLE KEYS */;
INSERT INTO `seances` VALUES (10,'2026-11-26','14:00:00','15:00:00',10,9,'ouverte',8,8,4,'2026-11-02 12:59:46','2026-11-02 12:59:46'),(11,'2026-12-11','18:00:00','19:00:00',10,10,'ouverte',8,7,7,'2026-11-02 13:02:28','2026-11-02 13:02:28'),(12,'2026-11-04','07:00:00','08:00:00',13,22,'ouverte',8,9,6,'2026-11-02 13:55:00','2026-11-02 13:55:00'),(13,'2026-12-02','12:00:00','13:00:00',8,25,'ouverte',8,8,5,'2026-11-02 14:09:06','2026-11-02 14:09:06'),(15,'2026-11-15','12:30:00','14:30:00',8,25,'ouverte',8,9,1,'2026-11-02 19:31:42','2026-11-02 19:31:42');
/*!40000 ALTER TABLE `seances` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user_coach`
--

DROP TABLE IF EXISTS `user_coach`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `user_coach` (
  `user_id` int NOT NULL,
  `coach_id` int NOT NULL,
  PRIMARY KEY (`user_id`,`coach_id`),
  KEY `IDX_DD9B9694A76ED395` (`user_id`),
  KEY `IDX_DD9B96943C105691` (`coach_id`),
  CONSTRAINT `FK_DD9B96943C105691` FOREIGN KEY (`coach_id`) REFERENCES `coachs` (`id`) ON DELETE CASCADE,
  CONSTRAINT `FK_DD9B9694A76ED395` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_coach`
--

LOCK TABLES `user_coach` WRITE;
/*!40000 ALTER TABLE `user_coach` DISABLE KEYS */;
/*!40000 ALTER TABLE `user_coach` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user_cours`
--

DROP TABLE IF EXISTS `user_cours`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `user_cours` (
  `user_id` int NOT NULL,
  `cours_id` int NOT NULL,
  PRIMARY KEY (`user_id`,`cours_id`),
  KEY `IDX_1F0877C4A76ED395` (`user_id`),
  KEY `IDX_1F0877C47ECF78B0` (`cours_id`),
  CONSTRAINT `FK_1F0877C47ECF78B0` FOREIGN KEY (`cours_id`) REFERENCES `cours` (`id`) ON DELETE CASCADE,
  CONSTRAINT `FK_1F0877C4A76ED395` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_cours`
--

LOCK TABLES `user_cours` WRITE;
/*!40000 ALTER TABLE `user_cours` DISABLE KEYS */;
/*!40000 ALTER TABLE `user_cours` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user_salle`
--

DROP TABLE IF EXISTS `user_salle`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `user_salle` (
  `user_id` int NOT NULL,
  `salle_id` int NOT NULL,
  PRIMARY KEY (`user_id`,`salle_id`),
  KEY `IDX_AC558504A76ED395` (`user_id`),
  KEY `IDX_AC558504DC304035` (`salle_id`),
  CONSTRAINT `FK_AC558504A76ED395` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `FK_AC558504DC304035` FOREIGN KEY (`salle_id`) REFERENCES `salles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_salle`
--

LOCK TABLES `user_salle` WRITE;
/*!40000 ALTER TABLE `user_salle` DISABLE KEYS */;
INSERT INTO `user_salle` VALUES (18,6);
/*!40000 ALTER TABLE `user_salle` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `email` varchar(180) NOT NULL,
  `roles` json NOT NULL,
  `password` varchar(255) NOT NULL,
  `nom` varchar(50) NOT NULL,
  `prenom` varchar(50) NOT NULL,
  `telephone` varchar(50) DEFAULT NULL,
  `is_verified` tinyint NOT NULL,
  `date_inscription` datetime NOT NULL,
  `token_confirmation` varchar(100) DEFAULT NULL,
  `image_name` varchar(255) DEFAULT NULL,
  `image_size` int DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_IDENTIFIER_EMAIL` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (3,'admin@fitbook.com','[\"ROLE_ADMIN\"]','$2y$13$gzE5yN.j9/YgBfH4C1ls3uoSOByKwQVK5iErQSkIPWg4onXlr0hpq','Admin','FitBook','0489196101',1,'2026-10-30 01:38:31',NULL,'images-1-6ae88e9691869489559832.jpg',23611,'2026-10-30 01:38:31','2026-11-02 13:22:30'),(17,'hajar@gerant.com','[\"ROLE_SALLE\"]','$2y$13$bP4RoFKfWx4ILME328oMWemTp0db3GhuZxkOzsSuHpsgoTSflzBSS','Ahmittach ','Hajar','0489196101',1,'2026-11-02 01:16:12',NULL,'images-6ae879c4a95e5530927736.jpg',39109,'2026-11-02 01:16:12','2026-11-02 11:55:49'),(18,'farid@fitbook.com','[\"ROLE_SPORTIF\"]','$2y$13$Tiycl3kd844dfoLFVcNSp.27pB/HoFiaO9Xwoiza9rTTCbijL1gpW','Ahmittach','Farid','0473804440',1,'2026-11-02 01:18:06',NULL,'sportif-6ae8d75f6155d386061496.jpg',18956,'2026-11-02 01:18:06','2026-11-02 18:33:03'),(19,'salma@coach.com','[\"ROLE_COACH\"]','$2y$13$AAarez2M0s/0MNpOi/qqPOCsfs1H7OJl2wKrt.d7NkBBIrvb8BIDG','Takkal','Salma','0456897564',1,'2026-11-02 01:20:10',NULL,'coach3-6ae895a22e4e0070003745.jpg',15604,'2026-11-02 01:20:10','2026-11-02 13:52:34'),(20,'jul@gerant.com','[\"ROLE_SALLE\"]','$2y$13$FXrtcAVc.UVgDL5rltCsLO49XkwYFN1EwNBgpR1Zq68qmIJ7ocWO6','Mathieu','jul','0498 456 098',1,'2026-11-02 19:33:39',NULL,NULL,NULL,'2026-11-02 19:33:39','2026-11-02 19:34:08'),(21,'fatiha@fitbook.com','[\"ROLE_SPORTIF\"]','$2y$13$71zJL1A28JFtWjwqilVy1uJpM5td9FS3NOdSFqAA/rLQFuh6XuCGW','Etem','Fatiha',NULL,1,'2026-11-02 19:37:48',NULL,'sportif2-6ae8e7397c69a628134810.jpg',22188,'2026-11-02 19:37:48','2026-11-02 19:40:41'),(22,'jams@coach.com','[\"ROLE_COACH\"]','$2y$13$/KpezFI4/1iWa6VeQOs4D.tbDrc5gHV1/CBToyklpjTa.qc0RHiAm','Matis','jams','0489196101',1,'2026-11-02 19:54:58',NULL,'coach2-6ae8f00ee52d8234066441.jpg',23970,'2026-11-02 19:54:58','2026-11-02 20:18:22');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping routines for database 'fitbook'
--
/*!50003 DROP PROCEDURE IF EXISTS `sp_note_moyenne_salle` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_AUTO_VALUE_ON_ZERO' */ ;
DELIMITER ;;
CREATE DEFINER=`fitbook_hajar`@`localhost` PROCEDURE `sp_note_moyenne_salle`(IN p_salle_id INT)
BEGIN
    SELECT p_salle_id AS salle_id,
           COALESCE(ROUND(AVG(note), 2), 0) AS note_moyenne,
           COUNT(*) AS nombre_avis
    FROM avis
    WHERE salle_id = p_salle_id AND statut_moderation = 'valide';
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `sp_places_restantes` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_AUTO_VALUE_ON_ZERO' */ ;
DELIMITER ;;
CREATE DEFINER=`fitbook_hajar`@`localhost` PROCEDURE `sp_places_restantes`(IN p_seance_id INT)
BEGIN
    SELECT s.id,
           s.capacite_max,
           COUNT(r.id) AS reservations_confirmees,
           s.capacite_max - COUNT(r.id) AS places_restantes
    FROM seances s
    LEFT JOIN reservations r ON r.seance_id = s.id AND r.statut = 'confirmee'
    WHERE s.id = p_seance_id
    GROUP BY s.id, s.capacite_max;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `sp_reservations_utilisateur` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_AUTO_VALUE_ON_ZERO' */ ;
DELIMITER ;;
CREATE DEFINER=`fitbook_hajar`@`localhost` PROCEDURE `sp_reservations_utilisateur`(IN p_user_id INT)
BEGIN
    SELECT r.id, r.statut, r.date_reservation,
           se.date_seance, se.heure_debut, se.heure_fin,
           c.nom AS cours, sa.nom AS salles
    FROM reservations r
    JOIN seances se ON se.id = r.seance_id
    JOIN cours c ON c.id = se.cours_id
    JOIN salles sa ON sa.id = se.salle_id
    WHERE r.user_id = p_user_id
    ORDER BY se.date_seance DESC;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-11-02 21:28:14
