DROP TABLE IF EXISTS `bid`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `bid` (
  `id` bigint(20) NOT NULL,
  `template` text NOT NULL,
  `filial` bigint(20) NOT NULL,
  `priority` bigint(20) NOT NULL,
  `theme` varchar(100) NOT NULL,
  `status` bigint(20) NOT NULL,
  `owner` bigint(20) NOT NULL,
  `contractor` bigint(20) NOT NULL,
  `date` bigint(20) NOT NULL,
  `message` text NOT NULL,
  `alarm_create` bigint(20) DEFAULT NULL,
  `alarm_update` bigint(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `bid`
--

LOCK TABLES `bid` WRITE;
/*!40000 ALTER TABLE `bid` DISABLE KEYS */;
INSERT INTO `bid` VALUES (5,'1',1,4,'Программа для обработки изображений',4,1,2,1714218839,'<div>Прошу установить программу для обработки изображений.&nbsp;</div>',1,1);
/*!40000 ALTER TABLE `bid` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `bid_files`
--

DROP TABLE IF EXISTS `bid_files`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `bid_files` (
  `id` bigint(20) NOT NULL,
  `id_bid` bigint(20) NOT NULL,
  `date` bigint(20) NOT NULL,
  `message` bigint(20) NOT NULL,
  `logo` bigint(20) NOT NULL,
  `type` varchar(100) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` varchar(500) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `bid_files`
--

LOCK TABLES `bid_files` WRITE;
/*!40000 ALTER TABLE `bid_files` DISABLE KEYS */;
/*!40000 ALTER TABLE `bid_files` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `bid_filials`
--

DROP TABLE IF EXISTS `bid_filials`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `bid_filials` (
  `id` bigint(20) NOT NULL,
  `name` varchar(100) NOT NULL,
  `active` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `bid_filials`
--

LOCK TABLES `bid_filials` WRITE;
/*!40000 ALTER TABLE `bid_filials` DISABLE KEYS */;
INSERT INTO `bid_filials` VALUES (1,'Москва',1),(2,'Нижний Новгород',1),(4,'Владивосток',1),(3,'Воронеж',1),(5,'Омск',1),(0,'***',0);
/*!40000 ALTER TABLE `bid_filials` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `bid_history`
--

DROP TABLE IF EXISTS `bid_history`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `bid_history` (
  `id` bigint(20) NOT NULL,
  `id_bid` bigint(20) NOT NULL,
  `status` bigint(20) NOT NULL,
  `date` bigint(20) NOT NULL,
  `user` varchar(500) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `bid_history`
--

LOCK TABLES `bid_history` WRITE;
/*!40000 ALTER TABLE `bid_history` DISABLE KEYS */;
/*!40000 ALTER TABLE `bid_history` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `bid_messages`
--

DROP TABLE IF EXISTS `bid_messages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `bid_messages` (
  `id` bigint(20) NOT NULL,
  `date` bigint(20) NOT NULL,
  `bid_number` bigint(20) NOT NULL,
  `user` text NOT NULL,
  `file` bigint(20) NOT NULL,
  `message` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `bid_messages`
--

LOCK TABLES `bid_messages` WRITE;
/*!40000 ALTER TABLE `bid_messages` DISABLE KEYS */;
/*!40000 ALTER TABLE `bid_messages` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `bid_priority`
--

DROP TABLE IF EXISTS `bid_priority`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `bid_priority` (
  `id` bigint(20) NOT NULL,
  `name` varchar(100) NOT NULL,
  `active` bigint(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `bid_priority`
--

LOCK TABLES `bid_priority` WRITE;
/*!40000 ALTER TABLE `bid_priority` DISABLE KEYS */;
INSERT INTO `bid_priority` VALUES (0,'***',0),(1,'Чрезвычайный',1),(2,'Высокий',1),(3,'Средний',1),(4,'Низкий',1);
/*!40000 ALTER TABLE `bid_priority` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `bid_status`
--

DROP TABLE IF EXISTS `bid_status`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `bid_status` (
  `id` bigint(20) NOT NULL,
  `status` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `bid_status`
--

LOCK TABLES `bid_status` WRITE;
/*!40000 ALTER TABLE `bid_status` DISABLE KEYS */;
INSERT INTO `bid_status` VALUES (1,'Не назначена'),(2,'Назначена'),(3,'В работе'),(4,'Ожидает подтверждения'),(5,'Закрыта'),(6,'Запрос информации'),(7,'Приостановлена'),(8,'На согласовании'),(9,'В закупке'),(10,'Возвращена');
/*!40000 ALTER TABLE `bid_status` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `bid_system`
--

DROP TABLE IF EXISTS `bid_system`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `bid_system` (
  `id` bigint(20) NOT NULL,
  `status_int` bigint(20) DEFAULT NULL,
  `status_text` varchar(100) DEFAULT NULL,
  `description` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `bid_system`
--

LOCK TABLES `bid_system` WRITE;
/*!40000 ALTER TABLE `bid_system` DISABLE KEYS */;
INSERT INTO `bid_system` VALUES (1,0,NULL,'Режим обслуживания'),(2,NULL,'192.168.18.128','LDAP server'),(3,NULL,'389','LDAP port'),(4,NULL,'test.local','LDAP domain'),(5,NULL,'DC=test,DC=local','LDAP dn'),(6,NULL,'helpdesk','LDAP group member'),(7,0,NULL,'LDAP enable or disable'),(8,NULL,'smtp.test.local','SMTP address'),(9,NULL,'587','SMTP port'),(10,1,NULL,'SMTP ssl'),(11,NULL,'mylogin','SMTP login'),(12,NULL,'mypassword','SMTP password'),(13,NULL,'no_replay@test.local','SMTP from em-mail'),(15,NULL,'localhost','Domain name Helpdesk'),(14,1,NULL,'SMTP enable or disable'),(16,72,NULL,'bids done to close until time H');
/*!40000 ALTER TABLE `bid_system` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `bid_tempates`
--

DROP TABLE IF EXISTS `bid_tempates`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `bid_tempates` (
  `id` bigint(20) NOT NULL,
  `name` text NOT NULL,
  `active` bigint(20) NOT NULL,
  `route` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `bid_tempates`
--

LOCK TABLES `bid_tempates` WRITE;
/*!40000 ALTER TABLE `bid_tempates` DISABLE KEYS */;
INSERT INTO `bid_tempates` VALUES (1,'Программное обеспечение',1,'1'),(2,'Сетевые доступы',1,'1'),(3,'Выход нового сотрудника',1,'1'),(4,'Увольнение сотрудника',1,'1'),(5,'Виртуальная среда',1,'1'),(6,'Другая проблема',1,'1'),(7,'Склад',1,'1'),(8,'Бухгалтерия',1,'1'),(0,'Восстановление доступа к порталу',0,'1');
/*!40000 ALTER TABLE `bid_tempates` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` bigint(20) NOT NULL,
  `id_user` text DEFAULT NULL,
  `active` bigint(20) NOT NULL,
  `login` text NOT NULL,
  `password` text NOT NULL,
  `local` bigint(20) NOT NULL,
  `domain` bigint(20) NOT NULL,
  `role` text NOT NULL,
  `unit` varchar(200) NOT NULL,
  `position` varchar(200) NOT NULL,
  `f_name` text NOT NULL,
  `s_name` text NOT NULL,
  `phone` text DEFAULT NULL,
  `email` text DEFAULT NULL,
  `telegram_id` text DEFAULT NULL,
  `last_action_1` text DEFAULT NULL,
  `last_action_2` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'1bdc1e815b38f43978b4',1,'admin','c3284d0f94606de1fd2af172aba15bf3',1,0,'superadmin','ИТ','Системный администратор','Дмитрий','Новиков','+7888888888','novikov@test.local',NULL,NULL,NULL),(0,'',0,'.system.','5a2bff4d3ffd8fe131cfb1a1517eb964',1,0,'user','','','Система','','','','***','*','*');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;
