/*
Navicat MySQL Data Transfer

Source Server         : Local
Source Server Version : 50505
Source Host           : 127.0.0.1:3306
Source Database       : tdscswd

Target Server Type    : MYSQL
Target Server Version : 50505
File Encoding         : 65001

Date: 2025-01-08 12:11:34
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for aics_compositions
-- ----------------------------
DROP TABLE IF EXISTS `aics_compositions`;
CREATE TABLE `aics_compositions` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `aics_gis_id` bigint(20) unsigned NOT NULL,
  `comp_name` varchar(255) DEFAULT '',
  `comp_age` varchar(255) DEFAULT '',
  `comp_relation_to_client` varchar(255) DEFAULT '',
  `comp_education` varchar(255) DEFAULT '',
  `comp_occupation` varchar(255) DEFAULT '',
  `comp_monthly_income` varchar(255) DEFAULT '',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of aics_compositions
-- ----------------------------
INSERT INTO `aics_compositions` VALUES ('1', '3', 'John Shander Casipong', '18', 'Sibling', 'College Level', 'n/a', 'n/a', '2024-12-01 13:05:55', '2024-12-03 02:28:51');
INSERT INTO `aics_compositions` VALUES ('14', '4', 'Sundae Sambulan', '6', 'Sibling', 'Elementary Level', null, null, '2024-12-10 11:39:23', '2024-12-10 11:39:23');

-- ----------------------------
-- Table structure for aics_gis
-- ----------------------------
DROP TABLE IF EXISTS `aics_gis`;
CREATE TABLE `aics_gis` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `birthdate` date DEFAULT NULL,
  `age` varchar(255) DEFAULT NULL,
  `sex` varchar(255) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `civil_status` varchar(255) DEFAULT NULL,
  `occupation` varchar(255) DEFAULT NULL,
  `education` varchar(255) DEFAULT NULL,
  `monthly_income` varchar(255) DEFAULT NULL,
  `mobile_number` varchar(255) DEFAULT NULL,
  `relation_to_beneficiary` varchar(255) DEFAULT NULL,
  `type_of_assistance` varchar(255) DEFAULT NULL,
  `amount_provided` varchar(255) DEFAULT NULL,
  `ik_name` varchar(255) DEFAULT NULL,
  `ik_birthdate` date DEFAULT NULL,
  `ik_age` varchar(255) DEFAULT NULL,
  `ik_sex` varchar(255) DEFAULT NULL,
  `ik_address` varchar(255) DEFAULT NULL,
  `ik_civil_status` varchar(255) DEFAULT NULL,
  `ik_occupation` varchar(255) DEFAULT NULL,
  `ik_education` varchar(255) DEFAULT NULL,
  `ik_monthly_income` varchar(255) DEFAULT NULL,
  `ik_mobile_number` varchar(255) DEFAULT NULL,
  `social_worker_assessment` varchar(255) DEFAULT NULL,
  `interviewee` varchar(255) DEFAULT NULL,
  `reviewee` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `status` tinyint(4) DEFAULT 0,
  `date` date DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of aics_gis
-- ----------------------------
INSERT INTO `aics_gis` VALUES ('3', 'Christopher Casipong', '2001-04-12', '23', 'Male', 'P-Madanihon, Tinago, Ozamiz City', 'Single', 'College Teacher', 'College Level', '14000', '9683022435', 'Son', '1', '10,000', 'Mary Joy M. Casipong', '1985-06-02', '39', 'Female', 'P-Madanihon, Tinago, Ozamiz City', 'Single', 'Clinic Secretary', 'High School Level', '4000-5000 /m', '09465894121', 'This is a remark', '2', '3', '2024-12-01 13:05:55', '2024-12-03 10:29:16', '1', '2024-12-01');
INSERT INTO `aics_gis` VALUES ('4', 'Angelie Sambulan', '2001-04-11', '24', 'Female', 'P-Madanihon, Tinago, Ozamiz City', 'Single', 'None', 'College Level', 'None', '09683022438', 'Daughter', '1', '5000', 'Maggie Sambulan', '1989-12-01', '35', 'Female', 'P-Madanihon, Tinago, Ozamiz City', 'Single', 'None', 'High School Level', '5000', '09465894121', 'Needs Assitance', '8', '2', '2024-12-10 11:39:23', '2024-12-10 11:40:56', '1', '2024-12-10');
INSERT INTO `aics_gis` VALUES ('5', 'Harvey Gwapa', '2002-12-23', '23', 'Male', 'P-Madanihon, Tinago, Ozamiz City', 'Single', 'None', 'College Level', 'None', '09683022435', 'Son', '1', '10,000', 'Haruko Dapal', '1985-12-02', '39', 'Male', 'P-Madanihon, Tinago, Ozamiz City', 'Single', 'Clinic Secretary', 'High School Level', '5000', '09465894121', 'Financial Needs', '2', '3', '2024-12-11 09:49:23', '2024-12-14 14:01:10', '1', '2024-12-11');
INSERT INTO `aics_gis` VALUES ('6', 'Mary', '2002-04-02', '22', 'Female', 'P-Madanihon', 'Single', 'None', 'None', 'None', '09841231232', 'Sibling', '1', '10000', 'Maria', '2001-04-12', '23', 'Female', 'P-Madanihon', 'Single', 'None', 'High School', '2000', '09412412543', 'Needs assistance', '8', '10', '2025-01-06 03:14:15', '2025-01-06 03:14:15', '1', '2025-01-06');

-- ----------------------------
-- Table structure for aics_staff
-- ----------------------------
DROP TABLE IF EXISTS `aics_staff`;
CREATE TABLE `aics_staff` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `position` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `role` tinyint(4) DEFAULT NULL,
  `status` tinyint(4) DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of aics_staff
-- ----------------------------
INSERT INTO `aics_staff` VALUES ('2', 'Maria Christine', 'Interviewee', '2024-12-03 02:01:34', '2024-12-14 13:31:37', '1', '0');
INSERT INTO `aics_staff` VALUES ('3', 'John Shander casipong', 'Reviewee', null, '2024-12-14 13:31:37', '1', '0');
INSERT INTO `aics_staff` VALUES ('5', 'Sabiniano S. Canama', 'City Mayor', '2024-12-03 01:15:13', '2024-12-14 13:31:37', '0', '0');
INSERT INTO `aics_staff` VALUES ('8', 'Clifford Dapal', 'Interviewee', '2024-12-03 02:18:11', '2024-12-14 13:31:37', '1', '0');
INSERT INTO `aics_staff` VALUES ('10', 'Kate Fernandez', 'Reviewee', '2024-12-14 13:38:57', '2024-12-14 13:39:20', '1', '0');

-- ----------------------------
-- Table structure for assistance_types
-- ----------------------------
DROP TABLE IF EXISTS `assistance_types`;
CREATE TABLE `assistance_types` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `assistance_type` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of assistance_types
-- ----------------------------
INSERT INTO `assistance_types` VALUES ('1', 'Financial assistance', null, null);
INSERT INTO `assistance_types` VALUES ('2', 'Material  assistance', null, null);
INSERT INTO `assistance_types` VALUES ('3', 'Medical  assistance', null, null);
INSERT INTO `assistance_types` VALUES ('4', 'Educational  assistance', null, null);
INSERT INTO `assistance_types` VALUES ('5', 'Psychosocial   assistance', null, null);

-- ----------------------------
-- Table structure for barangays
-- ----------------------------
DROP TABLE IF EXISTS `barangays`;
CREATE TABLE `barangays` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `barangay_name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `lat` varchar(255) DEFAULT NULL,
  `long` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=56 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of barangays
-- ----------------------------
INSERT INTO `barangays` VALUES ('1', 'Aquino', null, null, '8.0600404', '123.7116910');
INSERT INTO `barangays` VALUES ('2', 'Balatacan', null, null, '8.0414597', '123.6697954');
INSERT INTO `barangays` VALUES ('3', 'Baluc', null, null, '8.1154691', '123.6899954');
INSERT INTO `barangays` VALUES ('4', 'Banglay', null, null, '8.1450560', '123.7126663');
INSERT INTO `barangays` VALUES ('5', 'Bintana', null, null, '8.0782753', '123.6846069');
INSERT INTO `barangays` VALUES ('6', 'Bocator', null, null, '8.0426933', '123.6826356');
INSERT INTO `barangays` VALUES ('7', 'Bongabong', null, null, '8.1086838', '123.7213645');
INSERT INTO `barangays` VALUES ('8', 'Caniangan', null, null, '8.0896844', '123.6994432');
INSERT INTO `barangays` VALUES ('9', 'Capalaran', null, null, '8.1150931', '123.7433702');
INSERT INTO `barangays` VALUES ('10', 'Catagan', null, null, '8.1006945', '123.7017776');
INSERT INTO `barangays` VALUES ('11', 'Barangay I - City Hall (Poblacion)', null, null, '8.0609113', '123.7503223');
INSERT INTO `barangays` VALUES ('12', 'Barangay II - Marilou Annex (Poblacion)', null, null, '8.0642214', '123.7513630');
INSERT INTO `barangays` VALUES ('13', 'Barangay III- Market Kalubian (Poblacion)', null, null, '8.0658350', '123.7478716');
INSERT INTO `barangays` VALUES ('14', 'Barangay IV - St. Michael (Poblacion)', null, null, '8.0640522', '123.7454058');
INSERT INTO `barangays` VALUES ('15', 'Barangay V - Malubog (Poblacion)', null, null, '8.0598302', '123.7455286');
INSERT INTO `barangays` VALUES ('16', 'Barangay VI - Lower Polao (Poblacion)', null, null, '8.0710428', '123.7428862');
INSERT INTO `barangays` VALUES ('17', 'Barangay VII - Upper Polao (Poblacion)', null, null, '8.0765073', '123.7383922');
INSERT INTO `barangays` VALUES ('18', 'Hoyohoy', null, null, '8.1560508', '123.7008727');
INSERT INTO `barangays` VALUES ('19', 'Isidro D. Tan (Dimalooc)', null, null, '8.0736701', '123.7530862');
INSERT INTO `barangays` VALUES ('20', 'Garang', null, null, '8.0517278', '123.7291820');
INSERT INTO `barangays` VALUES ('21', 'Guinabot', null, null, '8.0750720', '123.6522257');
INSERT INTO `barangays` VALUES ('22', 'Guinalaban', null, null, '8.0755308', '123.6775138');
INSERT INTO `barangays` VALUES ('23', 'Kausawagan', null, null, '8.0854035', '123.7245681');
INSERT INTO `barangays` VALUES ('24', 'Kimat', null, null, '8.1289881', '123.6919059');
INSERT INTO `barangays` VALUES ('25', 'Labuyo', null, null, '8.0639280', '123.7194267');
INSERT INTO `barangays` VALUES ('26', 'Lorenzo Tan', null, null, '8.0562119', '123.7025178');
INSERT INTO `barangays` VALUES ('27', 'Lumban', null, null, '8.0934213', '123.7161282');
INSERT INTO `barangays` VALUES ('28', 'Maloro', null, null, '8.0619524', '123.7546175');
INSERT INTO `barangays` VALUES ('29', 'Manga', null, null, '8.0898954', '123.7375451');
INSERT INTO `barangays` VALUES ('30', 'Mantic', null, null, '8.0579917', '123.7492108');
INSERT INTO `barangays` VALUES ('31', 'Maquilao', null, null, '8.0480298', '123.7225024');
INSERT INTO `barangays` VALUES ('32', 'Matugnao', null, null, '8.1115115', '123.6366660');
INSERT INTO `barangays` VALUES ('33', 'Mingcanaway', null, null, '8.0578055', '123.7525227');
INSERT INTO `barangays` VALUES ('34', 'Minsubong', null, null, '8.0860103', '123.7623849');
INSERT INTO `barangays` VALUES ('35', 'Owayan', null, null, '8.1128938', '123.6478868');
INSERT INTO `barangays` VALUES ('36', 'Paiton', null, null, '8.0971879', '123.6642528');
INSERT INTO `barangays` VALUES ('37', 'Panalsalan', null, null, '8.0527577', '123.6849856');
INSERT INTO `barangays` VALUES ('38', 'Pangabuan', null, null, '8.0538818', '123.6929478');
INSERT INTO `barangays` VALUES ('39', 'Prenza', null, null, '8.0780606', '123.7126968');
INSERT INTO `barangays` VALUES ('40', 'Salimpuno', null, null, '8.1356387', '123.7030948');
INSERT INTO `barangays` VALUES ('41', 'San Antonio', null, null, '8.1113945', '123.6762827');
INSERT INTO `barangays` VALUES ('42', 'San Apolinario', null, null, '8.0557433', '123.7397606');
INSERT INTO `barangays` VALUES ('43', 'San Vicente', null, null, '8.0570004', '123.6613949');
INSERT INTO `barangays` VALUES ('44', 'Santa Cruz', null, null, '8.0678056', '123.7363905');
INSERT INTO `barangays` VALUES ('45', 'Santa Maria (Baga)', null, null, '8.0967861', '123.7548723');
INSERT INTO `barangays` VALUES ('46', 'Santo Niño', null, null, '8.1124807', '123.7044766');
INSERT INTO `barangays` VALUES ('47', 'Sicot', null, null, '8.1157197', '123.6614473');
INSERT INTO `barangays` VALUES ('48', 'Silanga', null, null, '8.0668249', '123.7657821');
INSERT INTO `barangays` VALUES ('49', 'Silangit', null, null, '8.0759017', '123.6618756');
INSERT INTO `barangays` VALUES ('50', 'Simasay', null, null, '8.0905017', '123.6417488');
INSERT INTO `barangays` VALUES ('51', 'Sumirap', null, null, '8.0376221', '123.6565002');
INSERT INTO `barangays` VALUES ('52', 'Taguite', null, null, '8.1027050', '123.6818747');
INSERT INTO `barangays` VALUES ('53', 'Tituron', null, null, '8.1149955', '123.7222835');
INSERT INTO `barangays` VALUES ('54', 'Tugas', null, null, '8.0578912', '123.6472960');
INSERT INTO `barangays` VALUES ('55', 'Villaba', null, null, '8.1300144', '123.7327714');

-- ----------------------------
-- Table structure for benefeciaries
-- ----------------------------
DROP TABLE IF EXISTS `benefeciaries`;
CREATE TABLE `benefeciaries` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `barangay_id` bigint(20) unsigned NOT NULL,
  `first_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `middle_name` varchar(255) DEFAULT NULL,
  `extension_name` varchar(255) DEFAULT NULL,
  `date_of_birth` date NOT NULL,
  `age` varchar(255) NOT NULL,
  `civil_status` varchar(255) NOT NULL,
  `sex` varchar(255) NOT NULL,
  `educational_attainment` varchar(255) DEFAULT NULL,
  `occupation` varchar(255) DEFAULT NULL,
  `street` varchar(255) DEFAULT NULL,
  `city` varchar(255) DEFAULT NULL,
  `province` varchar(255) DEFAULT NULL,
  `region` varchar(255) DEFAULT NULL,
  `contact_number` varchar(255) DEFAULT NULL,
  `pantawid_benefeciary` tinyint(4) DEFAULT NULL,
  `indigenous_person` tinyint(4) DEFAULT NULL,
  `lgbtq` tinyint(4) DEFAULT NULL,
  `remarks` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `status` tinyint(4) NOT NULL DEFAULT 1,
  `image` varchar(255) DEFAULT '',
  `religion` varchar(255) DEFAULT NULL,
  `work_status` varchar(255) DEFAULT NULL,
  `monthly_income_id` bigint(20) NOT NULL,
  `benefeciary_type` tinyint(4) DEFAULT NULL,
  `approved_status` tinyint(4) DEFAULT 0,
  `reason` text DEFAULT NULL,
  `user_id` bigint(20) DEFAULT NULL,
  `date_rejected` datetime DEFAULT NULL,
  `date_approved` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of benefeciaries
-- ----------------------------
INSERT INTO `benefeciaries` VALUES ('12', '3', 'Maria Christine', 'Caalim', null, null, '1961-01-26', '63', 'Married', 'Female', 'Doctorate', null, 'Triunfo', 'Tangub City', 'Misamis Occidental', '10', '09683021683', '0', '0', '0', 'Remarks', '2024-09-26 14:00:48', '2024-12-14 14:35:17', '1', '1728801332.jpg', 'Roman Catholic', null, '2', '1', '0', null, null, null, null);
INSERT INTO `benefeciaries` VALUES ('17', '3', 'Manta', 'Ray', null, null, '1976-11-09', '48', 'Single', 'Female', 'College Level', 'None', 'Purok 2', 'Tangub City', 'Misamis Occidental', '10', '9823141255', '0', '0', '0', null, '2024-11-18 05:21:17', '2025-01-08 02:43:29', '1', '1734493372.jpg', 'Roman Catholic', null, '1', '3', '1', null, null, null, '2025-01-08 00:00:00');
INSERT INTO `benefeciaries` VALUES ('18', '3', 'Guada', 'Lansa', null, null, '1964-12-01', '60', 'Single', 'Female', 'College Level', 'None', 'Purok IV', 'Tangub City', 'Misamis Occidental', '10', '09512623163', '0', '0', '0', 'Gwapo', '2024-11-18 05:47:08', '2024-12-10 11:08:10', '1', '', 'Roman Catholic', null, '1', '1', '1', null, null, null, null);
INSERT INTO `benefeciaries` VALUES ('20', '18', 'Jia', 'Binaoro', null, null, '1964-11-01', '60', 'Single', 'Female', 'College Level', 'None', 'P-Single', 'Tangub City', 'Misamis Occidental', '10', '09562235446', '0', '0', '0', null, '2024-11-27 09:22:48', '2024-12-04 14:21:08', '1', '', 'Roman Catholic', null, '1', '1', '1', null, null, null, null);
INSERT INTO `benefeciaries` VALUES ('21', '3', 'Clifford', 'Dapal', null, null, '1964-12-06', '60', 'Single', 'Male', 'College Level', 'None', 'P-Gwapo', 'Tangub City', 'Misamis Occidental', '10', '09412355678', '0', '0', '0', 'Gwapo', '2024-12-06 07:18:47', '2024-12-06 07:18:47', '1', '1733469527-9Iby0.jpg', 'Roman Catholic', null, '1', '1', '0', null, null, null, null);
INSERT INTO `benefeciaries` VALUES ('22', '6', 'Jessel', 'Zapanta', null, null, '1964-12-06', '60', 'Married', 'Male', 'College Level', 'None', 'P-Gwapo', 'Tangub City', 'Misamis Occidental', '10', '09345672122', '0', '0', '0', 'Gwapo', '2024-12-06 07:20:26', '2024-12-06 07:39:48', '1', '1733469626-LJ4b8.jpg', 'Catholic', null, '1', '1', '1', null, null, null, null);
INSERT INTO `benefeciaries` VALUES ('23', '6', 'Ryan', 'Rems', null, null, '1964-11-29', '60', 'Married', 'Male', 'College Level', 'None', 'P-Gwapo', 'Tangub City', 'Misamis Occidental', '10', '09412355671', '0', '0', '0', 'Gwapo', '2024-12-06 07:31:31', '2024-12-06 07:31:31', '1', '1733470291-eqOUS.jpg', 'Roman Catholic', null, '1', '1', '0', null, null, null, null);
INSERT INTO `benefeciaries` VALUES ('24', '3', 'Mary Rose', 'Embuscado', null, null, '1999-01-08', '25', 'Single', 'Female', 'College Level', 'None', 'P-Madanihon', 'Tangub City', 'Misamis Occidental', '10', '09512364899', '0', '0', '0', 'This is a remark', '2024-12-08 08:45:40', '2025-01-08 02:43:31', '1', '1734493332.jpg', 'Roman Catholic', null, '1', '3', '1', null, null, null, '2025-01-08 00:00:00');
INSERT INTO `benefeciaries` VALUES ('26', '3', 'Choy', 'Yan', null, null, '2001-01-01', '24', 'Single', 'Female', 'None', 'None', 'P-Gwapo', 'Tangub City', 'Misamis Occidental', '10', '9823141255', '0', '0', '0', null, '2025-01-05 11:29:41', '2025-01-08 04:09:19', '1', '1736076580-ntMO7.jpg', 'Roman Catholic', null, '1', '3', '1', null, '31', null, '2025-01-08 00:00:00');

-- ----------------------------
-- Table structure for birthday_data
-- ----------------------------
DROP TABLE IF EXISTS `birthday_data`;
CREATE TABLE `birthday_data` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `benefeciary_id` bigint(20) unsigned NOT NULL,
  `month` varchar(255) DEFAULT NULL,
  `year` varchar(255) DEFAULT NULL,
  `status` tinyint(4) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `barangay_id` bigint(20) NOT NULL,
  `date_approved` date DEFAULT NULL,
  `user_id` bigint(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of birthday_data
-- ----------------------------
INSERT INTO `birthday_data` VALUES ('19', '18', '12', '2024', '1', '2024-12-10 07:46:13', '2024-12-10 08:03:55', '3', '2024-12-10', '22');

-- ----------------------------
-- Table structure for cache
-- ----------------------------
DROP TABLE IF EXISTS `cache`;
CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of cache
-- ----------------------------

-- ----------------------------
-- Table structure for cache_locks
-- ----------------------------
DROP TABLE IF EXISTS `cache_locks`;
CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of cache_locks
-- ----------------------------

-- ----------------------------
-- Table structure for failed_jobs
-- ----------------------------
DROP TABLE IF EXISTS `failed_jobs`;
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

-- ----------------------------
-- Records of failed_jobs
-- ----------------------------

-- ----------------------------
-- Table structure for jobs
-- ----------------------------
DROP TABLE IF EXISTS `jobs`;
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
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of jobs
-- ----------------------------
INSERT INTO `jobs` VALUES ('1', 'default', '{\"uuid\":\"2ce4a10d-e5fa-4822-b208-887f170467d5\",\"displayName\":\"Illuminate\\\\Notifications\\\\Events\\\\BroadcastNotificationCreated\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Broadcasting\\\\BroadcastEvent\",\"command\":\"O:38:\\\"Illuminate\\\\Broadcasting\\\\BroadcastEvent\\\":14:{s:5:\\\"event\\\";O:60:\\\"Illuminate\\\\Notifications\\\\Events\\\\BroadcastNotificationCreated\\\":3:{s:10:\\\"notifiable\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:15:\\\"App\\\\Models\\\\User\\\";s:2:\\\"id\\\";i:23;s:9:\\\"relations\\\";a:0:{}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:12:\\\"notification\\\";O:38:\\\"App\\\\Notifications\\\\ActivityNotification\\\":1:{s:2:\\\"id\\\";s:36:\\\"efe3ab61-8b10-4079-8a19-50aba4c290b3\\\";}s:4:\\\"data\\\";a:5:{s:4:\\\"from\\\";s:11:\\\"Focal Women\\\";s:5:\\\"image\\\";N;s:7:\\\"message\\\";s:33:\\\"Created a new beneficiary request\\\";s:3:\\\"url\\\";s:51:\\\"http:\\/\\/localhost:8000\\/barangay\\/requests\\/beneficiary\\\";s:10:\\\"created_at\\\";O:25:\\\"Illuminate\\\\Support\\\\Carbon\\\":3:{s:4:\\\"date\\\";s:26:\\\"2024-12-08 08:45:59.243012\\\";s:13:\\\"timezone_type\\\";i:3;s:8:\\\"timezone\\\";s:3:\\\"UTC\\\";}}}s:5:\\\"tries\\\";N;s:7:\\\"timeout\\\";N;s:7:\\\"backoff\\\";N;s:13:\\\"maxExceptions\\\";N;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;}\"}}', '0', null, '1733647559', '1733647559');
INSERT INTO `jobs` VALUES ('2', 'default', '{\"uuid\":\"3485aaef-2b84-44e4-98ec-922afa13c9da\",\"displayName\":\"Illuminate\\\\Notifications\\\\Events\\\\BroadcastNotificationCreated\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Broadcasting\\\\BroadcastEvent\",\"command\":\"O:38:\\\"Illuminate\\\\Broadcasting\\\\BroadcastEvent\\\":14:{s:5:\\\"event\\\";O:60:\\\"Illuminate\\\\Notifications\\\\Events\\\\BroadcastNotificationCreated\\\":3:{s:10:\\\"notifiable\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:15:\\\"App\\\\Models\\\\User\\\";s:2:\\\"id\\\";i:23;s:9:\\\"relations\\\";a:0:{}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:12:\\\"notification\\\";O:38:\\\"App\\\\Notifications\\\\ActivityNotification\\\":1:{s:2:\\\"id\\\";s:36:\\\"84152c74-fa79-46c6-ad9d-187f8aa314e2\\\";}s:4:\\\"data\\\";a:6:{s:4:\\\"type\\\";i:0;s:4:\\\"from\\\";s:11:\\\"Focal Women\\\";s:5:\\\"image\\\";N;s:7:\\\"message\\\";s:35:\\\"Submitted a new beneficiary request\\\";s:3:\\\"url\\\";s:51:\\\"http:\\/\\/localhost:8000\\/barangay\\/requests\\/beneficiary\\\";s:10:\\\"created_at\\\";O:25:\\\"Illuminate\\\\Support\\\\Carbon\\\":3:{s:4:\\\"date\\\";s:26:\\\"2024-12-09 01:32:32.625184\\\";s:13:\\\"timezone_type\\\";i:3;s:8:\\\"timezone\\\";s:3:\\\"UTC\\\";}}}s:5:\\\"tries\\\";N;s:7:\\\"timeout\\\";N;s:7:\\\"backoff\\\";N;s:13:\\\"maxExceptions\\\";N;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;}\"}}', '0', null, '1733707953', '1733707953');
INSERT INTO `jobs` VALUES ('3', 'default', '{\"uuid\":\"13564b80-07a0-403b-a069-d5111837397a\",\"displayName\":\"Illuminate\\\\Notifications\\\\Events\\\\BroadcastNotificationCreated\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Broadcasting\\\\BroadcastEvent\",\"command\":\"O:38:\\\"Illuminate\\\\Broadcasting\\\\BroadcastEvent\\\":14:{s:5:\\\"event\\\";O:60:\\\"Illuminate\\\\Notifications\\\\Events\\\\BroadcastNotificationCreated\\\":3:{s:10:\\\"notifiable\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:15:\\\"App\\\\Models\\\\User\\\";s:2:\\\"id\\\";i:14;s:9:\\\"relations\\\";a:0:{}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:12:\\\"notification\\\";O:38:\\\"App\\\\Notifications\\\\ActivityNotification\\\":1:{s:2:\\\"id\\\";s:36:\\\"d7646a4a-503d-4e22-819a-8f9e0df328ba\\\";}s:4:\\\"data\\\";a:6:{s:4:\\\"type\\\";i:0;s:4:\\\"from\\\";s:14:\\\"Barangay Baluc\\\";s:5:\\\"image\\\";s:20:\\\"1731767807-Dw73j.jpg\\\";s:7:\\\"message\\\";s:49:\\\"Successfully checked your new beneficiary request\\\";s:3:\\\"url\\\";s:54:\\\"http:\\/\\/localhost:8000\\/staff\\/requests\\/new-beneficiaries\\\";s:10:\\\"created_at\\\";O:25:\\\"Illuminate\\\\Support\\\\Carbon\\\":3:{s:4:\\\"date\\\";s:26:\\\"2024-12-09 01:33:38.716395\\\";s:13:\\\"timezone_type\\\";i:3;s:8:\\\"timezone\\\";s:3:\\\"UTC\\\";}}}s:5:\\\"tries\\\";N;s:7:\\\"timeout\\\";N;s:7:\\\"backoff\\\";N;s:13:\\\"maxExceptions\\\";N;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;}\"}}', '0', null, '1733708018', '1733708018');
INSERT INTO `jobs` VALUES ('4', 'default', '{\"uuid\":\"83c47b5f-ab6f-4a98-b43b-edf30824bab5\",\"displayName\":\"Illuminate\\\\Notifications\\\\Events\\\\BroadcastNotificationCreated\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Broadcasting\\\\BroadcastEvent\",\"command\":\"O:38:\\\"Illuminate\\\\Broadcasting\\\\BroadcastEvent\\\":14:{s:5:\\\"event\\\";O:60:\\\"Illuminate\\\\Notifications\\\\Events\\\\BroadcastNotificationCreated\\\":3:{s:10:\\\"notifiable\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:15:\\\"App\\\\Models\\\\User\\\";s:2:\\\"id\\\";i:14;s:9:\\\"relations\\\";a:0:{}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:12:\\\"notification\\\";O:38:\\\"App\\\\Notifications\\\\ActivityNotification\\\":1:{s:2:\\\"id\\\";s:36:\\\"3b6bf208-9828-4061-9f27-3ce21adf6eea\\\";}s:4:\\\"data\\\";a:6:{s:4:\\\"type\\\";i:0;s:4:\\\"from\\\";s:14:\\\"Barangay Baluc\\\";s:5:\\\"image\\\";s:20:\\\"1731767807-Dw73j.jpg\\\";s:7:\\\"message\\\";s:49:\\\"Successfully checked your new beneficiary request\\\";s:3:\\\"url\\\";s:54:\\\"http:\\/\\/localhost:8000\\/staff\\/requests\\/new-beneficiaries\\\";s:10:\\\"created_at\\\";O:25:\\\"Illuminate\\\\Support\\\\Carbon\\\":3:{s:4:\\\"date\\\";s:26:\\\"2024-12-09 01:37:32.744552\\\";s:13:\\\"timezone_type\\\";i:3;s:8:\\\"timezone\\\";s:3:\\\"UTC\\\";}}}s:5:\\\"tries\\\";N;s:7:\\\"timeout\\\";N;s:7:\\\"backoff\\\";N;s:13:\\\"maxExceptions\\\";N;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;}\"}}', '0', null, '1733708252', '1733708252');

-- ----------------------------
-- Table structure for job_batches
-- ----------------------------
DROP TABLE IF EXISTS `job_batches`;
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

-- ----------------------------
-- Records of job_batches
-- ----------------------------

-- ----------------------------
-- Table structure for migrations
-- ----------------------------
DROP TABLE IF EXISTS `migrations`;
CREATE TABLE `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=30 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of migrations
-- ----------------------------
INSERT INTO `migrations` VALUES ('10', '0001_01_01_000000_create_users_table', '1');
INSERT INTO `migrations` VALUES ('11', '0001_01_01_000001_create_cache_table', '1');
INSERT INTO `migrations` VALUES ('12', '0001_01_01_000002_create_jobs_table', '1');
INSERT INTO `migrations` VALUES ('13', '2024_09_05_145432_create_benefeciaries_table', '1');
INSERT INTO `migrations` VALUES ('14', '2024_09_05_150754_create_seniors_table', '1');
INSERT INTO `migrations` VALUES ('15', '2024_09_05_150806_create_childrens_table', '1');
INSERT INTO `migrations` VALUES ('16', '2024_09_05_150828_create_pwds_table', '1');
INSERT INTO `migrations` VALUES ('17', '2024_09_05_150928_create_womens_table', '1');
INSERT INTO `migrations` VALUES ('18', '2024_09_06_033107_create_barangays_table', '1');
INSERT INTO `migrations` VALUES ('19', '2024_09_16_132321_create_monthly_incomes_table', '2');
INSERT INTO `migrations` VALUES ('20', '2024_11_17_131912_create_requests_table', '3');
INSERT INTO `migrations` VALUES ('21', '2024_11_19_130010_create_transition_data_table', '4');
INSERT INTO `migrations` VALUES ('22', '2024_11_21_032012_create_birthday_data_table', '5');
INSERT INTO `migrations` VALUES ('23', '2024_11_26_133943_create_aics_staff_table', '6');
INSERT INTO `migrations` VALUES ('24', '2024_11_26_134004_create_aics_gis_table', '7');
INSERT INTO `migrations` VALUES ('25', '2024_11_26_134044_create_aics_compositions_table', '8');
INSERT INTO `migrations` VALUES ('26', '2024_11_26_142421_create_programs_table', '9');
INSERT INTO `migrations` VALUES ('27', '2024_11_28_131822_create_program_beneficiaries_table', '10');
INSERT INTO `migrations` VALUES ('28', '2024_11_29_093844_create_assistance_types_table', '11');
INSERT INTO `migrations` VALUES ('29', '2024_12_03_060056_create_notifications_table', '12');

-- ----------------------------
-- Table structure for monthly_incomes
-- ----------------------------
DROP TABLE IF EXISTS `monthly_incomes`;
CREATE TABLE `monthly_incomes` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `income_range` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of monthly_incomes
-- ----------------------------
INSERT INTO `monthly_incomes` VALUES ('1', 'less than 30,000', null, null);
INSERT INTO `monthly_incomes` VALUES ('2', '30,000 - 60,000', null, null);
INSERT INTO `monthly_incomes` VALUES ('3', 'more than 60,000', null, null);

-- ----------------------------
-- Table structure for notifications
-- ----------------------------
DROP TABLE IF EXISTS `notifications`;
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

-- ----------------------------
-- Records of notifications
-- ----------------------------
INSERT INTO `notifications` VALUES ('01848cae-096d-4dc1-8082-7a0b0fe6ab0d', 'App\\Notifications\\ActivityNotification', 'App\\Models\\User', '22', '{\"type\":0,\"from\":\"Barangay Baluc\",\"image\":\"1731767807-Dw73j.jpg\",\"message\":\"Successfully checked your new beneficiary request\",\"url\":\"http:\\/\\/localhost:8000\\/focal\\/requests\\/beneficiary\",\"created_at\":\"2025-01-07T14:11:51.696977Z\"}', null, '2025-01-07 14:11:51', '2025-01-07 14:11:51');
INSERT INTO `notifications` VALUES ('02b61886-8bc7-45ff-9692-2000625405ab', 'App\\Notifications\\ActivityNotification', 'App\\Models\\User', '22', '{\"type\":0,\"from\":\"staff\",\"image\":null,\"message\":\"Rejected new beneficiary request\",\"url\":\"http:\\/\\/localhost:8000\\/focal\\/requests\\/beneficiary\",\"created_at\":\"2025-01-07T14:26:36.684795Z\"}', null, '2025-01-07 14:26:36', '2025-01-07 14:26:36');
INSERT INTO `notifications` VALUES ('044aec47-a25f-4e7f-a753-3b339bf35f4e', 'App\\Notifications\\ActivityNotification', 'App\\Models\\User', '23', '{\"type\":0,\"from\":\"staff\",\"image\":null,\"message\":\"Rejected the new beneficiary request\",\"url\":\"http:\\/\\/localhost:8000\\/barangay\\/requests\\/beneficiary\",\"created_at\":\"2025-01-08T02:03:53.186416Z\"}', null, '2025-01-08 02:03:53', '2025-01-08 02:03:53');
INSERT INTO `notifications` VALUES ('0625587e-2f13-4e2d-a49f-4f5ca2a84076', 'App\\Notifications\\ActivityNotification', 'App\\Models\\User', '22', '{\"type\":0,\"from\":\"staff\",\"image\":null,\"message\":\"Cancelled the Verification for beneficiary endorsement request\",\"url\":\"http:\\/\\/localhost:8000\\/focal\\/requests\\/beneficiary\",\"created_at\":\"2025-01-07T14:27:10.384801Z\"}', null, '2025-01-07 14:27:10', '2025-01-07 14:27:10');
INSERT INTO `notifications` VALUES ('06650fc4-6acd-408b-9251-be42a35bb879', 'App\\Notifications\\ActivityNotification', 'App\\Models\\User', '22', '{\"type\":0,\"from\":\"Administrator\",\"image\":null,\"message\":\"Rejected new beneficiary request\",\"url\":\"http:\\/\\/localhost:8000\\/focal\\/requests\\/beneficiary\",\"created_at\":\"2025-01-07T15:19:57.301634Z\"}', '2025-01-07 15:22:35', '2025-01-07 15:19:57', '2025-01-07 15:22:35');
INSERT INTO `notifications` VALUES ('0741157e-c9b5-4b66-9ca2-41f17eb91083', 'App\\Notifications\\ActivityNotification', 'App\\Models\\User', '23', '{\"type\":0,\"from\":\"Administrator\",\"image\":null,\"message\":\"Approved the beneficiary endorsement request\",\"url\":\"http:\\/\\/localhost:8000\\/barangay\\/requests\\/beneficiary\",\"created_at\":\"2025-01-08T02:50:37.477753Z\"}', null, '2025-01-08 02:50:37', '2025-01-08 02:50:37');
INSERT INTO `notifications` VALUES ('0ae6778c-0341-496d-8fb2-a84ac3f4e06a', 'App\\Notifications\\ActivityNotification', 'App\\Models\\User', '22', '{\"type\":0,\"from\":\"staff\",\"image\":null,\"message\":\"Cancelled the Verification for beneficiary endorsement request\",\"url\":\"http:\\/\\/localhost:8000\\/focal\\/requests\\/beneficiary\",\"created_at\":\"2025-01-07T14:26:43.070329Z\"}', null, '2025-01-07 14:26:43', '2025-01-07 14:26:43');
INSERT INTO `notifications` VALUES ('0bbc2b8e-d19e-4e14-ac8e-bc048e21a113', 'App\\Notifications\\ActivityNotification', 'App\\Models\\User', '14', '{\"type\":0,\"from\":\"Administrator\",\"image\":null,\"message\":\"Cancel the rejection of the endorsement request\",\"url\":\"http:\\/\\/localhost:8000\\/staff\\/requests\\/new-beneficiaries\",\"created_at\":\"2025-01-08T04:09:16.071054Z\"}', null, '2025-01-08 04:09:16', '2025-01-08 04:09:16');
INSERT INTO `notifications` VALUES ('0c8d66b3-3f99-4403-a03b-55b1ad455e23', 'App\\Notifications\\ActivityNotification', 'App\\Models\\User', '14', '{\"type\":2,\"from\":\"Administrator\",\"image\":null,\"message\":\"Reviewed the program Pamaskong Hakdog you requested.\",\"url\":\"http:\\/\\/localhost:8000\\/staff\\/program_info\\/7\",\"created_at\":\"2025-01-07T17:00:00.768755Z\"}', null, '2025-01-07 17:00:00', '2025-01-07 17:00:00');
INSERT INTO `notifications` VALUES ('0f0eb40d-3299-4f89-b22a-03b0741be347', 'App\\Notifications\\ActivityNotification', 'App\\Models\\User', '23', '{\"type\":0,\"from\":\"Focal Women\",\"image\":null,\"message\":\"Submitted a new beneficiary request\",\"url\":\"http:\\/\\/localhost:8000\\/barangay\\/requests\\/beneficiary\",\"created_at\":\"2025-01-07T17:14:19.418310Z\"}', null, '2025-01-07 17:14:19', '2025-01-07 17:14:19');
INSERT INTO `notifications` VALUES ('1060afbb-5d92-433c-b0d8-2d5d1c837c22', 'App\\Notifications\\ActivityNotification', 'App\\Models\\User', '14', '{\"type\":2,\"from\":\"Administrator\",\"image\":null,\"message\":\"Rejected the program Pamaskong Hakdog you requested.\",\"url\":\"http:\\/\\/localhost:8000\\/staff\\/program_info\\/7\",\"created_at\":\"2025-01-07T16:58:59.087151Z\"}', null, '2025-01-07 16:58:59', '2025-01-07 16:58:59');
INSERT INTO `notifications` VALUES ('117ed3bc-4eaf-4c78-9c66-b478b09ff356', 'App\\Notifications\\ActivityNotification', 'App\\Models\\User', '23', '{\"type\":0,\"from\":\"Administrator\",\"image\":null,\"message\":\"Rejected the new beneficiary request\",\"url\":\"http:\\/\\/localhost:8000\\/barangay\\/requests\\/beneficiary\",\"created_at\":\"2025-01-07T15:19:57.306441Z\"}', null, '2025-01-07 15:19:57', '2025-01-07 15:19:57');
INSERT INTO `notifications` VALUES ('11c82271-c47d-4d97-8e9e-b4e268cf632e', 'App\\Notifications\\ActivityNotification', 'App\\Models\\User', '23', '{\"type\":0,\"from\":\"staff\",\"image\":null,\"message\":\"Cancelled the Verification for the beneficiary endorsement request\",\"url\":\"http:\\/\\/localhost:8000\\/barangay\\/requests\\/beneficiary\",\"created_at\":\"2025-01-07T14:53:53.109387Z\"}', null, '2025-01-07 14:53:53', '2025-01-07 14:53:53');
INSERT INTO `notifications` VALUES ('17dad957-bec7-4342-b9b8-781cb21635fe', 'App\\Notifications\\ActivityNotification', 'App\\Models\\User', '13', '{\"type\":0,\"from\":\"staff\",\"image\":null,\"message\":\"Verified the beneficiary endorsement request\",\"url\":\"http:\\/\\/localhost:8000\\/admin\\/request\\/beneficiaries\",\"created_at\":\"2025-01-07T14:54:16.106697Z\"}', null, '2025-01-07 14:54:16', '2025-01-07 14:54:16');
INSERT INTO `notifications` VALUES ('1a491331-9ed9-42e8-b820-cfc3a75c6cff', 'App\\Notifications\\ActivityNotification', 'App\\Models\\User', '13', '{\"type\":2,\"from\":\"staff\",\"image\":null,\"message\":\"Created a new pogram.\",\"url\":\"http:\\/\\/localhost:8000\\/admin\\/request\\/programs\",\"created_at\":\"2025-01-07T15:43:07.055361Z\"}', null, '2025-01-07 15:43:07', '2025-01-07 15:43:07');
INSERT INTO `notifications` VALUES ('1b4a206e-8669-4748-9e28-246d5b311478', 'App\\Notifications\\ActivityNotification', 'App\\Models\\User', '23', '{\"type\":0,\"from\":\"Administrator\",\"image\":null,\"message\":\"Rejected the new beneficiary request\",\"url\":\"http:\\/\\/localhost:8000\\/barangay\\/requests\\/beneficiary\",\"created_at\":\"2025-01-08T02:29:41.234076Z\"}', null, '2025-01-08 02:29:41', '2025-01-08 02:29:41');
INSERT INTO `notifications` VALUES ('1b4b4571-a16c-412e-89cb-4a72ac5977fd', 'App\\Notifications\\ActivityNotification', 'App\\Models\\User', '23', '{\"type\":0,\"from\":\"staff\",\"image\":null,\"message\":\"Rejected the new beneficiary request\",\"url\":\"http:\\/\\/localhost:8000\\/barangay\\/requests\\/beneficiary\",\"created_at\":\"2025-01-07T14:26:36.700899Z\"}', null, '2025-01-07 14:26:36', '2025-01-07 14:26:36');
INSERT INTO `notifications` VALUES ('1d6a41ac-0229-49c1-bf6b-644c9ea97d4a', 'App\\Notifications\\ActivityNotification', 'App\\Models\\User', '13', '{\"type\":0,\"from\":\"staff\",\"image\":null,\"message\":\"Cancelled the Rejection for the beneficiary endorsement request\",\"url\":\"http:\\/\\/localhost:8000\\/admin\\/request\\/beneficiaries\",\"created_at\":\"2025-01-08T04:08:17.870869Z\"}', null, '2025-01-08 04:08:17', '2025-01-08 04:08:17');
INSERT INTO `notifications` VALUES ('1dd091f7-ac56-428b-9599-a2267676e813', 'App\\Notifications\\ActivityNotification', 'App\\Models\\User', '23', '{\"type\":0,\"from\":\"staff\",\"image\":null,\"message\":\"Cancelled the Verification for the beneficiary endorsement request\",\"url\":\"http:\\/\\/localhost:8000\\/barangay\\/requests\\/beneficiary\",\"created_at\":\"2025-01-08T02:14:38.412207Z\"}', null, '2025-01-08 02:14:38', '2025-01-08 02:14:38');
INSERT INTO `notifications` VALUES ('1feefa6a-099d-4a23-95c7-b221f610678e', 'App\\Notifications\\ActivityNotification', 'App\\Models\\User', '31', '{\"type\":0,\"from\":\"Administrator\",\"image\":null,\"message\":\"Approved your endorsement request\",\"url\":\"http:\\/\\/localhost:8000\\/beneficiary\\/my-profile\",\"created_at\":\"2025-01-08T04:09:19.270308Z\"}', null, '2025-01-08 04:09:19', '2025-01-08 04:09:19');
INSERT INTO `notifications` VALUES ('21407012-bb9a-44ee-9679-3b32e18f9ed1', 'App\\Notifications\\ActivityNotification', 'App\\Models\\User', '31', '{\"type\":0,\"from\":\"Administrator\",\"image\":null,\"message\":\"Approved your endorsement request\",\"url\":\"http:\\/\\/localhost:8000\\/beneficiary\\/my-profile\",\"created_at\":\"2025-01-08T02:50:37.474042Z\"}', null, '2025-01-08 02:50:37', '2025-01-08 02:50:37');
INSERT INTO `notifications` VALUES ('22e88061-64e8-4236-8ed4-99af5f674c99', 'App\\Notifications\\ActivityNotification', 'App\\Models\\User', '13', '{\"type\":0,\"from\":\"staff\",\"image\":null,\"message\":\"Verified the beneficiary endorsement request\",\"url\":\"http:\\/\\/localhost:8000\\/admin\\/request\\/beneficiaries\",\"created_at\":\"2025-01-07T14:54:12.339969Z\"}', null, '2025-01-07 14:54:12', '2025-01-07 14:54:12');
INSERT INTO `notifications` VALUES ('23234c11-2f55-49bc-a54b-2971c5833c98', 'App\\Notifications\\ActivityNotification', 'App\\Models\\User', '22', '{\"type\":0,\"from\":\"staff\",\"image\":null,\"message\":\"Verified the beneficiary endorsement request\",\"url\":\"http:\\/\\/localhost:8000\\/focal\\/requests\\/beneficiary\",\"created_at\":\"2025-01-07T14:29:43.670517Z\"}', null, '2025-01-07 14:29:43', '2025-01-07 14:29:43');
INSERT INTO `notifications` VALUES ('272007cf-b2b4-405a-a4ab-990f4d83cb88', 'App\\Notifications\\ActivityNotification', 'App\\Models\\User', '14', '{\"type\":0,\"from\":\"Administrator\",\"image\":null,\"message\":\"Rejected the endorsement request\",\"url\":\"http:\\/\\/localhost:8000\\/staff\\/requests\\/new-beneficiaries\",\"created_at\":\"2025-01-08T04:09:02.020975Z\"}', null, '2025-01-08 04:09:02', '2025-01-08 04:09:02');
INSERT INTO `notifications` VALUES ('280c551d-61bd-4c9b-a082-3b9a7a29a379', 'App\\Notifications\\ActivityNotification', 'App\\Models\\User', '22', '{\"type\":0,\"from\":\"Administrator\",\"image\":null,\"message\":\"Approved the beneficiary endorsement request\",\"url\":\"http:\\/\\/localhost:8000\\/focal\\/requests\\/beneficiary\",\"created_at\":\"2025-01-08T02:43:31.751742Z\"}', null, '2025-01-08 02:43:31', '2025-01-08 02:43:31');
INSERT INTO `notifications` VALUES ('28ce7808-ca87-4d5c-9f3d-1cc953336aa1', 'App\\Notifications\\ActivityNotification', 'App\\Models\\User', '23', '{\"type\":0,\"from\":\"staff\",\"image\":null,\"message\":\"Cancelled the Verification for the beneficiary endorsement request\",\"url\":\"http:\\/\\/localhost:8000\\/barangay\\/requests\\/beneficiary\",\"created_at\":\"2025-01-07T14:29:13.003257Z\"}', null, '2025-01-07 14:29:13', '2025-01-07 14:29:13');
INSERT INTO `notifications` VALUES ('2a018877-be4d-45aa-9c46-d52630236ec4', 'App\\Notifications\\ActivityNotification', 'App\\Models\\User', '14', '{\"type\":0,\"from\":\"Barangay Baluc\",\"image\":\"1731767807-Dw73j.jpg\",\"message\":\"Successfully checked your new beneficiary request\",\"url\":\"http:\\/\\/localhost:8000\\/staff\\/requests\\/new-beneficiaries\",\"created_at\":\"2025-01-08T02:03:40.646272Z\"}', null, '2025-01-08 02:03:40', '2025-01-08 02:03:40');
INSERT INTO `notifications` VALUES ('2cc8e9c5-3434-40e8-ae27-98d3218972fa', 'App\\Notifications\\ActivityNotification', 'App\\Models\\User', '14', '{\"type\":0,\"from\":\"Barangay Baluc\",\"image\":\"1731767807-Dw73j.jpg\",\"message\":\"Successfully checked your new beneficiary request\",\"url\":\"http:\\/\\/localhost:8000\\/staff\\/requests\\/new-beneficiaries\",\"created_at\":\"2025-01-07T14:11:48.437181Z\"}', null, '2025-01-07 14:11:48', '2025-01-07 14:11:48');
INSERT INTO `notifications` VALUES ('2e1030f3-ea47-427b-8509-972e5be227c9', 'App\\Notifications\\ActivityNotification', 'App\\Models\\User', '14', '{\"type\":0,\"from\":\"Barangay Baluc\",\"image\":\"1731767807-Dw73j.jpg\",\"message\":\"Successfully checked your new beneficiary request\",\"url\":\"http:\\/\\/localhost:8000\\/staff\\/requests\\/new-beneficiaries\",\"created_at\":\"2025-01-07T08:44:03.874139Z\"}', '2025-01-07 08:52:06', '2025-01-07 08:44:03', '2025-01-07 08:52:06');
INSERT INTO `notifications` VALUES ('2e1128d5-b0d6-4e3f-88e1-9a82b9c76145', 'App\\Notifications\\ActivityNotification', 'App\\Models\\User', '31', '{\"type\":0,\"from\":\"Barangay Baluc\",\"image\":\"1731767807-Dw73j.jpg\",\"message\":\"Rejected your endorsement\",\"url\":\"http:\\/\\/localhost:8000\\/beneficiary\\/my-profile\",\"created_at\":\"2025-01-07T08:48:05.828749Z\"}', '2025-01-07 08:49:47', '2025-01-07 08:48:05', '2025-01-07 08:49:47');
INSERT INTO `notifications` VALUES ('3139beea-35a6-4ce2-aba7-51f70178d8f2', 'App\\Notifications\\ActivityNotification', 'App\\Models\\User', '31', '{\"type\":0,\"from\":\"staff\",\"image\":null,\"message\":\"Verified your endorsement request\",\"url\":\"http:\\/\\/localhost:8000\\/beneficiary\\/my-profile\",\"created_at\":\"2025-01-08T04:08:20.673126Z\"}', null, '2025-01-08 04:08:20', '2025-01-08 04:08:20');
INSERT INTO `notifications` VALUES ('329cd2eb-b51e-4000-be5b-6776485fb191', 'App\\Notifications\\ActivityNotification', 'App\\Models\\User', '22', '{\"type\":0,\"from\":\"staff\",\"image\":null,\"message\":\"Cancelled the Verification for beneficiary endorsement request\",\"url\":\"http:\\/\\/localhost:8000\\/focal\\/requests\\/beneficiary\",\"created_at\":\"2025-01-07T14:53:53.105823Z\"}', null, '2025-01-07 14:53:53', '2025-01-07 14:53:53');
INSERT INTO `notifications` VALUES ('3a0dbaeb-2e6e-4d5b-aaf1-39c640426ad4', 'App\\Notifications\\ActivityNotification', 'App\\Models\\User', '23', '{\"type\":0,\"from\":\"staff\",\"image\":null,\"message\":\"Cancelled the Rejection for the beneficiary endorsement request\",\"url\":\"http:\\/\\/localhost:8000\\/barangay\\/requests\\/beneficiary\",\"created_at\":\"2025-01-08T04:08:17.868833Z\"}', null, '2025-01-08 04:08:17', '2025-01-08 04:08:17');
INSERT INTO `notifications` VALUES ('3b86bd93-1713-4863-bd95-cb9a3663f7d6', 'App\\Notifications\\ActivityNotification', 'App\\Models\\User', '23', '{\"type\":0,\"from\":\"staff\",\"image\":null,\"message\":\"Verified the beneficiary endorsement request\",\"url\":\"http:\\/\\/localhost:8000\\/barangay\\/requests\\/beneficiary\",\"created_at\":\"2025-01-08T02:48:28.422264Z\"}', null, '2025-01-08 02:48:28', '2025-01-08 02:48:28');
INSERT INTO `notifications` VALUES ('3b95cd44-d60d-43ee-a4c9-a965543aab86', 'App\\Notifications\\ActivityNotification', 'App\\Models\\User', '22', '{\"type\":0,\"from\":\"Barangay Baluc\",\"image\":\"1731767807-Dw73j.jpg\",\"message\":\"Rejected your new beneficiary request\",\"url\":\"http:\\/\\/localhost:8000\\/focal\\/requests\\/beneficiary\",\"created_at\":\"2025-01-08T04:06:45.896899Z\"}', null, '2025-01-08 04:06:45', '2025-01-08 04:06:45');
INSERT INTO `notifications` VALUES ('3c401ab7-e222-4439-b88d-600d40e7ea8c', 'App\\Notifications\\ActivityNotification', 'App\\Models\\User', '14', '{\"type\":2,\"from\":\"Administrator\",\"image\":null,\"message\":\"Cancelled the rejected og the program Pamaskong Hakdog you requested.\",\"url\":\"http:\\/\\/localhost:8000\\/staff\\/program_info\\/7\",\"created_at\":\"2025-01-07T16:59:14.413844Z\"}', null, '2025-01-07 16:59:14', '2025-01-07 16:59:14');
INSERT INTO `notifications` VALUES ('3d37f111-03a6-4be4-b5c5-9e2d5a63ddbd', 'App\\Notifications\\ActivityNotification', 'App\\Models\\User', '13', '{\"type\":0,\"from\":\"staff\",\"image\":null,\"message\":\"Cancelled the Verification for the beneficiary endorsement request\",\"url\":\"http:\\/\\/localhost:8000\\/admin\\/request\\/beneficiaries\",\"created_at\":\"2025-01-08T02:14:38.414676Z\"}', null, '2025-01-08 02:14:38', '2025-01-08 02:14:38');
INSERT INTO `notifications` VALUES ('3da1e7bc-0acc-4e73-970a-94d855e58278', 'App\\Notifications\\ActivityNotification', 'App\\Models\\User', '23', '{\"type\":0,\"from\":\"staff\",\"image\":null,\"message\":\"Verified the beneficiary endorsement request\",\"url\":\"http:\\/\\/localhost:8000\\/barangay\\/requests\\/beneficiary\",\"created_at\":\"2025-01-08T02:29:15.692899Z\"}', null, '2025-01-08 02:29:15', '2025-01-08 02:29:15');
INSERT INTO `notifications` VALUES ('3e232f93-7893-40c8-9c2e-3f5017ccb4b4', 'App\\Notifications\\ActivityNotification', 'App\\Models\\User', '23', '{\"type\":0,\"from\":\"Administrator\",\"image\":null,\"message\":\"Rejected the new beneficiary request\",\"url\":\"http:\\/\\/localhost:8000\\/barangay\\/requests\\/beneficiary\",\"created_at\":\"2025-01-08T04:09:02.029783Z\"}', null, '2025-01-08 04:09:02', '2025-01-08 04:09:02');
INSERT INTO `notifications` VALUES ('3f22bfbc-ab4d-4d8b-be7c-1d7f131c5e4d', 'App\\Notifications\\ActivityNotification', 'App\\Models\\User', '22', '{\"type\":0,\"from\":\"Administrator\",\"image\":null,\"message\":\"Rejected new beneficiary request\",\"url\":\"http:\\/\\/localhost:8000\\/focal\\/requests\\/beneficiary\",\"created_at\":\"2025-01-08T04:09:02.027944Z\"}', null, '2025-01-08 04:09:02', '2025-01-08 04:09:02');
INSERT INTO `notifications` VALUES ('40c8cabe-261e-4417-9222-99a66366a790', 'App\\Notifications\\ActivityNotification', 'App\\Models\\User', '23', '{\"type\":0,\"from\":\"Focal Women\",\"image\":null,\"message\":\"Submitted a new beneficiary request\",\"url\":\"http:\\/\\/localhost:8000\\/barangay\\/requests\\/beneficiary\",\"created_at\":\"2025-01-08T04:01:20.323040Z\"}', null, '2025-01-08 04:01:20', '2025-01-08 04:01:20');
INSERT INTO `notifications` VALUES ('41c0a5f8-48da-4964-8270-c3cde0aa648d', 'App\\Notifications\\ActivityNotification', 'App\\Models\\User', '22', '{\"type\":0,\"from\":\"Barangay Baluc\",\"image\":\"1731767807-Dw73j.jpg\",\"message\":\"Successfully checked your new beneficiary request\",\"url\":\"http:\\/\\/localhost:8000\\/focal\\/requests\\/beneficiary\",\"created_at\":\"2025-01-08T04:07:09.777010Z\"}', null, '2025-01-08 04:07:09', '2025-01-08 04:07:09');
INSERT INTO `notifications` VALUES ('45042fad-cb9f-4700-98d9-e285db47a677', 'App\\Notifications\\ActivityNotification', 'App\\Models\\User', '31', '{\"type\":0,\"from\":\"Barangay Baluc\",\"image\":\"1731767807-Dw73j.jpg\",\"message\":\"Checked your endorsement\",\"url\":\"http:\\/\\/localhost:8000\\/beneficiary\\/my-profile\",\"created_at\":\"2025-01-08T04:07:09.770511Z\"}', null, '2025-01-08 04:07:09', '2025-01-08 04:07:09');
INSERT INTO `notifications` VALUES ('47da769a-bdf6-4a95-8f2d-88d4e395ca3b', 'App\\Notifications\\ActivityNotification', 'App\\Models\\User', '31', '{\"type\":0,\"from\":\"Barangay Baluc\",\"image\":\"1731767807-Dw73j.jpg\",\"message\":\"Undo the response to your endorsement\",\"url\":\"http:\\/\\/localhost:8000\\/beneficiary\\/my-profile\",\"created_at\":\"2025-01-08T04:07:07.046256Z\"}', null, '2025-01-08 04:07:07', '2025-01-08 04:07:07');
INSERT INTO `notifications` VALUES ('4a8fe49d-52de-45fc-a8f3-9788cac8d687', 'App\\Notifications\\ActivityNotification', 'App\\Models\\User', '31', '{\"type\":0,\"from\":\"Barangay Baluc\",\"image\":\"1731767807-Dw73j.jpg\",\"message\":\"Undo the response to your endorsement\",\"url\":\"http:\\/\\/localhost:8000\\/beneficiary\\/my-profile\",\"created_at\":\"2025-01-07T08:50:01.275257Z\"}', '2025-01-07 08:50:09', '2025-01-07 08:50:01', '2025-01-07 08:50:09');
INSERT INTO `notifications` VALUES ('4c534910-c38c-4879-b93c-6ca1ae57c526', 'App\\Notifications\\ActivityNotification', 'App\\Models\\User', '23', '{\"type\":0,\"from\":\"staff\",\"image\":null,\"message\":\"Verified the beneficiary endorsement request\",\"url\":\"http:\\/\\/localhost:8000\\/barangay\\/requests\\/beneficiary\",\"created_at\":\"2025-01-07T14:26:57.822078Z\"}', null, '2025-01-07 14:26:57', '2025-01-07 14:26:57');
INSERT INTO `notifications` VALUES ('50b8712a-6945-4ef1-a8dd-6f8d7c40427d', 'App\\Notifications\\ActivityNotification', 'App\\Models\\User', '31', '{\"type\":0,\"from\":\"Barangay Baluc\",\"image\":\"1731767807-Dw73j.jpg\",\"message\":\"Checked your endorsement\",\"url\":\"http:\\/\\/localhost:8000\\/beneficiary\\/my-profile\",\"created_at\":\"2025-01-08T02:48:05.580289Z\"}', null, '2025-01-08 02:48:05', '2025-01-08 02:48:05');
INSERT INTO `notifications` VALUES ('50f0ee04-ec7c-4adf-a88c-4e82ebd53ba8', 'App\\Notifications\\ActivityNotification', 'App\\Models\\User', '13', '{\"type\":0,\"from\":\"staff\",\"image\":null,\"message\":\"Verified the beneficiary endorsement request\",\"url\":\"http:\\/\\/localhost:8000\\/admin\\/request\\/beneficiaries\",\"created_at\":\"2025-01-08T02:20:08.847248Z\"}', null, '2025-01-08 02:20:08', '2025-01-08 02:20:08');
INSERT INTO `notifications` VALUES ('5168edbe-0802-452f-9ce1-308978923705', 'App\\Notifications\\ActivityNotification', 'App\\Models\\User', '13', '{\"type\":0,\"from\":\"staff\",\"image\":null,\"message\":\"Verified the beneficiary endorsement request\",\"url\":\"http:\\/\\/localhost:8000\\/admin\\/request\\/beneficiaries\",\"created_at\":\"2025-01-08T02:48:28.424204Z\"}', '2025-01-08 02:48:56', '2025-01-08 02:48:28', '2025-01-08 02:48:56');
INSERT INTO `notifications` VALUES ('51a48661-d77d-4f8f-9edc-4929e90504fc', 'App\\Notifications\\ActivityNotification', 'App\\Models\\User', '14', '{\"type\":0,\"from\":\"Barangay Baluc\",\"image\":\"1731767807-Dw73j.jpg\",\"message\":\"Successfully checked your new beneficiary request\",\"url\":\"http:\\/\\/localhost:8000\\/staff\\/requests\\/new-beneficiaries\",\"created_at\":\"2025-01-08T04:07:09.775104Z\"}', null, '2025-01-08 04:07:09', '2025-01-08 04:07:09');
INSERT INTO `notifications` VALUES ('54f9785d-b930-463c-b50d-a56f0d62b511', 'App\\Notifications\\ActivityNotification', 'App\\Models\\User', '14', '{\"type\":0,\"from\":\"Barangay Baluc\",\"image\":\"1731767807-Dw73j.jpg\",\"message\":\"Successfully checked your new beneficiary request\",\"url\":\"http:\\/\\/localhost:8000\\/staff\\/requests\\/new-beneficiaries\",\"created_at\":\"2025-01-07T14:11:51.694909Z\"}', '2025-01-07 15:26:12', '2025-01-07 14:11:51', '2025-01-07 15:26:12');
INSERT INTO `notifications` VALUES ('5715dd19-ded9-441c-9113-6d77ee8e1ecc', 'App\\Notifications\\ActivityNotification', 'App\\Models\\User', '31', '{\"type\":0,\"from\":\"Barangay Baluc\",\"image\":\"1731767807-Dw73j.jpg\",\"message\":\"Checked your endorsement\",\"url\":\"http:\\/\\/localhost:8000\\/beneficiary\\/my-profile\",\"created_at\":\"2025-01-07T08:44:03.869679Z\"}', '2025-01-07 08:45:13', '2025-01-07 08:44:03', '2025-01-07 08:45:13');
INSERT INTO `notifications` VALUES ('5a9ed7ea-d0e6-4d6f-8bac-73d9218c85ea', 'App\\Notifications\\ActivityNotification', 'App\\Models\\User', '22', '{\"type\":0,\"from\":\"staff\",\"image\":null,\"message\":\"Verified the beneficiary endorsement request\",\"url\":\"http:\\/\\/localhost:8000\\/focal\\/requests\\/beneficiary\",\"created_at\":\"2025-01-07T14:54:16.100006Z\"}', null, '2025-01-07 14:54:16', '2025-01-07 14:54:16');
INSERT INTO `notifications` VALUES ('5e0edf60-a0ea-463a-8b91-9ed5035ddac3', 'App\\Notifications\\ActivityNotification', 'App\\Models\\User', '22', '{\"type\":0,\"from\":\"Barangay Baluc\",\"image\":\"1731767807-Dw73j.jpg\",\"message\":\"Successfully checked your new beneficiary request\",\"url\":\"http:\\/\\/localhost:8000\\/focal\\/requests\\/beneficiary\",\"created_at\":\"2025-01-08T02:48:05.599109Z\"}', null, '2025-01-08 02:48:05', '2025-01-08 02:48:05');
INSERT INTO `notifications` VALUES ('60493f20-b056-4246-93d3-123f74af08f8', 'App\\Notifications\\ActivityNotification', 'App\\Models\\User', '13', '{\"type\":0,\"from\":\"staff\",\"image\":null,\"message\":\"Verified the beneficiary endorsement request\",\"url\":\"http:\\/\\/localhost:8000\\/admin\\/request\\/beneficiaries\",\"created_at\":\"2025-01-07T14:29:43.677652Z\"}', null, '2025-01-07 14:29:43', '2025-01-07 14:29:43');
INSERT INTO `notifications` VALUES ('60d2af9a-bf17-4525-a660-60efd831bcc0', 'App\\Notifications\\ActivityNotification', 'App\\Models\\User', '22', '{\"type\":0,\"from\":\"staff\",\"image\":null,\"message\":\"Verified the beneficiary endorsement request\",\"url\":\"http:\\/\\/localhost:8000\\/focal\\/requests\\/beneficiary\",\"created_at\":\"2025-01-08T04:08:20.678055Z\"}', null, '2025-01-08 04:08:20', '2025-01-08 04:08:20');
INSERT INTO `notifications` VALUES ('61c9f901-0830-40b8-b560-2416ff998b5e', 'App\\Notifications\\ActivityNotification', 'App\\Models\\User', '23', '{\"type\":0,\"from\":\"Administrator\",\"image\":null,\"message\":\"Approved the beneficiary endorsement request\",\"url\":\"http:\\/\\/localhost:8000\\/barangay\\/requests\\/beneficiary\",\"created_at\":\"2025-01-08T02:43:29.031984Z\"}', null, '2025-01-08 02:43:29', '2025-01-08 02:43:29');
INSERT INTO `notifications` VALUES ('65c8f65b-5ff7-4ac2-86ee-6c8a2425d6eb', 'App\\Notifications\\ActivityNotification', 'App\\Models\\User', '22', '{\"type\":0,\"from\":\"staff\",\"image\":null,\"message\":\"Rejected new beneficiary request\",\"url\":\"http:\\/\\/localhost:8000\\/focal\\/requests\\/beneficiary\",\"created_at\":\"2025-01-08T04:08:04.531593Z\"}', null, '2025-01-08 04:08:04', '2025-01-08 04:08:04');
INSERT INTO `notifications` VALUES ('68189ffd-701f-4807-8642-67de9264555e', 'App\\Notifications\\ActivityNotification', 'App\\Models\\User', '22', '{\"type\":0,\"from\":\"staff\",\"image\":null,\"message\":\"Rejected new beneficiary request\",\"url\":\"http:\\/\\/localhost:8000\\/focal\\/requests\\/beneficiary\",\"created_at\":\"2025-01-07T14:28:21.933237Z\"}', null, '2025-01-07 14:28:21', '2025-01-07 14:28:21');
INSERT INTO `notifications` VALUES ('68813bfb-4058-4082-b322-62cadf37ff2c', 'App\\Notifications\\ActivityNotification', 'App\\Models\\User', '31', '{\"type\":0,\"from\":\"staff\",\"image\":null,\"message\":\"Verified your endorsement request\",\"url\":\"http:\\/\\/localhost:8000\\/beneficiary\\/my-profile\",\"created_at\":\"2025-01-07T14:54:12.331770Z\"}', '2025-01-07 14:54:23', '2025-01-07 14:54:12', '2025-01-07 14:54:23');
INSERT INTO `notifications` VALUES ('6adf4239-fc4f-4ca6-9bf8-6de135fe1bf7', 'App\\Notifications\\ActivityNotification', 'App\\Models\\User', '14', '{\"type\":0,\"from\":\"Barangay Baluc\",\"image\":\"1731767807-Dw73j.jpg\",\"message\":\"Successfully checked your new beneficiary request\",\"url\":\"http:\\/\\/localhost:8000\\/staff\\/requests\\/new-beneficiaries\",\"created_at\":\"2025-01-08T02:48:05.589311Z\"}', null, '2025-01-08 02:48:05', '2025-01-08 02:48:05');
INSERT INTO `notifications` VALUES ('6c146fff-7b5b-451c-8457-e44c225fd6cd', 'App\\Notifications\\ActivityNotification', 'App\\Models\\User', '22', '{\"type\":0,\"from\":\"staff\",\"image\":null,\"message\":\"Verified the beneficiary endorsement request\",\"url\":\"http:\\/\\/localhost:8000\\/focal\\/requests\\/beneficiary\",\"created_at\":\"2025-01-07T14:26:57.817445Z\"}', null, '2025-01-07 14:26:57', '2025-01-07 14:26:57');
INSERT INTO `notifications` VALUES ('6cab477a-2837-4026-a465-51b66d87b9dd', 'App\\Notifications\\ActivityNotification', 'App\\Models\\User', '14', '{\"type\":0,\"from\":\"Administrator\",\"image\":null,\"message\":\"Approved the endorsement request\",\"url\":\"http:\\/\\/localhost:8000\\/staff\\/requests\\/new-beneficiaries\",\"created_at\":\"2025-01-08T04:09:19.263457Z\"}', null, '2025-01-08 04:09:19', '2025-01-08 04:09:19');
INSERT INTO `notifications` VALUES ('70aecfb8-f402-4e91-aa2d-a4c63c72b525', 'App\\Notifications\\ActivityNotification', 'App\\Models\\User', '23', '{\"type\":0,\"from\":\"staff\",\"image\":null,\"message\":\"Verified the beneficiary endorsement request\",\"url\":\"http:\\/\\/localhost:8000\\/barangay\\/requests\\/beneficiary\",\"created_at\":\"2025-01-07T14:54:12.337986Z\"}', null, '2025-01-07 14:54:12', '2025-01-07 14:54:12');
INSERT INTO `notifications` VALUES ('7200008c-8777-4a53-9b00-dca3e33a8938', 'App\\Notifications\\ActivityNotification', 'App\\Models\\User', '31', '{\"type\":0,\"from\":\"staff\",\"image\":null,\"message\":\"Verified your endorsement request\",\"url\":\"http:\\/\\/localhost:8000\\/beneficiary\\/my-profile\",\"created_at\":\"2025-01-08T02:48:28.413616Z\"}', null, '2025-01-08 02:48:28', '2025-01-08 02:48:28');
INSERT INTO `notifications` VALUES ('74387dc7-0f65-4e38-aaf7-030dd069c7d2', 'App\\Notifications\\ActivityNotification', 'App\\Models\\User', '22', '{\"type\":0,\"from\":\"Administrator\",\"image\":null,\"message\":\"Approved the beneficiary endorsement request\",\"url\":\"http:\\/\\/localhost:8000\\/focal\\/requests\\/beneficiary\",\"created_at\":\"2025-01-08T02:43:29.026635Z\"}', '2025-01-08 02:47:25', '2025-01-08 02:43:29', '2025-01-08 02:47:25');
INSERT INTO `notifications` VALUES ('754a5e07-fdda-4559-9fa6-378c677e88b1', 'App\\Notifications\\ActivityNotification', 'App\\Models\\User', '13', '{\"type\":0,\"from\":\"staff\",\"image\":null,\"message\":\"Verified the beneficiary endorsement request\",\"url\":\"http:\\/\\/localhost:8000\\/admin\\/request\\/beneficiaries\",\"created_at\":\"2025-01-08T02:29:15.700033Z\"}', null, '2025-01-08 02:29:15', '2025-01-08 02:29:15');
INSERT INTO `notifications` VALUES ('7a39f19a-c130-4021-8af0-fc1684df6c51', 'App\\Notifications\\ActivityNotification', 'App\\Models\\User', '14', '{\"type\":0,\"from\":\"Administrator\",\"image\":null,\"message\":\"Approved the endorsement request\",\"url\":\"http:\\/\\/localhost:8000\\/staff\\/requests\\/new-beneficiaries\",\"created_at\":\"2025-01-08T02:50:37.468332Z\"}', null, '2025-01-08 02:50:37', '2025-01-08 02:50:37');
INSERT INTO `notifications` VALUES ('7aa4dde8-2e81-410a-b33a-79acd2719f1d', 'App\\Notifications\\ActivityNotification', 'App\\Models\\User', '14', '{\"type\":0,\"from\":\"Barangay Baluc\",\"image\":\"1731767807-Dw73j.jpg\",\"message\":\"Cancelled checking the new beneficiary request\",\"url\":\"http:\\/\\/localhost:8000\\/staff\\/requests\\/new-beneficiaries\",\"created_at\":\"2025-01-08T04:07:07.038373Z\"}', null, '2025-01-08 04:07:07', '2025-01-08 04:07:07');
INSERT INTO `notifications` VALUES ('7bc4a16b-4300-48e3-8b9b-187bb28a57a2', 'App\\Notifications\\ActivityNotification', 'App\\Models\\User', '22', '{\"type\":0,\"from\":\"staff\",\"image\":null,\"message\":\"Verified the beneficiary endorsement request\",\"url\":\"http:\\/\\/localhost:8000\\/focal\\/requests\\/beneficiary\",\"created_at\":\"2025-01-08T02:29:15.682714Z\"}', null, '2025-01-08 02:29:15', '2025-01-08 02:29:15');
INSERT INTO `notifications` VALUES ('7fe86c06-fb68-44bd-bd3b-0f6bb9ac0bba', 'App\\Notifications\\ActivityNotification', 'App\\Models\\User', '23', '{\"type\":0,\"from\":\"Administrator\",\"image\":null,\"message\":\"Approved the beneficiary endorsement request\",\"url\":\"http:\\/\\/localhost:8000\\/barangay\\/requests\\/beneficiary\",\"created_at\":\"2025-01-08T04:09:19.274779Z\"}', null, '2025-01-08 04:09:19', '2025-01-08 04:09:19');
INSERT INTO `notifications` VALUES ('828960f8-0892-4438-abd9-724f3b5a407f', 'App\\Notifications\\ActivityNotification', 'App\\Models\\User', '23', '{\"type\":0,\"from\":\"Administrator\",\"image\":null,\"message\":\"Approved the beneficiary endorsement request\",\"url\":\"http:\\/\\/localhost:8000\\/barangay\\/requests\\/beneficiary\",\"created_at\":\"2025-01-07T15:20:45.023742Z\"}', null, '2025-01-07 15:20:45', '2025-01-07 15:20:45');
INSERT INTO `notifications` VALUES ('8451e2ae-f62a-465a-aa7a-077bb70835b9', 'App\\Notifications\\ActivityNotification', 'App\\Models\\User', '22', '{\"type\":0,\"from\":\"staff\",\"image\":null,\"message\":\"Cancelled the Rejection for beneficiary endorsement request\",\"url\":\"http:\\/\\/localhost:8000\\/focal\\/requests\\/beneficiary\",\"created_at\":\"2025-01-08T02:19:57.483983Z\"}', null, '2025-01-08 02:19:57', '2025-01-08 02:19:57');
INSERT INTO `notifications` VALUES ('853cce29-848c-41b2-b166-69de15848205', 'App\\Notifications\\ActivityNotification', 'App\\Models\\User', '22', '{\"type\":0,\"from\":\"staff\",\"image\":null,\"message\":\"Verified the beneficiary endorsement request\",\"url\":\"http:\\/\\/localhost:8000\\/focal\\/requests\\/beneficiary\",\"created_at\":\"2025-01-08T02:48:28.420328Z\"}', null, '2025-01-08 02:48:28', '2025-01-08 02:48:28');
INSERT INTO `notifications` VALUES ('865cc7e5-3793-4582-a838-031ba1a6b21d', 'App\\Notifications\\ActivityNotification', 'App\\Models\\User', '14', '{\"type\":2,\"from\":\"Administrator\",\"image\":null,\"message\":\"Reviewed the program Pamaskong Hakdog you requested.\",\"url\":\"http:\\/\\/localhost:8000\\/staff\\/program_info\\/7\",\"created_at\":\"2025-01-07T16:53:51.327019Z\"}', null, '2025-01-07 16:53:51', '2025-01-07 16:53:51');
INSERT INTO `notifications` VALUES ('8a1bfe8d-8473-4bd1-80bc-d455edf93060', 'App\\Notifications\\ActivityNotification', 'App\\Models\\User', '22', '{\"type\":0,\"from\":\"Administrator\",\"image\":null,\"message\":\"Approved the beneficiary endorsement request\",\"url\":\"http:\\/\\/localhost:8000\\/focal\\/requests\\/beneficiary\",\"created_at\":\"2025-01-08T02:50:37.476029Z\"}', '2025-01-08 02:50:47', '2025-01-08 02:50:37', '2025-01-08 02:50:47');
INSERT INTO `notifications` VALUES ('8da3d790-ea02-409c-b080-62f284cfe1ed', 'App\\Notifications\\ActivityNotification', 'App\\Models\\User', '22', '{\"type\":0,\"from\":\"Administrator\",\"image\":null,\"message\":\"Rejected new beneficiary request\",\"url\":\"http:\\/\\/localhost:8000\\/focal\\/requests\\/beneficiary\",\"created_at\":\"2025-01-08T02:29:41.229488Z\"}', null, '2025-01-08 02:29:41', '2025-01-08 02:29:41');
INSERT INTO `notifications` VALUES ('907eab33-caad-4fcd-9453-e62ecdcec6f0', 'App\\Notifications\\ActivityNotification', 'App\\Models\\User', '13', '{\"type\":0,\"from\":\"staff\",\"image\":null,\"message\":\"Cancelled the Rejection for the beneficiary endorsement request\",\"url\":\"http:\\/\\/localhost:8000\\/admin\\/request\\/beneficiaries\",\"created_at\":\"2025-01-08T02:19:57.490710Z\"}', null, '2025-01-08 02:19:57', '2025-01-08 02:19:57');
INSERT INTO `notifications` VALUES ('90bd923c-52e8-4f09-a927-d23656852710', 'App\\Notifications\\ActivityNotification', 'App\\Models\\User', '31', '{\"type\":0,\"from\":\"Barangay Baluc\",\"image\":\"1731767807-Dw73j.jpg\",\"message\":\"Rejected your endorsement\",\"url\":\"http:\\/\\/localhost:8000\\/beneficiary\\/my-profile\",\"created_at\":\"2025-01-08T04:06:45.889920Z\"}', null, '2025-01-08 04:06:45', '2025-01-08 04:06:45');
INSERT INTO `notifications` VALUES ('927e88dd-b7e8-463f-a1cf-5d10384eb8f0', 'App\\Notifications\\ActivityNotification', 'App\\Models\\User', '22', '{\"type\":0,\"from\":\"staff\",\"image\":null,\"message\":\"Cancelled the Rejection for beneficiary endorsement request\",\"url\":\"http:\\/\\/localhost:8000\\/focal\\/requests\\/beneficiary\",\"created_at\":\"2025-01-08T04:08:17.866542Z\"}', null, '2025-01-08 04:08:17', '2025-01-08 04:08:17');
INSERT INTO `notifications` VALUES ('92840dac-5b8c-4530-8f1a-b16f53ca093f', 'App\\Notifications\\ActivityNotification', 'App\\Models\\User', '23', '{\"type\":0,\"from\":\"staff\",\"image\":null,\"message\":\"Rejected the new beneficiary request\",\"url\":\"http:\\/\\/localhost:8000\\/barangay\\/requests\\/beneficiary\",\"created_at\":\"2025-01-08T02:19:42.731141Z\"}', null, '2025-01-08 02:19:42', '2025-01-08 02:19:42');
INSERT INTO `notifications` VALUES ('93f68648-c433-4290-b2d5-c9b6df55a33c', 'App\\Notifications\\ActivityNotification', 'App\\Models\\User', '14', '{\"type\":2,\"from\":\"Administrator\",\"image\":null,\"message\":\"Cancelled the rejected og the program Pamaskong Hakdog you requested.\",\"url\":\"http:\\/\\/localhost:8000\\/staff\\/program_info\\/7\",\"created_at\":\"2025-01-07T16:58:28.628964Z\"}', null, '2025-01-07 16:58:28', '2025-01-07 16:58:28');
INSERT INTO `notifications` VALUES ('981f4af8-4e38-4888-8977-9f4e62aa5d2a', 'App\\Notifications\\ActivityNotification', 'App\\Models\\User', '22', '{\"type\":0,\"from\":\"staff\",\"image\":null,\"message\":\"Rejected new beneficiary request\",\"url\":\"http:\\/\\/localhost:8000\\/focal\\/requests\\/beneficiary\",\"created_at\":\"2025-01-08T02:03:53.181778Z\"}', null, '2025-01-08 02:03:53', '2025-01-08 02:03:53');
INSERT INTO `notifications` VALUES ('98f73244-66a2-48e2-bd2d-17a997f9bd31', 'App\\Notifications\\ActivityNotification', 'App\\Models\\User', '23', '{\"type\":0,\"from\":\"staff\",\"image\":null,\"message\":\"Verified the beneficiary endorsement request\",\"url\":\"http:\\/\\/localhost:8000\\/barangay\\/requests\\/beneficiary\",\"created_at\":\"2025-01-07T14:54:16.104573Z\"}', null, '2025-01-07 14:54:16', '2025-01-07 14:54:16');
INSERT INTO `notifications` VALUES ('995de966-b0c0-4e16-9165-a20c23338dd5', 'App\\Notifications\\ActivityNotification', 'App\\Models\\User', '23', '{\"type\":0,\"from\":\"staff\",\"image\":null,\"message\":\"Verified the beneficiary endorsement request\",\"url\":\"http:\\/\\/localhost:8000\\/barangay\\/requests\\/beneficiary\",\"created_at\":\"2025-01-07T14:29:43.675541Z\"}', null, '2025-01-07 14:29:43', '2025-01-07 14:29:43');
INSERT INTO `notifications` VALUES ('9e2e2d44-795c-490d-a97c-f94ea70ac7e3', 'App\\Notifications\\ActivityNotification', 'App\\Models\\User', '22', '{\"type\":0,\"from\":\"Administrator\",\"image\":null,\"message\":\"Approved the beneficiary endorsement request\",\"url\":\"http:\\/\\/localhost:8000\\/focal\\/requests\\/beneficiary\",\"created_at\":\"2025-01-08T04:09:19.272566Z\"}', null, '2025-01-08 04:09:19', '2025-01-08 04:09:19');
INSERT INTO `notifications` VALUES ('a30ab853-4fe7-4633-bd3b-ad7cf084d9aa', 'App\\Notifications\\ActivityNotification', 'App\\Models\\User', '13', '{\"type\":0,\"from\":\"staff\",\"image\":null,\"message\":\"Cancelled the Verification for the beneficiary endorsement request\",\"url\":\"http:\\/\\/localhost:8000\\/admin\\/request\\/beneficiaries\",\"created_at\":\"2025-01-07T14:27:10.392445Z\"}', null, '2025-01-07 14:27:10', '2025-01-07 14:27:10');
INSERT INTO `notifications` VALUES ('a3430042-2432-46fa-b93a-9bf0d3d9143f', 'App\\Notifications\\ActivityNotification', 'App\\Models\\User', '22', '{\"type\":0,\"from\":\"Barangay Baluc\",\"image\":\"1731767807-Dw73j.jpg\",\"message\":\"Successfully checked your new beneficiary request\",\"url\":\"http:\\/\\/localhost:8000\\/focal\\/requests\\/beneficiary\",\"created_at\":\"2025-01-07T14:11:48.447306Z\"}', null, '2025-01-07 14:11:48', '2025-01-07 14:11:48');
INSERT INTO `notifications` VALUES ('a7effbc5-04a5-4d29-b6d8-75c502e3c089', 'App\\Notifications\\ActivityNotification', 'App\\Models\\User', '23', '{\"type\":0,\"from\":\"staff\",\"image\":null,\"message\":\"Rejected the new beneficiary request\",\"url\":\"http:\\/\\/localhost:8000\\/barangay\\/requests\\/beneficiary\",\"created_at\":\"2025-01-08T04:08:04.533902Z\"}', null, '2025-01-08 04:08:04', '2025-01-08 04:08:04');
INSERT INTO `notifications` VALUES ('aba1bcf6-f57d-42ee-9b54-6ff3cde80869', 'App\\Notifications\\ActivityNotification', 'App\\Models\\User', '31', '{\"type\":0,\"from\":\"Administrator\",\"image\":null,\"message\":\"Rejected your endorsement request\",\"url\":\"http:\\/\\/localhost:8000\\/beneficiary\\/my-profile\",\"created_at\":\"2025-01-07T15:19:57.293814Z\"}', '2025-01-07 15:22:45', '2025-01-07 15:19:57', '2025-01-07 15:22:45');
INSERT INTO `notifications` VALUES ('acd1aebf-1164-4a2a-abb1-5c2e319d6b56', 'App\\Notifications\\ActivityNotification', 'App\\Models\\User', '31', '{\"type\":0,\"from\":\"Barangay Baluc\",\"image\":\"1731767807-Dw73j.jpg\",\"message\":\"Checked your endorsement\",\"url\":\"http:\\/\\/localhost:8000\\/beneficiary\\/my-profile\",\"created_at\":\"2025-01-07T14:11:51.690257Z\"}', '2025-01-07 14:29:33', '2025-01-07 14:11:51', '2025-01-07 14:29:33');
INSERT INTO `notifications` VALUES ('ad1e519f-785c-4a87-8b06-337a4bf3b8ca', 'App\\Notifications\\ActivityNotification', 'App\\Models\\User', '14', '{\"type\":0,\"from\":\"Barangay Baluc\",\"image\":\"1731767807-Dw73j.jpg\",\"message\":\"Successfully checked your new beneficiary request\",\"url\":\"http:\\/\\/localhost:8000\\/staff\\/requests\\/new-beneficiaries\",\"created_at\":\"2025-01-08T02:03:37.719296Z\"}', null, '2025-01-08 02:03:37', '2025-01-08 02:03:37');
INSERT INTO `notifications` VALUES ('aed1300a-76f4-4ab6-9317-5d34a1b6d9a5', 'App\\Notifications\\ActivityNotification', 'App\\Models\\User', '23', '{\"type\":0,\"from\":\"staff\",\"image\":null,\"message\":\"Cancelled the Verification for the beneficiary endorsement request\",\"url\":\"http:\\/\\/localhost:8000\\/barangay\\/requests\\/beneficiary\",\"created_at\":\"2025-01-07T14:26:43.075190Z\"}', null, '2025-01-07 14:26:43', '2025-01-07 14:26:43');
INSERT INTO `notifications` VALUES ('b02557b4-e746-4acc-bdf5-a1316baf2ee9', 'App\\Notifications\\ActivityNotification', 'App\\Models\\User', '23', '{\"type\":0,\"from\":\"Administrator\",\"image\":null,\"message\":\"Cancel the rejection of beneficiary endorsement request\",\"url\":\"http:\\/\\/localhost:8000\\/barangay\\/requests\\/beneficiary\",\"created_at\":\"2025-01-08T02:43:21.548895Z\"}', null, '2025-01-08 02:43:21', '2025-01-08 02:43:21');
INSERT INTO `notifications` VALUES ('b0875e08-1f0c-45d7-8a2b-178f5f54c254', 'App\\Notifications\\ActivityNotification', 'App\\Models\\User', '22', '{\"type\":0,\"from\":\"staff\",\"image\":null,\"message\":\"Cancelled the Verification for beneficiary endorsement request\",\"url\":\"http:\\/\\/localhost:8000\\/focal\\/requests\\/beneficiary\",\"created_at\":\"2025-01-07T14:29:12.998697Z\"}', null, '2025-01-07 14:29:12', '2025-01-07 14:29:12');
INSERT INTO `notifications` VALUES ('b40a7449-3034-4514-852e-81e62f963683', 'App\\Notifications\\ActivityNotification', 'App\\Models\\User', '14', '{\"type\":0,\"from\":\"Barangay Baluc\",\"image\":\"1731767807-Dw73j.jpg\",\"message\":\"Cancelled checking the new beneficiary request\",\"url\":\"http:\\/\\/localhost:8000\\/staff\\/requests\\/new-beneficiaries\",\"created_at\":\"2025-01-07T08:50:01.268419Z\"}', '2025-01-07 08:52:30', '2025-01-07 08:50:01', '2025-01-07 08:52:30');
INSERT INTO `notifications` VALUES ('b478ce78-c6b3-4111-a55a-902596491694', 'App\\Notifications\\ActivityNotification', 'App\\Models\\User', '13', '{\"type\":0,\"from\":\"staff\",\"image\":null,\"message\":\"Cancelled the Verification for the beneficiary endorsement request\",\"url\":\"http:\\/\\/localhost:8000\\/admin\\/request\\/beneficiaries\",\"created_at\":\"2025-01-07T14:26:43.077134Z\"}', null, '2025-01-07 14:26:43', '2025-01-07 14:26:43');
INSERT INTO `notifications` VALUES ('b4ffc2df-69d8-4aa3-98f3-e0bfab7ababc', 'App\\Notifications\\ActivityNotification', 'App\\Models\\User', '23', '{\"type\":0,\"from\":\"staff\",\"image\":null,\"message\":\"Verified the beneficiary endorsement request\",\"url\":\"http:\\/\\/localhost:8000\\/barangay\\/requests\\/beneficiary\",\"created_at\":\"2025-01-08T04:08:20.680232Z\"}', null, '2025-01-08 04:08:20', '2025-01-08 04:08:20');
INSERT INTO `notifications` VALUES ('b63a672a-93c5-4001-a495-04797996ce24', 'App\\Notifications\\ActivityNotification', 'App\\Models\\User', '13', '{\"type\":0,\"from\":\"staff\",\"image\":null,\"message\":\"Verified the beneficiary endorsement request\",\"url\":\"http:\\/\\/localhost:8000\\/admin\\/request\\/beneficiaries\",\"created_at\":\"2025-01-07T14:26:57.824145Z\"}', null, '2025-01-07 14:26:57', '2025-01-07 14:26:57');
INSERT INTO `notifications` VALUES ('b6495c19-a63e-4e4c-9b3f-ad0cac70f74e', 'App\\Notifications\\ActivityNotification', 'App\\Models\\User', '13', '{\"type\":2,\"from\":\"staff\",\"image\":null,\"message\":\"Created a new pogram.\",\"url\":\"http:\\/\\/localhost:8000\\/admin\\/request\\/programs\",\"created_at\":\"2025-01-07T15:28:18.571811Z\"}', '2025-01-07 15:28:26', '2025-01-07 15:28:18', '2025-01-07 15:28:26');
INSERT INTO `notifications` VALUES ('b97d13f5-17b9-451f-842f-2bbcf4be2362', 'App\\Notifications\\ActivityNotification', 'App\\Models\\User', '31', '{\"type\":0,\"from\":\"staff\",\"image\":null,\"message\":\"Cancelled the Verification of your endorsement request\",\"url\":\"http:\\/\\/localhost:8000\\/beneficiary\\/my-profile\",\"created_at\":\"2025-01-07T14:53:53.100000Z\"}', '2025-01-07 14:54:04', '2025-01-07 14:53:53', '2025-01-07 14:54:04');
INSERT INTO `notifications` VALUES ('b990cac6-02c8-4da0-9149-73f3daa23d73', 'App\\Notifications\\ActivityNotification', 'App\\Models\\User', '22', '{\"type\":0,\"from\":\"Administrator\",\"image\":null,\"message\":\"Cancel the rejection of beneficiary endorsement request\",\"url\":\"http:\\/\\/localhost:8000\\/focal\\/requests\\/beneficiary\",\"created_at\":\"2025-01-08T04:09:16.078210Z\"}', null, '2025-01-08 04:09:16', '2025-01-08 04:09:16');
INSERT INTO `notifications` VALUES ('bd4cd1c0-b815-458c-8df4-9c55058a26a6', 'App\\Notifications\\ActivityNotification', 'App\\Models\\User', '22', '{\"type\":0,\"from\":\"Administrator\",\"image\":null,\"message\":\"Cancel the rejection of beneficiary endorsement request\",\"url\":\"http:\\/\\/localhost:8000\\/focal\\/requests\\/beneficiary\",\"created_at\":\"2025-01-08T02:43:21.543421Z\"}', null, '2025-01-08 02:43:21', '2025-01-08 02:43:21');
INSERT INTO `notifications` VALUES ('bf2da3f1-6cda-4537-89f8-481e3b458711', 'App\\Notifications\\ActivityNotification', 'App\\Models\\User', '22', '{\"type\":0,\"from\":\"staff\",\"image\":null,\"message\":\"Verified the beneficiary endorsement request\",\"url\":\"http:\\/\\/localhost:8000\\/focal\\/requests\\/beneficiary\",\"created_at\":\"2025-01-08T02:20:08.840451Z\"}', null, '2025-01-08 02:20:08', '2025-01-08 02:20:08');
INSERT INTO `notifications` VALUES ('bf661b3d-8024-4d61-8011-055b4602b405', 'App\\Notifications\\ActivityNotification', 'App\\Models\\User', '23', '{\"type\":0,\"from\":\"Administrator\",\"image\":null,\"message\":\"Cancel the rejection of beneficiary endorsement request\",\"url\":\"http:\\/\\/localhost:8000\\/barangay\\/requests\\/beneficiary\",\"created_at\":\"2025-01-08T04:09:16.080304Z\"}', null, '2025-01-08 04:09:16', '2025-01-08 04:09:16');
INSERT INTO `notifications` VALUES ('c069b6af-8b8b-4b16-b608-c035d58ab526', 'App\\Notifications\\ActivityNotification', 'App\\Models\\User', '22', '{\"type\":0,\"from\":\"Barangay Baluc\",\"image\":\"1731767807-Dw73j.jpg\",\"message\":\"Cancelled checking the new beneficiary request\",\"url\":\"http:\\/\\/localhost:8000\\/focal\\/requests\\/beneficiary\",\"created_at\":\"2025-01-08T04:07:07.044002Z\"}', null, '2025-01-08 04:07:07', '2025-01-08 04:07:07');
INSERT INTO `notifications` VALUES ('c8d77167-283f-4151-a130-3372e23958aa', 'App\\Notifications\\ActivityNotification', 'App\\Models\\User', '31', '{\"type\":0,\"from\":\"Barangay Baluc\",\"image\":\"1731767807-Dw73j.jpg\",\"message\":\"Undo the response to your endorsement\",\"url\":\"http:\\/\\/localhost:8000\\/beneficiary\\/my-profile\",\"created_at\":\"2025-01-07T08:47:38.044677Z\"}', '2025-01-07 08:49:49', '2025-01-07 08:47:38', '2025-01-07 08:49:49');
INSERT INTO `notifications` VALUES ('c9cb8fc0-34fe-4ba2-8f32-fe25fca40f9b', 'App\\Notifications\\ActivityNotification', 'App\\Models\\User', '14', '{\"type\":2,\"from\":\"Administrator\",\"image\":null,\"message\":\"Reviewed the program Pamaskong Hakdog you requested.\",\"url\":\"http:\\/\\/localhost:8000\\/staff\\/program_info\\/7\",\"created_at\":\"2025-01-07T15:46:40.219929Z\"}', null, '2025-01-07 15:46:40', '2025-01-07 15:46:40');
INSERT INTO `notifications` VALUES ('ca8fecf1-3450-4173-9c91-b7f007b2453f', 'App\\Notifications\\ActivityNotification', 'App\\Models\\User', '22', '{\"type\":0,\"from\":\"staff\",\"image\":null,\"message\":\"Rejected new beneficiary request\",\"url\":\"http:\\/\\/localhost:8000\\/focal\\/requests\\/beneficiary\",\"created_at\":\"2025-01-08T02:19:42.726034Z\"}', null, '2025-01-08 02:19:42', '2025-01-08 02:19:42');
INSERT INTO `notifications` VALUES ('cb6e14c8-680c-4445-a3a8-99fccef6a5d9', 'App\\Notifications\\ActivityNotification', 'App\\Models\\User', '23', '{\"type\":0,\"from\":\"Focal Women\",\"image\":null,\"message\":\"Cancelled the endorsement request\",\"url\":\"http:\\/\\/localhost:8000\\/barangay\\/requests\\/beneficiary\",\"created_at\":\"2025-01-07T17:14:25.247801Z\"}', null, '2025-01-07 17:14:25', '2025-01-07 17:14:25');
INSERT INTO `notifications` VALUES ('cd2afc4f-763d-42f5-be20-4a7e37eac803', 'App\\Notifications\\ActivityNotification', 'App\\Models\\User', '14', '{\"type\":0,\"from\":\"Barangay Baluc\",\"image\":\"1731767807-Dw73j.jpg\",\"message\":\"Cancelled checking the new beneficiary request\",\"url\":\"http:\\/\\/localhost:8000\\/staff\\/requests\\/new-beneficiaries\",\"created_at\":\"2025-01-07T08:47:38.037317Z\"}', '2025-01-07 08:52:24', '2025-01-07 08:47:38', '2025-01-07 08:52:24');
INSERT INTO `notifications` VALUES ('cd40633f-e4a5-473c-a8ce-dc5aae68ec80', 'App\\Notifications\\ActivityNotification', 'App\\Models\\User', '14', '{\"type\":2,\"from\":\"Administrator\",\"image\":null,\"message\":\"Rejected the program Pamaskong Hakdog you requested.\",\"url\":\"http:\\/\\/localhost:8000\\/staff\\/program_info\\/7\",\"created_at\":\"2025-01-07T16:57:06.806614Z\"}', null, '2025-01-07 16:57:06', '2025-01-07 16:57:06');
INSERT INTO `notifications` VALUES ('cdd4fc85-87c8-4903-a8cd-86d3c6de340f', 'App\\Notifications\\ActivityNotification', 'App\\Models\\User', '31', '{\"type\":0,\"from\":\"staff\",\"image\":null,\"message\":\"Cancelled the Rejection of your endorsement request\",\"url\":\"http:\\/\\/localhost:8000\\/beneficiary\\/my-profile\",\"created_at\":\"2025-01-08T04:08:17.861988Z\"}', null, '2025-01-08 04:08:17', '2025-01-08 04:08:17');
INSERT INTO `notifications` VALUES ('d3abaa68-2eca-473b-8193-d1e329237a21', 'App\\Notifications\\ActivityNotification', 'App\\Models\\User', '22', '{\"type\":0,\"from\":\"Barangay Baluc\",\"image\":\"1731767807-Dw73j.jpg\",\"message\":\"Rejected your new beneficiary request\",\"url\":\"http:\\/\\/localhost:8000\\/focal\\/requests\\/beneficiary\",\"created_at\":\"2025-01-07T08:48:05.835782Z\"}', '2025-01-07 13:44:35', '2025-01-07 08:48:05', '2025-01-07 13:44:35');
INSERT INTO `notifications` VALUES ('d44b6357-1b62-40fb-a459-684192650f35', 'App\\Notifications\\ActivityNotification', 'App\\Models\\User', '22', '{\"type\":0,\"from\":\"staff\",\"image\":null,\"message\":\"Cancelled the Verification for beneficiary endorsement request\",\"url\":\"http:\\/\\/localhost:8000\\/focal\\/requests\\/beneficiary\",\"created_at\":\"2025-01-08T02:14:38.405761Z\"}', null, '2025-01-08 02:14:38', '2025-01-08 02:14:38');
INSERT INTO `notifications` VALUES ('d4c8fd07-377e-45ff-9923-9ed5065e871e', 'App\\Notifications\\ActivityNotification', 'App\\Models\\User', '23', '{\"type\":0,\"from\":\"staff\",\"image\":null,\"message\":\"Cancelled the Rejection for the beneficiary endorsement request\",\"url\":\"http:\\/\\/localhost:8000\\/barangay\\/requests\\/beneficiary\",\"created_at\":\"2025-01-08T02:19:57.488429Z\"}', null, '2025-01-08 02:19:57', '2025-01-08 02:19:57');
INSERT INTO `notifications` VALUES ('d5c81ee4-fe85-44e2-be96-7a88ea0a9944', 'App\\Notifications\\ActivityNotification', 'App\\Models\\User', '31', '{\"type\":0,\"from\":\"Administrator\",\"image\":null,\"message\":\"Cancel the rejection of your endorsement request\",\"url\":\"http:\\/\\/localhost:8000\\/beneficiary\\/my-profile\",\"created_at\":\"2025-01-08T04:09:16.075656Z\"}', null, '2025-01-08 04:09:16', '2025-01-08 04:09:16');
INSERT INTO `notifications` VALUES ('d704520c-6bee-47ab-9be2-b376db1d8ef4', 'App\\Notifications\\ActivityNotification', 'App\\Models\\User', '22', '{\"type\":0,\"from\":\"Barangay Baluc\",\"image\":\"1731767807-Dw73j.jpg\",\"message\":\"Successfully checked your new beneficiary request\",\"url\":\"http:\\/\\/localhost:8000\\/focal\\/requests\\/beneficiary\",\"created_at\":\"2025-01-08T02:03:40.651423Z\"}', null, '2025-01-08 02:03:40', '2025-01-08 02:03:40');
INSERT INTO `notifications` VALUES ('dc4f1e5a-d3ce-4935-ba44-5148c3d83990', 'App\\Notifications\\ActivityNotification', 'App\\Models\\User', '22', '{\"type\":0,\"from\":\"Barangay Baluc\",\"image\":\"1731767807-Dw73j.jpg\",\"message\":\"Cancelled checking the new beneficiary request\",\"url\":\"http:\\/\\/localhost:8000\\/focal\\/requests\\/beneficiary\",\"created_at\":\"2025-01-07T08:47:38.042799Z\"}', '2025-01-07 13:44:37', '2025-01-07 08:47:38', '2025-01-07 13:44:37');
INSERT INTO `notifications` VALUES ('e042399b-3210-4ee4-8fee-4b05af3a1aac', 'App\\Notifications\\ActivityNotification', 'App\\Models\\User', '13', '{\"type\":0,\"from\":\"staff\",\"image\":null,\"message\":\"Verified the beneficiary endorsement request\",\"url\":\"http:\\/\\/localhost:8000\\/admin\\/request\\/beneficiaries\",\"created_at\":\"2025-01-08T04:08:20.682352Z\"}', '2025-01-08 04:08:54', '2025-01-08 04:08:20', '2025-01-08 04:08:54');
INSERT INTO `notifications` VALUES ('e05ca73d-d4cb-4b42-b404-c8231c3a5fbe', 'App\\Notifications\\ActivityNotification', 'App\\Models\\User', '22', '{\"type\":0,\"from\":\"Barangay Baluc\",\"image\":\"1731767807-Dw73j.jpg\",\"message\":\"Cancelled checking the new beneficiary request\",\"url\":\"http:\\/\\/localhost:8000\\/focal\\/requests\\/beneficiary\",\"created_at\":\"2025-01-07T08:50:01.273268Z\"}', '2025-01-07 13:44:33', '2025-01-07 08:50:01', '2025-01-07 13:44:33');
INSERT INTO `notifications` VALUES ('e0c935dd-7bf9-4e34-a92d-34ed6c172f3b', 'App\\Notifications\\ActivityNotification', 'App\\Models\\User', '31', '{\"type\":0,\"from\":\"Administrator\",\"image\":null,\"message\":\"Rejected your endorsement request\",\"url\":\"http:\\/\\/localhost:8000\\/beneficiary\\/my-profile\",\"created_at\":\"2025-01-08T04:09:02.025703Z\"}', null, '2025-01-08 04:09:02', '2025-01-08 04:09:02');
INSERT INTO `notifications` VALUES ('e271f54b-a4ec-4e76-9ebe-f864b813b215', 'App\\Notifications\\ActivityNotification', 'App\\Models\\User', '14', '{\"type\":2,\"from\":\"Administrator\",\"image\":null,\"message\":\"Reviewed the program Pamaskong Hakdog you requested.\",\"url\":\"http:\\/\\/localhost:8000\\/staff\\/program_info\\/7\",\"created_at\":\"2025-01-07T16:53:21.521833Z\"}', null, '2025-01-07 16:53:21', '2025-01-07 16:53:21');
INSERT INTO `notifications` VALUES ('e6d1ada1-5646-4576-9bf0-490c1595dd2f', 'App\\Notifications\\ActivityNotification', 'App\\Models\\User', '13', '{\"type\":2,\"from\":\"staff\",\"image\":null,\"message\":\"Deleted the pogram Pamaskong Hakdog for a reason Bahog Tiil\",\"url\":\"http:\\/\\/localhost:8000\\/admin\\/request\\/programs\",\"created_at\":\"2025-01-07T15:42:30.272519Z\"}', '2025-01-07 15:42:39', '2025-01-07 15:42:30', '2025-01-07 15:42:39');
INSERT INTO `notifications` VALUES ('e72149c4-cda9-42a3-9a8e-28dcfa5e75cc', 'App\\Notifications\\ActivityNotification', 'App\\Models\\User', '13', '{\"type\":0,\"from\":\"staff\",\"image\":null,\"message\":\"Cancelled the Verification for the beneficiary endorsement request\",\"url\":\"http:\\/\\/localhost:8000\\/admin\\/request\\/beneficiaries\",\"created_at\":\"2025-01-07T14:29:13.005186Z\"}', null, '2025-01-07 14:29:13', '2025-01-07 14:29:13');
INSERT INTO `notifications` VALUES ('e73e6ed0-60f8-4b68-9d05-1abc929f986e', 'App\\Notifications\\ActivityNotification', 'App\\Models\\User', '22', '{\"type\":0,\"from\":\"staff\",\"image\":null,\"message\":\"Verified the beneficiary endorsement request\",\"url\":\"http:\\/\\/localhost:8000\\/focal\\/requests\\/beneficiary\",\"created_at\":\"2025-01-07T14:54:12.336098Z\"}', null, '2025-01-07 14:54:12', '2025-01-07 14:54:12');
INSERT INTO `notifications` VALUES ('e768abb8-12c7-4260-b668-a7494561659d', 'App\\Notifications\\ActivityNotification', 'App\\Models\\User', '23', '{\"type\":0,\"from\":\"staff\",\"image\":null,\"message\":\"Rejected the new beneficiary request\",\"url\":\"http:\\/\\/localhost:8000\\/barangay\\/requests\\/beneficiary\",\"created_at\":\"2025-01-07T14:28:21.938288Z\"}', null, '2025-01-07 14:28:21', '2025-01-07 14:28:21');
INSERT INTO `notifications` VALUES ('eabd667a-a8e9-46f2-998c-56c0ea6f1b24', 'App\\Notifications\\ActivityNotification', 'App\\Models\\User', '23', '{\"type\":0,\"from\":\"staff\",\"image\":null,\"message\":\"Verified the beneficiary endorsement request\",\"url\":\"http:\\/\\/localhost:8000\\/barangay\\/requests\\/beneficiary\",\"created_at\":\"2025-01-08T02:20:08.845384Z\"}', null, '2025-01-08 02:20:08', '2025-01-08 02:20:08');
INSERT INTO `notifications` VALUES ('eb999b81-ac69-4e13-a9bc-7c7087084073', 'App\\Notifications\\ActivityNotification', 'App\\Models\\User', '23', '{\"type\":0,\"from\":\"Focal Women\",\"image\":null,\"message\":\"Cancelled the endorsement request\",\"url\":\"http:\\/\\/localhost:8000\\/barangay\\/requests\\/beneficiary\",\"created_at\":\"2025-01-07T17:14:14.452951Z\"}', null, '2025-01-07 17:14:14', '2025-01-07 17:14:14');
INSERT INTO `notifications` VALUES ('eef3bad6-976e-4242-8f78-cdf8d9143c34', 'App\\Notifications\\ActivityNotification', 'App\\Models\\User', '22', '{\"type\":0,\"from\":\"Barangay Baluc\",\"image\":\"1731767807-Dw73j.jpg\",\"message\":\"Successfully checked your new beneficiary request\",\"url\":\"http:\\/\\/localhost:8000\\/focal\\/requests\\/beneficiary\",\"created_at\":\"2025-01-07T08:44:03.876073Z\"}', '2025-01-07 13:44:40', '2025-01-07 08:44:03', '2025-01-07 13:44:40');
INSERT INTO `notifications` VALUES ('f0df1456-0a6d-44f7-a655-08191e66ed34', 'App\\Notifications\\ActivityNotification', 'App\\Models\\User', '23', '{\"type\":0,\"from\":\"staff\",\"image\":null,\"message\":\"Cancelled the Verification for the beneficiary endorsement request\",\"url\":\"http:\\/\\/localhost:8000\\/barangay\\/requests\\/beneficiary\",\"created_at\":\"2025-01-07T14:27:10.390004Z\"}', null, '2025-01-07 14:27:10', '2025-01-07 14:27:10');
INSERT INTO `notifications` VALUES ('f10caeec-bdcf-4279-88d9-aa6b0af884d5', 'App\\Notifications\\ActivityNotification', 'App\\Models\\User', '31', '{\"type\":0,\"from\":\"staff\",\"image\":null,\"message\":\"Rejected your endorsement request\",\"url\":\"http:\\/\\/localhost:8000\\/beneficiary\\/my-profile\",\"created_at\":\"2025-01-08T04:08:04.525987Z\"}', null, '2025-01-08 04:08:04', '2025-01-08 04:08:04');
INSERT INTO `notifications` VALUES ('f4466acc-8eb8-415d-9039-48148f2d3416', 'App\\Notifications\\ActivityNotification', 'App\\Models\\User', '23', '{\"type\":0,\"from\":\"Administrator\",\"image\":null,\"message\":\"Approved the beneficiary endorsement request\",\"url\":\"http:\\/\\/localhost:8000\\/barangay\\/requests\\/beneficiary\",\"created_at\":\"2025-01-08T02:43:31.756324Z\"}', null, '2025-01-08 02:43:31', '2025-01-08 02:43:31');
INSERT INTO `notifications` VALUES ('f685e574-439e-44e4-9331-d64e4fce2756', 'App\\Notifications\\ActivityNotification', 'App\\Models\\User', '13', '{\"type\":0,\"from\":\"staff\",\"image\":null,\"message\":\"Cancelled the Verification for the beneficiary endorsement request\",\"url\":\"http:\\/\\/localhost:8000\\/admin\\/request\\/beneficiaries\",\"created_at\":\"2025-01-07T14:53:53.111912Z\"}', null, '2025-01-07 14:53:53', '2025-01-07 14:53:53');
INSERT INTO `notifications` VALUES ('f6c38214-31e0-4f93-98a4-d20a72f28d11', 'App\\Notifications\\ActivityNotification', 'App\\Models\\User', '22', '{\"type\":0,\"from\":\"Administrator\",\"image\":null,\"message\":\"Approved the beneficiary endorsement request\",\"url\":\"http:\\/\\/localhost:8000\\/focal\\/requests\\/beneficiary\",\"created_at\":\"2025-01-07T15:20:45.018092Z\"}', '2025-01-07 15:22:33', '2025-01-07 15:20:45', '2025-01-07 15:22:33');
INSERT INTO `notifications` VALUES ('fb5e6140-3012-4d13-97cf-d5d410ff6f98', 'App\\Notifications\\ActivityNotification', 'App\\Models\\User', '23', '{\"type\":0,\"from\":\"Focal Women\",\"image\":null,\"message\":\"Submitted a new beneficiary request\",\"url\":\"http:\\/\\/localhost:8000\\/barangay\\/requests\\/beneficiary\",\"created_at\":\"2025-01-08T02:47:35.396293Z\"}', '2025-01-08 02:48:02', '2025-01-08 02:47:35', '2025-01-08 02:48:02');
INSERT INTO `notifications` VALUES ('fb858d3c-5e6b-4018-8bb5-5fbbd5b94a97', 'App\\Notifications\\ActivityNotification', 'App\\Models\\User', '22', '{\"type\":0,\"from\":\"Barangay Baluc\",\"image\":\"1731767807-Dw73j.jpg\",\"message\":\"Successfully checked your new beneficiary request\",\"url\":\"http:\\/\\/localhost:8000\\/focal\\/requests\\/beneficiary\",\"created_at\":\"2025-01-08T02:03:37.725013Z\"}', null, '2025-01-08 02:03:37', '2025-01-08 02:03:37');

-- ----------------------------
-- Table structure for password_reset_tokens
-- ----------------------------
DROP TABLE IF EXISTS `password_reset_tokens`;
CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of password_reset_tokens
-- ----------------------------

-- ----------------------------
-- Table structure for programs
-- ----------------------------
DROP TABLE IF EXISTS `programs`;
CREATE TABLE `programs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `purpose` varchar(255) DEFAULT NULL,
  `beneficiaries` varchar(255) DEFAULT NULL,
  `status` tinyint(4) DEFAULT 0,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `venue` varchar(255) DEFAULT NULL,
  `reason` text DEFAULT NULL,
  `date_rejected` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of programs
-- ----------------------------
INSERT INTO `programs` VALUES ('1', 'School Supply Distrbution', 'To aid student\'s problem in acquiring school supplies', null, '3', '2024-12-12', '2024-12-12', '2024-11-27 14:30:45', '2024-11-29 04:19:51', 'Barangay Garang', null, null);
INSERT INTO `programs` VALUES ('2', 'Food Distribution', 'To Distribute food', null, '3', '2024-12-01', '2024-12-12', '2024-11-29 06:04:28', '2024-11-29 06:07:27', 'City Gymnasium', null, null);
INSERT INTO `programs` VALUES ('4', 'Financial Distribution', 'To aid cash problems to low income earner', null, '3', '2024-12-04', '2024-12-05', '2024-12-03 14:21:33', '2024-12-10 03:27:16', 'City Gym', null, null);
INSERT INTO `programs` VALUES ('5', 'Pamaskong Handog', 'To distribute christmas goods', null, '3', '2024-12-12', '2024-12-18', '2024-12-10 11:20:37', '2024-12-10 11:31:19', 'City Gymnasium', null, null);
INSERT INTO `programs` VALUES ('7', 'Pamaskong Hakdog', 'Gift', null, '1', '2025-01-08', '2025-01-10', '2025-01-07 15:43:07', '2025-01-07 17:00:00', 'TCGC Court', null, null);

-- ----------------------------
-- Table structure for program_beneficiaries
-- ----------------------------
DROP TABLE IF EXISTS `program_beneficiaries`;
CREATE TABLE `program_beneficiaries` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `program_id` bigint(20) unsigned NOT NULL,
  `benefeciary_id` bigint(20) unsigned NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=46 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of program_beneficiaries
-- ----------------------------
INSERT INTO `program_beneficiaries` VALUES ('10', '1', '12', '0', '2024-11-29 00:44:14', '2024-11-29 00:44:14');
INSERT INTO `program_beneficiaries` VALUES ('11', '1', '17', '0', '2024-11-29 00:44:14', '2024-11-29 00:44:14');
INSERT INTO `program_beneficiaries` VALUES ('12', '1', '18', '0', '2024-11-29 00:44:14', '2024-11-29 00:44:14');
INSERT INTO `program_beneficiaries` VALUES ('13', '1', '20', '0', '2024-11-29 00:44:14', '2024-11-29 00:44:14');
INSERT INTO `program_beneficiaries` VALUES ('14', '2', '12', '0', '2024-11-29 06:07:22', '2024-11-29 06:07:22');
INSERT INTO `program_beneficiaries` VALUES ('15', '2', '17', '0', '2024-11-29 06:07:22', '2024-11-29 06:07:22');
INSERT INTO `program_beneficiaries` VALUES ('16', '4', '12', '0', '2024-12-04 02:56:27', '2024-12-04 02:56:27');
INSERT INTO `program_beneficiaries` VALUES ('17', '4', '17', '0', '2024-12-04 02:56:27', '2024-12-04 02:56:27');
INSERT INTO `program_beneficiaries` VALUES ('18', '4', '18', '0', '2024-12-04 06:17:11', '2024-12-04 06:17:11');
INSERT INTO `program_beneficiaries` VALUES ('19', '4', '20', '0', '2024-12-04 06:17:11', '2024-12-04 06:17:11');
INSERT INTO `program_beneficiaries` VALUES ('36', '5', '12', '0', '2024-12-10 11:30:41', '2024-12-10 11:30:41');
INSERT INTO `program_beneficiaries` VALUES ('37', '5', '17', '0', '2024-12-10 11:30:41', '2024-12-10 11:30:41');
INSERT INTO `program_beneficiaries` VALUES ('38', '5', '18', '0', '2024-12-10 11:30:41', '2024-12-10 11:30:41');
INSERT INTO `program_beneficiaries` VALUES ('39', '5', '20', '0', '2024-12-10 11:30:42', '2024-12-10 11:30:42');
INSERT INTO `program_beneficiaries` VALUES ('40', '5', '21', '0', '2024-12-10 11:30:42', '2024-12-10 11:30:42');
INSERT INTO `program_beneficiaries` VALUES ('41', '5', '22', '0', '2024-12-10 11:30:42', '2024-12-10 11:30:42');
INSERT INTO `program_beneficiaries` VALUES ('42', '5', '23', '0', '2024-12-10 11:30:42', '2024-12-10 11:30:42');
INSERT INTO `program_beneficiaries` VALUES ('43', '5', '24', '0', '2024-12-10 11:30:42', '2024-12-10 11:30:42');
INSERT INTO `program_beneficiaries` VALUES ('44', '5', '26', '0', null, null);
INSERT INTO `program_beneficiaries` VALUES ('45', '4', '26', '0', null, null);

-- ----------------------------
-- Table structure for pwds
-- ----------------------------
DROP TABLE IF EXISTS `pwds`;
CREATE TABLE `pwds` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `benefeciary_id` bigint(20) unsigned NOT NULL,
  `pwd_id_number` varchar(255) DEFAULT '',
  `date_pic_issued` date DEFAULT NULL,
  `pic_status` tinyint(4) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of pwds
-- ----------------------------

-- ----------------------------
-- Table structure for requests
-- ----------------------------
DROP TABLE IF EXISTS `requests`;
CREATE TABLE `requests` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `benefeciary_id` bigint(255) NOT NULL,
  `status` tinyint(4) DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `user_id` bigint(20) NOT NULL,
  `date_checked` date DEFAULT NULL,
  `date_verified` date DEFAULT NULL,
  `date_approved` date DEFAULT NULL,
  `barangay_id` bigint(20) NOT NULL,
  `date_rejected` date DEFAULT NULL,
  `rejected_by` varchar(255) DEFAULT '',
  `reason` text DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of requests
-- ----------------------------
INSERT INTO `requests` VALUES ('12', '17', '3', '2025-01-06 15:02:16', '2025-01-08 02:43:29', '22', '2025-01-08', '2025-01-08', '2025-01-08', '3', null, null, null);
INSERT INTO `requests` VALUES ('15', '24', '3', '2025-01-07 08:14:34', '2025-01-08 02:43:31', '22', '2025-01-08', '2025-01-08', '2025-01-08', '3', '2025-01-08', null, null);
INSERT INTO `requests` VALUES ('19', '26', '3', '2025-01-08 04:01:18', '2025-01-08 04:09:19', '22', '2025-01-08', '2025-01-08', '2025-01-08', '3', '2025-01-08', null, null);

-- ----------------------------
-- Table structure for seniors
-- ----------------------------
DROP TABLE IF EXISTS `seniors`;
CREATE TABLE `seniors` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `benefeciary_id` bigint(20) unsigned NOT NULL,
  `senior_id_number` varchar(255) DEFAULT NULL,
  `date_sic_issued` date DEFAULT NULL,
  `socpen_benefeciary` tinyint(4) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `sic_status` tinyint(4) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of seniors
-- ----------------------------
INSERT INTO `seniors` VALUES ('12', '12', '9512512515212', '2024-11-23', '1', '2024-09-26 14:00:48', '2024-11-18 00:41:17', '1');
INSERT INTO `seniors` VALUES ('13', '21', '5125616742312', '2024-12-03', '0', '2024-12-06 07:18:47', '2024-12-06 07:18:47', '0');
INSERT INTO `seniors` VALUES ('14', '22', '6543782729278', '2024-12-06', '0', '2024-12-06 07:20:26', '2024-12-06 07:20:26', '0');
INSERT INTO `seniors` VALUES ('15', '23', '5125616742123', '2024-12-03', '0', '2024-12-06 07:31:31', '2024-12-06 07:31:31', '0');

-- ----------------------------
-- Table structure for sessions
-- ----------------------------
DROP TABLE IF EXISTS `sessions`;
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

-- ----------------------------
-- Records of sessions
-- ----------------------------
INSERT INTO `sessions` VALUES ('E5qCmsOxh4JLzMSCpEOtUkMYIjQMfUhyXP2iWzh1', '22', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/122.0.0.0 Safari/537.36', 'YTo1OntzOjY6Il90b2tlbiI7czo0MDoiQU4zYXBuQTNaM3hQTzhpM3lmZ3NubThCZXNXTnpscHlPaTk4NWsyUyI7czozOiJ1cmwiO2E6MDp7fXM6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjI3OiJodHRwOi8vbG9jYWxob3N0OjgwMDAvbG9naW4iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX1zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aToyMjt9', '1765258769');
INSERT INTO `sessions` VALUES ('iW4PuBOxQdwlRsZye4z4iswvC8lTlnpjukbfGZVk', '22', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/122.0.0.0 Safari/537.36', 'YTo1OntzOjY6Il90b2tlbiI7czo0MDoiMVRvNjhkTmZ6MlMwZ2pjZTZ2d3Jrd1ZLdVNrT01tYWhJRkdNYk9lNSI7czozOiJ1cmwiO2E6MDp7fXM6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjI3OiJodHRwOi8vbG9jYWxob3N0OjgwMDAvbG9naW4iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX1zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aToyMjt9', '1763715320');
INSERT INTO `sessions` VALUES ('mD8Sjv1iVTcBDsVx9GTvzpjLF6Cxg70uy2zks2Po', null, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/122.0.0.0 Safari/537.36', 'YToyOntzOjY6Il90b2tlbiI7czo0MDoicDNLZ3R2dkNqT25yY1NZNGJHMFBNM0dITk5kWUN6amt1RWRGbDJnTCI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', '1736309423');
INSERT INTO `sessions` VALUES ('rgwlvGxrrllVlDv611jS0c7eFrwQ7oxAv73fquA3', null, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/122.0.0.0 Safari/537.36', 'YToyOntzOjY6Il90b2tlbiI7czo0MDoiN2lRbkV5b3pUSW13YlF6aVl0YWcyZThDaHVRMjdwajRSUU5UNXpNQyI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', '1736304674');
INSERT INTO `sessions` VALUES ('wLlj9Isgi7ijtsLUdwp0PZIqP3UT0n1mjLxdps8T', null, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/122.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiOGhNUmJEeHFCTzI4cDNibGVLZ3ZKVWRTZnp4eVJQaUpVaVhPekwzdyI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC9sb2dpbiI7fX0=', '1736309386');

-- ----------------------------
-- Table structure for transition_data
-- ----------------------------
DROP TABLE IF EXISTS `transition_data`;
CREATE TABLE `transition_data` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `benefeciary_id` bigint(20) unsigned NOT NULL,
  `from_benefeciary_type` tinyint(4) NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT 0,
  `date_approved` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `barangay_id` bigint(20) NOT NULL,
  `user_id` bigint(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=48 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of transition_data
-- ----------------------------
INSERT INTO `transition_data` VALUES ('47', '18', '3', '2', '2024-12-10', '2024-12-10 10:42:19', '2024-12-10 10:48:41', '3', '22');

-- ----------------------------
-- Table structure for users
-- ----------------------------
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `barangay_id` bigint(20) unsigned NOT NULL,
  `name` varchar(255) NOT NULL,
  `username` varchar(255) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `role` tinyint(4) NOT NULL,
  `focal_role` tinyint(4) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `user_image` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=32 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of users
-- ----------------------------
INSERT INTO `users` VALUES ('13', '1', 'Administrator', 'admin', null, null, '$2y$12$PdLMYWwkpSLtdWwmky1feOTkiGYRQ4OUiggGy/vrurhEyIbIOeH3m', '3', '0', 'R18gNGyzHyInZ9sRrbIqtyLer5IcWv7Lkba4VEhCUD3MmEt1TXxRkqcwzBKR', null, '2024-11-16 14:04:23', null);
INSERT INTO `users` VALUES ('14', '3', 'staff', 'staff', null, null, '$2y$12$21giBG5NpsMXYZaob5SA5.KFq3VQKb17AhQ8NtRQRfrPdiYw3SZJa', '2', '0', null, null, '2024-12-06 08:03:20', null);
INSERT INTO `users` VALUES ('15', '3', 'Focal Senior', 'focal_senior', null, null, '$2y$12$pWGltSzgBTtXCVxJTdfuMOaQOEJMnVR8uztVtPrRuOZ/8WpBnkdx6', '0', '1', null, null, '2024-11-25 13:25:22', '1732266619-2pDwh.jpg');
INSERT INTO `users` VALUES ('17', '3', 'Focal PWD', 'focal_pwd', null, null, '$2y$12$4O5Yyjy7cuRfS44ssZa/3Oa7odxVDAZNygyfXadNITf3dJaxfzCL6', '0', '2', null, null, '2024-11-22 10:10:46', null);
INSERT INTO `users` VALUES ('22', '3', 'Focal Women', 'focal_women', null, null, '$2y$12$oATYxS1Xe.yhLi0n1uT78.LyyrYQsQHmZBT829VoOBEfjwqJOh0w2', '0', '3', null, '2024-11-16 10:50:22', '2024-11-17 11:56:31', null);
INSERT INTO `users` VALUES ('23', '3', 'Barangay Baluc', 'barangay_baluc', null, null, '$2y$12$6A3qvMngGSyPyOtK4FKciuU7jHifJ8QbVq5mcg61vI.8B1By6DfA.', '1', '0', null, '2024-11-16 14:21:45', '2024-11-22 10:11:18', '1731767807-Dw73j.jpg');
INSERT INTO `users` VALUES ('25', '18', 'Angelie', 'angelie', null, null, '$2y$12$UENMBkWGjhTAmnD.Q5mV/OWlRBMPLk4z8aY7B1Fg43FApdZPjLeBu', '0', '3', null, '2024-11-27 09:14:03', '2024-12-06 07:51:25', '');
INSERT INTO `users` VALUES ('26', '18', 'Kate Ventero', 'kate123', null, null, '$2y$12$DfT1gYdl/oSLcyn2RI5u6e0KJ53F5G6I0Yvtb6JrchvTSGak48iN6', '1', '0', null, '2024-11-27 09:15:32', '2024-11-27 09:15:32', '');
INSERT INTO `users` VALUES ('27', '18', 'Rex', 'rex12345', null, null, '$2y$12$uEOp85Coibk48OE0Cf0fdOlvx25MytoCIjQOY0advAgNBs7K3SJ1W', '0', '1', null, '2024-11-27 09:37:42', '2024-11-27 09:38:38', '');
INSERT INTO `users` VALUES ('28', '6', 'Clifford', 'clifford1234', null, null, '$2y$12$R6EehfsyRZMwI5jD8NW2TuFJfFdC7Dtj1iEcskP1m7C66Ym8aI5Gm', '1', '0', null, '2024-12-06 07:12:06', '2024-12-06 07:12:06', '1733469126-KjhLI.jpg');
INSERT INTO `users` VALUES ('29', '6', 'Toper', 'toper12345', null, null, '$2y$12$bSZJ.kdd6aLVQhN273hUauz.PXnEQ8EPBBN.uVN63EwuLDqyyLqnm', '0', '1', null, '2024-12-06 07:13:19', '2024-12-06 07:13:19', '');
INSERT INTO `users` VALUES ('30', '49', 'angeline', 'angie12345', null, null, '$2y$12$lGUO9WrmbBHa3ku90gl4OenUP6Pfe6EKwhQxntY/QdJBF1TBkC4xi', '1', '0', null, '2024-12-06 07:43:47', '2024-12-06 07:45:13', '');
INSERT INTO `users` VALUES ('31', '3', 'Choy Yan', 'choyan123', null, null, '$2y$12$x3w9V4NX20NRvFr4PyHfq.74I/A.cO85KVi4eGQebtLJW7duImoDa', '4', '0', null, '2025-01-05 11:29:41', '2025-01-05 11:29:41', null);

-- ----------------------------
-- Table structure for womens
-- ----------------------------
DROP TABLE IF EXISTS `womens`;
CREATE TABLE `womens` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `benefeciary_id` bigint(20) unsigned NOT NULL,
  `kalipi_id_number` varchar(255) DEFAULT NULL,
  `date_kic_issued` date DEFAULT NULL,
  `kic_status` tinyint(4) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of womens
-- ----------------------------
INSERT INTO `womens` VALUES ('3', '15', '512631225321', '2007-12-05', '1', '2024-11-18 05:16:35', '2024-11-18 05:16:35');
INSERT INTO `womens` VALUES ('5', '17', '512631241253', '2024-11-06', '1', '2024-11-18 05:21:17', '2024-12-18 03:42:52');
INSERT INTO `womens` VALUES ('6', '18', '512631241253', '2024-11-27', '1', '2024-11-18 05:47:08', '2024-12-10 10:20:59');
INSERT INTO `womens` VALUES ('7', '20', '512631242412', '2024-09-24', '1', '2024-11-27 09:22:48', '2024-11-27 09:36:58');
INSERT INTO `womens` VALUES ('8', '24', '512631225321', '2023-09-08', '0', '2024-12-08 08:45:40', '2025-01-04 11:58:33');
INSERT INTO `womens` VALUES ('10', '26', null, null, null, null, '2025-01-06 17:22:05');
