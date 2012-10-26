/*
SQLyog Trial v10.2 
MySQL - 5.5.16-log : Database - yii_space
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`yii_space` /*!40100 DEFAULT CHARACTER SET utf8 */;

USE `yii_space`;

/*Table structure for table `_user_badge` */

DROP TABLE IF EXISTS `_user_badge`;

CREATE TABLE `_user_badge` (
  `user_id` int(11) NOT NULL DEFAULT '0',
  `badge_id` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`user_id`,`badge_id`),
  KEY `_user_badge_badge_id_fkey` (`badge_id`),
  CONSTRAINT `_user_badge_badge_id_fkey` FOREIGN KEY (`badge_id`) REFERENCES `badge` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `_user_badge` */

/*Table structure for table `action_feed` */

DROP TABLE IF EXISTS `action_feed`;

CREATE TABLE `action_feed` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL COMMENT 'actor 行为者的id',
  `action_type` tinyint(3) NOT NULL DEFAULT '1' COMMENT '动作类型1表示ar_insert 2表示ar_update（跟object_type一起等价于verb语义） 3是控制器动作',
  `action_time` int(11) DEFAULT NULL COMMENT '动作的时间 注意不是ctime 是动作的时间',
  `data` text NOT NULL COMMENT '标题 和内容的数据 数组序列化后存储 含title和body的数据 原来设计为两个字段 感觉有浪费',
  `object_type` varchar(25) NOT NULL COMMENT '动作的主体 ar_class或者是当前路由(如果是当前路由时object_id可以取一个无意义的值)',
  `object_id` int(11) NOT NULL DEFAULT '0' COMMENT '动作的实体id配合object_type一起可以定位一个ar',
  `target_type` varchar(25) NOT NULL DEFAULT '' COMMENT '动作的目标 to 如添加照片to某相册',
  `target_id` int(11) NOT NULL DEFAULT '0' COMMENT '跟target_type一起可标识一个ar实体',
  `target_owner` int(11) NOT NULL DEFAULT '0' COMMENT '目标的主人一般都是自己根据verb决定',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=79 DEFAULT CHARSET=utf8 COMMENT='动态反馈表 是对某人干了什么的图文描述';

/*Data for the table `action_feed` */

insert  into `action_feed`(`id`,`uid`,`action_type`,`action_time`,`data`,`object_type`,`object_id`,`target_type`,`target_id`,`target_owner`) values (77,3,1,1346147288,'a:5:{s:8:\"accepted\";i:0;s:4:\"type\";s:1:\"1\";s:6:\"user_a\";s:1:\"3\";s:6:\"user_b\";s:1:\"3\";s:2:\"id\";s:1:\"5\";}','Relationship',5,'',0,0),(76,3,1,1346147284,'a:5:{s:8:\"accepted\";i:0;s:4:\"type\";s:1:\"1\";s:6:\"user_a\";s:1:\"3\";s:6:\"user_b\";s:1:\"2\";s:2:\"id\";s:1:\"4\";}','Relationship',4,'',0,0),(75,1,1,1346147200,'a:5:{s:8:\"accepted\";i:0;s:4:\"type\";s:1:\"1\";s:6:\"user_a\";s:1:\"1\";s:6:\"user_b\";s:1:\"1\";s:2:\"id\";s:1:\"3\";}','Relationship',3,'',0,0),(74,1,1,1346147172,'a:5:{s:8:\"accepted\";i:0;s:4:\"type\";s:1:\"1\";s:6:\"user_a\";s:1:\"1\";s:6:\"user_b\";s:1:\"3\";s:2:\"id\";s:1:\"2\";}','Relationship',2,'',0,0),(78,2,1,1346148089,'a:5:{s:8:\"accepted\";i:0;s:4:\"type\";s:1:\"1\";s:6:\"user_a\";s:1:\"2\";s:6:\"user_b\";s:1:\"2\";s:2:\"id\";s:1:\"6\";}','Relationship',6,'',0,0);

/*Table structure for table `admin_menu` */

DROP TABLE IF EXISTS `admin_menu`;

CREATE TABLE `admin_menu` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `root` int(10) unsigned DEFAULT NULL,
  `lft` int(10) unsigned NOT NULL,
  `rgt` int(10) unsigned NOT NULL,
  `level` smallint(5) unsigned NOT NULL,
  `label` varchar(255) NOT NULL,
  `url` varchar(255) DEFAULT NULL COMMENT 'url 如 array(user/create); 或者user/create  //user/create 服务端处理时要判断是否转为array 要考虑如果采用前者分号的问题 eval函数',
  `params` tinytext COMMENT 'url 后的请求参数',
  `ajaxoptions` text,
  `htmloptions` text,
  `is_visible` tinyint(1) NOT NULL DEFAULT '1',
  `uid` int(11) NOT NULL DEFAULT '0' COMMENT '谁的树 系统默认的是后台树0 ',
  `group_code` varchar(25) NOT NULL DEFAULT 'sys_admin_menu' COMMENT '归类码表示用途的 一般只需要标记根的用途即可 也可以考虑用eav 但考虑到查询问题 所以引入了此字段',
  PRIMARY KEY (`id`),
  KEY `root` (`root`),
  KEY `lft` (`lft`),
  KEY `rgt` (`rgt`),
  KEY `level` (`level`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8 COMMENT='用nestedset保存树关系';

/*Data for the table `admin_menu` */

insert  into `admin_menu`(`id`,`root`,`lft`,`rgt`,`level`,`label`,`url`,`params`,`ajaxoptions`,`htmloptions`,`is_visible`,`uid`,`group_code`) values (8,8,1,20,1,'top_virtual_root',NULL,NULL,NULL,NULL,1,0,'sys_admin_menu_root'),(9,8,4,7,2,'RELATIONS',NULL,NULL,NULL,NULL,1,0,'sys_admin_menu'),(10,8,2,3,2,'HOME',NULL,NULL,NULL,NULL,1,0,'sys_admin_menu'),(11,8,8,15,2,'user',NULL,NULL,NULL,NULL,1,0,'sys_admin_menu'),(12,8,5,6,3,'relationTypes','/friend/relationshipType/adminAdv',NULL,NULL,NULL,1,0,'sys_admin_menu'),(14,8,11,12,3,'User Profile Manager','/user/profileField/admin',NULL,NULL,NULL,1,0,'sys_admin_menu'),(16,8,9,10,3,'用户管理','/user/admin/admin',NULL,NULL,NULL,1,0,'sys_admin_menu'),(18,8,13,14,3,'新增用户profile字段','/user/profileField/create',NULL,NULL,NULL,1,0,'sys_admin_menu'),(19,8,16,19,2,'modules',NULL,NULL,NULL,NULL,1,0,'sys_admin_menu'),(20,8,17,18,3,'manage','module/admin',NULL,NULL,NULL,1,0,'sys_admin_menu');

/*Table structure for table `badge` */

DROP TABLE IF EXISTS `badge`;

CREATE TABLE `badge` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(64) NOT NULL,
  `slug` varchar(32) NOT NULL,
  `desc` text,
  `exp` int(11) DEFAULT '0',
  `active` tinyint(1) DEFAULT '1',
  `user_count` int(11) DEFAULT '0',
  `t_insert` datetime DEFAULT NULL,
  `t_update` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `badge_slug_ukey` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

/*Data for the table `badge` */

insert  into `badge`(`id`,`name`,`slug`,`desc`,`exp`,`active`,`user_count`,`t_insert`,`t_update`) values (1,'First login','login-first','Logged for the first time',0,1,0,NULL,NULL),(2,'List viewer','list-my','View my badges',0,1,0,NULL,NULL);

/*Table structure for table `comment` */

DROP TABLE IF EXISTS `comment`;

CREATE TABLE `comment` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID of the comment',
  `content` text NOT NULL COMMENT 'The comment',
  `profile_post` int(11) NOT NULL COMMENT 'The profile post the comment relates to',
  `creator` int(11) NOT NULL COMMENT 'The creator of the comment',
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'The date the comment was posted',
  `approved` tinyint(1) NOT NULL DEFAULT '1' COMMENT 'If the comment is approved',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `comment` */

/*Table structure for table `dashboard` */

DROP TABLE IF EXISTS `dashboard`;

CREATE TABLE `dashboard` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `position` varchar(50) CHARACTER SET armscii8 COLLATE armscii8_bin NOT NULL DEFAULT '146,383',
  `render` text NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '1',
  `size` varchar(50) NOT NULL DEFAULT '334,140',
  `allowdelete` tinyint(4) NOT NULL,
  `ajaxrequest` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=37 DEFAULT CHARSET=utf8;

/*Data for the table `dashboard` */

insert  into `dashboard`(`id`,`user_id`,`title`,`content`,`position`,`render`,`active`,`size`,`allowdelete`,`ajaxrequest`) values (32,0,'Ajax demo1','','170.5500030517578,123.63333129882812','',1,'207,41',0,'/sdashboard/dashboard/demoAjax'),(35,0,'A second portlet','with some content but [b]no ajax[/b]','178,367.08331298828125','',1,'218,51',0,''),(36,0,'Default portlet','[size=200]A default portlet[/size]','159,628.0908813476562','',1,'296,87',0,'');

/*Table structure for table `group` */

DROP TABLE IF EXISTS `group`;

CREATE TABLE `group` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `description` longtext NOT NULL,
  `creator` int(11) NOT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `type` enum('public','private','private-member-invite','private-self-invite') NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

/*Data for the table `group` */

insert  into `group`(`id`,`name`,`description`,`creator`,`created`,`type`,`active`) values (1,'test1','sdfsdfsdf',1,'2012-08-18 14:21:45','public',1);

/*Table structure for table `group_member` */

DROP TABLE IF EXISTS `group_member`;

CREATE TABLE `group_member` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `group` int(11) NOT NULL,
  `user` int(11) NOT NULL,
  `approved` tinyint(1) NOT NULL DEFAULT '0',
  `requested` tinyint(1) NOT NULL DEFAULT '0',
  `invited` tinyint(1) NOT NULL DEFAULT '0',
  `requested_date` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `invited_date` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `join_date` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `inviter` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `group_member` */

/*Table structure for table `group_topic` */

DROP TABLE IF EXISTS `group_topic`;

CREATE TABLE `group_topic` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `creator` int(11) NOT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `active` tinyint(1) NOT NULL DEFAULT '1',
  `group` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

/*Data for the table `group_topic` */

insert  into `group_topic`(`id`,`name`,`creator`,`created`,`active`,`group`) values (1,'test topic',1,'2012-08-18 15:16:00',1,1),(2,'test2',1,'2012-08-18 16:38:32',1,1);

/*Table structure for table `msg` */

DROP TABLE IF EXISTS `msg`;

CREATE TABLE `msg` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sender` int(11) NOT NULL,
  `recipient` int(11) NOT NULL,
  `sent` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `read` tinyint(1) NOT NULL,
  `subject` varchar(255) NOT NULL,
  `message` longtext NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=18 DEFAULT CHARSET=utf8;

/*Data for the table `msg` */

insert  into `msg`(`id`,`sender`,`recipient`,`sent`,`read`,`subject`,`message`) values (12,1,2,'2012-08-14 23:16:14',1,'斯蒂芬','斯蒂芬'),(13,1,2,'2012-08-14 23:18:03',1,'速度飞洒地方','斯蒂芬 '),(14,1,2,'2012-08-14 23:18:03',1,'速度飞洒地方','斯蒂芬 '),(15,1,2,'2012-08-14 23:20:40',1,'中文还是可以的啦','吼吼吼吼吼'),(16,1,1,'2012-08-14 23:23:41',1,'收到发送到','地方规定发给'),(17,1,3,'2012-08-18 16:28:57',0,'dfgdsf','sdfsdfs');

/*Table structure for table `relationship` */

DROP TABLE IF EXISTS `relationship`;

CREATE TABLE `relationship` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'A unique ID for the relationship between the two users',
  `type` int(11) NOT NULL COMMENT 'The type of relationship (a reference to the relationship_types table)',
  `user_a` int(11) NOT NULL COMMENT 'The user who initiated the relationship, a relation to the users table',
  `user_b` int(11) NOT NULL COMMENT 'The user who usera initiated a relationship with, a relation to the users table',
  `accepted` tinyint(1) NOT NULL DEFAULT '1' COMMENT 'Indicates if this is a mutual relationship (which is only used if the relationship type is a mutual relationship)',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

/*Data for the table `relationship` */

insert  into `relationship`(`id`,`type`,`user_a`,`user_b`,`accepted`) values (1,1,1,2,0),(2,1,1,3,1),(3,1,1,1,0),(4,1,3,2,0),(5,1,3,3,1),(6,1,2,2,0);

/*Table structure for table `relationship_type` */

DROP TABLE IF EXISTS `relationship_type`;

CREATE TABLE `relationship_type` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'A unique ID for the relationship type',
  `name` varchar(25) NOT NULL COMMENT 'The name of the relationship type, for example, friend',
  `plural_name` varchar(25) DEFAULT NULL COMMENT 'Plural version of the relationship type,for example, friends',
  `active` tinyint(1) NOT NULL DEFAULT '1' COMMENT 'If this relationship type is active, and should users be able to form such relationships?',
  `mutual` tinyint(1) DEFAULT '1' COMMENT 'Does this relationship require it to be a mutual connection, or can users connect without the permission of the other?',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

/*Data for the table `relationship_type` */

insert  into `relationship_type`(`id`,`name`,`plural_name`,`active`,`mutual`) values (1,'Friend','friends',1,1),(2,'Colleague','Colleagues',1,1),(3,'Jogging buddies','Jogging buddies',1,1);

/*Table structure for table `settings` */

DROP TABLE IF EXISTS `settings`;

CREATE TABLE `settings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `category` varchar(64) NOT NULL DEFAULT 'system',
  `key` varchar(255) NOT NULL,
  `value` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `category_key` (`category`,`key`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

/*Data for the table `settings` */

insert  into `settings`(`id`,`category`,`key`,`value`) values (1,'cate1','key1','s:27:\"hello actionTestCmsSettings\";'),(2,'action_feed','Relationship','s:61:\"application.modules.friend.components.RelationshipFeedHandler\";'),(3,'cate1','key2','a:2:{s:1:\"k\";s:1:\"v\";s:2:\"k2\";i:2;}');

/*Table structure for table `status` */

DROP TABLE IF EXISTS `status`;

CREATE TABLE `status` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID of the status',
  `update` longtext NOT NULL COMMENT 'The content of the update',
  `type` int(11) NOT NULL COMMENT 'Reference to the status types table',
  `creator` int(11) NOT NULL COMMENT 'The ID of the poster',
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Time status was posted',
  `profile` int(11) NOT NULL COMMENT 'Profile the status was posted on',
  `approved` tinyint(1) DEFAULT '1' COMMENT 'If the status is approved or notIf the status is approved or not',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=34 DEFAULT CHARSET=utf8;

/*Data for the table `status` */

insert  into `status`(`id`,`update`,`type`,`creator`,`created`,`profile`,`approved`) values (1,'hi  welcome to my space just test',1,1,'2012-08-15 12:25:53',1,1),(2,'realy funny',1,1,'2012-08-15 12:36:44',1,1),(3,'good good study day day up',1,1,'2012-08-15 12:40:20',1,1),(4,'yes',1,1,'2012-08-15 12:41:27',1,1),(5,'说的方法斯蒂芬发',1,1,'2012-08-15 12:43:29',1,1),(6,'速度飞洒地方',1,1,'2012-08-15 12:52:15',1,1),(7,'funny video',3,1,'2012-08-15 13:00:30',1,1),(8,'asadfsdf',3,1,'2012-08-15 13:01:03',1,1),(9,'this is great framework',4,1,'2012-08-15 13:01:49',1,1),(10,'this is great framework',4,1,'2012-08-15 13:03:21',1,1),(11,'sdfsdf',2,1,'2012-08-15 13:07:04',1,1),(12,'sdfsdfsadf',2,1,'2012-08-15 13:07:58',1,1),(13,'dff',2,1,'2012-08-15 13:09:17',1,1),(14,'sdfsdf',2,1,'2012-08-15 13:13:25',1,1),(15,'呵呵呵给你这六个眼',1,1,'2012-08-15 13:26:27',2,1),(16,'sdgfdsfg',1,2,'2012-08-15 13:36:13',2,1),(17,'hehhe 给管理员试试',1,2,'2012-08-15 13:36:43',1,1),(18,'阿斯顿飞说道',2,1,'2012-08-15 13:44:26',1,1),(19,'hello  i love 有',1,1,'2012-08-15 14:54:32',2,1),(20,'ffsadfsd',1,1,'2012-08-15 23:57:43',1,1),(21,'sdfsdfsd',1,1,'2012-08-16 12:26:43',1,1),(22,'thy it',1,1,'2012-08-16 14:05:55',3,1),(23,'hehahha it works ',1,1,'2012-08-16 14:06:18',3,1),(24,'测试发送图片',2,1,'2012-08-16 18:24:26',1,1),(25,'jj pp',1,2,'2012-08-16 23:13:04',1,1),(26,'ffdgdfgdfs',1,1,'2012-08-17 16:18:02',1,1),(27,'sdfsdf',2,1,'2012-08-17 16:18:32',1,1),(28,'蛋疼的表情',1,1,'2012-08-19 09:39:36',2,1),(29,'fdgdfg',2,1,'2012-08-24 22:03:01',1,1),(30,'今天把actionFeed 算搞的差不多了 算是比较难的一个主题了',1,2,'2012-08-28 18:18:33',2,1),(31,'主要参考了这个',4,2,'2012-08-28 18:19:31',2,1),(32,'投票系统的设计：',4,1,'2012-09-13 21:41:44',1,1),(33,'投票设计',4,1,'2012-09-13 21:46:53',1,1);

/*Table structure for table `status_image` */

DROP TABLE IF EXISTS `status_image`;

CREATE TABLE `status_image` (
  `id` int(11) NOT NULL,
  `image` varchar(255) CHARACTER SET latin1 NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `status_image` */

insert  into `status_image`(`id`,`image`) values (11,'1345007223_1345007223.jpg'),(12,'1345007278_1345007278.jpg'),(13,'1345007357_1345007357.jpg'),(14,'uploads/status/1345007605_1345007605.gif'),(18,'uploads/status/1345009466_1345009466.jpg'),(24,'uploads/status/1345112665_1345112665.jpg'),(27,'uploads/status/1345191512_1345191512.jpg'),(29,'uploads/status/1345816981_1345816981.jpg');

/*Table structure for table `status_link` */

DROP TABLE IF EXISTS `status_link`;

CREATE TABLE `status_link` (
  `id` int(11) NOT NULL,
  `url` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `status_link` */

insert  into `status_link`(`id`,`url`,`description`) values (33,'http://stackoverflow.com/questions/3433391/how-to-restrict-user-not-to-vote-an-article-more-than-once','禁止多次投票');

/*Table structure for table `status_plugin` */

DROP TABLE IF EXISTS `status_plugin`;

CREATE TABLE `status_plugin` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID of the status plugin',
  `name` varchar(25) NOT NULL COMMENT 'The name of the type of status',
  `type_reference` varchar(120) NOT NULL COMMENT 'A machine readable name for the type, used as the file name of template bits (that is, no spaces or punctuation)',
  `description` varchar(255) NOT NULL,
  `plugin_class` varchar(255) NOT NULL COMMENT 'the class for this type',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `status_plugin` */

/*Table structure for table `status_type` */

DROP TABLE IF EXISTS `status_type`;

CREATE TABLE `status_type` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID of the status type',
  `type_name` varchar(25) NOT NULL COMMENT 'The name of the type of status',
  `type_reference` varchar(120) NOT NULL COMMENT 'A machine readable name for the type, used as the file name of template bits (that is, no spaces or punctuation)',
  `active` tinyint(1) NOT NULL DEFAULT '1' COMMENT 'Indicates whether the status type is active or not',
  `type_name_other` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

/*Data for the table `status_type` */

insert  into `status_type`(`id`,`type_name`,`type_reference`,`active`,`type_name_other`) values (1,'Changed their status to','update',1,''),(2,'Posted an image','image',1,''),(3,'Uploaded a video','video',1,''),(4,'Posted a link','link',1,'');

/*Table structure for table `status_video` */

DROP TABLE IF EXISTS `status_video`;

CREATE TABLE `status_video` (
  `id` int(11) NOT NULL,
  `video_id` varchar(50) CHARACTER SET latin1 NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `status_video` */

insert  into `status_video`(`id`,`video_id`) values (7,'YCiY1y3uJ3o'),(8,'YCiY1y3uJ3o');

/*Table structure for table `sys_module` */

DROP TABLE IF EXISTS `sys_module`;

CREATE TABLE `sys_module` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `module_id` varchar(32) NOT NULL DEFAULT '',
  `title` varchar(255) NOT NULL DEFAULT '',
  `vendor` varchar(64) NOT NULL DEFAULT '',
  `version` varchar(32) NOT NULL DEFAULT '',
  `dependencies` varchar(255) NOT NULL DEFAULT '',
  `ctime` int(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=45 DEFAULT CHARSET=utf8;

/*Data for the table `sys_module` */

insert  into `sys_module`(`id`,`module_id`,`title`,`vendor`,`version`,`dependencies`,`ctime`) values (44,'friend','','','','',1346038195);

/*Table structure for table `tbl_migration` */

DROP TABLE IF EXISTS `tbl_migration`;

CREATE TABLE `tbl_migration` (
  `version` varchar(255) NOT NULL,
  `apply_time` int(11) DEFAULT NULL,
  PRIMARY KEY (`version`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `tbl_migration` */

insert  into `tbl_migration`(`version`,`apply_time`) values ('m000000_000000_base',1343115971),('m110805_153437_installYiiUser',1343115996),('m110810_162301_userTimestampFix',1343115996);

/*Table structure for table `user` */

DROP TABLE IF EXISTS `user`;

CREATE TABLE `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(20) NOT NULL DEFAULT '',
  `password` varchar(128) NOT NULL DEFAULT '',
  `email` varchar(128) NOT NULL DEFAULT '',
  `activkey` varchar(128) NOT NULL DEFAULT '',
  `superuser` int(1) NOT NULL DEFAULT '0',
  `status` int(1) NOT NULL DEFAULT '0',
  `create_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `lastvisit_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_username` (`username`),
  UNIQUE KEY `user_email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

/*Data for the table `user` */

insert  into `user`(`id`,`username`,`password`,`email`,`activkey`,`superuser`,`status`,`create_at`,`lastvisit_at`) values (1,'yiqing','32ee7d239bcc2eff68f47ca70bd6e4a7','webmaster@example.com','8abda1b47ccc436c2aaf3eb5912d3971',1,1,'2012-07-24 15:46:36','2012-09-18 14:30:26'),(2,'yiqing2','32ee7d239bcc2eff68f47ca70bd6e4a7','66104992@qq.com','c1a5a5f7d1cc198d132d2d367ca08877',0,1,'2012-07-27 10:29:01','2012-08-28 10:01:18'),(3,'yiqing3','32ee7d239bcc2eff68f47ca70bd6e4a7','yiqing_95@qq.com','f07550b610f3388b17ffc6b62aecb1ad',0,1,'2012-08-11 14:15:02','2012-08-28 10:00:24');

/*Table structure for table `user_profile` */

DROP TABLE IF EXISTS `user_profile`;

CREATE TABLE `user_profile` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `first_name` varchar(255) DEFAULT NULL,
  `last_name` varchar(255) DEFAULT NULL,
  `photo` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`user_id`),
  CONSTRAINT `user_profile_id` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

/*Data for the table `user_profile` */

insert  into `user_profile`(`user_id`,`first_name`,`last_name`,`photo`) values (1,'Administrator','Admin','uploads/user/13448535311344853531.gif'),(2,'qing','yii','uploads/user/13448530331344853033.gif'),(3,'qing','yii','uploads/user/13448530641344853064.jpg');

/*Table structure for table `user_profile_field` */

DROP TABLE IF EXISTS `user_profile_field`;

CREATE TABLE `user_profile_field` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `varname` varchar(50) NOT NULL DEFAULT '',
  `title` varchar(255) NOT NULL DEFAULT '',
  `field_type` varchar(50) NOT NULL DEFAULT '',
  `field_size` int(3) NOT NULL DEFAULT '0',
  `field_size_min` int(3) NOT NULL DEFAULT '0',
  `required` int(1) NOT NULL DEFAULT '0',
  `match` varchar(255) NOT NULL DEFAULT '',
  `range` varchar(255) NOT NULL DEFAULT '',
  `error_message` varchar(255) NOT NULL DEFAULT '',
  `other_validator` text,
  `default` varchar(255) NOT NULL DEFAULT '',
  `widget` varchar(255) NOT NULL DEFAULT '',
  `widgetparams` text,
  `position` int(3) NOT NULL DEFAULT '0',
  `visible` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

/*Data for the table `user_profile_field` */

insert  into `user_profile_field`(`id`,`varname`,`title`,`field_type`,`field_size`,`field_size_min`,`required`,`match`,`range`,`error_message`,`other_validator`,`default`,`widget`,`widgetparams`,`position`,`visible`) values (1,'first_name','First Name','VARCHAR',255,3,2,'','','Incorrect First Name (length between 3 and 50 characters).','','','','',1,3),(2,'last_name','Last Name','VARCHAR',255,3,2,'','','Incorrect Last Name (length between 3 and 50 characters).','','','','',2,3),(3,'photo','photo','VARCHAR',255,0,0,'','','','','','','',0,3);

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
