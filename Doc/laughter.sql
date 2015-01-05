-- MySQL dump 10.13  Distrib 5.1.73, for redhat-linux-gnu (x86_64)
--
-- Host: localhost    Database: laughter
-- ------------------------------------------------------
-- Server version	5.1.73-log

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
-- Table structure for table `Test`
--

DROP TABLE IF EXISTS `Test`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Test` (
  `a` char(1) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `comment`
--

DROP TABLE IF EXISTS `comment`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `comment` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) DEFAULT NULL,
  `joke_id` bigint(20) DEFAULT NULL,
  `reply_comment_id` bigint(20) DEFAULT NULL COMMENT '回复的评论ID',
  `content` varchar(500) DEFAULT NULL,
  `up_count` int(11) DEFAULT '0',
  `create_time` datetime DEFAULT NULL,
  `is_closed` int(11) DEFAULT '0' COMMENT '不在列表显示时用来屏蔽 0:未屏蔽 1：屏蔽',
  PRIMARY KEY (`id`),
  KEY `comment_index1` (`user_id`),
  KEY `comment_index2` (`joke_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3111 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `comment_up_record`
--

DROP TABLE IF EXISTS `comment_up_record`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `comment_up_record` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) DEFAULT NULL,
  `comment_id` bigint(20) DEFAULT NULL,
  `up_time` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `comment_up_index1` (`user_id`),
  KEY `comment_up_index2` (`comment_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1386 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `feedback`
--

DROP TABLE IF EXISTS `feedback`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `feedback` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) DEFAULT NULL,
  `content` varchar(500) DEFAULT NULL,
  `create_time` datetime DEFAULT NULL,
  `status` int(11) DEFAULT '0' COMMENT '0:未处理 1：已处理',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=51 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `joke`
--

DROP TABLE IF EXISTS `joke`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `joke` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `type` int(11) NOT NULL COMMENT '1文字 2图文',
  `user_id` bigint(20) NOT NULL,
  `content` varchar(600) NOT NULL,
  `img_url` varchar(200) DEFAULT NULL,
  `up_count` int(11) NOT NULL DEFAULT '0',
  `down_count` int(11) NOT NULL DEFAULT '0',
  `favorate_count` int(11) DEFAULT '0',
  `share_count` int(11) DEFAULT '0',
  `comment_count` int(11) NOT NULL DEFAULT '0',
  `create_time` datetime NOT NULL,
  `is_closed` int(11) NOT NULL DEFAULT '0' COMMENT '不在列表显示时用来屏蔽 0:未屏蔽 1：屏蔽',
  PRIMARY KEY (`id`),
  KEY `joke_index1` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4998 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `joke_down_record`
--

DROP TABLE IF EXISTS `joke_down_record`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `joke_down_record` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) DEFAULT NULL,
  `joke_id` bigint(20) DEFAULT NULL,
  `up_time` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `joke_down_index1` (`user_id`),
  KEY `joke_down_index2` (`joke_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1703 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `joke_favorate_record`
--

DROP TABLE IF EXISTS `joke_favorate_record`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `joke_favorate_record` (
  `id` bigint(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(11) unsigned NOT NULL,
  `joke_id` bigint(11) unsigned NOT NULL,
  `mtime` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_id_2` (`user_id`,`joke_id`),
  KEY `user_id` (`user_id`),
  KEY `joke_id` (`joke_id`)
) ENGINE=InnoDB AUTO_INCREMENT=36 DEFAULT CHARSET=utf8 COMMENT='笑话喜欢记录表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `joke_up_record`
--

DROP TABLE IF EXISTS `joke_up_record`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `joke_up_record` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) DEFAULT NULL,
  `joke_id` bigint(20) DEFAULT NULL,
  `up_time` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `joke_up_index1` (`user_id`),
  KEY `joke_up_index2` (`joke_id`)
) ENGINE=InnoDB AUTO_INCREMENT=23453 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `push_record`
--

DROP TABLE IF EXISTS `push_record`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `push_record` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `message` varchar(300) DEFAULT NULL,
  `create_time` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='只存储服务器端的推送记录以便后台查询';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `report`
--

DROP TABLE IF EXISTS `report`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `report` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) DEFAULT NULL,
  `joke_id` bigint(20) DEFAULT NULL,
  `create_time` datetime DEFAULT NULL,
  `content` varchar(200) DEFAULT NULL,
  `status` int(11) DEFAULT '0' COMMENT '0:未处理 1：已处理',
  PRIMARY KEY (`id`),
  KEY `report_index1` (`user_id`),
  KEY `report_index2` (`joke_id`)
) ENGINE=InnoDB AUTO_INCREMENT=53 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `account` varchar(50) NOT NULL,
  `password` varchar(32) DEFAULT NULL,
  `token` varchar(50) NOT NULL,
  `nickname` varchar(50) NOT NULL,
  `avatar` varchar(200) DEFAULT NULL,
  `score` int(11) NOT NULL,
  `sign` varchar(500) DEFAULT NULL,
  `account_type` int(11) NOT NULL DEFAULT '0' COMMENT '0 普通账号 1新浪微博 2qq帐号',
  `regist_time` datetime NOT NULL,
  `last_login_time` datetime NOT NULL,
  `is_disabled` int(11) NOT NULL DEFAULT '0' COMMENT '0:为禁用 1:禁用',
  PRIMARY KEY (`id`),
  KEY `users_index1` (`account`),
  KEY `users_index2` (`token`)
) ENGINE=InnoDB AUTO_INCREMENT=1892 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2015-01-05 11:45:06
