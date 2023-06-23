SET foreign_key_checks = 0;
SET @@session.time_zone = 'Europe/Berlin';

DROP TABLE IF EXISTS `_tests`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `_tests` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `cod` varchar(50) NOT NULL DEFAULT '',
  `title` varchar(250) NOT NULL DEFAULT '',
  `description` varchar(500) NOT NULL DEFAULT '',
  `result` int(10) unsigned NOT NULL DEFAULT 1,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
INSERT INTO `_tests` VALUES (1,'TEST001','PRIMO TEST','RIGA DA VERIFICARE AL PRIMO TEST DB',1,'2023-06-22 19:54:57',NULL);
DROP TABLE IF EXISTS `activities`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `activities` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(250) NOT NULL DEFAULT '',
  `namecod` varchar(250) NOT NULL DEFAULT '',
  `description` text DEFAULT '',
  `piva` varchar(250) NOT NULL DEFAULT '',
  `born` date DEFAULT NULL,
  `tpactivity` int(10) unsigned NOT NULL DEFAULT 0,
  `tpcat` int(10) unsigned NOT NULL DEFAULT 0,
  `parent_id` int(10) unsigned DEFAULT 0,
  `lft` int(10) unsigned DEFAULT 0,
  `rght` int(10) unsigned DEFAULT 0,
  `flgtest` int(10) unsigned DEFAULT 0,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_ACTIVITY_TPACTIVITY_TPACTIVITIES` (`tpactivity`),
  KEY `fk_ACTIVITY_TPCAT_TPCATS` (`tpcat`),
  CONSTRAINT `fk_ACTIVITY_TPACTIVITY_TPACTIVITIES` FOREIGN KEY (`tpactivity`) REFERENCES `tpactivities` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_ACTIVITY_TPCAT_TPCATS` FOREIGN KEY (`tpcat`) REFERENCES `tpcats` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
INSERT INTO `activities` VALUES (1,'Prima activity','Prima Srl','','12345678901','1980-01-01',1,1,0,0,0,1,'2023-06-22 19:56:52',NULL),(2,'Seconda activity','Seconda Srl','','12345678902','1980-01-01',1,1,0,0,0,1,'2023-06-22 19:56:52',NULL),(3,'Dandy Corporation','DANDY CORPORATION SA','','CH-550.1.142.990-3','2016-04-15',2,10,0,0,0,0,'2023-06-22 19:56:53',NULL),(4,'Engineering Ingegneria Informatica','Engineering Ingegneria Informatica S.p.A','','05724831002','1980-01-01',2,10,0,0,0,0,'2023-06-22 19:58:50',NULL),(5,'Engiweb Security','Engiweb Security S.r.l.','','07962091000','1980-01-01',2,10,4,0,0,0,'2023-06-22 19:58:50',NULL),(6,'INAIL','INAIL - ISTITUTO NAZIONALE PER L\'ASSICURAZIONE CONTRO GLI INFORTUNI SUL LAVORO','','00968951004','1883-01-01',1,1,0,0,0,0,'2023-06-22 19:58:50',NULL),(7,'Università La Sapienza di Roma','Università La Sapienza di Roma','','02133771002','1303-01-01',1,5,0,0,0,0,'2023-06-22 19:58:50',NULL);
DROP TABLE IF EXISTS `activityaddresses`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `activityaddresses` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `cod` varchar(50) NOT NULL DEFAULT '',
  `flgprincipal` int(10) unsigned NOT NULL DEFAULT 0,
  `address` int(10) unsigned NOT NULL DEFAULT 0,
  `tpaddress` int(10) unsigned NOT NULL DEFAULT 0,
  `activity` int(10) unsigned NOT NULL DEFAULT 0,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_ACTIVITYADDRESS_ADDRESS_ADDRESSES` (`address`),
  KEY `fk_ACTIVITYADDRESS_TPADDRESS_TPADDRESSES` (`tpaddress`),
  KEY `fk_ACTIVITYADDRESS_ACTIVITY_ACTIVITYS` (`activity`),
  CONSTRAINT `fk_ACTIVITYADDRESS_ACTIVITY_ACTIVITYS` FOREIGN KEY (`activity`) REFERENCES `activitys` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_ACTIVITYADDRESS_ADDRESS_ADDRESSES` FOREIGN KEY (`address`) REFERENCES `addresses` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_ACTIVITYADDRESS_TPADDRESS_TPADDRESSES` FOREIGN KEY (`tpaddress`) REFERENCES `tpaddresses` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
INSERT INTO `activityaddresses` VALUES (1,'ACADD1',1,1,0,1,'2023-06-22 19:56:52',NULL),(2,'DANDYCORP_LEGAL_1',1,3,0,3,'2023-06-22 19:56:53',NULL);
DROP TABLE IF EXISTS `activityattachments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `activityattachments` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `cod` varchar(50) NOT NULL DEFAULT '',
  `flgprincipal` int(10) unsigned NOT NULL DEFAULT 0,
  `attachment` int(10) unsigned NOT NULL DEFAULT 0,
  `tpattachment` int(10) unsigned NOT NULL DEFAULT 0,
  `activity` int(10) unsigned NOT NULL DEFAULT 0,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_ACTIVITYATTACHMENT_ATTACHMENT_ATTACHMENTS` (`attachment`),
  KEY `fk_ACTIVITYATTACHMENT_ACTIVITY_ACTIVITIES` (`activity`),
  KEY `fk_ACTIVITYATTACHMENT_TPATTACHMENT_TPATTACHMENTS` (`tpattachment`),
  CONSTRAINT `fk_ACTIVITYATTACHMENT_ACTIVITY_ACTIVITIES` FOREIGN KEY (`activity`) REFERENCES `activitys` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_ACTIVITYATTACHMENT_ATTACHMENT_ATTACHMENTS` FOREIGN KEY (`attachment`) REFERENCES `attachments` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_ACTIVITYATTACHMENT_TPATTACHMENT_TPATTACHMENTS` FOREIGN KEY (`tpattachment`) REFERENCES `tpattachments` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
INSERT INTO `activityattachments` VALUES (1,'ACATT1',1,1,6,1,'2023-06-22 19:56:52',NULL),(2,'DANDYCORP_LOGO_IMAGE_1',1,3,6,3,'2023-06-22 19:56:53',NULL);
DROP TABLE IF EXISTS `activityprofiles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `activityprofiles` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `cod` varchar(250) NOT NULL DEFAULT '',
  `activity` int(10) unsigned NOT NULL DEFAULT 0,
  `profile` int(10) unsigned NOT NULL DEFAULT 0,
  `flgdefault` int(10) unsigned NOT NULL DEFAULT 0,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_ACTIVITYPROFILE_PROFILE_PROFILES` (`profile`),
  KEY `fk_ACTIVITYPROFILE_ACTIVITY_ACTIVITIES` (`activity`),
  CONSTRAINT `fk_ACTIVITYPROFILE_ACTIVITY_ACTIVITIES` FOREIGN KEY (`activity`) REFERENCES `activities` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_ACTIVITYPROFILE_PROFILE_PROFILES` FOREIGN KEY (`profile`) REFERENCES `profiles` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
INSERT INTO `activityprofiles` VALUES (1,'12345678901_EMPLOYER',1,3,0,'2023-06-22 19:56:53',NULL),(2,'12345678901_MANAGER',1,4,0,'2023-06-22 19:56:53',NULL),(4,'12345678902_EMPLOYER',2,3,0,'2023-06-22 19:56:53',NULL),(5,'12345678902_MANAGER',2,4,0,'2023-06-22 19:56:53',NULL),(7,'CH-550.1.142.990-3_EMPLOYER',3,3,0,'2023-06-22 19:56:53',NULL),(8,'CH-550.1.142.990-3_MANAGER',3,4,0,'2023-06-22 19:56:53',NULL);
DROP TABLE IF EXISTS `activityreferences`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `activityreferences` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `cod` varchar(50) NOT NULL DEFAULT '',
  `flgprincipal` int(10) unsigned NOT NULL DEFAULT 0,
  `contactreference` int(10) unsigned NOT NULL DEFAULT 0,
  `tpcontactreference` int(10) unsigned NOT NULL DEFAULT 0,
  `activity` int(10) unsigned NOT NULL DEFAULT 0,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_ACTIVITYREFERENCE_REFERENCE_CONTACTREFERENCES` (`contactreference`),
  KEY `fk_ACTIVITYREFERENCE_ACTIVITY_ACTIVITYS` (`activity`),
  KEY `fk_ACTIVITYREFERENCE_TPREFERENCE_TPCONTACTREFERENCES` (`tpcontactreference`),
  CONSTRAINT `fk_ACTIVITYREFERENCE_ACTIVITY_ACTIVITYS` FOREIGN KEY (`activity`) REFERENCES `activitys` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_ACTIVITYREFERENCE_REFERENCE_CONTACTREFERENCES` FOREIGN KEY (`contactreference`) REFERENCES `contactreferences` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_ACTIVITYREFERENCE_TPREFERENCE_TPCONTACTREFERENCES` FOREIGN KEY (`tpcontactreference`) REFERENCES `tpcontactreferences` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
INSERT INTO `activityreferences` VALUES (1,'ACCTR1',1,1,4,1,'2023-06-22 19:56:52',NULL),(2,'ACCTR2',1,2,4,2,'2023-06-22 19:56:52',NULL),(3,'ACCTR3',1,3,2,1,'2023-06-22 19:56:52',NULL),(4,'ACCTR4',1,4,2,2,'2023-06-22 19:56:52',NULL),(5,'DANDYCORP_EMAIL_1',1,9,4,3,'2023-06-22 19:56:53',NULL),(6,'DANDYCORP_CEL_1',1,10,2,3,'2023-06-22 19:56:53',NULL);
DROP TABLE IF EXISTS `activityrelationpermissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `activityrelationpermissions` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `cod` varchar(250) NOT NULL DEFAULT '',
  `activityrelation` int(10) unsigned NOT NULL DEFAULT 0,
  `permission` int(10) unsigned NOT NULL DEFAULT 0,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_ACTIVITYRELATIONPERMISSION_ACTIVITYRELATION_RELATIONS` (`activityrelation`),
  KEY `fk_ACTIVITYRELATIONPERMISSION_PERMISSION_PERMISSIONS` (`permission`),
  CONSTRAINT `fk_ACTIVITYRELATIONPERMISSION_ACTIVITYRELATION_RELATIONS` FOREIGN KEY (`activityrelation`) REFERENCES `activityrelations` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_ACTIVITYRELATIONPERMISSION_PERMISSION_PERMISSIONS` FOREIGN KEY (`permission`) REFERENCES `permissions` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `activityrelations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `activityrelations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `cod` varchar(250) NOT NULL DEFAULT '',
  `user` int(10) unsigned NOT NULL DEFAULT 0,
  `activity` int(10) unsigned NOT NULL DEFAULT 0,
  `tprelation` int(10) unsigned NOT NULL DEFAULT 0,
  `inforelationuser` varchar(250) NOT NULL DEFAULT '',
  `inforelationactivity` varchar(250) NOT NULL DEFAULT '',
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_ACTIVITYRELATION_USER_USERS` (`user`),
  KEY `fk_ACTIVITYRELATION_ACTIVITY_ACTIVITIES` (`activity`),
  KEY `fk_ACTIVITYRELATION_TPRELATION_TPRELATIONS` (`tprelation`),
  CONSTRAINT `fk_ACTIVITYRELATION_ACTIVITY_ACTIVITIES` FOREIGN KEY (`activity`) REFERENCES `activities` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_ACTIVITYRELATION_TPRELATION_TPRELATIONS` FOREIGN KEY (`tprelation`) REFERENCES `tpactivityrelations` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_ACTIVITYRELATION_USER_USERS` FOREIGN KEY (`user`) REFERENCES `users` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `activityusers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `activityusers` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `cod` varchar(50) NOT NULL DEFAULT '',
  `user` int(10) unsigned NOT NULL DEFAULT 0,
  `activity` int(10) unsigned NOT NULL DEFAULT 0,
  `role` int(10) unsigned NOT NULL DEFAULT 0,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_ACTIVITYUSER_USER_USERS` (`user`),
  KEY `fk_ACTIVITYUSER_ACTIVITY_ACTIVITIES` (`activity`),
  KEY `fk_ACTIVITYUSER_ROLE_WORKROLES` (`role`),
  CONSTRAINT `fk_ACTIVITYUSER_ACTIVITY_ACTIVITIES` FOREIGN KEY (`activity`) REFERENCES `activities` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_ACTIVITYUSER_ROLE_WORKROLES` FOREIGN KEY (`role`) REFERENCES `workroles` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_ACTIVITYUSER_USER_USERS` FOREIGN KEY (`user`) REFERENCES `users` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
INSERT INTO `activityusers` VALUES (1,'DANDYCORP_GIUSASSO00',3,3,1,'2023-06-22 19:58:43',NULL);
DROP TABLE IF EXISTS `addresses`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `addresses` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `cod` varchar(50) DEFAULT '',
  `street` varchar(100) DEFAULT '',
  `number` varchar(10) DEFAULT '',
  `zip` varchar(10) DEFAULT '',
  `city` varchar(50) DEFAULT '',
  `province` varchar(50) DEFAULT '',
  `region` varchar(50) DEFAULT '',
  `geo1` varchar(50) DEFAULT '',
  `geo2` varchar(50) DEFAULT '',
  `nation` int(10) unsigned NOT NULL DEFAULT 0,
  `cityid` int(10) unsigned NOT NULL DEFAULT 0,
  `tpaddress` int(10) unsigned NOT NULL DEFAULT 0,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_ADDRESS_NATION_NATIONS` (`nation`),
  KEY `fk_ADDRESS_CITYID_CITIES` (`cityid`),
  KEY `fk_ADDRESS_TP_ADDRESSES` (`tpaddress`),
  CONSTRAINT `fk_ADDRESS_CITYID_CITIES` FOREIGN KEY (`cityid`) REFERENCES `cities` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_ADDRESS_NATION_NATIONS` FOREIGN KEY (`nation`) REFERENCES `nations` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_ADDRESS_TP_ADDRESSES` FOREIGN KEY (`tpaddress`) REFERENCES `tpaddresses` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
INSERT INTO `addresses` VALUES (1,'PARLAMENT_IT','Piazza di Monte Citorio','1','00186','Roma','','','41.9004026','12.4742519',0,0,0,'2023-06-22 19:56:30',NULL),(2,'GIUSASSO00_HOME','Via della Lesca','8D','','','','','41.7882519','12.3681826',1,0,1,'2023-06-22 19:56:53',NULL),(3,'DANDYCORP_LEGAL','Via della Lesca','8D','','','','','41.7882519','12.3681826',1,0,1,'2023-06-22 19:56:53',NULL),(4,'CV_GIUSEPPE_SASSONE_INF_ADDRESS','Via della Lesca','8D','','','','','41.7882519','12.3681826',1,0,1,'2023-06-22 19:58:50',NULL);
DROP TABLE IF EXISTS `attachments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `attachments` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `url` varchar(100) DEFAULT '',
  `path` varchar(250) DEFAULT '',
  `name` varchar(100) DEFAULT '',
  `cid` varchar(100) DEFAULT '',
  `cod` varchar(50) DEFAULT '',
  `description` varchar(100) DEFAULT '',
  `size` varchar(10) DEFAULT '',
  `ext` varchar(10) DEFAULT '',
  `mimetype` varchar(150) DEFAULT '',
  `type` varchar(150) DEFAULT '',
  `flgpre` int(10) unsigned NOT NULL DEFAULT 0,
  `flgpost` int(10) unsigned NOT NULL DEFAULT 0,
  `prehtml` text DEFAULT '',
  `posthtml` text DEFAULT '',
  `tpattachment` int(10) unsigned NOT NULL DEFAULT 0,
  `content` longtext DEFAULT '',
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_ATTACHMENT_TP_ATTACHMENTS` (`tpattachment`),
  CONSTRAINT `fk_ATTACHMENT_TP_ATTACHMENTS` FOREIGN KEY (`tpattachment`) REFERENCES `tpattachments` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
INSERT INTO `attachments` VALUES (1,'','img/logo.png','logo','provaimg','MYLOGO','','','','','',0,0,'','',6,'','2023-06-22 19:55:52',NULL),(2,'','img/user/GIUSEPPE_SASSONE_PROFILE.png','GIUSEPPE_SASSONE_PROFILE','','GIUSASSO00_PROFILE_IMAGE','','','png','','',0,0,'','',6,'','2023-06-22 19:56:53',NULL),(3,'','img/activity/DANDY_CORPORATION_LOGO.png','DANDY_CORPORATION_LOGO','','DANDYCORP_LOGO_IMAGE','','','png','','',0,0,'','',6,'','2023-06-22 19:56:53',NULL),(4,'','img/cv/GIUSEPPE_SASSONE_INF.png','GIUSEPPE_SASSONE_INF','','CV_GIUSEPPE_SASSONE_INF_IMAGE','','','png','','',0,0,'','',6,'','2023-06-22 19:58:50',NULL);
DROP TABLE IF EXISTS `balancepayments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `balancepayments` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `cod` varchar(50) NOT NULL DEFAULT '',
  `balance` int(10) unsigned NOT NULL DEFAULT 0,
  `payment` int(10) unsigned NOT NULL DEFAULT 0,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_BALANCEPAYMENT_BALANCE_BALANCES` (`balance`),
  KEY `fk_BALANCEPAYMENT_PAYMENT_PAYMENTS` (`payment`),
  CONSTRAINT `fk_BALANCEPAYMENT_BALANCE_BALANCES` FOREIGN KEY (`balance`) REFERENCES `balances` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_BALANCEPAYMENT_PAYMENT_PAYMENTS` FOREIGN KEY (`payment`) REFERENCES `payments` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `balances`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `balances` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `cod` varchar(50) NOT NULL DEFAULT '',
  `title` varchar(150) NOT NULL DEFAULT '',
  `description` text DEFAULT NULL,
  `user` int(10) unsigned NOT NULL DEFAULT 0,
  `activity` int(10) unsigned NOT NULL DEFAULT 0,
  `initdeposit` double(11,2) NOT NULL DEFAULT 0.00,
  `currencyid` int(10) unsigned NOT NULL DEFAULT 0,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_BALANCES_OWNER_USERS` (`user`),
  KEY `fk_BALANCES_ACTIVITY_ACTIVITIES` (`activity`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `basketproducts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `basketproducts` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `cod` varchar(50) NOT NULL DEFAULT '',
  `title` varchar(50) NOT NULL DEFAULT '',
  `product` int(10) unsigned NOT NULL DEFAULT 0,
  `basket` int(10) unsigned NOT NULL DEFAULT 0,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_BASKETPRODUCT_PRODUCT_PRODUCTS` (`product`),
  KEY `fk_BASKETPRODUCT_BASKET_BASKETS` (`basket`),
  CONSTRAINT `fk_BASKETPRODUCT_BASKET_BASKETS` FOREIGN KEY (`basket`) REFERENCES `baskets` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_BASKETPRODUCT_PRODUCT_PRODUCTS` FOREIGN KEY (`product`) REFERENCES `products` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `baskets`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `baskets` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `cod` varchar(50) NOT NULL DEFAULT '',
  `website` varchar(250) NOT NULL DEFAULT '',
  `title` varchar(50) NOT NULL DEFAULT '',
  `flgclosed` int(10) unsigned NOT NULL DEFAULT 0,
  `flgreserve` int(10) unsigned NOT NULL DEFAULT 0,
  `user` int(10) unsigned NOT NULL DEFAULT 0,
  `activity` int(10) unsigned NOT NULL DEFAULT 0,
  `email` varchar(50) NOT NULL DEFAULT '',
  `phone` varchar(50) NOT NULL DEFAULT '',
  `emailto` varchar(50) NOT NULL DEFAULT '',
  `phoneto` varchar(50) NOT NULL DEFAULT '',
  `strto` varchar(100) NOT NULL DEFAULT '',
  `note` text DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_BASKET_USER_USERS` (`user`),
  KEY `fk_BASKET_ACTIVITY_ACTIVITIES` (`activity`),
  CONSTRAINT `fk_BASKET_ACTIVITY_ACTIVITIES` FOREIGN KEY (`activity`) REFERENCES `activities` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_BASKET_USER_USERS` FOREIGN KEY (`user`) REFERENCES `users` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `basketservices`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `basketservices` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `cod` varchar(50) NOT NULL DEFAULT '',
  `title` varchar(50) NOT NULL DEFAULT '',
  `service` int(10) unsigned NOT NULL DEFAULT 0,
  `basket` int(10) unsigned NOT NULL DEFAULT 0,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_BASKETSERVICE_SERVICE_SERVICES` (`service`),
  KEY `fk_BASKETSERVICE_BASKET_BASKETS` (`basket`),
  CONSTRAINT `fk_BASKETSERVICE_BASKET_BASKETS` FOREIGN KEY (`basket`) REFERENCES `baskets` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_BASKETSERVICE_SERVICE_SERVICES` FOREIGN KEY (`service`) REFERENCES `services` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `baskettickets`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `baskettickets` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `cod` varchar(50) NOT NULL DEFAULT '',
  `title` varchar(50) NOT NULL DEFAULT '',
  `ticket` int(10) unsigned NOT NULL DEFAULT 0,
  `basket` int(10) unsigned NOT NULL DEFAULT 0,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_BASKETTICKET_TICKET_TICKETS` (`ticket`),
  KEY `fk_BASKETTICKET_BASKET_BASKETS` (`basket`),
  CONSTRAINT `fk_BASKETTICKET_BASKET_BASKETS` FOREIGN KEY (`basket`) REFERENCES `baskets` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_BASKETTICKET_TICKET_TICKETS` FOREIGN KEY (`ticket`) REFERENCES `tickets` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `brandattachments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `brandattachments` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `cod` varchar(50) NOT NULL DEFAULT '',
  `brand` int(10) unsigned NOT NULL DEFAULT 0,
  `attachment` int(10) unsigned NOT NULL DEFAULT 0,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_BRANDATTACHMENT_BRAND_BRANDS` (`brand`),
  KEY `fk_BRANDATTACHMENT_ATTACHMENT_ATTACHMENTS` (`attachment`),
  CONSTRAINT `fk_BRANDATTACHMENT_ATTACHMENT_ATTACHMENTS` FOREIGN KEY (`attachment`) REFERENCES `attachments` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_BRANDATTACHMENT_BRAND_BRANDS` FOREIGN KEY (`brand`) REFERENCES `brands` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `brandreferences`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `brandreferences` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `cod` varchar(50) NOT NULL DEFAULT '',
  `contactreference` int(10) unsigned NOT NULL DEFAULT 0,
  `brand` int(10) unsigned NOT NULL DEFAULT 0,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_BRANDREFERENCES_REFERENCE_REFERENCES` (`contactreference`),
  KEY `fk_BRANDREFERENCES_BRAND_BRANDS` (`brand`),
  CONSTRAINT `fk_BRANDREFERENCES_BRAND_BRANDS` FOREIGN KEY (`brand`) REFERENCES `brands` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_BRANDREFERENCES_REFERENCE_REFERENCES` FOREIGN KEY (`contactreference`) REFERENCES `contactreferences` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `brands`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `brands` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `cod` varchar(50) NOT NULL DEFAULT '',
  `name` varchar(50) NOT NULL DEFAULT '',
  `description` text DEFAULT NULL,
  `image` int(10) unsigned NOT NULL DEFAULT 0,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_BRAND_IMAGE_ATTACHMENT` (`image`),
  CONSTRAINT `fk_BRAND_IMAGE_ATTACHMENT` FOREIGN KEY (`image`) REFERENCES `attachments` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `categories` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `cod` varchar(50) NOT NULL DEFAULT '',
  `name` varchar(50) NOT NULL DEFAULT '',
  `description` text DEFAULT NULL,
  `image` int(10) unsigned NOT NULL DEFAULT 0,
  `parent_id` int(10) unsigned DEFAULT 0,
  `lft` int(10) unsigned DEFAULT 0,
  `rght` int(10) unsigned DEFAULT 0,
  `reftable` varchar(50) NOT NULL DEFAULT '',
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_CATEGORY_IMAGE_ATTACHMENT` (`image`),
  KEY `fk_CATEGORY_PARENT_CATEGORY` (`parent_id`),
  CONSTRAINT `fk_CATEGORY_IMAGE_ATTACHMENT` FOREIGN KEY (`image`) REFERENCES `attachments` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_CATEGORY_PARENT_CATEGORY` FOREIGN KEY (`parent_id`) REFERENCES `categories` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `categoryattachments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `categoryattachments` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `cod` varchar(50) NOT NULL DEFAULT '',
  `category` int(10) unsigned NOT NULL DEFAULT 0,
  `attachment` int(10) unsigned NOT NULL DEFAULT 0,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_CATEGORYATTACHMENT_CATEGORY_CATEGORIES` (`category`),
  KEY `fk_CATEGORYATTACHMENT_ATTACHMENT_ATTACHMENTS` (`attachment`),
  CONSTRAINT `fk_CATEGORYATTACHMENT_ATTACHMENT_ATTACHMENTS` FOREIGN KEY (`attachment`) REFERENCES `attachments` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_CATEGORYATTACHMENT_CATEGORY_CATEGORIES` FOREIGN KEY (`category`) REFERENCES `categories` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `cities`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cities` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `cod` varchar(50) DEFAULT '',
  `countrycode` varchar(10) DEFAULT '',
  `postalcode` varchar(10) DEFAULT '',
  `place` varchar(100) DEFAULT '',
  `region` varchar(100) DEFAULT '',
  `regioncode` varchar(10) DEFAULT '',
  `province` varchar(100) DEFAULT '',
  `provincecode` varchar(10) DEFAULT '',
  `community` varchar(100) DEFAULT '',
  `communitycode` varchar(10) DEFAULT '',
  `geo1` varchar(50) DEFAULT '',
  `geo2` varchar(50) DEFAULT '',
  `nation` int(10) unsigned NOT NULL DEFAULT 0,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_CITIES_NATION_NATIONS` (`nation`),
  CONSTRAINT `fk_CITIES_NATION_NATIONS` FOREIGN KEY (`nation`) REFERENCES `nations` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `clienttokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `clienttokens` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `appname` varchar(50) DEFAULT NULL,
  `token` text DEFAULT '',
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
INSERT INTO `clienttokens` VALUES (1,'clienttest','ZUt0bFc3dVpXQzR0SmM5YjlGTGE2MlVCU0xWTlE4NFc4cXdYRXlZZ1QxUEZkZjBKMFRCckJ0NXFOenlSMGdXdWdwV2xpV3RZaWcvcUFKdWtSM3Y5TEplQkphcUJZd0x1dkF5SnFsQWZySmc9','2023-06-22 19:56:52',NULL);
DROP TABLE IF EXISTS `confirmoperations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `confirmoperations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `codoperation` varchar(50) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `phone` varchar(150) DEFAULT NULL,
  `codsms` varchar(50) DEFAULT NULL,
  `email` varchar(150) DEFAULT NULL,
  `codemail` varchar(50) DEFAULT NULL,
  `user` int(10) unsigned NOT NULL DEFAULT 0,
  `token` text DEFAULT NULL,
  `flgaccepted` int(10) unsigned NOT NULL DEFAULT 0,
  `flgclosed` int(10) unsigned NOT NULL DEFAULT 0,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_CONFIRMOPERATIONS_USER_USERS` (`user`),
  CONSTRAINT `fk_CONFIRMOPERATIONS_USER_USERS` FOREIGN KEY (`user`) REFERENCES `users` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `contactreferences`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `contactreferences` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `cod` varchar(50) NOT NULL DEFAULT '',
  `val` varchar(250) NOT NULL DEFAULT '',
  `description` varchar(250) NOT NULL DEFAULT '',
  `tpcontactreference` int(10) unsigned NOT NULL DEFAULT 0,
  `tpsocialreference` int(10) unsigned NOT NULL DEFAULT 0,
  `prefix` varchar(50) NOT NULL DEFAULT '',
  `flgused` int(10) unsigned NOT NULL DEFAULT 1,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_CONTACTREFERENCE_TPREFERENCE_TPREFERENCES` (`tpcontactreference`),
  KEY `fk_CONTACTREFERENCE_TPSOCIAL_TPSOCIALREFERENCES` (`tpsocialreference`),
  CONSTRAINT `fk_CONTACTREFERENCE_TPREFERENCE_TPREFERENCES` FOREIGN KEY (`tpcontactreference`) REFERENCES `tpcontactreferences` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_CONTACTREFERENCE_TPSOCIAL_TPSOCIALREFERENCES` FOREIGN KEY (`tpsocialreference`) REFERENCES `tpsocialreferences` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
INSERT INTO `contactreferences` VALUES (1,'CTREF1','test1@gmail.com','',4,0,'',1,'2023-06-22 19:56:52',NULL),(2,'CTREF2','test2@gmail.com','',4,0,'',1,'2023-06-22 19:56:52',NULL),(3,'CTREF3','3334455676','',2,0,'+39',1,'2023-06-22 19:56:52',NULL),(4,'CTREF4','3487766554','',2,0,'+39',1,'2023-06-22 19:56:52',NULL),(5,'GIUSASSO00_EMAIL','giuseppesassone00@gmail.com','',4,0,'',1,'2023-06-22 19:56:53',NULL),(6,'GIUSASSO00_EMAIL_FB','giuseppesassone98@gmail.com','',4,0,'',1,'2023-06-22 19:56:53',NULL),(7,'GIUSASSO00_CEL','3281044127','',2,0,'+39',1,'2023-06-22 19:56:53',NULL),(8,'GIUSASSO00_LINKEDIN','https://it.linkedin.com/in/giuseppesassone','',6,3,'',1,'2023-06-22 19:56:53',NULL),(9,'DANDYCORP_EMAIL','keyemporium.manager@gmail.com','',4,0,'',1,'2023-06-22 19:56:53',NULL),(10,'DANDYCORP_CEL','787283339','',2,0,'+41',1,'2023-06-22 19:56:53',NULL),(11,'CV_GIUSEPPE_SASSONE_INF_EMAIL','giuseppesassone00@gmail.com','',4,0,'',1,'2023-06-22 19:58:50',NULL),(12,'CV_GIUSEPPE_SASSONE_INF_PHONE','3281044127','',2,0,'+39',1,'2023-06-22 19:58:50',NULL);
DROP TABLE IF EXISTS `cryptnotes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cryptnotes` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `cod` varchar(50) NOT NULL DEFAULT '',
  `title` varchar(250) NOT NULL DEFAULT '',
  `description` varchar(250) DEFAULT '',
  `crypt` varchar(250) NOT NULL DEFAULT '',
  `symbol` varchar(50) NOT NULL DEFAULT '',
  `flgused` int(10) unsigned NOT NULL DEFAULT 1,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `currencies`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `currencies` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `cod` varchar(50) NOT NULL DEFAULT '',
  `title` varchar(250) NOT NULL DEFAULT '',
  `symbol` varchar(50) NOT NULL DEFAULT '',
  `icon` varchar(150) NOT NULL DEFAULT '',
  `flgused` int(10) unsigned NOT NULL DEFAULT 1,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=104 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
INSERT INTO `currencies` VALUES (1,'EUR','CURRENCY-EUR','&#128;','euro-currency-symbol',1,'2023-06-22 19:57:41',NULL),(2,'AFG','CURRENCY-AFG','؋','afghanistan-afghani',1,'2023-06-22 19:57:41',NULL),(3,'ALL','CURRENCY-ALL','Lek','albania-lek-currency-symbol',1,'2023-06-22 19:57:41',NULL),(4,'AWG','CURRENCY-AWG','ƒ','aruba-guilder-currency-symbol',1,'2023-06-22 19:57:41',NULL),(5,'AZN','CURRENCY-AZN','ман','azerbaijan-currency-symbol',1,'2023-06-22 19:57:41',NULL),(6,'BYR','CURRENCY-BYR','р','belarus-ruble-currency-symbol',1,'2023-06-22 19:57:41',NULL),(7,'BZD','CURRENCY-BZD','BZ$','belize-dollar-symbol',1,'2023-06-22 19:57:41',NULL),(8,'BAM','CURRENCY-BAM','KM','bosnia-herzegovina-convertible-marka-currency-symbol',1,'2023-06-22 19:57:41',NULL),(9,'KHR','CURRENCY-KHR','៛','cambodia-riel-currency-symbol',1,'2023-06-22 19:57:41',NULL),(10,'HRK','CURRENCY-HRK','kn','croatia-kuna-currency-symbol',1,'2023-06-22 19:57:41',NULL),(11,'CUP','CURRENCY-CUP','₱','cuba-peso-currency-symbol',1,'2023-06-22 19:57:41',NULL),(12,'EEK','CURRENCY-EEK','KR','estonia-kroon-currency-symbol',1,'2023-06-22 19:57:41',NULL),(13,'GHS','CURRENCY-GHS','₵','ghana-cedis',1,'2023-06-22 19:57:41',NULL),(14,'USD','CURRENCY-USD','&#36;','dollar-currency-symbol-1',1,'2023-06-22 19:57:41',NULL),(15,'RSD','CURRENCY-RSD','дин','serbia-dinar-currency-symbol',1,'2023-06-22 19:57:41',NULL),(16,'SCR','CURRENCY-SCR','₨','seychelles-rupee-currency-symbol',1,'2023-06-22 19:57:41',NULL),(17,'SOS','CURRENCY-SOS','S','somalia-shilling',1,'2023-06-22 19:57:41',NULL),(18,'TWD','CURRENCY-TWD','NT$','taiwan-new-dollar-symbol',1,'2023-06-22 19:57:41',NULL),(19,'GBP','CURRENCY-GBP','&#163;','pound-symbol-variant',1,'2023-06-22 19:57:41',NULL),(20,'CHF','CURRENCY-CHF','chf','switzerland-franc',1,'2023-06-22 19:57:41',NULL),(21,'AUD','CURRENCY-AUD','A&#36;','dollar-currency-symbol-1',1,'2023-06-22 19:57:41',NULL),(22,'BWP','CURRENCY-BWP','P','botswana-pula-currency-sign',1,'2023-06-22 19:57:41',NULL),(23,'CAD','CURRENCY-CAD','C&#36;','dollar-currency-symbol-1',1,'2023-06-22 19:57:41',NULL),(24,'HKD','CURRENCY-HKD','HK&#36;','NOT_PACKING/hong-kong-dollar-symbol',1,'2023-06-22 19:57:41',NULL),(25,'INR','CURRENCY-INR','₹','india-rupee-currency-symbol',1,'2023-06-22 19:57:41',NULL),(26,'NZD','CURRENCY-NZD','NZ&#36;','dollar-currency-symbol-1',1,'2023-06-22 19:57:41',NULL),(27,'PHP','CURRENCY-PHP','₱','philippines-peso-currency-symbol',1,'2023-06-22 19:57:41',NULL),(28,'SGD','CURRENCY-SGD','S&#36;','dollar-currency-symbol-1',1,'2023-06-22 19:57:41',NULL),(29,'ZAR','CURRENCY-ZAR','R','south-africa-rand',1,'2023-06-22 19:57:41',NULL),(30,'ARS','CURRENCY-ARS','&#36;','dollar-currency-symbol-1',1,'2023-06-22 19:57:41',NULL),(31,'BOB','CURRENCY-BOB','$b','bolivia-boliviano-currency-symbol',1,'2023-06-22 19:57:41',NULL),(32,'CLP','CURRENCY-CLP','&#36;','dollar-currency-symbol-1',1,'2023-06-22 19:57:41',NULL),(33,'COP','CURRENCY-COP','Col&#36;','dollar-currency-symbol-1',1,'2023-06-22 19:57:41',NULL),(34,'CRC','CURRENCY-CRC','₡','costa-rica-colon-currency-symbol',1,'2023-06-22 19:57:41',NULL),(35,'DOP','CURRENCY-DOP','RD&#36;','dominican-republic-peso',1,'2023-06-22 19:57:41',NULL),(36,'GTQ','CURRENCY-GTQ','Q','guatemala-quetzal-currency-symbol',1,'2023-06-22 19:57:41',NULL),(37,'HNL','CURRENCY-HNL','L','honduras-lempira-currency-symbol',1,'2023-06-22 19:57:41',NULL),(38,'ISK','CURRENCY-ISK','kr','iceland-krona-currency-symbol',1,'2023-06-22 19:57:41',NULL),(39,'IRR','CURRENCY-IRR','﷼','iran-rial',1,'2023-06-22 19:57:41',NULL),(40,'ILS','CURRENCY-ILS','₪','israel-shekel-currency-symbol',1,'2023-06-22 19:57:41',NULL),(41,'JMD','CURRENCY-JMD','J&#36;','jamaica-dollar-currency-symbol',1,'2023-06-22 19:57:41',NULL),(42,'KZT','CURRENCY-KZT','₸','kazakhstan-tenge-currency-symbol',1,'2023-06-22 19:57:41',NULL),(43,'KGS','CURRENCY-KGS','лв','kyrgyzstan-som-currency-symbol',1,'2023-06-22 19:57:41',NULL),(44,'LAK','CURRENCY-LAK','₭','laos-kip-currency-symbol',1,'2023-06-22 19:57:41',NULL),(45,'LVL','CURRENCY-LVL','Ls','latvia-lat',1,'2023-06-22 19:57:41',NULL),(46,'LTL','CURRENCY-LTL','Lt','lithuania-litas-currency-symbol',1,'2023-06-22 19:57:41',NULL),(47,'MKD','CURRENCY-MKD','ден','macedonia-denar',1,'2023-06-22 19:57:41',NULL),(48,'MUR','CURRENCY-MUR','Rs','mauritius-rupee-currency-symbol',1,'2023-06-22 19:57:41',NULL),(49,'MNT','CURRENCY-MNT','₮','mongolia-tughrik-currency-symbol',1,'2023-06-22 19:57:41',NULL),(50,'MZN','CURRENCY-MZN','MTn','mozambique-metical-currency-symbol',1,'2023-06-22 19:57:41',NULL),(51,'NPR','CURRENCY-NPR','Rs','nepal-rupee-currency-symbol',1,'2023-06-22 19:57:41',NULL),(52,'NPR','CURRENCY-ANG','ƒ','netherlands-antilles-guilder',1,'2023-06-22 19:57:41',NULL),(53,'NPR','CURRENCY-NGN','₦','nigeria-naira-currency-symbol',1,'2023-06-22 19:57:41',NULL),(54,'LKR','CURRENCY-LKR','₨','sri-lanka-rupee-currency-symbol',1,'2023-06-22 19:57:41',NULL),(55,'MXN','CURRENCY-MXN','Mex&#36;','dollar-currency-symbol-1',1,'2023-06-22 19:57:41',NULL),(56,'NIO','CURRENCY-NIO','C&#36;','nicaragua-cordoba-currency-symbol',1,'2023-06-22 19:57:41',NULL),(57,'PKR','CURRENCY-PKR','Rs','pakistan-rupee-currency-symbol',1,'2023-06-22 19:57:41',NULL),(58,'PAB','CURRENCY-PAB',' B/.','panama-balboa-currency-symbol',1,'2023-06-22 19:57:41',NULL),(59,'PEN','CURRENCY-PEN','S/.','peru-nuevo-sol-currency-symbol',1,'2023-06-22 19:57:41',NULL),(60,'TTD','CURRENCY-TTD','TT$','trinidad-and-tobago-dollar-currency-symbol',1,'2023-06-22 19:57:41',NULL),(61,'PYG','CURRENCY-PYG','₲','paraguay-guarani-currency-symbol',1,'2023-06-22 19:57:41',NULL),(62,'SVC','CURRENCY-SVC','&#36;','dollar-currency-symbol-1',1,'2023-06-22 19:57:41',NULL),(63,'UYU','CURRENCY-UYU','&#36;U','uruguay-peso-currency-symbol',1,'2023-06-22 19:57:41',NULL),(64,'UZS','CURRENCY-UZS','лв','uzbekistan-som-currency-symbol',1,'2023-06-22 19:57:41',NULL),(65,'VEF','CURRENCY-VEF','Bs','venezuela-bolivar',1,'2023-06-22 19:57:41',NULL),(66,'CNY','CURRENCY-CNY','&#165;','yen-currency-symbol',1,'2023-06-22 19:57:41',NULL),(67,'RUB','CURRENCY-RUB','₽','russia-ruble-currency-symbol',1,'2023-06-22 19:57:41',NULL),(68,'UAH','CURRENCY-UAH','₴','ukraine-hryvna',1,'2023-06-22 19:57:41',NULL),(69,'BRL','CURRENCY-BRL','R&#36;','brazil-real-symbol',1,'2023-06-22 19:57:41',NULL),(70,'JPY','CURRENCY-JPY','&#165;','yen-currency-symbol',1,'2023-06-22 19:57:41',NULL),(71,'ZWD','CURRENCY-ZWD','Z$','zimbabwe-dollar',1,'2023-06-22 19:57:41',NULL),(72,'AED','CURRENCY-AED','د.إ','NOT_PACKING/arab_emirates_dirham_currency',1,'2023-06-22 19:57:41',NULL),(73,'BHD','CURRENCY-BHD','BD(.د.ب )','NOT_PACKING/Bahrain-Dinar',1,'2023-06-22 19:57:41',NULL),(74,'DZD','CURRENCY-DZD','DA(دج)','NOT_PACKING/algeria_algerian_dinar_currency',1,'2023-06-22 19:57:41',NULL),(75,'EGP','CURRENCY-EGP','&#163;(ج.م)','pound-symbol-variant',1,'2023-06-22 19:57:41',NULL),(76,'IQD','CURRENCY-IQD','ع.د','NOT_PACKING/iraq_iraqi_dinar_currency',1,'2023-06-22 19:57:41',NULL),(77,'JOD','CURRENCY-JOD','JD(د.ا)','NOT_PACKING/jordian_dinar',1,'2023-06-22 19:57:41',NULL),(78,'KWD','CURRENCY-KWD','ك','NOT_PACKING/Kuwaiti-Dinar-arabic',1,'2023-06-22 19:57:41',NULL),(79,'LYD','CURRENCY-LYD','LD(ل.د)','NOT_PACKING/libya_libyan_dinar_currency',1,'2023-06-22 19:57:41',NULL),(80,'MAD','CURRENCY-MAD','د.م.','NOT_PACKING/morocco_moroccon_dirham_currency',1,'2023-06-22 19:57:41',NULL),(81,'OMR','CURRENCY-OMR','﷼','oman-rial-currency',1,'2023-06-22 19:57:41',NULL),(82,'QAR','CURRENCY-QAR','﷼','qatar-riyal',1,'2023-06-22 19:57:41',NULL),(83,'SAR','CURRENCY-SAR','﷼','saudi-arabia-riyal-currency-symbol',1,'2023-06-22 19:57:41',NULL),(84,'SDG','CURRENCY-SDG','&#163;(ج.س.)','pound-symbol-variant',1,'2023-06-22 19:57:41',NULL),(85,'SYP','CURRENCY-SYP','&#163;','pound-symbol-variant',1,'2023-06-22 19:57:41',NULL),(86,'TND','CURRENCY-TND','DT(د.ت)','NOT_PACKING/tunisia_tunisian_dinar_currency',1,'2023-06-22 19:57:41',NULL),(87,'YER','CURRENCY-YER','﷼','yemen-rial-currency-symbol',1,'2023-06-22 19:57:41',NULL),(88,'IDR','CURRENCY-IDR','Rp','indonesia-rupiah-currency-symbol',1,'2023-06-22 19:57:41',NULL),(89,'BDT','CURRENCY-BDT','৳','NOT_PACKING/Taka_Bengali',1,'2023-06-22 19:57:41',NULL),(90,'VND','CURRENCY-VND','₫','viet-nam-dong-currency-symbol',1,'2023-06-22 19:57:41',NULL),(91,'THB','CURRENCY-THB','฿','thailand-baht',1,'2023-06-22 19:57:41',NULL),(92,'TRY','CURRENCY-TRY','₺','turkey-lira-currency-symbol-1',1,'2023-06-22 19:57:41',NULL),(93,'PLN','CURRENCY-PLN','zł','poland-zloty-currency-symbol',1,'2023-06-22 19:57:41',NULL),(94,'RON','CURRENCY-RON','lei','romania-lei-currency',1,'2023-06-22 19:57:41',NULL),(95,'MYR','CURRENCY-MYR','RM','malaysia-ringgit',1,'2023-06-22 19:57:41',NULL),(96,'DKK','CURRENCY-DKK','kr.','denmark-krone-currency-symbol',1,'2023-06-22 19:57:41',NULL),(97,'SEK','CURRENCY-SEK','kr','sweden-krona-currency-symbol',1,'2023-06-22 19:57:41',NULL),(98,'BGN','CURRENCY-BGN','лв','bulgaria-lev',1,'2023-06-22 19:57:41',NULL),(99,'CZK','CURRENCY-CZK','Kč','czech-republic-koruna-currency-symbol',1,'2023-06-22 19:57:41',NULL),(100,'HUF','CURRENCY-HUF','Ft','hungary-forint-currency-symbol',1,'2023-06-22 19:57:41',NULL),(101,'NOK','CURRENCY-NOK','kr','norway-krone-currency-symbol',1,'2023-06-22 19:57:41',NULL),(102,'KRW','CURRENCY-KRW','₩','south-korea-won-currency-symbol',1,'2023-06-22 19:57:41',NULL),(103,'KPW','CURRENCY-KRW','₩','north-korea-won',1,'2023-06-22 19:57:41',NULL);
DROP TABLE IF EXISTS `discounts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `discounts` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `cod` varchar(50) NOT NULL DEFAULT '',
  `discount` double(11,2) NOT NULL DEFAULT 0.00,
  `discount_percent` double(11,2) NOT NULL DEFAULT 0.00,
  `description` text DEFAULT NULL,
  `levelquantity` int(10) unsigned NOT NULL DEFAULT 0,
  `levelprice` double(11,2) NOT NULL DEFAULT 0.00,
  `dtainit` datetime DEFAULT NULL,
  `dtaend` datetime DEFAULT NULL,
  `flgsystem` int(10) unsigned NOT NULL DEFAULT 0,
  `flglevelbasket` int(10) unsigned NOT NULL DEFAULT 0,
  `currencyid` int(10) unsigned NOT NULL DEFAULT 0,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_DISCOUNT_CURRENCY_CURRENCIES` (`currencyid`),
  CONSTRAINT `fk_DISCOUNT_CURRENCY_CURRENCIES` FOREIGN KEY (`currencyid`) REFERENCES `currencies` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `eventattachments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `eventattachments` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `cod` varchar(50) NOT NULL DEFAULT '',
  `flgprincipal` int(10) unsigned NOT NULL DEFAULT 0,
  `attachment` int(10) unsigned NOT NULL DEFAULT 0,
  `tpattachment` int(10) unsigned NOT NULL DEFAULT 0,
  `event` int(10) unsigned NOT NULL DEFAULT 0,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_EVENTATTACHMENTS_EVENT_EVENTS` (`event`),
  KEY `fk_EVENTATTACHMENTS_ATTACHMENT_ATTACHMENTS` (`attachment`),
  KEY `fk_EVENTATTACHMENTS_TPATTACHMENT_TPATTACHMENTS` (`tpattachment`),
  CONSTRAINT `fk_EVENTATTACHMENTS_ATTACHMENT_ATTACHMENTS` FOREIGN KEY (`attachment`) REFERENCES `attachments` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_EVENTATTACHMENTS_EVENT_EVENTS` FOREIGN KEY (`event`) REFERENCES `events` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_EVENTATTACHMENTS_TPATTACHMENT_TPATTACHMENTS` FOREIGN KEY (`tpattachment`) REFERENCES `tpattachments` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `eventreferences`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `eventreferences` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `flgprincipal` int(10) unsigned NOT NULL DEFAULT 0,
  `contactreference` int(10) unsigned NOT NULL DEFAULT 0,
  `tpcontactreference` int(10) unsigned NOT NULL DEFAULT 0,
  `event` int(10) unsigned NOT NULL DEFAULT 0,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_EVENTREFERENCES_REFERENCE_REFERENCES` (`contactreference`),
  KEY `fk_EVENTREFERENCES_EVENT_EVENTS` (`event`),
  KEY `fk_EVENTREFERENCES_TPREFERENCE_TPCONTACTREFERENCES` (`tpcontactreference`),
  CONSTRAINT `fk_EVENTREFERENCES_EVENT_EVENTS` FOREIGN KEY (`event`) REFERENCES `events` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_EVENTREFERENCES_REFERENCE_REFERENCES` FOREIGN KEY (`contactreference`) REFERENCES `contactreferences` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_EVENTREFERENCES_TPREFERENCE_TPCONTACTREFERENCES` FOREIGN KEY (`tpcontactreference`) REFERENCES `tpcontactreferences` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `events`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `events` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `cod` varchar(50) NOT NULL DEFAULT '',
  `title` varchar(250) NOT NULL DEFAULT '',
  `description` text DEFAULT '',
  `dtainit` datetime DEFAULT NULL,
  `dtaend` datetime DEFAULT NULL,
  `tpevent` int(10) unsigned NOT NULL DEFAULT 0,
  `tpcat` int(10) unsigned NOT NULL DEFAULT 0,
  `flgdeleted` int(10) unsigned NOT NULL DEFAULT 0,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_EVENTS_TPEVENT_TPEVENTS` (`tpevent`),
  KEY `fk_EVENTS_TPCAT_TPCATS` (`tpcat`),
  CONSTRAINT `fk_EVENTS_TPCAT_TPCATS` FOREIGN KEY (`tpcat`) REFERENCES `tpcats` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_EVENTS_TPEVENT_TPEVENTS` FOREIGN KEY (`tpevent`) REFERENCES `tpevents` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `grouprelations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `grouprelations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `cod` varchar(50) NOT NULL DEFAULT '',
  `group` int(10) unsigned NOT NULL DEFAULT 0,
  `groupcod` varchar(50) NOT NULL DEFAULT '',
  `tableid` int(10) unsigned NOT NULL DEFAULT 0,
  `tablename` varchar(50) NOT NULL DEFAULT '',
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_GROUPRELATION_GROUP_GROUPS` (`group`),
  CONSTRAINT `fk_GROUPRELATION_GROUP_GROUPS` FOREIGN KEY (`group`) REFERENCES `groups` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
INSERT INTO `grouprelations` VALUES (1,'GRP1_REL1',1,'GRP1',1,'testfks','2023-06-22 19:55:46',NULL),(2,'GRP1_REL2',1,'GRP1',2,'testfks','2023-06-22 19:55:46',NULL),(3,'GRP2_REL1',2,'GRP2',2,'testfks','2023-06-22 19:55:46',NULL);
DROP TABLE IF EXISTS `groups`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `groups` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `cod` varchar(50) NOT NULL DEFAULT '',
  `title` varchar(250) NOT NULL DEFAULT '',
  `description` varchar(250) NOT NULL DEFAULT '',
  `symbol` varchar(50) NOT NULL DEFAULT '',
  `flgused` int(10) unsigned NOT NULL DEFAULT 1,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
INSERT INTO `groups` VALUES (1,'GRP1','Primo Gruppo Prova','','',1,'2023-06-22 19:55:46',NULL),(2,'GRP2','Secondo Gruppo Prova','','',1,'2023-06-22 19:55:46',NULL);
DROP TABLE IF EXISTS `languages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `languages` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `cod` varchar(50) NOT NULL DEFAULT '',
  `title` varchar(250) NOT NULL DEFAULT '',
  `symbol` varchar(50) NOT NULL DEFAULT '',
  `flgused` int(10) unsigned NOT NULL DEFAULT 0,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=90 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
INSERT INTO `languages` VALUES (1,'ita','LANGUAGE-IT','Italy.png',1,'2023-06-22 19:56:30',NULL),(2,'itach','LANGUAGE-ITCH','Switzerland.png',0,'2023-06-22 19:56:30',NULL),(3,'eng','LANGUAGE-EN','United-Kingdom.png',1,'2023-06-22 19:56:30',NULL),(4,'engau','LANGUAGE-ENAU','Australia.png',0,'2023-06-22 19:56:30',NULL),(5,'engbw','LANGUAGE-ENBW','Botswana.png',0,'2023-06-22 19:56:30',NULL),(6,'engca','LANGUAGE-ENCA','Canada.png',0,'2023-06-22 19:56:30',NULL),(7,'enghk','LANGUAGE-ENHK','HongKong_SAR.png',0,'2023-06-22 19:56:30',NULL),(8,'engie','LANGUAGE-ENIE','Ireland.png',0,'2023-06-22 19:56:30',NULL),(9,'engin','LANGUAGE-ENIN','India.png',0,'2023-06-22 19:56:30',NULL),(10,'engnz','LANGUAGE-ENNZ','New-Zealand.png',0,'2023-06-22 19:56:30',NULL),(11,'engph','LANGUAGE-ENPH','Philippines.png',0,'2023-06-22 19:56:30',NULL),(12,'engsg','LANGUAGE-ENSG','Singapore.png',0,'2023-06-22 19:56:30',NULL),(13,'engus','LANGUAGE-ENUS','USA.png',0,'2023-06-22 19:56:30',NULL),(14,'engza','LANGUAGE-ENZA','South-Africa.png',0,'2023-06-22 19:56:30',NULL),(15,'engzw','LANGUAGE-ENZW','Zimbabwe.png',0,'2023-06-22 19:56:30',NULL),(16,'fra','LANGUAGE-FR','France.png',1,'2023-06-22 19:56:30',NULL),(17,'frabe','LANGUAGE-FRBE','Belgium.png',0,'2023-06-22 19:56:30',NULL),(18,'fraca','LANGUAGE-FRCA','Canada.png',0,'2023-06-22 19:56:30',NULL),(19,'frach','LANGUAGE-FRCH','Switzerland.png',0,'2023-06-22 19:56:30',NULL),(20,'fralu','LANGUAGE-FRLU','Luxembourg.png',0,'2023-06-22 19:56:30',NULL),(21,'deu','LANGUAGE-DE','Germany.png',1,'2023-06-22 19:56:30',NULL),(22,'deuat','LANGUAGE-DEAT','Austria.png',0,'2023-06-22 19:56:30',NULL),(23,'deube','LANGUAGE-DEBE','Belgium.png',0,'2023-06-22 19:56:30',NULL),(24,'deuch','LANGUAGE-DECH','Switzerland.png',0,'2023-06-22 19:56:30',NULL),(25,'deulu','LANGUAGE-DELU','Luxembourg.png',0,'2023-06-22 19:56:30',NULL),(26,'esp','LANGUAGE-ES','Spain.png',1,'2023-06-22 19:56:30',NULL),(27,'espar','LANGUAGE-ESAR','Argentina.png',0,'2023-06-22 19:56:30',NULL),(28,'espbo','LANGUAGE-ESBO','Bolivia.png',0,'2023-06-22 19:56:30',NULL),(29,'espcl','LANGUAGE-ESCL','Chile.png',0,'2023-06-22 19:56:30',NULL),(30,'espco','LANGUAGE-ESCO','Colombia.png',0,'2023-06-22 19:56:30',NULL),(31,'espcr','LANGUAGE-ESCR','Costa-Rica.png',0,'2023-06-22 19:56:30',NULL),(32,'espdo','LANGUAGE-ESDO','Repubblica-Dominicana.png',0,'2023-06-22 19:56:30',NULL),(33,'espec','LANGUAGE-ESEC','Ecuador.png',0,'2023-06-22 19:56:30',NULL),(34,'espgt','LANGUAGE-ESGT','Guatemala.png',0,'2023-06-22 19:56:30',NULL),(35,'esphn','LANGUAGE-ESHN','Honduras.png',0,'2023-06-22 19:56:30',NULL),(36,'espmx','LANGUAGE-ESMX','Mexico.png',0,'2023-06-22 19:56:30',NULL),(37,'espni','LANGUAGE-ESNI','Nicaragua.png',0,'2023-06-22 19:56:30',NULL),(38,'esppa','LANGUAGE-ESPA','Panama.png',0,'2023-06-22 19:56:30',NULL),(39,'esppe','LANGUAGE-ESPE','Peru.png',0,'2023-06-22 19:56:30',NULL),(40,'esppr','LANGUAGE-ESPR','Puerto-Rico.png',0,'2023-06-22 19:56:30',NULL),(41,'esppy','LANGUAGE-ESPY','Paraguay.png',0,'2023-06-22 19:56:30',NULL),(42,'espsv','LANGUAGE-ESSV','El-Salvador.png',0,'2023-06-22 19:56:30',NULL),(43,'espus','LANGUAGE-ESUS','USA.png',0,'2023-06-22 19:56:30',NULL),(44,'espuy','LANGUAGE-ESUY','Uruguay.png',0,'2023-06-22 19:56:30',NULL),(45,'espve','LANGUAGE-ESVE','Venezuela.png',0,'2023-06-22 19:56:30',NULL),(46,'chn','LANGUAGE-ZH','China.png',1,'2023-06-22 19:56:30',NULL),(47,'rus','LANGUAGE-RU','Russia.png',1,'2023-06-22 19:56:30',NULL),(48,'rusua','LANGUAGE-RUUA','Ukraine.png',0,'2023-06-22 19:56:30',NULL),(49,'por','LANGUAGE-POR','Portugal.png',1,'2023-06-22 19:56:30',NULL),(50,'porbr','LANGUAGE-PORBR','Brazil.png',0,'2023-06-22 19:56:30',NULL),(51,'jpg','LANGUAGE-JP','Japan.png',1,'2023-06-22 19:56:30',NULL),(52,'arb','LANGUAGE-AR','Arabia.png',1,'2023-06-22 19:56:30',NULL),(53,'arbbh','LANGUAGE-ARBH','Bahrain.png',0,'2023-06-22 19:56:30',NULL),(54,'arbdz','LANGUAGE-ARDZ','Algeria.png',0,'2023-06-22 19:56:30',NULL),(55,'arbeg','LANGUAGE-AREG','Egypt.png',0,'2023-06-22 19:56:30',NULL),(56,'arbiq','LANGUAGE-ARIQ','Iraq.png',0,'2023-06-22 19:56:30',NULL),(57,'arbjo','LANGUAGE-ARJO','Giordania.png',0,'2023-06-22 19:56:30',NULL),(58,'arbkw','LANGUAGE-ARKW','Kuwait.png',0,'2023-06-22 19:56:30',NULL),(59,'arbly','LANGUAGE-ARLY','Libya.png',0,'2023-06-22 19:56:30',NULL),(60,'arbma','LANGUAGE-ARMA','Marocco.png',0,'2023-06-22 19:56:30',NULL),(61,'arbom','LANGUAGE-AROM','Oman.png',0,'2023-06-22 19:56:30',NULL),(62,'arbqa','LANGUAGE-ARQA','Qatar.png',0,'2023-06-22 19:56:30',NULL),(63,'arbsa','LANGUAGE-ARSA','Saudi-Arabia.png',0,'2023-06-22 19:56:30',NULL),(64,'arbsd','LANGUAGE-ARSD','Sudan.png',0,'2023-06-22 19:56:30',NULL),(65,'arbsy','LANGUAGE-ARSY','Syria.png',0,'2023-06-22 19:56:30',NULL),(66,'arbtn','LANGUAGE-ARTN','Tunisia.png',0,'2023-06-22 19:56:30',NULL),(67,'arbye','LANGUAGE-ARYE','Yemen.png',0,'2023-06-22 19:56:30',NULL),(68,'hin','LANGUAGE-HIN','India.png',1,'2023-06-22 19:56:30',NULL),(69,'ind','LANGUAGE-IND','Indonesia.png',0,'2023-06-22 19:56:30',NULL),(70,'ben','LANGUAGE-BEN','Bangladesh.png',0,'2023-06-22 19:56:30',NULL),(71,'benin','LANGUAGE-BENIN','India.png',0,'2023-06-22 19:56:30',NULL),(72,'vie','LANGUAGE-VIE','Vietnam.png',0,'2023-06-22 19:56:30',NULL),(73,'tha','LANGUAGE-THA','Thailand.png',0,'2023-06-22 19:56:30',NULL),(74,'tur','LANGUAGE-TUR','Turkey.png',0,'2023-06-22 19:56:30',NULL),(75,'pol','LANGUAGE-POL','Poland.png',1,'2023-06-22 19:56:30',NULL),(76,'rom','LANGUAGE-ROM','Romania.png',1,'2023-06-22 19:56:30',NULL),(77,'mal','LANGUAGE-MAL','Malaysia.png',0,'2023-06-22 19:56:30',NULL),(78,'fin','LANGUAGE-FIN','Finland.png',1,'2023-06-22 19:56:30',NULL),(79,'dan','LANGUAGE-DAN','Denmark.png',1,'2023-06-22 19:56:30',NULL),(80,'swe','LANGUAGE-SWE','Sweden.png',1,'2023-06-22 19:56:30',NULL),(81,'swefi','LANGUAGE-SWEFI','Finland.png',0,'2023-06-22 19:56:30',NULL),(82,'bul','LANGUAGE-BUL','Bulgaria.png',1,'2023-06-22 19:56:30',NULL),(83,'ces','LANGUAGE-CES','Czech-Republic.png',1,'2023-06-22 19:56:30',NULL),(84,'grk','LANGUAGE-GR','Greece.png',1,'2023-06-22 19:56:30',NULL),(85,'grkcy','LANGUAGE-GRCY','Cipro.png',0,'2023-06-22 19:56:30',NULL),(86,'hun','LANGUAGE-HUN','Hungary.png',1,'2023-06-22 19:56:30',NULL),(87,'nld','LANGUAGE-NL','Netherlands.png',1,'2023-06-22 19:56:30',NULL),(88,'nldbe','LANGUAGE-NLBE','Belgium.png',0,'2023-06-22 19:56:30',NULL),(89,'kor','LANGUAGE-KOR','Korea.png',0,'2023-06-22 19:56:30',NULL);
DROP TABLE IF EXISTS `mailattachments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `mailattachments` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `cod` varchar(150) NOT NULL DEFAULT '',
  `mail` int(10) unsigned NOT NULL DEFAULT 0,
  `attachment` int(10) unsigned NOT NULL DEFAULT 0,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_MAILATTACHMENTS_MAIL_MAILS` (`mail`),
  KEY `fk_MAILATTACHMENTS_ATTACHMENT_ATTACHMENTS` (`attachment`),
  CONSTRAINT `fk_MAILATTACHMENTS_ATTACHMENT_ATTACHMENTS` FOREIGN KEY (`attachment`) REFERENCES `attachments` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_MAILATTACHMENTS_MAIL_MAILS` FOREIGN KEY (`mail`) REFERENCES `mails` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
INSERT INTO `mailattachments` VALUES (1,'',1,1,'2023-06-22 19:56:03',NULL);
DROP TABLE IF EXISTS `mailcids`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `mailcids` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `mail` int(10) unsigned NOT NULL DEFAULT 0,
  `attachment` int(10) unsigned NOT NULL DEFAULT 0,
  `cid` varchar(150) NOT NULL DEFAULT '',
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_MAILCIDS_MAIL_MAILS` (`mail`),
  KEY `fk_MAILCIDS_ATTACHMENT_ATTACHMENTS` (`attachment`),
  CONSTRAINT `fk_MAILCIDS_ATTACHMENT_ATTACHMENTS` FOREIGN KEY (`attachment`) REFERENCES `attachments` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_MAILCIDS_MAIL_MAILS` FOREIGN KEY (`mail`) REFERENCES `mails` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
INSERT INTO `mailcids` VALUES (1,1,1,'provaimg','2023-06-22 19:56:03',NULL);
DROP TABLE IF EXISTS `mailers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `mailers` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `cod` varchar(50) NOT NULL DEFAULT '',
  `name` varchar(50) DEFAULT NULL,
  `host` varchar(250) DEFAULT NULL,
  `port` varchar(10) DEFAULT NULL,
  `username` varchar(150) DEFAULT NULL,
  `password` varchar(50) DEFAULT NULL,
  `sendername` varchar(150) DEFAULT NULL,
  `senderemail` varchar(150) DEFAULT NULL,
  `crypttype` varchar(150) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
INSERT INTO `mailers` VALUES (1,'MAIL1','SERVER EMAIL DI TEST','smtp.gmail.com','587','giuseppesassone98@gmail.com','VnpsRldmMCtRRW42SkdQaEhNR3hUUT09','giuseppesassone98','giuseppesassone98@gmail.com','S256','2023-06-22 19:56:03',NULL);
DROP TABLE IF EXISTS `mailreceivers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `mailreceivers` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `mail` int(10) unsigned NOT NULL DEFAULT 0,
  `receivername` varchar(150) NOT NULL DEFAULT '',
  `receiveremail` varchar(150) NOT NULL DEFAULT '',
  `flgcc` int(10) unsigned NOT NULL DEFAULT 0,
  `flgccn` int(10) unsigned NOT NULL DEFAULT 0,
  `flgreaded` int(10) unsigned NOT NULL DEFAULT 0,
  `dtaread` datetime DEFAULT NULL,
  `dtareceive` datetime DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_MAILRECEIVERS_MAIL_MAILS` (`mail`),
  CONSTRAINT `fk_MAILRECEIVERS_MAIL_MAILS` FOREIGN KEY (`mail`) REFERENCES `mails` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
INSERT INTO `mailreceivers` VALUES (1,1,'destinator1','destinator1@email.it',0,0,0,NULL,'2023-06-22 19:56:03','2023-06-22 19:56:03',NULL),(2,1,'destinator2','destinator2@email.it',1,0,0,NULL,'2023-06-22 19:56:03','2023-06-22 19:56:03',NULL),(3,1,'destinator3','destinator3@email.it',0,1,0,NULL,'2023-06-22 19:56:03','2023-06-22 19:56:03',NULL);
DROP TABLE IF EXISTS `mails`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `mails` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `ipname` varchar(150) NOT NULL DEFAULT '',
  `subject` varchar(150) NOT NULL DEFAULT '',
  `sendername` varchar(150) NOT NULL DEFAULT '',
  `senderemail` varchar(150) NOT NULL DEFAULT '',
  `message` text DEFAULT NULL,
  `flgdeleted` int(10) unsigned NOT NULL DEFAULT 0,
  `dtasend` datetime DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
INSERT INTO `mails` VALUES (1,'::0','oggetto','test','test@email.it','<h1>Prova Email</h1> Questa è una mail di prova con immagine <img src=\"cid:provaimg\" width=\"30px\"/> <br/>Fine',0,'2023-06-22 19:56:03','2023-06-22 19:56:03',NULL);
DROP TABLE IF EXISTS `mimetypes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `mimetypes` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `ext` varchar(10) DEFAULT NULL,
  `value` varchar(150) DEFAULT NULL,
  `type` varchar(150) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=647 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
INSERT INTO `mimetypes` VALUES (1,'3dm','x-world/x-3dmf','x-world','2023-06-22 19:55:51',NULL),(2,'3dmf','x-world/x-3dmf','x-world','2023-06-22 19:55:51',NULL),(3,'a','application/octet-stream','application','2023-06-22 19:55:51',NULL),(4,'aab','application/x-authorware-bin','application','2023-06-22 19:55:51',NULL),(5,'aam','application/x-authorware-map','application','2023-06-22 19:55:51',NULL),(6,'aas','application/x-authorware-seg','application','2023-06-22 19:55:51',NULL),(7,'abc','text/vnd.abc','text','2023-06-22 19:55:51',NULL),(8,'acgi','text/html','text','2023-06-22 19:55:51',NULL),(9,'afl','video/animaflex','video','2023-06-22 19:55:51',NULL),(10,'ai','application/postscript','application','2023-06-22 19:55:51',NULL),(11,'aif','audio/aiff','audio','2023-06-22 19:55:51',NULL),(12,'aif','audio/x-aiff','audio','2023-06-22 19:55:51',NULL),(13,'aifc','audio/aiff','audio','2023-06-22 19:55:51',NULL),(14,'aifc','audio/x-aiff','audio','2023-06-22 19:55:51',NULL),(15,'aiff','audio/aiff','audio','2023-06-22 19:55:51',NULL),(16,'aiff','audio/x-aiff','audio','2023-06-22 19:55:51',NULL),(17,'aim','application/x-aim','application','2023-06-22 19:55:51',NULL),(18,'aip','text/x-audiosoft-intra','text','2023-06-22 19:55:51',NULL),(19,'ani','application/x-navi-animation','application','2023-06-22 19:55:51',NULL),(20,'aos','application/x-nokia-9000-communicator-add-on-software','application','2023-06-22 19:55:51',NULL),(21,'aps','application/mime','application','2023-06-22 19:55:51',NULL),(22,'arc','application/octet-stream','application','2023-06-22 19:55:51',NULL),(23,'arj','application/arj','application','2023-06-22 19:55:51',NULL),(24,'arj','application/octet-stream','application','2023-06-22 19:55:51',NULL),(25,'art','image/x-jg','image','2023-06-22 19:55:51',NULL),(26,'asf','video/x-ms-asf','video','2023-06-22 19:55:51',NULL),(27,'asm','text/x-asm','text','2023-06-22 19:55:51',NULL),(28,'asp','text/asp','text','2023-06-22 19:55:51',NULL),(29,'asx','application/x-mplayer2','application','2023-06-22 19:55:51',NULL),(30,'asx','video/x-ms-asf','video','2023-06-22 19:55:51',NULL),(31,'asx','video/x-ms-asf-plugin','video','2023-06-22 19:55:51',NULL),(32,'au','audio/basic','audio','2023-06-22 19:55:51',NULL),(33,'au','audio/x-au','audio','2023-06-22 19:55:51',NULL),(34,'avi','application/x-troff-msvideo','application','2023-06-22 19:55:51',NULL),(35,'avi','video/avi','video','2023-06-22 19:55:51',NULL),(36,'avi','video/msvideo','video','2023-06-22 19:55:51',NULL),(37,'avi','video/x-msvideo','video','2023-06-22 19:55:51',NULL),(38,'avs','video/avs-video','video','2023-06-22 19:55:51',NULL),(39,'bcpio','application/x-bcpio','application','2023-06-22 19:55:51',NULL),(40,'bin','application/mac-binary','application','2023-06-22 19:55:51',NULL),(41,'bin','application/macbinary','application','2023-06-22 19:55:51',NULL),(42,'bin','application/octet-stream','application','2023-06-22 19:55:51',NULL),(43,'bin','application/x-binary','application','2023-06-22 19:55:51',NULL),(44,'bin','application/x-macbinary','application','2023-06-22 19:55:51',NULL),(45,'bm','image/bmp','image','2023-06-22 19:55:51',NULL),(46,'bmp','image/bmp','image','2023-06-22 19:55:51',NULL),(47,'bmp','image/x-windows-bmp','image','2023-06-22 19:55:51',NULL),(48,'boo','application/book','application','2023-06-22 19:55:51',NULL),(49,'book','application/book','application','2023-06-22 19:55:51',NULL),(50,'boz','application/x-bzip2','application','2023-06-22 19:55:51',NULL),(51,'bsh','application/x-bsh','application','2023-06-22 19:55:51',NULL),(52,'bz','application/x-bzip','application','2023-06-22 19:55:51',NULL),(53,'bz2','application/x-bzip2','application','2023-06-22 19:55:51',NULL),(54,'c','text/plain','text','2023-06-22 19:55:51',NULL),(55,'c','text/x-c','text','2023-06-22 19:55:51',NULL),(56,'c++','text/plain','text','2023-06-22 19:55:51',NULL),(57,'cat','application/vnd.ms-pki.seccat','application','2023-06-22 19:55:51',NULL),(58,'cc','text/plain','text','2023-06-22 19:55:51',NULL),(59,'cc','text/x-c','text','2023-06-22 19:55:51',NULL),(60,'ccad','application/clariscad','application','2023-06-22 19:55:51',NULL),(61,'cco','application/x-cocoa','application','2023-06-22 19:55:51',NULL),(62,'cdf','application/cdf','application','2023-06-22 19:55:51',NULL),(63,'cdf','application/x-cdf','application','2023-06-22 19:55:51',NULL),(64,'cdf','application/x-netcdf','application','2023-06-22 19:55:51',NULL),(65,'cer','application/pkix-cert','application','2023-06-22 19:55:51',NULL),(66,'cer','application/x-x509-ca-cert','application','2023-06-22 19:55:51',NULL),(67,'cha','application/x-chat','application','2023-06-22 19:55:51',NULL),(68,'chat','application/x-chat','application','2023-06-22 19:55:51',NULL),(69,'class','application/java','application','2023-06-22 19:55:51',NULL),(70,'class','application/java-byte-code','application','2023-06-22 19:55:51',NULL),(71,'class','application/x-java-class','application','2023-06-22 19:55:51',NULL),(72,'com','application/octet-stream','application','2023-06-22 19:55:51',NULL),(73,'com','text/plain','text','2023-06-22 19:55:51',NULL),(74,'conf','text/plain','text','2023-06-22 19:55:51',NULL),(75,'cpio','application/x-cpio','application','2023-06-22 19:55:51',NULL),(76,'cpp','text/x-c','text','2023-06-22 19:55:51',NULL),(77,'cpt','application/mac-compactpro','application','2023-06-22 19:55:51',NULL),(78,'cpt','application/x-compactpro','application','2023-06-22 19:55:51',NULL),(79,'cpt','application/x-cpt','application','2023-06-22 19:55:51',NULL),(80,'crl','application/pkcs-crl','application','2023-06-22 19:55:51',NULL),(81,'crl','application/pkix-crl','application','2023-06-22 19:55:51',NULL),(82,'crt','application/pkix-cert','application','2023-06-22 19:55:51',NULL),(83,'crt','application/x-x509-ca-cert','application','2023-06-22 19:55:51',NULL),(84,'crt','application/x-x509-user-cert','application','2023-06-22 19:55:51',NULL),(85,'csh','application/x-csh','application','2023-06-22 19:55:51',NULL),(86,'csh','text/x-script.csh','text','2023-06-22 19:55:51',NULL),(87,'css','application/x-pointplus','application','2023-06-22 19:55:51',NULL),(88,'css','text/css','text','2023-06-22 19:55:51',NULL),(89,'cxx','text/plain','text','2023-06-22 19:55:51',NULL),(90,'dcr','application/x-director','application','2023-06-22 19:55:51',NULL),(91,'deepv','application/x-deepv','application','2023-06-22 19:55:51',NULL),(92,'def','text/plain','text','2023-06-22 19:55:51',NULL),(93,'der','application/x-x509-ca-cert','application','2023-06-22 19:55:51',NULL),(94,'dif','video/x-dv','video','2023-06-22 19:55:51',NULL),(95,'dir','application/x-director','application','2023-06-22 19:55:51',NULL),(96,'dl','video/dl','video','2023-06-22 19:55:51',NULL),(97,'dl','video/x-dl','video','2023-06-22 19:55:51',NULL),(98,'doc','application/msword','application','2023-06-22 19:55:51',NULL),(99,'dot','application/msword','application','2023-06-22 19:55:51',NULL),(100,'dp','application/commonground','application','2023-06-22 19:55:51',NULL),(101,'drw','application/drafting','application','2023-06-22 19:55:51',NULL),(102,'dump','application/octet-stream','application','2023-06-22 19:55:51',NULL),(103,'dv','video/x-dv','video','2023-06-22 19:55:51',NULL),(104,'dvi','application/x-dvi','application','2023-06-22 19:55:51',NULL),(105,'dwf','drawing/x-dwf (old)','drawing','2023-06-22 19:55:51',NULL),(106,'dwf','model/vnd.dwf','model','2023-06-22 19:55:51',NULL),(107,'dwg','application/acad','application','2023-06-22 19:55:51',NULL),(108,'dwg','image/vnd.dwg','image','2023-06-22 19:55:51',NULL),(109,'dwg','image/x-dwg','image','2023-06-22 19:55:51',NULL),(110,'dxf','application/dxf','application','2023-06-22 19:55:51',NULL),(111,'dxf','image/vnd.dwg','image','2023-06-22 19:55:51',NULL),(112,'dxf','image/x-dwg','image','2023-06-22 19:55:51',NULL),(113,'dxr','application/x-director','application','2023-06-22 19:55:51',NULL),(114,'el','text/x-script.elisp','text','2023-06-22 19:55:51',NULL),(115,'elc','application/x-bytecode.elisp (compiled elisp)','application','2023-06-22 19:55:51',NULL),(116,'elc','application/x-elc','application','2023-06-22 19:55:51',NULL),(117,'env','application/x-envoy','application','2023-06-22 19:55:51',NULL),(118,'eps','application/postscript','application','2023-06-22 19:55:51',NULL),(119,'es','application/x-esrehber','application','2023-06-22 19:55:51',NULL),(120,'etx','text/x-setext','text','2023-06-22 19:55:51',NULL),(121,'evy','application/envoy','application','2023-06-22 19:55:51',NULL),(122,'evy','application/x-envoy','application','2023-06-22 19:55:51',NULL),(123,'exe','application/octet-stream','application','2023-06-22 19:55:51',NULL),(124,'f','text/plain','text','2023-06-22 19:55:51',NULL),(125,'f','text/x-fortran','text','2023-06-22 19:55:51',NULL),(126,'f77','text/x-fortran','text','2023-06-22 19:55:51',NULL),(127,'f90','text/plain','text','2023-06-22 19:55:51',NULL),(128,'f90','text/x-fortran','text','2023-06-22 19:55:51',NULL),(129,'fdf','application/vnd.fdf','application','2023-06-22 19:55:51',NULL),(130,'fif','application/fractals','application','2023-06-22 19:55:51',NULL),(131,'fif','image/fif','image','2023-06-22 19:55:51',NULL),(132,'fli','video/fli','video','2023-06-22 19:55:51',NULL),(133,'fli','video/x-fli','video','2023-06-22 19:55:51',NULL),(134,'flo','image/florian','image','2023-06-22 19:55:51',NULL),(135,'flx','text/vnd.fmi.flexstor','text','2023-06-22 19:55:51',NULL),(136,'fmf','video/x-atomic3d-feature','video','2023-06-22 19:55:51',NULL),(137,'for','text/plain','text','2023-06-22 19:55:51',NULL),(138,'for','text/x-fortran','text','2023-06-22 19:55:51',NULL),(139,'fpx','image/vnd.fpx','image','2023-06-22 19:55:51',NULL),(140,'fpx','image/vnd.net-fpx','image','2023-06-22 19:55:51',NULL),(141,'frl','application/freeloader','application','2023-06-22 19:55:51',NULL),(142,'funk','audio/make','audio','2023-06-22 19:55:51',NULL),(143,'g','text/plain','text','2023-06-22 19:55:51',NULL),(144,'g3','image/g3fax','image','2023-06-22 19:55:51',NULL),(145,'gif','image/gif','image','2023-06-22 19:55:51',NULL),(146,'gl','video/gl','video','2023-06-22 19:55:51',NULL),(147,'gl','video/x-gl','video','2023-06-22 19:55:51',NULL),(148,'gsd','audio/x-gsm','audio','2023-06-22 19:55:51',NULL),(149,'gsm','audio/x-gsm','audio','2023-06-22 19:55:51',NULL),(150,'gsp','application/x-gsp','application','2023-06-22 19:55:51',NULL),(151,'gss','application/x-gss','application','2023-06-22 19:55:51',NULL),(152,'gtar','application/x-gtar','application','2023-06-22 19:55:51',NULL),(153,'gz','application/x-compressed','application','2023-06-22 19:55:51',NULL),(154,'gz','application/x-gzip','application','2023-06-22 19:55:51',NULL),(155,'gzip','application/x-gzip','application','2023-06-22 19:55:51',NULL),(156,'gzip','multipart/x-gzip','multipart','2023-06-22 19:55:51',NULL),(157,'h','text/plain','text','2023-06-22 19:55:51',NULL),(158,'h','text/x-h','text','2023-06-22 19:55:51',NULL),(159,'hdf','application/x-hdf','application','2023-06-22 19:55:51',NULL),(160,'help','application/x-helpfile','application','2023-06-22 19:55:51',NULL),(161,'hgl','application/vnd.hp-hpgl','application','2023-06-22 19:55:51',NULL),(162,'hh','text/plain','text','2023-06-22 19:55:51',NULL),(163,'hh','text/x-h','text','2023-06-22 19:55:51',NULL),(164,'hlb','text/x-script','text','2023-06-22 19:55:51',NULL),(165,'hlp','application/hlp','application','2023-06-22 19:55:51',NULL),(166,'hlp','application/x-helpfile','application','2023-06-22 19:55:51',NULL),(167,'hlp','application/x-winhelp','application','2023-06-22 19:55:51',NULL),(168,'hpg','application/vnd.hp-hpgl','application','2023-06-22 19:55:51',NULL),(169,'hpgl','application/vnd.hp-hpgl','application','2023-06-22 19:55:51',NULL),(170,'hqx','application/binhex','application','2023-06-22 19:55:51',NULL),(171,'hqx','application/binhex4','application','2023-06-22 19:55:51',NULL),(172,'hqx','application/mac-binhex','application','2023-06-22 19:55:51',NULL),(173,'hqx','application/mac-binhex40','application','2023-06-22 19:55:51',NULL),(174,'hqx','application/x-binhex40','application','2023-06-22 19:55:51',NULL),(175,'hqx','application/x-mac-binhex40','application','2023-06-22 19:55:51',NULL),(176,'hta','application/hta','application','2023-06-22 19:55:51',NULL),(177,'htc','text/x-component','text','2023-06-22 19:55:51',NULL),(178,'htm','text/html','text','2023-06-22 19:55:51',NULL),(179,'html','text/html','text','2023-06-22 19:55:51',NULL),(180,'htmls','text/html','text','2023-06-22 19:55:51',NULL),(181,'htt','text/webviewhtml','text','2023-06-22 19:55:51',NULL),(182,'htx','text/html','text','2023-06-22 19:55:51',NULL),(183,'ice','x-conference/x-cooltalk','x-conference','2023-06-22 19:55:51',NULL),(184,'ico','image/x-icon','image','2023-06-22 19:55:51',NULL),(185,'idc','text/plain','text','2023-06-22 19:55:51',NULL),(186,'ief','image/ief','image','2023-06-22 19:55:51',NULL),(187,'iefs','image/ief','image','2023-06-22 19:55:51',NULL),(188,'iges','application/iges','application','2023-06-22 19:55:51',NULL),(189,'iges','model/iges','model','2023-06-22 19:55:51',NULL),(190,'igs','application/iges','application','2023-06-22 19:55:51',NULL),(191,'igs','model/iges','model','2023-06-22 19:55:51',NULL),(192,'ima','application/x-ima','application','2023-06-22 19:55:51',NULL),(193,'imap','application/x-httpd-imap','application','2023-06-22 19:55:51',NULL),(194,'inf','application/inf','application','2023-06-22 19:55:51',NULL),(195,'ins','application/x-internett-signup','application','2023-06-22 19:55:51',NULL),(196,'ip','application/x-ip2','application','2023-06-22 19:55:51',NULL),(197,'isu','video/x-isvideo','video','2023-06-22 19:55:51',NULL),(198,'it','audio/it','audio','2023-06-22 19:55:51',NULL),(199,'iv','application/x-inventor','application','2023-06-22 19:55:51',NULL),(200,'ivr','i-world/i-vrml','i-world','2023-06-22 19:55:51',NULL),(201,'ivy','application/x-livescreen','application','2023-06-22 19:55:51',NULL),(202,'jam','audio/x-jam','audio','2023-06-22 19:55:51',NULL),(203,'jav','text/plain','text','2023-06-22 19:55:51',NULL),(204,'jav','text/x-java-source','text','2023-06-22 19:55:51',NULL),(205,'java','text/plain','text','2023-06-22 19:55:51',NULL),(206,'java','text/x-java-source','text','2023-06-22 19:55:51',NULL),(207,'jcm','application/x-java-commerce','application','2023-06-22 19:55:51',NULL),(208,'jfif','image/jpeg','image','2023-06-22 19:55:51',NULL),(209,'jfif','image/pjpeg','image','2023-06-22 19:55:51',NULL),(210,'jfif-tbnl','image/jpeg','image','2023-06-22 19:55:51',NULL),(211,'jpe','image/jpeg','image','2023-06-22 19:55:51',NULL),(212,'jpe','image/pjpeg','image','2023-06-22 19:55:51',NULL),(213,'jpeg','image/jpeg','image','2023-06-22 19:55:51',NULL),(214,'jpeg','image/pjpeg','image','2023-06-22 19:55:51',NULL),(215,'jpg','image/jpeg','image','2023-06-22 19:55:51',NULL),(216,'jpg','image/pjpeg','image','2023-06-22 19:55:51',NULL),(217,'jps','image/x-jps','image','2023-06-22 19:55:51',NULL),(218,'js','application/x-javascript','application','2023-06-22 19:55:51',NULL),(219,'js','application/javascript','application','2023-06-22 19:55:51',NULL),(220,'js','application/ecmascript','application','2023-06-22 19:55:51',NULL),(221,'js','text/javascript','text','2023-06-22 19:55:51',NULL),(222,'js','text/ecmascript','text','2023-06-22 19:55:51',NULL),(223,'jut','image/jutvision','image','2023-06-22 19:55:51',NULL),(224,'kar','audio/midi','audio','2023-06-22 19:55:51',NULL),(225,'kar','music/x-karaoke','music','2023-06-22 19:55:51',NULL),(226,'ksh','application/x-ksh','application','2023-06-22 19:55:51',NULL),(227,'ksh','text/x-script.ksh','text','2023-06-22 19:55:51',NULL),(228,'la','audio/nspaudio','audio','2023-06-22 19:55:51',NULL),(229,'la','audio/x-nspaudio','audio','2023-06-22 19:55:51',NULL),(230,'lam','audio/x-liveaudio','audio','2023-06-22 19:55:51',NULL),(231,'latex','application/x-latex','application','2023-06-22 19:55:51',NULL),(232,'lha','application/lha','application','2023-06-22 19:55:51',NULL),(233,'lha','application/octet-stream','application','2023-06-22 19:55:51',NULL),(234,'lha','application/x-lha','application','2023-06-22 19:55:51',NULL),(235,'lhx','application/octet-stream','application','2023-06-22 19:55:51',NULL),(236,'list','text/plain','text','2023-06-22 19:55:51',NULL),(237,'lma','audio/nspaudio','audio','2023-06-22 19:55:51',NULL),(238,'lma','audio/x-nspaudio','audio','2023-06-22 19:55:51',NULL),(239,'log','text/plain','text','2023-06-22 19:55:51',NULL),(240,'lsp','application/x-lisp','application','2023-06-22 19:55:51',NULL),(241,'lsp','text/x-script.lisp','text','2023-06-22 19:55:51',NULL),(242,'lst','text/plain','text','2023-06-22 19:55:51',NULL),(243,'lsx','text/x-la-asf','text','2023-06-22 19:55:51',NULL),(244,'ltx','application/x-latex','application','2023-06-22 19:55:51',NULL),(245,'lzh','application/octet-stream','application','2023-06-22 19:55:51',NULL),(246,'lzh','application/x-lzh','application','2023-06-22 19:55:51',NULL),(247,'lzx','application/lzx','application','2023-06-22 19:55:51',NULL),(248,'lzx','application/octet-stream','application','2023-06-22 19:55:51',NULL),(249,'lzx','application/x-lzx','application','2023-06-22 19:55:51',NULL),(250,'m','text/plain','text','2023-06-22 19:55:51',NULL),(251,'m','text/x-m','text','2023-06-22 19:55:51',NULL),(252,'m1v','video/mpeg','video','2023-06-22 19:55:51',NULL),(253,'m2a','audio/mpeg','audio','2023-06-22 19:55:51',NULL),(254,'m2v','video/mpeg','video','2023-06-22 19:55:51',NULL),(255,'m3u','audio/x-mpequrl','audio','2023-06-22 19:55:51',NULL),(256,'man','application/x-troff-man','application','2023-06-22 19:55:51',NULL),(257,'map','application/x-navimap','application','2023-06-22 19:55:51',NULL),(258,'mar','text/plain','text','2023-06-22 19:55:51',NULL),(259,'mbd','application/mbedlet','application','2023-06-22 19:55:51',NULL),(260,'mc$','application/x-magic-cap-package-1.0','application','2023-06-22 19:55:51',NULL),(261,'mcd','application/mcad','application','2023-06-22 19:55:51',NULL),(262,'mcd','application/x-mathcad','application','2023-06-22 19:55:51',NULL),(263,'mcf','image/vasa','image','2023-06-22 19:55:51',NULL),(264,'mcf','text/mcf','text','2023-06-22 19:55:51',NULL),(265,'mcp','application/netmc','application','2023-06-22 19:55:51',NULL),(266,'me','application/x-troff-me','application','2023-06-22 19:55:51',NULL),(267,'mht','message/rfc822','message','2023-06-22 19:55:51',NULL),(268,'mhtml','message/rfc822','message','2023-06-22 19:55:51',NULL),(269,'mid','application/x-midi','application','2023-06-22 19:55:51',NULL),(270,'mid','audio/midi','audio','2023-06-22 19:55:51',NULL),(271,'mid','audio/x-mid','audio','2023-06-22 19:55:51',NULL),(272,'mid','audio/x-midi','audio','2023-06-22 19:55:51',NULL),(273,'mid','music/crescendo','music','2023-06-22 19:55:51',NULL),(274,'mid','x-music/x-midi','x-music','2023-06-22 19:55:51',NULL),(275,'midi','application/x-midi','application','2023-06-22 19:55:51',NULL),(276,'midi','audio/midi','audio','2023-06-22 19:55:51',NULL),(277,'midi','audio/x-mid','audio','2023-06-22 19:55:51',NULL),(278,'midi','audio/x-midi','audio','2023-06-22 19:55:51',NULL),(279,'midi','music/crescendo','music','2023-06-22 19:55:51',NULL),(280,'midi','x-music/x-midi','x-music','2023-06-22 19:55:51',NULL),(281,'mif','application/x-frame','application','2023-06-22 19:55:51',NULL),(282,'mif','application/x-mif','application','2023-06-22 19:55:51',NULL),(283,'mime','message/rfc822','message','2023-06-22 19:55:51',NULL),(284,'mime','www/mime','www','2023-06-22 19:55:51',NULL),(285,'mjf','audio/x-vnd.audioexplosion.mjuicemediafile','audio','2023-06-22 19:55:51',NULL),(286,'mjpg','video/x-motion-jpeg','video','2023-06-22 19:55:51',NULL),(287,'mm','application/base64','application','2023-06-22 19:55:51',NULL),(288,'mm','application/x-meme','application','2023-06-22 19:55:51',NULL),(289,'mme','application/base64','application','2023-06-22 19:55:51',NULL),(290,'mod','audio/mod','audio','2023-06-22 19:55:51',NULL),(291,'mod','audio/x-mod','audio','2023-06-22 19:55:51',NULL),(292,'moov','video/quicktime','video','2023-06-22 19:55:51',NULL),(293,'mov','video/quicktime','video','2023-06-22 19:55:51',NULL),(294,'movie','video/x-sgi-movie','video','2023-06-22 19:55:51',NULL),(295,'mp2','audio/mpeg','audio','2023-06-22 19:55:51',NULL),(296,'mp2','audio/x-mpeg','audio','2023-06-22 19:55:51',NULL),(297,'mp2','video/mpeg','video','2023-06-22 19:55:51',NULL),(298,'mp2','video/x-mpeg','video','2023-06-22 19:55:51',NULL),(299,'mp2','video/x-mpeq2a','video','2023-06-22 19:55:51',NULL),(300,'mp3','audio/mpeg3','audio','2023-06-22 19:55:51',NULL),(301,'mp3','audio/x-mpeg-3','audio','2023-06-22 19:55:51',NULL),(302,'mp3','video/mpeg','video','2023-06-22 19:55:51',NULL),(303,'mp3','video/x-mpeg','video','2023-06-22 19:55:51',NULL),(304,'mpa','audio/mpeg','audio','2023-06-22 19:55:51',NULL),(305,'mpa','video/mpeg','video','2023-06-22 19:55:51',NULL),(306,'mpc','application/x-project','application','2023-06-22 19:55:51',NULL),(307,'mpe','video/mpeg','video','2023-06-22 19:55:51',NULL),(308,'mpeg','video/mpeg','video','2023-06-22 19:55:51',NULL),(309,'mpg','audio/mpeg','audio','2023-06-22 19:55:51',NULL),(310,'mpg','video/mpeg','video','2023-06-22 19:55:51',NULL),(311,'mpga','audio/mpeg','audio','2023-06-22 19:55:51',NULL),(312,'mpp','application/vnd.ms-project','application','2023-06-22 19:55:51',NULL),(313,'mpt','application/x-project','application','2023-06-22 19:55:51',NULL),(314,'mpv','application/x-project','application','2023-06-22 19:55:51',NULL),(315,'mpx','application/x-project','application','2023-06-22 19:55:51',NULL),(316,'mrc','application/marc','application','2023-06-22 19:55:51',NULL),(317,'ms','application/x-troff-ms','application','2023-06-22 19:55:51',NULL),(318,'mv','video/x-sgi-movie','video','2023-06-22 19:55:51',NULL),(319,'my','audio/make','audio','2023-06-22 19:55:51',NULL),(320,'mzz','application/x-vnd.audioexplosion.mzz','application','2023-06-22 19:55:51',NULL),(321,'nap','image/naplps','image','2023-06-22 19:55:51',NULL),(322,'naplps','image/naplps','image','2023-06-22 19:55:51',NULL),(323,'nc','application/x-netcdf','application','2023-06-22 19:55:51',NULL),(324,'ncm','application/vnd.nokia.configuration-message','application','2023-06-22 19:55:51',NULL),(325,'nif','image/x-niff','image','2023-06-22 19:55:51',NULL),(326,'niff','image/x-niff','image','2023-06-22 19:55:51',NULL),(327,'nix','application/x-mix-transfer','application','2023-06-22 19:55:51',NULL),(328,'nsc','application/x-conference','application','2023-06-22 19:55:51',NULL),(329,'nvd','application/x-navidoc','application','2023-06-22 19:55:51',NULL),(330,'o','application/octet-stream','application','2023-06-22 19:55:51',NULL),(331,'oda','application/oda','application','2023-06-22 19:55:51',NULL),(332,'omc','application/x-omc','application','2023-06-22 19:55:51',NULL),(333,'omcd','application/x-omcdatamaker','application','2023-06-22 19:55:51',NULL),(334,'omcr','application/x-omcregerator','application','2023-06-22 19:55:51',NULL),(335,'p','text/x-pascal','text','2023-06-22 19:55:51',NULL),(336,'p10','application/pkcs10','application','2023-06-22 19:55:51',NULL),(337,'p10','application/x-pkcs10','application','2023-06-22 19:55:51',NULL),(338,'p12','application/pkcs-12','application','2023-06-22 19:55:51',NULL),(339,'p12','application/x-pkcs12','application','2023-06-22 19:55:51',NULL),(340,'p7a','application/x-pkcs7-signature','application','2023-06-22 19:55:51',NULL),(341,'p7c','application/pkcs7-mime','application','2023-06-22 19:55:51',NULL),(342,'p7c','application/x-pkcs7-mime','application','2023-06-22 19:55:51',NULL),(343,'p7m','application/pkcs7-mime','application','2023-06-22 19:55:51',NULL),(344,'p7m','application/x-pkcs7-mime','application','2023-06-22 19:55:51',NULL),(345,'p7r','application/x-pkcs7-certreqresp','application','2023-06-22 19:55:51',NULL),(346,'p7s','application/pkcs7-signature','application','2023-06-22 19:55:51',NULL),(347,'part','application/pro_eng','application','2023-06-22 19:55:51',NULL),(348,'pas','text/pascal','text','2023-06-22 19:55:51',NULL),(349,'pbm','image/x-portable-bitmap','image','2023-06-22 19:55:51',NULL),(350,'pcl','application/vnd.hp-pcl','application','2023-06-22 19:55:51',NULL),(351,'pcl','application/x-pcl','application','2023-06-22 19:55:51',NULL),(352,'pct','image/x-pict','image','2023-06-22 19:55:51',NULL),(353,'pcx','image/x-pcx','image','2023-06-22 19:55:51',NULL),(354,'pdb','chemical/x-pdb','chemical','2023-06-22 19:55:51',NULL),(355,'pdf','application/pdf','application','2023-06-22 19:55:51',NULL),(356,'pfunk','audio/make','audio','2023-06-22 19:55:51',NULL),(357,'pfunk','audio/make.my.funk','audio','2023-06-22 19:55:51',NULL),(358,'pgm','image/x-portable-graymap','image','2023-06-22 19:55:51',NULL),(359,'pgm','image/x-portable-greymap','image','2023-06-22 19:55:51',NULL),(360,'pic','image/pict','image','2023-06-22 19:55:51',NULL),(361,'pict','image/pict','image','2023-06-22 19:55:51',NULL),(362,'pkg','application/x-newton-compatible-pkg','application','2023-06-22 19:55:51',NULL),(363,'pko','application/vnd.ms-pki.pko','application','2023-06-22 19:55:51',NULL),(364,'pl','text/plain','text','2023-06-22 19:55:51',NULL),(365,'pl','text/x-script.perl','text','2023-06-22 19:55:51',NULL),(366,'plx','application/x-pixclscript','application','2023-06-22 19:55:51',NULL),(367,'pm','image/x-xpixmap','image','2023-06-22 19:55:51',NULL),(368,'pm','text/x-script.perl-module','text','2023-06-22 19:55:51',NULL),(369,'pm4','application/x-pagemaker','application','2023-06-22 19:55:51',NULL),(370,'pm5','application/x-pagemaker','application','2023-06-22 19:55:51',NULL),(371,'png','image/png','image','2023-06-22 19:55:51',NULL),(372,'pnm','application/x-portable-anymap','application','2023-06-22 19:55:51',NULL),(373,'pnm','image/x-portable-anymap','image','2023-06-22 19:55:51',NULL),(374,'pot','application/mspowerpoint','application','2023-06-22 19:55:51',NULL),(375,'pot','application/vnd.ms-powerpoint','application','2023-06-22 19:55:51',NULL),(376,'pov','model/x-pov','model','2023-06-22 19:55:51',NULL),(377,'ppa','application/vnd.ms-powerpoint','application','2023-06-22 19:55:51',NULL),(378,'ppm','image/x-portable-pixmap','image','2023-06-22 19:55:51',NULL),(379,'pps','application/mspowerpoint','application','2023-06-22 19:55:51',NULL),(380,'pps','application/vnd.ms-powerpoint','application','2023-06-22 19:55:51',NULL),(381,'ppt','application/mspowerpoint','application','2023-06-22 19:55:51',NULL),(382,'ppt','application/powerpoint','application','2023-06-22 19:55:51',NULL),(383,'ppt','application/vnd.ms-powerpoint','application','2023-06-22 19:55:51',NULL),(384,'ppt','application/x-mspowerpoint','application','2023-06-22 19:55:51',NULL),(385,'ppz','application/mspowerpoint','application','2023-06-22 19:55:51',NULL),(386,'pre','application/x-freelance','application','2023-06-22 19:55:51',NULL),(387,'prt','application/pro_eng','application','2023-06-22 19:55:51',NULL),(388,'ps','application/postscript','application','2023-06-22 19:55:51',NULL),(389,'psd','application/octet-stream','application','2023-06-22 19:55:51',NULL),(390,'pvu','paleovu/x-pv','paleovu','2023-06-22 19:55:51',NULL),(391,'pwz','application/vnd.ms-powerpoint','application','2023-06-22 19:55:51',NULL),(392,'py','text/x-script.phyton','text','2023-06-22 19:55:51',NULL),(393,'pyc','application/x-bytecode.python','application','2023-06-22 19:55:51',NULL),(394,'qcp','audio/vnd.qcelp','audio','2023-06-22 19:55:51',NULL),(395,'qd3','x-world/x-3dmf','x-world','2023-06-22 19:55:51',NULL),(396,'qd3d','x-world/x-3dmf','x-world','2023-06-22 19:55:51',NULL),(397,'qif','image/x-quicktime','image','2023-06-22 19:55:51',NULL),(398,'qt','video/quicktime','video','2023-06-22 19:55:51',NULL),(399,'qtc','video/x-qtc','video','2023-06-22 19:55:51',NULL),(400,'qti','image/x-quicktime','image','2023-06-22 19:55:51',NULL),(401,'qtif','image/x-quicktime','image','2023-06-22 19:55:51',NULL),(402,'ra','audio/x-pn-realaudio','audio','2023-06-22 19:55:51',NULL),(403,'ra','audio/x-pn-realaudio-plugin','audio','2023-06-22 19:55:51',NULL),(404,'ra','audio/x-realaudio','audio','2023-06-22 19:55:51',NULL),(405,'ram','audio/x-pn-realaudio','audio','2023-06-22 19:55:51',NULL),(406,'ras','application/x-cmu-raster','application','2023-06-22 19:55:51',NULL),(407,'ras','image/cmu-raster','image','2023-06-22 19:55:51',NULL),(408,'ras','image/x-cmu-raster','image','2023-06-22 19:55:51',NULL),(409,'rast','image/cmu-raster','image','2023-06-22 19:55:51',NULL),(410,'rexx','text/x-script.rexx','text','2023-06-22 19:55:51',NULL),(411,'rf','image/vnd.rn-realflash','image','2023-06-22 19:55:51',NULL),(412,'rgb','image/x-rgb','image','2023-06-22 19:55:51',NULL),(413,'rm','application/vnd.rn-realmedia','application','2023-06-22 19:55:51',NULL),(414,'rm','audio/x-pn-realaudio','audio','2023-06-22 19:55:51',NULL),(415,'rmi','audio/mid','audio','2023-06-22 19:55:51',NULL),(416,'rmm','audio/x-pn-realaudio','audio','2023-06-22 19:55:51',NULL),(417,'rmp','audio/x-pn-realaudio','audio','2023-06-22 19:55:51',NULL),(418,'rmp','audio/x-pn-realaudio-plugin','audio','2023-06-22 19:55:51',NULL),(419,'rng','application/ringing-tones','application','2023-06-22 19:55:51',NULL),(420,'rng','application/vnd.nokia.ringing-tone','application','2023-06-22 19:55:51',NULL),(421,'rnx','application/vnd.rn-realplayer','application','2023-06-22 19:55:51',NULL),(422,'roff','application/x-troff','application','2023-06-22 19:55:51',NULL),(423,'rp','image/vnd.rn-realpix','image','2023-06-22 19:55:51',NULL),(424,'rpm','audio/x-pn-realaudio-plugin','audio','2023-06-22 19:55:51',NULL),(425,'rt','text/richtext','text','2023-06-22 19:55:51',NULL),(426,'rt','text/vnd.rn-realtext','text','2023-06-22 19:55:51',NULL),(427,'rtf','application/rtf','application','2023-06-22 19:55:51',NULL),(428,'rtf','application/x-rtf','application','2023-06-22 19:55:51',NULL),(429,'rtf','text/richtext','text','2023-06-22 19:55:51',NULL),(430,'rtx','application/rtf','application','2023-06-22 19:55:51',NULL),(431,'rtx','text/richtext','text','2023-06-22 19:55:51',NULL),(432,'rv','video/vnd.rn-realvideo','video','2023-06-22 19:55:51',NULL),(433,'s','text/x-asm','text','2023-06-22 19:55:51',NULL),(434,'s3m','audio/s3m','audio','2023-06-22 19:55:51',NULL),(435,'saveme','application/octet-stream','application','2023-06-22 19:55:51',NULL),(436,'sbk','application/x-tbook','application','2023-06-22 19:55:51',NULL),(437,'scm','application/x-lotusscreencam','application','2023-06-22 19:55:51',NULL),(438,'scm','text/x-script.guile','text','2023-06-22 19:55:51',NULL),(439,'scm','text/x-script.scheme','text','2023-06-22 19:55:51',NULL),(440,'scm','video/x-scm','video','2023-06-22 19:55:51',NULL),(441,'sdml','text/plain','text','2023-06-22 19:55:51',NULL),(442,'sdp','application/sdp','application','2023-06-22 19:55:51',NULL),(443,'sdp','application/x-sdp','application','2023-06-22 19:55:51',NULL),(444,'sdr','application/sounder','application','2023-06-22 19:55:51',NULL),(445,'sea','application/sea','application','2023-06-22 19:55:51',NULL),(446,'sea','application/x-sea','application','2023-06-22 19:55:51',NULL),(447,'set','application/set','application','2023-06-22 19:55:51',NULL),(448,'sgm','text/sgml','text','2023-06-22 19:55:51',NULL),(449,'sgm','text/x-sgml','text','2023-06-22 19:55:51',NULL),(450,'sgml','text/sgml','text','2023-06-22 19:55:51',NULL),(451,'sgml','text/x-sgml','text','2023-06-22 19:55:51',NULL),(452,'sh','application/x-bsh','application','2023-06-22 19:55:51',NULL),(453,'sh','application/x-sh','application','2023-06-22 19:55:51',NULL),(454,'sh','application/x-shar','application','2023-06-22 19:55:51',NULL),(455,'sh','text/x-script.sh','text','2023-06-22 19:55:51',NULL),(456,'shar','application/x-bsh','application','2023-06-22 19:55:51',NULL),(457,'shar','application/x-shar','application','2023-06-22 19:55:51',NULL),(458,'shtml','text/html','text','2023-06-22 19:55:51',NULL),(459,'shtml','text/x-server-parsed-html','text','2023-06-22 19:55:51',NULL),(460,'sid','audio/x-psid','audio','2023-06-22 19:55:51',NULL),(461,'sit','application/x-sit','application','2023-06-22 19:55:51',NULL),(462,'sit','application/x-stuffit','application','2023-06-22 19:55:51',NULL),(463,'skd','application/x-koan','application','2023-06-22 19:55:51',NULL),(464,'skm','application/x-koan','application','2023-06-22 19:55:51',NULL),(465,'skp','application/x-koan','application','2023-06-22 19:55:51',NULL),(466,'skt','application/x-koan','application','2023-06-22 19:55:51',NULL),(467,'sl','application/x-seelogo','application','2023-06-22 19:55:51',NULL),(468,'smi','application/smil','application','2023-06-22 19:55:51',NULL),(469,'smil','application/smil','application','2023-06-22 19:55:51',NULL),(470,'snd','audio/basic','audio','2023-06-22 19:55:51',NULL),(471,'snd','audio/x-adpcm','audio','2023-06-22 19:55:51',NULL),(472,'sol','application/solids','application','2023-06-22 19:55:51',NULL),(473,'spc','application/x-pkcs7-certificates','application','2023-06-22 19:55:51',NULL),(474,'spc','text/x-speech','text','2023-06-22 19:55:51',NULL),(475,'spl','application/futuresplash','application','2023-06-22 19:55:51',NULL),(476,'spr','application/x-sprite','application','2023-06-22 19:55:51',NULL),(477,'sprite','application/x-sprite','application','2023-06-22 19:55:51',NULL),(478,'src','application/x-wais-source','application','2023-06-22 19:55:51',NULL),(479,'ssi','text/x-server-parsed-html','text','2023-06-22 19:55:51',NULL),(480,'ssm','application/streamingmedia','application','2023-06-22 19:55:51',NULL),(481,'sst','application/vnd.ms-pki.certstore','application','2023-06-22 19:55:51',NULL),(482,'step','application/step','application','2023-06-22 19:55:51',NULL),(483,'stl','application/sla','application','2023-06-22 19:55:51',NULL),(484,'stl','application/vnd.ms-pki.stl','application','2023-06-22 19:55:51',NULL),(485,'stl','application/x-navistyle','application','2023-06-22 19:55:51',NULL),(486,'stp','application/step','application','2023-06-22 19:55:51',NULL),(487,'sv4cpio','application/x-sv4cpio','application','2023-06-22 19:55:51',NULL),(488,'sv4crc','application/x-sv4crc','application','2023-06-22 19:55:51',NULL),(489,'svf','image/vnd.dwg','image','2023-06-22 19:55:51',NULL),(490,'svf','image/x-dwg','image','2023-06-22 19:55:51',NULL),(491,'svr','application/x-world','application','2023-06-22 19:55:51',NULL),(492,'svr','x-world/x-svr','x-world','2023-06-22 19:55:51',NULL),(493,'swf','application/x-shockwave-flash','application','2023-06-22 19:55:51',NULL),(494,'t','application/x-troff','application','2023-06-22 19:55:51',NULL),(495,'talk','text/x-speech','text','2023-06-22 19:55:51',NULL),(496,'tar','application/x-tar','application','2023-06-22 19:55:51',NULL),(497,'tbk','application/toolbook','application','2023-06-22 19:55:51',NULL),(498,'tbk','application/x-tbook','application','2023-06-22 19:55:51',NULL),(499,'tcl','application/x-tcl','application','2023-06-22 19:55:51',NULL),(500,'tcl','text/x-script.tcl','text','2023-06-22 19:55:51',NULL),(501,'tcsh','text/x-script.tcsh','text','2023-06-22 19:55:51',NULL),(502,'tex','application/x-tex','application','2023-06-22 19:55:51',NULL),(503,'texi','application/x-texinfo','application','2023-06-22 19:55:51',NULL),(504,'texinfo','application/x-texinfo','application','2023-06-22 19:55:51',NULL),(505,'text','application/plain','application','2023-06-22 19:55:51',NULL),(506,'text','text/plain','text','2023-06-22 19:55:51',NULL),(507,'tgz','application/gnutar','application','2023-06-22 19:55:51',NULL),(508,'tgz','application/x-compressed','application','2023-06-22 19:55:51',NULL),(509,'tif','image/tiff','image','2023-06-22 19:55:51',NULL),(510,'tif','image/x-tiff','image','2023-06-22 19:55:51',NULL),(511,'tiff','image/tiff','image','2023-06-22 19:55:51',NULL),(512,'tiff','image/x-tiff','image','2023-06-22 19:55:51',NULL),(513,'tr','application/x-troff','application','2023-06-22 19:55:51',NULL),(514,'tsi','audio/tsp-audio','audio','2023-06-22 19:55:51',NULL),(515,'tsp','application/dsptype','application','2023-06-22 19:55:51',NULL),(516,'tsp','audio/tsplayer','audio','2023-06-22 19:55:51',NULL),(517,'tsv','text/tab-separated-values','text','2023-06-22 19:55:51',NULL),(518,'turbot','image/florian','image','2023-06-22 19:55:51',NULL),(519,'txt','text/plain','text','2023-06-22 19:55:51',NULL),(520,'uil','text/x-uil','text','2023-06-22 19:55:51',NULL),(521,'uni','text/uri-list','text','2023-06-22 19:55:51',NULL),(522,'unis','text/uri-list','text','2023-06-22 19:55:51',NULL),(523,'unv','application/i-deas','application','2023-06-22 19:55:51',NULL),(524,'uri','text/uri-list','text','2023-06-22 19:55:51',NULL),(525,'uris','text/uri-list','text','2023-06-22 19:55:51',NULL),(526,'ustar','application/x-ustar','application','2023-06-22 19:55:51',NULL),(527,'ustar','multipart/x-ustar','multipart','2023-06-22 19:55:51',NULL),(528,'uu','application/octet-stream','application','2023-06-22 19:55:51',NULL),(529,'uu','text/x-uuencode','text','2023-06-22 19:55:51',NULL),(530,'uue','text/x-uuencode','text','2023-06-22 19:55:51',NULL),(531,'vcd','application/x-cdlink','application','2023-06-22 19:55:51',NULL),(532,'vcs','text/x-vcalendar','text','2023-06-22 19:55:51',NULL),(533,'vda','application/vda','application','2023-06-22 19:55:51',NULL),(534,'vdo','video/vdo','video','2023-06-22 19:55:51',NULL),(535,'vew','application/groupwise','application','2023-06-22 19:55:51',NULL),(536,'viv','video/vivo','video','2023-06-22 19:55:51',NULL),(537,'viv','video/vnd.vivo','video','2023-06-22 19:55:51',NULL),(538,'vivo','video/vivo','video','2023-06-22 19:55:51',NULL),(539,'vivo','video/vnd.vivo','video','2023-06-22 19:55:51',NULL),(540,'vmd','application/vocaltec-media-desc','application','2023-06-22 19:55:51',NULL),(541,'vmf','application/vocaltec-media-file','application','2023-06-22 19:55:51',NULL),(542,'voc','audio/voc','audio','2023-06-22 19:55:51',NULL),(543,'voc','audio/x-voc','audio','2023-06-22 19:55:51',NULL),(544,'vos','video/vosaic','video','2023-06-22 19:55:51',NULL),(545,'vox','audio/voxware','audio','2023-06-22 19:55:51',NULL),(546,'vqe','audio/x-twinvq-plugin','audio','2023-06-22 19:55:51',NULL),(547,'vqf','audio/x-twinvq','audio','2023-06-22 19:55:51',NULL),(548,'vql','audio/x-twinvq-plugin','audio','2023-06-22 19:55:51',NULL),(549,'vrml','application/x-vrml','application','2023-06-22 19:55:51',NULL),(550,'vrml','model/vrml','model','2023-06-22 19:55:51',NULL),(551,'vrml','x-world/x-vrml','x-world','2023-06-22 19:55:51',NULL),(552,'vrt','x-world/x-vrt','x-world','2023-06-22 19:55:51',NULL),(553,'vsd','application/x-visio','application','2023-06-22 19:55:51',NULL),(554,'vst','application/x-visio','application','2023-06-22 19:55:51',NULL),(555,'vsw','application/x-visio','application','2023-06-22 19:55:51',NULL),(556,'w60','application/wordperfect6.0','application','2023-06-22 19:55:51',NULL),(557,'w61','application/wordperfect6.1','application','2023-06-22 19:55:51',NULL),(558,'w6w','application/msword','application','2023-06-22 19:55:51',NULL),(559,'wav','audio/wav','audio','2023-06-22 19:55:51',NULL),(560,'wav','audio/x-wav','audio','2023-06-22 19:55:51',NULL),(561,'wb1','application/x-qpro','application','2023-06-22 19:55:51',NULL),(562,'wbmp','image/vnd.wap.wbmp','image','2023-06-22 19:55:51',NULL),(563,'web','application/vnd.xara','application','2023-06-22 19:55:51',NULL),(564,'wiz','application/msword','application','2023-06-22 19:55:51',NULL),(565,'wk1','application/x-123','application','2023-06-22 19:55:51',NULL),(566,'wmf','windows/metafile','windows','2023-06-22 19:55:51',NULL),(567,'wml','text/vnd.wap.wml','text','2023-06-22 19:55:51',NULL),(568,'wmlc','application/vnd.wap.wmlc','application','2023-06-22 19:55:51',NULL),(569,'wmls','text/vnd.wap.wmlscript','text','2023-06-22 19:55:51',NULL),(570,'wmlsc','application/vnd.wap.wmlscriptc','application','2023-06-22 19:55:51',NULL),(571,'word','application/msword','application','2023-06-22 19:55:51',NULL),(572,'wp','application/wordperfect','application','2023-06-22 19:55:51',NULL),(573,'wp5','application/wordperfect','application','2023-06-22 19:55:51',NULL),(574,'wp5','application/wordperfect6.0','application','2023-06-22 19:55:51',NULL),(575,'wp6','application/wordperfect','application','2023-06-22 19:55:51',NULL),(576,'wpd','application/wordperfect','application','2023-06-22 19:55:51',NULL),(577,'wpd','application/x-wpwin','application','2023-06-22 19:55:51',NULL),(578,'wq1','application/x-lotus','application','2023-06-22 19:55:51',NULL),(579,'wri','application/mswrite','application','2023-06-22 19:55:51',NULL),(580,'wri','application/x-wri','application','2023-06-22 19:55:51',NULL),(581,'wrl','application/x-world','application','2023-06-22 19:55:51',NULL),(582,'wrl','model/vrml','model','2023-06-22 19:55:51',NULL),(583,'wrl','x-world/x-vrml','x-world','2023-06-22 19:55:51',NULL),(584,'wrz','model/vrml','model','2023-06-22 19:55:51',NULL),(585,'wrz','x-world/x-vrml','x-world','2023-06-22 19:55:51',NULL),(586,'wsc','text/scriplet','text','2023-06-22 19:55:51',NULL),(587,'wsrc','application/x-wais-source','application','2023-06-22 19:55:51',NULL),(588,'wtk','application/x-wintalk','application','2023-06-22 19:55:51',NULL),(589,'xbm','image/x-xbitmap','image','2023-06-22 19:55:51',NULL),(590,'xbm','image/x-xbm','image','2023-06-22 19:55:51',NULL),(591,'xbm','image/xbm','image','2023-06-22 19:55:51',NULL),(592,'xdr','video/x-amt-demorun','video','2023-06-22 19:55:51',NULL),(593,'xgz','xgl/drawing','xgl','2023-06-22 19:55:51',NULL),(594,'xif','image/vnd.xiff','image','2023-06-22 19:55:51',NULL),(595,'xl','application/excel','application','2023-06-22 19:55:51',NULL),(596,'xla','application/excel','application','2023-06-22 19:55:51',NULL),(597,'xla','application/x-excel','application','2023-06-22 19:55:51',NULL),(598,'xla','application/x-msexcel','application','2023-06-22 19:55:51',NULL),(599,'xlb','application/excel','application','2023-06-22 19:55:51',NULL),(600,'xlb','application/vnd.ms-excel','application','2023-06-22 19:55:51',NULL),(601,'xlb','application/x-excel','application','2023-06-22 19:55:51',NULL),(602,'xlc','application/excel','application','2023-06-22 19:55:51',NULL),(603,'xlc','application/vnd.ms-excel','application','2023-06-22 19:55:51',NULL),(604,'xlc','application/x-excel','application','2023-06-22 19:55:51',NULL),(605,'xld','application/excel','application','2023-06-22 19:55:51',NULL),(606,'xld','application/x-excel','application','2023-06-22 19:55:51',NULL),(607,'xlk','application/excel','application','2023-06-22 19:55:51',NULL),(608,'xlk','application/x-excel','application','2023-06-22 19:55:51',NULL),(609,'xll','application/excel','application','2023-06-22 19:55:51',NULL),(610,'xll','application/vnd.ms-excel','application','2023-06-22 19:55:51',NULL),(611,'xll','application/x-excel','application','2023-06-22 19:55:51',NULL),(612,'xlm','application/excel','application','2023-06-22 19:55:51',NULL),(613,'xlm','application/vnd.ms-excel','application','2023-06-22 19:55:51',NULL),(614,'xlm','application/x-excel','application','2023-06-22 19:55:51',NULL),(615,'xls','application/excel','application','2023-06-22 19:55:51',NULL),(616,'xls','application/vnd.ms-excel','application','2023-06-22 19:55:51',NULL),(617,'xls','application/x-excel','application','2023-06-22 19:55:51',NULL),(618,'xls','application/x-msexcel','application','2023-06-22 19:55:51',NULL),(619,'xlt','application/excel','application','2023-06-22 19:55:51',NULL),(620,'xlt','application/x-excel','application','2023-06-22 19:55:51',NULL),(621,'xlv','application/excel','application','2023-06-22 19:55:51',NULL),(622,'xlv','application/x-excel','application','2023-06-22 19:55:51',NULL),(623,'xlw','application/excel','application','2023-06-22 19:55:51',NULL),(624,'xlw','application/vnd.ms-excel','application','2023-06-22 19:55:51',NULL),(625,'xlw','application/x-excel','application','2023-06-22 19:55:51',NULL),(626,'xlw','application/x-msexcel','application','2023-06-22 19:55:51',NULL),(627,'xm','audio/xm','audio','2023-06-22 19:55:51',NULL),(628,'xml','application/xml','application','2023-06-22 19:55:51',NULL),(629,'xml','text/xml','text','2023-06-22 19:55:51',NULL),(630,'xmz','xgl/movie','xgl','2023-06-22 19:55:51',NULL),(631,'xpix','application/x-vnd.ls-xpix','application','2023-06-22 19:55:51',NULL),(632,'xpm','image/x-xpixmap','image','2023-06-22 19:55:51',NULL),(633,'xpm','image/xpm','image','2023-06-22 19:55:51',NULL),(634,'x-png','image/png','image','2023-06-22 19:55:51',NULL),(635,'xsr','video/x-amt-showrun','video','2023-06-22 19:55:51',NULL),(636,'xwd','image/x-xwd','image','2023-06-22 19:55:51',NULL),(637,'xwd','image/x-xwindowdump','image','2023-06-22 19:55:51',NULL),(638,'xyz','chemical/x-pdb','chemical','2023-06-22 19:55:51',NULL),(639,'z','application/x-compress','application','2023-06-22 19:55:51',NULL),(640,'z','application/x-compressed','application','2023-06-22 19:55:51',NULL),(641,'zip','application/x-compressed','application','2023-06-22 19:55:51',NULL),(642,'zip','application/x-zip-compressed','application','2023-06-22 19:55:51',NULL),(643,'zip','application/zip','application','2023-06-22 19:55:51',NULL),(644,'zip','multipart/x-zip','multipart','2023-06-22 19:55:51',NULL),(645,'zoo','application/octet-stream','application','2023-06-22 19:55:51',NULL),(646,'zsh','text/x-script.zsh','text','2023-06-22 19:55:51',NULL);
DROP TABLE IF EXISTS `multilanguages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `multilanguages` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `tablename` varchar(50) NOT NULL DEFAULT '',
  `fieldname` varchar(50) NOT NULL DEFAULT '',
  `content` text DEFAULT '',
  `objraw` int(10) unsigned NOT NULL DEFAULT 0,
  `languageid` int(10) unsigned NOT NULL DEFAULT 0,
  `languagecod` varchar(50) NOT NULL DEFAULT '',
  `type` varchar(50) NOT NULL DEFAULT 'INPUT_TEXT',
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_MULTILANGUAGES_LANGUAGE_LANGUAGES` (`languageid`),
  CONSTRAINT `fk_MULTILANGUAGES_LANGUAGE_LANGUAGES` FOREIGN KEY (`languageid`) REFERENCES `languages` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
INSERT INTO `multilanguages` VALUES (1,'addresses','cod','Parlamento Italiano',1,0,'ITA','INPUT_TEXT','2023-06-22 19:56:30',NULL),(2,'addresses','cod','Italian Parlment',1,0,'ENG','INPUT_TEXT','2023-06-22 19:56:30',NULL);
DROP TABLE IF EXISTS `nationlanguages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `nationlanguages` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `nation` int(10) unsigned NOT NULL DEFAULT 0,
  `languageid` int(10) unsigned NOT NULL DEFAULT 0,
  `val` varchar(150) NOT NULL DEFAULT '',
  `flgused` int(10) unsigned NOT NULL DEFAULT 1,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_NATIONLANGUAGES_NATION_NATIONS` (`nation`),
  KEY `fk_NATIONLANGUAGES_LANGUAGE_LANGUAGES` (`languageid`),
  CONSTRAINT `fk_NATIONLANGUAGES_LANGUAGE_LANGUAGES` FOREIGN KEY (`languageid`) REFERENCES `languages` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_NATIONLANGUAGES_NATION_NATIONS` FOREIGN KEY (`nation`) REFERENCES `nations` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `nations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `nations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `cod` varchar(50) NOT NULL DEFAULT '',
  `name` varchar(150) NOT NULL DEFAULT '',
  `description` varchar(150) NOT NULL DEFAULT '',
  `capital` varchar(150) NOT NULL DEFAULT '',
  `continent` varchar(10) DEFAULT '',
  `currencycod` varchar(10) DEFAULT '',
  `tld` varchar(10) DEFAULT '',
  `type` varchar(5) NOT NULL DEFAULT '',
  `cod_iso3166` varchar(10) NOT NULL DEFAULT '',
  `geo1` varchar(50) DEFAULT '',
  `geo2` varchar(50) DEFAULT '',
  `tel` varchar(10) DEFAULT '',
  `flgiban` int(10) unsigned NOT NULL DEFAULT 0,
  `flgused` int(10) unsigned NOT NULL DEFAULT 1,
  `priority` int(10) unsigned NOT NULL DEFAULT 0,
  `symbol` varchar(250) NOT NULL DEFAULT '',
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=257 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
INSERT INTO `nations` VALUES (1,'ITA','Italy','Italia','Rome','EU','EUR','.it','E','IT','41.87194','12.56738','+39',1,1,2,'Italy.svg','2023-06-22 19:56:33',NULL),(2,'ALB','Albania','Albania','Tirana','EU','ALL','.al','X','AL','41.153332','20.168331','+355',1,1,0,'Albania.svg','2023-06-22 19:56:33',NULL),(3,'DZA','Algeria','Algeria','Algiers','AF','DZD','.dz','X','DZ','28.033886','1.659626','+213',0,1,0,'Algeria.svg','2023-06-22 19:56:33',NULL),(4,'AND','Andorra','Andorra','Andorra la Vella','EU','EUR','.ad','X','AD','42.546245','1.601554','+376',1,1,0,'Andorra.svg','2023-06-22 19:56:33',NULL),(5,'AGO','Angola','Angola','Luanda','AF','AOA','.ao','X','AO','-11.202692','17.873887','+244',0,1,0,'Angola.svg','2023-06-22 19:56:33',NULL),(6,'ATG','Antigua and Barbuda','Antigua e Barbuda','St. John\'s','NA','XCD','.ag','X','AG','17.060816','-61.796428','+1-268',0,1,0,'Antigua_and_Barbuda.svg','2023-06-22 19:56:33',NULL),(7,'AIA','Anguilla','Antille Olandesi','The Valley','NA','XCD','.ai','X','AI','18.220554','-63.068615','+1-264',0,1,0,'Anguilla.svg','2023-06-22 19:56:33',NULL),(8,'SAU','Saudi Arabia','Arabia Saudita','Riyadh','AS','SAR','.sa','X','SA','23.885942','45.079162','+966',1,1,0,'Saudi_Arabia.svg','2023-06-22 19:56:33',NULL),(9,'ATG','Antigua and Barbuda','Argentina','St. John\'s','NA','XCD','.ag','X','AG','17.060816','-61.796428','+1-268',0,1,0,'Antigua_and_Barbuda.svg','2023-06-22 19:56:33',NULL),(10,'ARM','Armenia','Armenia','Yerevan','AS','AMD','.am','X','AM','40.069099','45.038189','+374',0,1,0,'Armenia.svg','2023-06-22 19:56:33',NULL),(11,'AUS','Australia','Australia','Canberra','OC','AUD','.au','X','AU','-25.274398','133.775136','+61',0,1,0,'Australia.svg','2023-06-22 19:56:33',NULL),(12,'AZE','Azerbaijan','Azerbaigian','Baku','AS','AZN','.az','X','AZ','40.143105','47.576927','+994',1,1,0,'Azerbaijan.svg','2023-06-22 19:56:33',NULL),(13,'BHS','Bahamas','Bahamas','Nassau','NA','BSD','.bs','X','BS','25.03428','-77.39628','+1-242',0,1,0,'Bahamas.svg','2023-06-22 19:56:33',NULL),(14,'BHR','Bahrain','Bahrein','Manama','AS','BHD','.bh','X','BH','25.930414','50.637772','+973',1,1,0,'Bahrain.svg','2023-06-22 19:56:33',NULL),(15,'BGD','Bangladesh','Bangladesh','Dhaka','AS','BDT','.bd','X','BD','23.684994','90.356331','+880',0,1,0,'Bangladesh.svg','2023-06-22 19:56:33',NULL),(16,'BRB','Barbados','Barbados','Bridgetown','NA','BBD','.bb','X','BB','13.193887','-59.543198','+1-246',0,1,0,'Barbados.svg','2023-06-22 19:56:33',NULL),(17,'BLZ','Belize','Belize','Belmopan','NA','BZD','.bz','X','BZ','17.189877','-88.49765','+501',0,1,0,'Belize.svg','2023-06-22 19:56:33',NULL),(18,'BEN','Benin','Benin','Porto-Novo','AF','XOF','.bj','X','BJ','9.30769','2.315834','+229',0,1,0,'Benin.svg','2023-06-22 19:56:33',NULL),(19,'BTN','Bhutan','Bhutan','Thimphu','AS','BTN','.bt','X','BT','27.514162','90.433601','+975',0,1,0,'Bhutan.svg','2023-06-22 19:56:33',NULL),(20,'BLR','Belarus','Bielorussia','Minsk','EU','BYR','.by','X','BY','53.709807','27.953389','+375',0,1,0,'Belarus.svg','2023-06-22 19:56:33',NULL),(21,'BOL','Bolivia','Bolivia','Sucre','SA','BOB','.bo','X','BO','-16.290154','-63.588653','+591',0,1,0,'Bolivia.svg','2023-06-22 19:56:33',NULL),(22,'BIH','Bosnia and Herzegovina','Bosnia-Erzegovina','Sarajevo','EU','BAM','.ba','X','BA','43.915886','17.679076','+387',1,1,0,'Bosnia_and_Herzegovina.svg','2023-06-22 19:56:33',NULL),(23,'BWA','Botswana','Botswana','Gaborone','AF','BWP','.bw','X','BW','-22.328474','24.684866','+267',0,1,0,'Botswana.svg','2023-06-22 19:56:33',NULL),(24,'BRA','Brazil','Brasile','Brasilia','SA','BRL','.br','X','BR','-14.235004','-51.92528','+55',0,1,0,'Brazil.svg','2023-06-22 19:56:33',NULL),(25,'BRN','Brunei','Brunei','Bandar Seri Begawan','AS','BND','.bn','X','BN','4.535277','114.727669','+673',0,1,0,'Brunei.svg','2023-06-22 19:56:33',NULL),(26,'BGR','Bulgaria','Bulgaria','Sofia','EU','BGN','.bg','X','BG','42.733883','25.48583','+359',1,1,0,'Bulgaria.svg','2023-06-22 19:56:33',NULL),(27,'BFA','Burkina Faso','Burkina Faso','Ouagadougou','AF','XOF','.bf','X','BF','12.238333','-1.561593','+226',0,1,0,'Burkina_Faso.svg','2023-06-22 19:56:33',NULL),(28,'BDI','Burundi','Burundi','Bujumbura','AF','BIF','.bi','X','BI','-3.373056','29.918886','+257',0,1,0,'Burundi.svg','2023-06-22 19:56:33',NULL),(29,'KHM','Cambodia','Cambogia','Phnom Penh','AS','KHR','.kh','X','KH','12.565679','104.990963','+855',0,1,0,'Cambodia.svg','2023-06-22 19:56:33',NULL),(30,'CMR','Cameroon','Camerun','Yaounde','AF','XAF','.cm','X','CM','7.369722','12.354722','+237',0,1,0,'Cameroon.svg','2023-06-22 19:56:33',NULL),(31,'CAN','Canada','Canada','Ottawa','NA','CAD','.ca','X','CA','56.130366','-106.346771','+1',0,1,0,'Canada.svg','2023-06-22 19:56:33',NULL),(32,'CPV','Cape Verde','Capo Verde','Praia','AF','CVE','.cv','X','CV','16.002082','-24.013197','+238',0,1,0,'Cape_Verde.svg','2023-06-22 19:56:33',NULL),(33,'CAF','Central African Republic','Repubblica Centrafricana','Bangui','AF','XAF','.cf','X','CF','6.611111','20.939444','+236',0,1,0,'Central_African_Republic.svg','2023-06-22 19:56:33',NULL),(34,'TCD','Chad','Ciad','N\'Djamena','AF','XAF','.td','X','TD','15.454166','18.732207','+235',0,1,0,'Chad.svg','2023-06-22 19:56:33',NULL),(35,'CHL','Chile','Cile','Santiago','SA','CLP','.cl','X','CL','-35.675147','-71.542969','+56',0,1,0,'Chile.svg','2023-06-22 19:56:33',NULL),(36,'CHN','China','Repubblica Popolare Cinese','Beijing','AS','CNY','.cn','X','CN','35.86166','104.195397','+86',0,1,0,'Republic_of_China.svg','2023-06-22 19:56:33',NULL),(37,'COL','Colombia','Colombia','Bogota','SA','COP','.co','X','CO','4.570868','-74.297333','+57',0,1,0,'Colombia.svg','2023-06-22 19:56:33',NULL),(38,'COM','Comoros','Comore','Moroni','AF','KMF','.km','X','KM','-11.875001','43.872219','+269',0,1,0,'Comoros.svg','2023-06-22 19:56:33',NULL),(39,'COG','Republic of the Congo','Repubblica del Congo ','Brazzaville','AF','XAF','.cg','X','CG','-0.228021','15.827659','+242',0,1,0,'Republic_of_the_Congo.svg','2023-06-22 19:56:33',NULL),(40,'COD','Democratic Republic of the Congo','Repubblica democratica del Congo','Kinshasa','AF','CDF','.cd','X','CD','-4.038333','21.758664','+243',0,1,0,'Democratic_Republic_of_the_Congo.svg','2023-06-22 19:56:33',NULL),(41,'KOR','South Korea','Repubblica di Corea (Corea del Sud)','Seoul','AS','KRW','.kr','X','KR','35.907757','127.766922','+82',0,1,0,'South_Korea.svg','2023-06-22 19:56:33',NULL),(42,'PRK','North Korea','Repubblica Popolare Democratica di Corea (Corea del Nord)','Pyongyang','AS','KPW','.kp','X','KP','40.339852','127.510093','+850',0,1,0,'North_Korea.svg','2023-06-22 19:56:33',NULL),(43,'CIV','Ivory Coast','Costa d\'Avorio','Yamoussoukro','AF','XOF','.ci','X','CI','7.539989','-5.54708','+225',0,1,0,'Ivory_Coast.svg','2023-06-22 19:56:33',NULL),(44,'CRI','Costa Rica','Costa Rica','San Jose','NA','CRC','.cr','X','CR','9.748917','-83.753428','+506',1,1,0,'Costa_Rica.svg','2023-06-22 19:56:33',NULL),(45,'CUB','Cuba','Cuba','Havana','NA','CUP','.cu','X','CU','21.521757','-77.781167','+53',0,1,0,'Cuba.svg','2023-06-22 19:56:33',NULL),(46,'DMA','Dominica','Dominica','Roseau','NA','XCD','.dm','X','DM','15.414999','-61.370976','+1-767',0,1,0,'Dominica.svg','2023-06-22 19:56:33',NULL),(47,'DOM','Dominican Republic','Repubblica Dominicana ','Santo Domingo','NA','DOP','.do','X','DO','18.735693','-70.162651','+1-809 and',1,1,0,'Dominican_Republic.svg','2023-06-22 19:56:33',NULL),(48,'ECU','Ecuador','Ecuador','Quito','SA','USD','.ec','X','EC','-1.831239','-78.183406','+593',0,1,0,'Ecuador.svg','2023-06-22 19:56:33',NULL),(49,'EGY','Egypt','Egitto','Cairo','AF','EGP','.eg','X','EG','26.820553','30.802498','+20',0,1,0,'Egypt.svg','2023-06-22 19:56:33',NULL),(50,'SLV','El Salvador','El Salvador','San Salvador','NA','USD','.sv','X','SV','13.794185','-88.89653','+503',0,1,0,'El_Salvador.svg','2023-06-22 19:56:33',NULL),(51,'ARE','United Arab Emirates','Emirati Arabi Uniti','Abu Dhabi','AS','AED','.ae','X','AE','23.424076','53.847818','+971',0,1,0,'United_Arab_Emirates.svg','2023-06-22 19:56:33',NULL),(52,'ERI','Eritrea','Eritrea','Asmara','AF','ERN','.er','X','ER','15.179384','39.782334','+291',0,1,0,'Eritrea.svg','2023-06-22 19:56:33',NULL),(53,'ETH','Ethiopia','Etiopia','Addis Ababa','AF','ETB','.et','X','ET','9.145','40.489673','+251',0,1,0,'Ethiopia.svg','2023-06-22 19:56:33',NULL),(54,'FJI','Fiji','Repubblica delle Isole Figi','Suva','OC','FJD','.fj','X','FJ','-16.578193','179.414413','+679',0,1,0,'Fiji.svg','2023-06-22 19:56:33',NULL),(55,'PHL','Philippines','Filippine','Manila','AS','PHP','.ph','X','PH','12.879721','121.774017','+63',0,1,0,'Philippines.svg','2023-06-22 19:56:33',NULL),(56,'GAB','Gabon','Gabon','Libreville','AF','XAF','.ga','X','GA','-0.803689','11.609444','+241',0,1,0,'Gabon.svg','2023-06-22 19:56:33',NULL),(57,'GMB','Gambia','Gambia','Banjul','AF','GMD','.gm','X','GM','13.443182','-15.310139','+220',0,1,0,'Gambia.svg','2023-06-22 19:56:33',NULL),(58,'GEO','Georgia','Georgia','Tbilisi','AS','GEL','.ge','X','GE','42.315407','43.356892','+995',1,1,0,'Georgia.svg','2023-06-22 19:56:33',NULL),(59,'GHA','Ghana','Ghana','Accra','AF','GHS','.gh','X','GH','7.946527','-1.023194','+233',0,1,0,'Ghana.svg','2023-06-22 19:56:33',NULL),(60,'JAM','Jamaica','Giamaica','Kingston','NA','JMD','.jm','X','JM','18.109581','-77.297508','+1-876',0,1,0,'Jamaica.svg','2023-06-22 19:56:33',NULL),(61,'JPN','Japan','Giappone','Tokyo','AS','JPY','.jp','X','JP','36.204824','138.252924','+81',0,1,0,'Japan.svg','2023-06-22 19:56:33',NULL),(62,'DJI','Djibouti','Gibuti','Djibouti','AF','DJF','.dj','X','DJ','11.825138','42.590275','+253',0,1,0,'Djibouti.svg','2023-06-22 19:56:33',NULL),(63,'JOR','Jordan','Giordania','Amman','AS','JOD','.jo','X','JO','30.585164','36.238414','+962',0,1,0,'Jordan.svg','2023-06-22 19:56:33',NULL),(64,'GRD','Grenada','Grenada','St. George\'s','NA','XCD','.gd','X','GD','12.262776','-61.604171','+1-473',0,1,0,'Grenada.svg','2023-06-22 19:56:33',NULL),(65,'GTM','Guatemala','Guatemala','Guatemala City','NA','GTQ','.gt','X','GT','15.783471','-90.230759','+502',0,1,0,'Guatemala.svg','2023-06-22 19:56:33',NULL),(66,'GIN','Guinea','Guinea','Conakry','AF','GNF','.gn','X','GN','9.945587','-9.696645','+224',0,1,0,'Guinea.svg','2023-06-22 19:56:33',NULL),(67,'GNB','Guinea-Bissau','Guinea Bissau','Bissau','AF','XOF','.gw','X','GW','11.803749','-15.180413','+245',0,1,0,'Guinea_Bissau.svg','2023-06-22 19:56:33',NULL),(68,'GNQ','Equatorial Guinea','Guinea Equatoriale','Malabo','AF','XAF','.gq','X','GQ','1.650801','10.267895','+240',0,1,0,'Equatorial_Guinea.svg','2023-06-22 19:56:33',NULL),(69,'GUY','Guyana','Guyana','Georgetown','SA','GYD','.gy','X','GY','4.860416','-58.93018','+592',0,1,0,'Guyana.svg','2023-06-22 19:56:33',NULL),(70,'HTI','Haiti','Haiti','Port-au-Prince','NA','HTG','.ht','X','HT','18.971187','-72.285215','+509',0,1,0,'Haiti.svg','2023-06-22 19:56:33',NULL),(71,'HND','Honduras','Honduras','Tegucigalpa','NA','HNL','.hn','X','HN','15.199999','-86.241905','+504',0,1,0,'Honduras.svg','2023-06-22 19:56:33',NULL),(72,'IND','India','India','New Delhi','AS','INR','.in','X','IN','20.593684','78.96288','+91',0,1,0,'India.svg','2023-06-22 19:56:33',NULL),(73,'IDN','Indonesia','Indonesia','Jakarta','AS','IDR','.id','X','ID','-0.789275','113.921327','+62',0,1,0,'Indonesia.svg','2023-06-22 19:56:33',NULL),(74,'IRN','Iran','Repubblica Islamica del Iran','Tehran','AS','IRR','.ir','X','IR','32.427908','53.688046','+98',0,1,0,'Iran.svg','2023-06-22 19:56:33',NULL),(75,'IRQ','Iraq','Iraq','Baghdad','AS','IQD','.iq','X','IQ','33.223191','43.679291','+964',0,1,0,'Iraq.svg','2023-06-22 19:56:33',NULL),(76,'ISR','Israel','Israele','Jerusalem','AS','ILS','.il','X','IL','31.046051','34.851612','+972',1,1,0,'Israel.svg','2023-06-22 19:56:33',NULL),(77,'JEY','Jersey','Isole Jersey','Saint Helier','EU','GBP','.je','X','JE','49.214439','-2.13125','++44-1534',0,1,0,'Jersey.svg','2023-06-22 19:56:33',NULL),(78,'KEN','Kenya','Kenya','Nairobi','AF','KES','.ke','X','KE','-0.023559','37.906193','+254',0,1,0,'Kenya.svg','2023-06-22 19:56:33',NULL),(79,'KGZ','Kyrgyzstan','Kirghizistan','Bishkek','AS','KGS','.kg','X','KG','41.20438','74.766098','+996',0,1,0,'Kyrgyzstan.svg','2023-06-22 19:56:33',NULL),(80,'KIR','Kiribati','Kiribati','Tarawa','OC','AUD','.ki','X','KI','-3.370417','-168.734039','+686',0,1,0,'Kiribati.svg','2023-06-22 19:56:33',NULL),(82,'KWT','Kuwait','Kuwait','Kuwait City','AS','KWD','.kw','X','KW','29.31166','47.481766','+965',1,1,0,'Kuwait.svg','2023-06-22 19:56:33',NULL),(83,'LAO','Laos','Laos','Vientiane','AS','LAK','.la','X','LA','19.85627','102.495496','+856',0,1,0,'Laos.svg','2023-06-22 19:56:33',NULL),(84,'LSO','Lesotho','Lesotho','Maseru','AF','LSL','.ls','X','LS','-29.609988','28.233608','+266',0,1,0,'Lesotho.svg','2023-06-22 19:56:33',NULL),(85,'LBN','Lebanon','Libano','Beirut','AS','LBP','.lb','X','LB','33.854721','35.862285','+961',1,1,0,'Lebanon.svg','2023-06-22 19:56:33',NULL),(86,'LBR','Liberia','Liberia','Monrovia','AF','LRD','.lr','X','LR','6.428055','-9.429499','+231',0,1,0,'Liberia.svg','2023-06-22 19:56:33',NULL),(87,'LBY','Libya','Libia','Tripoli','AF','LYD','.ly','X','LY','26.3351','17.228331','+218',0,1,0,'Libya.svg','2023-06-22 19:56:33',NULL),(88,'MAC','Macao','Macao','Macao','AS','MOP','.mo','X','MO','22.198745','113.543873','+853',0,1,0,'Macau.svg','2023-06-22 19:56:33',NULL),(89,'MKD','Macedonia','Repubblica di Macedonia (FYROM)','Skopje','EU','MKD','.mk','X','MK','41.608635','21.745275','+389',1,1,0,'Macedonia.svg','2023-06-22 19:56:33',NULL),(90,'MDG','Madagascar','Madagascar','Antananarivo','AF','MGA','.mg','X','MG','-18.766947','46.869107','+261',0,1,0,'Madagascar.svg','2023-06-22 19:56:33',NULL),(91,'MWI','Malawi','Malawi','Lilongwe','AF','MWK','.mw','X','MW','-13.254308','34.301525','+265',0,1,0,'Malawi.svg','2023-06-22 19:56:33',NULL),(92,'MYS','Malaysia','Malaysia','Kuala Lumpur','AS','MYR','.my','X','MY','4.210484','101.975766','+60',0,1,0,'Malaysia.svg','2023-06-22 19:56:33',NULL),(93,'MDV','Maldives','Maldive','Male','AS','MVR','.mv','X','MV','3.202778','73.22068','+960',0,1,0,'Maldives.svg','2023-06-22 19:56:33',NULL),(94,'MLI','Mali','Mali','Bamako','AF','XOF','.ml','X','ML','17.570692','-3.996166','+223',0,1,0,'Mali.svg','2023-06-22 19:56:33',NULL),(95,'IMN','Isle of Man','Isola di Man','Douglas','EU','GBP','.im','X','IM','54.236107','-4.548056','++44-1624',0,1,0,'Isle_of_Mann.svg','2023-06-22 19:56:33',NULL),(96,'MAR','Morocco','Marocco','Rabat','AF','MAD','.ma','X','MA','31.791702','-7.09262','+212',0,1,0,'Morocco.svg','2023-06-22 19:56:33',NULL),(97,'MHL','Marshall Islands','Isole Marshal','Majuro','OC','USD','.mh','X','MH','7.131474','171.184478','+692',0,1,0,'Marshall_Islands.svg','2023-06-22 19:56:33',NULL),(98,'MRT','Mauritania','Mauritania','Nouakchott','AF','MRO','.mr','X','MR','21.00789','-10.940835','+222',1,1,0,'Mauritania.svg','2023-06-22 19:56:33',NULL),(99,'MUS','Mauritius','Mauritius','Port Louis','AF','MUR','.mu','X','MU','-20.348404','57.552152','+230',1,1,0,'Mauritius.svg','2023-06-22 19:56:33',NULL),(100,'MEX','Mexico','Messico','Mexico City','NA','MXN','.mx','X','MX','23.634501','-102.552784','+52',0,1,0,'Mexico.svg','2023-06-22 19:56:33',NULL),(101,'FSM','Micronesia','Stati Federati Micronesia','Palikir','OC','USD','.fm','X','FM','7.425554','150.550812','+691',0,1,0,'Federated_States_of_Micronesia.svg','2023-06-22 19:56:33',NULL),(102,'MDA','Moldova','Moldavia','Chisinau','EU','MDL','.md','X','MD','47.411631','28.369885','+373',1,1,0,'Moldova.svg','2023-06-22 19:56:33',NULL),(103,'MCO','Monaco','Monaco','Monaco','EU','EUR','.mc','X','MC','43.750298','7.412841','+377',1,1,0,'Monaco.svg','2023-06-22 19:56:33',NULL),(104,'MNG','Mongolia','Mongolia','Ulan Bator','AS','MNT','.mn','X','MN','46.862496','103.846656','+976',0,1,0,'Mongolia.svg','2023-06-22 19:56:33',NULL),(105,'MNE','Montenegro','Montenegro','Podgorica','EU','EUR','.me','X','ME','42.708678','19.37439','+382',1,1,0,'Montenegro.svg','2023-06-22 19:56:33',NULL),(106,'MOZ','Mozambique','Mozambico','Maputo','AF','MZN','.mz','X','MZ','-18.665695','35.529562','+258',0,1,0,'Mozambique.svg','2023-06-22 19:56:33',NULL),(107,'MMR','Myanmar','Myanmar','Nay Pyi Taw','AS','MMK','.mm','X','MM','21.913965','95.956223','+95',0,1,0,'Myanmar.svg','2023-06-22 19:56:33',NULL),(108,'NAM','Namibia','Namibia','Windhoek','AF','NAD','.na','X','NA','-22.95764','18.49041','+264',0,1,0,'Namibia.svg','2023-06-22 19:56:33',NULL),(109,'NRU','Nauru','Nauru','Yaren','OC','AUD','.nr','X','NR','-0.522778','166.931503','+674',0,1,0,'Nauru.svg','2023-06-22 19:56:33',NULL),(110,'NPL','Nepal','Nepal','Kathmandu','AS','NPR','.np','X','NP','28.394857','84.124008','+977',0,1,0,'Nepal.svg','2023-06-22 19:56:33',NULL),(111,'NIC','Nicaragua','Nicaragua','Managua','NA','NIO','.ni','X','NI','12.865416','-85.207229','+505',0,1,0,'Nicaragua.svg','2023-06-22 19:56:33',NULL),(112,'NER','Niger','Niger','Niamey','AF','XOF','.ne','X','NE','17.607789','8.081666','+227',0,1,0,'Niger.svg','2023-06-22 19:56:33',NULL),(113,'NGA','Nigeria','Nigeria','Abuja','AF','NGN','.ng','X','NG','9.081999','8.675277','+234',0,1,0,'Nigeria.svg','2023-06-22 19:56:33',NULL),(114,'NZL','New Zealand','Nuova Zelanda','Wellington','OC','NZD','.nz','X','NZ','-40.900557','174.885971','+64',0,1,0,'New_Zealand.svg','2023-06-22 19:56:33',NULL),(115,'OMN','Oman','Oman','Muscat','AS','OMR','.om','X','OM','21.512583','55.923255','+968',0,1,0,'Oman.svg','2023-06-22 19:56:33',NULL),(116,'PAK','Pakistan','Pakistan','Islamabad','AS','PKR','.pk','X','PK','30.375321','69.345116','+92',0,1,0,'Pakistan.svg','2023-06-22 19:56:33',NULL),(117,'PLW','Palau','Palau','Melekeok','OC','USD','.pw','X','PW','7.51498','134.58252','+680',0,1,0,'Palau.svg','2023-06-22 19:56:33',NULL),(118,'PAN','Panama','Panama','Panama City','NA','PAB','.pa','X','PA','8.537981','-80.782127','+507',0,1,0,'Panama.svg','2023-06-22 19:56:33',NULL),(119,'PNG','Papua New Guinea','Papua Nuova Guinea','Port Moresby','OC','PGK','.pg','X','PG','-6.314993','143.95555','+675',0,1,0,'Papua_New_Guinea.svg','2023-06-22 19:56:33',NULL),(120,'PRY','Paraguay','Paraguay','Asuncion','SA','PYG','.py','X','PY','-23.442503','-58.443832','+595',0,1,0,'Paraguay.svg','2023-06-22 19:56:33',NULL),(121,'PER','Peru','Perù','Lima','SA','PEN','.pe','X','PE','-9.189967','-75.015152','+51',0,1,0,'Peru.svg','2023-06-22 19:56:33',NULL),(122,'QAT','Qatar','Qatar','Doha','AS','QAR','.qa','X','QA','25.354826','51.183884','+974',0,1,0,'Qatar.svg','2023-06-22 19:56:33',NULL),(123,'RWA','Rwanda','Ruanda','Kigali','AF','RWF','.rw','X','RW','-1.940278','29.873888','+250',0,1,0,'Rwanda.svg','2023-06-22 19:56:33',NULL),(124,'RUS','Russia','Federazione Russa ','Moscow','EU','RUB','.ru','X','RU','61.52401','105.318756','+7',0,1,0,'Russia.svg','2023-06-22 19:56:33',NULL),(125,'VCT','Saint Vincent and the Grenadines','Saint Vincent e Grenadine','Kingstown','NA','XCD','.vc','X','VC','12.984305','-61.287228','+1-784',0,1,0,'Saint_Vincent_and_the_Grenadines.svg','2023-06-22 19:56:33',NULL),(126,'SLB','Solomon Islands','Isole Salomone','Honiara','OC','SBD','.sb','X','SB','-9.64571','160.156194','+677',0,1,0,'Solomon_Islands.svg','2023-06-22 19:56:33',NULL),(127,'WSM','Samoa','Samoa','Apia','OC','WST','.ws','X','WS','-13.759029','-172.104629','+685',0,1,0,'Samoa.svg','2023-06-22 19:56:33',NULL),(128,'STP','Sao Tome and Principe','São Tomé e Principe','Sao Tome','AF','STD','.st','X','ST','0.18636','6.613081','+239',0,1,0,'Sao_Tome_and_Principe.svg','2023-06-22 19:56:33',NULL),(129,'SEN','Senegal','Senegal','Dakar','AF','XOF','.sn','X','SN','14.497401','-14.452362','+221',0,1,0,'Senegal.svg','2023-06-22 19:56:33',NULL),(130,'SYC','Seychelles','Seychelles','Victoria','AF','SCR','.sc','X','SC','-4.679574','55.491977','+248',0,1,0,'Seychelles.svg','2023-06-22 19:56:33',NULL),(131,'SLE','Sierra Leone','Sierra Leone','Freetown','AF','SLL','.sl','X','SL','8.460555','-11.779889','+232',0,1,0,'Sierra_Leone.svg','2023-06-22 19:56:33',NULL),(132,'SGP','Singapore','Singapore','Singapore','AS','SGD','.sg','X','SG','1.352083','103.819836','+65',0,1,0,'Singapore.svg','2023-06-22 19:56:33',NULL),(133,'SYR','Syria','Siria','Damascus','AS','SYP','.sy','X','SY','34.802075','38.996815','+963',0,1,0,'Syria.svg','2023-06-22 19:56:33',NULL),(134,'SOM','Somalia','Somalia','Mogadishu','AF','SOS','.so','X','SO','5.152149','46.199616','+252',0,1,0,'Somalia.svg','2023-06-22 19:56:33',NULL),(135,'LKA','Sri Lanka','Sri Lanka','Colombo','AS','LKR','.lk','X','LK','7.873054','80.771797','+94',0,1,0,'Sri_Lanka.svg','2023-06-22 19:56:33',NULL),(136,'SDN','Sudan','Sudan','Khartoum','AF','SDG','.sd','X','SD','12.862807','30.217636','+249',0,1,0,'Sudan.svg','2023-06-22 19:56:33',NULL),(137,'SUR','Suriname','Suriname','Paramaribo','SA','SRD','.sr','X','SR','3.919305','-56.027783','+597',0,1,0,'Suriname.svg','2023-06-22 19:56:33',NULL),(138,'SWZ','Swaziland','Swaziland','Mbabane','AF','SZL','.sz','X','SZ','-26.522503','31.465866','+268',0,1,0,'Eswatini.svg','2023-06-22 19:56:33',NULL),(139,'TJK','Tajikistan','Tagikistan','Dushanbe','AS','TJS','.tj','X','TJ','38.861034','71.276093','+992',0,1,0,'Tajikistan.svg','2023-06-22 19:56:33',NULL),(140,'TWN','Taiwan','Taiwan ','Taipei','AS','TWD','.tw','X','TW','23.69781','120.960515','+886',0,1,0,'Taiwan.svg','2023-06-22 19:56:33',NULL),(141,'TZA','Tanzania','Tanzania','Dodoma','AF','TZS','.tz','X','TZ','-6.369028','34.888822','+255',0,1,0,'Tanzania.svg','2023-06-22 19:56:33',NULL),(142,'PSE','Palestinian Territory','Territori dell\'Autonomia Palestinese','East Jerusalem','AS','ILS','.ps','X','PS','31.952162','35.233154','+970',0,1,0,'Palestine.svg','2023-06-22 19:56:33',NULL),(143,'THA','Thailand','Thailandia','Bangkok','AS','THB','.th','X','TH','15.870032','100.992541','+66',0,1,0,'Thailand.svg','2023-06-22 19:56:33',NULL),(144,'TGO','Togo','Togo','Lome','AF','XOF','.tg','X','TG','8.619543','0.824782','+228',0,1,0,'Togo.svg','2023-06-22 19:56:33',NULL),(145,'TON','Tonga','Tonga','Nuku\'alofa','OC','TOP','.to','X','TO','-21.178986','-175.198242','+676',0,1,0,'Tonga.svg','2023-06-22 19:56:33',NULL),(146,'TTO','Trinidad and Tobago','Trinidad e Tobago','Port of Spain','NA','TTD','.tt','X','TT','10.691803','-61.222503','+1-868',0,1,0,'Trinidad_and_Tobago.svg','2023-06-22 19:56:33',NULL),(147,'TUN','Tunisia','Tunisia','Tunis','AF','TND','.tn','X','TN','33.886917','9.537499','+216',1,1,0,'Tunisia.svg','2023-06-22 19:56:33',NULL),(148,'TUR','Turkey','Turchia','Ankara','AS','TRY','.tr','X','TR','38.963745','35.243322','+90',1,1,0,'Turkey.svg','2023-06-22 19:56:33',NULL),(149,'TKM','Turkmenistan','Turkmenistan','Ashgabat','AS','TMT','.tm','X','TM','38.969719','59.556278','+993',0,1,0,'Turkmenistan.svg','2023-06-22 19:56:33',NULL),(150,'TUV','Tuvalu','Tuvalu','Funafuti','OC','AUD','.tv','X','TV','-7.109535','177.64933','+688',0,1,0,'Tuvalu.svg','2023-06-22 19:56:33',NULL),(151,'UKR','Ukraine','Ucraina','Kiev','EU','UAH','.ua','X','UA','48.379433','31.16558','+380',0,1,0,'Ukraine.svg','2023-06-22 19:56:33',NULL),(152,'UGA','Uganda','Uganda','Kampala','AF','UGX','.ug','X','UG','1.373333','32.290275','+256',0,1,0,'Uganda.svg','2023-06-22 19:56:33',NULL),(153,'URY','Uruguay','Uruguay','Montevideo','SA','UYU','.uy','X','UY','-32.522779','-55.765835','+598',0,1,0,'Uruguay.svg','2023-06-22 19:56:33',NULL),(154,'UZB','Uzbekistan','Uzbekistan','Tashkent','AS','UZS','.uz','X','UZ','41.377491','64.585262','+998',0,1,0,'Uzbekistan.svg','2023-06-22 19:56:33',NULL),(155,'VUT','Vanuatu','Vanuatu','Port Vila','OC','VUV','.vu','X','VU','-15.376706','166.959158','+678',0,1,0,'Vanuatu.svg','2023-06-22 19:56:33',NULL),(156,'VEN','Venezuela','Venezuela','Caracas','SA','VEF','.ve','X','VE','6.42375','-66.58973','+58',0,1,0,'Venezuela.svg','2023-06-22 19:56:33',NULL),(157,'VNM','Vietnam','Vietnam','Hanoi','AS','VND','.vn','X','VN','14.058324','108.277199','+84',0,1,0,'Vietnam.svg','2023-06-22 19:56:33',NULL),(158,'YEM','Yemen','Yemen','Sanaa','AS','YER','.ye','X','YE','15.552727','48.516388','+967',0,1,0,'Yemen.svg','2023-06-22 19:56:33',NULL),(159,'ZMB','Zambia','Zambia','Lusaka','AF','ZMW','.zm','X','ZM','-13.133897','27.849332','+260',0,1,0,'Zambia.svg','2023-06-22 19:56:33',NULL),(160,'ZWE','Zimbabwe','Zimbabwe','Harare','AF','ZWL','.zw','X','ZW','-19.015438','29.154857','+263',0,1,0,'Zimbabwe.svg','2023-06-22 19:56:33',NULL),(161,'AUT','Austria','Austria','Vienna','EU','EUR','.at','E','AT','47.516231','14.550072','+43',0,1,0,'Austria.svg','2023-06-22 19:56:33',NULL),(162,'BEL','Belgium','Belgio','Brussels','EU','EUR','.be','E','BE','50.503887','4.469936','+32',1,1,0,'Belgium.svg','2023-06-22 19:56:33',NULL),(163,'BGR','Bulgaria','Bulgaria','Sofia','EU','BGN','.bg','E','BG','42.733883','25.48583','+359',1,1,0,'Bulgaria.svg','2023-06-22 19:56:33',NULL),(164,'CZE','Czechia','Repubblica Ceca ','Prague','EU','CZK','.cz','E','CZ','49.817492','15.472962','+420',1,1,0,'Czech_Republic.svg','2023-06-22 19:56:33',NULL),(165,'CYP','Cyprus','Cipro','Nicosia','EU','EUR','.cy','E','CY','35.126413','33.429859','+357',1,1,0,'Cyprus.svg','2023-06-22 19:56:33',NULL),(166,'HRV','Croatia','Croazia','Zagreb','EU','HRK','.hr','E','HR','45.1','15.2','+385',1,1,0,'Croatia.svg','2023-06-22 19:56:33',NULL),(167,'DNK','Denmark','Danimarca','Copenhagen','EU','DKK','.dk','E','DK','56.26392','9.501785','+45',1,1,0,'Denmark.svg','2023-06-22 19:56:33',NULL),(168,'EST','Estonia','Estonia','Tallinn','EU','EUR','.ee','E','EE','58.595272','25.013607','+372',1,1,0,'Estonia.svg','2023-06-22 19:56:33',NULL),(169,'FIN','Finland','Finlandia','Helsinki','EU','EUR','.fi','E','FI','61.92411','25.748151','+358',1,1,0,'Finland.svg','2023-06-22 19:56:33',NULL),(170,'FRA','France','Francia','Paris','EU','EUR','.fr','E','FR','46.227638','2.213749','+33',1,1,0,'France.svg','2023-06-22 19:56:33',NULL),(171,'DEU','Germany','Germania','Berlin','EU','EUR','.de','E','DE','51.165691','10.451526','+49',1,1,0,'Germany.svg','2023-06-22 19:56:33',NULL),(172,'GRC','Greece','Grecia','Athens','EU','EUR','.gr','E','GR','39.074208','21.824312','+30',1,1,0,'Greece.svg','2023-06-22 19:56:33',NULL),(173,'IRL','Ireland','Irlanda','Dublin','EU','EUR','.ie','E','IE','53.41291','-8.24389','+353',1,1,0,'Ireland.svg','2023-06-22 19:56:33',NULL),(174,'AFG','Afghanistan','Afghanistan','Kabul','AS','AFN','.af','X','AF','33.93911','67.709953','+93',0,1,0,'Afghanistan.svg','2023-06-22 19:56:33',NULL),(175,'LVA','Latvia','Lettonia','Riga','EU','EUR','.lv','E','LV','56.879635','24.603189','+371',1,1,0,'Latvia.svg','2023-06-22 19:56:33',NULL),(176,'LTU','Lithuania','Lituania','Vilnius','EU','EUR','.lt','E','LT','55.169438','23.881275','+370',1,1,0,'Lithuania.svg','2023-06-22 19:56:33',NULL),(177,'LUX','Luxembourg','Lussemburgo','Luxembourg','EU','EUR','.lu','E','LU','49.815273','6.129583','+352',1,1,0,'Luxembourg.svg','2023-06-22 19:56:33',NULL),(178,'MLT','Malta','Malta','Valletta','EU','EUR','.mt','E','MT','35.937496','14.375416','+356',1,1,0,'Malta.svg','2023-06-22 19:56:33',NULL),(179,'NLD','Netherlands','Paesi Bassi','Amsterdam','EU','EUR','.nl','E','NL','52.132633','5.291266','+31',1,1,0,'Netherlands.svg','2023-06-22 19:56:33',NULL),(180,'POL','Poland','Polonia','Warsaw','EU','PLN','.pl','E','PL','51.919438','19.145136','+48',1,1,0,'Poland.svg','2023-06-22 19:56:33',NULL),(181,'PRT','Portugal','Portogallo','Lisbon','EU','EUR','.pt','E','PT','39.399872','-8.224454','+351',1,1,0,'Portugal.svg','2023-06-22 19:56:33',NULL),(182,'GBR','United Kingdom','Regno Unito','London','EU','GBP','.uk','E','GB','55.378051','-3.435973','+44',1,1,0,'United_Kingdom.svg','2023-06-22 19:56:33',NULL),(183,'ROU','Romania','Romania','Bucharest','EU','RON','.ro','E','RO','45.943161','24.96676','+40',1,1,0,'Romania.svg','2023-06-22 19:56:33',NULL),(184,'SVK','Slovakia','Slovacchia','Bratislava','EU','EUR','.sk','E','SK','48.669026','19.699024','+421',1,1,0,'Slovakia.svg','2023-06-22 19:56:33',NULL),(185,'SVN','Slovenia','Slovenia','Ljubljana','EU','EUR','.si','E','SI','46.151241','14.995463','+386',1,1,0,'Slovenia.svg','2023-06-22 19:56:33',NULL),(186,'ESP','Spain','Spagna','Madrid','EU','EUR','.es','E','ES','40.463667','-3.74922','+34',1,1,0,'Spain.svg','2023-06-22 19:56:33',NULL),(187,'SWE','Sweden','Svezia','Stockholm','EU','SEK','.se','E','SE','60.128161','18.643501','+46',1,1,0,'Sweden.svg','2023-06-22 19:56:33',NULL),(188,'HUN','Hungary','Ungheria','Budapest','EU','HUF','.hu','E','HU','47.162494','19.503304','+36',1,1,0,'Hungary.svg','2023-06-22 19:56:33',NULL),(189,'ISL','Iceland','Islanda','Reykjavik','EU','ISK','.is','EQ','IS','64.963051','-19.020835','+354',1,1,0,'Iceland.svg','2023-06-22 19:56:33',NULL),(190,'LIE','Liechtenstein','Liechtenstein','Vaduz','EU','CHF','.li','EQ','LI','47.166','9.555373','+423',1,1,0,'Liechtenstein.svg','2023-06-22 19:56:33',NULL),(191,'NOR','Norway','Norvegia','Oslo','EU','NOK','.no','EQ','NO','60.472024','8.468946','+47',1,1,0,'Norway.svg','2023-06-22 19:56:33',NULL),(192,'SMR','San Marino','San Marino','San Marino','EU','EUR','.sm','EQ','SM','43.94236','12.457777','+378',1,1,0,'San_Marino.svg','2023-06-22 19:56:33',NULL),(193,'CHE','Switzerland','Svizzera','Bern','EU','CHF','.ch','EQ','CH','46.818188','8.227512','+41',1,1,1,'Switzerland.svg','2023-06-22 19:56:33',NULL),(194,'USA','United States','','Washington','NA','USD','.us','','US','37.09024','-95.712891','+1',0,1,0,'United_States.svg','2023-06-22 19:56:33',NULL),(195,'SGS','South Georgia and the South Sandwich Islands','','Grytviken','AN','GBP','.gs','','GS','-54.429579','-36.587909','',0,1,0,'South_Georgia_and_the_South_Sandwich_Islands.svg','2023-06-22 19:56:33',NULL),(196,'NIU','Niue','','Alofi','OC','NZD','.nu','','NU','-19.054445','-169.867233','+683',0,1,0,'Niue.svg','2023-06-22 19:56:33',NULL),(197,'BLM','Saint Barthelemy','','Gustavia','NA','EUR','.gp','','BL',NULL,NULL,'+590',0,1,0,'Saint_Barthelemy.svg','2023-06-22 19:56:33',NULL),(198,'TKL','Tokelau','','','OC','NZD','.tk','','TK','-8.967363','-171.855881','+690',0,1,0,'Tokelau.svg','2023-06-22 19:56:33',NULL),(199,'GIB','Gibraltar','','Gibraltar','EU','GIP','.gi','','GI','36.137741','-5.345374','+350',1,1,0,'Gibraltar.svg','2023-06-22 19:56:33',NULL),(200,'MSR','Montserrat','','Plymouth','NA','XCD','.ms','','MS','16.742498','-62.187366','+1-664',0,1,0,'Montserrat.svg','2023-06-22 19:56:33',NULL),(201,'ASM','American Samoa','','Pago Pago','OC','USD','.as','','AS','-14.270972','-170.132217','+1-684',0,1,0,'American_Samoa.svg','2023-06-22 19:56:33',NULL),(202,'SXM','Sint Maarten','','Philipsburg','NA','ANG','.sx','','SX',NULL,NULL,'+599',0,1,0,'Sint_Maarten.svg','2023-06-22 19:56:33',NULL),(203,'FRO','Faroe Islands','','Torshavn','EU','DKK','.fo','','FO','61.892635','-6.911806','+298',1,1,0,'Faroe_Islands.svg','2023-06-22 19:56:33',NULL),(204,'MAF','Saint Martin','','Marigot','NA','EUR','.gp','','MF',NULL,NULL,'+590',0,1,0,'Sint_Maarten.svg','2023-06-22 19:56:33',NULL),(205,'SSD','South Sudan','','Juba','AF','SSP','','','SS',NULL,NULL,'+211',0,1,0,'South_Sudan.svg','2023-06-22 19:56:33',NULL),(206,'CXR','Christmas Island','','Flying Fish Cove','AS','AUD','.cx','','CX','-10.447525','105.690449','+61',0,1,0,'Christmas_Island.svg','2023-06-22 19:56:33',NULL),(207,'SCG','Serbia and Montenegro','','Belgrade','EU','RSD','.cs','','CS',NULL,NULL,'+381',0,1,0,'Serbia_and_Montenegro.svg','2023-06-22 19:56:33',NULL),(208,'CYM','Cayman Islands','','George Town','NA','KYD','.ky','','KY','19.513469','-80.566956','+1-345',0,1,0,'Cayman_Islands.svg','2023-06-22 19:56:33',NULL),(209,'CCK','Cocos Islands','','West Island','AS','AUD','.cc','','CC','-12.164165','96.870956','+61',0,1,0,'Cocos_Islands.svg','2023-06-22 19:56:33',NULL),(210,'WLF','Wallis and Futuna','','Mata Utu','OC','XPF','.wf','','WF','-13.768752','-177.156097','+681',0,1,0,'Wallis_and_Futuna.svg','2023-06-22 19:56:33',NULL),(211,'IOT','British Indian Ocean Territory','','Diego Garcia','AS','USD','.io','','IO','-6.343194','71.876519','+246',0,1,0,'British_Indian_Ocean_Territory.svg','2023-06-22 19:56:33',NULL),(212,'PRI','Puerto Rico','','San Juan','NA','USD','.pr','','PR','18.220833','-66.590149','+1-787 and',0,1,0,'Puerto_Rico.svg','2023-06-22 19:56:33',NULL),(213,'VAT','Vatican','','Vatican City','EU','EUR','.va','','VA','41.902916','12.453389','+379',0,1,0,'Vatican_City.svg','2023-06-22 19:56:33',NULL),(214,'GUM','Guam','','Hagatna','OC','USD','.gu','','GU','13.444304','144.793731','+1-671',0,1,0,'Guam.svg','2023-06-22 19:56:33',NULL),(215,'PYF','French Polynesia','','Papeete','OC','XPF','.pf','','PF','-17.679742','-149.406843','+689',0,1,0,'French_Polynesia.svg','2023-06-22 19:56:33',NULL),(216,'BMU','Bermuda','','Hamilton','NA','BMD','.bm','','BM','32.321384','-64.75737','+1-441',0,1,0,'Bermuda.svg','2023-06-22 19:56:33',NULL),(217,'TLS','East Timor','','Dili','OC','USD','.tl','','TL','-8.874217','125.727539','+670',0,1,0,'East_Timor.svg','2023-06-22 19:56:33',NULL),(218,'GRL','Greenland','','Nuuk','NA','DKK','.gl','','GL','71.706936','-42.604303','+299',1,1,0,'Greenland.svg','2023-06-22 19:56:33',NULL),(219,'NCL','New Caledonia','','Noumea','OC','XPF','.nc','','NC','-20.904305','165.618042','+687',0,1,0,'New_Caledonia.svg','2023-06-22 19:56:33',NULL),(220,'ABW','Aruba','','Oranjestad','NA','AWG','.aw','','AW','12.52111','-69.968338','+297',0,1,0,'Aruba.svg','2023-06-22 19:56:33',NULL),(221,'TCA','Turks and Caicos Islands','','Cockburn Town','NA','USD','.tc','','TC','21.694025','-71.797928','+1-649',0,1,0,'Turks_and_Caicos_Islands.svg','2023-06-22 19:56:33',NULL),(222,'GUF','French Guiana','','Cayenne','SA','EUR','.gf','','GF','3.933889','-53.125782','+594',0,1,0,'Drapeau_de_la_Guyane.svg','2023-06-22 19:56:33',NULL),(223,'MNP','Northern Mariana Islands','','Saipan','OC','USD','.mp','','MP','17.33083','145.38469','+1-670',0,1,0,'Northern_Mariana_Islands.svg','2023-06-22 19:56:33',NULL),(224,'ATA','Antarctica','','','AN','','.aq','','AQ','-75.250973','-0.071389','',0,1,0,'Federated_States_of_Antarctica.svg','2023-06-22 19:56:33',NULL),(225,'SHN','Saint Helena','','Jamestown','AF','SHP','.sh','','SH','-24.143474','-10.030696','+290',0,1,0,'Saint_Helena.svg','2023-06-22 19:56:33',NULL),(226,'ESH','Western Sahara','','El-Aaiun','AF','MAD','.eh','','EH','24.215527','-12.885834','+212',0,1,0,'Western_Sahara.svg','2023-06-22 19:56:33',NULL),(227,'ANT','Netherlands Antilles','','Willemstad','NA','ANG','.an','','AN','12.226079','-69.060087','+599',0,1,0,'Netherlands_Antilles.svg','2023-06-22 19:56:33',NULL),(228,'KAZ','Kazakhstan','','Astana','AS','KZT','.kz','','KZ','48.019573','66.923684','+7',1,1,0,'Kazakhstan.svg','2023-06-22 19:56:33',NULL),(229,'REU','Reunion','','Saint-Denis','AF','EUR','.re','','RE','-21.115141','55.536384','+262',0,1,0,'Reunion.svg','2023-06-22 19:56:33',NULL),(230,'COK','Cook Islands','','Avarua','OC','NZD','.ck','','CK','-21.236736','-159.777671','+682',0,1,0,'Cook_Islands.svg','2023-06-22 19:56:33',NULL),(231,'MYT','Mayotte','','Mamoudzou','AF','EUR','.yt','','YT','-12.8275','45.166244','+262',0,1,0,'Mayotte.svg','2023-06-22 19:56:33',NULL),(232,'KNA','Saint Kitts and Nevis','','Basseterre','NA','XCD','.kn','','KN','17.357822','-62.782998','+1-869',0,1,0,'Saint_Kitts_and_Nevis.svg','2023-06-22 19:56:33',NULL),(233,'BES','Bonaire, Saint Eustatius and Saba ','','','NA','USD','.bq','','BQ',NULL,NULL,'+599',0,1,0,'BES.svg','2023-06-22 19:56:33',NULL),(234,'VGB','British Virgin Islands','','Road Town','NA','USD','.vg','','VG','18.420695','-64.639968','+1-284',0,1,0,'British_Virgin_Islands.svg','2023-06-22 19:56:33',NULL),(235,'HKG','Hong Kong','','Hong Kong','AS','HKD','.hk','','HK','22.396428','114.109497','+852',0,1,0,'Hong_Kong.svg','2023-06-22 19:56:33',NULL),(236,'SPM','Saint Pierre and Miquelon','','Saint-Pierre','NA','EUR','.pm','','PM','46.941936','-56.27111','+508',0,1,0,'Saint_Pierre_and_Miquelon.svg','2023-06-22 19:56:33',NULL),(237,'UMI','United States Minor Outlying Islands','','','OC','USD','.um','','UM','','','+1',0,1,0,'United_States_Minor_Outlying_Islands.svg','2023-06-22 19:56:33',NULL),(238,'GLP','Guadeloupe','','Basse-Terre','NA','EUR','.gp','','GP','16.995971','-62.067641','+590',0,1,0,'Guadeloupe.svg','2023-06-22 19:56:33',NULL),(239,'NFK','Norfolk Island','','Kingston','OC','AUD','.nf','','NF','-29.040835','167.954712','+672',0,1,0,'Norfolk_Island.svg','2023-06-22 19:56:33',NULL),(240,'ALA','Aland Islands','','Mariehamn','EU','EUR','.ax','','AX',NULL,NULL,'++358-18',0,1,0,'Flag_of_Aland.svg','2023-06-22 19:56:33',NULL),(241,'ATF','French Southern Territories','','Port-aux-Francais','AN','EUR','.tf','','TF','-49.280366','69.348557','',0,1,0,'French_Southern_and_Antarctic_Lands.svg','2023-06-22 19:56:33',NULL),(242,'GGY','Guernsey','','St Peter Port','EU','GBP','.gg','','GG','49.465691','-2.585278','++44-1481',0,1,0,'Guernsey.svg','2023-06-22 19:56:33',NULL),(243,'MTQ','Martinique','','Fort-de-France','NA','EUR','.mq','','MQ','14.641528','-61.024174','+596',0,1,0,'Martinique.svg','2023-06-22 19:56:33',NULL),(244,'ARG','Argentina','','Buenos Aires','SA','ARS','.ar','','AR','-38.416097','-63.616672','+54',0,1,0,'Argentina.svg','2023-06-22 19:56:33',NULL),(245,'SJM','Svalbard and Jan Mayen','','Longyearbyen','EU','NOK','.sj','','SJ','77.553604','23.670272','+47',0,1,0,'Svalbard_and_Jan_Mayen.svg','2023-06-22 19:56:33',NULL),(246,'FLK','Falkland Islands','','Stanley','SA','FKP','.fk','','FK','-51.796253','-59.523613','+500',0,1,0,'Falkland_Islands.svg','2023-06-22 19:56:33',NULL),(247,'LCA','Saint Lucia','','Castries','NA','XCD','.lc','','LC','13.909444','-60.978893','+1-758',0,1,0,'Saint_Lucia.svg','2023-06-22 19:56:33',NULL),(248,'SRB','Serbia','','Belgrade','EU','RSD','.rs','','RS','44.016521','21.005859','+381',1,1,0,'Serbia.svg','2023-06-22 19:56:33',NULL),(249,'CUW','Curacao','',' Willemstad','NA','ANG','.cw','','CW',NULL,NULL,'+599',0,1,0,'Curacao.svg','2023-06-22 19:56:33',NULL),(250,'ZAF','South Africa','','Pretoria','AF','ZAR','.za','','ZA','-30.559482','22.937506','+27',0,1,0,'South_Africa.svg','2023-06-22 19:56:33',NULL),(251,'XKX','Kosovo','','Pristina','EU','EUR','','','XK','42.602636','20.902977','',1,1,0,'Kosovo.svg','2023-06-22 19:56:33',NULL),(252,'BVT','Bouvet Island','','','AN','NOK','.bv','','BV','-54.423199','3.413194','',0,1,0,'Bouvet_Island.svg','2023-06-22 19:56:33',NULL),(253,'VIR','U.S. Virgin Islands','','Charlotte Amalie','NA','USD','.vi','','VI','18.335765','-64.896335','+1-340',0,1,0,'United_States_Virgin_Islands.svg','2023-06-22 19:56:33',NULL),(254,'HMD','Heard Island and McDonald Islands','','','AN','AUD','.hm','','HM','-53.08181','73.504158','',0,1,0,'hm.svg','2023-06-22 19:56:33',NULL),(255,'PCN','Pitcairn','','Adamstown','OC','NZD','.pn','','PN','-24.703615','-127.439308','+870',0,1,0,'Pitcairn_Islands.svg','2023-06-22 19:56:33',NULL);
DROP TABLE IF EXISTS `paymentmethods`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `paymentmethods` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `cod` varchar(50) NOT NULL DEFAULT '',
  `name` varchar(250) NOT NULL DEFAULT '',
  `intest` varchar(250) NOT NULL DEFAULT '',
  `description` text DEFAULT '',
  `tppaymentmethod` int(10) unsigned NOT NULL DEFAULT 0,
  `tpwebpayment` int(10) unsigned NOT NULL DEFAULT 0,
  `user` int(10) unsigned NOT NULL DEFAULT 0,
  `activity` int(10) unsigned NOT NULL DEFAULT 0,
  `email` varchar(150) NOT NULL DEFAULT '',
  `account_id` varchar(150) NOT NULL DEFAULT '',
  `iban` varchar(50) NOT NULL DEFAULT '',
  `bban` varchar(50) NOT NULL DEFAULT '',
  `swift_bic` varchar(50) NOT NULL DEFAULT '',
  `swift` varchar(50) NOT NULL DEFAULT '',
  `bic` varchar(50) NOT NULL DEFAULT '',
  `abi` varchar(50) NOT NULL DEFAULT '',
  `cab` varchar(50) NOT NULL DEFAULT '',
  `cin` varchar(10) NOT NULL DEFAULT '',
  `bank` varchar(150) NOT NULL DEFAULT '',
  `bank_address` varchar(250) NOT NULL DEFAULT '',
  `cc` varchar(150) NOT NULL DEFAULT '',
  `card` varchar(150) NOT NULL DEFAULT '',
  `card_number` varchar(150) NOT NULL DEFAULT '',
  `card_deadline_m` varchar(10) NOT NULL DEFAULT '',
  `card_deadline_y` varchar(10) NOT NULL DEFAULT '',
  `cvv` varchar(10) NOT NULL DEFAULT '',
  `cvv2` varchar(10) NOT NULL DEFAULT '',
  `cvc` varchar(10) NOT NULL DEFAULT '',
  `typein` int(10) unsigned NOT NULL DEFAULT 0,
  `typeout` int(10) unsigned NOT NULL DEFAULT 0,
  `flgonline` int(10) unsigned NOT NULL DEFAULT 0,
  `flgdefault` int(10) unsigned NOT NULL DEFAULT 0,
  `signin` varchar(50) NOT NULL DEFAULT '',
  `signout` varchar(50) NOT NULL DEFAULT '',
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_PAYMENTMETHOD_USER_USERS` (`user`),
  KEY `fk_PAYMENTMETHOD_ACTIVITY_ACTIVITIES` (`activity`),
  KEY `fk_PAYMENTMETHOD_TPPAYMENTMETHOD_TPPAYMENTMETHODS` (`tppaymentmethod`),
  KEY `fk_PAYMENTMETHOD_TPWEBPAYMENT_TPWEBPAYMENT` (`tpwebpayment`),
  CONSTRAINT `fk_PAYMENTMETHOD_ACTIVITY_ACTIVITIES` FOREIGN KEY (`activity`) REFERENCES `activities` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_PAYMENTMETHOD_TPPAYMENTMETHOD_TPPAYMENTMETHODS` FOREIGN KEY (`tppaymentmethod`) REFERENCES `tppaymentmethods` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_PAYMENTMETHOD_TPWEBPAYMENT_TPWEBPAYMENT` FOREIGN KEY (`tpwebpayment`) REFERENCES `tpwebpayment` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_PAYMENTMETHOD_USER_USERS` FOREIGN KEY (`user`) REFERENCES `users` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `payments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `payments` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `cod` varchar(50) NOT NULL DEFAULT '',
  `price` int(10) unsigned NOT NULL DEFAULT 0,
  `flgin` int(10) unsigned NOT NULL DEFAULT 1,
  `paymentmethod` int(10) unsigned NOT NULL DEFAULT 0,
  `dtapayment` datetime DEFAULT NULL,
  `note` text DEFAULT NULL,
  `causal` text DEFAULT NULL,
  `bank_sender` varchar(150) NOT NULL DEFAULT '',
  `bank_receiver` varchar(150) NOT NULL DEFAULT '',
  `flgconfirm` int(11) DEFAULT 0,
  `tppayment` int(10) unsigned DEFAULT 0,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_PAYMENTS_PRICE_PRICES` (`price`),
  KEY `fk_PAYMENTS_TPPAYMENT_TPPAYMENTS` (`tppayment`),
  KEY `fk_PAYMENTS_PAYMENTMETHOD_PAYMENTMETHODS` (`paymentmethod`),
  CONSTRAINT `fk_PAYMENTS_PAYMENTMETHOD_PAYMENTMETHODS` FOREIGN KEY (`paymentmethod`) REFERENCES `paymentmethods` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_PAYMENTS_PRICE_PRICES` FOREIGN KEY (`price`) REFERENCES `prices` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_PAYMENTS_TPPAYMENT_TPPAYMENTS` FOREIGN KEY (`tppayment`) REFERENCES `tppayments` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `permissions` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `cod` varchar(250) NOT NULL DEFAULT '',
  `name` varchar(250) NOT NULL DEFAULT '',
  `description` text DEFAULT '',
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
INSERT INTO `permissions` VALUES (1,'ALL_PERMISSIONS','Tutti i permessi','I permessi di tutti gli utenti','2023-06-22 19:56:53',NULL),(2,'ALL_ACTIVITY_PERMISSIONS','Tutti i permessi aziendali','I permessi di tutte le operazioni aziendali','2023-06-22 19:56:53',NULL),(3,'MANAGE_PROFILES','Gestione profili utente','Gestione di tutti i profili utente','2023-06-22 19:56:53',NULL),(4,'VIEW_PROFILES','Visualizzazione profili utente','Visualizzazione di tutti i profili utente','2023-06-22 19:56:53',NULL),(5,'MANAGE_ACTIVITY_PROFILES','Gestione profili aziendali','Gestione di tutti i profili aziendali','2023-06-22 19:56:53',NULL),(6,'VIEW_ACTIVITY_PROFILES','Visualizzazione profili aziendali','Visualizzazione di tutti i profili aziendali','2023-06-22 19:56:53',NULL);
DROP TABLE IF EXISTS `phonereceivers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `phonereceivers` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `phone` int(10) unsigned NOT NULL DEFAULT 0,
  `receivername` varchar(150) NOT NULL DEFAULT '',
  `receiverphone` varchar(150) NOT NULL DEFAULT '',
  `flgreaded` int(10) unsigned NOT NULL DEFAULT 0,
  `dtaread` datetime DEFAULT NULL,
  `dtareceive` datetime DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_PHONERECEIVERS_PHONE_PHONES` (`phone`),
  CONSTRAINT `fk_PHONERECEIVERS_PHONE_PHONES` FOREIGN KEY (`phone`) REFERENCES `phones` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
INSERT INTO `phonereceivers` VALUES (1,1,'destinator1','+393483344888',0,NULL,'2023-06-22 19:56:03','2023-06-22 19:56:03',NULL);
DROP TABLE IF EXISTS `phones`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `phones` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `sendername` varchar(150) NOT NULL DEFAULT '',
  `senderphone` varchar(150) NOT NULL DEFAULT '',
  `message` text DEFAULT NULL,
  `flgdeleted` int(10) unsigned NOT NULL DEFAULT 0,
  `dtasend` datetime DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
INSERT INTO `phones` VALUES (1,'test','+393353535355','Messaggio di prova',0,'2023-06-22 19:56:03','2023-06-22 19:56:03',NULL);
DROP TABLE IF EXISTS `pocketattachments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pocketattachments` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `cod` varchar(50) NOT NULL DEFAULT '',
  `pocket` int(10) unsigned NOT NULL DEFAULT 0,
  `attachment` int(10) unsigned NOT NULL DEFAULT 0,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_POCKETATTACHMENT_POCKET_POCKETS` (`pocket`),
  KEY `fk_POCKETATTACHMENT_ATTACHMENT_ATTACHMENTS` (`attachment`),
  CONSTRAINT `fk_POCKETATTACHMENT_ATTACHMENT_ATTACHMENTS` FOREIGN KEY (`attachment`) REFERENCES `attachments` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_POCKETATTACHMENT_POCKET_POCKETS` FOREIGN KEY (`pocket`) REFERENCES `pockets` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `pocketdiscounts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pocketdiscounts` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `cod` varchar(50) NOT NULL DEFAULT '',
  `pocket` int(10) unsigned NOT NULL DEFAULT 0,
  `discount` int(10) unsigned NOT NULL DEFAULT 0,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_POCKETDISCOUNT_POCKET_POCKETS` (`pocket`),
  KEY `fk_POCKETDISCOUNT_DISCOUNT_DISCOUNTS` (`discount`),
  CONSTRAINT `fk_POCKETDISCOUNT_DISCOUNT_DISCOUNTS` FOREIGN KEY (`discount`) REFERENCES `discounts` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_POCKETDISCOUNT_POCKET_POCKETS` FOREIGN KEY (`pocket`) REFERENCES `pockets` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `pocketproducts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pocketproducts` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `cod` varchar(50) NOT NULL DEFAULT '',
  `pocket` int(10) unsigned NOT NULL DEFAULT 0,
  `product` int(10) unsigned NOT NULL DEFAULT 0,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_POCKETPRODUCT_POCKET_POCKETS` (`pocket`),
  KEY `fk_POCKETPRODUCT_PRODUCT_PRODUCTS` (`product`),
  CONSTRAINT `fk_POCKETPRODUCT_POCKET_POCKETS` FOREIGN KEY (`pocket`) REFERENCES `pockets` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_POCKETPRODUCT_PRODUCT_PRODUCTS` FOREIGN KEY (`product`) REFERENCES `products` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `pocketreservesettings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pocketreservesettings` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `cod` varchar(50) NOT NULL DEFAULT '',
  `pocket` int(10) unsigned NOT NULL DEFAULT 0,
  `settings` int(10) unsigned NOT NULL DEFAULT 0,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_POCKETRESERVESETTING_POCKET_POCKETS` (`pocket`),
  KEY `fk_POCKETRESERVESETTING_SETTINGS_RESERVESETTINGS` (`settings`),
  CONSTRAINT `fk_POCKETRESERVESETTING_POCKET_POCKETS` FOREIGN KEY (`pocket`) REFERENCES `pockets` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_POCKETRESERVESETTING_SETTINGS_RESERVESETTINGS` FOREIGN KEY (`settings`) REFERENCES `reservationsettings` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `pockets`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pockets` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `cod` varchar(50) NOT NULL DEFAULT '',
  `name` varchar(150) NOT NULL DEFAULT '',
  `description` text DEFAULT NULL,
  `image` int(10) unsigned NOT NULL DEFAULT 0,
  `price` int(10) unsigned NOT NULL DEFAULT 0,
  `note` text DEFAULT NULL,
  `flgreleted` int(10) unsigned NOT NULL DEFAULT 0,
  `flgdeleted` int(10) unsigned NOT NULL DEFAULT 0,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_POCKET_IMAGE_ATTACHMENT` (`image`),
  KEY `fk_POCKET_PRICE_PRICES` (`price`),
  CONSTRAINT `fk_POCKET_IMAGE_ATTACHMENT` FOREIGN KEY (`image`) REFERENCES `attachments` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_POCKET_PRICE_PRICES` FOREIGN KEY (`price`) REFERENCES `prices` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `pocketservices`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pocketservices` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `cod` varchar(50) NOT NULL DEFAULT '',
  `pocket` int(10) unsigned NOT NULL DEFAULT 0,
  `service` int(10) unsigned NOT NULL DEFAULT 0,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_POCKETSERVICE_POCKET_POCKETS` (`pocket`),
  KEY `fk_POCKETSERVICE_SERVICE_SERVICES` (`service`),
  CONSTRAINT `fk_POCKETSERVICE_POCKET_POCKETS` FOREIGN KEY (`pocket`) REFERENCES `pockets` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_POCKETSERVICE_SERVICE_SERVICES` FOREIGN KEY (`service`) REFERENCES `services` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `pockettaxes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pockettaxes` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `cod` varchar(50) NOT NULL DEFAULT '',
  `pocket` int(10) unsigned NOT NULL DEFAULT 0,
  `tax` double(11,2) NOT NULL DEFAULT 0.00,
  `tax_percent` double(11,2) NOT NULL DEFAULT 0.00,
  `taxdescription` text DEFAULT NULL,
  `currencyid` int(10) unsigned NOT NULL DEFAULT 0,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_POCKETTAX_POCKET_POCKETS` (`pocket`),
  KEY `fk_POCKETTAX_CURRENCY_CURRENCIES` (`currencyid`),
  CONSTRAINT `fk_POCKETTAX_CURRENCY_CURRENCIES` FOREIGN KEY (`currencyid`) REFERENCES `currencies` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_POCKETTAX_POCKET_POCKETS` FOREIGN KEY (`pocket`) REFERENCES `pockets` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `prices`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `prices` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `cod` varchar(50) NOT NULL DEFAULT '',
  `price` double(11,2) NOT NULL DEFAULT 0.00,
  `total` double(11,2) NOT NULL DEFAULT 0.00,
  `iva` double(11,2) NOT NULL DEFAULT 0.00,
  `iva_percent` double(11,2) NOT NULL DEFAULT 0.00,
  `discount` double(11,2) NOT NULL DEFAULT 0.00,
  `discount_percent` double(11,2) NOT NULL DEFAULT 0.00,
  `tax` double(11,2) NOT NULL DEFAULT 0.00,
  `currencyid` int(10) unsigned NOT NULL DEFAULT 0,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_PRICE_CURRENCY_CURRENCIES` (`currencyid`),
  CONSTRAINT `fk_PRICE_CURRENCY_CURRENCIES` FOREIGN KEY (`currencyid`) REFERENCES `currencies` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `productattachments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `productattachments` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `cod` varchar(50) NOT NULL DEFAULT '',
  `product` int(10) unsigned NOT NULL DEFAULT 0,
  `attachment` int(10) unsigned NOT NULL DEFAULT 0,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_PRODUCTATTACHMENT_PRODUCT_PRODUCTS` (`product`),
  KEY `fk_PRODUCTATTACHMENT_ATTACHMENT_ATTACHMENTS` (`attachment`),
  CONSTRAINT `fk_PRODUCTATTACHMENT_ATTACHMENT_ATTACHMENTS` FOREIGN KEY (`attachment`) REFERENCES `attachments` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_PRODUCTATTACHMENT_PRODUCT_PRODUCTS` FOREIGN KEY (`product`) REFERENCES `products` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `productdiscounts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `productdiscounts` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `cod` varchar(50) NOT NULL DEFAULT '',
  `product` int(10) unsigned NOT NULL DEFAULT 0,
  `discount` int(10) unsigned NOT NULL DEFAULT 0,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_PRODUCTDISCOUNT_PRODUCT_PRODUCTS` (`product`),
  KEY `fk_PRODUCTDISCOUNT_DISCOUNT_DISCOUNTS` (`discount`),
  CONSTRAINT `fk_PRODUCTDISCOUNT_DISCOUNT_DISCOUNTS` FOREIGN KEY (`discount`) REFERENCES `discounts` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_PRODUCTDISCOUNT_PRODUCT_PRODUCTS` FOREIGN KEY (`product`) REFERENCES `products` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `productreservesettings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `productreservesettings` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `cod` varchar(50) NOT NULL DEFAULT '',
  `product` int(10) unsigned NOT NULL DEFAULT 0,
  `settings` int(10) unsigned NOT NULL DEFAULT 0,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_PRODUCTRESERVESETTING_PRODUCT_PRODUCTS` (`product`),
  KEY `fk_PRODUCTRESERVESETTING_SETTINGS_RESERVATIONSETTINGS` (`settings`),
  CONSTRAINT `fk_PRODUCTRESERVESETTING_PRODUCT_PRODUCTS` FOREIGN KEY (`product`) REFERENCES `products` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_PRODUCTRESERVESETTING_SETTINGS_RESERVATIONSETTINGS` FOREIGN KEY (`settings`) REFERENCES `reservationsettings` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `products`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `products` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `cod` varchar(50) NOT NULL DEFAULT '',
  `name` varchar(150) NOT NULL DEFAULT '',
  `description` text DEFAULT NULL,
  `image` int(10) unsigned NOT NULL DEFAULT 0,
  `quantity` int(10) unsigned NOT NULL DEFAULT 0,
  `brand` int(10) unsigned NOT NULL DEFAULT 0,
  `category` int(10) unsigned NOT NULL DEFAULT 0,
  `price` int(10) unsigned NOT NULL DEFAULT 0,
  `note` text DEFAULT NULL,
  `weight` double(11,2) NOT NULL DEFAULT 0.00,
  `length` double(11,2) NOT NULL DEFAULT 0.00,
  `width` double(11,2) NOT NULL DEFAULT 0.00,
  `height` double(11,2) NOT NULL DEFAULT 0.00,
  `flgdeleted` int(10) unsigned NOT NULL DEFAULT 0,
  `flgwarehouse` int(10) unsigned DEFAULT 0,
  `flgreserve` int(10) unsigned NOT NULL DEFAULT 0,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_PRODUCT_IMAGE_ATTACHMENT` (`image`),
  KEY `fk_PRODUCT_BRAND_BRANDS` (`brand`),
  KEY `fk_PRODUCT_CATEGORY_CATEGORIES` (`category`),
  KEY `fk_PRODUCT_PRICE_PRICES` (`price`),
  CONSTRAINT `fk_PRODUCT_BRAND_BRANDS` FOREIGN KEY (`brand`) REFERENCES `brands` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_PRODUCT_CATEGORY_CATEGORIES` FOREIGN KEY (`category`) REFERENCES `categories` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_PRODUCT_IMAGE_ATTACHMENT` FOREIGN KEY (`image`) REFERENCES `attachments` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_PRODUCT_PRICE_PRICES` FOREIGN KEY (`price`) REFERENCES `prices` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `producttaxes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `producttaxes` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `cod` varchar(50) NOT NULL DEFAULT '',
  `product` int(10) unsigned NOT NULL DEFAULT 0,
  `tax` double(11,2) NOT NULL DEFAULT 0.00,
  `tax_percent` double(11,2) NOT NULL DEFAULT 0.00,
  `taxdescription` text DEFAULT NULL,
  `currencyid` int(10) unsigned NOT NULL DEFAULT 0,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_PRODUCTTAX_PRODUCT_PRODUCTS` (`product`),
  KEY `fk_PRODUCTTAX_CURRENCY_CURRENCIES` (`currencyid`),
  CONSTRAINT `fk_PRODUCTTAX_CURRENCY_CURRENCIES` FOREIGN KEY (`currencyid`) REFERENCES `currencies` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_PRODUCTTAX_PRODUCT_PRODUCTS` FOREIGN KEY (`product`) REFERENCES `products` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `professionattachments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `professionattachments` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `cod` varchar(50) NOT NULL DEFAULT '',
  `profession` int(10) unsigned NOT NULL DEFAULT 0,
  `attachment` int(10) unsigned NOT NULL DEFAULT 0,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_PROFESSIONATTACHMENTS_PROFESSION_PROFESSIONS` (`profession`),
  KEY `fk_PROFESSIONATTACHMENTS_ATTACHMENT_ATTACHMENTS` (`attachment`),
  CONSTRAINT `fk_PROFESSIONATTACHMENTS_ATTACHMENT_ATTACHMENTS` FOREIGN KEY (`attachment`) REFERENCES `attachments` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_PROFESSIONATTACHMENTS_PROFESSION_PROFESSIONS` FOREIGN KEY (`profession`) REFERENCES `professions` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `professionexperiences`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `professionexperiences` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `cod` varchar(50) NOT NULL DEFAULT '',
  `profession` int(10) unsigned NOT NULL DEFAULT 0,
  `experience` int(10) unsigned NOT NULL DEFAULT 0,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_PROFESSIONEXPERIENCES_PROFESSION_PROFESSIONS` (`profession`),
  KEY `fk_PROFESSIONEXPERIENCES_EXPERIENCE_WORKEXPERIENCES` (`experience`),
  CONSTRAINT `fk_PROFESSIONEXPERIENCES_EXPERIENCE_WORKEXPERIENCES` FOREIGN KEY (`experience`) REFERENCES `workexperiences` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_PROFESSIONEXPERIENCES_PROFESSION_PROFESSIONS` FOREIGN KEY (`profession`) REFERENCES `professions` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
INSERT INTO `professionexperiences` VALUES (1,'CV_GIUSEPPE_SASSONE_INF_EXP1',1,1,'2023-06-22 19:58:50',NULL);
DROP TABLE IF EXISTS `professionreferences`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `professionreferences` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `cod` varchar(50) NOT NULL DEFAULT '',
  `contactreference` int(10) unsigned NOT NULL DEFAULT 0,
  `profession` int(10) unsigned NOT NULL DEFAULT 0,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_PROFESSIONREFERENCES_REFERENCE_CONTACTREFERENCES` (`contactreference`),
  KEY `fk_PROFESSIONREFERENCES_PROFESSION_PROFESSIONS` (`profession`),
  CONSTRAINT `fk_PROFESSIONREFERENCES_PROFESSION_PROFESSIONS` FOREIGN KEY (`profession`) REFERENCES `professions` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_PROFESSIONREFERENCES_REFERENCE_CONTACTREFERENCES` FOREIGN KEY (`contactreference`) REFERENCES `contactreferences` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `professionroles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `professionroles` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `cod` varchar(50) NOT NULL DEFAULT '',
  `gg` int(10) unsigned NOT NULL DEFAULT 0,
  `months` double(11,2) NOT NULL DEFAULT 0.00,
  `profession` int(10) unsigned NOT NULL DEFAULT 0,
  `role` int(10) unsigned NOT NULL DEFAULT 0,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_PROFESSIONSROLES_PROFESSION_PROFESSIONS` (`profession`),
  KEY `fk_PROFESSIONROLE_ROLE_WORKROLES` (`role`),
  CONSTRAINT `fk_PROFESSIONROLE_ROLE_WORKROLES` FOREIGN KEY (`role`) REFERENCES `workroles` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_PROFESSIONSROLES_PROFESSION_PROFESSIONS` FOREIGN KEY (`profession`) REFERENCES `professions` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `professions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `professions` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `cod` varchar(50) NOT NULL DEFAULT '',
  `name` varchar(250) NOT NULL DEFAULT '',
  `description` text DEFAULT '',
  `user` int(10) unsigned NOT NULL DEFAULT 0,
  `address` int(10) unsigned NOT NULL DEFAULT 0,
  `email` int(10) unsigned NOT NULL DEFAULT 0,
  `phone` int(10) unsigned NOT NULL DEFAULT 0,
  `image` int(10) unsigned NOT NULL DEFAULT 0,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_PROFESSION_USER_USERS` (`user`),
  KEY `fk_PROFESSION_ADDRESS_ADDRESSES` (`address`),
  KEY `fk_PROFESSION_EMAIL_CONTACTREFERENCES` (`email`),
  KEY `fk_PROFESSION_PHONE_CONTACTREFERENCES` (`phone`),
  KEY `fk_PROFESSION_IMAGE_ATTACHMENTS` (`image`),
  CONSTRAINT `fk_PROFESSION_ADDRESS_ADDRESSES` FOREIGN KEY (`address`) REFERENCES `addresses` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_PROFESSION_EMAIL_CONTACTREFERENCES` FOREIGN KEY (`email`) REFERENCES `contactreferences` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_PROFESSION_IMAGE_ATTACHMENTS` FOREIGN KEY (`image`) REFERENCES `attachments` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_PROFESSION_PHONE_CONTACTREFERENCES` FOREIGN KEY (`phone`) REFERENCES `contactreferences` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_PROFESSION_USER_USERS` FOREIGN KEY (`user`) REFERENCES `users` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
INSERT INTO `professions` VALUES (1,'CV_GIUSEPPE_SASSONE_INF','Ingegnere Informatico','Mi occupo della realizzazione di piattaforme software per il web',3,4,11,12,4,'2023-06-22 19:58:50',NULL);
DROP TABLE IF EXISTS `professionschools`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `professionschools` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `cod` varchar(50) NOT NULL DEFAULT '',
  `name` varchar(250) NOT NULL DEFAULT '',
  `description` text DEFAULT '',
  `institute` int(10) unsigned NOT NULL DEFAULT 0,
  `profession` int(10) unsigned NOT NULL DEFAULT 0,
  `levelval` int(10) unsigned NOT NULL DEFAULT 0,
  `levelmax` int(10) unsigned NOT NULL DEFAULT 0,
  `leveldesc` varchar(250) NOT NULL DEFAULT '',
  `dtainit` datetime DEFAULT NULL,
  `dtaend` datetime DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_PROFESSIONSCHOOL_PROFESSION_PROFESSIONS` (`profession`),
  KEY `fk_PROFESSIONSCHOOL_INSTITUTE_ACTIVITIES` (`institute`),
  CONSTRAINT `fk_PROFESSIONSCHOOL_INSTITUTE_ACTIVITIES` FOREIGN KEY (`institute`) REFERENCES `activities` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_PROFESSIONSCHOOL_PROFESSION_PROFESSIONS` FOREIGN KEY (`profession`) REFERENCES `professions` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
INSERT INTO `professionschools` VALUES (1,'CV_GIUSEPPE_SASSONE_INF_SCHOOL1','Laurea di I° livello in Ingegneria Informatica','',7,1,94,110,'','2000-01-01 00:00:00','2010-01-01 00:00:00','2023-06-22 19:58:50',NULL);
DROP TABLE IF EXISTS `professionskills`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `professionskills` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `cod` varchar(50) NOT NULL DEFAULT '',
  `gg` int(10) unsigned NOT NULL DEFAULT 0,
  `months` double(11,2) NOT NULL DEFAULT 0.00,
  `profession` int(10) unsigned NOT NULL DEFAULT 0,
  `skill` int(10) unsigned NOT NULL DEFAULT 0,
  `levelval` int(10) unsigned NOT NULL DEFAULT 0,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_PROFESSIONSKILLS_PROFESSION_PROFESSIONS` (`profession`),
  KEY `fk_PROFESSIONSKILLS_SKILL_WORKSKILLS` (`skill`),
  CONSTRAINT `fk_PROFESSIONSKILLS_PROFESSION_PROFESSIONS` FOREIGN KEY (`profession`) REFERENCES `professions` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_PROFESSIONSKILLS_SKILL_WORKSKILLS` FOREIGN KEY (`skill`) REFERENCES `workskills` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `profilepermissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `profilepermissions` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `cod` varchar(250) NOT NULL DEFAULT '',
  `profile` int(10) unsigned NOT NULL DEFAULT 0,
  `permission` int(10) unsigned NOT NULL DEFAULT 0,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_PROFILEPERMISSION_PROFILE_PROFILES` (`profile`),
  KEY `fk_PROFILEPERMISSION_PERMISSION_PERMISSIONS` (`permission`),
  CONSTRAINT `fk_PROFILEPERMISSION_PERMISSION_PERMISSIONS` FOREIGN KEY (`permission`) REFERENCES `permissions` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_PROFILEPERMISSION_PROFILE_PROFILES` FOREIGN KEY (`profile`) REFERENCES `profiles` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `profiles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `profiles` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `cod` varchar(50) NOT NULL DEFAULT '',
  `name` varchar(250) NOT NULL DEFAULT '',
  `description` text DEFAULT '',
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
INSERT INTO `profiles` VALUES (1,'PROFILE','Profilo Utente','Profilo di utente partecipante','2023-06-22 19:56:53',NULL),(2,'SUPERVISOR','Profilo Amministratore','Profilo di utente amministratore','2023-06-22 19:56:53',NULL),(3,'EMPLOYER','Dipendente aziendale','Dipendente di azienda','2023-06-22 19:56:53',NULL),(4,'MANAGER','Manager aziendale','Amministratore di azienda','2023-06-22 19:56:53',NULL);
DROP TABLE IF EXISTS `reservationsettings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `reservationsettings` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `cod` varchar(50) NOT NULL DEFAULT '',
  `dailyweeks` varchar(150) NOT NULL DEFAULT '',
  `dailymonths` varchar(150) NOT NULL DEFAULT '',
  `hhreservefrom` datetime DEFAULT NULL,
  `hhreserveto` datetime DEFAULT NULL,
  `dtafrom` datetime DEFAULT NULL,
  `dtato` datetime DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `serviceattachments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `serviceattachments` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `cod` varchar(50) NOT NULL DEFAULT '',
  `service` int(10) unsigned NOT NULL DEFAULT 0,
  `attachment` int(10) unsigned NOT NULL DEFAULT 0,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_SERVICEATTACHMENT_SERVICE_SERVICES` (`service`),
  KEY `fk_SERVICEATTACHMENT_ATTACHMENT_ATTACHMENTS` (`attachment`),
  CONSTRAINT `fk_SERVICEATTACHMENT_ATTACHMENT_ATTACHMENTS` FOREIGN KEY (`attachment`) REFERENCES `attachments` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_SERVICEATTACHMENT_SERVICE_SERVICES` FOREIGN KEY (`service`) REFERENCES `services` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `servicediscounts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `servicediscounts` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `cod` varchar(50) NOT NULL DEFAULT '',
  `service` int(10) unsigned NOT NULL DEFAULT 0,
  `discount` int(10) unsigned NOT NULL DEFAULT 0,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_SERVICEDISCOUNT_SERVICE_SERVICES` (`service`),
  KEY `fk_SERVICEDISCOUNT_DISCOUNT_DISCOUNTS` (`discount`),
  CONSTRAINT `fk_SERVICEDISCOUNT_DISCOUNT_DISCOUNTS` FOREIGN KEY (`discount`) REFERENCES `discounts` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_SERVICEDISCOUNT_SERVICE_SERVICES` FOREIGN KEY (`service`) REFERENCES `services` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `servicereservesettings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `servicereservesettings` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `cod` varchar(50) NOT NULL DEFAULT '',
  `service` int(10) unsigned NOT NULL DEFAULT 0,
  `settings` int(10) unsigned NOT NULL DEFAULT 0,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_SERVICERESERVESETTING_SERVICE_SERVICES` (`service`),
  KEY `fk_SERVICERESERVESETTING_SETTINGS_RESERVESETTINGS` (`settings`),
  CONSTRAINT `fk_SERVICERESERVESETTING_SERVICE_SERVICES` FOREIGN KEY (`service`) REFERENCES `services` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_SERVICERESERVESETTING_SETTINGS_RESERVESETTINGS` FOREIGN KEY (`settings`) REFERENCES `reservationsettings` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `services`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `services` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `cod` varchar(50) NOT NULL DEFAULT '',
  `name` varchar(150) NOT NULL DEFAULT '',
  `description` text DEFAULT NULL,
  `image` int(10) unsigned NOT NULL DEFAULT 0,
  `category` int(10) unsigned NOT NULL DEFAULT 0,
  `price` int(10) unsigned NOT NULL DEFAULT 0,
  `note` text DEFAULT NULL,
  `flgdeleted` int(10) unsigned NOT NULL DEFAULT 0,
  `flgreserve` int(10) unsigned NOT NULL DEFAULT 0,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_SERVICE_IMAGE_ATTACHMENT` (`image`),
  KEY `fk_SERVICE_CATEGORY_CATEGORIES` (`category`),
  KEY `fk_SERVICE_PRICE_PRICES` (`price`),
  CONSTRAINT `fk_SERVICE_CATEGORY_CATEGORIES` FOREIGN KEY (`category`) REFERENCES `categories` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_SERVICE_IMAGE_ATTACHMENT` FOREIGN KEY (`image`) REFERENCES `attachments` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_SERVICE_PRICE_PRICES` FOREIGN KEY (`price`) REFERENCES `prices` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `servicetaxes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `servicetaxes` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `cod` varchar(50) NOT NULL DEFAULT '',
  `service` int(10) unsigned NOT NULL DEFAULT 0,
  `tax` double(11,2) NOT NULL DEFAULT 0.00,
  `tax_percent` double(11,2) NOT NULL DEFAULT 0.00,
  `taxdescription` text DEFAULT NULL,
  `currencyid` int(10) unsigned NOT NULL DEFAULT 0,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_SERVICETAX_SERVICE_SERVICES` (`service`),
  KEY `fk_SERVICETAX_CURRENCY_CURRENCIES` (`currencyid`),
  CONSTRAINT `fk_SERVICETAX_CURRENCY_CURRENCIES` FOREIGN KEY (`currencyid`) REFERENCES `currencies` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_SERVICETAX_SERVICE_SERVICES` FOREIGN KEY (`service`) REFERENCES `services` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `testfks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `testfks` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `cod` varchar(50) NOT NULL DEFAULT '',
  `title` varchar(250) NOT NULL DEFAULT '',
  `description` varchar(500) NOT NULL DEFAULT '',
  `result` int(10) unsigned NOT NULL DEFAULT 1,
  `test` int(10) unsigned NOT NULL DEFAULT 0,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_TESTFKS_TEST_TESTS` (`test`),
  CONSTRAINT `fk_TESTFKS_TEST_TESTS` FOREIGN KEY (`test`) REFERENCES `tests` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
INSERT INTO `testfks` VALUES (1,'FK001','PRIMA FK','RIGA DA VERIFICARE AL PRIMO TEST FK',1,1,'2023-06-22 19:55:46',NULL),(2,'FK002','SECONDA FK','RIGA DA VERIFICARE AL SECONDO TEST FK',1,2,'2023-06-22 19:55:46',NULL);
DROP TABLE IF EXISTS `tests`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tests` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `cod` varchar(50) NOT NULL DEFAULT '',
  `title` varchar(250) NOT NULL DEFAULT '',
  `description` varchar(500) NOT NULL DEFAULT '',
  `result` int(10) unsigned NOT NULL DEFAULT 1,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
INSERT INTO `tests` VALUES (1,'ENTITY001','PRIMA ENTITY','RIGA DA VERIFICARE AL PRIMO TEST ENTITY',1,'2023-06-22 19:55:24',NULL),(2,'ENTITY002','SECONDA ENTITY','RIGA DA VERIFICARE AL SECONDO TEST ENTITY',1,'2023-06-22 19:55:24',NULL);
DROP TABLE IF EXISTS `testtypologicals`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `testtypologicals` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `cod` varchar(50) NOT NULL DEFAULT '',
  `title` varchar(250) NOT NULL DEFAULT '',
  `symbol` varchar(50) NOT NULL DEFAULT '',
  `flgused` int(10) unsigned NOT NULL DEFAULT 1,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
INSERT INTO `testtypologicals` VALUES (1,'COD_TEST1','Test 1','',1,'2023-06-22 19:55:46',NULL),(2,'COD_TEST2','Test 2','',1,'2023-06-22 19:55:46',NULL);
DROP TABLE IF EXISTS `ticketattachments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ticketattachments` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `cod` varchar(50) NOT NULL DEFAULT '',
  `ticket` int(10) unsigned NOT NULL DEFAULT 0,
  `attachment` int(10) unsigned NOT NULL DEFAULT 0,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_TICKETATTACHMENT_TICKET_TICKETS` (`ticket`),
  KEY `fk_TICKETATTACHMENT_ATTACHMENT_ATTACHMENTS` (`attachment`),
  CONSTRAINT `fk_TICKETATTACHMENT_ATTACHMENT_ATTACHMENTS` FOREIGN KEY (`attachment`) REFERENCES `attachments` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_TICKETATTACHMENT_TICKET_TICKETS` FOREIGN KEY (`ticket`) REFERENCES `tickets` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `ticketdiscounts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ticketdiscounts` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `cod` varchar(50) NOT NULL DEFAULT '',
  `ticket` int(10) unsigned NOT NULL DEFAULT 0,
  `discount` int(10) unsigned NOT NULL DEFAULT 0,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_TICKETDISCOUNT_TICKET_TICKETS` (`ticket`),
  KEY `fk_TICKETDISCOUNT_DISCOUNT_DISCOUNTS` (`discount`),
  CONSTRAINT `fk_TICKETDISCOUNT_DISCOUNT_DISCOUNTS` FOREIGN KEY (`discount`) REFERENCES `discounts` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_TICKETDISCOUNT_TICKET_TICKETS` FOREIGN KEY (`ticket`) REFERENCES `tickets` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `ticketreservesettings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ticketreservesettings` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `cod` varchar(50) NOT NULL DEFAULT '',
  `ticket` int(10) unsigned NOT NULL DEFAULT 0,
  `settings` int(10) unsigned NOT NULL DEFAULT 0,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_TICKETRESERVESETTING_TICKET_TICKETS` (`ticket`),
  KEY `fk_TICKETRESERVESETTING_SETTINGS_RESERVATIONSETTINGS` (`settings`),
  CONSTRAINT `fk_TICKETRESERVESETTING_SETTINGS_RESERVATIONSETTINGS` FOREIGN KEY (`settings`) REFERENCES `reservationsettings` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_TICKETRESERVESETTING_TICKET_TICKETS` FOREIGN KEY (`ticket`) REFERENCES `tickets` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `tickets`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tickets` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `cod` varchar(50) NOT NULL DEFAULT '',
  `name` varchar(150) NOT NULL DEFAULT '',
  `description` text DEFAULT NULL,
  `image` int(10) unsigned NOT NULL DEFAULT 0,
  `quantity` int(10) unsigned NOT NULL DEFAULT 0,
  `event` int(10) unsigned NOT NULL DEFAULT 0,
  `category` int(10) unsigned NOT NULL DEFAULT 0,
  `price` int(10) unsigned NOT NULL DEFAULT 0,
  `note` text DEFAULT NULL,
  `dtafrom` datetime DEFAULT NULL,
  `dtato` datetime DEFAULT NULL,
  `flgdeleted` int(10) unsigned NOT NULL DEFAULT 0,
  `flgwarehouse` int(10) unsigned DEFAULT 0,
  `flgreserve` int(10) unsigned NOT NULL DEFAULT 0,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_TICKET_EVENT_EVENTS` (`event`),
  KEY `fk_TICKET_CATEGORY_CATEGORIES` (`category`),
  KEY `fk_TICKET_IMAGE_ATTACHMENTS` (`image`),
  KEY `fk_TICKET_PRICE_PRICES` (`price`),
  CONSTRAINT `fk_TICKET_CATEGORY_CATEGORIES` FOREIGN KEY (`category`) REFERENCES `categories` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_TICKET_EVENT_EVENTS` FOREIGN KEY (`event`) REFERENCES `events` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_TICKET_IMAGE_ATTACHMENTS` FOREIGN KEY (`image`) REFERENCES `attachments` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_TICKET_PRICE_PRICES` FOREIGN KEY (`price`) REFERENCES `prices` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `tickettaxes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tickettaxes` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `cod` varchar(50) NOT NULL DEFAULT '',
  `ticket` int(10) unsigned NOT NULL DEFAULT 0,
  `tax` double(11,2) NOT NULL DEFAULT 0.00,
  `tax_percent` double(11,2) NOT NULL DEFAULT 0.00,
  `taxdescription` text DEFAULT NULL,
  `currencyid` int(10) unsigned NOT NULL DEFAULT 0,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_TICKETTAX_TICKET_TICKETS` (`ticket`),
  KEY `fk_TICKETTAX_CURRENCY_CURRENCIES` (`currencyid`),
  CONSTRAINT `fk_TICKETTAX_CURRENCY_CURRENCIES` FOREIGN KEY (`currencyid`) REFERENCES `currencies` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_TICKETTAX_TICKET_TICKETS` FOREIGN KEY (`ticket`) REFERENCES `tickets` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `tpactivities`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tpactivities` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `cod` varchar(50) NOT NULL DEFAULT '',
  `title` varchar(250) NOT NULL DEFAULT '',
  `symbol` varchar(50) NOT NULL DEFAULT '',
  `flgused` int(10) unsigned NOT NULL DEFAULT 1,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
INSERT INTO `tpactivities` VALUES (1,'ent','Government Institution','',1,'2023-06-22 19:56:52',NULL),(2,'com','Commercial Activity','',1,'2023-06-22 19:56:52',NULL),(3,'sin','Single Activity','',1,'2023-06-22 19:56:52',NULL),(4,'ass','Association','',1,'2023-06-22 19:56:52',NULL),(5,'per','Personal','',1,'2023-06-22 19:56:52',NULL),(6,'prg','Project','',1,'2023-06-22 19:56:52',NULL);
DROP TABLE IF EXISTS `tpactivityrelations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tpactivityrelations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `cod` varchar(50) NOT NULL DEFAULT '',
  `title` varchar(250) NOT NULL DEFAULT '',
  `symbol` varchar(50) NOT NULL DEFAULT '',
  `flgused` int(10) unsigned NOT NULL DEFAULT 1,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
INSERT INTO `tpactivityrelations` VALUES (1,'PARTNER','partner','partner.png',1,'2023-06-22 19:56:52',NULL),(2,'GENERIC','generic','generic.png',1,'2023-06-22 19:56:52',NULL),(3,'WORK_EXPERIENCE','work experience','work_experience.png',1,'2023-06-22 19:56:52',NULL);
DROP TABLE IF EXISTS `tpaddresses`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tpaddresses` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `cod` varchar(50) NOT NULL DEFAULT '',
  `title` varchar(250) NOT NULL DEFAULT '',
  `symbol` varchar(50) NOT NULL DEFAULT '',
  `category` int(10) unsigned NOT NULL DEFAULT 0,
  `flgused` int(10) unsigned NOT NULL DEFAULT 1,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
INSERT INTO `tpaddresses` VALUES (1,'home','Permanent Address','',1,1,'2023-06-22 19:56:30',NULL),(2,'domicile','Domicile Address','',1,1,'2023-06-22 19:56:30',NULL),(3,'born','Born Address','',1,1,'2023-06-22 19:56:30',NULL),(4,'legal','Registered Office','',2,1,'2023-06-22 19:56:30',NULL),(5,'office','Office','',2,1,'2023-06-22 19:56:30',NULL),(6,'secondary_office','Registerd Office Secondary','',2,1,'2023-06-22 19:56:30',NULL),(7,'work_site','Work Site Experience','',3,1,'2023-06-22 19:56:30',NULL),(8,'instruction_site','Instruction Site Experience','',3,1,'2023-06-22 19:56:30',NULL),(9,'secondary_home','Other home','',1,1,'2023-06-22 19:56:30',NULL);
DROP TABLE IF EXISTS `tpattachments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tpattachments` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `cod` varchar(50) NOT NULL DEFAULT '',
  `title` varchar(250) NOT NULL DEFAULT '',
  `symbol` varchar(50) NOT NULL DEFAULT '',
  `flgused` int(10) unsigned NOT NULL DEFAULT 1,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
INSERT INTO `tpattachments` VALUES (1,'ci','Identity Card','',1,'2023-06-22 19:55:51',NULL),(2,'passport','Passport Card','',1,'2023-06-22 19:55:51',NULL),(3,'cf','Fiscal Code','',1,'2023-06-22 19:55:51',NULL),(4,'address','Permanent Address Certificate','',1,'2023-06-22 19:55:51',NULL),(5,'cv','Curriculum Vitae','',1,'2023-06-22 19:55:51',NULL),(6,'image','Personal Picture','',1,'2023-06-22 19:55:51',NULL),(7,'book','Personal Book','',1,'2023-06-22 19:55:51',NULL),(8,'audio','Audio','',1,'2023-06-22 19:55:51',NULL),(9,'video','Video','',1,'2023-06-22 19:55:51',NULL),(10,'doc','Document','',1,'2023-06-22 19:55:51',NULL),(11,'song','Song','',1,'2023-06-22 19:55:51',NULL),(12,'movie','Movie Film','',1,'2023-06-22 19:55:51',NULL),(13,'driving','Driving license','',1,'2023-06-22 19:55:51',NULL);
DROP TABLE IF EXISTS `tpcats`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tpcats` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `cod` varchar(50) NOT NULL DEFAULT '',
  `title` varchar(250) NOT NULL DEFAULT '',
  `symbol` varchar(50) NOT NULL DEFAULT '',
  `flgused` int(10) unsigned NOT NULL DEFAULT 1,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
INSERT INTO `tpcats` VALUES (1,'gov','Government and Law','',1,'2023-06-22 19:56:52',NULL),(2,'art','Art and Literature','',1,'2023-06-22 19:56:52',NULL),(3,'fod','Food and Catering','',1,'2023-06-22 19:56:52',NULL),(4,'wrk','Work and Commerce','',1,'2023-06-22 19:56:52',NULL),(5,'sch','Lessons and Instruction','',1,'2023-06-22 19:56:52',NULL),(6,'med','Biology and Drugs','',1,'2023-06-22 19:56:52',NULL),(7,'sci','Science and Astronomy','',1,'2023-06-22 19:56:52',NULL),(8,'his','History','',1,'2023-06-22 19:56:52',NULL),(9,'geo','Geography','',1,'2023-06-22 19:56:52',NULL),(10,'tec','Technology','',1,'2023-06-22 19:56:52',NULL),(11,'msc','Music','',1,'2023-06-22 19:56:52',NULL),(12,'trv','Travel and Vueling','',1,'2023-06-22 19:56:52',NULL),(13,'fin','Financial and Business','',1,'2023-06-22 19:56:52',NULL),(14,'bea','Beauty and Body','',1,'2023-06-22 19:56:52',NULL);
DROP TABLE IF EXISTS `tpcontactreferences`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tpcontactreferences` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `cod` varchar(50) NOT NULL DEFAULT '',
  `title` varchar(250) NOT NULL DEFAULT '',
  `symbol` varchar(50) NOT NULL DEFAULT '',
  `flgused` int(10) unsigned NOT NULL DEFAULT 1,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
INSERT INTO `tpcontactreferences` VALUES (1,'tel','Home Phone','tel.png',1,'2023-06-22 19:56:52',NULL),(2,'cel','Mobile Phone','cel.png',1,'2023-06-22 19:56:52',NULL),(3,'fax','Fax','fax.png',1,'2023-06-22 19:56:52',NULL),(4,'email','Email','email.png',1,'2023-06-22 19:56:52',NULL),(5,'site','Web Site','website.png',1,'2023-06-22 19:56:52',NULL),(6,'social','Social Profile','social.png',1,'2023-06-22 19:56:52',NULL),(7,'blog','Blog Page','blog.png',1,'2023-06-22 19:56:52',NULL),(8,'pec','Email PEC','pec.png',1,'2023-06-22 19:56:52',NULL);
DROP TABLE IF EXISTS `tpevents`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tpevents` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `cod` varchar(50) NOT NULL DEFAULT '',
  `title` varchar(250) NOT NULL DEFAULT '',
  `symbol` varchar(50) NOT NULL DEFAULT '',
  `flgused` int(10) unsigned NOT NULL DEFAULT 1,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
INSERT INTO `tpevents` VALUES (1,'evt','Event','',1,'2023-06-22 19:56:59',NULL),(2,'app','Appointment','',1,'2023-06-22 19:56:59',NULL),(3,'not','Remember Note','',1,'2023-06-22 19:56:59',NULL),(4,'dta','Remember Date','',1,'2023-06-22 19:56:59',NULL),(5,'bnd','Public Contest','',1,'2023-06-22 19:56:59',NULL),(6,'prg','Project','',1,'2023-06-22 19:56:59',NULL),(7,'off','Work Offers','',1,'2023-06-22 19:56:59',NULL),(8,'cnt','Contest','',1,'2023-06-22 19:56:59',NULL),(9,'prm','Promotion','',1,'2023-06-22 19:56:59',NULL);
DROP TABLE IF EXISTS `tppaymentmethods`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tppaymentmethods` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `cod` varchar(50) NOT NULL DEFAULT '',
  `title` varchar(250) NOT NULL DEFAULT '',
  `symbol` varchar(50) NOT NULL DEFAULT '',
  `flgused` int(10) unsigned NOT NULL DEFAULT 1,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
INSERT INTO `tppaymentmethods` VALUES (1,'web','Web Account','',1,'2023-06-22 19:58:29',NULL),(2,'bnk','Bank','',1,'2023-06-22 19:58:29',NULL),(3,'crd','Credit Card','',1,'2023-06-22 19:58:29',NULL);
DROP TABLE IF EXISTS `tppayments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tppayments` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `cod` varchar(50) NOT NULL DEFAULT '',
  `title` varchar(250) NOT NULL DEFAULT '',
  `symbol` varchar(50) NOT NULL DEFAULT '',
  `flgused` int(10) unsigned NOT NULL DEFAULT 1,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
INSERT INTO `tppayments` VALUES (1,'bon','bonifico','',1,'2023-06-22 19:58:29',NULL),(2,'bol','bollettino','',1,'2023-06-22 19:58:29',NULL),(3,'rid','RID o SEPA Direct Debit','',1,'2023-06-22 19:58:29',NULL),(4,'web','Remote Banking o Home Banking','',1,'2023-06-22 19:58:29',NULL),(5,'cnt','Contanti','',1,'2023-06-22 19:58:29',NULL),(6,'ass','Assegno','',1,'2023-06-22 19:58:29',NULL),(7,'asc','Assegno Circolare','',1,'2023-06-22 19:58:29',NULL),(8,'cmb','Cambiali o Tratte','',1,'2023-06-22 19:58:29',NULL),(9,'rcv','Ricevuta Bancaria','',1,'2023-06-22 19:58:29',NULL),(10,'mav','MAV','',1,'2023-06-22 19:58:29',NULL);
DROP TABLE IF EXISTS `tpskills`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tpskills` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `cod` varchar(50) NOT NULL DEFAULT '',
  `title` varchar(250) NOT NULL DEFAULT '',
  `symbol` varchar(50) NOT NULL DEFAULT '',
  `flgused` int(10) unsigned NOT NULL DEFAULT 1,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
INSERT INTO `tpskills` VALUES (1,'knw','Knowledgments','',1,'2023-06-22 19:58:42',NULL),(2,'lan','Languages','',1,'2023-06-22 19:58:42',NULL),(3,'prd','Products or Instruments','',1,'2023-06-22 19:58:42',NULL);
DROP TABLE IF EXISTS `tpsocialreferences`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tpsocialreferences` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `cod` varchar(50) NOT NULL DEFAULT '',
  `title` varchar(250) NOT NULL DEFAULT '',
  `symbol` varchar(50) NOT NULL DEFAULT '',
  `flgused` int(10) unsigned NOT NULL DEFAULT 1,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
INSERT INTO `tpsocialreferences` VALUES (1,'FB','facebook','facebook.png',1,'2023-06-22 19:56:52',NULL),(2,'TW','twitter','twitter.png',1,'2023-06-22 19:56:52',NULL),(3,'LI','linked in','linkedin.png',1,'2023-06-22 19:56:52',NULL),(4,'GP','google plus','googleplus.png',1,'2023-06-22 19:56:52',NULL),(5,'PI','pinterest','pinterest.png',1,'2023-06-22 19:56:52',NULL),(6,'YB','youtube','youtube.png',1,'2023-06-22 19:56:52',NULL),(7,'IS','instagram','instagram.png',1,'2023-06-22 19:56:52',NULL),(8,'MU','meetup','meetup.png',1,'2023-06-22 19:56:52',NULL),(9,'TB','tumblr','tumblr.png',1,'2023-06-22 19:56:52',NULL),(10,'WA','whatsapp','whatsapp.png',1,'2023-06-22 19:56:52',NULL);
DROP TABLE IF EXISTS `tpuserrelations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tpuserrelations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `cod` varchar(50) NOT NULL DEFAULT '',
  `title` varchar(250) NOT NULL DEFAULT '',
  `symbol` varchar(50) NOT NULL DEFAULT '',
  `flgused` int(10) unsigned NOT NULL DEFAULT 1,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
INSERT INTO `tpuserrelations` VALUES (1,'FRIENDSHIP','friendship','friendship.png',1,'2023-06-22 19:56:52',NULL),(2,'MARRIAGE','marriage','marriage.png',1,'2023-06-22 19:56:52',NULL),(3,'FAMILY','family','family.png',1,'2023-06-22 19:56:52',NULL),(4,'KNOWN','known','known.png',1,'2023-06-22 19:56:52',NULL);
DROP TABLE IF EXISTS `tpwebpayments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tpwebpayments` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `cod` varchar(50) NOT NULL DEFAULT '',
  `title` varchar(250) NOT NULL DEFAULT '',
  `symbol` varchar(50) NOT NULL DEFAULT '',
  `flgused` int(10) unsigned NOT NULL DEFAULT 1,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
INSERT INTO `tpwebpayments` VALUES (1,'paypal','Paypal','',1,'2023-06-22 19:58:29',NULL),(2,'postefinance','Postefinance','',0,'2023-06-22 19:58:29',NULL),(3,'nexy','Nexy','',0,'2023-06-22 19:58:29',NULL),(4,'twint','Twint','',0,'2023-06-22 19:58:29',NULL),(5,'sofort','Sofort','',0,'2023-06-22 19:58:29',NULL);
DROP TABLE IF EXISTS `useraddresses`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `useraddresses` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `cod` varchar(50) NOT NULL DEFAULT '',
  `flgprincipal` int(10) unsigned NOT NULL DEFAULT 0,
  `address` int(10) unsigned NOT NULL DEFAULT 0,
  `tpaddress` int(10) unsigned NOT NULL DEFAULT 0,
  `user` int(10) unsigned NOT NULL DEFAULT 0,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_USERADDRESS_ADDRESS_ADDRESSES` (`address`),
  KEY `fk_USERADDRESS_TPADDRESS_TPADDRESSES` (`tpaddress`),
  KEY `fk_USERADDRESS_USER_USERS` (`user`),
  CONSTRAINT `fk_USERADDRESS_ADDRESS_ADDRESSES` FOREIGN KEY (`address`) REFERENCES `addresses` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_USERADDRESS_TPADDRESS_TPADDRESSES` FOREIGN KEY (`tpaddress`) REFERENCES `tpaddresses` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_USERADDRESS_USER_USERS` FOREIGN KEY (`user`) REFERENCES `users` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
INSERT INTO `useraddresses` VALUES (1,'USADD1',1,1,0,1,'2023-06-22 19:56:52',NULL),(2,'GIUSASSO00_HOME_1',1,2,0,3,'2023-06-22 19:56:53',NULL);
DROP TABLE IF EXISTS `userattachments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `userattachments` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `cod` varchar(50) NOT NULL DEFAULT '',
  `flgprincipal` int(10) unsigned NOT NULL DEFAULT 0,
  `attachment` int(10) unsigned NOT NULL DEFAULT 0,
  `tpattachment` int(10) unsigned NOT NULL DEFAULT 0,
  `user` int(10) unsigned NOT NULL DEFAULT 0,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_USERATTACHMENT_ATTACHMENT_ATTACHMENTS` (`attachment`),
  KEY `fk_USERATTACHMENT_USER_USERS` (`user`),
  KEY `fk_USERATTACHMENT_TPATTACHMENT_TPATTACHMENTS` (`tpattachment`),
  CONSTRAINT `fk_USERATTACHMENT_ATTACHMENT_ATTACHMENTS` FOREIGN KEY (`attachment`) REFERENCES `attachments` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_USERATTACHMENT_TPATTACHMENT_TPATTACHMENTS` FOREIGN KEY (`tpattachment`) REFERENCES `tpattachments` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_USERATTACHMENT_USER_USERS` FOREIGN KEY (`user`) REFERENCES `users` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
INSERT INTO `userattachments` VALUES (1,'USATT1',1,1,6,1,'2023-06-22 19:56:52',NULL),(2,'GIUSASSO00_PROFILE_IMAGE_1',1,2,6,3,'2023-06-22 19:56:53',NULL);
DROP TABLE IF EXISTS `useroauthsocials`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `useroauthsocials` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `cod` varchar(50) NOT NULL DEFAULT '',
  `oauthid` varchar(250) NOT NULL DEFAULT '',
  `tpsocialreference` int(10) unsigned NOT NULL DEFAULT 0,
  `user` int(10) unsigned NOT NULL DEFAULT 0,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_USEROAUTHSOCIAL_USER_USERS` (`user`),
  KEY `fk_USEROAUTHSOCIAL_TPSOCIAL_TPSOCIALREFERENCES` (`tpsocialreference`),
  CONSTRAINT `fk_USEROAUTHSOCIAL_TPSOCIAL_TPSOCIALREFERENCES` FOREIGN KEY (`tpsocialreference`) REFERENCES `tpsocialreferences` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_USEROAUTHSOCIAL_USER_USERS` FOREIGN KEY (`user`) REFERENCES `users` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
INSERT INTO `useroauthsocials` VALUES (1,'OAUTH_GIUSASSO00_GOOGLE','104155857554057665469',4,3,'2023-06-22 19:56:53',NULL),(2,'OAUTH_GIUSASSO00_FACEBOOK','10225186616802742',1,3,'2023-06-22 19:56:53',NULL);
DROP TABLE IF EXISTS `userprofiles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `userprofiles` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `cod` varchar(250) NOT NULL DEFAULT '',
  `user` int(10) unsigned NOT NULL DEFAULT 0,
  `profile` int(10) unsigned NOT NULL DEFAULT 0,
  `flgdefault` int(10) unsigned NOT NULL DEFAULT 0,
  `activity` int(10) unsigned NOT NULL DEFAULT 0,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_USERPROFILE_PROFILE_PROFILES` (`profile`),
  KEY `fk_USERPROFILE_USER_USERS` (`user`),
  KEY `fk_USERPROFILE_ACTIVITY_ACTIVITIES` (`activity`),
  CONSTRAINT `fk_USERPROFILE_ACTIVITY_ACTIVITIES` FOREIGN KEY (`activity`) REFERENCES `activities` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_USERPROFILE_PROFILE_PROFILES` FOREIGN KEY (`profile`) REFERENCES `profiles` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_USERPROFILE_USER_USERS` FOREIGN KEY (`user`) REFERENCES `users` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
INSERT INTO `userprofiles` VALUES (1,'test1@gmail.com_PROFILE',1,1,1,0,'2023-06-22 19:56:53',NULL),(2,'test2@gmail.com_PROFILE',2,1,0,0,'2023-06-22 19:56:53',NULL),(3,'test2@gmail.com_SUPERVISOR',2,2,1,0,'2023-06-22 19:56:53',NULL),(5,'test1@gmail.com_EMPLOYER_1',1,3,0,1,'2023-06-22 19:56:53',NULL),(6,'test1@gmail.com_MANAGER_2',1,4,0,2,'2023-06-22 19:56:53',NULL),(7,'test2@gmail.com_EMPLOYER_2',2,3,0,2,'2023-06-22 19:56:53',NULL),(8,'test2@gmail.com_MANAGER_1',2,4,0,1,'2023-06-22 19:56:53',NULL),(9,'giuseppesassone00@gmail.com_PROFILE',3,1,0,0,'2023-06-22 19:56:53',NULL),(10,'giuseppesassone00@gmail.com_SUPERVISOR',3,2,1,0,'2023-06-22 19:56:53',NULL),(12,'giuseppesassone00@gmail.com_EMPLOYER_3',3,3,0,3,'2023-06-22 19:56:53',NULL),(13,'giuseppesassone00@gmail.com_MANAGER_3',3,4,0,3,'2023-06-22 19:56:53',NULL);
DROP TABLE IF EXISTS `userreferences`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `userreferences` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `cod` varchar(50) NOT NULL DEFAULT '',
  `flgprincipal` int(10) unsigned NOT NULL DEFAULT 0,
  `contactreference` int(10) unsigned NOT NULL DEFAULT 0,
  `tpcontactreference` int(10) unsigned NOT NULL DEFAULT 0,
  `user` int(10) unsigned NOT NULL DEFAULT 0,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_USERREFERENCE_REFERENCE_CONTACTREFERENCES` (`contactreference`),
  KEY `fk_USERREFERENCE_USER_USERS` (`user`),
  KEY `fk_USERREFERENCE_TPREFERENCE_TPCONTACTREFERENCES` (`tpcontactreference`),
  CONSTRAINT `fk_USERREFERENCE_REFERENCE_CONTACTREFERENCES` FOREIGN KEY (`contactreference`) REFERENCES `contactreferences` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_USERREFERENCE_TPREFERENCE_TPCONTACTREFERENCES` FOREIGN KEY (`tpcontactreference`) REFERENCES `tpcontactreferences` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_USERREFERENCE_USER_USERS` FOREIGN KEY (`user`) REFERENCES `users` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
INSERT INTO `userreferences` VALUES (1,'USCTR1',1,1,4,1,'2023-06-22 19:56:52',NULL),(2,'USCTR2',1,2,4,2,'2023-06-22 19:56:52',NULL),(3,'USCTR3',1,3,2,1,'2023-06-22 19:56:52',NULL),(4,'USCTR4',1,4,2,2,'2023-06-22 19:56:52',NULL),(5,'GIUSASSO00_EMAIL_1',1,5,4,3,'2023-06-22 19:56:53',NULL),(6,'GIUSASSO00_EMAIL_2',0,6,4,3,'2023-06-22 19:56:53',NULL),(7,'GIUSASSO00_CEL_1',1,7,2,3,'2023-06-22 19:56:53',NULL),(8,'GIUSASSO00_LINKEDIN_1',1,8,6,3,'2023-06-22 19:56:53',NULL);
DROP TABLE IF EXISTS `userrelationpermissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `userrelationpermissions` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `cod` varchar(250) NOT NULL DEFAULT '',
  `userrelation` int(10) unsigned NOT NULL DEFAULT 0,
  `permission` int(10) unsigned NOT NULL DEFAULT 0,
  `direction` int(10) unsigned NOT NULL DEFAULT 0,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_USERRELATIONPERMISSION_USERELATION_RELATIONS` (`userrelation`),
  KEY `fk_USERRELATIONPERMISSION_PERMISSION_PERMISSIONS` (`permission`),
  CONSTRAINT `fk_USERRELATIONPERMISSION_PERMISSION_PERMISSIONS` FOREIGN KEY (`permission`) REFERENCES `permissions` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_USERRELATIONPERMISSION_USERELATION_RELATIONS` FOREIGN KEY (`userrelation`) REFERENCES `userrelations` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `userrelations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `userrelations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `cod` varchar(250) NOT NULL DEFAULT '',
  `user1` int(10) unsigned NOT NULL DEFAULT 0,
  `user2` int(10) unsigned NOT NULL DEFAULT 0,
  `tprelation` int(10) unsigned NOT NULL DEFAULT 0,
  `inforelation1` varchar(250) NOT NULL DEFAULT '',
  `inforelation2` varchar(250) NOT NULL DEFAULT '',
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_USERRELATION_USER1_USERS` (`user1`),
  KEY `fk_USERRELATION_USER2_USERS` (`user2`),
  KEY `fk_USERRELATION_TPRELATION_TPRELATIONS` (`tprelation`),
  CONSTRAINT `fk_USERRELATION_TPRELATION_TPRELATIONS` FOREIGN KEY (`tprelation`) REFERENCES `tpuserrelations` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_USERRELATION_USER1_USERS` FOREIGN KEY (`user1`) REFERENCES `users` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_USERRELATION_USER2_USERS` FOREIGN KEY (`user2`) REFERENCES `users` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `userreports`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `userreports` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `codoperation` varchar(50) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `sessionid` varchar(50) DEFAULT NULL,
  `ip` varchar(50) DEFAULT NULL,
  `os` varchar(50) DEFAULT NULL,
  `browser` varchar(50) DEFAULT NULL,
  `browser_version` varchar(10) DEFAULT NULL,
  `user` int(10) unsigned NOT NULL DEFAULT 0,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_USERREPORTS_USER_USERS` (`user`),
  CONSTRAINT `fk_USERREPORTS_USER_USERS` FOREIGN KEY (`user`) REFERENCES `users` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(50) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `passclean` varchar(255) DEFAULT NULL,
  `cf` varchar(50) NOT NULL DEFAULT '',
  `name` varchar(50) NOT NULL DEFAULT '',
  `surname` varchar(50) NOT NULL DEFAULT '',
  `sex` varchar(1) NOT NULL DEFAULT '',
  `born` date DEFAULT NULL,
  `flgtest` int(10) unsigned NOT NULL DEFAULT 0,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
INSERT INTO `users` VALUES (1,'test1@gmail.com','a6b3dfeaeb17f278913b046d537a6ad4b5633203','NWtMZy82WDkxcVF1SXJMWVZEN0VOZz09','CF001','Primo','Account','M','1980-01-01',1,'2023-06-22 19:56:52',NULL),(2,'test2@gmail.com','91fc2c0ec7669138d0431d357f87d32f834123dd','TnkvVlFnRU5mdnpBeGZQZ1NDdk94UT09','CF002','Secondo','Account','F','1980-01-01',1,'2023-06-22 19:56:52',NULL),(3,'giuseppesassone00@gmail.com','56901c92258fb247917db9521358543f8be013b2','V0RMdkdMTTduRzMyTUh5Y21ETURBQT09','SSSGPP81E25E919B','Giuseppe','Sassone','M','1981-05-25',0,'2023-06-22 19:56:53',NULL);
DROP TABLE IF EXISTS `workactivities`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `workactivities` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `cod` varchar(50) NOT NULL DEFAULT '',
  `activity` int(10) unsigned NOT NULL DEFAULT 0,
  `image` int(10) unsigned NOT NULL DEFAULT 0,
  `home` int(10) unsigned NOT NULL DEFAULT 0,
  `phone` int(10) unsigned NOT NULL DEFAULT 0,
  `email` int(10) unsigned NOT NULL DEFAULT 0,
  `website` int(10) unsigned NOT NULL DEFAULT 0,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_WORKACTIVITY_ACTIVITY_ACTIVITIES` (`activity`),
  KEY `fk_WORKACTIVITY_IMAGE_ATTACHMENTS` (`image`),
  KEY `fk_WORKACTIVITY_HOME_ADDRESSES` (`home`),
  KEY `fk_WORKACTIVITY_PHONE_CONTACTREFERENCES` (`phone`),
  KEY `fk_WORKACTIVITY_EMAIL_CONTACTREFERENCES` (`email`),
  KEY `fk_WORKACTIVITY_WEBSITE_CONTACTREFERENCES` (`website`),
  CONSTRAINT `fk_WORKACTIVITY_ACTIVITY_ACTIVITIES` FOREIGN KEY (`activity`) REFERENCES `activities` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_WORKACTIVITY_EMAIL_CONTACTREFERENCES` FOREIGN KEY (`email`) REFERENCES `contactreferences` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_WORKACTIVITY_HOME_ADDRESSES` FOREIGN KEY (`home`) REFERENCES `addresses` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_WORKACTIVITY_IMAGE_ATTACHMENTS` FOREIGN KEY (`image`) REFERENCES `attachments` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_WORKACTIVITY_PHONE_CONTACTREFERENCES` FOREIGN KEY (`phone`) REFERENCES `contactreferences` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_WORKACTIVITY_WEBSITE_CONTACTREFERENCES` FOREIGN KEY (`website`) REFERENCES `contactreferences` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
INSERT INTO `workactivities` VALUES (1,'WACT001',1,1,1,3,1,0,'2023-06-22 19:58:35',NULL);
DROP TABLE IF EXISTS `workexperiencecompanies`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `workexperiencecompanies` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `cod` varchar(50) NOT NULL DEFAULT '',
  `company` int(10) unsigned NOT NULL DEFAULT 0,
  `experience` int(10) unsigned NOT NULL DEFAULT 0,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_WORKEXPCOMPANY_COMPANY_ACTIVITIES` (`company`),
  KEY `fk_WORKEXPCOMPANY_EXPERIENCE_WORKEXPERIENCES` (`experience`),
  CONSTRAINT `fk_WORKEXPCOMPANY_COMPANY_ACTIVITIES` FOREIGN KEY (`company`) REFERENCES `activities` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_WORKEXPCOMPANY_EXPERIENCE_WORKEXPERIENCES` FOREIGN KEY (`experience`) REFERENCES `workexperiences` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
INSERT INTO `workexperiencecompanies` VALUES (1,'CV_GIUSEPPE_SASSONE_INF_EXP1_COMPANY1',4,1,'2023-06-22 19:58:50',NULL),(2,'CV_GIUSEPPE_SASSONE_INF_EXP1_COMPANY2',6,1,'2023-06-22 19:58:50',NULL);
DROP TABLE IF EXISTS `workexperienceroles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `workexperienceroles` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `cod` varchar(50) NOT NULL DEFAULT '',
  `gg` int(10) unsigned NOT NULL DEFAULT 0,
  `months` double(11,2) NOT NULL DEFAULT 0.00,
  `role` int(10) unsigned NOT NULL DEFAULT 0,
  `experience` int(10) unsigned NOT NULL DEFAULT 0,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_WORKEXPROLE_ROLE_WORKROLES` (`role`),
  KEY `fk_WORKEXROLE_EXPERIENCE_WORKEXPERIENCES` (`experience`),
  CONSTRAINT `fk_WORKEXPROLE_ROLE_WORKROLES` FOREIGN KEY (`role`) REFERENCES `workroles` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_WORKEXROLE_EXPERIENCE_WORKEXPERIENCES` FOREIGN KEY (`experience`) REFERENCES `workexperiences` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `workexperiences`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `workexperiences` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `cod` varchar(50) NOT NULL DEFAULT '',
  `title` varchar(250) NOT NULL DEFAULT '',
  `description` text DEFAULT '',
  `company` int(10) unsigned NOT NULL DEFAULT 0,
  `role` int(10) unsigned NOT NULL DEFAULT 0,
  `place` varchar(250) NOT NULL DEFAULT '',
  `city` int(10) unsigned NOT NULL DEFAULT 0,
  `nation` int(10) unsigned NOT NULL DEFAULT 0,
  `dtainit` datetime DEFAULT NULL,
  `dtaend` datetime DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_WORKEXPERIENCES_COMPANY_ACTIVITIES` (`company`),
  KEY `fk_WORKEXPERIENCES_CITY_CITIES` (`city`),
  KEY `fk_WORKEXPERIENCES_ROLE_WORKROLES` (`role`),
  KEY `fk_WORKEXPERIENCES_NATION_NATIONS` (`nation`),
  CONSTRAINT `fk_WORKEXPERIENCES_CITY_CITIES` FOREIGN KEY (`city`) REFERENCES `cities` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_WORKEXPERIENCES_COMPANY_ACTIVITIES` FOREIGN KEY (`company`) REFERENCES `activities` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_WORKEXPERIENCES_NATION_NATIONS` FOREIGN KEY (`nation`) REFERENCES `nations` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_WORKEXPERIENCES_ROLE_WORKROLES` FOREIGN KEY (`role`) REFERENCES `workroles` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
INSERT INTO `workexperiences` VALUES (1,'CV_GIUSEPPE_SASSONE_INF_EXP1','Programmatore ANGULAR sul progetto SGP per conto di INAIL','Sviluppo software per la gestione di domande di infortunio',5,2,'Roma',0,1,'2019-07-01 00:00:00',NULL,'2023-06-22 19:58:50',NULL);
DROP TABLE IF EXISTS `workexperienceskills`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `workexperienceskills` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `cod` varchar(50) NOT NULL DEFAULT '',
  `gg` int(10) unsigned NOT NULL DEFAULT 0,
  `months` double(11,2) NOT NULL DEFAULT 0.00,
  `skill` int(10) unsigned NOT NULL DEFAULT 0,
  `levelval` int(10) unsigned NOT NULL DEFAULT 0,
  `experience` int(10) unsigned NOT NULL DEFAULT 0,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_WORKEXPSKILL_SKILL_WORKSKILLS` (`skill`),
  KEY `fk_WORKEXPSKILL_EXPERIENCE_WORKEXPERIENCES` (`experience`),
  CONSTRAINT `fk_WORKEXPSKILL_EXPERIENCE_WORKEXPERIENCES` FOREIGN KEY (`experience`) REFERENCES `workexperiences` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_WORKEXPSKILL_SKILL_WORKSKILLS` FOREIGN KEY (`skill`) REFERENCES `workskills` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
INSERT INTO `workexperienceskills` VALUES (1,'CV_GIUSEPPE_SASSONE_INF_EXP1_SKILL1',720,0.00,1,9,1,'2023-06-22 19:58:50',NULL),(2,'CV_GIUSEPPE_SASSONE_INF_EXP1_SKILL2',720,0.00,2,8,1,'2023-06-22 19:58:50',NULL),(3,'CV_GIUSEPPE_SASSONE_INF_EXP1_SKILL3',720,0.00,3,7,1,'2023-06-22 19:58:50',NULL),(4,'CV_GIUSEPPE_SASSONE_INF_EXP1_SKILL4',720,0.00,4,7,1,'2023-06-22 19:58:50',NULL),(5,'CV_GIUSEPPE_SASSONE_INF_EXP1_SKILL5',720,0.00,5,8,1,'2023-06-22 19:58:50',NULL);
DROP TABLE IF EXISTS `workroles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `workroles` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `cod` varchar(50) NOT NULL DEFAULT '',
  `name` varchar(250) NOT NULL DEFAULT '',
  `description` text DEFAULT '',
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
INSERT INTO `workroles` VALUES (1,'GIUSASSO00_CEO_DANDYCORP','CEO','Responsabile settore informatico','2023-06-22 19:58:43',NULL),(2,'CV_GIUSEPPE_SASSONE_INF_EXP1_ROLE1','Programmatore Frond End ANGULAR','Sviluppo di applicazione front-end in ANGULAR 2','2023-06-22 19:58:50',NULL);
DROP TABLE IF EXISTS `workskills`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `workskills` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `cod` varchar(50) NOT NULL DEFAULT '',
  `name` varchar(250) NOT NULL DEFAULT '',
  `description` text DEFAULT '',
  `tpskill` int(10) unsigned NOT NULL DEFAULT 0,
  `levelmax` int(10) unsigned NOT NULL DEFAULT 0,
  `leveldesc` varchar(250) NOT NULL DEFAULT '',
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_WORKSKILLS_TPSKILL_TPSKILLS` (`tpskill`),
  CONSTRAINT `fk_WORKSKILLS_TPSKILL_TPSKILLS` FOREIGN KEY (`tpskill`) REFERENCES `tpskills` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
INSERT INTO `workskills` VALUES (1,'SKILL_ANGULAR6','Angular6','',1,10,'','2023-06-22 19:58:50',NULL),(2,'SKILL_NODEJS','NodeJS','',1,10,'','2023-06-22 19:58:50',NULL),(3,'SKILL_RXJS','Rxjs','',1,10,'','2023-06-22 19:58:50',NULL),(4,'SKILL_SASS','Sass','',1,10,'','2023-06-22 19:58:50',NULL),(5,'SKILL_VSCODE','VSCode','',3,10,'','2023-06-22 19:58:50',NULL);
DROP TABLE IF EXISTS `workusers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `workusers` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `cod` varchar(50) NOT NULL DEFAULT '',
  `user` int(10) unsigned NOT NULL DEFAULT 0,
  `image` int(10) unsigned NOT NULL DEFAULT 0,
  `home` int(10) unsigned NOT NULL DEFAULT 0,
  `phone` int(10) unsigned NOT NULL DEFAULT 0,
  `email` int(10) unsigned NOT NULL DEFAULT 0,
  `website` int(10) unsigned NOT NULL DEFAULT 0,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_WORKUSER_USER_USERS` (`user`),
  KEY `fk_WORKUSER_IMAGE_ATTACHMENTS` (`image`),
  KEY `fk_WORKUSER_HOME_ADDRESSES` (`home`),
  KEY `fk_WORKUSER_PHONE_CONTACTREFERENCES` (`phone`),
  KEY `fk_WORKUSER_EMAIL_CONTACTREFERENCES` (`email`),
  KEY `fk_WORKUSER_WEBSITE_CONTACTREFERENCES` (`website`),
  CONSTRAINT `fk_WORKUSER_EMAIL_CONTACTREFERENCES` FOREIGN KEY (`email`) REFERENCES `contactreferences` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_WORKUSER_HOME_ADDRESSES` FOREIGN KEY (`home`) REFERENCES `addresses` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_WORKUSER_IMAGE_ATTACHMENTS` FOREIGN KEY (`image`) REFERENCES `attachments` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_WORKUSER_PHONE_CONTACTREFERENCES` FOREIGN KEY (`phone`) REFERENCES `contactreferences` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_WORKUSER_USER_USERS` FOREIGN KEY (`user`) REFERENCES `users` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_WORKUSER_WEBSITE_CONTACTREFERENCES` FOREIGN KEY (`website`) REFERENCES `contactreferences` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
INSERT INTO `workusers` VALUES (1,'WUSR001',1,1,1,4,2,0,'2023-06-22 19:58:35',NULL);
