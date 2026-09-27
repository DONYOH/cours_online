-- EduSphere LMS — base complète avec données de démonstration
-- Compatible MySQL 8+ / MariaDB 10.6+ · utf8mb4
-- Comptes (mot de passe : password) : admin@edusphere.test, prof@edusphere.test, prof2@edusphere.test, etudiant@edusphere.test
-- Import : mysql -u root -p edusphere < edusphere_demo.sql  (ou phpMyAdmin > Importer)

-- MariaDB dump 10.19  Distrib 10.11.14-MariaDB, for debian-linux-gnu (x86_64)
--
-- Host: localhost    Database: edusphere
-- ------------------------------------------------------
-- Server version	10.11.14-MariaDB-0ubuntu0.24.04.1

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `activities`
--

DROP TABLE IF EXISTS `activities`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `activities` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL,
  `course_id` bigint(20) unsigned DEFAULT NULL,
  `type` varchar(40) NOT NULL,
  `subject_type` varchar(255) DEFAULT NULL,
  `subject_id` bigint(20) unsigned DEFAULT NULL,
  `meta` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`meta`)),
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `activities_user_id_foreign` (`user_id`),
  KEY `activities_course_id_foreign` (`course_id`),
  KEY `activities_subject_type_subject_id_index` (`subject_type`,`subject_id`),
  KEY `activities_type_index` (`type`),
  KEY `activities_created_at_index` (`created_at`),
  CONSTRAINT `activities_course_id_foreign` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE,
  CONSTRAINT `activities_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=579 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `activities`
--

/*!40000 ALTER TABLE `activities` DISABLE KEYS */;
INSERT INTO `activities` VALUES
(1,15,1,'lesson_completed',NULL,NULL,NULL,'2026-09-04 15:57:00'),
(2,15,1,'lesson_viewed',NULL,NULL,NULL,'2026-09-04 15:09:00'),
(3,15,1,'lesson_viewed',NULL,NULL,NULL,'2026-09-04 15:44:00'),
(4,15,1,'lesson_viewed',NULL,NULL,NULL,'2026-09-04 14:40:00'),
(5,15,1,'lesson_completed',NULL,NULL,NULL,'2026-09-07 10:56:00'),
(6,15,1,'lesson_viewed',NULL,NULL,NULL,'2026-09-07 10:33:00'),
(7,15,1,'lesson_viewed',NULL,NULL,NULL,'2026-09-07 10:29:00'),
(8,15,1,'lesson_viewed',NULL,NULL,NULL,'2026-09-07 09:40:00'),
(9,15,1,'lesson_completed',NULL,NULL,NULL,'2026-09-10 07:50:00'),
(10,15,1,'lesson_viewed',NULL,NULL,NULL,'2026-09-10 07:12:00'),
(11,15,1,'lesson_viewed',NULL,NULL,NULL,'2026-09-10 06:20:00'),
(12,15,1,'quiz_completed',NULL,NULL,NULL,'2026-09-12 16:25:00'),
(13,15,1,'lesson_viewed',NULL,NULL,NULL,'2026-09-12 15:51:00'),
(14,15,1,'lesson_viewed',NULL,NULL,NULL,'2026-09-12 15:45:00'),
(15,15,1,'lesson_completed',NULL,NULL,NULL,'2026-09-15 19:08:00'),
(16,15,1,'lesson_viewed',NULL,NULL,NULL,'2026-09-15 17:56:00'),
(17,15,1,'lesson_viewed',NULL,NULL,NULL,'2026-09-15 18:40:00'),
(18,15,1,'lesson_completed',NULL,NULL,NULL,'2026-09-18 10:19:00'),
(19,15,1,'lesson_viewed',NULL,NULL,NULL,'2026-09-18 09:38:00'),
(20,15,1,'quiz_completed',NULL,NULL,NULL,'2026-09-20 07:58:00'),
(21,15,1,'lesson_viewed',NULL,NULL,NULL,'2026-09-20 07:44:00'),
(22,15,1,'lesson_viewed',NULL,NULL,NULL,'2026-09-20 07:45:00'),
(23,15,1,'assignment_completed',NULL,NULL,NULL,'2026-09-23 08:02:00'),
(24,15,1,'lesson_viewed',NULL,NULL,NULL,'2026-09-23 07:06:00'),
(25,15,1,'lesson_viewed',NULL,NULL,NULL,'2026-09-23 07:55:00'),
(26,15,1,'lesson_completed',NULL,NULL,NULL,'2026-09-26 19:20:00'),
(27,15,1,'lesson_viewed',NULL,NULL,NULL,'2026-09-26 18:48:00'),
(28,15,1,'lesson_viewed',NULL,NULL,NULL,'2026-09-26 18:06:00'),
(29,26,1,'lesson_completed',NULL,NULL,NULL,'2026-09-02 12:59:00'),
(30,26,1,'lesson_viewed',NULL,NULL,NULL,'2026-09-02 11:56:00'),
(31,26,1,'lesson_viewed',NULL,NULL,NULL,'2026-09-02 12:31:00'),
(32,26,1,'lesson_viewed',NULL,NULL,NULL,'2026-09-02 12:43:00'),
(33,26,1,'lesson_completed',NULL,NULL,NULL,'2026-09-07 07:13:00'),
(34,26,1,'lesson_viewed',NULL,NULL,NULL,'2026-09-07 07:03:00'),
(35,26,1,'lesson_viewed',NULL,NULL,NULL,'2026-09-07 06:26:00'),
(36,26,1,'lesson_completed',NULL,NULL,NULL,'2026-09-13 06:49:00'),
(37,26,1,'lesson_viewed',NULL,NULL,NULL,'2026-09-13 06:32:00'),
(38,26,1,'lesson_viewed',NULL,NULL,NULL,'2026-09-13 05:27:00'),
(39,26,1,'quiz_completed',NULL,NULL,NULL,'2026-09-18 19:55:00'),
(40,26,1,'lesson_viewed',NULL,NULL,NULL,'2026-09-18 18:28:00'),
(41,26,1,'lesson_viewed',NULL,NULL,NULL,'2026-09-18 18:37:00'),
(42,26,1,'lesson_completed',NULL,NULL,NULL,'2026-09-24 18:57:00'),
(43,26,1,'lesson_viewed',NULL,NULL,NULL,'2026-09-24 18:31:00'),
(44,11,1,'lesson_completed',NULL,NULL,NULL,'2026-09-08 08:30:00'),
(45,11,1,'lesson_viewed',NULL,NULL,NULL,'2026-09-08 07:45:00'),
(46,11,1,'lesson_viewed',NULL,NULL,NULL,'2026-09-08 07:24:00'),
(47,11,1,'lesson_completed',NULL,NULL,NULL,'2026-09-12 08:12:00'),
(48,11,1,'lesson_viewed',NULL,NULL,NULL,'2026-09-12 07:37:00'),
(49,11,1,'lesson_viewed',NULL,NULL,NULL,'2026-09-12 07:41:00'),
(50,11,1,'lesson_completed',NULL,NULL,NULL,'2026-09-15 19:28:00'),
(51,11,1,'lesson_viewed',NULL,NULL,NULL,'2026-09-15 19:04:00'),
(52,11,1,'lesson_viewed',NULL,NULL,NULL,'2026-09-15 19:20:00'),
(53,11,1,'quiz_completed',NULL,NULL,NULL,'2026-09-19 18:38:00'),
(54,11,1,'lesson_viewed',NULL,NULL,NULL,'2026-09-19 17:14:00'),
(55,11,1,'lesson_viewed',NULL,NULL,NULL,'2026-09-19 17:13:00'),
(56,11,1,'lesson_completed',NULL,NULL,NULL,'2026-09-22 15:01:00'),
(57,11,1,'lesson_viewed',NULL,NULL,NULL,'2026-09-22 13:50:00'),
(58,11,1,'lesson_completed',NULL,NULL,NULL,'2026-09-26 18:35:00'),
(59,11,1,'lesson_viewed',NULL,NULL,NULL,'2026-09-26 17:12:00'),
(60,19,1,'lesson_completed',NULL,NULL,NULL,'2026-09-06 18:58:00'),
(61,19,1,'lesson_viewed',NULL,NULL,NULL,'2026-09-06 18:51:00'),
(62,19,1,'lesson_viewed',NULL,NULL,NULL,'2026-09-06 17:42:00'),
(63,19,1,'lesson_completed',NULL,NULL,NULL,'2026-09-10 10:47:00'),
(64,19,1,'lesson_viewed',NULL,NULL,NULL,'2026-09-10 09:57:00'),
(65,19,1,'lesson_viewed',NULL,NULL,NULL,'2026-09-10 10:41:00'),
(66,19,1,'lesson_completed',NULL,NULL,NULL,'2026-09-14 13:00:00'),
(67,19,1,'lesson_viewed',NULL,NULL,NULL,'2026-09-14 12:52:00'),
(68,19,1,'lesson_viewed',NULL,NULL,NULL,'2026-09-14 12:00:00'),
(69,19,1,'quiz_completed',NULL,NULL,NULL,'2026-09-18 14:44:00'),
(70,19,1,'lesson_viewed',NULL,NULL,NULL,'2026-09-18 14:09:00'),
(71,19,1,'lesson_completed',NULL,NULL,NULL,'2026-09-22 15:30:00'),
(72,19,1,'lesson_viewed',NULL,NULL,NULL,'2026-09-22 14:58:00'),
(73,19,1,'lesson_viewed',NULL,NULL,NULL,'2026-09-22 14:01:00'),
(74,19,1,'lesson_viewed',NULL,NULL,NULL,'2026-09-22 15:12:00'),
(75,19,1,'lesson_completed',NULL,NULL,NULL,'2026-09-26 12:26:00'),
(76,19,1,'lesson_viewed',NULL,NULL,NULL,'2026-09-26 11:27:00'),
(77,19,1,'lesson_viewed',NULL,NULL,NULL,'2026-09-26 11:47:00'),
(78,17,1,'lesson_completed',NULL,NULL,NULL,'2026-09-07 14:19:00'),
(79,17,1,'lesson_viewed',NULL,NULL,NULL,'2026-09-07 12:53:00'),
(80,17,1,'lesson_viewed',NULL,NULL,NULL,'2026-09-07 13:05:00'),
(81,17,1,'lesson_completed',NULL,NULL,NULL,'2026-09-09 12:07:00'),
(82,17,1,'lesson_viewed',NULL,NULL,NULL,'2026-09-09 12:02:00'),
(83,17,1,'lesson_viewed',NULL,NULL,NULL,'2026-09-09 10:48:00'),
(84,17,1,'lesson_viewed',NULL,NULL,NULL,'2026-09-09 10:57:00'),
(85,17,1,'lesson_completed',NULL,NULL,NULL,'2026-09-12 08:00:00'),
(86,17,1,'lesson_viewed',NULL,NULL,NULL,'2026-09-12 06:48:00'),
(87,17,1,'lesson_viewed',NULL,NULL,NULL,'2026-09-12 07:08:00'),
(88,17,1,'quiz_completed',NULL,NULL,NULL,'2026-09-14 09:59:00'),
(89,17,1,'lesson_viewed',NULL,NULL,NULL,'2026-09-14 08:58:00'),
(90,17,1,'lesson_viewed',NULL,NULL,NULL,'2026-09-14 09:01:00'),
(91,17,1,'lesson_completed',NULL,NULL,NULL,'2026-09-17 18:12:00'),
(92,17,1,'lesson_viewed',NULL,NULL,NULL,'2026-09-17 17:25:00'),
(93,17,1,'lesson_viewed',NULL,NULL,NULL,'2026-09-17 17:12:00'),
(94,17,1,'lesson_viewed',NULL,NULL,NULL,'2026-09-17 17:50:00'),
(95,17,1,'lesson_completed',NULL,NULL,NULL,'2026-09-19 14:45:00'),
(96,17,1,'lesson_viewed',NULL,NULL,NULL,'2026-09-19 14:26:00'),
(97,17,1,'lesson_viewed',NULL,NULL,NULL,'2026-09-19 14:40:00'),
(98,17,1,'quiz_completed',NULL,NULL,NULL,'2026-09-22 08:54:00'),
(99,17,1,'lesson_viewed',NULL,NULL,NULL,'2026-09-22 08:03:00'),
(100,17,1,'lesson_viewed',NULL,NULL,NULL,'2026-09-22 08:41:00'),
(101,17,1,'assignment_completed',NULL,NULL,NULL,'2026-09-24 18:03:00'),
(102,17,1,'lesson_viewed',NULL,NULL,NULL,'2026-09-24 16:52:00'),
(103,17,1,'lesson_completed',NULL,NULL,NULL,'2026-09-27 17:38:00'),
(104,17,1,'lesson_viewed',NULL,NULL,NULL,'2026-09-27 16:56:00'),
(105,17,1,'lesson_viewed',NULL,NULL,NULL,'2026-09-27 17:27:00'),
(106,17,1,'lesson_viewed',NULL,NULL,NULL,'2026-09-27 16:21:00'),
(107,28,1,'lesson_completed',NULL,NULL,NULL,'2026-08-30 10:56:00'),
(108,28,1,'lesson_viewed',NULL,NULL,NULL,'2026-08-30 09:33:00'),
(109,28,1,'lesson_viewed',NULL,NULL,NULL,'2026-08-30 10:46:00'),
(110,28,1,'lesson_completed',NULL,NULL,NULL,'2026-09-02 06:07:00'),
(111,28,1,'lesson_viewed',NULL,NULL,NULL,'2026-09-02 05:25:00'),
(112,28,1,'lesson_viewed',NULL,NULL,NULL,'2026-09-02 05:54:00'),
(113,28,1,'lesson_completed',NULL,NULL,NULL,'2026-09-05 08:44:00'),
(114,28,1,'lesson_viewed',NULL,NULL,NULL,'2026-09-05 07:44:00'),
(115,28,1,'quiz_completed',NULL,NULL,NULL,'2026-09-08 06:23:00'),
(116,28,1,'lesson_viewed',NULL,NULL,NULL,'2026-09-08 05:48:00'),
(117,28,1,'lesson_completed',NULL,NULL,NULL,'2026-09-11 06:35:00'),
(118,28,1,'lesson_viewed',NULL,NULL,NULL,'2026-09-11 05:24:00'),
(119,28,1,'lesson_viewed',NULL,NULL,NULL,'2026-09-11 05:51:00'),
(120,28,1,'lesson_completed',NULL,NULL,NULL,'2026-09-13 11:13:00'),
(121,28,1,'lesson_viewed',NULL,NULL,NULL,'2026-09-13 10:17:00'),
(122,28,1,'lesson_viewed',NULL,NULL,NULL,'2026-09-13 09:43:00'),
(123,28,1,'quiz_completed',NULL,NULL,NULL,'2026-09-16 13:12:00'),
(124,28,1,'lesson_viewed',NULL,NULL,NULL,'2026-09-16 11:46:00'),
(125,28,1,'assignment_completed',NULL,NULL,NULL,'2026-09-19 19:39:00'),
(126,28,1,'lesson_viewed',NULL,NULL,NULL,'2026-09-19 19:17:00'),
(127,28,1,'lesson_completed',NULL,NULL,NULL,'2026-09-22 09:53:00'),
(128,28,1,'lesson_viewed',NULL,NULL,NULL,'2026-09-22 08:23:00'),
(129,28,1,'assignment_completed',NULL,NULL,NULL,'2026-09-25 07:01:00'),
(130,28,1,'lesson_viewed',NULL,NULL,NULL,'2026-09-25 06:15:00'),
(131,28,1,'lesson_viewed',NULL,NULL,NULL,'2026-09-25 06:13:00'),
(132,8,1,'lesson_completed',NULL,NULL,NULL,'2026-09-04 10:45:00'),
(133,8,1,'lesson_viewed',NULL,NULL,NULL,'2026-09-04 10:16:00'),
(134,8,1,'lesson_viewed',NULL,NULL,NULL,'2026-09-04 10:22:00'),
(135,8,1,'lesson_viewed',NULL,NULL,NULL,'2026-09-04 09:22:00'),
(136,8,1,'lesson_completed',NULL,NULL,NULL,'2026-09-06 08:31:00'),
(137,8,1,'lesson_viewed',NULL,NULL,NULL,'2026-09-06 07:41:00'),
(138,8,1,'lesson_completed',NULL,NULL,NULL,'2026-09-09 17:24:00'),
(139,8,1,'lesson_viewed',NULL,NULL,NULL,'2026-09-09 16:45:00'),
(140,8,1,'quiz_completed',NULL,NULL,NULL,'2026-09-11 10:01:00'),
(141,8,1,'lesson_viewed',NULL,NULL,NULL,'2026-09-11 09:19:00'),
(142,8,1,'lesson_viewed',NULL,NULL,NULL,'2026-09-11 09:38:00'),
(143,8,1,'lesson_completed',NULL,NULL,NULL,'2026-09-14 16:25:00'),
(144,8,1,'lesson_viewed',NULL,NULL,NULL,'2026-09-14 15:19:00'),
(145,8,1,'lesson_viewed',NULL,NULL,NULL,'2026-09-14 15:03:00'),
(146,8,1,'lesson_completed',NULL,NULL,NULL,'2026-09-16 11:23:00'),
(147,8,1,'lesson_viewed',NULL,NULL,NULL,'2026-09-16 10:54:00'),
(148,8,1,'quiz_completed',NULL,NULL,NULL,'2026-09-18 07:33:00'),
(149,8,1,'lesson_viewed',NULL,NULL,NULL,'2026-09-18 06:46:00'),
(150,8,1,'assignment_completed',NULL,NULL,NULL,'2026-09-21 10:58:00'),
(151,8,1,'lesson_viewed',NULL,NULL,NULL,'2026-09-21 09:45:00'),
(152,8,1,'lesson_viewed',NULL,NULL,NULL,'2026-09-21 09:50:00'),
(153,8,1,'lesson_completed',NULL,NULL,NULL,'2026-09-23 12:08:00'),
(154,8,1,'lesson_viewed',NULL,NULL,NULL,'2026-09-23 10:45:00'),
(155,8,1,'lesson_viewed',NULL,NULL,NULL,'2026-09-23 10:40:00'),
(156,8,1,'assignment_completed',NULL,NULL,NULL,'2026-09-26 09:17:00'),
(157,8,1,'lesson_viewed',NULL,NULL,NULL,'2026-09-26 08:08:00'),
(158,24,1,'lesson_completed',NULL,NULL,NULL,'2026-09-11 13:02:00'),
(159,24,1,'lesson_viewed',NULL,NULL,NULL,'2026-09-11 12:20:00'),
(160,24,1,'lesson_completed',NULL,NULL,NULL,'2026-09-19 13:24:00'),
(161,24,1,'lesson_viewed',NULL,NULL,NULL,'2026-09-19 12:40:00'),
(162,24,1,'lesson_viewed',NULL,NULL,NULL,'2026-09-19 12:08:00'),
(163,7,1,'lesson_completed',NULL,NULL,NULL,'2026-09-03 07:27:00'),
(164,7,1,'lesson_viewed',NULL,NULL,NULL,'2026-09-03 06:05:00'),
(165,7,1,'lesson_viewed',NULL,NULL,NULL,'2026-09-03 07:00:00'),
(166,20,1,'lesson_completed',NULL,NULL,NULL,'2026-09-06 10:06:00'),
(167,20,1,'lesson_viewed',NULL,NULL,NULL,'2026-09-06 09:42:00'),
(168,20,1,'lesson_completed',NULL,NULL,NULL,'2026-09-10 12:27:00'),
(169,20,1,'lesson_viewed',NULL,NULL,NULL,'2026-09-10 11:15:00'),
(170,20,1,'lesson_completed',NULL,NULL,NULL,'2026-09-14 18:33:00'),
(171,20,1,'lesson_viewed',NULL,NULL,NULL,'2026-09-14 18:18:00'),
(172,20,1,'lesson_viewed',NULL,NULL,NULL,'2026-09-14 17:31:00'),
(173,20,1,'quiz_completed',NULL,NULL,NULL,'2026-09-18 12:56:00'),
(174,20,1,'lesson_viewed',NULL,NULL,NULL,'2026-09-18 11:46:00'),
(175,20,1,'lesson_viewed',NULL,NULL,NULL,'2026-09-18 12:27:00'),
(176,20,1,'lesson_completed',NULL,NULL,NULL,'2026-09-23 08:18:00'),
(177,20,1,'lesson_viewed',NULL,NULL,NULL,'2026-09-23 06:56:00'),
(178,20,1,'lesson_viewed',NULL,NULL,NULL,'2026-09-23 07:43:00'),
(179,27,1,'lesson_completed',NULL,NULL,NULL,'2026-09-05 16:44:00'),
(180,27,1,'lesson_viewed',NULL,NULL,NULL,'2026-09-05 16:36:00'),
(181,27,1,'lesson_viewed',NULL,NULL,NULL,'2026-09-05 16:28:00'),
(182,13,1,'lesson_completed',NULL,NULL,NULL,'2026-09-04 09:34:00'),
(183,13,1,'lesson_viewed',NULL,NULL,NULL,'2026-09-04 08:28:00'),
(184,9,1,'lesson_completed',NULL,NULL,NULL,'2026-09-04 12:10:00'),
(185,9,1,'lesson_viewed',NULL,NULL,NULL,'2026-09-04 12:02:00'),
(186,9,1,'lesson_completed',NULL,NULL,NULL,'2026-09-08 09:19:00'),
(187,9,1,'lesson_viewed',NULL,NULL,NULL,'2026-09-08 08:01:00'),
(188,9,1,'lesson_viewed',NULL,NULL,NULL,'2026-09-08 08:42:00'),
(189,9,1,'lesson_viewed',NULL,NULL,NULL,'2026-09-08 09:13:00'),
(190,9,1,'lesson_completed',NULL,NULL,NULL,'2026-09-13 14:20:00'),
(191,9,1,'lesson_viewed',NULL,NULL,NULL,'2026-09-13 14:10:00'),
(192,9,1,'lesson_viewed',NULL,NULL,NULL,'2026-09-13 13:13:00'),
(193,9,1,'lesson_viewed',NULL,NULL,NULL,'2026-09-13 13:19:00'),
(194,9,1,'quiz_completed',NULL,NULL,NULL,'2026-09-17 11:08:00'),
(195,9,1,'lesson_viewed',NULL,NULL,NULL,'2026-09-17 10:49:00'),
(196,9,1,'lesson_viewed',NULL,NULL,NULL,'2026-09-17 10:56:00'),
(197,9,1,'lesson_completed',NULL,NULL,NULL,'2026-09-22 14:36:00'),
(198,9,1,'lesson_viewed',NULL,NULL,NULL,'2026-09-22 13:10:00'),
(199,9,1,'lesson_viewed',NULL,NULL,NULL,'2026-09-22 14:03:00'),
(200,25,1,'lesson_completed',NULL,NULL,NULL,'2026-09-05 11:55:00'),
(201,25,1,'lesson_viewed',NULL,NULL,NULL,'2026-09-05 10:56:00'),
(202,25,1,'lesson_completed',NULL,NULL,NULL,'2026-09-09 17:41:00'),
(203,25,1,'lesson_viewed',NULL,NULL,NULL,'2026-09-09 16:14:00'),
(204,25,1,'lesson_viewed',NULL,NULL,NULL,'2026-09-09 17:17:00'),
(205,25,1,'lesson_completed',NULL,NULL,NULL,'2026-09-13 06:53:00'),
(206,25,1,'lesson_viewed',NULL,NULL,NULL,'2026-09-13 05:54:00'),
(207,25,1,'lesson_viewed',NULL,NULL,NULL,'2026-09-13 05:52:00'),
(208,25,1,'quiz_completed',NULL,NULL,NULL,'2026-09-16 17:37:00'),
(209,25,1,'lesson_viewed',NULL,NULL,NULL,'2026-09-16 16:10:00'),
(210,25,1,'lesson_viewed',NULL,NULL,NULL,'2026-09-16 16:35:00'),
(211,25,1,'lesson_viewed',NULL,NULL,NULL,'2026-09-16 16:38:00'),
(212,25,1,'lesson_completed',NULL,NULL,NULL,'2026-09-20 19:10:00'),
(213,25,1,'lesson_viewed',NULL,NULL,NULL,'2026-09-20 17:43:00'),
(214,25,1,'lesson_viewed',NULL,NULL,NULL,'2026-09-20 18:52:00'),
(215,25,1,'lesson_viewed',NULL,NULL,NULL,'2026-09-20 18:29:00'),
(216,25,1,'lesson_completed',NULL,NULL,NULL,'2026-09-24 09:10:00'),
(217,25,1,'lesson_viewed',NULL,NULL,NULL,'2026-09-24 09:04:00'),
(218,25,1,'lesson_viewed',NULL,NULL,NULL,'2026-09-24 08:37:00'),
(219,4,1,'lesson_completed',NULL,NULL,NULL,'2026-09-09 10:48:00'),
(220,4,1,'lesson_viewed',NULL,NULL,NULL,'2026-09-09 10:33:00'),
(221,4,1,'lesson_viewed',NULL,NULL,NULL,'2026-09-09 10:24:00'),
(222,4,1,'lesson_completed',NULL,NULL,NULL,'2026-09-13 06:05:00'),
(223,4,1,'lesson_viewed',NULL,NULL,NULL,'2026-09-13 05:57:00'),
(224,4,1,'lesson_viewed',NULL,NULL,NULL,'2026-09-13 05:35:00'),
(225,4,1,'lesson_viewed',NULL,NULL,NULL,'2026-09-13 05:56:00'),
(226,4,1,'lesson_completed',NULL,NULL,NULL,'2026-09-17 18:21:00'),
(227,4,1,'lesson_viewed',NULL,NULL,NULL,'2026-09-17 17:45:00'),
(228,4,1,'lesson_viewed',NULL,NULL,NULL,'2026-09-17 18:07:00'),
(229,4,1,'quiz_completed',NULL,NULL,NULL,'2026-09-21 12:38:00'),
(230,4,1,'lesson_viewed',NULL,NULL,NULL,'2026-09-21 12:16:00'),
(231,4,1,'lesson_viewed',NULL,NULL,NULL,'2026-09-21 11:36:00'),
(232,4,1,'lesson_viewed',NULL,NULL,NULL,'2026-09-21 11:48:00'),
(233,4,1,'lesson_completed',NULL,NULL,NULL,'2026-09-26 11:29:00'),
(234,4,1,'lesson_viewed',NULL,NULL,NULL,'2026-09-26 11:20:00'),
(235,4,1,'lesson_viewed',NULL,NULL,NULL,'2026-09-26 10:07:00'),
(236,6,1,'lesson_completed',NULL,NULL,NULL,'2026-09-04 14:49:00'),
(237,6,1,'lesson_viewed',NULL,NULL,NULL,'2026-09-04 14:20:00'),
(238,6,1,'lesson_completed',NULL,NULL,NULL,'2026-09-05 09:35:00'),
(239,6,1,'lesson_viewed',NULL,NULL,NULL,'2026-09-05 09:05:00'),
(240,6,1,'lesson_viewed',NULL,NULL,NULL,'2026-09-05 08:34:00'),
(241,12,1,'lesson_completed',NULL,NULL,NULL,'2026-09-10 14:01:00'),
(242,12,1,'lesson_viewed',NULL,NULL,NULL,'2026-09-10 13:28:00'),
(243,12,1,'lesson_viewed',NULL,NULL,NULL,'2026-09-10 12:33:00'),
(244,12,1,'lesson_completed',NULL,NULL,NULL,'2026-09-20 07:54:00'),
(245,12,1,'lesson_viewed',NULL,NULL,NULL,'2026-09-20 06:27:00'),
(246,12,1,'lesson_viewed',NULL,NULL,NULL,'2026-09-20 07:17:00'),
(247,16,1,'lesson_completed',NULL,NULL,NULL,'2026-09-10 16:17:00'),
(248,16,1,'lesson_viewed',NULL,NULL,NULL,'2026-09-10 15:29:00'),
(249,16,1,'lesson_viewed',NULL,NULL,NULL,'2026-09-10 15:35:00'),
(250,16,1,'lesson_completed',NULL,NULL,NULL,'2026-09-19 06:11:00'),
(251,16,1,'lesson_viewed',NULL,NULL,NULL,'2026-09-19 05:30:00'),
(252,16,1,'lesson_viewed',NULL,NULL,NULL,'2026-09-19 06:03:00'),
(253,23,1,'lesson_completed',NULL,NULL,NULL,'2026-09-09 13:03:00'),
(254,23,1,'lesson_viewed',NULL,NULL,NULL,'2026-09-09 12:13:00'),
(255,23,1,'lesson_viewed',NULL,NULL,NULL,'2026-09-09 12:00:00'),
(256,23,1,'lesson_completed',NULL,NULL,NULL,'2026-09-13 10:06:00'),
(257,23,1,'lesson_viewed',NULL,NULL,NULL,'2026-09-13 09:39:00'),
(258,23,1,'lesson_viewed',NULL,NULL,NULL,'2026-09-13 09:11:00'),
(259,23,1,'lesson_completed',NULL,NULL,NULL,'2026-09-17 17:55:00'),
(260,23,1,'lesson_viewed',NULL,NULL,NULL,'2026-09-17 17:05:00'),
(261,23,1,'lesson_viewed',NULL,NULL,NULL,'2026-09-17 17:24:00'),
(262,18,1,'lesson_completed',NULL,NULL,NULL,'2026-09-04 08:06:00'),
(263,18,1,'lesson_viewed',NULL,NULL,NULL,'2026-09-04 06:51:00'),
(264,18,1,'lesson_completed',NULL,NULL,NULL,'2026-09-08 09:47:00'),
(265,18,1,'lesson_viewed',NULL,NULL,NULL,'2026-09-08 09:32:00'),
(266,18,1,'lesson_viewed',NULL,NULL,NULL,'2026-09-08 09:10:00'),
(267,18,1,'lesson_completed',NULL,NULL,NULL,'2026-09-13 09:47:00'),
(268,18,1,'lesson_viewed',NULL,NULL,NULL,'2026-09-13 09:02:00'),
(269,18,1,'quiz_completed',NULL,NULL,NULL,'2026-09-17 11:33:00'),
(270,18,1,'lesson_viewed',NULL,NULL,NULL,'2026-09-17 11:06:00'),
(271,18,1,'lesson_viewed',NULL,NULL,NULL,'2026-09-17 10:08:00'),
(272,18,1,'lesson_completed',NULL,NULL,NULL,'2026-09-21 08:24:00'),
(273,18,1,'lesson_viewed',NULL,NULL,NULL,'2026-09-21 07:29:00'),
(274,18,1,'lesson_viewed',NULL,NULL,NULL,'2026-09-21 07:39:00'),
(275,18,1,'lesson_viewed',NULL,NULL,NULL,'2026-09-21 08:03:00'),
(276,18,1,'lesson_completed',NULL,NULL,NULL,'2026-09-26 15:50:00'),
(277,18,1,'lesson_viewed',NULL,NULL,NULL,'2026-09-26 14:39:00'),
(278,18,1,'lesson_viewed',NULL,NULL,NULL,'2026-09-26 14:44:00'),
(279,10,1,'lesson_completed',NULL,NULL,NULL,'2026-09-07 06:55:00'),
(280,10,1,'lesson_viewed',NULL,NULL,NULL,'2026-09-07 06:23:00'),
(281,10,1,'lesson_viewed',NULL,NULL,NULL,'2026-09-07 06:21:00'),
(282,10,1,'lesson_completed',NULL,NULL,NULL,'2026-09-11 16:41:00'),
(283,10,1,'lesson_viewed',NULL,NULL,NULL,'2026-09-11 15:21:00'),
(284,10,1,'lesson_completed',NULL,NULL,NULL,'2026-09-15 09:38:00'),
(285,10,1,'lesson_viewed',NULL,NULL,NULL,'2026-09-15 09:03:00'),
(286,10,1,'lesson_viewed',NULL,NULL,NULL,'2026-09-15 08:51:00'),
(287,10,1,'lesson_viewed',NULL,NULL,NULL,'2026-09-15 09:19:00'),
(288,10,1,'quiz_completed',NULL,NULL,NULL,'2026-09-19 09:52:00'),
(289,10,1,'lesson_viewed',NULL,NULL,NULL,'2026-09-19 09:39:00'),
(290,10,1,'lesson_viewed',NULL,NULL,NULL,'2026-09-19 09:07:00'),
(291,10,1,'lesson_viewed',NULL,NULL,NULL,'2026-09-19 08:57:00'),
(292,10,1,'lesson_completed',NULL,NULL,NULL,'2026-09-23 14:53:00'),
(293,10,1,'lesson_viewed',NULL,NULL,NULL,'2026-09-23 13:47:00'),
(294,10,1,'lesson_viewed',NULL,NULL,NULL,'2026-09-23 14:35:00'),
(295,6,2,'lesson_completed',NULL,NULL,NULL,'2026-09-08 15:51:00'),
(296,6,2,'lesson_viewed',NULL,NULL,NULL,'2026-09-08 15:36:00'),
(297,6,2,'lesson_viewed',NULL,NULL,NULL,'2026-09-08 14:21:00'),
(298,6,2,'lesson_completed',NULL,NULL,NULL,'2026-09-11 15:38:00'),
(299,6,2,'lesson_viewed',NULL,NULL,NULL,'2026-09-11 14:42:00'),
(300,6,2,'lesson_viewed',NULL,NULL,NULL,'2026-09-11 15:00:00'),
(301,6,2,'quiz_completed',NULL,NULL,NULL,'2026-09-14 17:07:00'),
(302,6,2,'lesson_viewed',NULL,NULL,NULL,'2026-09-14 16:51:00'),
(303,6,2,'lesson_viewed',NULL,NULL,NULL,'2026-09-14 15:50:00'),
(304,6,2,'lesson_completed',NULL,NULL,NULL,'2026-09-17 18:12:00'),
(305,6,2,'lesson_viewed',NULL,NULL,NULL,'2026-09-17 17:29:00'),
(306,6,2,'lesson_completed',NULL,NULL,NULL,'2026-09-20 08:50:00'),
(307,6,2,'lesson_viewed',NULL,NULL,NULL,'2026-09-20 08:44:00'),
(308,6,2,'lesson_viewed',NULL,NULL,NULL,'2026-09-20 07:20:00'),
(309,6,2,'quiz_completed',NULL,NULL,NULL,'2026-09-23 08:11:00'),
(310,6,2,'lesson_viewed',NULL,NULL,NULL,'2026-09-23 07:53:00'),
(311,6,2,'lesson_viewed',NULL,NULL,NULL,'2026-09-23 07:09:00'),
(312,6,2,'assignment_completed',NULL,NULL,NULL,'2026-09-26 12:33:00'),
(313,6,2,'lesson_viewed',NULL,NULL,NULL,'2026-09-26 12:21:00'),
(314,6,2,'lesson_viewed',NULL,NULL,NULL,'2026-09-26 11:53:00'),
(315,13,2,'lesson_completed',NULL,NULL,NULL,'2026-09-12 13:19:00'),
(316,13,2,'lesson_viewed',NULL,NULL,NULL,'2026-09-12 13:07:00'),
(317,13,2,'lesson_viewed',NULL,NULL,NULL,'2026-09-12 12:12:00'),
(318,13,2,'lesson_completed',NULL,NULL,NULL,'2026-09-19 08:55:00'),
(319,13,2,'lesson_viewed',NULL,NULL,NULL,'2026-09-19 08:18:00'),
(320,20,2,'lesson_completed',NULL,NULL,NULL,'2026-09-03 16:57:00'),
(321,20,2,'lesson_viewed',NULL,NULL,NULL,'2026-09-03 15:51:00'),
(322,20,2,'lesson_viewed',NULL,NULL,NULL,'2026-09-03 16:42:00'),
(323,20,2,'lesson_completed',NULL,NULL,NULL,'2026-09-10 19:55:00'),
(324,20,2,'lesson_viewed',NULL,NULL,NULL,'2026-09-10 19:48:00'),
(325,20,2,'lesson_viewed',NULL,NULL,NULL,'2026-09-10 19:02:00'),
(326,20,2,'quiz_completed',NULL,NULL,NULL,'2026-09-17 14:00:00'),
(327,20,2,'lesson_viewed',NULL,NULL,NULL,'2026-09-17 12:40:00'),
(328,20,2,'lesson_completed',NULL,NULL,NULL,'2026-09-24 16:22:00'),
(329,20,2,'lesson_viewed',NULL,NULL,NULL,'2026-09-24 14:57:00'),
(330,20,2,'lesson_viewed',NULL,NULL,NULL,'2026-09-24 15:27:00'),
(331,20,2,'lesson_viewed',NULL,NULL,NULL,'2026-09-24 15:11:00'),
(332,28,2,'lesson_completed',NULL,NULL,NULL,'2026-09-08 07:01:00'),
(333,28,2,'lesson_viewed',NULL,NULL,NULL,'2026-09-08 06:31:00'),
(334,28,2,'lesson_viewed',NULL,NULL,NULL,'2026-09-08 06:40:00'),
(335,28,2,'lesson_viewed',NULL,NULL,NULL,'2026-09-08 06:56:00'),
(336,28,2,'lesson_completed',NULL,NULL,NULL,'2026-09-13 19:17:00'),
(337,28,2,'lesson_viewed',NULL,NULL,NULL,'2026-09-13 18:10:00'),
(338,28,2,'lesson_viewed',NULL,NULL,NULL,'2026-09-13 19:04:00'),
(339,28,2,'lesson_viewed',NULL,NULL,NULL,'2026-09-13 17:57:00'),
(340,28,2,'quiz_completed',NULL,NULL,NULL,'2026-09-18 12:20:00'),
(341,28,2,'lesson_viewed',NULL,NULL,NULL,'2026-09-18 11:32:00'),
(342,28,2,'lesson_viewed',NULL,NULL,NULL,'2026-09-18 11:25:00'),
(343,28,2,'lesson_completed',NULL,NULL,NULL,'2026-09-23 17:35:00'),
(344,28,2,'lesson_viewed',NULL,NULL,NULL,'2026-09-23 16:34:00'),
(345,28,2,'lesson_viewed',NULL,NULL,NULL,'2026-09-23 17:03:00'),
(346,12,2,'lesson_completed',NULL,NULL,NULL,'2026-09-07 12:08:00'),
(347,12,2,'lesson_viewed',NULL,NULL,NULL,'2026-09-07 11:44:00'),
(348,12,2,'lesson_viewed',NULL,NULL,NULL,'2026-09-07 10:47:00'),
(349,12,2,'lesson_viewed',NULL,NULL,NULL,'2026-09-07 11:18:00'),
(350,12,2,'lesson_completed',NULL,NULL,NULL,'2026-09-10 07:19:00'),
(351,12,2,'lesson_viewed',NULL,NULL,NULL,'2026-09-10 06:31:00'),
(352,12,2,'quiz_completed',NULL,NULL,NULL,'2026-09-13 15:02:00'),
(353,12,2,'lesson_viewed',NULL,NULL,NULL,'2026-09-13 13:36:00'),
(354,12,2,'lesson_completed',NULL,NULL,NULL,'2026-09-16 12:53:00'),
(355,12,2,'lesson_viewed',NULL,NULL,NULL,'2026-09-16 12:42:00'),
(356,12,2,'lesson_viewed',NULL,NULL,NULL,'2026-09-16 11:57:00'),
(357,12,2,'lesson_completed',NULL,NULL,NULL,'2026-09-19 09:00:00'),
(358,12,2,'lesson_viewed',NULL,NULL,NULL,'2026-09-19 08:50:00'),
(359,12,2,'quiz_completed',NULL,NULL,NULL,'2026-09-22 09:43:00'),
(360,12,2,'lesson_viewed',NULL,NULL,NULL,'2026-09-22 09:34:00'),
(361,12,2,'lesson_viewed',NULL,NULL,NULL,'2026-09-22 09:19:00'),
(362,12,2,'assignment_completed',NULL,NULL,NULL,'2026-09-26 06:56:00'),
(363,12,2,'lesson_viewed',NULL,NULL,NULL,'2026-09-26 06:21:00'),
(364,12,2,'lesson_viewed',NULL,NULL,NULL,'2026-09-26 06:36:00'),
(365,11,2,'lesson_completed',NULL,NULL,NULL,'2026-08-30 12:44:00'),
(366,11,2,'lesson_viewed',NULL,NULL,NULL,'2026-08-30 11:56:00'),
(367,11,2,'lesson_viewed',NULL,NULL,NULL,'2026-08-30 11:47:00'),
(368,21,2,'lesson_completed',NULL,NULL,NULL,'2026-09-05 08:54:00'),
(369,21,2,'lesson_viewed',NULL,NULL,NULL,'2026-09-05 08:20:00'),
(370,21,2,'lesson_viewed',NULL,NULL,NULL,'2026-09-05 08:26:00'),
(371,21,2,'lesson_completed',NULL,NULL,NULL,'2026-09-09 19:19:00'),
(372,21,2,'lesson_viewed',NULL,NULL,NULL,'2026-09-09 18:01:00'),
(373,21,2,'lesson_viewed',NULL,NULL,NULL,'2026-09-09 17:58:00'),
(374,21,2,'quiz_completed',NULL,NULL,NULL,'2026-09-13 12:11:00'),
(375,21,2,'lesson_viewed',NULL,NULL,NULL,'2026-09-13 11:31:00'),
(376,21,2,'lesson_viewed',NULL,NULL,NULL,'2026-09-13 11:17:00'),
(377,21,2,'lesson_viewed',NULL,NULL,NULL,'2026-09-13 12:00:00'),
(378,21,2,'lesson_completed',NULL,NULL,NULL,'2026-09-17 18:26:00'),
(379,21,2,'lesson_viewed',NULL,NULL,NULL,'2026-09-17 17:39:00'),
(380,21,2,'lesson_completed',NULL,NULL,NULL,'2026-09-21 13:18:00'),
(381,21,2,'lesson_viewed',NULL,NULL,NULL,'2026-09-21 12:08:00'),
(382,21,2,'lesson_viewed',NULL,NULL,NULL,'2026-09-21 12:47:00'),
(383,21,2,'quiz_completed',NULL,NULL,NULL,'2026-09-25 18:47:00'),
(384,21,2,'lesson_viewed',NULL,NULL,NULL,'2026-09-25 18:28:00'),
(385,21,2,'lesson_viewed',NULL,NULL,NULL,'2026-09-25 18:34:00'),
(386,4,2,'lesson_completed',NULL,NULL,NULL,'2026-09-12 13:18:00'),
(387,4,2,'lesson_viewed',NULL,NULL,NULL,'2026-09-12 12:40:00'),
(388,4,2,'lesson_viewed',NULL,NULL,NULL,'2026-09-12 12:11:00'),
(389,4,2,'lesson_completed',NULL,NULL,NULL,'2026-09-19 19:47:00'),
(390,4,2,'lesson_viewed',NULL,NULL,NULL,'2026-09-19 19:14:00'),
(391,4,2,'lesson_viewed',NULL,NULL,NULL,'2026-09-19 19:39:00'),
(392,4,2,'quiz_completed',NULL,NULL,NULL,'2026-09-26 17:59:00'),
(393,4,2,'lesson_viewed',NULL,NULL,NULL,'2026-09-26 17:44:00'),
(394,4,2,'lesson_viewed',NULL,NULL,NULL,'2026-09-26 17:14:00'),
(395,4,2,'lesson_viewed',NULL,NULL,NULL,'2026-09-26 17:40:00'),
(396,14,2,'lesson_completed',NULL,NULL,NULL,'2026-09-07 08:46:00'),
(397,14,2,'lesson_viewed',NULL,NULL,NULL,'2026-09-07 07:48:00'),
(398,14,2,'lesson_completed',NULL,NULL,NULL,'2026-09-18 09:11:00'),
(399,14,2,'lesson_viewed',NULL,NULL,NULL,'2026-09-18 08:42:00'),
(400,19,2,'lesson_completed',NULL,NULL,NULL,'2026-09-09 06:51:00'),
(401,19,2,'lesson_viewed',NULL,NULL,NULL,'2026-09-09 05:49:00'),
(402,19,2,'lesson_viewed',NULL,NULL,NULL,'2026-09-09 05:50:00'),
(403,19,2,'lesson_completed',NULL,NULL,NULL,'2026-09-16 12:10:00'),
(404,19,2,'lesson_viewed',NULL,NULL,NULL,'2026-09-16 11:53:00'),
(405,19,2,'quiz_completed',NULL,NULL,NULL,'2026-09-24 18:56:00'),
(406,19,2,'lesson_viewed',NULL,NULL,NULL,'2026-09-24 18:24:00'),
(407,19,2,'lesson_viewed',NULL,NULL,NULL,'2026-09-24 18:18:00'),
(408,22,2,'lesson_completed',NULL,NULL,NULL,'2026-09-01 12:32:00'),
(409,22,2,'lesson_viewed',NULL,NULL,NULL,'2026-09-01 12:21:00'),
(410,22,2,'lesson_viewed',NULL,NULL,NULL,'2026-09-01 12:02:00'),
(411,22,2,'lesson_completed',NULL,NULL,NULL,'2026-09-06 12:12:00'),
(412,22,2,'lesson_viewed',NULL,NULL,NULL,'2026-09-06 11:41:00'),
(413,22,2,'quiz_completed',NULL,NULL,NULL,'2026-09-11 14:32:00'),
(414,22,2,'lesson_viewed',NULL,NULL,NULL,'2026-09-11 13:08:00'),
(415,22,2,'lesson_viewed',NULL,NULL,NULL,'2026-09-11 13:18:00'),
(416,22,2,'lesson_viewed',NULL,NULL,NULL,'2026-09-11 13:59:00'),
(417,22,2,'lesson_completed',NULL,NULL,NULL,'2026-09-16 09:06:00'),
(418,22,2,'lesson_viewed',NULL,NULL,NULL,'2026-09-16 08:30:00'),
(419,22,2,'lesson_viewed',NULL,NULL,NULL,'2026-09-16 08:48:00'),
(420,22,2,'lesson_viewed',NULL,NULL,NULL,'2026-09-16 08:30:00'),
(421,22,2,'lesson_completed',NULL,NULL,NULL,'2026-09-21 11:11:00'),
(422,22,2,'lesson_viewed',NULL,NULL,NULL,'2026-09-21 10:34:00'),
(423,22,2,'quiz_completed',NULL,NULL,NULL,'2026-09-26 18:38:00'),
(424,22,2,'lesson_viewed',NULL,NULL,NULL,'2026-09-26 17:28:00'),
(425,22,2,'lesson_viewed',NULL,NULL,NULL,'2026-09-26 18:04:00'),
(426,22,2,'lesson_viewed',NULL,NULL,NULL,'2026-09-26 18:22:00'),
(427,23,2,'lesson_completed',NULL,NULL,NULL,'2026-09-09 10:05:00'),
(428,23,2,'lesson_viewed',NULL,NULL,NULL,'2026-09-09 09:22:00'),
(429,23,2,'lesson_completed',NULL,NULL,NULL,'2026-09-14 11:34:00'),
(430,23,2,'lesson_viewed',NULL,NULL,NULL,'2026-09-14 10:41:00'),
(431,23,2,'quiz_completed',NULL,NULL,NULL,'2026-09-18 09:38:00'),
(432,23,2,'lesson_viewed',NULL,NULL,NULL,'2026-09-18 08:42:00'),
(433,23,2,'lesson_viewed',NULL,NULL,NULL,'2026-09-18 08:29:00'),
(434,23,2,'lesson_viewed',NULL,NULL,NULL,'2026-09-18 08:42:00'),
(435,23,2,'lesson_completed',NULL,NULL,NULL,'2026-09-23 13:38:00'),
(436,23,2,'lesson_viewed',NULL,NULL,NULL,'2026-09-23 12:13:00'),
(437,26,2,'lesson_completed',NULL,NULL,NULL,'2026-09-10 11:21:00'),
(438,26,2,'lesson_viewed',NULL,NULL,NULL,'2026-09-10 11:03:00'),
(439,26,2,'lesson_completed',NULL,NULL,NULL,'2026-09-15 15:17:00'),
(440,26,2,'lesson_viewed',NULL,NULL,NULL,'2026-09-15 14:38:00'),
(441,26,2,'lesson_viewed',NULL,NULL,NULL,'2026-09-15 13:48:00'),
(442,26,2,'quiz_completed',NULL,NULL,NULL,'2026-09-20 19:54:00'),
(443,26,2,'lesson_viewed',NULL,NULL,NULL,'2026-09-20 18:25:00'),
(444,26,2,'lesson_completed',NULL,NULL,NULL,'2026-09-25 14:56:00'),
(445,26,2,'lesson_viewed',NULL,NULL,NULL,'2026-09-25 13:44:00'),
(446,17,2,'lesson_completed',NULL,NULL,NULL,'2026-08-31 19:18:00'),
(447,17,2,'lesson_viewed',NULL,NULL,NULL,'2026-08-31 18:04:00'),
(448,17,2,'lesson_viewed',NULL,NULL,NULL,'2026-08-31 18:56:00'),
(449,15,2,'lesson_completed',NULL,NULL,NULL,'2026-09-08 08:36:00'),
(450,15,2,'lesson_viewed',NULL,NULL,NULL,'2026-09-08 08:04:00'),
(451,15,2,'lesson_completed',NULL,NULL,NULL,'2026-09-13 17:47:00'),
(452,15,2,'lesson_viewed',NULL,NULL,NULL,'2026-09-13 17:36:00'),
(453,15,2,'lesson_viewed',NULL,NULL,NULL,'2026-09-13 16:26:00'),
(454,15,2,'quiz_completed',NULL,NULL,NULL,'2026-09-18 10:44:00'),
(455,15,2,'lesson_viewed',NULL,NULL,NULL,'2026-09-18 10:28:00'),
(456,15,2,'lesson_viewed',NULL,NULL,NULL,'2026-09-18 09:21:00'),
(457,15,2,'lesson_completed',NULL,NULL,NULL,'2026-09-23 12:44:00'),
(458,15,2,'lesson_viewed',NULL,NULL,NULL,'2026-09-23 11:20:00'),
(459,15,2,'lesson_viewed',NULL,NULL,NULL,'2026-09-23 11:20:00'),
(460,17,3,'lesson_completed',NULL,NULL,NULL,'2026-09-04 16:54:00'),
(461,17,3,'lesson_viewed',NULL,NULL,NULL,'2026-09-04 15:59:00'),
(462,17,3,'lesson_viewed',NULL,NULL,NULL,'2026-09-04 15:27:00'),
(463,17,3,'lesson_completed',NULL,NULL,NULL,'2026-09-12 19:47:00'),
(464,17,3,'lesson_viewed',NULL,NULL,NULL,'2026-09-12 19:35:00'),
(465,17,3,'lesson_viewed',NULL,NULL,NULL,'2026-09-12 19:07:00'),
(466,17,3,'quiz_completed',NULL,NULL,NULL,'2026-09-19 19:00:00'),
(467,17,3,'lesson_viewed',NULL,NULL,NULL,'2026-09-19 18:48:00'),
(468,17,3,'lesson_viewed',NULL,NULL,NULL,'2026-09-19 18:11:00'),
(469,17,3,'lesson_viewed',NULL,NULL,NULL,'2026-09-19 18:05:00'),
(470,17,3,'lesson_completed',NULL,NULL,NULL,'2026-09-27 06:42:00'),
(471,17,3,'lesson_viewed',NULL,NULL,NULL,'2026-09-27 05:40:00'),
(472,16,3,'lesson_completed',NULL,NULL,NULL,'2026-09-09 10:39:00'),
(473,16,3,'lesson_viewed',NULL,NULL,NULL,'2026-09-09 09:14:00'),
(474,16,3,'lesson_viewed',NULL,NULL,NULL,'2026-09-09 10:09:00'),
(475,16,3,'lesson_completed',NULL,NULL,NULL,'2026-09-16 11:40:00'),
(476,16,3,'lesson_viewed',NULL,NULL,NULL,'2026-09-16 10:15:00'),
(477,16,3,'lesson_viewed',NULL,NULL,NULL,'2026-09-16 11:22:00'),
(478,16,3,'quiz_completed',NULL,NULL,NULL,'2026-09-24 14:53:00'),
(479,16,3,'lesson_viewed',NULL,NULL,NULL,'2026-09-24 13:23:00'),
(480,16,3,'lesson_viewed',NULL,NULL,NULL,'2026-09-24 14:06:00'),
(481,27,3,'lesson_completed',NULL,NULL,NULL,'2026-09-10 10:48:00'),
(482,27,3,'lesson_viewed',NULL,NULL,NULL,'2026-09-10 10:05:00'),
(483,27,3,'lesson_viewed',NULL,NULL,NULL,'2026-09-10 10:37:00'),
(484,27,3,'lesson_viewed',NULL,NULL,NULL,'2026-09-10 10:04:00'),
(485,27,3,'lesson_completed',NULL,NULL,NULL,'2026-09-16 15:28:00'),
(486,27,3,'lesson_viewed',NULL,NULL,NULL,'2026-09-16 15:11:00'),
(487,27,3,'lesson_viewed',NULL,NULL,NULL,'2026-09-16 14:44:00'),
(488,27,3,'quiz_completed',NULL,NULL,NULL,'2026-09-22 12:51:00'),
(489,27,3,'lesson_viewed',NULL,NULL,NULL,'2026-09-22 12:34:00'),
(490,6,3,'lesson_completed',NULL,NULL,NULL,'2026-09-07 15:29:00'),
(491,6,3,'lesson_viewed',NULL,NULL,NULL,'2026-09-07 14:53:00'),
(492,6,3,'lesson_viewed',NULL,NULL,NULL,'2026-09-07 15:16:00'),
(493,6,3,'lesson_completed',NULL,NULL,NULL,'2026-09-14 12:08:00'),
(494,6,3,'lesson_viewed',NULL,NULL,NULL,'2026-09-14 11:12:00'),
(495,6,3,'lesson_viewed',NULL,NULL,NULL,'2026-09-14 11:51:00'),
(496,6,3,'quiz_completed',NULL,NULL,NULL,'2026-09-20 15:09:00'),
(497,6,3,'lesson_viewed',NULL,NULL,NULL,'2026-09-20 14:28:00'),
(498,6,3,'lesson_viewed',NULL,NULL,NULL,'2026-09-20 14:22:00'),
(499,6,3,'lesson_completed',NULL,NULL,NULL,'2026-09-27 07:45:00'),
(500,6,3,'lesson_viewed',NULL,NULL,NULL,'2026-09-27 06:53:00'),
(501,6,3,'lesson_viewed',NULL,NULL,NULL,'2026-09-27 07:38:00'),
(502,10,3,'lesson_completed',NULL,NULL,NULL,'2026-09-10 11:46:00'),
(503,10,3,'lesson_viewed',NULL,NULL,NULL,'2026-09-10 11:11:00'),
(504,10,3,'lesson_viewed',NULL,NULL,NULL,'2026-09-10 10:38:00'),
(505,10,3,'lesson_viewed',NULL,NULL,NULL,'2026-09-10 11:26:00'),
(506,10,3,'lesson_completed',NULL,NULL,NULL,'2026-09-16 10:06:00'),
(507,10,3,'lesson_viewed',NULL,NULL,NULL,'2026-09-16 09:09:00'),
(508,10,3,'lesson_viewed',NULL,NULL,NULL,'2026-09-16 09:59:00'),
(509,10,3,'quiz_completed',NULL,NULL,NULL,'2026-09-22 14:12:00'),
(510,10,3,'lesson_viewed',NULL,NULL,NULL,'2026-09-22 13:11:00'),
(511,10,3,'lesson_viewed',NULL,NULL,NULL,'2026-09-22 13:56:00'),
(512,10,3,'lesson_viewed',NULL,NULL,NULL,'2026-09-22 13:41:00'),
(513,28,3,'lesson_completed',NULL,NULL,NULL,'2026-09-06 08:11:00'),
(514,28,3,'lesson_viewed',NULL,NULL,NULL,'2026-09-06 06:48:00'),
(515,28,3,'lesson_completed',NULL,NULL,NULL,'2026-09-15 09:48:00'),
(516,28,3,'lesson_viewed',NULL,NULL,NULL,'2026-09-15 09:27:00'),
(517,28,3,'quiz_completed',NULL,NULL,NULL,'2026-09-24 16:37:00'),
(518,28,3,'lesson_viewed',NULL,NULL,NULL,'2026-09-24 15:31:00'),
(519,28,3,'lesson_viewed',NULL,NULL,NULL,'2026-09-24 15:12:00'),
(520,28,3,'lesson_viewed',NULL,NULL,NULL,'2026-09-24 16:05:00'),
(521,23,3,'lesson_completed',NULL,NULL,NULL,'2026-09-08 18:12:00'),
(522,23,3,'lesson_viewed',NULL,NULL,NULL,'2026-09-08 16:43:00'),
(523,23,3,'lesson_completed',NULL,NULL,NULL,'2026-09-14 15:00:00'),
(524,23,3,'lesson_viewed',NULL,NULL,NULL,'2026-09-14 14:05:00'),
(525,23,3,'lesson_viewed',NULL,NULL,NULL,'2026-09-14 13:36:00'),
(526,23,3,'quiz_completed',NULL,NULL,NULL,'2026-09-19 06:09:00'),
(527,23,3,'lesson_viewed',NULL,NULL,NULL,'2026-09-19 05:49:00'),
(528,23,3,'lesson_viewed',NULL,NULL,NULL,'2026-09-19 06:04:00'),
(529,23,3,'lesson_completed',NULL,NULL,NULL,'2026-09-25 06:23:00'),
(530,23,3,'lesson_viewed',NULL,NULL,NULL,'2026-09-25 06:14:00'),
(531,4,3,'lesson_completed',NULL,NULL,NULL,'2026-09-12 06:14:00'),
(532,4,3,'lesson_viewed',NULL,NULL,NULL,'2026-09-12 05:17:00'),
(533,4,3,'lesson_viewed',NULL,NULL,NULL,'2026-09-12 05:05:00'),
(534,4,3,'lesson_completed',NULL,NULL,NULL,'2026-09-26 19:57:00'),
(535,4,3,'lesson_viewed',NULL,NULL,NULL,'2026-09-26 19:27:00'),
(536,4,3,'lesson_viewed',NULL,NULL,NULL,'2026-09-26 18:31:00'),
(537,11,3,'lesson_completed',NULL,NULL,NULL,'2026-09-06 06:20:00'),
(538,11,3,'lesson_viewed',NULL,NULL,NULL,'2026-09-06 05:32:00'),
(539,11,3,'lesson_viewed',NULL,NULL,NULL,'2026-09-06 05:23:00'),
(540,11,3,'lesson_viewed',NULL,NULL,NULL,'2026-09-06 05:18:00'),
(541,11,3,'lesson_completed',NULL,NULL,NULL,'2026-09-14 06:47:00'),
(542,11,3,'lesson_viewed',NULL,NULL,NULL,'2026-09-14 05:17:00'),
(543,11,3,'lesson_viewed',NULL,NULL,NULL,'2026-09-14 05:48:00'),
(544,11,3,'quiz_completed',NULL,NULL,NULL,'2026-09-22 11:20:00'),
(545,11,3,'lesson_viewed',NULL,NULL,NULL,'2026-09-22 11:14:00'),
(546,11,3,'lesson_viewed',NULL,NULL,NULL,'2026-09-22 09:58:00'),
(547,13,3,'lesson_completed',NULL,NULL,NULL,'2026-09-05 07:47:00'),
(548,13,3,'lesson_viewed',NULL,NULL,NULL,'2026-09-05 07:24:00'),
(549,13,3,'lesson_completed',NULL,NULL,NULL,'2026-09-11 07:58:00'),
(550,13,3,'lesson_viewed',NULL,NULL,NULL,'2026-09-11 06:41:00'),
(551,13,3,'lesson_viewed',NULL,NULL,NULL,'2026-09-11 07:33:00'),
(552,13,3,'quiz_completed',NULL,NULL,NULL,'2026-09-17 19:22:00'),
(553,13,3,'lesson_viewed',NULL,NULL,NULL,'2026-09-17 18:08:00'),
(554,13,3,'lesson_viewed',NULL,NULL,NULL,'2026-09-17 18:24:00'),
(555,13,3,'lesson_viewed',NULL,NULL,NULL,'2026-09-17 18:50:00'),
(556,13,3,'lesson_completed',NULL,NULL,NULL,'2026-09-24 16:17:00'),
(557,13,3,'lesson_viewed',NULL,NULL,NULL,'2026-09-24 15:56:00'),
(558,13,3,'lesson_viewed',NULL,NULL,NULL,'2026-09-24 14:47:00'),
(559,19,3,'lesson_completed',NULL,NULL,NULL,'2026-09-09 16:42:00'),
(560,19,3,'lesson_viewed',NULL,NULL,NULL,'2026-09-09 15:24:00'),
(561,19,3,'lesson_viewed',NULL,NULL,NULL,'2026-09-09 15:15:00'),
(562,19,3,'lesson_viewed',NULL,NULL,NULL,'2026-09-09 15:29:00'),
(563,19,3,'lesson_completed',NULL,NULL,NULL,'2026-09-14 19:15:00'),
(564,19,3,'lesson_viewed',NULL,NULL,NULL,'2026-09-14 18:50:00'),
(565,19,3,'lesson_viewed',NULL,NULL,NULL,'2026-09-14 18:09:00'),
(566,19,3,'quiz_completed',NULL,NULL,NULL,'2026-09-18 08:18:00'),
(567,19,3,'lesson_viewed',NULL,NULL,NULL,'2026-09-18 07:13:00'),
(568,19,3,'lesson_viewed',NULL,NULL,NULL,'2026-09-18 07:00:00'),
(569,19,3,'lesson_completed',NULL,NULL,NULL,'2026-09-23 09:46:00'),
(570,19,3,'lesson_viewed',NULL,NULL,NULL,'2026-09-23 08:34:00'),
(571,19,3,'lesson_viewed',NULL,NULL,NULL,'2026-09-23 09:27:00'),
(572,19,3,'lesson_viewed',NULL,NULL,NULL,'2026-09-23 09:37:00'),
(573,9,3,'lesson_completed',NULL,NULL,NULL,'2026-09-12 12:16:00'),
(574,9,3,'lesson_viewed',NULL,NULL,NULL,'2026-09-12 11:33:00'),
(575,9,3,'lesson_viewed',NULL,NULL,NULL,'2026-09-12 11:47:00'),
(576,9,3,'lesson_completed',NULL,NULL,NULL,'2026-09-26 12:09:00'),
(577,9,3,'lesson_viewed',NULL,NULL,NULL,'2026-09-26 10:41:00'),
(578,9,3,'lesson_viewed',NULL,NULL,NULL,'2026-09-26 12:04:00');
/*!40000 ALTER TABLE `activities` ENABLE KEYS */;

--
-- Table structure for table `announcements`
--

DROP TABLE IF EXISTS `announcements`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `announcements` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `course_id` bigint(20) unsigned NOT NULL,
  `user_id` bigint(20) unsigned NOT NULL,
  `title` varchar(255) NOT NULL,
  `body` text NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `announcements_course_id_foreign` (`course_id`),
  KEY `announcements_user_id_foreign` (`user_id`),
  CONSTRAINT `announcements_course_id_foreign` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE,
  CONSTRAINT `announcements_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `announcements`
--

/*!40000 ALTER TABLE `announcements` DISABLE KEYS */;
INSERT INTO `announcements` VALUES
(1,1,2,'Bienvenue dans le cours !','Commencez par le chapitre 1 et n\'hésitez pas à poser vos questions sur le **forum**. Le mini-projet est à rendre en fin de semaine 4.','2026-09-27 20:54:12','2026-09-27 20:54:12');
/*!40000 ALTER TABLE `announcements` ENABLE KEYS */;

--
-- Table structure for table `assignments`
--

DROP TABLE IF EXISTS `assignments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `assignments` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `course_id` bigint(20) unsigned NOT NULL,
  `section_id` bigint(20) unsigned DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `instructions` longtext DEFAULT NULL,
  `due_at` timestamp NULL DEFAULT NULL,
  `max_points` smallint(5) unsigned NOT NULL DEFAULT 20,
  `allow_late` tinyint(1) NOT NULL DEFAULT 1,
  `position` int(10) unsigned NOT NULL DEFAULT 0,
  `is_published` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `assignments_course_id_foreign` (`course_id`),
  KEY `assignments_section_id_foreign` (`section_id`),
  CONSTRAINT `assignments_course_id_foreign` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE,
  CONSTRAINT `assignments_section_id_foreign` FOREIGN KEY (`section_id`) REFERENCES `sections` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `assignments`
--

/*!40000 ALTER TABLE `assignments` DISABLE KEYS */;
INSERT INTO `assignments` VALUES
(1,1,2,'Mini-projet : analyse des ventes','Téléchargez le jeu de données de ventes, puis :\n\n1. Nettoyez les valeurs manquantes et les doublons\n2. Calculez le chiffre d\'affaires par région et par mois\n3. Rédigez 5 constats métier\n\nRendez votre notebook `.ipynb` ou un PDF.','2026-09-25 21:59:00',20,1,8,1,'2026-09-27 20:54:11','2026-09-27 20:54:11'),
(2,1,3,'Tableau de bord final','Produisez un tableau de bord de 3 graphiques répondant à la question : *quelles régions faut-il prioriser ?*','2026-10-09 21:59:00',20,1,10,1,'2026-09-27 20:54:11','2026-09-27 20:54:11'),
(3,2,5,'Étude de cas : base de données d\'une école','Proposez le MCD puis le script SQL de création des tables d\'une école (élèves, classes, notes, enseignants).','2026-10-17 21:59:00',20,1,17,1,'2026-09-27 20:54:11','2026-09-27 20:54:11'),
(4,3,7,'API de gestion de bibliothèque','Livrez une API CRUD sécurisée (livres, auteurs, emprunts) avec tests.','2026-10-12 21:59:00',20,1,22,1,'2026-09-27 20:54:11','2026-09-27 20:54:11');
/*!40000 ALTER TABLE `assignments` ENABLE KEYS */;

--
-- Table structure for table `cache`
--

DROP TABLE IF EXISTS `cache`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_expiration_index` (`expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache`
--

/*!40000 ALTER TABLE `cache` DISABLE KEYS */;
/*!40000 ALTER TABLE `cache` ENABLE KEYS */;

--
-- Table structure for table `cache_locks`
--

DROP TABLE IF EXISTS `cache_locks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_locks_expiration_index` (`expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache_locks`
--

/*!40000 ALTER TABLE `cache_locks` DISABLE KEYS */;
/*!40000 ALTER TABLE `cache_locks` ENABLE KEYS */;

--
-- Table structure for table `categories`
--

DROP TABLE IF EXISTS `categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `categories` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `color` varchar(20) NOT NULL DEFAULT 'indigo',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `categories_slug_unique` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `categories`
--

/*!40000 ALTER TABLE `categories` DISABLE KEYS */;
INSERT INTO `categories` VALUES
(1,'Data & IA','data-ia','indigo','2026-09-27 20:54:11','2026-09-27 20:54:11'),
(2,'Développement web','developpement-web','indigo','2026-09-27 20:54:11','2026-09-27 20:54:11'),
(3,'Bases de données','bases-de-donnees','indigo','2026-09-27 20:54:11','2026-09-27 20:54:11'),
(4,'Bureautique','bureautique','indigo','2026-09-27 20:54:11','2026-09-27 20:54:11');
/*!40000 ALTER TABLE `categories` ENABLE KEYS */;

--
-- Table structure for table `certificates`
--

DROP TABLE IF EXISTS `certificates`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `certificates` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `course_id` bigint(20) unsigned NOT NULL,
  `user_id` bigint(20) unsigned NOT NULL,
  `code` varchar(40) NOT NULL,
  `final_grade` decimal(5,2) DEFAULT NULL,
  `issued_at` timestamp NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `certificates_course_id_user_id_unique` (`course_id`,`user_id`),
  UNIQUE KEY `certificates_code_unique` (`code`),
  KEY `certificates_user_id_foreign` (`user_id`),
  CONSTRAINT `certificates_course_id_foreign` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE,
  CONSTRAINT `certificates_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `certificates`
--

/*!40000 ALTER TABLE `certificates` DISABLE KEYS */;
INSERT INTO `certificates` VALUES
(1,1,28,'Z8NZ-C8AI-QAMB',18.83,'2026-09-27 20:54:11','2026-09-27 20:54:11','2026-09-27 20:54:11'),
(2,1,8,'TDQL-LQWN-R2Y6',20.00,'2026-09-27 20:54:11','2026-09-27 20:54:11','2026-09-27 20:54:11'),
(3,2,6,'D2OG-H8EW-T0LF',16.67,'2026-09-27 20:54:11','2026-09-27 20:54:11','2026-09-27 20:54:11'),
(4,2,12,'47VP-JYHY-LFSK',16.67,'2026-09-27 20:54:11','2026-09-27 20:54:11','2026-09-27 20:54:11');
/*!40000 ALTER TABLE `certificates` ENABLE KEYS */;

--
-- Table structure for table `courses`
--

DROP TABLE IF EXISTS `courses`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `courses` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `teacher_id` bigint(20) unsigned NOT NULL,
  `category_id` bigint(20) unsigned DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `summary` varchar(300) DEFAULT NULL,
  `description` longtext DEFAULT NULL,
  `level` varchar(20) NOT NULL DEFAULT 'beginner',
  `language` varchar(5) NOT NULL DEFAULT 'fr',
  `enrollment_key` varchar(255) DEFAULT NULL,
  `is_published` tinyint(1) NOT NULL DEFAULT 0,
  `starts_at` date DEFAULT NULL,
  `ends_at` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `courses_slug_unique` (`slug`),
  KEY `courses_teacher_id_foreign` (`teacher_id`),
  KEY `courses_category_id_foreign` (`category_id`),
  KEY `courses_is_published_index` (`is_published`),
  CONSTRAINT `courses_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL,
  CONSTRAINT `courses_teacher_id_foreign` FOREIGN KEY (`teacher_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `courses`
--

/*!40000 ALTER TABLE `courses` DISABLE KEYS */;
INSERT INTO `courses` VALUES
(1,2,1,'Python pour l\'analyse de données','python-pour-lanalyse-de-donnees','Des bases du langage à pandas : chargez, nettoyez, analysez et visualisez vos données.','## Objectifs\n\n- Maîtriser la syntaxe de base de Python\n- Manipuler des tableaux de données avec **pandas**\n- Produire des graphiques clairs avec **matplotlib**\n\n## Prérequis\n\nAucun : ce cours part de zéro.\n\n## Évaluation\n\nQuiz auto-corrigés à chaque chapitre et un mini-projet d\'analyse noté sur 20.','beginner','fr',NULL,1,'2026-08-30','2026-10-25','2026-09-27 20:54:11','2026-09-27 20:54:11',NULL),
(2,2,3,'SQL et modélisation de bases de données','sql-et-modelisation-de-bases-de-donnees','Concevez un schéma relationnel propre et écrivez des requêtes SQL efficaces.','Du modèle conceptuel aux requêtes avancées : jointures, agrégations, sous-requêtes et index.','intermediate','fr',NULL,1,NULL,NULL,'2026-09-27 20:54:11','2026-09-27 20:54:11',NULL),
(3,3,2,'Créer une API REST avec Laravel 12','creer-une-api-rest-avec-laravel-12','Routes, contrôleurs, Eloquent, validation et authentification par jetons avec Sanctum.','Construisez pas à pas une API REST professionnelle avec Laravel 12 et PHP 8.2.','intermediate','fr',NULL,1,NULL,NULL,'2026-09-27 20:54:11','2026-09-27 20:54:11',NULL),
(4,2,1,'Machine Learning avec scikit-learn','machine-learning-avec-scikit-learn','Régression, classification et validation croisée.',NULL,'advanced','fr',NULL,0,NULL,NULL,'2026-09-27 20:54:11','2026-09-27 20:54:11',NULL);
/*!40000 ALTER TABLE `courses` ENABLE KEYS */;

--
-- Table structure for table `discussions`
--

DROP TABLE IF EXISTS `discussions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `discussions` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `course_id` bigint(20) unsigned NOT NULL,
  `user_id` bigint(20) unsigned NOT NULL,
  `title` varchar(255) NOT NULL,
  `body` text NOT NULL,
  `is_pinned` tinyint(1) NOT NULL DEFAULT 0,
  `is_locked` tinyint(1) NOT NULL DEFAULT 0,
  `last_reply_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `discussions_course_id_foreign` (`course_id`),
  KEY `discussions_user_id_foreign` (`user_id`),
  CONSTRAINT `discussions_course_id_foreign` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE,
  CONSTRAINT `discussions_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `discussions`
--

/*!40000 ALTER TABLE `discussions` DISABLE KEYS */;
INSERT INTO `discussions` VALUES
(1,1,4,'Différence entre liste et tuple ?','Je ne comprends pas quand utiliser un tuple plutôt qu\'une liste. Quelqu\'un peut expliquer ?',0,0,'2026-09-24 20:54:12','2026-09-27 20:54:12','2026-09-27 20:54:12'),
(2,1,5,'Erreur KeyError avec pandas','J\'obtiens `KeyError: \'Montant\'` en sélectionnant ma colonne.',0,0,'2026-09-25 20:54:12','2026-09-27 20:54:12','2026-09-27 20:54:12'),
(3,1,6,'Ressources pour aller plus loin','Des livres ou sites à conseiller pour progresser en data ?',1,0,'2026-09-26 20:54:12','2026-09-27 20:54:12','2026-09-27 20:54:12');
/*!40000 ALTER TABLE `discussions` ENABLE KEYS */;

--
-- Table structure for table `enrollments`
--

DROP TABLE IF EXISTS `enrollments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `enrollments` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `course_id` bigint(20) unsigned NOT NULL,
  `user_id` bigint(20) unsigned NOT NULL,
  `progress` tinyint(3) unsigned NOT NULL DEFAULT 0,
  `last_activity_at` timestamp NULL DEFAULT NULL,
  `completed_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `enrollments_course_id_user_id_unique` (`course_id`,`user_id`),
  KEY `enrollments_user_id_foreign` (`user_id`),
  CONSTRAINT `enrollments_course_id_foreign` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE,
  CONSTRAINT `enrollments_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=51 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `enrollments`
--

/*!40000 ALTER TABLE `enrollments` DISABLE KEYS */;
INSERT INTO `enrollments` VALUES
(1,1,22,0,'2026-09-06 20:54:11',NULL,'2026-09-02 20:54:11','2026-09-27 20:54:11'),
(2,1,15,90,'2026-09-26 20:54:11',NULL,'2026-09-02 20:54:11','2026-09-27 20:54:11'),
(3,1,26,50,'2026-09-24 20:54:11',NULL,'2026-08-28 20:54:11','2026-09-27 20:54:11'),
(4,1,11,50,'2026-09-26 20:54:11',NULL,'2026-09-05 20:54:11','2026-09-27 20:54:11'),
(5,1,19,60,'2026-09-26 20:54:11',NULL,'2026-09-02 20:54:11','2026-09-27 20:54:11'),
(6,1,17,90,'2026-09-27 20:54:11',NULL,'2026-09-05 20:54:11','2026-09-27 20:54:11'),
(7,1,28,100,'2026-09-25 20:54:11','2026-09-27 20:54:11','2026-08-28 20:54:11','2026-09-27 20:54:11'),
(8,1,8,100,'2026-09-26 20:54:11','2026-09-27 20:54:11','2026-09-02 20:54:11','2026-09-27 20:54:11'),
(9,1,24,20,'2026-09-19 20:54:11',NULL,'2026-09-04 20:54:11','2026-09-27 20:54:11'),
(10,1,7,10,'2026-09-03 20:54:11',NULL,'2026-09-02 20:54:11','2026-09-27 20:54:11'),
(11,1,20,50,'2026-09-23 20:54:11',NULL,'2026-09-02 20:54:11','2026-09-27 20:54:11'),
(12,1,27,10,'2026-09-05 20:54:11',NULL,'2026-08-29 20:54:11','2026-09-27 20:54:11'),
(13,1,13,10,'2026-09-04 20:54:11',NULL,'2026-08-31 20:54:11','2026-09-27 20:54:11'),
(14,1,9,40,'2026-09-22 20:54:11',NULL,'2026-08-31 20:54:11','2026-09-27 20:54:11'),
(15,1,25,60,'2026-09-24 20:54:11',NULL,'2026-09-02 20:54:11','2026-09-27 20:54:11'),
(16,1,4,50,'2026-09-26 20:54:11',NULL,'2026-09-05 20:54:11','2026-09-27 20:54:11'),
(17,1,6,20,'2026-09-05 20:54:11',NULL,'2026-09-03 20:54:11','2026-09-27 20:54:11'),
(18,1,12,20,'2026-09-20 20:54:11',NULL,'2026-08-31 20:54:11','2026-09-27 20:54:11'),
(19,1,16,20,'2026-09-19 20:54:11',NULL,'2026-09-01 20:54:11','2026-09-27 20:54:11'),
(20,1,23,30,'2026-09-17 20:54:11',NULL,'2026-09-05 20:54:11','2026-09-27 20:54:11'),
(21,1,18,60,'2026-09-26 20:54:11',NULL,'2026-08-31 20:54:11','2026-09-27 20:54:11'),
(22,1,10,50,'2026-09-23 20:54:11',NULL,'2026-09-04 20:54:11','2026-09-27 20:54:11'),
(23,2,6,100,'2026-09-26 20:54:11','2026-09-27 20:54:11','2026-09-05 20:54:11','2026-09-27 20:54:11'),
(24,2,13,28,'2026-09-19 20:54:11',NULL,'2026-09-05 20:54:11','2026-09-27 20:54:11'),
(25,2,20,42,'2026-09-24 20:54:11',NULL,'2026-08-28 20:54:11','2026-09-27 20:54:11'),
(26,2,28,57,'2026-09-23 20:54:11',NULL,'2026-09-03 20:54:11','2026-09-27 20:54:11'),
(27,2,12,100,'2026-09-26 20:54:11','2026-09-27 20:54:11','2026-09-04 20:54:11','2026-09-27 20:54:11'),
(28,2,11,14,'2026-08-30 20:54:11',NULL,'2026-08-28 20:54:11','2026-09-27 20:54:11'),
(29,2,21,85,'2026-09-25 20:54:11',NULL,'2026-09-01 20:54:11','2026-09-27 20:54:11'),
(30,2,4,42,'2026-09-26 20:54:11',NULL,'2026-09-05 20:54:11','2026-09-27 20:54:12'),
(31,2,14,28,'2026-09-18 20:54:12',NULL,'2026-08-28 20:54:12','2026-09-27 20:54:12'),
(32,2,8,0,'2026-08-30 20:54:12',NULL,'2026-09-01 20:54:12','2026-09-27 20:54:12'),
(33,2,19,42,'2026-09-24 20:54:12',NULL,'2026-09-02 20:54:12','2026-09-27 20:54:12'),
(34,2,22,85,'2026-09-26 20:54:12',NULL,'2026-08-28 20:54:12','2026-09-27 20:54:12'),
(35,2,23,57,'2026-09-23 20:54:12',NULL,'2026-09-05 20:54:12','2026-09-27 20:54:12'),
(36,2,26,57,'2026-09-25 20:54:12',NULL,'2026-09-05 20:54:12','2026-09-27 20:54:12'),
(37,2,17,14,'2026-08-30 20:54:12',NULL,'2026-08-30 20:54:12','2026-09-27 20:54:12'),
(38,2,15,57,'2026-09-23 20:54:12',NULL,'2026-09-03 20:54:12','2026-09-27 20:54:12'),
(39,3,17,80,'2026-09-27 20:54:12',NULL,'2026-08-28 20:54:12','2026-09-27 20:54:12'),
(40,3,16,40,'2026-09-24 20:54:12',NULL,'2026-09-02 20:54:12','2026-09-27 20:54:12'),
(41,3,27,40,'2026-09-22 20:54:12',NULL,'2026-09-04 20:54:12','2026-09-27 20:54:12'),
(42,3,6,80,'2026-09-27 20:54:12',NULL,'2026-09-01 20:54:12','2026-09-27 20:54:12'),
(43,3,10,60,'2026-09-22 20:54:12',NULL,'2026-09-04 20:54:12','2026-09-27 20:54:12'),
(44,3,28,60,'2026-09-24 20:54:12',NULL,'2026-08-28 20:54:12','2026-09-27 20:54:12'),
(45,3,23,60,'2026-09-25 20:54:12',NULL,'2026-09-03 20:54:12','2026-09-27 20:54:12'),
(46,3,4,40,'2026-09-26 20:54:12',NULL,'2026-08-29 20:54:12','2026-09-27 20:54:12'),
(47,3,11,60,'2026-09-22 20:54:12',NULL,'2026-08-29 20:54:12','2026-09-27 20:54:12'),
(48,3,13,60,'2026-09-24 20:54:12',NULL,'2026-08-30 20:54:12','2026-09-27 20:54:12'),
(49,3,19,60,'2026-09-23 20:54:12',NULL,'2026-09-05 20:54:12','2026-09-27 20:54:12'),
(50,3,9,40,'2026-09-26 20:54:12',NULL,'2026-08-30 20:54:12','2026-09-27 20:54:12');
/*!40000 ALTER TABLE `enrollments` ENABLE KEYS */;

--
-- Table structure for table `failed_jobs`
--

DROP TABLE IF EXISTS `failed_jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `failed_jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `failed_jobs`
--

/*!40000 ALTER TABLE `failed_jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `failed_jobs` ENABLE KEYS */;

--
-- Table structure for table `job_batches`
--

DROP TABLE IF EXISTS `job_batches`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `job_batches`
--

/*!40000 ALTER TABLE `job_batches` DISABLE KEYS */;
/*!40000 ALTER TABLE `job_batches` ENABLE KEYS */;

--
-- Table structure for table `jobs`
--

DROP TABLE IF EXISTS `jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) unsigned NOT NULL,
  `reserved_at` int(10) unsigned DEFAULT NULL,
  `available_at` int(10) unsigned NOT NULL,
  `created_at` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jobs`
--

/*!40000 ALTER TABLE `jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `jobs` ENABLE KEYS */;

--
-- Table structure for table `lesson_completions`
--

DROP TABLE IF EXISTS `lesson_completions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `lesson_completions` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `lesson_id` bigint(20) unsigned NOT NULL,
  `user_id` bigint(20) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `lesson_completions_lesson_id_user_id_unique` (`lesson_id`,`user_id`),
  KEY `lesson_completions_user_id_foreign` (`user_id`),
  CONSTRAINT `lesson_completions_lesson_id_foreign` FOREIGN KEY (`lesson_id`) REFERENCES `lessons` (`id`) ON DELETE CASCADE,
  CONSTRAINT `lesson_completions_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=149 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `lesson_completions`
--

/*!40000 ALTER TABLE `lesson_completions` DISABLE KEYS */;
INSERT INTO `lesson_completions` VALUES
(1,1,15,'2026-09-04 15:57:00','2026-09-04 15:57:00'),
(2,2,15,'2026-09-07 10:56:00','2026-09-07 10:56:00'),
(3,3,15,'2026-09-10 07:50:00','2026-09-10 07:50:00'),
(4,4,15,'2026-09-15 19:08:00','2026-09-15 19:08:00'),
(5,5,15,'2026-09-18 10:19:00','2026-09-18 10:19:00'),
(6,6,15,'2026-09-26 19:20:00','2026-09-26 19:20:00'),
(7,1,26,'2026-09-02 12:59:00','2026-09-02 12:59:00'),
(8,2,26,'2026-09-07 07:13:00','2026-09-07 07:13:00'),
(9,3,26,'2026-09-13 06:49:00','2026-09-13 06:49:00'),
(10,4,26,'2026-09-24 18:57:00','2026-09-24 18:57:00'),
(11,1,11,'2026-09-08 08:30:00','2026-09-08 08:30:00'),
(12,2,11,'2026-09-12 08:12:00','2026-09-12 08:12:00'),
(13,3,11,'2026-09-15 19:28:00','2026-09-15 19:28:00'),
(14,4,11,'2026-09-22 15:01:00','2026-09-22 15:01:00'),
(15,5,11,'2026-09-26 18:35:00','2026-09-26 18:35:00'),
(16,1,19,'2026-09-06 18:58:00','2026-09-06 18:58:00'),
(17,2,19,'2026-09-10 10:47:00','2026-09-10 10:47:00'),
(18,3,19,'2026-09-14 13:00:00','2026-09-14 13:00:00'),
(19,4,19,'2026-09-22 15:30:00','2026-09-22 15:30:00'),
(20,5,19,'2026-09-26 12:26:00','2026-09-26 12:26:00'),
(21,1,17,'2026-09-07 14:19:00','2026-09-07 14:19:00'),
(22,2,17,'2026-09-09 12:07:00','2026-09-09 12:07:00'),
(23,3,17,'2026-09-12 08:00:00','2026-09-12 08:00:00'),
(24,4,17,'2026-09-17 18:12:00','2026-09-17 18:12:00'),
(25,5,17,'2026-09-19 14:45:00','2026-09-19 14:45:00'),
(26,6,17,'2026-09-27 17:38:00','2026-09-27 17:38:00'),
(27,1,28,'2026-08-30 10:56:00','2026-08-30 10:56:00'),
(28,2,28,'2026-09-02 06:07:00','2026-09-02 06:07:00'),
(29,3,28,'2026-09-05 08:44:00','2026-09-05 08:44:00'),
(30,4,28,'2026-09-11 06:35:00','2026-09-11 06:35:00'),
(31,5,28,'2026-09-13 11:13:00','2026-09-13 11:13:00'),
(32,6,28,'2026-09-22 09:53:00','2026-09-22 09:53:00'),
(33,1,8,'2026-09-04 10:45:00','2026-09-04 10:45:00'),
(34,2,8,'2026-09-06 08:31:00','2026-09-06 08:31:00'),
(35,3,8,'2026-09-09 17:24:00','2026-09-09 17:24:00'),
(36,4,8,'2026-09-14 16:25:00','2026-09-14 16:25:00'),
(37,5,8,'2026-09-16 11:23:00','2026-09-16 11:23:00'),
(38,6,8,'2026-09-23 12:08:00','2026-09-23 12:08:00'),
(39,1,24,'2026-09-11 13:02:00','2026-09-11 13:02:00'),
(40,2,24,'2026-09-19 13:24:00','2026-09-19 13:24:00'),
(41,1,7,'2026-09-03 07:27:00','2026-09-03 07:27:00'),
(42,1,20,'2026-09-06 10:06:00','2026-09-06 10:06:00'),
(43,2,20,'2026-09-10 12:27:00','2026-09-10 12:27:00'),
(44,3,20,'2026-09-14 18:33:00','2026-09-14 18:33:00'),
(45,4,20,'2026-09-23 08:18:00','2026-09-23 08:18:00'),
(46,1,27,'2026-09-05 16:44:00','2026-09-05 16:44:00'),
(47,1,13,'2026-09-04 09:34:00','2026-09-04 09:34:00'),
(48,1,9,'2026-09-04 12:10:00','2026-09-04 12:10:00'),
(49,2,9,'2026-09-08 09:19:00','2026-09-08 09:19:00'),
(50,3,9,'2026-09-13 14:20:00','2026-09-13 14:20:00'),
(51,4,9,'2026-09-22 14:36:00','2026-09-22 14:36:00'),
(52,1,25,'2026-09-05 11:55:00','2026-09-05 11:55:00'),
(53,2,25,'2026-09-09 17:41:00','2026-09-09 17:41:00'),
(54,3,25,'2026-09-13 06:53:00','2026-09-13 06:53:00'),
(55,4,25,'2026-09-20 19:10:00','2026-09-20 19:10:00'),
(56,5,25,'2026-09-24 09:10:00','2026-09-24 09:10:00'),
(57,1,4,'2026-09-09 10:48:00','2026-09-09 10:48:00'),
(58,2,4,'2026-09-13 06:05:00','2026-09-13 06:05:00'),
(59,3,4,'2026-09-17 18:21:00','2026-09-17 18:21:00'),
(60,4,4,'2026-09-26 11:29:00','2026-09-26 11:29:00'),
(61,1,6,'2026-09-04 14:49:00','2026-09-04 14:49:00'),
(62,2,6,'2026-09-05 09:35:00','2026-09-05 09:35:00'),
(63,1,12,'2026-09-10 14:01:00','2026-09-10 14:01:00'),
(64,2,12,'2026-09-20 07:54:00','2026-09-20 07:54:00'),
(65,1,16,'2026-09-10 16:17:00','2026-09-10 16:17:00'),
(66,2,16,'2026-09-19 06:11:00','2026-09-19 06:11:00'),
(67,1,23,'2026-09-09 13:03:00','2026-09-09 13:03:00'),
(68,2,23,'2026-09-13 10:06:00','2026-09-13 10:06:00'),
(69,3,23,'2026-09-17 17:55:00','2026-09-17 17:55:00'),
(70,1,18,'2026-09-04 08:06:00','2026-09-04 08:06:00'),
(71,2,18,'2026-09-08 09:47:00','2026-09-08 09:47:00'),
(72,3,18,'2026-09-13 09:47:00','2026-09-13 09:47:00'),
(73,4,18,'2026-09-21 08:24:00','2026-09-21 08:24:00'),
(74,5,18,'2026-09-26 15:50:00','2026-09-26 15:50:00'),
(75,1,10,'2026-09-07 06:55:00','2026-09-07 06:55:00'),
(76,2,10,'2026-09-11 16:41:00','2026-09-11 16:41:00'),
(77,3,10,'2026-09-15 09:38:00','2026-09-15 09:38:00'),
(78,4,10,'2026-09-23 14:53:00','2026-09-23 14:53:00'),
(79,7,6,'2026-09-08 15:51:00','2026-09-08 15:51:00'),
(80,8,6,'2026-09-11 15:38:00','2026-09-11 15:38:00'),
(81,9,6,'2026-09-17 18:12:00','2026-09-17 18:12:00'),
(82,10,6,'2026-09-20 08:50:00','2026-09-20 08:50:00'),
(83,7,13,'2026-09-12 13:19:00','2026-09-12 13:19:00'),
(84,8,13,'2026-09-19 08:55:00','2026-09-19 08:55:00'),
(85,7,20,'2026-09-03 16:57:00','2026-09-03 16:57:00'),
(86,8,20,'2026-09-10 19:55:00','2026-09-10 19:55:00'),
(87,9,20,'2026-09-24 16:22:00','2026-09-24 16:22:00'),
(88,7,28,'2026-09-08 07:01:00','2026-09-08 07:01:00'),
(89,8,28,'2026-09-13 19:17:00','2026-09-13 19:17:00'),
(90,9,28,'2026-09-23 17:35:00','2026-09-23 17:35:00'),
(91,7,12,'2026-09-07 12:08:00','2026-09-07 12:08:00'),
(92,8,12,'2026-09-10 07:19:00','2026-09-10 07:19:00'),
(93,9,12,'2026-09-16 12:53:00','2026-09-16 12:53:00'),
(94,10,12,'2026-09-19 09:00:00','2026-09-19 09:00:00'),
(95,7,11,'2026-08-30 12:44:00','2026-08-30 12:44:00'),
(96,7,21,'2026-09-05 08:54:00','2026-09-05 08:54:00'),
(97,8,21,'2026-09-09 19:19:00','2026-09-09 19:19:00'),
(98,9,21,'2026-09-17 18:26:00','2026-09-17 18:26:00'),
(99,10,21,'2026-09-21 13:18:00','2026-09-21 13:18:00'),
(100,7,4,'2026-09-12 13:18:00','2026-09-12 13:18:00'),
(101,8,4,'2026-09-19 19:47:00','2026-09-19 19:47:00'),
(102,7,14,'2026-09-07 08:46:00','2026-09-07 08:46:00'),
(103,8,14,'2026-09-18 09:11:00','2026-09-18 09:11:00'),
(104,7,19,'2026-09-09 06:51:00','2026-09-09 06:51:00'),
(105,8,19,'2026-09-16 12:10:00','2026-09-16 12:10:00'),
(106,7,22,'2026-09-01 12:32:00','2026-09-01 12:32:00'),
(107,8,22,'2026-09-06 12:12:00','2026-09-06 12:12:00'),
(108,9,22,'2026-09-16 09:06:00','2026-09-16 09:06:00'),
(109,10,22,'2026-09-21 11:11:00','2026-09-21 11:11:00'),
(110,7,23,'2026-09-09 10:05:00','2026-09-09 10:05:00'),
(111,8,23,'2026-09-14 11:34:00','2026-09-14 11:34:00'),
(112,9,23,'2026-09-23 13:38:00','2026-09-23 13:38:00'),
(113,7,26,'2026-09-10 11:21:00','2026-09-10 11:21:00'),
(114,8,26,'2026-09-15 15:17:00','2026-09-15 15:17:00'),
(115,9,26,'2026-09-25 14:56:00','2026-09-25 14:56:00'),
(116,7,17,'2026-08-31 19:18:00','2026-08-31 19:18:00'),
(117,7,15,'2026-09-08 08:36:00','2026-09-08 08:36:00'),
(118,8,15,'2026-09-13 17:47:00','2026-09-13 17:47:00'),
(119,9,15,'2026-09-23 12:44:00','2026-09-23 12:44:00'),
(120,11,17,'2026-09-04 16:54:00','2026-09-04 16:54:00'),
(121,12,17,'2026-09-12 19:47:00','2026-09-12 19:47:00'),
(122,13,17,'2026-09-27 06:42:00','2026-09-27 06:42:00'),
(123,11,16,'2026-09-09 10:39:00','2026-09-09 10:39:00'),
(124,12,16,'2026-09-16 11:40:00','2026-09-16 11:40:00'),
(125,11,27,'2026-09-10 10:48:00','2026-09-10 10:48:00'),
(126,12,27,'2026-09-16 15:28:00','2026-09-16 15:28:00'),
(127,11,6,'2026-09-07 15:29:00','2026-09-07 15:29:00'),
(128,12,6,'2026-09-14 12:08:00','2026-09-14 12:08:00'),
(129,13,6,'2026-09-27 07:45:00','2026-09-27 07:45:00'),
(130,11,10,'2026-09-10 11:46:00','2026-09-10 11:46:00'),
(131,12,10,'2026-09-16 10:06:00','2026-09-16 10:06:00'),
(132,11,28,'2026-09-06 08:11:00','2026-09-06 08:11:00'),
(133,12,28,'2026-09-15 09:48:00','2026-09-15 09:48:00'),
(134,11,23,'2026-09-08 18:12:00','2026-09-08 18:12:00'),
(135,12,23,'2026-09-14 15:00:00','2026-09-14 15:00:00'),
(136,13,23,'2026-09-25 06:23:00','2026-09-25 06:23:00'),
(137,11,4,'2026-09-12 06:14:00','2026-09-12 06:14:00'),
(138,12,4,'2026-09-26 19:57:00','2026-09-26 19:57:00'),
(139,11,11,'2026-09-06 06:20:00','2026-09-06 06:20:00'),
(140,12,11,'2026-09-14 06:47:00','2026-09-14 06:47:00'),
(141,11,13,'2026-09-05 07:47:00','2026-09-05 07:47:00'),
(142,12,13,'2026-09-11 07:58:00','2026-09-11 07:58:00'),
(143,13,13,'2026-09-24 16:17:00','2026-09-24 16:17:00'),
(144,11,19,'2026-09-09 16:42:00','2026-09-09 16:42:00'),
(145,12,19,'2026-09-14 19:15:00','2026-09-14 19:15:00'),
(146,13,19,'2026-09-23 09:46:00','2026-09-23 09:46:00'),
(147,11,9,'2026-09-12 12:16:00','2026-09-12 12:16:00'),
(148,12,9,'2026-09-26 12:09:00','2026-09-26 12:09:00');
/*!40000 ALTER TABLE `lesson_completions` ENABLE KEYS */;

--
-- Table structure for table `lessons`
--

DROP TABLE IF EXISTS `lessons`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `lessons` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `course_id` bigint(20) unsigned NOT NULL,
  `section_id` bigint(20) unsigned NOT NULL,
  `title` varchar(255) NOT NULL,
  `type` varchar(20) NOT NULL DEFAULT 'text',
  `content` longtext DEFAULT NULL,
  `video_url` varchar(255) DEFAULT NULL,
  `attachment_path` varchar(255) DEFAULT NULL,
  `attachment_name` varchar(255) DEFAULT NULL,
  `duration_minutes` smallint(5) unsigned NOT NULL DEFAULT 10,
  `position` int(10) unsigned NOT NULL DEFAULT 0,
  `is_published` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `lessons_course_id_foreign` (`course_id`),
  KEY `lessons_section_id_foreign` (`section_id`),
  CONSTRAINT `lessons_course_id_foreign` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE,
  CONSTRAINT `lessons_section_id_foreign` FOREIGN KEY (`section_id`) REFERENCES `sections` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `lessons`
--

/*!40000 ALTER TABLE `lessons` DISABLE KEYS */;
INSERT INTO `lessons` VALUES
(1,1,1,'Variables et types de données','text','En Python, une **variable** est un nom qui référence une valeur. On la crée par simple affectation, sans déclarer son type.\n\n```python\nage = 25            # int\nprix = 19.99        # float\nnom = \"Awa\"         # str\nactif = True        # bool\n```\n\nPython est un langage à **typage dynamique** : le type est déterminé à l\'exécution. La fonction `type()` permet de connaître le type d\'une valeur.\n\nLes **listes** stockent des collections ordonnées et modifiables, tandis que les **tuples** sont immuables. Les **dictionnaires** associent des clés à des valeurs, ce qui est idéal pour représenter un enregistrement.\n\n```python\nnotes = [12, 15, 9]\netudiant = {\"nom\": \"Kofi\", \"moyenne\": 12.0}\n```',NULL,NULL,NULL,12,1,1,'2026-09-27 20:54:11','2026-09-27 20:54:11'),
(2,1,1,'Conditions et boucles','text','Les **conditions** s\'écrivent avec `if`, `elif` et `else`. L\'indentation délimite les blocs : elle est obligatoire en Python.\n\n```python\nif moyenne >= 10:\n    print(\"Admis\")\nelse:\n    print(\"Ajourné\")\n```\n\nLa boucle **for** parcourt les éléments d\'une séquence, tandis que la boucle **while** répète un bloc tant qu\'une condition reste vraie.\n\nLes **compréhensions de liste** offrent une syntaxe concise pour construire une liste : `carres = [x**2 for x in range(10)]`.',NULL,NULL,NULL,15,2,1,'2026-09-27 20:54:11','2026-09-27 20:54:11'),
(3,1,1,'Vidéo : premiers pas avec Jupyter','video','Découvrez l\'environnement **Jupyter Notebook**, l\'outil de référence des data analysts pour exécuter du code cellule par cellule.','https://www.youtube.com/watch?v=HW29067qVWk',NULL,NULL,8,3,1,'2026-09-27 20:54:11','2026-09-27 20:54:11'),
(4,1,2,'Le DataFrame','text','Le **DataFrame** est la structure centrale de pandas : un tableau à deux dimensions avec des colonnes nommées et typées.\n\n```python\nimport pandas as pd\ndf = pd.read_csv(\"ventes.csv\")\ndf.head()\n```\n\nLa méthode `head()` affiche les premières lignes, `info()` résume les types et valeurs manquantes, et `describe()` calcule les statistiques descriptives des colonnes numériques.\n\nOn sélectionne une colonne avec `df[\"montant\"]` et on filtre les lignes avec une condition booléenne : `df[df[\"montant\"] > 100]`.',NULL,NULL,NULL,20,5,1,'2026-09-27 20:54:11','2026-09-27 20:54:11'),
(5,1,2,'Nettoyer les données','text','Les données réelles sont rarement propres. Les **valeurs manquantes** se détectent avec `isna()` et se traitent avec `dropna()` ou `fillna()`.\n\nLes **doublons** se suppriment avec `drop_duplicates()`. Il faut aussi vérifier les types : une date stockée comme texte se convertit avec `pd.to_datetime()`.\n\nL\'agrégation se fait avec `groupby()` : `df.groupby(\"region\")[\"montant\"].sum()` calcule le chiffre d\'affaires par région.',NULL,NULL,NULL,18,6,1,'2026-09-27 20:54:11','2026-09-27 20:54:11'),
(6,1,3,'Graphiques avec matplotlib','text','La bibliothèque **matplotlib** permet de tracer des courbes, histogrammes et diagrammes.\n\n```python\nimport matplotlib.pyplot as plt\ndf.groupby(\"mois\")[\"montant\"].sum().plot(kind=\"bar\")\nplt.show()\n```\n\nUn bon graphique possède un titre explicite, des axes légendés et une seule idée principale.',NULL,NULL,NULL,15,9,1,'2026-09-27 20:54:11','2026-09-27 20:54:11'),
(7,2,4,'Entités, associations et cardinalités','text','Un **modèle conceptuel** décrit les entités (Client, Commande…) et leurs associations. Les **cardinalités** précisent combien d\'occurrences d\'une entité peuvent être liées à une autre.\n\nLa **normalisation** élimine la redondance : en troisième forme normale, chaque attribut non clé dépend uniquement de la clé primaire.',NULL,NULL,NULL,20,11,1,'2026-09-27 20:54:11','2026-09-27 20:54:11'),
(8,2,4,'Clés primaires et étrangères','text','La **clé primaire** identifie de façon unique chaque ligne d\'une table. Une **clé étrangère** référence la clé primaire d\'une autre table et garantit l\'intégrité référentielle.',NULL,NULL,NULL,12,12,1,'2026-09-27 20:54:11','2026-09-27 20:54:11'),
(9,2,5,'SELECT, WHERE et ORDER BY','text','```sql\nSELECT nom, ville FROM clients WHERE pays = \'Togo\' ORDER BY nom;\n```\n\nLa clause **WHERE** filtre les lignes, **ORDER BY** les trie.',NULL,NULL,NULL,15,14,1,'2026-09-27 20:54:11','2026-09-27 20:54:11'),
(10,2,5,'Jointures et agrégations','text','La **jointure interne** (INNER JOIN) ne conserve que les lignes correspondantes dans les deux tables, alors que la **jointure gauche** (LEFT JOIN) conserve toutes les lignes de la table de gauche.\n\nLes fonctions d\'agrégation (COUNT, SUM, AVG) s\'utilisent avec **GROUP BY** ; la clause **HAVING** filtre après agrégation.',NULL,NULL,NULL,25,15,1,'2026-09-27 20:54:11','2026-09-27 20:54:11'),
(11,3,6,'Routes et contrôleurs','text','Les routes d\'API se déclarent dans `routes/api.php`. Un **contrôleur de ressource** regroupe les actions index, store, show, update et destroy.\n\n```php\nRoute::apiResource(\'articles\', ArticleController::class);\n```',NULL,NULL,NULL,15,18,1,'2026-09-27 20:54:11','2026-09-27 20:54:11'),
(12,3,6,'Eloquent ORM','text','**Eloquent** associe chaque table à un modèle. Les relations (`hasMany`, `belongsTo`) se déclarent comme des méthodes, et le chargement anticipé avec `with()` évite le problème des requêtes N+1.',NULL,NULL,NULL,20,19,1,'2026-09-27 20:54:11','2026-09-27 20:54:11'),
(13,3,7,'Authentification avec Sanctum','text','**Sanctum** délivre des jetons d\'API personnels. Le middleware `auth:sanctum` protège les routes.',NULL,NULL,NULL,18,21,1,'2026-09-27 20:54:11','2026-09-27 20:54:11');
/*!40000 ALTER TABLE `lessons` ENABLE KEYS */;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES
(1,'0001_01_01_000000_create_users_table',1),
(2,'0001_01_01_000001_create_cache_table',1),
(3,'0001_01_01_000002_create_jobs_table',1),
(4,'2026_09_27_100000_create_lms_core_tables',1),
(5,'2026_09_27_100100_create_assessment_tables',1),
(6,'2026_09_27_100200_create_community_tables',1),
(7,'2026_09_27_200458_create_notifications_table',1),
(8,'2026_09_27_200459_create_personal_access_tokens_table',1);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;

--
-- Table structure for table `notifications`
--

DROP TABLE IF EXISTS `notifications`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `notifications` (
  `id` char(36) NOT NULL,
  `type` varchar(255) NOT NULL,
  `notifiable_type` varchar(255) NOT NULL,
  `notifiable_id` bigint(20) unsigned NOT NULL,
  `data` text NOT NULL,
  `read_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `notifications_notifiable_type_notifiable_id_index` (`notifiable_type`,`notifiable_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `notifications`
--

/*!40000 ALTER TABLE `notifications` DISABLE KEYS */;
/*!40000 ALTER TABLE `notifications` ENABLE KEYS */;

--
-- Table structure for table `password_reset_tokens`
--

DROP TABLE IF EXISTS `password_reset_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `password_reset_tokens`
--

/*!40000 ALTER TABLE `password_reset_tokens` DISABLE KEYS */;
/*!40000 ALTER TABLE `password_reset_tokens` ENABLE KEYS */;

--
-- Table structure for table `personal_access_tokens`
--

DROP TABLE IF EXISTS `personal_access_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(255) NOT NULL,
  `tokenable_id` bigint(20) unsigned NOT NULL,
  `name` text NOT NULL,
  `token` varchar(64) NOT NULL,
  `abilities` text DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`),
  KEY `personal_access_tokens_expires_at_index` (`expires_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `personal_access_tokens`
--

/*!40000 ALTER TABLE `personal_access_tokens` DISABLE KEYS */;
/*!40000 ALTER TABLE `personal_access_tokens` ENABLE KEYS */;

--
-- Table structure for table `questions`
--

DROP TABLE IF EXISTS `questions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `questions` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `quiz_id` bigint(20) unsigned NOT NULL,
  `type` varchar(20) NOT NULL,
  `prompt` text NOT NULL,
  `options` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`options`)),
  `correct` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`correct`)),
  `explanation` text DEFAULT NULL,
  `points` smallint(5) unsigned NOT NULL DEFAULT 1,
  `position` int(10) unsigned NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `questions_quiz_id_foreign` (`quiz_id`),
  CONSTRAINT `questions_quiz_id_foreign` FOREIGN KEY (`quiz_id`) REFERENCES `quizzes` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `questions`
--

/*!40000 ALTER TABLE `questions` DISABLE KEYS */;
INSERT INTO `questions` VALUES
(1,1,'single','Quel est le type de la valeur 3.14 en Python ?','[\"int\",\"float\",\"str\",\"decimal\"]','[1]','Un nombre à virgule est un float.',1,1,'2026-09-27 20:54:11','2026-09-27 20:54:11'),
(2,1,'true_false','En Python, l\'indentation est facultative.','[]','[1]','L\'indentation délimite les blocs : elle est obligatoire.',1,2,'2026-09-27 20:54:11','2026-09-27 20:54:11'),
(3,1,'multiple','Quelles structures sont modifiables (mutables) ?','[\"list\",\"tuple\",\"dict\",\"str\"]','[0,2]','Les listes et dictionnaires sont mutables ; tuples et chaînes sont immuables.',1,3,'2026-09-27 20:54:11','2026-09-27 20:54:11'),
(4,1,'short','Quelle fonction renvoie le type d\'une valeur ?','[]','[\"type\",\"type()\"]','type(valeur) renvoie la classe de la valeur.',1,4,'2026-09-27 20:54:11','2026-09-27 20:54:11'),
(5,1,'single','Que produit [x*2 for x in range(3)] ?','[\"[0, 2, 4]\",\"[2, 4, 6]\",\"[0, 1, 2]\",\"Une erreur\"]','[0]','range(3) produit 0, 1, 2.',1,5,'2026-09-27 20:54:11','2026-09-27 20:54:11'),
(6,2,'single','Quelle méthode affiche les premières lignes d\'un DataFrame ?','[\"first()\",\"head()\",\"top()\",\"show()\"]','[1]',NULL,1,1,'2026-09-27 20:54:11','2026-09-27 20:54:11'),
(7,2,'single','Comment remplacer les valeurs manquantes par 0 ?','[\"df.dropna(0)\",\"df.fillna(0)\",\"df.replace(None)\",\"df.isna(0)\"]','[1]',NULL,1,2,'2026-09-27 20:54:11','2026-09-27 20:54:11'),
(8,2,'multiple','Quelles méthodes aident à explorer un jeu de données ?','[\"info()\",\"describe()\",\"to_csv()\",\"head()\"]','[0,1,3]','to_csv() sert à exporter.',1,3,'2026-09-27 20:54:11','2026-09-27 20:54:11'),
(9,2,'true_false','groupby() permet d\'agréger des données par catégorie.','[]','[0]',NULL,1,4,'2026-09-27 20:54:11','2026-09-27 20:54:11'),
(10,3,'single','Quel est le rôle d\'une clé étrangère ?','[\"Acc\\u00e9l\\u00e9rer les requ\\u00eates\",\"Garantir l\'int\\u00e9grit\\u00e9 r\\u00e9f\\u00e9rentielle\",\"Chiffrer les donn\\u00e9es\",\"Trier la table\"]','[1]',NULL,1,1,'2026-09-27 20:54:11','2026-09-27 20:54:11'),
(11,3,'true_false','Une table peut avoir plusieurs clés primaires distinctes.','[]','[1]','Une seule clé primaire, éventuellement composée de plusieurs colonnes.',1,2,'2026-09-27 20:54:11','2026-09-27 20:54:11'),
(12,3,'short','Quel processus élimine la redondance dans un schéma ?','[]','[\"normalisation\",\"la normalisation\"]',NULL,1,3,'2026-09-27 20:54:11','2026-09-27 20:54:11'),
(13,4,'single','Quelle clause filtre après un GROUP BY ?','[\"WHERE\",\"HAVING\",\"FILTER\",\"LIMIT\"]','[1]',NULL,1,1,'2026-09-27 20:54:11','2026-09-27 20:54:11'),
(14,4,'multiple','Quelles sont des fonctions d\'agrégation ?','[\"COUNT\",\"SUM\",\"CONCAT\",\"AVG\"]','[0,1,3]',NULL,1,2,'2026-09-27 20:54:11','2026-09-27 20:54:11'),
(15,4,'single','Quelle jointure conserve toutes les lignes de la table de gauche ?','[\"INNER JOIN\",\"LEFT JOIN\",\"CROSS JOIN\",\"SELF JOIN\"]','[1]',NULL,1,3,'2026-09-27 20:54:11','2026-09-27 20:54:11'),
(16,5,'single','Quelle méthode évite le problème N+1 ?','[\"load()\",\"with()\",\"all()\",\"find()\"]','[1]','with() charge les relations en amont (eager loading).',1,1,'2026-09-27 20:54:11','2026-09-27 20:54:11'),
(17,5,'true_false','Route::apiResource crée aussi les routes create et edit.','[]','[1]','apiResource exclut les formulaires create/edit.',1,2,'2026-09-27 20:54:11','2026-09-27 20:54:11');
/*!40000 ALTER TABLE `questions` ENABLE KEYS */;

--
-- Table structure for table `quiz_attempts`
--

DROP TABLE IF EXISTS `quiz_attempts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `quiz_attempts` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `quiz_id` bigint(20) unsigned NOT NULL,
  `user_id` bigint(20) unsigned NOT NULL,
  `question_order` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`question_order`)),
  `answers` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`answers`)),
  `score` decimal(8,2) DEFAULT NULL,
  `max_score` decimal(8,2) DEFAULT NULL,
  `percent` decimal(5,2) DEFAULT NULL,
  `passed` tinyint(1) NOT NULL DEFAULT 0,
  `started_at` timestamp NOT NULL,
  `submitted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `quiz_attempts_user_id_foreign` (`user_id`),
  KEY `quiz_attempts_quiz_id_user_id_index` (`quiz_id`,`user_id`),
  CONSTRAINT `quiz_attempts_quiz_id_foreign` FOREIGN KEY (`quiz_id`) REFERENCES `quizzes` (`id`) ON DELETE CASCADE,
  CONSTRAINT `quiz_attempts_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=46 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `quiz_attempts`
--

/*!40000 ALTER TABLE `quiz_attempts` DISABLE KEYS */;
INSERT INTO `quiz_attempts` VALUES
(1,1,15,'[1,2,3,4,5]','{\"1\":{\"given\":1,\"earned\":1,\"correct\":true},\"2\":{\"given\":1,\"earned\":1,\"correct\":true},\"3\":{\"given\":[0,2],\"earned\":1,\"correct\":true},\"4\":{\"given\":\"type\",\"earned\":1,\"correct\":true},\"5\":{\"given\":1,\"earned\":0,\"correct\":false}}',4.00,5.00,80.00,1,'2026-09-12 16:17:00','2026-09-12 16:25:00','2026-09-12 16:25:00','2026-09-27 20:54:11'),
(2,2,15,'[6,7,8,9]','{\"6\":{\"given\":2,\"earned\":0,\"correct\":false},\"7\":{\"given\":1,\"earned\":1,\"correct\":true},\"8\":{\"given\":[0,1,3],\"earned\":1,\"correct\":true},\"9\":{\"given\":0,\"earned\":1,\"correct\":true}}',3.00,4.00,75.00,1,'2026-09-20 07:50:00','2026-09-20 07:58:00','2026-09-20 07:58:00','2026-09-27 20:54:11'),
(3,1,26,'[1,2,3,4,5]','{\"1\":{\"given\":2,\"earned\":0,\"correct\":false},\"2\":{\"given\":1,\"earned\":1,\"correct\":true},\"3\":{\"given\":[0,2],\"earned\":1,\"correct\":true},\"4\":{\"given\":\"je ne sais pas\",\"earned\":0,\"correct\":false},\"5\":{\"given\":0,\"earned\":1,\"correct\":true}}',3.00,5.00,60.00,1,'2026-09-18 19:47:00','2026-09-18 19:55:00','2026-09-18 19:55:00','2026-09-27 20:54:11'),
(4,1,11,'[1,2,3,4,5]','{\"1\":{\"given\":2,\"earned\":0,\"correct\":false},\"2\":{\"given\":0,\"earned\":0,\"correct\":false},\"3\":{\"given\":[0,2],\"earned\":1,\"correct\":true},\"4\":{\"given\":\"je ne sais pas\",\"earned\":0,\"correct\":false},\"5\":{\"given\":0,\"earned\":1,\"correct\":true}}',2.00,5.00,40.00,0,'2026-09-19 18:30:00','2026-09-19 18:38:00','2026-09-19 18:38:00','2026-09-27 20:54:11'),
(5,1,19,'[1,2,3,4,5]','{\"1\":{\"given\":2,\"earned\":0,\"correct\":false},\"2\":{\"given\":1,\"earned\":1,\"correct\":true},\"3\":{\"given\":[0],\"earned\":0.5,\"correct\":false},\"4\":{\"given\":\"type\",\"earned\":1,\"correct\":true},\"5\":{\"given\":1,\"earned\":0,\"correct\":false}}',2.50,5.00,50.00,0,'2026-09-18 14:36:00','2026-09-18 14:44:00','2026-09-18 14:44:00','2026-09-27 20:54:11'),
(6,1,19,'[1,2,3,4,5]','{\"1\":{\"given\":1,\"earned\":1,\"correct\":true},\"2\":{\"given\":0,\"earned\":0,\"correct\":false},\"3\":{\"given\":[0,2],\"earned\":1,\"correct\":true},\"4\":{\"given\":\"type\",\"earned\":1,\"correct\":true},\"5\":{\"given\":0,\"earned\":1,\"correct\":true}}',4.00,5.00,80.00,1,'2026-09-18 14:36:00','2026-09-18 14:44:00','2026-09-18 14:44:00','2026-09-27 20:54:11'),
(7,1,17,'[1,2,3,4,5]','{\"1\":{\"given\":1,\"earned\":1,\"correct\":true},\"2\":{\"given\":1,\"earned\":1,\"correct\":true},\"3\":{\"given\":[0],\"earned\":0.5,\"correct\":false},\"4\":{\"given\":\"type\",\"earned\":1,\"correct\":true},\"5\":{\"given\":0,\"earned\":1,\"correct\":true}}',4.50,5.00,90.00,1,'2026-09-14 09:51:00','2026-09-14 09:59:00','2026-09-14 09:59:00','2026-09-27 20:54:11'),
(8,2,17,'[6,7,8,9]','{\"6\":{\"given\":1,\"earned\":1,\"correct\":true},\"7\":{\"given\":1,\"earned\":1,\"correct\":true},\"8\":{\"given\":[0,1,3],\"earned\":1,\"correct\":true},\"9\":{\"given\":0,\"earned\":1,\"correct\":true}}',4.00,4.00,100.00,1,'2026-09-22 08:46:00','2026-09-22 08:54:00','2026-09-22 08:54:00','2026-09-27 20:54:11'),
(9,1,28,'[1,2,3,4,5]','{\"1\":{\"given\":1,\"earned\":1,\"correct\":true},\"2\":{\"given\":1,\"earned\":1,\"correct\":true},\"3\":{\"given\":[0],\"earned\":0.5,\"correct\":false},\"4\":{\"given\":\"type\",\"earned\":1,\"correct\":true},\"5\":{\"given\":0,\"earned\":1,\"correct\":true}}',4.50,5.00,90.00,1,'2026-09-08 06:15:00','2026-09-08 06:23:00','2026-09-08 06:23:00','2026-09-27 20:54:11'),
(10,2,28,'[6,7,8,9]','{\"6\":{\"given\":1,\"earned\":1,\"correct\":true},\"7\":{\"given\":1,\"earned\":1,\"correct\":true},\"8\":{\"given\":[0,1,3],\"earned\":1,\"correct\":true},\"9\":{\"given\":0,\"earned\":1,\"correct\":true}}',4.00,4.00,100.00,1,'2026-09-16 13:04:00','2026-09-16 13:12:00','2026-09-16 13:12:00','2026-09-27 20:54:11'),
(11,1,8,'[1,2,3,4,5]','{\"1\":{\"given\":1,\"earned\":1,\"correct\":true},\"2\":{\"given\":1,\"earned\":1,\"correct\":true},\"3\":{\"given\":[0,2],\"earned\":1,\"correct\":true},\"4\":{\"given\":\"type\",\"earned\":1,\"correct\":true},\"5\":{\"given\":0,\"earned\":1,\"correct\":true}}',5.00,5.00,100.00,1,'2026-09-11 09:53:00','2026-09-11 10:01:00','2026-09-11 10:01:00','2026-09-27 20:54:11'),
(12,2,8,'[6,7,8,9]','{\"6\":{\"given\":1,\"earned\":1,\"correct\":true},\"7\":{\"given\":1,\"earned\":1,\"correct\":true},\"8\":{\"given\":[0,1,3],\"earned\":1,\"correct\":true},\"9\":{\"given\":0,\"earned\":1,\"correct\":true}}',4.00,4.00,100.00,1,'2026-09-18 07:25:00','2026-09-18 07:33:00','2026-09-18 07:33:00','2026-09-27 20:54:11'),
(13,1,20,'[1,2,3,4,5]','{\"1\":{\"given\":1,\"earned\":1,\"correct\":true},\"2\":{\"given\":1,\"earned\":1,\"correct\":true},\"3\":{\"given\":[0],\"earned\":0.5,\"correct\":false},\"4\":{\"given\":\"type\",\"earned\":1,\"correct\":true},\"5\":{\"given\":0,\"earned\":1,\"correct\":true}}',4.50,5.00,90.00,1,'2026-09-18 12:48:00','2026-09-18 12:56:00','2026-09-18 12:56:00','2026-09-27 20:54:11'),
(14,1,9,'[1,2,3,4,5]','{\"1\":{\"given\":2,\"earned\":0,\"correct\":false},\"2\":{\"given\":0,\"earned\":0,\"correct\":false},\"3\":{\"given\":[0],\"earned\":0.5,\"correct\":false},\"4\":{\"given\":\"type\",\"earned\":1,\"correct\":true},\"5\":{\"given\":1,\"earned\":0,\"correct\":false}}',1.50,5.00,30.00,0,'2026-09-17 11:00:00','2026-09-17 11:08:00','2026-09-17 11:08:00','2026-09-27 20:54:11'),
(15,1,25,'[1,2,3,4,5]','{\"1\":{\"given\":1,\"earned\":1,\"correct\":true},\"2\":{\"given\":1,\"earned\":1,\"correct\":true},\"3\":{\"given\":[0,2],\"earned\":1,\"correct\":true},\"4\":{\"given\":\"je ne sais pas\",\"earned\":0,\"correct\":false},\"5\":{\"given\":0,\"earned\":1,\"correct\":true}}',4.00,5.00,80.00,1,'2026-09-16 17:29:00','2026-09-16 17:37:00','2026-09-16 17:37:00','2026-09-27 20:54:11'),
(16,1,4,'[1,2,3,4,5]','{\"1\":{\"given\":1,\"earned\":1,\"correct\":true},\"2\":{\"given\":1,\"earned\":1,\"correct\":true},\"3\":{\"given\":[0,2],\"earned\":1,\"correct\":true},\"4\":{\"given\":\"je ne sais pas\",\"earned\":0,\"correct\":false},\"5\":{\"given\":1,\"earned\":0,\"correct\":false}}',3.00,5.00,60.00,1,'2026-09-21 12:30:00','2026-09-21 12:38:00','2026-09-21 12:38:00','2026-09-27 20:54:11'),
(17,1,18,'[1,2,3,4,5]','{\"1\":{\"given\":1,\"earned\":1,\"correct\":true},\"2\":{\"given\":0,\"earned\":0,\"correct\":false},\"3\":{\"given\":[0,2],\"earned\":1,\"correct\":true},\"4\":{\"given\":\"type\",\"earned\":1,\"correct\":true},\"5\":{\"given\":0,\"earned\":1,\"correct\":true}}',4.00,5.00,80.00,1,'2026-09-17 11:25:00','2026-09-17 11:33:00','2026-09-17 11:33:00','2026-09-27 20:54:11'),
(18,1,10,'[1,2,3,4,5]','{\"1\":{\"given\":1,\"earned\":1,\"correct\":true},\"2\":{\"given\":1,\"earned\":1,\"correct\":true},\"3\":{\"given\":[0,2],\"earned\":1,\"correct\":true},\"4\":{\"given\":\"type\",\"earned\":1,\"correct\":true},\"5\":{\"given\":1,\"earned\":0,\"correct\":false}}',4.00,5.00,80.00,1,'2026-09-19 09:44:00','2026-09-19 09:52:00','2026-09-19 09:52:00','2026-09-27 20:54:11'),
(19,3,6,'[10,11,12]','{\"10\":{\"given\":1,\"earned\":1,\"correct\":true},\"11\":{\"given\":1,\"earned\":1,\"correct\":true},\"12\":{\"given\":\"normalisation\",\"earned\":1,\"correct\":true}}',3.00,3.00,100.00,1,'2026-09-14 16:59:00','2026-09-14 17:07:00','2026-09-14 17:07:00','2026-09-27 20:54:11'),
(20,4,6,'[13,14,15]','{\"13\":{\"given\":1,\"earned\":1,\"correct\":true},\"14\":{\"given\":[0,1,3],\"earned\":1,\"correct\":true},\"15\":{\"given\":2,\"earned\":0,\"correct\":false}}',2.00,3.00,66.67,1,'2026-09-23 08:03:00','2026-09-23 08:11:00','2026-09-23 08:11:00','2026-09-27 20:54:11'),
(21,3,20,'[10,11,12]','{\"10\":{\"given\":2,\"earned\":0,\"correct\":false},\"11\":{\"given\":0,\"earned\":0,\"correct\":false},\"12\":{\"given\":\"normalisation\",\"earned\":1,\"correct\":true}}',1.00,3.00,33.33,0,'2026-09-17 13:52:00','2026-09-17 14:00:00','2026-09-17 14:00:00','2026-09-27 20:54:11'),
(22,3,28,'[10,11,12]','{\"10\":{\"given\":1,\"earned\":1,\"correct\":true},\"11\":{\"given\":1,\"earned\":1,\"correct\":true},\"12\":{\"given\":\"normalisation\",\"earned\":1,\"correct\":true}}',3.00,3.00,100.00,1,'2026-09-18 12:12:00','2026-09-18 12:20:00','2026-09-18 12:20:00','2026-09-27 20:54:11'),
(23,3,12,'[10,11,12]','{\"10\":{\"given\":2,\"earned\":0,\"correct\":false},\"11\":{\"given\":1,\"earned\":1,\"correct\":true},\"12\":{\"given\":\"normalisation\",\"earned\":1,\"correct\":true}}',2.00,3.00,66.67,1,'2026-09-13 14:54:00','2026-09-13 15:02:00','2026-09-13 15:02:00','2026-09-27 20:54:11'),
(24,4,12,'[13,14,15]','{\"13\":{\"given\":1,\"earned\":1,\"correct\":true},\"14\":{\"given\":[0,1,3],\"earned\":1,\"correct\":true},\"15\":{\"given\":1,\"earned\":1,\"correct\":true}}',3.00,3.00,100.00,1,'2026-09-22 09:35:00','2026-09-22 09:43:00','2026-09-22 09:43:00','2026-09-27 20:54:11'),
(25,3,21,'[10,11,12]','{\"10\":{\"given\":2,\"earned\":0,\"correct\":false},\"11\":{\"given\":1,\"earned\":1,\"correct\":true},\"12\":{\"given\":\"je ne sais pas\",\"earned\":0,\"correct\":false}}',1.00,3.00,33.33,0,'2026-09-13 12:03:00','2026-09-13 12:11:00','2026-09-13 12:11:00','2026-09-27 20:54:11'),
(26,3,21,'[10,11,12]','{\"10\":{\"given\":1,\"earned\":1,\"correct\":true},\"11\":{\"given\":1,\"earned\":1,\"correct\":true},\"12\":{\"given\":\"normalisation\",\"earned\":1,\"correct\":true}}',3.00,3.00,100.00,1,'2026-09-13 12:03:00','2026-09-13 12:11:00','2026-09-13 12:11:00','2026-09-27 20:54:11'),
(27,4,21,'[13,14,15]','{\"13\":{\"given\":1,\"earned\":1,\"correct\":true},\"14\":{\"given\":[0],\"earned\":0.33,\"correct\":false},\"15\":{\"given\":1,\"earned\":1,\"correct\":true}}',2.33,3.00,77.67,1,'2026-09-25 18:39:00','2026-09-25 18:47:00','2026-09-25 18:47:00','2026-09-27 20:54:11'),
(28,3,4,'[10,11,12]','{\"10\":{\"given\":1,\"earned\":1,\"correct\":true},\"11\":{\"given\":1,\"earned\":1,\"correct\":true},\"12\":{\"given\":\"normalisation\",\"earned\":1,\"correct\":true}}',3.00,3.00,100.00,1,'2026-09-26 17:51:00','2026-09-26 17:59:00','2026-09-26 17:59:00','2026-09-27 20:54:12'),
(29,3,19,'[10,11,12]','{\"10\":{\"given\":2,\"earned\":0,\"correct\":false},\"11\":{\"given\":1,\"earned\":1,\"correct\":true},\"12\":{\"given\":\"normalisation\",\"earned\":1,\"correct\":true}}',2.00,3.00,66.67,1,'2026-09-24 18:48:00','2026-09-24 18:56:00','2026-09-24 18:56:00','2026-09-27 20:54:12'),
(30,3,22,'[10,11,12]','{\"10\":{\"given\":2,\"earned\":0,\"correct\":false},\"11\":{\"given\":1,\"earned\":1,\"correct\":true},\"12\":{\"given\":\"normalisation\",\"earned\":1,\"correct\":true}}',2.00,3.00,66.67,1,'2026-09-11 14:24:00','2026-09-11 14:32:00','2026-09-11 14:32:00','2026-09-27 20:54:12'),
(31,4,22,'[13,14,15]','{\"13\":{\"given\":2,\"earned\":0,\"correct\":false},\"14\":{\"given\":[0,1,3],\"earned\":1,\"correct\":true},\"15\":{\"given\":1,\"earned\":1,\"correct\":true}}',2.00,3.00,66.67,1,'2026-09-26 18:30:00','2026-09-26 18:38:00','2026-09-26 18:38:00','2026-09-27 20:54:12'),
(32,3,23,'[10,11,12]','{\"10\":{\"given\":1,\"earned\":1,\"correct\":true},\"11\":{\"given\":1,\"earned\":1,\"correct\":true},\"12\":{\"given\":\"je ne sais pas\",\"earned\":0,\"correct\":false}}',2.00,3.00,66.67,1,'2026-09-18 09:30:00','2026-09-18 09:38:00','2026-09-18 09:38:00','2026-09-27 20:54:12'),
(33,3,26,'[10,11,12]','{\"10\":{\"given\":1,\"earned\":1,\"correct\":true},\"11\":{\"given\":1,\"earned\":1,\"correct\":true},\"12\":{\"given\":\"je ne sais pas\",\"earned\":0,\"correct\":false}}',2.00,3.00,66.67,1,'2026-09-20 19:46:00','2026-09-20 19:54:00','2026-09-20 19:54:00','2026-09-27 20:54:12'),
(34,3,15,'[10,11,12]','{\"10\":{\"given\":1,\"earned\":1,\"correct\":true},\"11\":{\"given\":1,\"earned\":1,\"correct\":true},\"12\":{\"given\":\"je ne sais pas\",\"earned\":0,\"correct\":false}}',2.00,3.00,66.67,1,'2026-09-18 10:36:00','2026-09-18 10:44:00','2026-09-18 10:44:00','2026-09-27 20:54:12'),
(35,5,17,'[16,17]','{\"16\":{\"given\":1,\"earned\":1,\"correct\":true},\"17\":{\"given\":1,\"earned\":1,\"correct\":true}}',2.00,2.00,100.00,1,'2026-09-19 18:52:00','2026-09-19 19:00:00','2026-09-19 19:00:00','2026-09-27 20:54:12'),
(36,5,16,'[16,17]','{\"16\":{\"given\":1,\"earned\":1,\"correct\":true},\"17\":{\"given\":0,\"earned\":0,\"correct\":false}}',1.00,2.00,50.00,0,'2026-09-24 14:45:00','2026-09-24 14:53:00','2026-09-24 14:53:00','2026-09-27 20:54:12'),
(37,5,27,'[16,17]','{\"16\":{\"given\":1,\"earned\":1,\"correct\":true},\"17\":{\"given\":0,\"earned\":0,\"correct\":false}}',1.00,2.00,50.00,0,'2026-09-22 12:43:00','2026-09-22 12:51:00','2026-09-22 12:51:00','2026-09-27 20:54:12'),
(38,5,6,'[16,17]','{\"16\":{\"given\":1,\"earned\":1,\"correct\":true},\"17\":{\"given\":1,\"earned\":1,\"correct\":true}}',2.00,2.00,100.00,1,'2026-09-20 15:01:00','2026-09-20 15:09:00','2026-09-20 15:09:00','2026-09-27 20:54:12'),
(39,5,10,'[16,17]','{\"16\":{\"given\":1,\"earned\":1,\"correct\":true},\"17\":{\"given\":1,\"earned\":1,\"correct\":true}}',2.00,2.00,100.00,1,'2026-09-22 14:04:00','2026-09-22 14:12:00','2026-09-22 14:12:00','2026-09-27 20:54:12'),
(40,5,28,'[16,17]','{\"16\":{\"given\":1,\"earned\":1,\"correct\":true},\"17\":{\"given\":1,\"earned\":1,\"correct\":true}}',2.00,2.00,100.00,1,'2026-09-24 16:29:00','2026-09-24 16:37:00','2026-09-24 16:37:00','2026-09-27 20:54:12'),
(41,5,23,'[16,17]','{\"16\":{\"given\":2,\"earned\":0,\"correct\":false},\"17\":{\"given\":1,\"earned\":1,\"correct\":true}}',1.00,2.00,50.00,0,'2026-09-19 06:01:00','2026-09-19 06:09:00','2026-09-19 06:09:00','2026-09-27 20:54:12'),
(42,5,11,'[16,17]','{\"16\":{\"given\":2,\"earned\":0,\"correct\":false},\"17\":{\"given\":1,\"earned\":1,\"correct\":true}}',1.00,2.00,50.00,0,'2026-09-22 11:12:00','2026-09-22 11:20:00','2026-09-22 11:20:00','2026-09-27 20:54:12'),
(43,5,11,'[16,17]','{\"16\":{\"given\":1,\"earned\":1,\"correct\":true},\"17\":{\"given\":1,\"earned\":1,\"correct\":true}}',2.00,2.00,100.00,1,'2026-09-22 11:12:00','2026-09-22 11:20:00','2026-09-22 11:20:00','2026-09-27 20:54:12'),
(44,5,13,'[16,17]','{\"16\":{\"given\":1,\"earned\":1,\"correct\":true},\"17\":{\"given\":0,\"earned\":0,\"correct\":false}}',1.00,2.00,50.00,0,'2026-09-17 19:14:00','2026-09-17 19:22:00','2026-09-17 19:22:00','2026-09-27 20:54:12'),
(45,5,19,'[16,17]','{\"16\":{\"given\":2,\"earned\":0,\"correct\":false},\"17\":{\"given\":0,\"earned\":0,\"correct\":false}}',0.00,2.00,0.00,0,'2026-09-18 08:10:00','2026-09-18 08:18:00','2026-09-18 08:18:00','2026-09-27 20:54:12');
/*!40000 ALTER TABLE `quiz_attempts` ENABLE KEYS */;

--
-- Table structure for table `quizzes`
--

DROP TABLE IF EXISTS `quizzes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `quizzes` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `course_id` bigint(20) unsigned NOT NULL,
  `section_id` bigint(20) unsigned DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `time_limit_minutes` smallint(5) unsigned DEFAULT NULL,
  `max_attempts` tinyint(3) unsigned DEFAULT NULL,
  `pass_score` tinyint(3) unsigned NOT NULL DEFAULT 50,
  `shuffle_questions` tinyint(1) NOT NULL DEFAULT 0,
  `show_answers` tinyint(1) NOT NULL DEFAULT 1,
  `due_at` timestamp NULL DEFAULT NULL,
  `position` int(10) unsigned NOT NULL DEFAULT 0,
  `is_published` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `quizzes_course_id_foreign` (`course_id`),
  KEY `quizzes_section_id_foreign` (`section_id`),
  CONSTRAINT `quizzes_course_id_foreign` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE,
  CONSTRAINT `quizzes_section_id_foreign` FOREIGN KEY (`section_id`) REFERENCES `sections` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `quizzes`
--

/*!40000 ALTER TABLE `quizzes` DISABLE KEYS */;
INSERT INTO `quizzes` VALUES
(1,1,1,'Quiz — Les bases','Vérifiez vos acquis. Correction détaillée après soumission.',NULL,NULL,60,0,1,'2026-09-30 20:54:11',4,1,'2026-09-27 20:54:11','2026-09-27 20:54:11'),
(2,1,2,'Quiz — pandas','Vérifiez vos acquis. Correction détaillée après soumission.',NULL,NULL,60,0,1,'2026-10-07 20:54:11',7,1,'2026-09-27 20:54:11','2026-09-27 20:54:11'),
(3,2,4,'Quiz — Modélisation','Vérifiez vos acquis. Correction détaillée après soumission.',NULL,NULL,60,0,1,NULL,13,1,'2026-09-27 20:54:11','2026-09-27 20:54:11'),
(4,2,5,'Quiz — Requêtes','Vérifiez vos acquis. Correction détaillée après soumission.',10,3,60,0,1,NULL,16,1,'2026-09-27 20:54:11','2026-09-27 20:54:11'),
(5,3,6,'Quiz — Fondamentaux Laravel','Vérifiez vos acquis. Correction détaillée après soumission.',NULL,NULL,60,0,1,NULL,20,1,'2026-09-27 20:54:11','2026-09-27 20:54:11');
/*!40000 ALTER TABLE `quizzes` ENABLE KEYS */;

--
-- Table structure for table `replies`
--

DROP TABLE IF EXISTS `replies`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `replies` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `discussion_id` bigint(20) unsigned NOT NULL,
  `user_id` bigint(20) unsigned NOT NULL,
  `body` text NOT NULL,
  `is_solution` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `replies_discussion_id_foreign` (`discussion_id`),
  KEY `replies_user_id_foreign` (`user_id`),
  CONSTRAINT `replies_discussion_id_foreign` FOREIGN KEY (`discussion_id`) REFERENCES `discussions` (`id`) ON DELETE CASCADE,
  CONSTRAINT `replies_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `replies`
--

/*!40000 ALTER TABLE `replies` DISABLE KEYS */;
INSERT INTO `replies` VALUES
(1,1,2,'Un tuple est immuable : utilisez-le pour des données qui ne doivent pas changer (coordonnées, clés de dictionnaire). Sinon, une liste.',1,'2026-09-27 20:54:12','2026-09-27 20:54:12'),
(2,1,8,'Merci, c\'est beaucoup plus clair !',0,'2026-09-27 20:54:12','2026-09-27 20:54:12'),
(3,2,2,'Vérifiez la casse avec `df.columns` : la colonne s\'appelle sans doute `montant` en minuscules.',1,'2026-09-27 20:54:12','2026-09-27 20:54:12'),
(4,2,9,'Merci, c\'est beaucoup plus clair !',0,'2026-09-27 20:54:12','2026-09-27 20:54:12');
/*!40000 ALTER TABLE `replies` ENABLE KEYS */;

--
-- Table structure for table `sections`
--

DROP TABLE IF EXISTS `sections`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `sections` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `course_id` bigint(20) unsigned NOT NULL,
  `title` varchar(255) NOT NULL,
  `summary` text DEFAULT NULL,
  `position` int(10) unsigned NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `sections_course_id_foreign` (`course_id`),
  CONSTRAINT `sections_course_id_foreign` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sections`
--

/*!40000 ALTER TABLE `sections` DISABLE KEYS */;
INSERT INTO `sections` VALUES
(1,1,'Les bases de Python',NULL,1,'2026-09-27 20:54:11','2026-09-27 20:54:11'),
(2,1,'Manipuler des données avec pandas',NULL,2,'2026-09-27 20:54:11','2026-09-27 20:54:11'),
(3,1,'Visualisation',NULL,3,'2026-09-27 20:54:11','2026-09-27 20:54:11'),
(4,2,'Modélisation',NULL,1,'2026-09-27 20:54:11','2026-09-27 20:54:11'),
(5,2,'Requêtes SQL',NULL,2,'2026-09-27 20:54:11','2026-09-27 20:54:11'),
(6,3,'Fondamentaux',NULL,1,'2026-09-27 20:54:11','2026-09-27 20:54:11'),
(7,3,'Sécurité',NULL,2,'2026-09-27 20:54:11','2026-09-27 20:54:11'),
(8,4,'Introduction',NULL,1,'2026-09-27 20:54:11','2026-09-27 20:54:11');
/*!40000 ALTER TABLE `sections` ENABLE KEYS */;

--
-- Table structure for table `sessions`
--

DROP TABLE IF EXISTS `sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sessions`
--

/*!40000 ALTER TABLE `sessions` DISABLE KEYS */;
/*!40000 ALTER TABLE `sessions` ENABLE KEYS */;

--
-- Table structure for table `submissions`
--

DROP TABLE IF EXISTS `submissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `submissions` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `assignment_id` bigint(20) unsigned NOT NULL,
  `user_id` bigint(20) unsigned NOT NULL,
  `content` longtext DEFAULT NULL,
  `file_path` varchar(255) DEFAULT NULL,
  `file_name` varchar(255) DEFAULT NULL,
  `submitted_at` timestamp NOT NULL,
  `is_late` tinyint(1) NOT NULL DEFAULT 0,
  `grade` decimal(6,2) DEFAULT NULL,
  `feedback` text DEFAULT NULL,
  `graded_by` bigint(20) unsigned DEFAULT NULL,
  `graded_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `submissions_assignment_id_user_id_unique` (`assignment_id`,`user_id`),
  KEY `submissions_user_id_foreign` (`user_id`),
  KEY `submissions_graded_by_foreign` (`graded_by`),
  CONSTRAINT `submissions_assignment_id_foreign` FOREIGN KEY (`assignment_id`) REFERENCES `assignments` (`id`) ON DELETE CASCADE,
  CONSTRAINT `submissions_graded_by_foreign` FOREIGN KEY (`graded_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `submissions_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `submissions`
--

/*!40000 ALTER TABLE `submissions` DISABLE KEYS */;
INSERT INTO `submissions` VALUES
(1,1,15,'Voici mon rendu pour « Mini-projet : analyse des ventes ».\n\n- Démarche suivie\n- Résultats obtenus\n- Limites identifiées',NULL,NULL,'2026-09-23 08:02:00',0,16.00,'Bon travail, analyse claire.',2,'2026-09-25 08:02:00','2026-09-27 20:54:11','2026-09-27 20:54:11'),
(2,1,17,'Voici mon rendu pour « Mini-projet : analyse des ventes ».\n\n- Démarche suivie\n- Résultats obtenus\n- Limites identifiées',NULL,NULL,'2026-09-24 18:03:00',0,17.00,'Les graphiques manquent de titres.',2,'2026-09-26 18:03:00','2026-09-27 20:54:11','2026-09-27 20:54:11'),
(3,1,28,'Voici mon rendu pour « Mini-projet : analyse des ventes ».\n\n- Démarche suivie\n- Résultats obtenus\n- Limites identifiées',NULL,NULL,'2026-09-19 19:39:00',0,18.50,'Les graphiques manquent de titres.',2,'2026-09-21 19:39:00','2026-09-27 20:54:11','2026-09-27 20:54:11'),
(4,2,28,'Voici mon rendu pour « Tableau de bord final ».\n\n- Démarche suivie\n- Résultats obtenus\n- Limites identifiées',NULL,NULL,'2026-09-25 07:01:00',0,NULL,NULL,NULL,NULL,'2026-09-27 20:54:11','2026-09-27 20:54:11'),
(5,1,8,'Voici mon rendu pour « Mini-projet : analyse des ventes ».\n\n- Démarche suivie\n- Résultats obtenus\n- Limites identifiées',NULL,NULL,'2026-09-21 10:58:00',0,NULL,NULL,NULL,NULL,'2026-09-27 20:54:11','2026-09-27 20:54:11'),
(6,2,8,'Voici mon rendu pour « Tableau de bord final ».\n\n- Démarche suivie\n- Résultats obtenus\n- Limites identifiées',NULL,NULL,'2026-09-26 09:17:00',0,NULL,NULL,NULL,NULL,'2026-09-27 20:54:11','2026-09-27 20:54:11'),
(7,3,6,'Voici mon rendu pour « Étude de cas : base de données d\'une école ».\n\n- Démarche suivie\n- Résultats obtenus\n- Limites identifiées',NULL,NULL,'2026-09-26 12:33:00',0,NULL,NULL,NULL,NULL,'2026-09-27 20:54:11','2026-09-27 20:54:11'),
(8,3,12,'Voici mon rendu pour « Étude de cas : base de données d\'une école ».\n\n- Démarche suivie\n- Résultats obtenus\n- Limites identifiées',NULL,NULL,'2026-09-26 06:56:00',0,NULL,NULL,NULL,NULL,'2026-09-27 20:54:11','2026-09-27 20:54:11');
/*!40000 ALTER TABLE `submissions` ENABLE KEYS */;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `role` varchar(20) NOT NULL DEFAULT 'student',
  `bio` text DEFAULT NULL,
  `xp` int(10) unsigned NOT NULL DEFAULT 0,
  `last_seen_at` timestamp NULL DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`),
  KEY `users_role_index` (`role`)
) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES
(1,'Admin EduSphere','admin@edusphere.test','2026-09-27 20:54:11','$2y$12$QTa3b1E6Kk2wtm4PJ3Nsd.THECrhiiAccbCcDYPP8b7/2mB8z8TB.','admin',NULL,0,NULL,'5YBlb1b4wP','2026-09-27 20:54:11','2026-09-27 20:54:11'),
(2,'Awa Mensah','prof@edusphere.test','2026-09-27 20:54:11','$2y$12$QTa3b1E6Kk2wtm4PJ3Nsd.THECrhiiAccbCcDYPP8b7/2mB8z8TB.','teacher','Data engineer et formatrice Python / SQL.',0,NULL,'4ZLDWNzpKd','2026-09-27 20:54:11','2026-09-27 20:54:11'),
(3,'Julien Martin','prof2@edusphere.test','2026-09-27 20:54:11','$2y$12$QTa3b1E6Kk2wtm4PJ3Nsd.THECrhiiAccbCcDYPP8b7/2mB8z8TB.','teacher',NULL,0,NULL,'Bw81qCRp9G','2026-09-27 20:54:11','2026-09-27 20:54:11'),
(4,'Kofi Agbeko','etudiant@edusphere.test','2026-09-27 20:54:11','$2y$12$QTa3b1E6Kk2wtm4PJ3Nsd.THECrhiiAccbCcDYPP8b7/2mB8z8TB.','student',NULL,187,NULL,'2ikG1MvirR','2026-09-27 20:54:11','2026-09-27 20:54:12'),
(5,'Claudine Noel','denis.menard@example.org','2026-09-27 20:54:11','$2y$12$QTa3b1E6Kk2wtm4PJ3Nsd.THECrhiiAccbCcDYPP8b7/2mB8z8TB.','student',NULL,0,NULL,'omjVyOrOVY','2026-09-27 20:54:11','2026-09-27 20:54:11'),
(6,'Éric Martinez','hugues.remy@example.net','2026-09-27 20:54:11','$2y$12$QTa3b1E6Kk2wtm4PJ3Nsd.THECrhiiAccbCcDYPP8b7/2mB8z8TB.','student',NULL,316,NULL,'nmbbEBpUPj','2026-09-27 20:54:11','2026-09-27 20:54:12'),
(7,'Théophile du Julien','moreno.monique@example.com','2026-09-27 20:54:11','$2y$12$QTa3b1E6Kk2wtm4PJ3Nsd.THECrhiiAccbCcDYPP8b7/2mB8z8TB.','student',NULL,19,NULL,'Vi10iG29cK','2026-09-27 20:54:11','2026-09-27 20:54:11'),
(8,'Charles Mercier','ndurand@example.org','2026-09-27 20:54:11','$2y$12$QTa3b1E6Kk2wtm4PJ3Nsd.THECrhiiAccbCcDYPP8b7/2mB8z8TB.','student',NULL,280,NULL,'oWs8LGzA2c','2026-09-27 20:54:11','2026-09-27 20:54:12'),
(9,'Luc Richard','normand.alexandria@example.net','2026-09-27 20:54:11','$2y$12$QTa3b1E6Kk2wtm4PJ3Nsd.THECrhiiAccbCcDYPP8b7/2mB8z8TB.','student',NULL,142,NULL,'qQq33HcaE1','2026-09-27 20:54:11','2026-09-27 20:54:12'),
(10,'Anastasie Daniel','smoulin@example.com','2026-09-27 20:54:11','$2y$12$QTa3b1E6Kk2wtm4PJ3Nsd.THECrhiiAccbCcDYPP8b7/2mB8z8TB.','student',NULL,127,NULL,'wEjpUHs62H','2026-09-27 20:54:11','2026-09-27 20:54:12'),
(11,'Alex Thibault-Garnier','xavier68@example.net','2026-09-27 20:54:11','$2y$12$QTa3b1E6Kk2wtm4PJ3Nsd.THECrhiiAccbCcDYPP8b7/2mB8z8TB.','student',NULL,163,NULL,'TxZAMQ1sGb','2026-09-27 20:54:11','2026-09-27 20:54:12'),
(12,'Émile Peltier','edavid@example.net','2026-09-27 20:54:11','$2y$12$QTa3b1E6Kk2wtm4PJ3Nsd.THECrhiiAccbCcDYPP8b7/2mB8z8TB.','student',NULL,256,NULL,'qAz5sJ4Kxd','2026-09-27 20:54:11','2026-09-27 20:54:11'),
(13,'Jacques Perret','eugene43@example.net','2026-09-27 20:54:11','$2y$12$QTa3b1E6Kk2wtm4PJ3Nsd.THECrhiiAccbCcDYPP8b7/2mB8z8TB.','student',NULL,118,NULL,'FeAinRFVIt','2026-09-27 20:54:11','2026-09-27 20:54:12'),
(14,'Geneviève Boucher','rlombard@example.net','2026-09-27 20:54:11','$2y$12$QTa3b1E6Kk2wtm4PJ3Nsd.THECrhiiAccbCcDYPP8b7/2mB8z8TB.','student',NULL,24,NULL,'DLqElcob0Q','2026-09-27 20:54:11','2026-09-27 20:54:12'),
(15,'Émile-Claude De Oliveira','faure.frederique@example.org','2026-09-27 20:54:11','$2y$12$QTa3b1E6Kk2wtm4PJ3Nsd.THECrhiiAccbCcDYPP8b7/2mB8z8TB.','student',NULL,282,NULL,'ub5E7c9DWs','2026-09-27 20:54:11','2026-09-27 20:54:12'),
(16,'Gabriel de la Caron','alice.lemaitre@example.com','2026-09-27 20:54:11','$2y$12$QTa3b1E6Kk2wtm4PJ3Nsd.THECrhiiAccbCcDYPP8b7/2mB8z8TB.','student',NULL,90,NULL,'zIYUttPxfI','2026-09-27 20:54:11','2026-09-27 20:54:12'),
(17,'Thierry Marty','hrousseau@example.net','2026-09-27 20:54:11','$2y$12$QTa3b1E6Kk2wtm4PJ3Nsd.THECrhiiAccbCcDYPP8b7/2mB8z8TB.','student',NULL,252,NULL,'EXonUrYNbq','2026-09-27 20:54:11','2026-09-27 20:54:12'),
(18,'Éléonore Charpentier-Bertrand','chevallier.vincent@example.org','2026-09-27 20:54:11','$2y$12$QTa3b1E6Kk2wtm4PJ3Nsd.THECrhiiAccbCcDYPP8b7/2mB8z8TB.','student',NULL,120,NULL,'Z8WCEuciHj','2026-09-27 20:54:11','2026-09-27 20:54:11'),
(19,'François Teixeira-Andre','guilbert.noel@example.com','2026-09-27 20:54:11','$2y$12$QTa3b1E6Kk2wtm4PJ3Nsd.THECrhiiAccbCcDYPP8b7/2mB8z8TB.','student',NULL,210,NULL,'UkTblz56eQ','2026-09-27 20:54:11','2026-09-27 20:54:12'),
(20,'Gérard Weiss-Techer','lemoine.emile@example.net','2026-09-27 20:54:11','$2y$12$QTa3b1E6Kk2wtm4PJ3Nsd.THECrhiiAccbCcDYPP8b7/2mB8z8TB.','student',NULL,178,NULL,'cMtrd3uQQL','2026-09-27 20:54:11','2026-09-27 20:54:11'),
(21,'René Nguyen','tristan21@example.com','2026-09-27 20:54:11','$2y$12$QTa3b1E6Kk2wtm4PJ3Nsd.THECrhiiAccbCcDYPP8b7/2mB8z8TB.','student',NULL,114,NULL,'T9CeCsrRDV','2026-09-27 20:54:11','2026-09-27 20:54:11'),
(22,'Marthe Leclerc','nrossi@example.com','2026-09-27 20:54:11','$2y$12$QTa3b1E6Kk2wtm4PJ3Nsd.THECrhiiAccbCcDYPP8b7/2mB8z8TB.','student',NULL,96,NULL,'uiw7KH3e6S','2026-09-27 20:54:11','2026-09-27 20:54:12'),
(23,'Anaïs Schmitt','xpicard@example.com','2026-09-27 20:54:11','$2y$12$QTa3b1E6Kk2wtm4PJ3Nsd.THECrhiiAccbCcDYPP8b7/2mB8z8TB.','student',NULL,194,NULL,'CVNdfuKxJI','2026-09-27 20:54:11','2026-09-27 20:54:12'),
(24,'Gabriel Reynaud','emmanuelle36@example.com','2026-09-27 20:54:11','$2y$12$QTa3b1E6Kk2wtm4PJ3Nsd.THECrhiiAccbCcDYPP8b7/2mB8z8TB.','student',NULL,28,NULL,'6Al1vgn4fZ','2026-09-27 20:54:11','2026-09-27 20:54:11'),
(25,'Laure Fouquet','laurence23@example.net','2026-09-27 20:54:11','$2y$12$QTa3b1E6Kk2wtm4PJ3Nsd.THECrhiiAccbCcDYPP8b7/2mB8z8TB.','student',NULL,72,NULL,'at1MznM7AB','2026-09-27 20:54:11','2026-09-27 20:54:11'),
(26,'Émile Leblanc','morin.william@example.net','2026-09-27 20:54:11','$2y$12$QTa3b1E6Kk2wtm4PJ3Nsd.THECrhiiAccbCcDYPP8b7/2mB8z8TB.','student',NULL,181,NULL,'vgm0TOCoIJ','2026-09-27 20:54:11','2026-09-27 20:54:12'),
(27,'Thibault du Pierre','michelle58@example.org','2026-09-27 20:54:11','$2y$12$QTa3b1E6Kk2wtm4PJ3Nsd.THECrhiiAccbCcDYPP8b7/2mB8z8TB.','student',NULL,69,NULL,'BFyyyBYcog','2026-09-27 20:54:11','2026-09-27 20:54:12'),
(28,'Margaret Deschamps','ogosselin@example.com','2026-09-27 20:54:11','$2y$12$QTa3b1E6Kk2wtm4PJ3Nsd.THECrhiiAccbCcDYPP8b7/2mB8z8TB.','student',NULL,369,NULL,'BbfZ0H3ULk','2026-09-27 20:54:11','2026-09-27 20:54:12');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed
