-- MySQL dump 10.13  Distrib 8.0.25, for Linux (x86_64)
--
-- Host: localhost    Database: camagru
-- ------------------------------------------------------
-- Server version	8.0.25-0ubuntu0.20.04.1

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
-- Table structure for table `comment_reacts`
--

DROP TABLE IF EXISTS `comment_reacts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `comment_reacts` (
  `id` int NOT NULL AUTO_INCREMENT,
  `comment` int NOT NULL,
  `user` int NOT NULL,
  `type` tinyint NOT NULL,
  `status` tinyint NOT NULL DEFAULT '0',
  `created_at` timestamp NOT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fkIdx_64` (`comment`),
  KEY `fkIdx_67` (`user`),
  CONSTRAINT `FK_63` FOREIGN KEY (`comment`) REFERENCES `comments` (`id`),
  CONSTRAINT `FK_66` FOREIGN KEY (`user`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `comment_reacts`
--

LOCK TABLES `comment_reacts` WRITE;
/*!40000 ALTER TABLE `comment_reacts` DISABLE KEYS */;
/*!40000 ALTER TABLE `comment_reacts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `comments`
--

DROP TABLE IF EXISTS `comments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `comments` (
  `id` int NOT NULL AUTO_INCREMENT,
  `post` int NOT NULL,
  `user` int NOT NULL,
  `content` text NOT NULL,
  `status` tinyint NOT NULL DEFAULT '0',
  `created_at` timestamp NOT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fkIdx_51` (`post`),
  KEY `fkIdx_54` (`user`),
  CONSTRAINT `FK_50` FOREIGN KEY (`post`) REFERENCES `posts` (`id`),
  CONSTRAINT `FK_53` FOREIGN KEY (`user`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `comments`
--

LOCK TABLES `comments` WRITE;
/*!40000 ALTER TABLE `comments` DISABLE KEYS */;
INSERT INTO `comments` VALUES (1,1,1,'s',0,'2021-05-18 17:18:36',NULL),(2,1,1,'dance monkey',0,'2021-05-18 17:19:25',NULL),(3,1,1,'dance monkey',0,'2021-05-18 17:20:16',NULL),(4,1,1,'dance monkey',0,'2021-05-18 17:20:17',NULL),(5,1,1,'',0,'2021-05-18 18:04:53',NULL),(6,1,1,'test',0,'2021-05-18 18:22:49',NULL),(7,1,1,'this is a comment',0,'2021-05-18 18:50:42',NULL),(8,1,1,'yarbi ikhdm',0,'2021-05-18 18:55:42',NULL),(9,1,1,'test',0,'2021-05-18 19:03:36',NULL),(10,1,1,'.',0,'2021-05-18 20:34:28',NULL),(11,1,1,'.',0,'2021-05-18 20:35:17',NULL),(12,1,1,'.',0,'2021-05-18 20:36:33',NULL),(13,1,1,'.',0,'2021-05-18 20:36:56',NULL),(14,1,1,'.',0,'2021-05-18 20:37:49',NULL),(15,1,1,'.',0,'2021-05-18 20:43:23',NULL),(16,1,1,'.',0,'2021-05-18 20:55:26',NULL),(17,1,1,'.',0,'2021-05-18 21:23:08',NULL),(18,1,1,'up',0,'2021-05-18 21:24:12',NULL),(19,1,1,'.',0,'2021-05-18 21:25:42',NULL),(20,1,1,'.',0,'2021-05-18 21:27:06',NULL),(21,1,1,'..',0,'2021-05-18 21:47:54',NULL),(22,1,1,'.',0,'2021-05-18 21:48:39',NULL),(23,2,1,'fuck you',0,'2021-05-31 17:35:48',NULL),(24,2,1,'&#60;h1&#62;test&#60;/h1&#62;',0,'2021-05-31 18:05:48',NULL),(25,2,1,'.',0,'2021-05-31 18:06:41',NULL),(26,2,1,'&#60;h1&#62;test&#60;/h1&#62;',0,'2021-06-01 22:28:35',NULL),(27,1,1,'test',0,'2021-06-26 00:28:45',NULL);
/*!40000 ALTER TABLE `comments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `contact_us`
--

DROP TABLE IF EXISTS `contact_us`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `contact_us` (
  `id` int NOT NULL AUTO_INCREMENT,
  `logged` tinyint NOT NULL,
  `user` int DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `content` tinytext NOT NULL,
  `ParentId` int DEFAULT NULL,
  `status` tinyint NOT NULL,
  `created_at` timestamp NOT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fkIdx_78` (`user`),
  KEY `fkIdx_84` (`ParentId`),
  CONSTRAINT `FK_77` FOREIGN KEY (`user`) REFERENCES `users` (`id`),
  CONSTRAINT `FK_83` FOREIGN KEY (`ParentId`) REFERENCES `contact_us` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `contact_us`
--

LOCK TABLES `contact_us` WRITE;
/*!40000 ALTER TABLE `contact_us` DISABLE KEYS */;
INSERT INTO `contact_us` VALUES (1,1,1,NULL,'we are testing','Sheeeeeesh',NULL,1,'2021-05-29 01:16:25',NULL),(2,0,NULL,'','we are still testing','I guess this one will create a bug where no information of the sender will be saved',NULL,1,'2021-05-29 01:20:15',NULL),(3,0,NULL,'nowweare@talk.ing','we are still testing','I guess this one will create a bug where no information of the sender will be saved',NULL,1,'2021-05-29 01:22:56',NULL),(4,0,NULL,'agoumihunter@gmail.com','the site is brokem','the site did broke when you first wrote it cause you never write something that actually works sadly',NULL,1,'2021-05-29 01:28:46',NULL),(5,0,NULL,'lovtech99@gmail.com','sfs','haha haha haha',NULL,1,'2021-05-31 17:38:01',NULL),(6,0,NULL,'agoumihunter@gmail.com','visiting every web page and testing it','ok is this form still working with all the modes we did',NULL,1,'2021-06-26 16:20:05',NULL);
/*!40000 ALTER TABLE `contact_us` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `languages`
--

DROP TABLE IF EXISTS `languages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `languages` (
  `id` int NOT NULL AUTO_INCREMENT,
  `language` varchar(45) NOT NULL,
  `created_at` timestamp NOT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `languages`
--

LOCK TABLES `languages` WRITE;
/*!40000 ALTER TABLE `languages` DISABLE KEYS */;
INSERT INTO `languages` VALUES (1,'en','2021-05-31 10:06:45',NULL),(2,'fr','2021-05-31 10:07:14',NULL),(3,'ar','2021-05-31 11:59:40',NULL);
/*!40000 ALTER TABLE `languages` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `likes`
--

DROP TABLE IF EXISTS `likes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `likes` (
  `id` int NOT NULL AUTO_INCREMENT,
  `post` int NOT NULL,
  `user` int NOT NULL,
  `type` tinyint NOT NULL DEFAULT '0',
  `status` tinyint NOT NULL DEFAULT '0',
  `created_at` timestamp NOT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fkIdx_38` (`post`),
  KEY `fkIdx_41` (`user`),
  CONSTRAINT `FK_37` FOREIGN KEY (`post`) REFERENCES `posts` (`id`) ON DELETE CASCADE,
  CONSTRAINT `FK_40` FOREIGN KEY (`user`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `likes`
--

LOCK TABLES `likes` WRITE;
/*!40000 ALTER TABLE `likes` DISABLE KEYS */;
INSERT INTO `likes` VALUES (12,5,1,0,1,'2021-05-12 17:17:41','2021-05-12 18:04:08'),(13,5,3,0,0,'2021-05-12 17:21:05',NULL),(14,1,1,2,1,'2021-05-17 23:04:31','2021-06-01 16:59:56'),(15,2,1,0,0,'2021-05-29 18:39:23','2021-05-31 17:36:04');
/*!40000 ALTER TABLE `likes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `migrations` (
  `id` int NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (7,'mg0001_creating_user_table.php','2021-04-20 11:31:57'),(8,'mg0002_creating_posts_table.php','2021-04-20 11:31:57'),(10,'mg0003_creating_password_restore_table.php','2021-04-28 13:28:08'),(11,'mg0004_creating_likes_table.php','2021-05-03 11:44:19'),(14,'mg0005_creating_comments_table.php','2021-05-18 15:20:43'),(15,'mg0006_creating_comment_reacts_table.php','2021-05-18 15:20:43'),(17,'mg0007_creating_contact_us_table.php','2021-05-29 00:16:17'),(20,'mg0008_create_languages_table.php','2021-05-31 10:05:47'),(21,'mg0009_create_preferences_table.php','2021-05-31 10:05:47'),(23,'mg0010_create_roles_table.php','2021-07-03 23:27:26');
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `password_reset`
--

DROP TABLE IF EXISTS `password_reset`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `password_reset` (
  `id` int NOT NULL AUTO_INCREMENT,
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `used` tinyint NOT NULL DEFAULT '0',
  `created_at` timestamp NOT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `password_reset`
--

LOCK TABLES `password_reset` WRITE;
/*!40000 ALTER TABLE `password_reset` DISABLE KEYS */;
INSERT INTO `password_reset` VALUES (18,'agoumihunter@gmail.com','31a146b3d11ce5a7aafee2eb9f6a4f7d7c8b',0,'2021-06-26 19:12:57','2021-07-04 00:05:05'),(19,'lovtech99@gmail.com','72a1d606e4f07d18f422c6cccb10141665fc',1,'2021-07-04 00:16:58','2021-07-04 00:17:07');
/*!40000 ALTER TABLE `password_reset` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `posts`
--

DROP TABLE IF EXISTS `posts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `posts` (
  `id` int NOT NULL AUTO_INCREMENT,
  `title` varchar(255) DEFAULT NULL,
  `comment` text,
  `picture` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `status` tinyint NOT NULL DEFAULT '0',
  `author` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_row_unique` (`slug`),
  KEY `fkIdx_23` (`author`),
  CONSTRAINT `FK_22` FOREIGN KEY (`author`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `posts`
--

LOCK TABLES `posts` WRITE;
/*!40000 ALTER TABLE `posts` DISABLE KEYS */;
INSERT INTO `posts` VALUES (1,'my post','this picture is taken during a beatiful day','image_60a03e414800d.jpeg','my-post','2021-05-02 21:06:55',NULL,0,1),(2,'cute post','a day in front of the computer #IT #keyboard #work','image_60a03bc6c5d9b.jpeg','cute-post','2021-05-11 13:46:58',NULL,0,1),(4,'cute screen','#IT #screen #setup','image_607966cd22a3b.jpeg','cute-screen','2021-05-11 13:57:21',NULL,0,2),(5,'test','a test post','image_609bf22b2fc7a.jpeg','test-609bf23e0114c','2021-05-12 15:20:30',NULL,0,1),(6,'black screen sadly','beautiful dick in the #beach','image_60b511e049ea4.jpeg','black-screen-sadly-60b512191ad97','2021-05-31 17:43:05',NULL,0,1);
/*!40000 ALTER TABLE `posts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `preferences`
--

DROP TABLE IF EXISTS `preferences`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `preferences` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user` int NOT NULL,
  `language` int NOT NULL,
  `created_at` timestamp NOT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fkIdx_102` (`language`),
  KEY `fkIdx_93` (`user`),
  CONSTRAINT `FK_101` FOREIGN KEY (`language`) REFERENCES `languages` (`id`),
  CONSTRAINT `FK_92` FOREIGN KEY (`user`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `preferences`
--

LOCK TABLES `preferences` WRITE;
/*!40000 ALTER TABLE `preferences` DISABLE KEYS */;
INSERT INTO `preferences` VALUES (2,1,1,'2021-06-26 20:49:29','2021-06-28 18:01:54');
/*!40000 ALTER TABLE `preferences` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `roles`
--

DROP TABLE IF EXISTS `roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `roles` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `user` int NOT NULL,
  `super_admin` tinyint NOT NULL DEFAULT '0',
  `users` tinyint NOT NULL DEFAULT '0',
  `posts` tinyint NOT NULL DEFAULT '0',
  `comments` tinyint NOT NULL DEFAULT '0',
  `likes` tinyint NOT NULL DEFAULT '0',
  `promote` tinyint NOT NULL DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL,
  `created_by` int NOT NULL,
  `updated_by` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fkIdx_115` (`user`),
  KEY `fkIdx_125` (`updated_by`),
  KEY `fkIdx_135` (`updated_by`),
  CONSTRAINT `FK_114` FOREIGN KEY (`user`) REFERENCES `users` (`id`),
  CONSTRAINT `FK_124` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`),
  CONSTRAINT `FK_134` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `roles`
--

LOCK TABLES `roles` WRITE;
/*!40000 ALTER TABLE `roles` DISABLE KEYS */;
INSERT INTO `roles` VALUES (1,1,1,1,1,1,1,1,'2021-07-03 23:27:26',NULL,1,1);
/*!40000 ALTER TABLE `roles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `email` varchar(255) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `username` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `status` tinyint NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'agoumihunter@gmail.com','Mohamed Agoumi','FtMerio','$2y$10$sfTgqvy3MMlFTYTMFQnLeuVN4wM8ajZn3be2j97HdzgKYpEeyV4Ee',0,'2021-04-20 11:32:00','2021-07-04 00:12:44'),(2,'lovtech99@gmail.com','Andrew Kramer','xyfarudyf','$2y$10$R7ifZuktQw9iohlcnBu3DeK1JiHAWSQcIAKnuuzA7SDfoiVlTRKdi',0,'2021-05-03 12:12:39',NULL),(3,'mrx.ga10@hotmail.com','dracula vamp','vampSpeed','$2y$10$gy7oBe13rT4Dk1Y2iLvCLOvN94KrvU7zO0Fw5DnCndqByD7my9GRC',0,'2021-05-12 17:20:47',NULL),(4,'magoumi@student.1337.ma','Phillip Contreras','kitiluwe','$2y$10$lyuL0TkgeJMxoTWdU7QwceV0GL6mmaWD41okHr7eyz.CsRiaXVhs.',0,'2021-05-29 20:13:27',NULL),(5,'pipizasi@mailinator.com','Jack Higgins','wotal','$2y$10$tWITC8kd/q6A6CD6WGGKneeBr6sqbpmGVrsA6LFKzDLRG4kezv8Am',0,'2021-05-29 20:14:26',NULL),(6,'xesupy@mailinator.com','Shannon Bernard','zofuvure','$2y$10$mHTq4JkMoyxjPyDAOB9jX.M47L71bavj6jW9ibVTXuHRCpFnrhbq6',0,'2021-05-31 01:06:57',NULL),(7,'lyqo@mailinator.com','Hyatt Sutton','mocoky','$2y$10$M66AtK61dwf.mxniG4VCbu2XHmn9KSlgHkp/WI1EzOu6e3H0ziYPW',0,'2021-05-31 17:34:12',NULL),(8,'magoumi@gmail.com','anas agoumi','deamon_slayer69','$2y$10$U5hRyf9gg57511RSBxfQWO8x/TyEHBQyJzAXuYhd5zospgmJB1a/m',0,'2021-06-26 18:56:23',NULL);
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

-- Dump completed on 2021-07-04  0:46:08
