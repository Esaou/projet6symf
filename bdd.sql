-- MySQL dump 10.13  Distrib 5.7.31, for Win64 (x86_64)
--
-- Host: 127.0.0.1    Database: projet6
-- ------------------------------------------------------
-- Server version	5.7.31

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
-- Table structure for table `category`
--

DROP TABLE IF EXISTS `category`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_64C19C15E237E06` (`name`),
  UNIQUE KEY `UNIQ_64C19C1989D9B62` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=546 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `category`
--

LOCK TABLES `category` WRITE;
/*!40000 ALTER TABLE `category` DISABLE KEYS */;
INSERT INTO `category` VALUES (541,'Grabs','Grabs'),(542,'Rotation verticale','Rotation-verticale'),(543,'Rotation horizontale','Rotation-horizontale'),(544,'Switch','Switch'),(545,'Fly style','Fly-style');
/*!40000 ALTER TABLE `category` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `doctrine_migration_versions`
--

DROP TABLE IF EXISTS `doctrine_migration_versions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `doctrine_migration_versions` (
  `version` varchar(191) COLLATE utf8_unicode_ci NOT NULL,
  `executed_at` datetime DEFAULT NULL,
  `execution_time` int(11) DEFAULT NULL,
  PRIMARY KEY (`version`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `doctrine_migration_versions`
--

LOCK TABLES `doctrine_migration_versions` WRITE;
/*!40000 ALTER TABLE `doctrine_migration_versions` DISABLE KEYS */;
INSERT INTO `doctrine_migration_versions` VALUES ('DoctrineMigrations\\Version20211208104336','2021-12-08 10:44:10',62),('DoctrineMigrations\\Version20211208104712','2021-12-08 10:47:22',81),('DoctrineMigrations\\Version20211208104901','2021-12-08 10:49:06',80),('DoctrineMigrations\\Version20211208104946','2021-12-08 10:49:50',72),('DoctrineMigrations\\Version20211208105112','2021-12-08 10:51:15',62),('DoctrineMigrations\\Version20211208105201','2021-12-08 10:52:05',111),('DoctrineMigrations\\Version20211208110015','2021-12-08 11:00:19',233),('DoctrineMigrations\\Version20211208110406','2021-12-08 11:04:10',201),('DoctrineMigrations\\Version20211208110559','2021-12-08 11:06:03',145),('DoctrineMigrations\\Version20211208110849','2021-12-08 11:08:53',254),('DoctrineMigrations\\Version20211208111224','2021-12-08 11:12:28',237),('DoctrineMigrations\\Version20211208125804','2021-12-08 12:58:10',177),('DoctrineMigrations\\Version20211208170636','2021-12-08 17:06:55',560),('DoctrineMigrations\\Version20220104094112','2022-01-04 09:41:27',1138),('DoctrineMigrations\\Version20220104094759','2022-01-04 09:48:09',45),('DoctrineMigrations\\Version20220104095115','2022-01-04 09:52:33',67),('DoctrineMigrations\\Version20220104113339','2022-01-04 11:33:45',229),('DoctrineMigrations\\Version20220316142041','2022-03-16 14:20:51',587);
/*!40000 ALTER TABLE `doctrine_migration_versions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `figure`
--

DROP TABLE IF EXISTS `figure`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `figure` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `updated_at` datetime DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)',
  `user_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_2F57B37A5E237E06` (`name`),
  UNIQUE KEY `UNIQ_2F57B37A989D9B62` (`slug`),
  KEY `IDX_2F57B37AA76ED395` (`user_id`),
  KEY `IDX_2F57B37A12469DE2` (`category_id`),
  CONSTRAINT `FK_2F57B37A12469DE2` FOREIGN KEY (`category_id`) REFERENCES `category` (`id`),
  CONSTRAINT `FK_2F57B37AA76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1861 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `figure`
--

LOCK TABLES `figure` WRITE;
/*!40000 ALTER TABLE `figure` DISABLE KEYS */;
INSERT INTO `figure` VALUES (1851,'Rotation 180','Il s\'agit d\'une figure relativement simple, et plus précisément d\'un saut sans rotation qui se fait généralement dans un pipe (un U). Le rider s\'élance dans les airs et retombe dans le sens inverse.','Rotation-180','2022-03-23 21:21:41','2022-03-23 21:21:41',591,544),(1852,'Rotation 360','C\'est un mot qui revient souvent dans la bouche des snowboardeurs. Mais pas que, puisqu\'on parle aussi de carving en skis. Mais alors qu\'est-ce que c\'est ? Carver, c\'est tout simplement faire un virage net en se penchant et sans déraper.','Rotation-360','2022-03-22 21:21:41',NULL,593,543),(1853,'Rotation 540','Figures réalisée avec un pied décroché de la fixation, afin de tendre la jambe correspondante pour mettre en évidence le fait que le pied n\'est pas fixé. Ce type de figure est extrêmement dangereuse pour les ligaments du genou en cas de mauvaise réception.','Rotation-540','2022-03-21 21:21:41','2022-03-21 21:21:41',590,544),(1854,'Rotation 720','Les grabs sont la base des figures freestyle en snowboard. C’est le fait d’attraper sa planche avec une ou deux mains pendant un saut. On en compte six de base : indy, mute, nose grab, melon, stalefish et tail grab.','Rotation-720','2022-03-20 21:21:41',NULL,590,542),(1855,'Rotation 810','Le Jib (aussi appelé slide ou grind) est une pratique du snow freestyle qui consiste à glisser sur tous types de modules autres que la neige (rails, troncs d\'arbre, tables etc.)','Rotation-810','2022-03-19 21:21:41','2022-03-19 21:21:41',594,541),(1856,'Indy','Le lispslide consiste à glisser sur un obstacle en mettant la planche perpendiculaire à celui-ci. Un jib à 90 degrés en d\'autres termes. Le lipslide peut se faire en avant ou en arrière. Frontside ou backside, donc.','Indy','2022-03-18 21:21:41',NULL,594,541),(1857,'Japanair','Le Mc Twist est un flip (rotation verticale) agrémenté d\'une vrille. Un saut plutôt périlleux réservé aux riders les plus confirmés. Le champion Shaun White s\'est illustré par un Double Mc Twist 1260 lors de sa session de Half-Pipe aux Jeux Olympiques de Vancouver en 2010.','Japanair','2022-03-17 21:21:41','2022-03-17 21:21:41',593,545),(1858,'Mute','C\'est un jib que le rider effectue sur le nose de la planche, soit la spatule qui se trouve devant lui. La spatule arrière s\'appelle le tail. Le noseslide peut être frontside ou backside.','Mute','2022-03-16 21:21:41',NULL,591,544),(1859,'Nose grab','Il s\'agit d\'un concept assez flou qu\'il est difficile de définir. Le pop est le fait de faire décoller sa board avec un mouvement assez énergique. Certains riders ont plus de pop que d\'autres et ça se voit quand ils sautent par dessus un obstacle.','Nose-grab','2022-03-15 21:21:41','2022-03-15 21:21:41',590,542),(1860,'Sad','Comme son nom l\'indique, le quarter-pipe est un demi half-pipe, soit un module avec pente ascendante se terminant à la verticale. Certains quarter-pipes peuvent atteindre plus de 8 mètres de haut. neige.','Sad','2022-03-14 21:21:41',NULL,591,543);
/*!40000 ALTER TABLE `figure` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `image`
--

DROP TABLE IF EXISTS `image`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `image` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `filename` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `main` tinyint(1) NOT NULL,
  `figure_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_C53D045F5C011B5` (`figure_id`),
  CONSTRAINT `FK_C53D045F5C011B5` FOREIGN KEY (`figure_id`) REFERENCES `figure` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2997 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `image`
--

LOCK TABLES `image` WRITE;
/*!40000 ALTER TABLE `image` DISABLE KEYS */;
INSERT INTO `image` VALUES (2977,'180.jpg',1,1851),(2978,'360.jpg',1,1852),(2979,'540.jpg',1,1853),(2980,'720.jpg',1,1854),(2981,'810.jpg',1,1855),(2982,'indy.jpg',1,1856),(2983,'japanair.jpg',1,1857),(2984,'mute.jpg',1,1858),(2985,'nosegrab.jpg',1,1859),(2986,'sad.jpg',1,1860),(2987,'seatbelt.jpg',0,1857),(2988,'stalefish.jpg',0,1857),(2989,'tailgrab.jpg',0,1859),(2990,'trick15.jpg',0,1852),(2991,'trick16.jpg',0,1851),(2992,'trick17.jpg',0,1858),(2993,'trick18.jpg',0,1856),(2994,'trick19.jpg',0,1851),(2995,'trick20.jpg',0,1854),(2996,'truckdriver.jpg',0,1852);
/*!40000 ALTER TABLE `image` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `message`
--

DROP TABLE IF EXISTS `message`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `message` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `content` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `user_id` int(11) NOT NULL,
  `figure_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_B6BD307FA76ED395` (`user_id`),
  KEY `IDX_B6BD307F5C011B5` (`figure_id`),
  CONSTRAINT `FK_B6BD307F5C011B5` FOREIGN KEY (`figure_id`) REFERENCES `figure` (`id`),
  CONSTRAINT `FK_B6BD307FA76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11770 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `message`
--

LOCK TABLES `message` WRITE;
/*!40000 ALTER TABLE `message` DISABLE KEYS */;
INSERT INTO `message` VALUES (11620,'Je suis surpris de la facilité ...','2022-03-23 21:21:41',593,1853),(11621,'Je trouve tout ce qu\'il me faut sur ce site web, merci !','2022-03-22 21:21:41',593,1854),(11622,'Très beau skill !','2022-03-21 21:21:41',590,1859),(11623,'Je trouve tout ce qu\'il me faut sur ce site web, merci !','2022-03-20 21:21:41',591,1852),(11624,'Comment ne pas aimer ce trick ?','2022-03-19 21:21:41',590,1855),(11625,'Je suis très satisfait de ce site.','2022-03-18 21:21:41',593,1853),(11626,'Je n\'aime pas cette figure','2022-03-17 21:21:41',591,1859),(11627,'Je suis très satisfait de ce site.','2022-03-16 21:21:41',592,1854),(11628,'Je suis surpris de la facilité ...','2022-03-15 21:21:41',594,1852),(11629,'Je trouve tout ce qu\'il me faut sur ce site web, merci !','2022-03-14 21:21:41',594,1860),(11630,'Quelle figure !','2022-03-13 21:21:41',592,1860),(11631,'Comment réaliser cette figure simplement ?','2022-03-12 21:21:41',594,1851),(11632,'Je laisse un avis très positif pour cette figure ! Au top','2022-03-11 21:21:41',591,1855),(11633,'Je suis débutant, cette figure est parfaite !','2022-03-10 21:21:41',593,1855),(11634,'Je trouve tout ce qu\'il me faut sur ce site web, merci !','2022-03-09 21:21:41',593,1860),(11635,'Je suis surpris de la facilité ...','2022-03-08 21:21:41',593,1851),(11636,'Merci des informations !','2022-03-07 21:21:41',592,1857),(11637,'Merci des informations !','2022-03-06 21:21:41',594,1858),(11638,'Je suis débutant, cette figure est parfaite !','2022-03-05 21:21:41',590,1854),(11639,'Je n\'aime pas cette figure','2022-03-04 21:21:41',592,1857),(11640,'J\'ai hâte d\'essayer ce trick !','2022-03-03 21:21:41',591,1851),(11641,'Merci des informations !','2022-03-02 21:21:41',594,1859),(11642,'Comment ne pas aimer ce trick ?','2022-03-01 21:21:41',594,1855),(11643,'Très beau skill !','2022-02-28 21:21:41',592,1851),(11644,'Merci des informations !','2022-02-27 21:21:41',590,1859),(11645,'Je laisse un avis très positif pour cette figure ! Au top','2022-02-26 21:21:41',593,1854),(11646,'Très beau skill !','2022-02-25 21:21:41',593,1856),(11647,'Comment ne pas aimer ce trick ?','2022-02-24 21:21:41',592,1857),(11648,'Je n\'aime pas cette figure','2022-02-23 21:21:41',593,1856),(11649,'Comment réaliser cette figure simplement ?','2022-02-22 21:21:41',594,1852),(11650,'J\'ai hâte d\'essayer ce trick !','2022-02-21 21:21:41',590,1857),(11651,'Quelle figure !','2022-02-20 21:21:41',594,1852),(11652,'Est il possible d\'apprendre cette figure en un an ?','2022-02-19 21:21:41',594,1860),(11653,'Je n\'aime pas cette figure','2022-02-18 21:21:41',594,1858),(11654,'Je suis surpris de la facilité ...','2022-02-17 21:21:41',593,1858),(11655,'J\'ai hâte d\'essayer ce trick !','2022-02-16 21:21:41',593,1855),(11656,'Merci des informations !','2022-02-15 21:21:41',594,1853),(11657,'Très beau skill !','2022-02-14 21:21:41',592,1857),(11658,'Je n\'aime pas cette figure','2022-02-13 21:21:41',591,1853),(11659,'Je trouve tout ce qu\'il me faut sur ce site web, merci !','2022-02-12 21:21:41',592,1854),(11660,'Comment réaliser cette figure simplement ?','2022-02-11 21:21:41',594,1855),(11661,'Je suis débutant, cette figure est parfaite !','2022-02-10 21:21:41',594,1852),(11662,'Est il possible d\'apprendre cette figure en un an ?','2022-02-09 21:21:41',592,1856),(11663,'Je suis très satisfait de ce site.','2022-02-08 21:21:41',592,1858),(11664,'Je trouve tout ce qu\'il me faut sur ce site web, merci !','2022-02-07 21:21:41',591,1860),(11665,'Je suis débutant, cette figure est parfaite !','2022-02-06 21:21:41',592,1854),(11666,'J\'ai hâte d\'essayer ce trick !','2022-02-05 21:21:41',592,1853),(11667,'Comment ne pas aimer ce trick ?','2022-02-04 21:21:41',592,1851),(11668,'Je trouve tout ce qu\'il me faut sur ce site web, merci !','2022-02-03 21:21:41',592,1856),(11669,'Je suis surpris de la facilité ...','2022-02-02 21:21:41',591,1851),(11670,'Je suis très satisfait de ce site.','2022-02-01 21:21:41',591,1855),(11671,'Comment ne pas aimer ce trick ?','2022-01-31 21:21:41',591,1857),(11672,'Est il possible d\'apprendre cette figure en un an ?','2022-01-30 21:21:41',593,1852),(11673,'Comment réaliser cette figure simplement ?','2022-01-29 21:21:41',593,1853),(11674,'Très beau saut !','2022-01-28 21:21:41',594,1852),(11675,'Je suis surpris de la facilité ...','2022-01-27 21:21:41',591,1855),(11676,'Est il possible d\'apprendre cette figure en un an ?','2022-01-26 21:21:41',591,1851),(11677,'Très beau skill !','2022-01-25 21:21:41',590,1853),(11678,'Je laisse un avis très positif pour cette figure ! Au top','2022-01-24 21:21:41',590,1857),(11679,'J\'ai hâte d\'essayer ce trick !','2022-01-23 21:21:41',591,1856),(11680,'Je laisse un avis très positif pour cette figure ! Au top','2022-01-22 21:21:41',592,1852),(11681,'Merci des informations !','2022-01-21 21:21:41',591,1858),(11682,'Quelle figure !','2022-01-20 21:21:41',592,1857),(11683,'J\'ai hâte d\'essayer ce trick !','2022-01-19 21:21:41',594,1856),(11684,'Merci des informations !','2022-01-18 21:21:41',592,1859),(11685,'J\'ai hâte d\'essayer ce trick !','2022-01-17 21:21:41',592,1854),(11686,'Je trouve tout ce qu\'il me faut sur ce site web, merci !','2022-01-16 21:21:41',593,1851),(11687,'Très beau saut !','2022-01-15 21:21:41',593,1853),(11688,'J\'ai hâte d\'essayer ce trick !','2022-01-14 21:21:41',592,1853),(11689,'Comment réaliser cette figure simplement ?','2022-01-13 21:21:41',590,1859),(11690,'Très beau skill !','2022-01-12 21:21:41',592,1859),(11691,'Est il possible d\'apprendre cette figure en un an ?','2022-01-11 21:21:41',592,1854),(11692,'Comment réaliser cette figure simplement ?','2022-01-10 21:21:41',592,1852),(11693,'Quelle figure !','2022-01-09 21:21:41',594,1852),(11694,'Je suis débutant, cette figure est parfaite !','2022-01-08 21:21:41',590,1857),(11695,'Très beau saut !','2022-01-07 21:21:41',592,1856),(11696,'Je laisse un avis très positif pour cette figure ! Au top','2022-01-06 21:21:41',593,1851),(11697,'Quelle figure !','2022-01-05 21:21:41',590,1852),(11698,'Très beau skill !','2022-01-04 21:21:41',590,1859),(11699,'Très beau skill !','2022-01-03 21:21:41',590,1853),(11700,'J\'ai hâte d\'essayer ce trick !','2022-01-02 21:21:41',591,1859),(11701,'Je trouve tout ce qu\'il me faut sur ce site web, merci !','2022-01-01 21:21:41',592,1859),(11702,'Je trouve tout ce qu\'il me faut sur ce site web, merci !','2021-12-31 21:21:41',590,1852),(11703,'Je n\'aime pas cette figure','2021-12-30 21:21:41',591,1858),(11704,'Merci des informations !','2021-12-29 21:21:41',593,1855),(11705,'Merci des informations !','2021-12-28 21:21:41',590,1855),(11706,'Quelle figure !','2021-12-27 21:21:41',590,1860),(11707,'J\'ai hâte d\'essayer ce trick !','2021-12-26 21:21:41',594,1858),(11708,'J\'ai hâte d\'essayer ce trick !','2021-12-25 21:21:41',590,1860),(11709,'Je n\'aime pas cette figure','2021-12-24 21:21:41',592,1853),(11710,'Je laisse un avis très positif pour cette figure ! Au top','2021-12-23 21:21:41',591,1854),(11711,'Je trouve tout ce qu\'il me faut sur ce site web, merci !','2021-12-22 21:21:41',590,1853),(11712,'Je trouve tout ce qu\'il me faut sur ce site web, merci !','2021-12-21 21:21:41',591,1857),(11713,'Comment réaliser cette figure simplement ?','2021-12-20 21:21:41',594,1854),(11714,'Je n\'aime pas cette figure','2021-12-19 21:21:41',592,1858),(11715,'Très beau skill !','2021-12-18 21:21:41',594,1852),(11716,'Je trouve tout ce qu\'il me faut sur ce site web, merci !','2021-12-17 21:21:41',591,1858),(11717,'Comment ne pas aimer ce trick ?','2021-12-16 21:21:41',594,1854),(11718,'Je laisse un avis très positif pour cette figure ! Au top','2021-12-15 21:21:41',594,1852),(11719,'Très beau saut !','2021-12-14 21:21:41',592,1852),(11720,'Très beau saut !','2021-12-13 21:21:41',591,1856),(11721,'Je laisse un avis très positif pour cette figure ! Au top','2021-12-12 21:21:41',592,1852),(11722,'Comment ne pas aimer ce trick ?','2021-12-11 21:21:41',594,1858),(11723,'Est il possible d\'apprendre cette figure en un an ?','2021-12-10 21:21:41',591,1854),(11724,'Je suis débutant, cette figure est parfaite !','2021-12-09 21:21:41',594,1853),(11725,'Quelle figure !','2021-12-08 21:21:41',594,1858),(11726,'Est il possible d\'apprendre cette figure en un an ?','2021-12-07 21:21:41',590,1859),(11727,'Est il possible d\'apprendre cette figure en un an ?','2021-12-06 21:21:41',590,1857),(11728,'Comment réaliser cette figure simplement ?','2021-12-05 21:21:41',592,1859),(11729,'Très beau skill !','2021-12-04 21:21:41',592,1857),(11730,'Je suis débutant, cette figure est parfaite !','2021-12-03 21:21:41',592,1851),(11731,'Très beau skill !','2021-12-02 21:21:41',592,1856),(11732,'Quelle figure !','2021-12-01 21:21:41',592,1854),(11733,'Je trouve tout ce qu\'il me faut sur ce site web, merci !','2021-11-30 21:21:41',594,1857),(11734,'Très beau saut !','2021-11-29 21:21:41',594,1856),(11735,'Je n\'aime pas cette figure','2021-11-28 21:21:41',590,1856),(11736,'Je suis débutant, cette figure est parfaite !','2021-11-27 21:21:41',594,1857),(11737,'Je suis surpris de la facilité ...','2021-11-26 21:21:41',592,1855),(11738,'Je n\'aime pas cette figure','2021-11-25 21:21:41',594,1860),(11739,'Comment réaliser cette figure simplement ?','2021-11-24 21:21:41',593,1854),(11740,'J\'ai hâte d\'essayer ce trick !','2021-11-23 21:21:41',594,1851),(11741,'Très beau skill !','2021-11-22 21:21:41',590,1854),(11742,'Je laisse un avis très positif pour cette figure ! Au top','2021-11-21 21:21:41',591,1852),(11743,'Je suis débutant, cette figure est parfaite !','2021-11-20 21:21:41',594,1854),(11744,'Est il possible d\'apprendre cette figure en un an ?','2021-11-19 21:21:41',593,1852),(11745,'Je laisse un avis très positif pour cette figure ! Au top','2021-11-18 21:21:41',593,1858),(11746,'Je suis surpris de la facilité ...','2021-11-17 21:21:41',591,1852),(11747,'Je suis débutant, cette figure est parfaite !','2021-11-16 21:21:41',594,1858),(11748,'Très beau skill !','2021-11-15 21:21:41',593,1858),(11749,'Je suis surpris de la facilité ...','2021-11-14 21:21:41',590,1853),(11750,'Très beau skill !','2021-11-13 21:21:41',590,1851),(11751,'Merci des informations !','2021-11-12 21:21:41',592,1860),(11752,'J\'ai hâte d\'essayer ce trick !','2021-11-11 21:21:41',590,1853),(11753,'Je suis très satisfait de ce site.','2021-11-10 21:21:41',592,1856),(11754,'Très beau saut !','2021-11-09 21:21:41',593,1856),(11755,'Comment réaliser cette figure simplement ?','2021-11-08 21:21:41',594,1859),(11756,'Je laisse un avis très positif pour cette figure ! Au top','2021-11-07 21:21:41',594,1859),(11757,'Je laisse un avis très positif pour cette figure ! Au top','2021-11-06 21:21:41',592,1857),(11758,'Je suis très satisfait de ce site.','2021-11-05 21:21:41',593,1853),(11759,'Très beau skill !','2021-11-04 21:21:41',590,1853),(11760,'Je suis débutant, cette figure est parfaite !','2021-11-03 21:21:41',594,1854),(11761,'Merci des informations !','2021-11-02 21:21:41',592,1859),(11762,'Quelle figure !','2021-11-01 21:21:41',591,1858),(11763,'Comment réaliser cette figure simplement ?','2021-10-31 21:21:41',591,1853),(11764,'Comment réaliser cette figure simplement ?','2021-10-30 21:21:41',594,1851),(11765,'Je n\'aime pas cette figure','2021-10-29 21:21:41',593,1857),(11766,'Je n\'aime pas cette figure','2021-10-28 21:21:41',593,1852),(11767,'Je suis débutant, cette figure est parfaite !','2021-10-27 21:21:41',594,1855),(11768,'Merci des informations !','2021-10-26 21:21:41',593,1854),(11769,'Je trouve tout ce qu\'il me faut sur ce site web, merci !','2021-10-25 21:21:41',592,1859);
/*!40000 ALTER TABLE `message` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(180) COLLATE utf8mb4_unicode_ci NOT NULL,
  `roles` json NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_valid` tinyint(1) NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `avatar` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `token_reset` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_8D93D649F85E0677` (`username`),
  UNIQUE KEY `UNIQ_8D93D649E7927C74` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=595 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user`
--

LOCK TABLES `user` WRITE;
/*!40000 ALTER TABLE `user` DISABLE KEYS */;
INSERT INTO `user` VALUES (590,'Esaou','[]','$2y$13$MB9sd9xDdb4VwM11OSec7OQ.zKnfKUfm892pnT3Xc.c/OfK5eCtHe','eric.test@test.com',1,'1ecaaef3-983c-6a04-8209-bd191cf8a62b',NULL,NULL,'2022-03-23 21:21:38',NULL),(591,'Melissa38','[]','$2y$13$8Su1LrXa.IHCYeE1zlHDU.0.bTddVtL0VT7VhFsMej8Cd.R9aYm7.','melissa.test@test.com',1,'1ecaaef3-9fc8-630e-bcc4-bd191cf8a62b',NULL,'default.png','2022-03-23 21:21:38',NULL),(592,'JakeHenderson','[]','$2y$13$SPU1ZZgUR5qGLQkquRIkEOhaB8NiB3Wrg/3tpSE12IayLtHvCTdQq','jake.henderson@test.com',1,'1ecaaef3-a73a-618c-8f5f-bd191cf8a62b',NULL,NULL,'2022-03-23 21:21:39',NULL),(593,'Jacques07','[]','$2y$13$V9tjHpWkl3eZEEujMTfpWexCRKIuVh8HfHg5Ytm7XQHi2j6iHWGey','jacques07@test.com',0,'1ecaaef3-af33-6a64-b95d-bd191cf8a62b','1ecaaef3-af33-68fc-b4d5-bd191cf8a62b',NULL,'2022-03-23 21:21:40',NULL),(594,'HelloWorld','[]','$2y$13$FidBi9PZeHjFviToaS/bsuXBIngzWzEXE99YsUs9gMgaMC.6IQMwq','hello.world@test.com',0,'1ecaaef3-b63d-66e8-b460-bd191cf8a62b','1ecaaef3-b63d-65e4-9bfc-bd191cf8a62b',NULL,'2022-03-23 21:21:41',NULL);
/*!40000 ALTER TABLE `user` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `video`
--

DROP TABLE IF EXISTS `video`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `video` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `url` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `figure_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_7CC7DA2C5C011B5` (`figure_id`),
  CONSTRAINT `FK_7CC7DA2C5C011B5` FOREIGN KEY (`figure_id`) REFERENCES `figure` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2148 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `video`
--

LOCK TABLES `video` WRITE;
/*!40000 ALTER TABLE `video` DISABLE KEYS */;
INSERT INTO `video` VALUES (2128,'<iframe width=\"560\" height=\"315\" src=\"https://www.youtube.com/embed/P-HnC7Ej9mw\" title=\"YouTube video player\" frameborder=\"0\" allow=\"accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture\" allowfullscreen></iframe>',1851),(2129,'<iframe width=\"560\" height=\"315\" src=\"https://www.youtube.com/embed/ohPRqA3Rstk\" title=\"YouTube video player\" frameborder=\"0\" allow=\"accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture\" allowfullscreen></iframe>',1852),(2130,'<iframe width=\"560\" height=\"315\" src=\"https://www.youtube.com/embed/JCjmmlvVnc8\" title=\"YouTube video player\" frameborder=\"0\" allow=\"accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture\" allowfullscreen></iframe>',1853),(2131,'<iframe width=\"560\" height=\"315\" src=\"https://www.youtube.com/embed/OsbpD8BN10k\" title=\"YouTube video player\" frameborder=\"0\" allow=\"accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture\" allowfullscreen></iframe>',1854),(2132,'<iframe width=\"560\" height=\"315\" src=\"https://www.youtube.com/embed/G-kZiEfdiVY\" title=\"YouTube video player\" frameborder=\"0\" allow=\"accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture\" allowfullscreen></iframe>',1855),(2133,'<iframe width=\"560\" height=\"315\" src=\"https://www.youtube.com/embed/4I9eMYbrY2Y\" title=\"YouTube video player\" frameborder=\"0\" allow=\"accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture\" allowfullscreen></iframe>',1856),(2134,'<iframe width=\"560\" height=\"315\" src=\"https://www.youtube.com/embed/w9KuTkeNQfY\" title=\"YouTube video player\" frameborder=\"0\" allow=\"accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture\" allowfullscreen></iframe>',1857),(2135,'<iframe width=\"560\" height=\"315\" src=\"https://www.youtube.com/embed/QMrelVooJR4\" title=\"YouTube video player\" frameborder=\"0\" allow=\"accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture\" allowfullscreen></iframe>',1858),(2136,'<iframe width=\"560\" height=\"315\" src=\"https://www.youtube.com/embed/Ey5elKTrUCk\" title=\"YouTube video player\" frameborder=\"0\" allow=\"accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture\" allowfullscreen></iframe>',1859),(2137,'<iframe width=\"560\" height=\"315\" src=\"https://www.youtube.com/embed/OlkBw78JIM4\" title=\"YouTube video player\" frameborder=\"0\" allow=\"accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture\" allowfullscreen></iframe>',1860),(2138,'<iframe width=\"560\" height=\"315\" src=\"https://www.youtube.com/embed/1TJ08caetkw\" title=\"YouTube video player\" frameborder=\"0\" allow=\"accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture\" allowfullscreen></iframe>',1851),(2139,'<iframe width=\"560\" height=\"315\" src=\"https://www.youtube.com/embed/lZCCY_lECDw\" title=\"YouTube video player\" frameborder=\"0\" allow=\"accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture\" allowfullscreen></iframe>',1856),(2140,'<iframe width=\"560\" height=\"315\" src=\"https://www.youtube.com/embed/V9xuy-rVj9w\" title=\"YouTube video player\" frameborder=\"0\" allow=\"accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture\" allowfullscreen></iframe>',1853),(2141,'<iframe width=\"560\" height=\"315\" src=\"https://www.youtube.com/embed/wfKClEdfcNk\" title=\"YouTube video player\" frameborder=\"0\" allow=\"accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture\" allowfullscreen></iframe>',1856),(2142,'<iframe width=\"560\" height=\"315\" src=\"https://www.youtube.com/embed/aGPiQ47ahsE\" title=\"YouTube video player\" frameborder=\"0\" allow=\"accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture\" allowfullscreen></iframe>',1854),(2143,'<iframe width=\"560\" height=\"315\" src=\"https://www.youtube.com/embed/YzeqgAeOr9o\" title=\"YouTube video player\" frameborder=\"0\" allow=\"accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture\" allowfullscreen></iframe>',1851),(2144,'<iframe width=\"560\" height=\"315\" src=\"https://www.youtube.com/embed/sAopZDE8Dvc\" title=\"YouTube video player\" frameborder=\"0\" allow=\"accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture\" allowfullscreen></iframe>',1851),(2145,'<iframe width=\"560\" height=\"315\" src=\"https://www.youtube.com/embed/NkQmIwOduCw\" title=\"YouTube video player\" frameborder=\"0\" allow=\"accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture\" allowfullscreen></iframe>',1853),(2146,'<iframe width=\"560\" height=\"315\" src=\"https://www.youtube.com/embed/aGPiQ47ahsE\" title=\"YouTube video player\" frameborder=\"0\" allow=\"accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture\" allowfullscreen></iframe>',1852),(2147,'<iframe width=\"560\" height=\"315\" src=\"https://www.youtube.com/embed/aGPiQ47ahsE\" title=\"YouTube video player\" frameborder=\"0\" allow=\"accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture\" allowfullscreen></iframe>',1854);
/*!40000 ALTER TABLE `video` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2022-03-23 22:22:02
