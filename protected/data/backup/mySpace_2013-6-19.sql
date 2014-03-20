/*
SQLyog Trial v10.2 
MySQL - 5.5.16-log : Database - rcyx
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
USE `rcyx`;

/*Table structure for table `access_trace` */

DROP TABLE IF EXISTS `access_trace`;

CREATE TABLE `access_trace` (
  `ip` varchar(40) NOT NULL COMMENT '访问者ip',
  `space_id` int(11) DEFAULT NULL COMMENT '被访问者的空间id',
  `first_acc_time` int(11) NOT NULL COMMENT '首次访问时间',
  `last_acc_time` int(11) DEFAULT NULL COMMENT '最好一次访问时间',
  `acc_count` int(11) NOT NULL DEFAULT '0' COMMENT '访问次数',
  `acc_type` tinyint(4) NOT NULL DEFAULT '0' COMMENT '访问类型 默认是空间访问  另有后台访问，系统空间访问 公共前端访问等'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='访问追踪 以日为单位 一日内多次访问只记录第一次的';

/*Data for the table `access_trace` */

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
) ENGINE=InnoDB AUTO_INCREMENT=52 DEFAULT CHARSET=utf8 COMMENT='用nestedset保存树关系';

/*Data for the table `admin_menu` */

insert  into `admin_menu`(`id`,`root`,`lft`,`rgt`,`level`,`label`,`url`,`params`,`ajaxoptions`,`htmloptions`,`is_visible`,`uid`,`group_code`) values (8,8,1,54,1,'top_virtual_root',NULL,NULL,NULL,NULL,1,0,'sys_admin_menu_root'),(10,8,2,5,2,'HOME',NULL,NULL,NULL,NULL,1,0,'sys_admin_menu'),(11,8,6,13,2,'user',NULL,NULL,NULL,NULL,1,0,'sys_admin_menu'),(14,8,9,10,3,'User Profile Manager','/user/profileField/admin',NULL,NULL,NULL,1,0,'sys_admin_menu'),(16,8,7,8,3,'用户管理','/user/admin/admin',NULL,NULL,NULL,1,0,'sys_admin_menu'),(18,8,11,12,3,'新增用户profile字段','/user/profileField/create',NULL,NULL,NULL,1,0,'sys_admin_menu'),(19,8,14,17,2,'modules',NULL,NULL,NULL,NULL,1,0,'sys_admin_menu'),(20,8,15,16,3,'manage','/module/admin',NULL,NULL,NULL,1,0,'sys_admin_menu'),(27,8,18,21,2,'评论管理',NULL,NULL,NULL,NULL,1,0,'sys_admin_menu'),(28,8,19,20,3,'管理','/comments/comment/admin',NULL,NULL,NULL,1,0,'sys_admin_menu'),(29,8,22,31,2,'系统',NULL,NULL,NULL,NULL,1,0,'sys_admin_menu'),(35,8,23,24,3,'后台用户管理','/adminUser/admin',NULL,NULL,NULL,1,0,'sys_admin_menu'),(37,8,3,4,3,'index',NULL,NULL,NULL,NULL,1,0,'sys_admin_menu'),(38,8,25,26,3,'后台角色管理','/adminRole/admin',NULL,NULL,NULL,1,0,'sys_admin_menu'),(39,8,32,37,2,'新闻管理',NULL,NULL,NULL,NULL,1,0,'sys_admin_menu'),(40,8,33,34,3,'新闻分类管理','/news/newsCategory/admin',NULL,NULL,NULL,1,0,'sys_admin_menu'),(41,8,35,36,3,'新闻条目管理','/news/newsEntry/admin',NULL,NULL,NULL,1,0,'sys_admin_menu'),(42,8,38,43,2,'公告',NULL,NULL,NULL,NULL,1,0,'sys_admin_menu'),(43,8,39,40,3,'公告分类管理','/notice/noticeCategory/admin',NULL,NULL,NULL,1,0,'sys_admin_menu'),(44,8,41,42,3,'公告管理','/notice/noticePost/admin',NULL,NULL,NULL,1,0,'sys_admin_menu'),(45,8,27,28,3,'友情链接','/sysFriendLink/admin',NULL,NULL,NULL,1,0,'sys_admin_menu'),(46,8,29,30,3,'系统菜单','/sysMenu/admin',NULL,NULL,NULL,1,0,'sys_admin_menu'),(47,8,44,45,2,'临时菜单 用来安装应用的',NULL,NULL,NULL,NULL,1,0,'sys_admin_menu'),(48,8,47,48,3,'日志系统分类管理','/blog/blogSysCategory/admin',NULL,NULL,NULL,1,0,'sys_admin_menu'),(49,8,49,50,3,'日志系统分类创建','/blog/blogSysCategory/create',NULL,NULL,NULL,1,0,'sys_admin_menu'),(50,8,46,53,2,'博文',NULL,NULL,NULL,NULL,1,0,'sys_admin_menu'),(51,8,51,52,3,'日志管理','/blog/post/admin',NULL,NULL,NULL,1,0,'sys_admin_menu');

/*Table structure for table `admin_role` */

DROP TABLE IF EXISTS `admin_role`;

CREATE TABLE `admin_role` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL COMMENT '角色名',
  `description` text COMMENT '说明',
  `disabled` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '启用',
  `create_time` int(10) unsigned NOT NULL COMMENT '添加时间',
  `update_time` int(10) unsigned NOT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`),
  KEY `disabled` (`disabled`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8;

/*Data for the table `admin_role` */

insert  into `admin_role`(`id`,`name`,`description`,`disabled`,`create_time`,`update_time`) values (1,'超级管理员','this is  description here',1,0,1349365482),(3,'商品管理员','this is  description here',0,0,1360728172),(4,'yiqing2','this is  description here',1,1357746295,1357746295),(7,'testrole2','this is  description here',0,1360725188,1360725188),(9,'jjj','this is  description here',0,1360725393,1360725393),(10,'商品管理员','this is  description here',0,1360728172,1360728172),(12,'速度飞洒地方','this is  description here',0,1360941272,1360941272),(13,'yiqing2','this is  description here',1,1360941453,1360941453),(14,'hi','this is  description here',0,1361287891,1361287891),(15,'papa',NULL,0,1361636018,1361636018);

/*Table structure for table `admin_role_priv` */

DROP TABLE IF EXISTS `admin_role_priv`;

CREATE TABLE `admin_role_priv` (
  `role_id` int(10) unsigned NOT NULL COMMENT '角色ID',
  `menu_id` int(10) unsigned NOT NULL COMMENT '菜单ID',
  PRIMARY KEY (`role_id`,`menu_id`),
  KEY `roleid` (`role_id`),
  KEY `menu_id` (`menu_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `admin_role_priv` */

insert  into `admin_role_priv`(`role_id`,`menu_id`) values (1,0),(3,0),(4,0),(7,0),(9,0),(10,0),(12,0),(13,0),(14,0),(15,10),(15,11),(15,14),(15,16),(15,18),(15,19),(15,20),(15,27),(15,28),(15,29),(15,35),(15,37),(15,38);

/*Table structure for table `admin_user` */

DROP TABLE IF EXISTS `admin_user`;

CREATE TABLE `admin_user` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '用户ID',
  `username` varchar(50) NOT NULL COMMENT '用户名',
  `password` varchar(40) NOT NULL COMMENT '密码',
  `name` varchar(50) NOT NULL COMMENT '用户真名',
  `encrypt` char(6) NOT NULL COMMENT '加密',
  `role_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '角色',
  `disabled` tinyint(1) DEFAULT '0' COMMENT '禁用',
  `setting` text COMMENT '设置',
  `create_time` int(10) unsigned NOT NULL COMMENT '添加时间',
  `update_time` int(10) unsigned NOT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `id` (`id`),
  KEY `role_id` (`role_id`)
) ENGINE=InnoDB AUTO_INCREMENT=44 DEFAULT CHARSET=utf8;

/*Data for the table `admin_user` */

insert  into `admin_user`(`id`,`username`,`password`,`name`,`encrypt`,`role_id`,`disabled`,`setting`,`create_time`,`update_time`) values (4,'admin','6ad69ef346b5051100da8676cc340e78','管理员','RKPjN3',1,0,NULL,0,1353301592),(6,'yiqing','a4b54fec26e9075c6447c436fef27cfc','yiqing','uvcOSn',1,0,NULL,1357745292,1357745292),(43,'hello','036cb99d9d136ceed861ff1646f00e07','hello','z9GGtp',0,0,NULL,1361635056,1361635056);

/*Table structure for table `blog_attachment` */

DROP TABLE IF EXISTS `blog_attachment`;

CREATE TABLE `blog_attachment` (
  `id` bigint(32) unsigned NOT NULL AUTO_INCREMENT COMMENT '序号',
  `post_id` int(11) unsigned NOT NULL COMMENT '博客序号',
  `filename` varchar(255) NOT NULL COMMENT '附件名称',
  `filesize` int(11) unsigned NOT NULL DEFAULT '0',
  `filepath` varchar(255) NOT NULL COMMENT '附件路径',
  `created` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `updated` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`),
  KEY `post_id` (`post_id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='附件表';

/*Data for the table `blog_attachment` */

insert  into `blog_attachment`(`id`,`post_id`,`filename`,`filesize`,`filepath`,`created`,`updated`) values (1,66,'66',6,'6',0,0);

/*Table structure for table `blog_category` */

DROP TABLE IF EXISTS `blog_category`;

CREATE TABLE `blog_category` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '序号',
  `uid` int(11) NOT NULL DEFAULT '0',
  `pid` int(11) unsigned NOT NULL DEFAULT '0',
  `name` varchar(255) NOT NULL,
  `alias` varchar(255) DEFAULT NULL COMMENT '别名',
  `position` int(11) unsigned DEFAULT '0' COMMENT '排序序号',
  `mbr_count` int(6) unsigned DEFAULT '0' COMMENT '成员数量',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=34 DEFAULT CHARSET=utf8 COMMENT='分类表';

/*Data for the table `blog_category` */

insert  into `blog_category`(`id`,`uid`,`pid`,`name`,`alias`,`position`,`mbr_count`) values (1,0,0,'yii','yii',0,19),(2,0,0,'php','php',0,2),(6,2,0,'心情','',0,9),(9,1,0,'我的分类啊','',0,14),(10,1,0,'wo quee','',0,0),(11,1,0,'我去2','',0,2),(12,3,0,'看不见哎','',0,4),(13,3,0,'是打发斯蒂芬','',0,0),(15,2,0,'第三方撒旦','',0,0),(16,2,0,'你好','',0,0),(17,2,0,'速度飞洒地方','',0,1),(28,2,0,'好啊','',0,0),(29,2,0,'我去','',0,1),(30,1,0,'阿斯蒂芬克束带结发','',0,0),(31,3,0,'碎月','',0,1),(32,1,0,'神仙','',0,1),(33,3,0,'what\'s a beautiful girls','',0,0);

/*Table structure for table `blog_comment` */

DROP TABLE IF EXISTS `blog_comment`;

CREATE TABLE `blog_comment` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `content` text NOT NULL,
  `status` int(11) unsigned NOT NULL,
  `created` int(11) unsigned DEFAULT NULL,
  `author` varchar(128) NOT NULL,
  `email` varchar(128) NOT NULL,
  `url` varchar(128) DEFAULT NULL,
  `ip` varchar(128) DEFAULT NULL,
  `post_id` int(11) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_comment_post` (`post_id`)
) ENGINE=MyISAM AUTO_INCREMENT=73 DEFAULT CHARSET=utf8;

/*Data for the table `blog_comment` */

insert  into `blog_comment`(`id`,`content`,`status`,`created`,`author`,`email`,`url`,`ip`,`post_id`) values (72,'hhehe',2,1370704514,'多发点','yiqing_95@qq.com','','127.0.0.1',55),(58,'<a href=\"http://beats-by-dre8.webnode.fr/\">casque beats by dre</a> EcLaGbB<a href=http://beats-by-dre8.webnode.fr/>beats by dre detox</a> RzRmYk http://beats-by-dre8.webnode.fr/ <a href=\"http://christianlouboutinpas-cher.webnode.fr/\">louboutin soldes</a> XmCiDnI<a href=http://christianlouboutinpas-cher.webnode.fr/>christian louboutin pas cher</a> HfOxXq http://christianlouboutinpas-cher.webnode.fr/ <a href=\"http://beats-by-dre-pas-cher8.webnode.fr/\">Casque Dr Dre</a> IrFaLsU<a href=http://beats-by-dre-pas-cher8.webnode.fr/>casque beats by dre</a> WrBpEy http://beats-by-dre-pas-cher8.webnode.fr/ <a href=\"http://christianlouboutinhomme2.webnode.fr/\">christian louboutin soldes</a> LdKfVqM<a href=http://christianlouboutinhomme2.webnode.fr/>chaussures louboutin soldes</a> GxBoSu http://christianlouboutinhomme2.webnode.fr/ <a href=\"http://beats-by-dre-studio0.webnode.fr/\">casque beats</a> CjKrNwV<a href=http://beats-by-dre-studio0.webnode.fr/>casque beats</a> UiByBt http://beats-by-dre-studio0.webnode.fr/',1,1348470973,'jqumatrw','christiubousoldes@gmail.com','http://christiubousoldes.webnode.fr/','216.244.78.98',11),(4,'新版感觉怎么样呀！',2,1322327794,'winds','windsdeng@hotmail.com','http://www.dlf5.com',NULL,3),(5,'This blog system is developed using Yii. ',2,1322327830,'winds','windsdeng@hotmail.com','http://www.dlf5.com',NULL,1),(7,'磊',2,1333334787,'winds','winds','',NULL,11),(8,'tests',2,1342664286,'winds','winds@dlf5.com','',NULL,18),(9,'aaaaaaaaaaaaa',2,1342670342,'winds','winds@dlf5.com','','14.151.160.139',18),(12,'IP所在地演示',2,1343036021,'winds','winds@dlf5.com','http://dlf5.com','14.151.136.184',1),(13,'teste',2,1343170499,'teste','teste@teste.com','http://teste.com','186.204.167.230',1),(14,'This blog system is developed using Yii.',2,1343180043,'a','a@yahoo.com','','110.139.119.47',1),(15,'laskdjfasdf',2,1343227116,'qwer','qwer@ss.cc','','122.87.148.182',18),(17,'192.168.10.1 Yeah.........',2,1343520946,'melengo','ozan.rock@yahoo.co.id','http://melengo.wordpress.com','103.3.222.228',1),(18,'test',2,1343541617,'preketek','admin@google.co.id','','125.163.104.55',1),(19,'asd',2,1343786964,'123','asd@asd.asd','','211.144.84.242',1),(20,'test',2,1343824371,'test','test@test.com','http://www.test.com','101.109.214.68',1),(21,'asasd',2,1344031521,'sephiroth','plop@gmail.com','','190.201.47.138',1),(24,'test',2,1345530860,'winds','winds@dlf5.com','http://dlf5.com','59.41.95.95',1),(25,'winds',2,1345530897,'winds','winds@dlf5.com','http://dlf5.com','59.41.95.95',1),(26,'to to to ',2,1345530935,'winds','winds@dlf5.com','http://dlf5.com','59.41.95.95',1),(27,'Test',2,1345618336,'mbahsomo','mbahsomo@do-event.com','','111.196.127.133',1),(59,'<a href=\"http://casque-dr-dre1.webnode.com/\">beats by dre pas cher</a> IyDlLqJ<a href=http://casque-dr-dre1.webnode.com/>beats by dre</a> LdMwKy http://casque-dr-dre1.webnode.com/ <a href=\"http://louboutin-pas-cher1.webnode.fr/\">christian louboutin pas cher</a> OdIvEpH<a href=http://louboutin-pas-cher1.webnode.fr/>christian louboutin pas cher</a> MgHqQt http://louboutin-pas-cher1.webnode.fr/ <a href=\"http://monster-beats11.webnode.fr/\">casque beats by dre</a> QgHmFzA<a href=http://monster-beats11.webnode.fr/>casque beats by dre</a> PrGqKd http://monster-beats11.webnode.fr/ <a href=\"http://louboutin-soldes1.webnode.fr/\">chaussures louboutin pas cher</a> WhZkRrA<a href=http://louboutin-soldes1.webnode.fr/>christian louboutin soldes</a> ZsBtFh http://louboutin-soldes1.webnode.fr/ <a href=\"http://casque-beats11.webnode.fr/\">beats by dre test</a> AbViOoA<a href=http://casque-beats11.webnode.fr/>monster beats</a> FyPbIn http://casque-beats11.webnode.fr/',1,1348471006,'rzluzowz','christianpnrp@gmail.com','http://beats-by-dre11.webnode.fr/','216.244.78.98',11),(60,'<a href=\"http://louboutinmouche.webnode.fr/\">christian louboutin soldes</a> TrEmPkP<a href=http://louboutinmouche.webnode.fr/>chaussures louboutin soldes</a> QfOzUw http://louboutinmouche.webnode.fr/ <a href=\"http://monsterbeatsbstudio5.webnode.fr/\">beats by dre detox</a> IxFjZbN<a href=http://monsterbeatsbstudio5.webnode.fr/>casque beats</a> CfGzNv http://monsterbeatsbstudio5.webnode.fr/ <a href=\"http://louboutinny22.webnode.fr/\">christian louboutin soldes</a> TvIaGsS<a href=http://louboutinny22.webnode.fr/>christian louboutin france</a> KzYpQb http://louboutinny22.webnode.fr/ <a href=\"http://casquebeatsbycher8.webnode.fr/\">casque monster beats</a> OnHhAwZ<a href=http://casquebeatsbycher8.webnode.fr/>monster beats</a> RvNwOh http://casquebeatsbycher8.webnode.fr/ <a href=\"http://christianlouboutinfr.webnode.fr/\">christian louboutin france</a> DxOgIjI<a href=http://christianlouboutinfr.webnode.fr/>louboutin soldes</a> IqJvBz http://christianlouboutinfr.webnode.fr/',1,1348472008,'guwvphuw','casqeabdreqep@gmail.com','http://louboutinmouche.webnode.fr/','216.244.78.98',11),(61,'shahhah',2,1361976895,'yiqing','yiqing_95@qq.com','','127.0.0.1',28),(62,'hehheh',2,1368031910,'yiqing','yiqing_95@qq.com','','127.0.0.1',32),(63,'sjdfasldkfjaslkdf\r\n',2,1368032085,'yiqing','yiqing_95@qq.com','','127.0.0.1',32),(64,'sakdjf;laskdjg;ldsfg',2,1368032101,'jfdjsdklf','yiqing_95@qq.com','','127.0.0.1',32),(65,'风吹屁屁凉\r\n',2,1368032259,'yiqing','yiqing_95@qq.com','','127.0.0.1',32),(66,'评论管用哦',2,1368032733,'束带结发','yiqing_95@qq.com','','127.0.0.1',34),(67,'sadhfasldkjflsdkfgjsdfg',2,1368073962,'yiqing','yiqing_95@qq.com','','127.0.0.1',31),(68,'pppp.clasjdlkdg',2,1368079058,'dangdang','yiqing_95@qq.com','','127.0.0.1',3),(69,'aslkdjfl;asjkdf',2,1368156290,'yiqing','yiqing_95@qq.com','','127.0.0.1',41),(70,'askljdf;laksjdfk\r\n',2,1369068187,'dsfsad','yiqing_95@qq.com','','127.0.0.1',65),(71,'HEHHEH',2,1369330194,'YIQING','yiqing_95@qq.com','','127.0.0.1',65);

/*Table structure for table `blog_link` */

DROP TABLE IF EXISTS `blog_link`;

CREATE TABLE `blog_link` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '序号',
  `sitename` varchar(128) NOT NULL COMMENT '网站名称',
  `logo` varchar(128) DEFAULT NULL COMMENT '站标地址',
  `siteurl` varchar(255) NOT NULL COMMENT '网站地址',
  `description` varchar(255) DEFAULT NULL COMMENT '描述',
  `target` enum('_blank','_top','_self','_parent') DEFAULT '_blank' COMMENT '打开方式',
  `status` int(11) unsigned NOT NULL,
  `position` int(11) unsigned DEFAULT '0' COMMENT '排序序号',
  `created` int(11) unsigned DEFAULT NULL COMMENT '创建时间',
  `updated` int(11) unsigned DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `blog_link` */

/*Table structure for table `blog_lookup` */

DROP TABLE IF EXISTS `blog_lookup`;

CREATE TABLE `blog_lookup` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(128) NOT NULL,
  `code` int(11) unsigned NOT NULL,
  `type` varchar(128) NOT NULL,
  `position` int(11) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

/*Data for the table `blog_lookup` */

insert  into `blog_lookup`(`id`,`name`,`code`,`type`,`position`) values (1,'Draft',1,'PostStatus',1),(2,'Published',2,'PostStatus',2),(3,'Archived',3,'PostStatus',3),(4,'Pending Approval',1,'CommentStatus',1),(5,'Approved',2,'CommentStatus',2);

/*Table structure for table `blog_options` */

DROP TABLE IF EXISTS `blog_options`;

CREATE TABLE `blog_options` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `object_id` int(11) unsigned NOT NULL DEFAULT '0',
  `option_name` varchar(255) NOT NULL COMMENT '选项名称',
  `option_value` text NOT NULL COMMENT '值',
  PRIMARY KEY (`id`),
  KEY `option_name` (`option_name`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='选项设置表';

/*Data for the table `blog_options` */

insert  into `blog_options`(`id`,`object_id`,`option_name`,`option_value`) values (1,0,'settings','{\"site_name\":\"windsdeng\'s blog\",\"site_closed\":\"no\",\"close_information\":\"\\u7f51\\u7ad9\\u5728\\u7ef4\\u62a4\\u4e2d\\u3002<br \\/> \\u8bf7\\u7a0d\\u5019\\u8bbf\\u95ee\\u3002\",\"site_url\":\"http:\\/\\/demo.dlf5.net\\/\",\"keywords\":\"\\u9093\\u6797\\u950b\\u7684\\u535a\\u5ba2\",\"description\":\"\\u9093\\u6797\\u950b\\u7684\\u535a\\u5ba2http:\\/\\/www.dlf5.com\",\"copyright\":\"windsdeng\'s blog\",\"author\":\"winds\",\"blogdescription\":\"\\u9093\\u6797\\u950b\\u7684\\u535a\\u5ba2\",\"default_editor\":\"ueditor\",\"theme\":\"classic\",\"email\":\"winds@dlf5.com\",\"rss_output_num\":\"10\",\"rss_output_fulltext\":\"yes\",\"post_num\":\"10\",\"time_zone\":\"\\u4e0a\\u6d77\",\"icp\":\"\",\"footer_info\":\"\",\"rewrite\":\"no\",\"showScriptName\":\"false\",\"urlSuffix\":\".html\"}');

/*Table structure for table `blog_post` */

DROP TABLE IF EXISTS `blog_post`;

CREATE TABLE `blog_post` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(128) NOT NULL,
  `content` text NOT NULL,
  `summary` varchar(255) NOT NULL COMMENT '摘要',
  `tags` text,
  `status` int(11) unsigned NOT NULL,
  `created` int(11) unsigned DEFAULT '0',
  `updated` int(11) unsigned DEFAULT '0',
  `author_id` int(11) unsigned NOT NULL,
  `category_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '分类ID',
  `rep_image` varchar(255) DEFAULT NULL COMMENT '代表图 如果有的话tipical_image',
  PRIMARY KEY (`id`),
  KEY `FK_post_author` (`author_id`)
) ENGINE=MyISAM AUTO_INCREMENT=75 DEFAULT CHARSET=utf8;

/*Data for the table `blog_post` */

insert  into `blog_post`(`id`,`title`,`content`,`summary`,`tags`,`status`,`created`,`updated`,`author_id`,`category_id`,`rep_image`) values (1,'Welcome!','This blog system is developed using Yii. It is meant to demonstrate how to use Yii to build a complete real-world application. Complete source code may be found in the Yii releases.\n\nFeel free to try this system by writing new posts and posting comments.','This blog system is developed using Yii. It is meant to demonstrate how to use Yii to build a complete real-world application. Complete source code may be found in the Yii releases.','yii, blog',2,1230952187,1230952187,1,0,NULL),(72,'sdfsdf','<p>sdfsadgdfgdf<br /></p>','sdfdfgdfg','',1,1370623143,1370623143,1,9,NULL),(3,'我的博客','最新更新我的博客，新版感觉怎么样呀！','最新更新我的博客，新版感觉怎么样呀！','',2,1322064648,1322064686,1,0,NULL),(7,'ueditor-for-yii 所见即所得富文本web编辑器','<p><a style=\"text-decoration:underline;\" href=\"http://code.google.com/p/ueditor-for-yii/\"><span style=\"font-size:16px\">ueditor-for-yii</span></a></p><div id=\"psum\"><a id=\"project_summary_link\" href=\"http://code.google.com/p/ueditor-for-yii/\">Ueditor是由百度web前端研发部开发的所见即所得富文本web编辑器，开源基于BSD协议。 &nbsp; &nbsp; &nbsp; &nbsp;</a></div><div>最近看到百度开源的一个产品Ueditor 所见即所得富文本web编辑器，感觉很不错，最近又有一个项目，是用YiiFramework 开发的，就把Ueditor 用在这项目里了，于是就把它写成了extensions形式提供给大家下载！yii 地址：<a href=\"http://www.yiiframework.com/extension/ueditor-for-yii/\">http://www.yiiframework.com/extension/ueditor-for-yii/ &nbsp; &nbsp; &nbsp; &nbsp;</a></div><div>由于文件大过2M上传不了到YII官方网站的extensions库里！不一会就好几个差评了！最来就把它上传到谷歌上面了！</div><div>如果有用到的话大家拿去吧！下载地址：<a href=\"http://code.google.com/p/ueditor-for-yii/downloads/list\">http://code.google.com/p/ueditor-for-yii/downloads/list &nbsp; &nbsp; &nbsp; &nbsp;</a></div><div>使用方法：</div><p>…how to use this extension…</p><p>把ueditor插件放到 extensions/ 在Html 调用</p><pre class=\"brush:php;toolbar:false;\">&lt;?php    \r\n    $this-&gt;widget(\'ext.ueditor.Ueditor\',    \r\n            array(    \r\n                \'getId\'=&gt;\'Article_content\',    \r\n                \'textarea\'=&gt;\"Article[content]\",    \r\n                \'imagePath\'=&gt;\'/attachment/ueditor/\',    \r\n                \'UEDITOR_HOME_URL\'=&gt;\'/\',    \r\n            ));    \r\n?&gt;</pre><p>订制Toolbars 方法</p><pre class=\"brush:php;toolbar:false;\">&lt;?php    \r\n    $this-&gt;widget(\'ext.ueditor.Ueditor\',    \r\n            array(    \r\n                \'getId\'=&gt;\'Settings_about\',    \r\n                \'minFrameHeight\'=&gt;180,    \r\n                \'textarea\'=&gt;\"Article[content]\",    \r\n                \'imagePath\'=&gt;\'/attachment/ueditor/\',    \r\n                \'UEDITOR_HOME_URL\'=&gt;\'/\',    \r\n                \'toolbars\'=&gt;\"\'Undo\',\'Redo\',\'ForeColor\', \'BackColor\', \'Bold\',\'Italic\',\'Underline\', \'JustifyLeft\',\'JustifyCenter\',\'JustifyRight\', ,\'InsertImage\',\'ImageNone\',\'ImageLeft\',\'ImageRight\',\'ImageCenter\',\",    \r\n            ));    \r\n?&gt;</pre><p><br /></p><p>关于UEditor</p><p>Ueditor概述 Ueditor是由百度web前端研发部开发的所见即所得富文本web编辑器，具有轻量，可定制，注重用户体验等特点，开源基于BSD协议，允许自由使用和使用代码 为什么使用Ueditor 体积小巧，性能优良，使用简单 分层架构，方便定制与扩展 满足不同层次用户需求，更加适合团队开发 丰富完善的中文文档 多个浏览器支持：Mozilla, MSIE, FireFox?, Maxthon,Safari 和Chrome 更好的使用体验 拥有专业QA团队持续支持，已应用在百度各大产品线上</p><p><br /></p>','Ueditor是由百度web前端研发部开发的所见即所得富文本web编辑器，开源基于BSD协议。</a></div><div>最近看到百度开源的一个产品Ueditor 所见即所得富文本web编辑器，感觉很不错，最近又有一个项目，是用YiiFramework 开发的，就把Ueditor 用在这项目里了，于是就把它写成了extensions形式提供给大家下载！','UEditor',2,1322404562,1342545530,1,1,NULL),(8,'Your title here','break-all','Your title here','yii',2,1322580959,1342664214,1,1,NULL),(24,'新增文章归档功能','<p>新增文章归档功能<br /></p><p><span style=\"color:#ffffff;font-family:&#39;helvetica neue&#39;, helvetica, arial, sans-serif;font-size:18px;font-weight:bold;line-height:18px;background-color:#1187dc;\">Archives</span><br /></p>','新增文章归档功能','Archives,文章归档',2,1347253302,1347253302,1,1,NULL),(9,'Your Code here','Your&nbsp;title&nbsp;here...\r\n==================Y\r\nour&nbsp;title&nbsp;here...\r\n------------------\r\n###&nbsp;Your&nbsp;title&nbsp;here...\r\n###&nbsp;Your&nbsp;title&nbsp;here...\r\n#####&nbsp;Your&nbsp;title&nbsp;here...\r\n######&nbsp;Your&nbsp;title&nbsp;here...\r\n~~~\r\n[php]\r\nYour&nbsp;Code&nbsp;here...\r\n~~~','Your Code here','Your Code here',2,1323511144,1342663980,1,1,NULL),(11,'标题 cannot be blank. ','Your title here...\r\n==================\r\nYour title here...\r\n------------------\r\n### Your title here...\r\n#### Your title here...\r\n##### Your title here...\r\n###### Your title here...\r\n\r\n','','',2,1323705679,1323705679,1,0,NULL),(12,'这在测试中','<p id=\"initContent\">这在测试中</p><p id=\"initContent\">这在测试中<br /></p><ol style=\"list-style-type:decimal;\"><li><p id=\"initContent\">这在测试中</p></li><li><p id=\"initContent\">这在测试中</p></li><li><p id=\"initContent\">这在测试中</p></li><li><p id=\"initContent\"><span>这在测试中</span><br /></p></li></ol>','test\r\n','test',2,1342540719,1342593593,1,1,NULL),(13,'我要做测试','<p id=\"initContent\">我要做测试<br /></p><p><strong>我要做测试</strong><br /></p><ol style=\"list-style-type:decimal;\"><li><p><strong>我要做测试</strong></p></li><li><p><strong>我要做测试</strong></p></li><li><p><strong>我要做测试</strong></p></li><li><p><strong>我要做测试<br /></strong></p></li></ol><p><br /></p>','test','test',2,1342540786,1342593609,1,1,NULL),(14,'ueditor-for-yii ','<p>it is code </p><p><span style=\"color:#222222;font-family:arial, sans-serif;font-size:13px;font-style:italic;line-height:19px;background-color:#ffffff;\">Ueditor是由百度web前端研发部开发的所见即所得富文本web编辑器，开源基于BSD协议。</span><br /></p><pre class=\"brush:php;toolbar:false;\">&lt;?php\r\n    $this-&gt;widget(\'ext.ueditor.Ueditor\',\r\n            array(\r\n                \'getId\'=&gt;\'Post_content\',\r\n                \'UEDITOR_HOME_URL\'=&gt;\"/\",\r\n                \'options\'=&gt;\'toolbars:[[\"fontfamily\",\"fontsize\",\"forecolor\",\"bold\",\"italic\",\"strikethrough\",\"|\",\r\n\"insertunorderedlist\",\"insertorderedlist\",\"blockquote\",\"|\",\r\n\"link\",\"unlink\",\"highlightcode\",\"|\",\"undo\",\"redo\",\"source\"]],\r\n                    wordCount:false,\r\n                    elementPathEnabled:false,\r\n                    imagePath:\"/attachment/ueditor/\",\r\n                    \',\r\n            ));\r\n?&gt;</pre><p><br /></p>','Ueditor是由百度web前端研发部开发的所见即所得富文本web编辑器，开源基于BSD协议。','php',2,1342541883,1342606184,1,1,NULL),(17,'php递归实现99乘法表','<p>代码如下：</p><p><span style=\"color:#333333;font-family:georgia, &#39;bitstream charter&#39;, serif;line-height:24px;background-color:#ffffff;\"></span></p><pre class=\"brush:php;toolbar:false;\">&lt;?php\r\nfunction _99 ($n) {\r\nfor ($i=1;$i&lt;=$n;$i++) {\r\necho $i.’*’.$n.’=’.$n*$i.’&amp;nbsp;’;\r\n}\r\necho ‘&lt;br/&gt;’;\r\n$pre = $n – 1;\r\nif ($pre &lt; $n &amp;&amp; $pre &gt; 0) {\r\n_99 ($pre);\r\n}   \r\n}\r\n_99 (9);  \r\n?&gt;</pre><p><span style=\"color:#333333;font-family:georgia, bitstream charter, serif;\"><span style=\"line-height:24px;\"><span style=\"color:#333333;font-family:georgia, &#39;bitstream charter&#39;, serif;line-height:24px;background-color:#ffffff;\">结果如下：</span><br /></span></span></p><p><span style=\"color:#333333;font-family:georgia, bitstream charter, serif;\"><span style=\"line-height:24px;\"><span style=\"color:#333333;font-family:georgia, &#39;bitstream charter&#39;, serif;line-height:24px;background-color:#ffffff;\"><br /></span></span></span></p><blockquote><p><span style=\"color:#333333;font-family:georgia, bitstream charter, serif;\"><span style=\"line-height:24px;\"><span style=\"color:#333333;font-family:georgia, &#39;bitstream charter&#39;, serif;line-height:24px;background-color:#ffffff;\"><span style=\"color:#333333;font-family:georgia, &#39;bitstream charter&#39;, serif;line-height:24px;background-color:#ffffff;\">1*9=9 2*9=18 3*9=27 4*9=36 5*9=45 6*9=54 7*9=63 8*9=72 9*9=81</span><br style=\"color:#333333;font-family:georgia, &#39;bitstream charter&#39;, serif;line-height:24px;background-color:#ffffff;\" /><span style=\"color:#333333;font-family:georgia, &#39;bitstream charter&#39;, serif;line-height:24px;background-color:#ffffff;\">1*8=8 2*8=16 3*8=24 4*8=32 5*8=40 6*8=48 7*8=56 8*8=64</span><br style=\"color:#333333;font-family:georgia, &#39;bitstream charter&#39;, serif;line-height:24px;background-color:#ffffff;\" /><span style=\"color:#333333;font-family:georgia, &#39;bitstream charter&#39;, serif;line-height:24px;background-color:#ffffff;\">1*7=7 2*7=14 3*7=21 4*7=28 5</span><br /></span></span></span></p></blockquote><p><span style=\"color:#333333;font-family:georgia, &#39;bitstream charter&#39;, serif;line-height:24px;background-color:#ffffff;\"><br /></span></p>','代码如下：','php',2,1342606433,1342606475,1,2,NULL),(18,'Yii在Nginx下的rewrite配置','<p><span style=\"color:#333333;font-family:georgia, &#39;bitstream charter&#39;, serif;line-height:24px;background-color:#ffffff;\">1. Nginx配置</span><br style=\"color:#333333;font-family:georgia, &#39;bitstream charter&#39;, serif;line-height:24px;background-color:#ffffff;\" /><span style=\"color:#333333;font-family:georgia, &#39;bitstream charter&#39;, serif;line-height:24px;background-color:#ffffff;\">在nginx.conf的server {段添加类似如下代码：</span><br style=\"color:#333333;font-family:georgia, &#39;bitstream charter&#39;, serif;line-height:24px;background-color:#ffffff;\" /><span style=\"color:#333333;font-family:georgia, &#39;bitstream charter&#39;, serif;line-height:24px;background-color:#ffffff;\">Nginx.conf代码:</span><br style=\"color:#333333;font-family:georgia, &#39;bitstream charter&#39;, serif;line-height:24px;background-color:#ffffff;\" /><font color=\"#333333\" face=\"monaco, consolas, andale mono, dejavu sans mono, monospace\" size=\"2\"><span style=\"line-height:24px;\"></span></font></p><pre class=\"brush:bash;toolbar:false;\">location / {\r\nif (!-e $request_filename){\r\nrewrite ^/(.*) /index.php last;\r\n}\r\n}</pre><p><font color=\"#333333\" face=\"monaco, consolas, andale mono, dejavu sans mono, monospace\" size=\"2\"><span style=\"line-height:24px;\"><br style=\"background-color:#FFFFFF;\" /></span></font><span style=\"color:#333333;font-family:georgia, &#39;bitstream charter&#39;, serif;line-height:24px;background-color:#ffffff;\">2. 在Yii的protected/conf/main.php去掉如下的注释</span><br style=\"color:#333333;font-family:georgia, &#39;bitstream charter&#39;, serif;line-height:24px;background-color:#ffffff;\" /><span style=\"color:#333333;font-family:georgia, &#39;bitstream charter&#39;, serif;line-height:24px;background-color:#ffffff;\">Php代码:</span><br style=\"color:#333333;font-family:georgia, &#39;bitstream charter&#39;, serif;line-height:24px;background-color:#ffffff;\" /><font color=\"#333333\" face=\"monaco, consolas, andale mono, dejavu sans mono, monospace\" size=\"2\"><span style=\"line-height:24px;\"></span></font></p><pre class=\"brush:php;toolbar:false;\">\'urlManager\'=&gt;array(\r\n\'urlFormat\'=&gt;\'path\',\r\n\'rules\'=&gt;array(\r\n\'/\'=&gt;\'/view\',\r\n\'//\'=&gt;\'/\',\r\n\'/\'=&gt;\'/\',\r\n),\r\n),</pre><p><font color=\"#333333\" face=\"monaco, consolas, andale mono, dejavu sans mono, monospace\" size=\"2\"><span style=\"line-height:24px;\"><br /></span></font></p>','Nginx配置','yii',2,1342606936,1342606936,1,1,NULL),(25,'钓鱼岛是中国的','<h2 style=\"margin:0px;color:#555555;font-family:arial, helvetica, sans-serif;background-color:#ffffff;padding-bottom:12px;\">钓鱼岛是中国的</h2><h2 style=\"margin:0px;color:#555555;font-family:arial, helvetica, sans-serif;background-color:#ffffff;padding-bottom:12px;\">钓鱼岛是中国的</h2><h2 style=\"margin:0px;color:#555555;font-family:arial, helvetica, sans-serif;background-color:#ffffff;padding-bottom:12px;\">钓鱼岛是中国的</h2><h2 style=\"margin:0px;color:#555555;font-family:arial, helvetica, sans-serif;background-color:#ffffff;padding-bottom:12px;\">钓鱼岛是中国的</h2><p><br /><br /></p><p><br /></p>','钓鱼岛是中国的','钓鱼岛',2,1347357597,1347357597,1,1,NULL),(26,'新增皮肤功能','<p>新增皮肤功能</p><p>修改<br />在config/main.php</p><pre class=\"brush:php;toolbar:false;\">\'theme\'=&gt;\'classic\',     //皮肤配置 default为默认或注释掉</pre><p>欢迎大家下载学习。</p><p><a href=\"https://github.com/windsdeng/dlfblog\">https://github.com/windsdeng/dlfblog</a></p><p><br /></p><h2><a name=\"qq交流群\" class=\"anchor\" href=\"https://github.com/windsdeng/dlfblog#qq%E4%BA%A4%E6%B5%81%E7%BE%A4\"></a>QQ交流群</h2><p><code>1、185207750</code></p><p><br /></p><p>有什么建议可以提出来</p><p>所有功能都先是架起一个大至的框架，到时慢慢细致。<br /></p>','新增皮肤功能\r\n有什么建议可以提出来','classic,theme',2,1348392308,1348398138,1,1,NULL),(27,'sdfsdf','<p>sdsfsdgdfg<br /></p>','sdfsdgdfg','',1,1357115018,1369330667,1,11,NULL),(28,'edfsdfgsdg','<p>fdghfghfghfdg<br /></p>','dsfgdfgfdgh','',2,1357143919,1357143919,1,1,NULL),(29,'hello','<p>sdfdsfgdfgdsfg<br /></p>','dsfsdf','',2,1357223912,1357223912,3,1,NULL),(30,'fgdg','<p>哈哈哈哈哈哈<br /></p>','测试啦','',1,1367983100,1367983100,3,1,NULL),(31,'testByYiqing95 2','<p>heeheheh<br /></p>','sdsdg','Archives, hello,yiqing',1,1367993234,1367993234,3,1,NULL),(32,'shenme a ','<p>sdfsdf<br /></p>','dsfgdfg','dfgd,test,hello',1,1367994113,1367994113,3,1,NULL),(33,'shehh','<p>thissssssssssssssssssssssssssssssssssssssssssssssssssssssss<br /></p><p>thissssssssssssssssssssssssssssssssssssssssssssssssssssssss<br /></p><p>thissssssssssssssssssssssssssssssssssssssssssssssssssssssss<br /></p><p>thissssssssssssssssssssssssssssssssssssssssssssssssssssssss<br /></p><p><br /></p>','蛋疼的啊','',1,1368031991,1368031991,3,2,NULL),(34,'刀剑如梦=345','<p>到房管局劳动法看几个看 &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;<br /></p>','生','',1,1368032688,1368032688,3,1,NULL),(35,'teww','<p>sdfsdfg<br /></p>','sdgdfg','',1,1368116939,1368116939,2,1,NULL),(36,'sdfsdf','<p>sdfsdgdf<br /></p>','sdgdfg','',1,1368117488,1368117488,2,1,NULL),(37,'电饭锅','<p>是打发第三方<br /></p>','加加减减','',1,1368150942,1368150942,2,1,NULL),(38,'蛇呵呵呵','<p>sd敢达双方各电饭锅<br /></p>','是打发第三方给','',1,1368153754,1368153754,2,6,NULL),(39,'撒剪短发卡萨丁','<p>送电饭锅第三方给<br /></p>','撒旦个的双方各','',1,1368154967,1368154967,2,6,NULL),(40,'说地方撒旦','<p>撒旦嘎斯电饭锅<br /></p>','撒电饭锅','',1,1368155244,1368155244,2,0,NULL),(41,'是大家发顺丰','<p>阿斯顿个飞洒 &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; <br /></p>','阿斯顿个第三个','',1,1368155651,1368155651,2,0,NULL),(42,'说地方撒旦','<p>撒旦郭殿方<br /></p>','多个','',1,1368156337,1368156337,1,9,NULL),(43,'sdfsajdkfjk','<p>sdfsdgdfg<br /></p>','dfgdsfgdsf','',2,1368160052,1368160052,2,0,NULL),(44,'收到发送到','<p>撒打发<br /></p>','是打发斯蒂芬','',1,1368294485,1368294485,3,12,NULL),(45,'说地方撒旦','<p>撒旦嘎斯电饭锅 <br /></p>','撒旦法撒旦个','',2,1368294572,1368294572,3,12,NULL),(46,'收到发送到','<p>但神风怪盗东方红<br /></p>','撒大幅度','',1,1368326791,1368326791,2,29,NULL),(47,'的所发生的','<p>随碟附送大概电饭锅<br /></p>','斯蒂芬','',1,1368363029,1368363722,1,11,NULL),(48,'收到发送到','<p>送大幅度覆盖电饭锅<br /></p>','速度飞洒地方给','',1,1368687666,1368687666,3,31,NULL),(49,'uuuu','<p>jhlkjhlkjhlkjhkl<br /></p>','bgfjhgfhjgfhjgfjhgfjhg','',1,1368719504,1368719504,2,6,NULL),(50,'sadfsdfgdsf','<p>asgdsfgdsfhg<br /></p>','dfhfgh','',1,1368757048,1368757048,2,6,NULL),(51,'sdfasd','<p>sadgdsfg<br /></p>','sdfgdsfhg','',1,1368758141,1368758141,2,6,NULL),(52,'sadffsdfg','<p>sadgsdfhg<br /></p>','sadgdfgdfg','',1,1368759650,1368759650,2,17,NULL),(53,'sdfsadg','<p>dfgdsfghdsfh<br /></p>','sdfgdfh','',1,1368759738,1368759738,2,6,NULL),(54,'sdgdsafg','<p>dfhdsfhfgh<br /></p>','asdfgfgh','',1,1368759794,1368759794,2,6,NULL),(55,'认真的标题啊','<p>撒打发呵呵呵呵<br /></p>','省心 吧','',1,1368759836,1368759836,2,6,NULL),(56,'撒旦法撒旦个是','<p>撒旦郭殿方<br /></p>','撒旦郭殿方 ','',1,1368761209,1368761209,2,6,NULL),(57,'什么情况','<p>哈哈水电费和<br /></p>','撒旦法撒旦个','',1,1368761305,1368761305,3,12,NULL),(58,'急急急','<p>与图腾<br /></p>','快交换机','',1,1368762129,1368762129,1,9,NULL),(59,'撒打发','<p>撒旦发送到</p>','撒打发','',1,1368765491,1368765491,1,9,NULL),(60,'速度飞洒地方','<p>阿斯顿嘎斯<br /></p>','说但发鬼地方','',1,1368766172,1368766172,1,9,NULL),(61,'撒旦个电饭锅','<p>的发送到</p>','电饭锅','',1,1368766643,1368766643,1,9,NULL),(62,'是打发斯蒂芬','<p>是打发斯蒂芬<br /></p>','十多个','',1,1368769360,1368769360,1,9,NULL),(63,'撒旦发送到','<p>撒旦发送到<br /></p>','撒旦发送到','',1,1368769702,1368769702,1,9,NULL),(64,'sdfsadg','<p>sdfgdfghdfhfgh<br /></p>','sdfsadg','',1,1368896342,1368896342,1,9,NULL),(65,'撒旦发送到','<p>撒打发送电饭锅第三方给电饭锅<br /></p>','打底衫','',1,1368981449,1368981449,3,12,NULL),(66,'速度飞洒地方','<p>说地方撒旦<br /></p>','盛大富翁 ','',1,1369240440,1369240441,1,9,NULL),(67,'说地方撒旦','<p>说地方撒旦<br /></p>','随碟附送','',1,1369240535,1369240535,1,9,NULL),(68,'说地方撒旦','<p>个发速度飞洒地方<br /></p>','说地方撒旦','',1,1369240637,1369240637,1,9,NULL),(69,'说地方撒旦','<p>说地方撒旦速度飞洒地方<br /></p>','随碟附送','',1,1369240938,1369241812,1,9,NULL),(70,'tewst','<p>sdgdfg<br /></p>','sdgdfg','',1,1369323185,1369323185,1,9,NULL),(71,'标题','<p>撒的发生连快递费<br /></p>','撒打发','yii, pp,单独',1,1369330770,1369330770,1,32,NULL);

/*Table structure for table `blog_recommend` */

DROP TABLE IF EXISTS `blog_recommend`;

CREATE TABLE `blog_recommend` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL COMMENT '推荐者',
  `object_id` int(11) NOT NULL COMMENT '推荐的目标对象',
  `grade` tinyint(3) NOT NULL DEFAULT '0' COMMENT '推荐的等级 可用来排序',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=utf8;

/*Data for the table `blog_recommend` */

insert  into `blog_recommend`(`id`,`user_id`,`object_id`,`grade`) values (25,6,72,0),(26,6,71,4);

/*Table structure for table `blog_sys_category` */

DROP TABLE IF EXISTS `blog_sys_category`;

CREATE TABLE `blog_sys_category` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(60) NOT NULL,
  `position` tinyint(3) NOT NULL DEFAULT '0',
  `enable` tinyint(3) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

/*Data for the table `blog_sys_category` */

insert  into `blog_sys_category`(`id`,`name`,`position`,`enable`) values (1,'改动下',3,1),(2,'牛叉啊',2,1),(3,'test',0,1);

/*Table structure for table `blog_sys_category2post` */

DROP TABLE IF EXISTS `blog_sys_category2post`;

CREATE TABLE `blog_sys_category2post` (
  `post_id` int(10) unsigned NOT NULL,
  `sys_cate_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`post_id`,`sys_cate_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `blog_sys_category2post` */

insert  into `blog_sys_category2post`(`post_id`,`sys_cate_id`) values (27,2),(27,3),(69,3),(70,3),(71,2),(71,3),(72,2),(72,3),(73,3),(74,1),(74,2);

/*Table structure for table `blog_tag` */

DROP TABLE IF EXISTS `blog_tag`;

CREATE TABLE `blog_tag` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(128) NOT NULL,
  `frequency` int(11) unsigned DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=21 DEFAULT CHARSET=utf8;

/*Data for the table `blog_tag` */

insert  into `blog_tag`(`id`,`name`,`frequency`) values (1,'yii',4),(2,'blog',1),(3,'test',3),(6,'UEditor',1),(7,'php',2),(14,'classic',1),(11,'Archives',2),(12,'文章归档',1),(13,'钓鱼岛',1),(15,'theme',1),(16,'hello',2),(17,'yiqing',1),(18,'dfgd',1),(19,'pp',1),(20,'单独',1);

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
) ENGINE=MyISAM AUTO_INCREMENT=19 DEFAULT CHARSET=utf8;

/*Data for the table `msg` */

insert  into `msg`(`id`,`sender`,`recipient`,`sent`,`read`,`subject`,`message`) values (12,1,2,'2012-08-14 23:16:14',1,'斯蒂芬','斯蒂芬'),(13,1,2,'2012-08-14 23:18:03',1,'速度飞洒地方','斯蒂芬 '),(14,1,2,'2012-08-14 23:18:03',1,'速度飞洒地方','斯蒂芬 '),(15,1,2,'2012-08-14 23:20:40',1,'中文还是可以的啦','吼吼吼吼吼'),(16,1,1,'2012-08-14 23:23:41',1,'收到发送到','地方规定发给'),(17,1,3,'2012-08-18 16:28:57',0,'dfgdsf','sdfsdfs'),(18,1,2,'2012-11-09 13:04:20',0,'sdfsdf','dfgdfgdfgdf');

/*Table structure for table `news_category` */

DROP TABLE IF EXISTS `news_category`;

CREATE TABLE `news_category` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `root` int(10) unsigned DEFAULT NULL,
  `lft` int(10) unsigned NOT NULL,
  `rgt` int(10) unsigned NOT NULL,
  `level` smallint(5) unsigned NOT NULL,
  `name` varchar(255) NOT NULL,
  `enable` tinyint(1) NOT NULL DEFAULT '1',
  `group_code` varchar(100) NOT NULL DEFAULT 'page' COMMENT '归类码表示用途的 也可以用来表示位置 一般只需要标记根的用途即可 也可以考虑用eav 但考虑到查询问题 所以引入了此字段',
  `mbr_count` int(10) DEFAULT '0' COMMENT '下面新闻的数量 节点数量',
  `link_to` varchar(60) DEFAULT 'route' COMMENT '如果是树的叶子那么链接到（page,pageList,route）',
  PRIMARY KEY (`id`),
  KEY `root` (`root`),
  KEY `lft` (`lft`),
  KEY `rgt` (`rgt`),
  KEY `level` (`level`)
) ENGINE=InnoDB AUTO_INCREMENT=41 DEFAULT CHARSET=utf8 COMMENT='用nestedset保存树关系';

/*Data for the table `news_category` */

insert  into `news_category`(`id`,`root`,`lft`,`rgt`,`level`,`name`,`enable`,`group_code`,`mbr_count`,`link_to`) values (33,33,1,14,1,'顶级虚拟分类',1,'news_cate',0,'route'),(34,33,2,3,2,'sub-测试分类1',1,'news_cate',0,'route'),(35,33,4,13,2,'sub-测试分类2',1,'news_cate',0,'route'),(37,33,5,10,3,'测试分类4',1,'news_cate',0,'route'),(38,33,11,12,3,'测试分类5',1,'news_cate',0,'route'),(39,33,6,9,4,'ppxia',1,'news_cate',0,'route'),(40,33,7,8,5,'ppxiaxia',1,'news_cate',0,'route');

/*Table structure for table `news_entry` */

DROP TABLE IF EXISTS `news_entry`;

CREATE TABLE `news_entry` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `creator` int(11) NOT NULL DEFAULT '0' COMMENT '新闻创建者',
  `cate_id` int(11) NOT NULL DEFAULT '0' COMMENT '新闻分类id',
  `title` varchar(255) NOT NULL COMMENT '新闻标题',
  `order` tinyint(3) NOT NULL DEFAULT '0' COMMENT '显示顺序 除了按时间还可按此字段',
  `deleted` tinyint(1) NOT NULL DEFAULT '0' COMMENT '当新闻类别删除时 置此字段为1',
  `create_time` int(11) NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8;

/*Data for the table `news_entry` */

insert  into `news_entry`(`id`,`creator`,`cate_id`,`title`,`order`,`deleted`,`create_time`) values (6,6,40,'呵呵呵呵呵呵呵呵',0,0,1365318729),(8,6,40,'sdfsadf',0,0,1365675245),(11,6,37,'快快快快la ',0,0,1365863344),(12,1,0,'痴痴缠缠',0,0,1366003507);

/*Table structure for table `news_post` */

DROP TABLE IF EXISTS `news_post`;

CREATE TABLE `news_post` (
  `nid` int(10) unsigned NOT NULL COMMENT '关联的新闻条目id',
  `content` text NOT NULL COMMENT '新闻内容这里',
  `keywords` varchar(100) DEFAULT NULL COMMENT '关键字 手动还是自动提取',
  `meta_title` varchar(255) DEFAULT NULL,
  `meta_description` varchar(255) DEFAULT NULL,
  `meta_keywords` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`nid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `news_post` */

insert  into `news_post`(`nid`,`content`,`keywords`,`meta_title`,`meta_description`,`meta_keywords`) values (6,'ujjkljhhhhghhjhjhj\r\n',NULL,NULL,NULL,NULL),(8,'<p>asdfasdgsdfgdsfgdf<br></p>',NULL,NULL,NULL,NULL),(11,'<p>一月又一月<br></p>',NULL,NULL,NULL,NULL);

/*Table structure for table `notice_category` */

DROP TABLE IF EXISTS `notice_category`;

CREATE TABLE `notice_category` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(120) NOT NULL,
  `order` tinyint(3) NOT NULL DEFAULT '0',
  `enable` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

/*Data for the table `notice_category` */

insert  into `notice_category`(`id`,`name`,`order`,`enable`) values (2,'notice2',2,1),(3,'ddd',5,1),(4,'yes',6,1),(5,'heee',1,1);

/*Table structure for table `notice_post` */

DROP TABLE IF EXISTS `notice_post`;

CREATE TABLE `notice_post` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `cate_id` int(11) NOT NULL,
  `title` varchar(120) NOT NULL,
  `content` text NOT NULL,
  `create_time` int(11) NOT NULL,
  `creator_id` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

/*Data for the table `notice_post` */

insert  into `notice_post`(`id`,`cate_id`,`title`,`content`,`create_time`,`creator_id`) values (2,2,'sdfasdg','dfgdsfg',1365995585,0),(3,2,'sadfasdg','asdgsdfgdsfhg',1365995677,6),(4,2,'sdfgdf','dfgdfg',1365995788,6);

/*Table structure for table `photo` */

DROP TABLE IF EXISTS `photo`;

CREATE TABLE `photo` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned DEFAULT NULL,
  `album_id` int(10) NOT NULL DEFAULT '0',
  `title` varchar(255) DEFAULT '',
  `desc` text NOT NULL,
  `path` varchar(255) NOT NULL DEFAULT '' COMMENT 'resized max deminsion for display',
  `orig_path` varchar(255) NOT NULL DEFAULT '' COMMENT 'original uploaded image',
  `ext` varchar(4) NOT NULL DEFAULT '',
  `size` varchar(10) DEFAULT '',
  `tags` varchar(255) NOT NULL DEFAULT '',
  `create_time` int(11) NOT NULL DEFAULT '0',
  `views` int(11) DEFAULT '0',
  `rate` float NOT NULL DEFAULT '0',
  `rate_count` int(11) NOT NULL DEFAULT '0',
  `cmt_count` int(11) NOT NULL DEFAULT '0',
  `is_featured` tinyint(4) NOT NULL DEFAULT '0',
  `status` enum('approved','disapproved','pending') NOT NULL DEFAULT 'pending',
  `hash` varchar(32) NOT NULL DEFAULT '',
  `categories` text NOT NULL,
  `up_votes` int(11) NOT NULL DEFAULT '0',
  `down_votes` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `Hash` (`hash`),
  KEY `Owner` (`uid`),
  KEY `Date` (`create_time`),
  FULLTEXT KEY `ftMain` (`title`,`tags`,`desc`,`categories`),
  FULLTEXT KEY `ftTags` (`tags`),
  FULLTEXT KEY `ftCategories` (`categories`)
) ENGINE=MyISAM AUTO_INCREMENT=29 DEFAULT CHARSET=utf8;

/*Data for the table `photo` */

insert  into `photo`(`id`,`uid`,`album_id`,`title`,`desc`,`path`,`orig_path`,`ext`,`size`,`tags`,`create_time`,`views`,`rate`,`rate_count`,`cmt_count`,`is_featured`,`status`,`hash`,`categories`,`up_votes`,`down_votes`) values (7,1,17,NULL,'','uploads/photo/14b78418_1_0580202001354080827.gif','uploads/photo/14b78418_1_0572068001354080827.gif','',NULL,'',0,8,4.1667,6,0,0,'pending','150ea03ec2e732c40cc6f2d67e7b738f','',1,4),(6,1,17,NULL,'','uploads/photo/14b78418_1_0536732001354080827.jpg','uploads/photo/14b78418_1_0354406001354080827.jpg','',NULL,'',0,4,4,5,0,0,'pending','4fdd716936ea044df6bfb7f7fd1b5f4d','',5,1),(5,1,17,NULL,'','uploads/photo/14b78418_1_0186442001354080827.jpg','uploads/photo/14b78418_1_0904550001354080826.jpg','',NULL,'',0,4,3.3333,3,0,0,'pending','889db8de76d6801e6d54d038e265e85b','',1,0),(8,1,14,'klll','','uploads/photo/14b78418_1_0149329001354082256.jpg','uploads/photo/14b78418_1_0967305001354082255.jpg','',NULL,'',0,8,3,1,0,0,'pending','7b906bf144e9780e836e4ff7790c7f33','',2,2),(9,1,18,NULL,'','uploads/photo/14b78418_1_0469778001354083471.gif','uploads/photo/14b78418_1_0434331001354083471.gif','',NULL,'',0,6,4,2,0,0,'pending','35f235aec029932f729686997bb56282','',0,0),(10,1,18,'sdf','','uploads/photo/14b78418_1_0695832001354115228.jpg','uploads/photo/14b78418_1_0415269001354115215.jpg','',NULL,'',0,12,5,3,0,0,'pending','f140b59579d9a0e37e2635250d13d1e6','',0,0),(11,1,14,'dsfsdfdf','','uploads/photo/14b78418_1_0338850001354202427.gif','uploads/photo/14b78418_1_0779175001354202426.gif','',NULL,'',0,3,5,1,0,0,'pending','99f201acbb1706cf46e2600716c14255','',0,0),(12,1,14,'dsfsdfdf','','uploads/photo/14b78418_1_0786619001354202427.gif','uploads/photo/14b78418_1_0774837001354202427.gif','',NULL,'',0,0,3,1,0,0,'pending','8e80c03905ece04f05d5d0643865acbe','',0,0),(13,1,14,'dsfsdfdf','','uploads/photo/14b78418_1_0846882001354202427.jpg','uploads/photo/14b78418_1_0795038001354202427.jpg','',NULL,'',0,1,5,1,0,0,'pending','c866ab522343b1f80d153283d1d14846','',0,0),(14,2,19,'yteen','','uploads/photo/14b78418_2_0576444001354434888.jpg','uploads/photo/14b78418_2_0216119001354434888.jpg','',NULL,'',0,102,5,3,0,0,'pending','d7f69143f8e937b31fc85c42414bc980','',1,0),(15,2,19,'yteen','','uploads/photo/14b78418_2_0903833001354434888.jpg','uploads/photo/14b78418_2_0862540001354434888.jpg','',NULL,'',0,55,4,4,0,0,'pending','3f4148cf362badc0c9b6ec71fc3b84e3','',1,0),(16,2,20,'fgdfg','','uploads/photo/14b78418_2_0620196001354435271.gif','uploads/photo/14b78418_2_0436555001354435271.gif','',NULL,'',0,0,3.6667,3,0,0,'pending','fe8c3a4a952e2c6a5e17dca4046d1ab2','',0,0),(17,2,20,'fgdfg','','uploads/photo/14b78418_2_0706584001354435271.jpg','uploads/photo/14b78418_2_0662943001354435271.jpg','',NULL,'',0,16,3.5,2,0,0,'pending','c6d4f4aeb8077329252f303218bff299','',0,0),(18,1,14,'dsfsdf','','uploads/photo/14b78418_1_0165501001354603881.gif','uploads/photo/14b78418_1_0000093001354603879.gif','',NULL,'',0,5,3,2,0,0,'pending','84efd6df578978564df480a70eecf953','',0,0),(19,1,17,'dd','','uploads/photo/14b78418_1_0874141001354718058.gif','uploads/photo/14b78418_1_0783212001354718057.gif','',NULL,'',0,20,4,2,0,0,'pending','99a7ad42c41ba8d70145cbbea343292f','',0,1),(20,1,18,'的','','uploads/photo/14b78418_1_0268352001355038984.jpg','uploads/photo/14b78418_1_0375692001355038981.jpg','',NULL,'',0,8,5,1,0,0,'pending','5aecb131e0487aea7445d233545781e4','',0,0),(21,1,18,'的','','uploads/photo/14b78418_1_0455574001355038989.jpg','uploads/photo/14b78418_1_0333254001355038989.jpg','',NULL,'',0,7,4.5,2,0,0,'pending','eb2c823e25120f00f269899aec06129d','',0,0),(22,2,20,'efsd','','uploads/photo/14b78418_2_0833420001355195632.jpg','uploads/photo/14b78418_2_0130276001355195632.jpg','',NULL,'',0,53,3,1,0,0,'pending','0a633f7e25c511121b9f3b6de9495403','',1,0),(23,2,20,'efsd','','uploads/photo/14b78418_2_0297742001355195633.jpg','uploads/photo/14b78418_2_0243559001355195633.jpg','',NULL,'',0,57,3,1,0,0,'pending','0133dc56f63bbf39625d88b51764f74c','',0,0),(24,1,22,'sdgsdf','','uploads/photo/14b78418_1_0573148001356329979.jpg','uploads/photo/14b78418_1_0148744001356329979.jpg','',NULL,'',0,7,5,2,0,0,'pending','91ac43893c0e92db9e9bb6628115a9e7','',2,0),(25,2,19,NULL,'','uploads/photo/14b78418_2_0217468001356859521.jpg','uploads/photo/14b78418_2_0361770001356859520.jpg','',NULL,'',0,6,0,0,0,0,'pending','a1fc9aa8cf29bec3d7b61ce480827899','',1,0),(26,1,18,'dsfsdf','','uploads/photo/14b78418_1_0068947001359599793.jpg','uploads/photo/14b78418_1_0734863001359599792.jpg','',NULL,'',0,5,5,1,0,0,'pending','deba9299774bc3a0338e3c9198dc9caf','',0,0),(27,1,18,'dsfsdf','','uploads/photo/14b78418_1_0293438001359599793.jpg','uploads/photo/14b78418_1_0263305001359599793.jpg','',NULL,'',0,3,0,0,0,0,'pending','0cd16748c40c6573320e7c07ba2a0dc0','',1,0),(28,1,18,'呵呵','','uploads/photo/14b78418_1_0827872001363450391.jpg','uploads/photo/14b78418_1_0576211001363450391.jpg','',NULL,'',0,5,5,1,0,0,'pending','79aa5171a3beb012b86d5d499b5e40e4','',1,0);

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

insert  into `relationship`(`id`,`type`,`user_a`,`user_b`,`accepted`) values (1,1,1,2,1),(2,1,1,3,1),(3,1,1,1,1),(4,1,3,2,1),(5,1,3,3,1),(6,1,2,2,1);

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
  `type` varchar(120) NOT NULL COMMENT 'Reference to the status types table',
  `creator` int(11) NOT NULL COMMENT 'The ID of the poster',
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Time status was posted',
  `profile` int(11) NOT NULL COMMENT 'Profile the status was posted on',
  `approved` tinyint(1) DEFAULT '1' COMMENT 'If the status is approved or notIf the status is approved or not',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=166 DEFAULT CHARSET=utf8;

/*Data for the table `status` */

insert  into `status`(`id`,`update`,`type`,`creator`,`created`,`profile`,`approved`) values (1,'hi  welcome to my space just test','update',1,'2013-05-17 11:52:13',1,1),(2,'realy funny','update',1,'2013-05-17 11:52:13',1,1),(3,'good good study day day up','update',1,'2013-05-17 11:52:13',1,1),(4,'yes','update',1,'2013-05-17 11:52:13',1,1),(5,'说的方法斯蒂芬发','update',1,'2013-05-17 11:52:13',1,1),(6,'速度飞洒地方','update',1,'2013-05-17 11:52:13',1,1),(7,'funny video','video',1,'2013-05-17 11:54:12',1,1),(8,'asadfsdf','video',1,'2013-05-17 11:54:12',1,1),(9,'this is great framework','link',1,'2013-05-17 11:54:29',1,1),(10,'this is great framework','link',1,'2013-05-17 11:54:29',1,1),(11,'sdfsdf','image',1,'2013-05-17 11:53:24',1,1),(12,'sdfsdfsadf','image',1,'2013-05-17 11:53:24',1,1),(13,'dff','image',1,'2013-05-17 11:53:24',1,1),(14,'sdfsdf','image',1,'2013-05-17 11:53:24',1,1),(15,'呵呵呵给你这六个眼','update',1,'2013-05-17 11:52:13',2,1),(16,'sdgfdsfg','update',2,'2013-05-17 11:52:13',2,1),(17,'hehhe 给管理员试试','update',2,'2013-05-17 11:52:13',1,1),(18,'阿斯顿飞说道','image',1,'2013-05-17 11:53:24',1,1),(19,'hello  i love 有','update',1,'2013-05-17 11:52:13',2,1),(20,'ffsadfsd','update',1,'2013-05-17 11:52:13',1,1),(21,'sdfsdfsd','update',1,'2013-05-17 11:52:13',1,1),(22,'thy it','update',1,'2013-05-17 11:52:13',3,1),(23,'hehahha it works ','update',1,'2013-05-17 11:52:13',3,1),(24,'测试发送图片','image',1,'2013-05-17 11:53:24',1,1),(25,'jj pp','update',2,'2013-05-17 11:52:13',1,1),(26,'ffdgdfgdfs','update',1,'2013-05-17 11:52:13',1,1),(27,'sdfsdf','image',1,'2013-05-17 11:53:24',1,1),(28,'蛋疼的表情','update',1,'2013-05-17 11:52:13',2,1),(29,'fdgdfg','image',1,'2013-05-17 11:53:24',1,1),(30,'今天把actionFeed 算搞的差不多了 算是比较难的一个主题了','update',2,'2013-05-17 11:52:13',2,1),(31,'主要参考了这个','link',2,'2013-05-17 11:54:29',2,1),(32,'投票系统的设计：','link',1,'2013-05-17 11:54:29',1,1),(33,'投票设计','link',1,'2013-05-17 11:54:29',1,1),(34,'地发生地方','image',1,'2013-05-17 11:53:24',1,1),(35,'啊呀呀呀呀和','update',1,'2013-05-17 11:52:13',1,1),(36,'呀呀呀哟','update',1,'2013-05-17 11:52:13',1,1),(37,'地发生地方说的方法斯蒂芬','image',3,'2013-05-17 11:53:24',3,1),(38,'ekjjdsjjfjsajdf','update',1,'2013-05-17 11:52:13',1,1),(39,'dsafsdf','image',1,'2013-05-17 11:53:24',1,1),(40,'泡别人那里 发表东东去了','update',1,'2013-05-17 11:52:13',3,1),(41,'撒旦随碟附送','update',1,'2013-05-17 11:52:13',1,1),(42,'jjjjjjjjjj','update',1,'2013-05-17 11:52:13',2,1),(43,'sdfsdfsdf','update',1,'2013-05-17 11:52:13',1,1),(44,'xdgdfgetgdfsg','update',2,'2013-05-17 11:52:13',2,1),(45,'xdgdfgetgdfsg','video',2,'2013-05-17 11:54:12',2,1),(46,'wewerwer','image',2,'2013-05-17 11:53:24',2,1),(47,'收到发送到覆盖的','update',2,'2013-05-17 11:52:13',2,1),(48,'打发打发','update',2,'2013-05-17 11:52:13',2,1),(49,'家快快快','update',2,'2013-05-17 11:52:13',2,1),(50,'家快快快','image',2,'2013-05-17 11:53:24',2,1),(51,'对司法斯蒂芬电饭锅','update',2,'2013-05-17 11:52:13',2,1),(52,'对司法斯蒂芬电饭锅','image',2,'2013-05-17 11:53:24',2,1),(53,'retetert','image',1,'2013-05-17 11:53:24',1,1),(54,'asdfsdg','link',1,'2013-05-17 11:54:29',1,1),(55,'hehe  大乌龟\r\n','image',1,'2013-05-17 11:53:24',1,1),(56,'uuuuu','update',1,'2013-05-17 11:52:13',1,1),(57,'好可爱的猫咪啊','image',1,'2013-05-17 11:53:24',1,1),(58,'好可爱的猫咪啊','image',1,'2013-05-17 11:53:24',1,1),(59,'地方规定发给','update',1,'2013-05-17 11:52:13',1,1),(60,'地方规定发给送打发打发','update',1,'2013-05-17 11:52:13',1,1),(61,'helllo','update',1,'2013-05-17 11:52:13',1,1),(62,'神鼎飞丹砂','image',1,'2013-05-17 11:53:24',1,1),(63,'地方规定发给','update',1,'2013-05-17 11:52:13',1,1),(64,'电饭锅撒打发','update',1,'2013-05-17 11:52:13',1,1),(65,'电饭锅撒打发','update',1,'2013-05-17 11:52:13',1,1),(66,'电饭锅撒打发','update',1,'2013-05-17 11:52:13',1,1),(67,'好开心哦','update',1,'2013-05-17 11:52:13',1,1),(68,'美女哦','image',1,'2013-05-17 11:53:24',1,1),(69,'哈哈哈哈','link',1,'2013-05-17 11:54:29',1,1),(70,'牛X哦','update',2,'2013-05-17 11:52:13',2,1),(71,'家所发生的','update',1,'2013-05-17 11:52:13',1,1),(72,'家所发生的','link',1,'2013-05-17 11:54:29',1,1),(73,'baidu home page','link',1,'2013-05-17 11:54:29',1,1),(74,'sdfsdf','update',1,'2013-05-17 11:52:13',1,1),(75,'hao 孩子啊 ','update',3,'2013-05-17 11:52:13',3,1),(76,'速度飞洒地方给\r\n神马','update',3,'2013-05-17 11:52:13',3,1),(77,'什么情况','update',3,'2013-05-17 11:52:13',3,1),(78,'asdvdsagsadg','update',2,'2013-05-17 11:52:13',2,1),(79,'asdvdsagsadg','update',2,'2013-05-17 11:52:13',2,1),(80,'dfgdfg','update',2,'2013-05-17 11:52:13',2,1),(81,'dfgdfg','image',2,'2013-05-17 11:53:24',2,1),(82,'iii','update',3,'2013-05-17 11:52:13',3,1),(83,'{\"id\":\"51\",\"title\":\"sdfasd\",\"teaser\":\"sdfgdsfhg\"}','blog_create',2,'2013-05-17 11:54:48',2,1),(84,'{\"id\":\"52\",\"title\":\"sadffsdfg\",\"teaser\":\"sadgdfgdfg\"}','blog_create',2,'2013-05-17 11:54:48',2,1),(85,'{\"id\":\"54\",\"title\":\"sdgdsafg\",\"teaser\":\"asdfgfgh\"}','blog_create',2,'2013-05-17 11:54:48',2,1),(86,'{\"id\":\"55\",\"title\":\"\\u8ba4\\u771f\\u7684\\u6807\\u9898\\u554a\",\"teaser\":\"\\u7701\\u5fc3 \\u5427\"}','blog_create',2,'2013-05-17 11:54:48',2,1),(87,'{\"id\":\"56\",\"title\":\"\\u6492\\u65e6\\u6cd5\\u6492\\u65e6\\u4e2a\\u662f\",\"teaser\":\"\\u6492\\u65e6\\u90ed\\u6bbf\\u65b9 \"}','blog_create',2,'2013-05-17 11:54:57',2,1),(88,'说地方规定发给','update',2,'2013-05-17 11:52:13',2,1),(89,'{\"id\":\"57\",\"title\":\"\\u4ec0\\u4e48\\u60c5\\u51b5\",\"teaser\":\"\\u6492\\u65e6\\u6cd5\\u6492\\u65e6\\u4e2a\"}','blog_create',3,'2013-05-17 11:54:57',3,1),(90,'{\"id\":\"58\",\"title\":\"\\u6025\\u6025\\u6025\",\"teaser\":\"\\u5feb\\u4ea4\\u6362\\u673a\"}','blog_create',1,'2013-05-17 11:54:57',1,1),(91,'说地方撒旦','update',1,'2013-05-17 12:20:07',1,1),(92,'十多个','image',1,'2013-05-17 12:20:38',1,1),(93,'无法撒旦','image',1,'2013-05-17 12:23:51',1,1),(94,'随碟附送','image',1,'2013-05-17 12:24:38',1,1),(95,'申达股份','update',1,'2013-05-17 12:25:58',1,1),(96,'撒打发','image',1,'2013-05-17 12:27:28',1,1),(97,'收到发送到','update',1,'2013-05-17 12:30:07',1,1),(98,'发的鬼地方','update',1,'2013-05-17 12:30:17',1,1),(99,'撒打发','update',1,'2013-05-17 12:30:46',1,1),(100,'sdf ','update',1,'2013-05-17 12:31:22',1,1),(101,'sdf','update',1,'2013-05-17 12:32:25',1,1),(102,'斯蒂芬','update',1,'2013-05-17 12:33:02',1,1),(103,'斯蒂芬','image',1,'2013-05-17 12:33:11',1,1),(104,'sdf','update',1,'2013-05-17 12:33:28',1,1),(105,'sdfsd','update',1,'2013-05-17 12:34:01',1,1),(106,'电饭锅','update',1,'2013-05-17 12:34:44',1,1),(107,'电饭锅','image',1,'2013-05-17 12:34:58',1,1),(108,'{\"id\":\"59\",\"title\":\"\\u6492\\u6253\\u53d1\",\"teaser\":\"\\u6492\\u6253\\u53d1\"}','blog_create',1,'2013-05-17 12:46:26',1,1),(109,'{\"id\":\"60\",\"title\":\"\\u901f\\u5ea6\\u98de\\u6d12\\u5730\\u65b9\",\"teaser\":\"\\u8bf4\\u4f46\\u53d1\\u9b3c\\u5730\\u65b9\"}','blog_create',1,'2013-05-17 12:49:32',1,1),(110,'撒打发','image',1,'2013-05-17 12:49:47',1,1),(111,'家快快快快','update',1,'2013-05-17 12:53:05',1,1),(112,'急急急','image',1,'2013-05-17 12:54:33',1,1),(113,'撒旦发送到','image',1,'2013-05-17 12:54:51',1,1),(114,'斯蒂芬','image',1,'2013-05-17 12:56:30',1,1),(115,'斯蒂芬','image',1,'2013-05-17 12:56:42',1,1),(116,'斯蒂芬','update',1,'2013-05-17 12:57:01',1,1),(117,'{\"id\":\"61\",\"title\":\"\\u6492\\u65e6\\u4e2a\\u7535\\u996d\\u9505\",\"teaser\":\"\\u7535\\u996d\\u9505\"}','blog_create',1,'2013-05-17 12:57:23',1,1),(118,'{\"id\":\"62\",\"title\":\"\\u662f\\u6253\\u53d1\\u65af\\u8482\\u82ac\",\"teaser\":\"\\u5341\\u591a\\u4e2a\"}','blog_create',1,'2013-05-17 13:42:40',1,1),(119,'第三方郭殿方','image',1,'2013-05-17 13:43:35',1,1),(120,'{\"id\":\"63\",\"title\":\"\\u6492\\u65e6\\u53d1\\u9001\\u5230\",\"teaser\":\"\\u6492\\u65e6\\u53d1\\u9001\\u5230\"}','blog_create',1,'2013-05-17 13:48:22',1,1),(121,'kkk','update',1,'2013-05-17 13:56:34',1,1),(122,'kkk','update',1,'2013-05-17 13:56:40',1,1),(123,'ikkk','update',1,'2013-05-17 13:57:19',1,1),(124,'ikkk','update',1,'2013-05-17 13:57:27',1,1),(125,'ikkki快i','update',1,'2013-05-17 13:57:49',1,1),(126,'是大润发撒旦','update',1,'2013-05-17 14:02:55',1,1),(127,'是大润发撒旦撒旦发送到','update',1,'2013-05-17 14:03:04',1,1),(128,'是大润发撒旦撒旦发送到','update',1,'2013-05-17 14:03:09',1,1),(129,'是大润发撒旦撒旦发送到','update',1,'2013-05-17 14:05:24',1,1),(130,'开看看','update',1,'2013-05-17 14:06:00',1,1),(131,'撒打发v','update',1,'2013-05-17 14:08:07',1,1),(132,'撒打发v撒打发','update',1,'2013-05-17 14:08:18',1,1),(133,'uuiii','update',1,'2013-05-18 13:10:04',1,1),(134,'{\"id\":\"64\",\"title\":\"sdfsadg\",\"teaser\":\"sdfsadg\"}','blog_create',1,'2013-05-19 00:59:03',1,1),(135,'jjjjj','update',1,'2013-05-19 00:59:28',1,1),(136,'jjjjj','update',1,'2013-05-19 01:00:03',1,1),(137,'sdfsadgdfg','update',1,'2013-05-19 01:20:38',1,1),(138,'sdfsadgdfgsdfasdfg','update',1,'2013-05-19 01:20:44',1,1),(139,'sdfsadgdfgsdfasdfgsadgsdfg','update',1,'2013-05-19 01:20:48',1,1),(140,'号码','update',1,'2013-05-19 01:56:32',1,1),(141,'号码','image',1,'2013-05-19 01:56:49',1,1),(142,'jhhgjghj','update',3,'2013-05-19 10:30:31',3,1),(143,'jhhgjghj','image',3,'2013-05-19 10:30:53',3,1),(144,'但发郭德纲','update',2,'2013-05-19 10:34:26',2,1),(145,'hello 啊\r\n','update',2,'2013-05-20 00:34:09',2,1),(146,'加加减减','update',3,'2013-05-20 00:35:34',3,1),(147,'解决','image',3,'2013-05-20 00:36:31',3,1),(148,'{\"id\":\"65\",\"title\":\"\\u6492\\u65e6\\u53d1\\u9001\\u5230\",\"teaser\":\"\\u6253\\u5e95\\u886b\"}','blog_create',3,'2013-05-20 00:37:29',3,1),(149,'{\"id\":\"66\",\"title\":\"\\u901f\\u5ea6\\u98de\\u6d12\\u5730\\u65b9\",\"teaser\":\"\\u76db\\u5927\\u5bcc\\u7fc1 \"}','blog_create',1,'2013-05-23 00:34:01',1,1),(150,'{\"id\":\"67\",\"title\":\"\\u8bf4\\u5730\\u65b9\\u6492\\u65e6\",\"teaser\":\"\\u968f\\u789f\\u9644\\u9001\"}','blog_create',1,'2013-05-23 00:35:35',1,1),(151,'{\"id\":\"68\",\"title\":\"\\u8bf4\\u5730\\u65b9\\u6492\\u65e6\",\"teaser\":\"\\u8bf4\\u5730\\u65b9\\u6492\\u65e6\"}','blog_create',1,'2013-05-23 00:37:17',1,1),(152,'{\"id\":\"69\",\"title\":\"\\u8bf4\\u5730\\u65b9\\u6492\\u65e6\",\"teaser\":\"\\u968f\\u789f\\u9644\\u9001\"}','blog_create',1,'2013-05-23 00:42:18',1,1),(153,'{\"id\":\"70\",\"title\":\"tewst\",\"teaser\":\"sdgdfg\"}','blog_create',1,'2013-05-23 23:33:06',1,1),(154,'hashdhfh ','update',1,'2013-05-24 01:36:20',1,1),(155,'{\"id\":\"71\",\"title\":\"\\u6807\\u9898\",\"teaser\":\"\\u6492\\u6253\\u53d1\"}','blog_create',1,'2013-05-24 01:39:31',1,1),(156,'不错的视频介绍啊','video',1,'2013-05-24 17:09:20',1,1),(157,'不错的视频介绍啊','link',1,'2013-05-24 17:09:36',1,1),(158,'dfgdsf ','image',3,'2013-05-30 18:49:32',3,1),(159,'{\"id\":\"72\",\"title\":\"sdfsdf\",\"teaser\":\"sdfdfgdfg\"}','blog_create',1,'2013-06-08 00:39:04',1,1),(160,'sdgdsfg','image',3,'2013-06-08 00:42:35',3,1),(161,'{\"id\":\"73\",\"title\":\"niucha\",\"teaser\":\"hello\"}','blog_create',3,'2013-06-08 00:45:26',3,1),(162,'kkkkk','image',3,'2013-06-08 00:46:00',3,1),(163,'kkkkk','image',3,'2013-06-08 00:46:09',3,1),(164,'kkkkk','update',3,'2013-06-08 00:46:16',3,1),(165,'{\"id\":\"74\",\"title\":\"\\u5c71\\u6cb3\",\"teaser\":\"\\u554asd\\u6562\\u8fbe\\u662f\\u5426\"}','blog_create',1,'2013-06-08 23:02:41',1,1);

/*Table structure for table `status_image` */

DROP TABLE IF EXISTS `status_image`;

CREATE TABLE `status_image` (
  `id` int(11) NOT NULL,
  `image` varchar(255) CHARACTER SET latin1 NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `status_image` */

insert  into `status_image`(`id`,`image`) values (11,'1345007223_1345007223.jpg'),(12,'1345007278_1345007278.jpg'),(13,'1345007357_1345007357.jpg'),(14,'uploads/status/1345007605_1345007605.gif'),(18,'uploads/status/1345009466_1345009466.jpg'),(24,'uploads/status/1345112665_1345112665.jpg'),(27,'uploads/status/1345191512_1345191512.jpg'),(29,'uploads/status/1345816981_1345816981.jpg'),(34,'uploads/status/1351353539_1351353539.gif'),(37,'uploads/status/1352130465_1352130465.gif'),(39,'uploads/status/1352176959_1352176959.jpg'),(46,'uploads/status/1355562419_1355562419.jpg'),(50,'uploads/status/1356794058_1356794058.gif'),(52,'uploads/status/1356794197_1356794197.jpg'),(53,'uploads/status/1357121634_1357121634.gif'),(55,'uploads/status/1361894336_1361894336.jpg'),(57,'uploads/status/1363106979_1363106979.jpg'),(58,'uploads/status/1363107006_1363107006.jpg'),(62,'uploads/status/1367807217_1367807217.jpg'),(68,'uploads/status/1367810660_1367810660.jpg'),(81,'uploads/status/1368605088_1368605088.jpg'),(96,'uploads/status/1368764847_1368764847.jpg'),(103,'uploads/status/1368765191_1368765191.jpg'),(107,'uploads/status/1368765298_1368765298.jpg'),(110,'uploads/status/1368766187_1368766187.jpg'),(112,'uploads/status/1368766473_1368766473.jpg'),(113,'uploads/status/1368766491_1368766491.jpg'),(114,'uploads/status/1368766590_1368766590.jpg'),(115,'uploads/status/1368766602_1368766602.jpg'),(119,'uploads/status/1368769415_1368769415.jpg'),(141,'uploads/status/1368899809_1368899809.jpg'),(143,'uploads/status/1368930652_1368930652.jpg'),(147,'uploads/status/1368981391_1368981391.jpg'),(158,'uploads/status/1369910969_1369910969.jpg'),(160,'uploads/status/1370623355_1370623355.jpg'),(162,'uploads/status/1370623560_1370623560.jpg'),(163,'uploads/status/1370623569_1370623569.jpg');

/*Table structure for table `status_link` */

DROP TABLE IF EXISTS `status_link`;

CREATE TABLE `status_link` (
  `id` int(11) NOT NULL,
  `url` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `status_link` */

insert  into `status_link`(`id`,`url`,`description`) values (33,'http://stackoverflow.com/questions/3433391/how-to-restrict-user-not-to-vote-an-article-more-than-once','禁止多次投票'),(54,'',''),(69,'http://www.nofilm.cn/user/portal.html','一个yii开发的网站呢'),(72,'www.baidu.com','baidu'),(73,'http://www.baidu.com','this is baidu home page '),(157,'http://www.youtube.com/watch?v=ZqSiVFnD93U','jsonRpc');

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
  `id` varchar(120) NOT NULL COMMENT 'A machine readable name for the type, used as the file name of template bits (that is, no spaces or punctuation)',
  `type_name` varchar(25) NOT NULL COMMENT 'The name of the type of status',
  `active` tinyint(1) NOT NULL DEFAULT '1' COMMENT 'Indicates whether the status type is active or not',
  `handler` varchar(255) DEFAULT NULL COMMENT 'the handler in charge of render status for this type',
  `is_core` tinyint(2) NOT NULL DEFAULT '0' COMMENT 'is core status type ,only non-core type has handler',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='status_type_name 可以做国际化！';

/*Data for the table `status_type` */

insert  into `status_type`(`id`,`type_name`,`active`,`handler`,`is_core`) values ('blog_create','post a blog ',1,'blog.statusWall.BlogStatusHandler',0),('image','Posted an image',1,'',1),('link','Posted a link',1,'',1),('update','Changed their status to',1,'',1),('video','Uploaded a video',1,'',1);

/*Table structure for table `status_video` */

DROP TABLE IF EXISTS `status_video`;

CREATE TABLE `status_video` (
  `id` int(11) NOT NULL,
  `video_id` varchar(50) CHARACTER SET latin1 NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `status_video` */

insert  into `status_video`(`id`,`video_id`) values (156,'ZqSiVFnD93U');

/*Table structure for table `sys_album` */

DROP TABLE IF EXISTS `sys_album`;

CREATE TABLE `sys_album` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `caption` varchar(128) NOT NULL DEFAULT '',
  `Uri` varchar(255) NOT NULL DEFAULT '',
  `Location` varchar(128) NOT NULL DEFAULT '',
  `Description` varchar(255) NOT NULL DEFAULT '',
  `Type` varchar(20) NOT NULL DEFAULT '',
  `Owner` int(10) NOT NULL DEFAULT '0',
  `Status` enum('active','passive') NOT NULL DEFAULT 'active',
  `Date` int(10) NOT NULL DEFAULT '0',
  `ObjCount` int(10) NOT NULL DEFAULT '0',
  `LastObjId` int(10) NOT NULL DEFAULT '0',
  `AllowAlbumView` int(10) NOT NULL DEFAULT '3',
  PRIMARY KEY (`id`),
  UNIQUE KEY `Uri` (`Uri`,`Type`,`Owner`),
  KEY `Owner` (`Owner`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `sys_album` */

/*Table structure for table `sys_albums_objects` */

DROP TABLE IF EXISTS `sys_albums_objects`;

CREATE TABLE `sys_albums_objects` (
  `id_album` int(10) NOT NULL,
  `id_object` int(10) NOT NULL,
  `obj_order` int(10) NOT NULL DEFAULT '0',
  UNIQUE KEY `id_album` (`id_album`,`id_object`),
  KEY `id_object` (`id_object`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `sys_albums_objects` */

/*Table structure for table `sys_article` */

DROP TABLE IF EXISTS `sys_article`;

CREATE TABLE `sys_article` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '索引id',
  `cate_id` int(11) DEFAULT NULL COMMENT '分类id',
  `url` varchar(255) DEFAULT NULL COMMENT '跳转链接 如果有那么内容被忽略',
  `enable` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '是否显示，0为否，1为是，默认为1',
  `order` tinyint(3) unsigned NOT NULL DEFAULT '255' COMMENT '排序',
  `title` varchar(125) DEFAULT NULL COMMENT '标题',
  `article_content` text COMMENT '内容',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '发布时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=46 DEFAULT CHARSET=utf8 COMMENT='文章表';

/*Data for the table `sys_article` */

insert  into `sys_article`(`id`,`cate_id`,`url`,`enable`,`order`,`title`,`article_content`,`create_time`) values (6,2,'',1,255,'如何注册成为会员','<h3 style=\"margin:15pt 0cm 0pt;\"><span style=\"font-family:宋体;mso-ascii-font-family:Calibri;mso-hansi-font-family:Calibri;\"><span style=\"font-size:24px;color:#003399;font-family:Microsoft YaHei;background-color:#ffffff;\"><span style=\"font-size:18px;\">登录商城首页，点击页面右上方“注册”</span></span></span></h3>\r\n<h3 style=\"margin:15pt 0cm 0pt;\"><span style=\"font-family:宋体;mso-ascii-font-family:Calibri;mso-hansi-font-family:Calibri;\"><span style=\"font-size:small;color:#335a89;\"><span lang=\"EN-US\" style=\"font-size:9pt;color:red;line-height:115%;font-family:Calibri;mso-bidi-font-size:8.0pt;mso-fareast-font-family:宋体;mso-bidi-font-family:Times New Roman;mso-ansi-language:EN-US;mso-fareast-language:ZH-CN;mso-bidi-language:AR-SA;\"><?xml:namespace prefix = v ns = \"urn:schemas-microsoft-com:vml\" /><v:shapetype id=\"_x0000_t75\" coordsize=\"21600,21600\" o:spt=\"75\" o:preferrelative=\"t\" path=\"m@4@5l@4@11@9@11@9@5xe\" stroked=\"f\" filled=\"f\"><span style=\"font-size:24px;\">&nbsp;</span><img alt=\"\" src=\"./upload/editor/20110128134626_62236.jpg\" border=\"0\" /></v:shapetype></span></span></span></h3>\r\n<h3 style=\"margin:15pt 0cm 0pt;\"><span style=\"font-family:宋体;mso-ascii-font-family:Calibri;mso-hansi-font-family:Calibri;\"><span style=\"font-size:small;color:#335a89;\"><span lang=\"EN-US\" style=\"font-size:9pt;color:red;line-height:115%;font-family:Calibri;mso-bidi-font-size:8.0pt;mso-fareast-font-family:宋体;mso-bidi-font-family:Times New Roman;mso-ansi-language:EN-US;mso-fareast-language:ZH-CN;mso-bidi-language:AR-SA;\"><v:shapetype coordsize=\"21600,21600\" o:spt=\"75\" o:preferrelative=\"t\" path=\"m@4@5l@4@11@9@11@9@5xe\" stroked=\"f\" filled=\"f\"><span style=\"font-size:24px;\">&nbsp;</span></h3>\r\n<h3 style=\"margin:15pt 0cm 0pt;\"><span style=\"font-family:宋体;\"><span style=\"font-size:small;color:#003399;\"><span style=\"font-size:18px;\">进入注册页面，填写相关信息并阅读用户服务手册</span></span></span></h3>\r\n<h3 style=\"margin:15pt 0cm 0pt;\">&nbsp;</h3>\r\n<h3 style=\"margin:15pt 0cm 0pt;\"><span style=\"font-family:宋体;\"><span style=\"font-size:small;color:#335a89;\"><img alt=\"\" src=\"./upload/editor/20110128135022_79350.jpg\" border=\"0\" /></span></span></h3>\r\n<h3 style=\"margin:15pt 0cm 0pt;\">&nbsp;</h3>\r\n<h3 style=\"margin:15pt 0cm 0pt;\"><span style=\"font-family:宋体;\"><span style=\"font-size:small;color:#003399;\"><span style=\"font-size:18px;\">相关资料填写完成后点击“免费注册”提交</span></span></span></h3>\r\n<h3 style=\"margin:15pt 0cm 0pt;\"><span style=\"font-family:宋体;\"><span style=\"font-size:small;color:#003399;\"><img alt=\"\" src=\"./upload/editor/20110128135237_43758.jpg\" border=\"0\" /></span></span></h3>\r\n<h3 style=\"margin:15pt 0cm 0pt;\">&nbsp;</h3>\r\n<h3 style=\"margin:15pt 0cm 0pt;\"><span style=\"font-family:宋体;\"><span style=\"font-size:small;color:#003399;\"><span style=\"font-size:18px;\">提示注册成功</span></span></span></h3>\r\n<h3 style=\"margin:15pt 0cm 0pt;\">&nbsp;</h3>\r\n<h3 style=\"margin:15pt 0cm 0pt;\"><span style=\"font-family:宋体;\"><span style=\"font-size:small;color:#003399;\"><img alt=\"\" src=\"./upload/editor/20110128135406_55835.jpg\" border=\"0\" /></span></span></h3>\r\n<h3 style=\"margin:15pt 0cm 0pt;\">&nbsp;</h3>\r\n<h3 style=\"margin:15pt 0cm 0pt;\">&nbsp;</h3>\r\n<h3>&nbsp;</h3>\r\n</v:shapetype></span></span></span>',1294709136),(7,2,'',1,255,'如何搜索','<h3 style=\"margin:15pt 0cm 0pt;\"><span style=\"font-family:宋体;mso-ascii-font-family:Calibri;mso-hansi-font-family:Calibri;\"><span style=\"font-size:24px;color:#003399;font-family:Microsoft YaHei;background-color:#ffffff;\"><span style=\"font-size:18px;\">登录商城首页，在搜索商品的搜索框内填入要搜索的商品的关键字，点击“搜索”</span></span></span></h3>\r\n<h3 style=\"margin:15pt 0cm 0pt;\"><span style=\"font-family:宋体;mso-ascii-font-family:Calibri;mso-hansi-font-family:Calibri;\"><span style=\"font-size:24px;color:#003399;font-family:Microsoft YaHei;background-color:#ffffff;\"><span style=\"font-size:18px;\"><img alt=\"\" src=\"./upload/editor/20110208093142_97861.jpg\" border=\"0\" /></span></span></span></h3>\r\n<h3 style=\"margin:15pt 0cm 0pt;\">&nbsp;</h3>\r\n<h3 style=\"margin:15pt 0cm 0pt;\"><span style=\"font-family:宋体;mso-ascii-font-family:Calibri;mso-hansi-font-family:Calibri;\"><span style=\"font-size:24px;color:#003399;font-family:Microsoft YaHei;background-color:#ffffff;\"><span style=\"font-size:18px;\">出现含有关键字的商品页面</span></span></span></h3>\r\n<h3 style=\"margin:15pt 0cm 0pt;\"><span style=\"font-family:宋体;mso-ascii-font-family:Calibri;mso-hansi-font-family:Calibri;\"><span style=\"font-size:24px;color:#003399;font-family:Microsoft YaHei;background-color:#ffffff;\"><span style=\"font-size:18px;\"><img alt=\"\" src=\"./upload/editor/20110208094130_68431.jpg\" border=\"0\" width=\"700px\" /></span></span></span></h3>\r\n<h3 style=\"margin:15pt 0cm 0pt;\">&nbsp;</h3>\r\n<h3 style=\"margin:15pt 0cm 0pt;\">&nbsp;</h3>',1294709301),(8,2,'',1,255,'忘记密码','<span style=\"font-family:SimSun;\"><span style=\"font-size:18px;\"><span style=\"color:#003399;\"> <h3 style=\"margin:15pt 0cm 0pt;\"><span style=\"font-family:宋体;mso-ascii-font-family:Calibri;mso-hansi-font-family:Calibri;\"><span style=\"font-size:small;color:#335a89;\">进入会员登录页面，点击“忘记密码”</span></span></h3>\r\n<h3 style=\"margin:15pt 0cm 0pt;\"><span style=\"font-family:宋体;mso-ascii-font-family:Calibri;mso-hansi-font-family:Calibri;\"><img alt=\"\" src=\"./upload/editor/20110208095308_38085.jpg\" border=\"0\" /></span></h3>\r\n<h3 style=\"margin:15pt 0cm 0pt;\">&nbsp;</h3>\r\n<h3 style=\"margin:15pt 0cm 0pt;\"><span style=\"font-family:宋体;mso-ascii-font-family:Calibri;mso-hansi-font-family:Calibri;\"> <h3 style=\"margin:15pt 0cm 0pt;\"><span style=\"font-family:宋体;mso-ascii-font-family:Calibri;mso-hansi-font-family:Calibri;\"><span style=\"font-size:small;color:#335a89;\">在忘记密码页面中填写用户名、电子邮箱等信息，点击“提交找回”</span></span></h3>\r\n<h3 style=\"margin:15pt 0cm 0pt;\"><span style=\"font-family:宋体;mso-ascii-font-family:Calibri;mso-hansi-font-family:Calibri;\"><img alt=\"\" src=\"./upload/editor/20110208095715_55839.jpg\" border=\"0\" /></span></h3>\r\n<h3 style=\"margin:15pt 0cm 0pt;\"><span style=\"font-family:宋体;mso-ascii-font-family:Calibri;mso-hansi-font-family:Calibri;\"><span style=\"font-family:宋体;mso-ascii-font-family:Calibri;mso-hansi-font-family:Calibri;\"><span style=\"font-size:x-small;color:#ff0000;\">电子邮箱地址要填写注册用户名是的邮箱</span></span></h3>\r\n<h3 style=\"margin:15pt 0cm 0pt;\">\r\n<h3 style=\"margin:15pt 0cm 0pt;\">&nbsp;</h3>\r\n<h3 style=\"margin:15pt 0cm 0pt;\"><span style=\"font-family:宋体;mso-ascii-font-family:Calibri;mso-hansi-font-family:Calibri;\"><span style=\"font-size:small;color:#335a89;\"><span style=\"color:#003399;\">提示电子邮件已发送成功</span></span></span></h3>\r\n<h3 style=\"margin:15pt 0cm 0pt;\"><span style=\"font-family:宋体;mso-ascii-font-family:Calibri;mso-hansi-font-family:Calibri;\"><img alt=\"\" src=\"./upload/editor/20110208095945_10374.jpg\" border=\"0\" /></span></h3>\r\n<h3 style=\"margin:15pt 0cm 0pt;\">&nbsp;</h3>\r\n<h3 style=\"margin:15pt 0cm 0pt;\">&nbsp;</h3>\r\n<h3 style=\"margin:15pt 0cm 0pt;\"><span style=\"font-family:宋体;mso-ascii-font-family:Calibri;mso-hansi-font-family:Calibri;\"><span style=\"font-size:18px;color:#003399;line-height:115%;font-family:SimSun;mso-ascii-font-family:Calibri;mso-hansi-font-family:Calibri;mso-bidi-font-size:8.0pt;mso-bidi-font-family:Times New Roman;mso-ansi-language:EN-US;mso-fareast-language:ZH-CN;mso-bidi-language:AR-SA;\">登录电子邮箱查看，找回密码</span></span></h3>\r\n<h3 style=\"margin:15pt 0cm 0pt;\"><span style=\"font-family:宋体;mso-ascii-font-family:Calibri;mso-hansi-font-family:Calibri;\"><span style=\"font-size:18px;color:#003399;line-height:115%;font-family:SimSun;mso-ascii-font-family:Calibri;mso-hansi-font-family:Calibri;mso-bidi-font-size:8.0pt;mso-bidi-font-family:Times New Roman;mso-ansi-language:EN-US;mso-fareast-language:ZH-CN;mso-bidi-language:AR-SA;\"><img alt=\"\" src=\"./upload/editor/20110208100610_22468.jpg\" border=\"0\" /></span></span></h3>\r\n<h3 style=\"margin:15pt 0cm 0pt;\">&nbsp;</h3>\r\n<h3 style=\"margin:15pt 0cm 0pt;\">&nbsp;</h3>\r\n<h3 style=\"margin:15pt 0cm 0pt;\">&nbsp;</h3>\r\n<h3 style=\"margin:15pt 0cm 0pt;\">&nbsp;</h3>\r\n<h3 style=\"margin:15pt 0cm 0pt;\">&nbsp;</h3>\r\n<h3 style=\"margin:15pt 0cm 0pt;\">&nbsp;</h3>\r\n</span></h3>\r\n</span></h3>\r\n<h3 style=\"margin:15pt 0cm 0pt;\">&nbsp;</h3>\r\n</span></span></span>',1294709313),(9,2,'',1,255,'我要买','<p><span style=\"font-family:SimSun;\"><span style=\"font-size:18px;\"><span style=\"color:#003399;\">会员登陆商城首页，打开商品信息页面</span></span></span></p>\r\n<p><span style=\"font-family:SimSun;\"><span style=\"font-size:18px;\"><span style=\"color:#003399;\"><img alt=\"\" src=\"./upload/editor/20110208170115_29919.jpg\" border=\"0\" width=\"700px\" /></span></span></span></p>\r\n<p>&nbsp;</p>\r\n<p><span style=\"font-family:SimSun;\"><span style=\"font-size:18px;\"><span style=\"color:#003399;\">填写购买数量，点击“加入购物车”</span></span></span></p>\r\n<p></p>\r\n<p><img alt=\"\" src=\"./upload/editor/20110209093017_89659.jpg\" border=\"0\" /></p>\r\n<p>&nbsp;</p>\r\n<p><span style=\"font-family:SimSun;\"><span style=\"font-size:18px;\"><span style=\"color:#003399;\">点击“查看购物车”</span></span></span></p>\r\n<p><img alt=\"\" src=\"./upload/editor/20110209093309_99011.jpg\" border=\"0\" /></p>\r\n<p>&nbsp;</p>\r\n<p><span style=\"font-family:SimSun;\"><span style=\"font-size:18px;\"><span style=\"color:#003399;\">点击“填写并确认订单”</span></span></span></p>\r\n<p><span style=\"font-family:SimSun;\"><span style=\"font-size:18px;\"><span style=\"color:#003399;\"><img alt=\"\" src=\"./upload/editor/20110209093721_30123.jpg\" border=\"0\" width=\"700px\" /></span></span></span></p>\r\n<p>&nbsp;</p>\r\n<p><span style=\"font-family:SimSun;\"><span style=\"font-size:18px;\"><span style=\"color:#003399;\">填写信息，点击“下单完成并支付”</span></span></span></p>\r\n<p><span style=\"font-family:SimSun;\"><span style=\"font-size:18px;\"><span style=\"color:#003399;\"><img alt=\"\" src=\"./upload/editor/20110209093819_87401.jpg\" border=\"0\" width=\"700px\" /></span></span></span></p>\r\n<p>&nbsp;</p>\r\n<p><span style=\"font-family:SimSun;\"><span style=\"font-size:18px;\"><span style=\"color:#003399;\"><span style=\"font-family:SimSun;\"><span style=\"font-size:18px;\"><span style=\"color:#003399;\">选择支付方式，点击“确认支付”</span></span></span></span></span></span></p>\r\n<p><span style=\"font-family:SimSun;\"><span style=\"font-size:18px;\"><span style=\"color:#003399;\"><span style=\"font-family:SimSun;\"><span style=\"font-size:18px;\"><span style=\"color:#003399;\"><img alt=\"\" src=\"./upload/editor/20110209094020_49119.jpg\" border=\"0\" width=\"700px\" /></span></span></span></span></span></span></p>\r\n<p>&nbsp;</p>\r\n<p><span style=\"font-family:SimSun;\"><span style=\"font-size:18px;\"><span style=\"color:#003399;\"><span style=\"font-family:SimSun;\"><span style=\"font-size:18px;\"><span style=\"color:#003399;\">购买商品成功</span></span></span></span></span></span></p>\r\n<p><span style=\"font-family:SimSun;\"><span style=\"font-size:18px;\"><span style=\"color:#003399;\"><span style=\"font-family:SimSun;\"><span style=\"font-size:18px;\"><span style=\"color:#003399;\"><img alt=\"\" src=\"./upload/editor/20110209094145_20766.jpg\" border=\"0\" width=\"700px\"/></span></span></span></span></span></span></p>\r\n<p>&nbsp;</p>\r\n<p>&nbsp;</p>',1294709365),(10,2,'',1,255,'查看已购买商品','<p><span style=\"font-family:SimSun;\"><span style=\"font-size:18px;\"><span style=\"color:#003399;\">会员登录商城，进入用户中心</span></span></span></p>\r\n<p><span style=\"font-family:SimSun;\"><span style=\"font-size:18px;\"><span style=\"color:#003399;\"><img alt=\"\" src=\"./upload/editor/20110209094358_99646.jpg\" border=\"0\" /></span></span></span></p>\r\n<p>&nbsp;</p>\r\n<p><span style=\"font-family:SimSun;\"><span style=\"font-size:18px;\"><span style=\"color:#003399;\">在我是买家板块点进“我的订单”进入，则可查看已购买宝贝</span></span></span></p>\r\n<p><span style=\"font-family:SimSun;\"><span style=\"font-size:18px;\"><span style=\"color:#003399;\"><img alt=\"\" src=\"./upload/editor/20110209094502_62272.jpg\" border=\"0\" width=\"700px\"/></span></span></span></p>',1294709380),(11,3,'',1,255,'如何管理店铺','<p><span style=\"font-family:SimSun;\"><span style=\"font-size:18px;\"><span style=\"font-size:18px;color:#003399;font-family:SimSun;\"><span style=\"font-size:18px;color:#003399;font-family:SimSun;\">&nbsp;</span><span style=\"font-family:宋体;mso-ascii-font-family:Calibri;mso-hansi-font-family:Calibri;\"><span style=\"font-size:small;color:#335a89;\"><span style=\"font-size:18px;color:#003399;font-family:SimSun;\">会员登陆商城进入用户中心，在我是卖家板块找到“店铺设置”点击</span></span></span></span></span></span></p>\r\n<p><span style=\"font-family:SimSun;\"><span style=\"font-size:18px;\"><span style=\"font-size:18px;color:#003399;font-family:SimSun;\"><span style=\"font-family:宋体;mso-ascii-font-family:Calibri;mso-hansi-font-family:Calibri;\"><span style=\"font-size:small;color:#335a89;\"><span style=\"font-size:18px;color:#003399;font-family:SimSun;\"><img alt=\"\" src=\"./upload/editor/20110209101544_91746.jpg\" border=\"0\" /></span></span></span></span></span></span></p>\r\n<span style=\"font-family:SimSun;\"><span style=\"font-size:18px;\"><span style=\"font-size:18px;color:#003399;font-family:SimSun;\"><span style=\"font-family:宋体;mso-ascii-font-family:Calibri;mso-hansi-font-family:Calibri;\"><span style=\"font-size:small;color:#335a89;\"><span style=\"font-size:18px;color:#003399;font-family:SimSun;\"> <p style=\"margin:15pt 0cm 0pt;\"><span style=\"font-family:宋体;mso-ascii-font-family:Calibri;mso-hansi-font-family:Calibri;\"><span style=\"font-size:small;color:#335a89;\"><span style=\"font-size:18px;color:#003399;font-family:SimSun;\">填写店铺信息后点击“提交”</span></span></span></p>\r\n<h3 style=\"margin:15pt 0cm 0pt;\"></h3>\r\n<h3 style=\"margin:15pt 0cm 0pt;\"><img alt=\"\" src=\"./upload/editor/20110209132805_31517.jpg\" border=\"0\" /></h3>\r\n<p style=\"margin:15pt 0cm 0pt;\">&nbsp;</p>\r\n<p style=\"margin:15pt 0cm 0pt;\"><span style=\"font-family:宋体;mso-ascii-font-family:Calibri;mso-hansi-font-family:Calibri;\"><span style=\"font-size:small;color:#335a89;\"><span style=\"font-size:18px;color:#003399;font-family:SimSun;\">点击我的店铺首页可查看设置后的首页</span></span></span></p>\r\n<p style=\"margin:15pt 0cm 0pt;\"><span style=\"font-family:宋体;mso-ascii-font-family:Calibri;mso-hansi-font-family:Calibri;\"><span style=\"font-size:small;color:#335a89;\"><span style=\"font-size:18px;color:#003399;font-family:SimSun;\"><img alt=\"\" src=\"./upload/editor/20110209133004_49592.jpg\" border=\"0\" /></span></span></span></p>\r\n</span></span></span></span></span></span> <p><span style=\"font-family:SimSun;\"><span style=\"font-size:18px;\"><span style=\"font-size:18px;color:#003399;font-family:SimSun;\">&nbsp;</p>\r\n<p class=\"MsoNormal\" style=\"margin:10pt 0cm;\"><span style=\"font-size:x-small;\"><span style=\"color:#000000;\"><span style=\"font-size:18px;color:#003399;font-family:SimSun;mso-ascii-font-family:Calibri;mso-hansi-font-family:Calibri;\"><span lang=\"EN-US\" style=\"font-size:10pt;line-height:115%;font-family:Calibri;mso-fareast-font-family:宋体;mso-bidi-font-family:Times New Roman;mso-bidi-font-size:10.5pt;mso-ansi-language:EN-US;mso-fareast-language:ZH-CN;mso-bidi-language:AR-SA;\"><?xml:namespace prefix = v /><v:shapetype stroked=\"f\" filled=\"f\" path=\"m@4@5l@4@11@9@11@9@5xe\" o:preferrelative=\"t\" o:spt=\"75\" coordsize=\"21600,21600\"></v:shapetype></span></span></span></span>&nbsp;</p>\r\n<p class=\"MsoNormal\" style=\"margin:10pt 0cm;\"><span style=\"font-size:x-small;\"><span style=\"color:#000000;\"><span style=\"font-size:18px;color:#003399;font-family:SimSun;mso-ascii-font-family:Calibri;mso-hansi-font-family:Calibri;\"><span lang=\"EN-US\" style=\"font-size:10pt;line-height:115%;font-family:Calibri;mso-fareast-font-family:宋体;mso-bidi-font-family:Times New Roman;mso-bidi-font-size:10.5pt;mso-ansi-language:EN-US;mso-fareast-language:ZH-CN;mso-bidi-language:AR-SA;\"><v:shapetype stroked=\"f\" filled=\"f\" path=\"m@4@5l@4@11@9@11@9@5xe\" o:preferrelative=\"t\" o:spt=\"75\" coordsize=\"21600,21600\"><span style=\"color:#000000;\"><?xml:namespace prefix = v ns = \"urn:schemas-microsoft-com:vml\" /><v:stroke joinstyle=\"miter\"></v:stroke></span><v:formulas><v:f eqn=\"if lineDrawn pixelLineWidth 0\"></v:f><v:f eqn=\"sum @0 1 0\"></v:f><v:f eqn=\"sum 0 0 @1\"></v:f><v:f eqn=\"prod @2 1 2\"></v:f><v:f eqn=\"prod @3 21600 pixelWidth\"></v:f><v:f eqn=\"prod @3 21600 pixelHeight\"></v:f><v:f eqn=\"sum @0 0 1\"></v:f><v:f eqn=\"prod @6 1 2\"></v:f><v:f eqn=\"prod @7 21600 pixelWidth\"></v:f><v:f eqn=\"sum @8 21600 0\"></v:f><v:f eqn=\"prod @7 21600 pixelHeight\"></v:f><v:f eqn=\"sum @10 21600 0\"></v:f></v:formulas><v:path o:connecttype=\"rect\" gradientshapeok=\"t\" o:extrusionok=\"f\"></v:path><?xml:namespace prefix = o ns = \"urn:schemas-microsoft-com:office:office\" /><o:lock aspectratio=\"t\" v:ext=\"edit\"></o:lock></v:shapetype></span></span></span></span>&nbsp;</p>\r\n</span></span></span>',1294709442),(12,3,'',1,255,'查看售出商品','<span style=\"font-size:18px;color:#003399;font-family:SimSun;\"> <p style=\"margin:15pt 0cm 0pt;\"><span style=\"font-family:宋体;mso-ascii-font-family:Calibri;mso-hansi-font-family:Calibri;\"><span style=\"font-size:small;color:#335a89;\"><span style=\"font-size:18px;color:#003399;font-family:SimSun;\">会员登陆商城进入用户中心，在我是卖家板块找到“订单管理”点击</span></span></span></p>\r\n<p style=\"margin:15pt 0cm 0pt;\"><span style=\"font-family:宋体;mso-ascii-font-family:Calibri;mso-hansi-font-family:Calibri;\"><span style=\"font-size:small;color:#335a89;\"><span style=\"font-size:18px;color:#003399;font-family:SimSun;\"><img alt=\"\" src=\"./upload/editor/20110209141334_26280.jpg\" border=\"0\" /></span></span></span></p>\r\n<p style=\"margin:15pt 0cm 0pt;\">&nbsp;</p>\r\n<p style=\"margin:15pt 0cm 0pt;\"><span style=\"font-family:宋体;mso-ascii-font-family:Calibri;mso-hansi-font-family:Calibri;\"><span style=\"font-size:small;color:#335a89;\"><span style=\"font-size:18px;color:#003399;font-family:SimSun;\">点击“查看订单”</span></span></span></p>\r\n<p style=\"margin:15pt 0cm 0pt;\"><span style=\"font-family:宋体;mso-ascii-font-family:Calibri;mso-hansi-font-family:Calibri;\"><span style=\"font-size:small;color:#335a89;\"><span style=\"font-size:18px;color:#003399;font-family:SimSun;\"><img alt=\"\" src=\"./upload/editor/20110209143053_56888.jpg\" border=\"0\" /></span></span></span></p>\r\n<p style=\"margin:15pt 0cm 0pt;\">&nbsp;</p>\r\n<p style=\"margin:15pt 0cm 0pt;\">&nbsp;</p>\r\n<p style=\"margin:15pt 0cm 0pt;\">&nbsp;</p>\r\n<p style=\"margin:15pt 0cm 0pt;\">&nbsp;</p>\r\n</span>',1294709506),(13,3,'',1,255,'如何发货','<p style=\"margin:15pt 0cm 0pt;\"><span style=\"font-family:宋体;mso-ascii-font-family:Calibri;mso-hansi-font-family:Calibri;\"><span style=\"font-size:small;color:#335a89;\"><span style=\"font-size:18px;color:#003399;font-family:SimSun;\">在所有订单列表页面，点击“收到货款”</span></span></span></p>\r\n<p style=\"margin:15pt 0cm 0pt;\"><span style=\"font-family:宋体;mso-ascii-font-family:Calibri;mso-hansi-font-family:Calibri;\"><span style=\"font-size:small;color:#335a89;\"><span style=\"font-size:18px;color:#003399;font-family:SimSun;\"><img alt=\"\" src=\"./upload/editor/20110209144219_67019.jpg\" border=\"0\" /></span></span></span></p>\r\n<p style=\"margin:15pt 0cm 0pt;\">&nbsp;</p>\r\n<span style=\"font-family:宋体;mso-ascii-font-family:Calibri;mso-hansi-font-family:Calibri;\"><span style=\"font-size:small;color:#335a89;\"><span style=\"font-size:18px;color:#003399;font-family:SimSun;\"> <p style=\"margin:15pt 0cm 0pt;\"><span style=\"font-family:SimSun;mso-ascii-font-family:Calibri;mso-hansi-font-family:Calibri;\">填写操作描述，点击“确定”</span></p>\r\n<p style=\"margin:15pt 0cm 0pt;\"><span style=\"font-family:SimSun;mso-ascii-font-family:Calibri;mso-hansi-font-family:Calibri;\"><img alt=\"\" src=\"./upload/editor/20110209144319_11772.jpg\" border=\"0\" /></span></p>\r\n<p style=\"margin:15pt 0cm 0pt;\">&nbsp;</p>\r\n<span style=\"font-family:SimSun;mso-ascii-font-family:Calibri;mso-hansi-font-family:Calibri;\"> <p style=\"margin:15pt 0cm 0pt;\"><span style=\"font-family:宋体;mso-ascii-font-family:Calibri;mso-hansi-font-family:Calibri;\"><span style=\"color:#335a89;font-size:small;\">在所有订单列表页面，点击“发货”</span></span></p>\r\n<p style=\"margin:15pt 0cm 0pt;\"><span style=\"font-family:宋体;mso-ascii-font-family:Calibri;mso-hansi-font-family:Calibri;\"><img alt=\"\" src=\"./upload/editor/20110209150146_55268.jpg\" border=\"0\" /></span></p>\r\n<p style=\"margin:15pt 0cm 0pt;\">&nbsp;</p>\r\n<span style=\"font-family:宋体;mso-ascii-font-family:Calibri;mso-hansi-font-family:Calibri;\"> <p style=\"margin:15pt 0cm 0pt;\"><span style=\"font-family:宋体;mso-ascii-font-family:Calibri;mso-hansi-font-family:Calibri;\"><span style=\"color:#335a89;font-size:small;\">填写物流编号以及操作描述，点击“确定”</span></span></p>\r\n<p style=\"margin:15pt 0cm 0pt;\"><span style=\"font-family:宋体;mso-ascii-font-family:Calibri;mso-hansi-font-family:Calibri;\"><img alt=\"\" src=\"./upload/editor/20110209150256_60581.jpg\" border=\"0\" /></span></p>\r\n<p style=\"margin:15pt 0cm 0pt;\">&nbsp;</p>\r\n</span></span> <p style=\"margin:15pt 0cm 0pt;\">&nbsp;</p>\r\n</span></span></span>',1294709579),(14,3,'',1,255,'商城商品推荐','<p><span style=\"font-size:18px;color:#003399;line-height:115%;font-family:宋体;mso-bidi-font-size:8.0pt;mso-ascii-font-family:Calibri;mso-hansi-font-family:Calibri;mso-bidi-font-family:Times New Roman;mso-ansi-language:EN-US;mso-fareast-language:ZH-CN;mso-bidi-language:AR-SA;\">会员登陆商城进入用户中心，在我是卖家板块找到“商品管理”点击</span></p>\r\n<p><span style=\"font-size:18px;color:#003399;line-height:115%;font-family:宋体;mso-bidi-font-size:8.0pt;mso-ascii-font-family:Calibri;mso-hansi-font-family:Calibri;mso-bidi-font-family:Times New Roman;mso-ansi-language:EN-US;mso-fareast-language:ZH-CN;mso-bidi-language:AR-SA;\"><img alt=\"\" src=\"./upload/editor/20110209154027_17581.jpg\" border=\"0\" /></span></p>\r\n<p>&nbsp;</p>\r\n<span style=\"font-size:18px;color:#003399;line-height:115%;font-family:宋体;mso-bidi-font-size:8.0pt;mso-ascii-font-family:Calibri;mso-hansi-font-family:Calibri;mso-bidi-font-family:Times New Roman;mso-ansi-language:EN-US;mso-fareast-language:ZH-CN;mso-bidi-language:AR-SA;\"> <p style=\"margin:15pt 0cm 0pt;\"><span style=\"font-family:宋体;mso-ascii-font-family:Calibri;mso-hansi-font-family:Calibri;\"><span style=\"font-size:small;color:#335a89;\">在商品列表中点击该商品后的“编辑”</span></span></p>\r\n<p style=\"margin:15pt 0cm 0pt;\"><span style=\"font-family:宋体;mso-ascii-font-family:Calibri;mso-hansi-font-family:Calibri;\"><span style=\"font-size:small;color:#335a89;\"><img alt=\"\" src=\"./upload/editor/20110209155654_54046.jpg\" border=\"0\" /></span></span></p>\r\n<p style=\"margin:15pt 0cm 0pt;\">&nbsp;</p>\r\n<p style=\"margin:15pt 0cm 0pt;\"><span style=\"font-family:宋体;mso-ascii-font-family:Calibri;mso-hansi-font-family:Calibri;\"><span style=\"font-size:small;color:#335a89;\">选择推荐，点击“提交”</span></span></p>\r\n<p style=\"margin:15pt 0cm 0pt;\"><span style=\"font-family:宋体;mso-ascii-font-family:Calibri;mso-hansi-font-family:Calibri;\"><span style=\"font-size:small;color:#335a89;\"><img alt=\"\" src=\"./upload/editor/20110209160008_38544.jpg\" border=\"0\" /></span></span></p>\r\n<p style=\"margin:15pt 0cm 0pt;\"><span style=\"font-family:宋体;mso-ascii-font-family:Calibri;mso-hansi-font-family:Calibri;\"><span style=\"font-size:small;color:#335a89;\"><img alt=\"\" src=\"./upload/editor/20110209160122_33174.jpg\" border=\"0\" /></span></span></p>\r\n<p style=\"margin:15pt 0cm 0pt;\">&nbsp;</p>\r\n<p style=\"margin:15pt 0cm 0pt;\">&nbsp;</p>\r\n<p style=\"margin:15pt 0cm 0pt;\">&nbsp;</p>\r\n</span>',1294709599),(15,3,'',1,255,'如何申请开店','<p><span style=\"font-size:18px;color:#003399;font-family:SimSun;mso-bidi-font-size:12.0pt;mso-ascii-font-family:Times New Roman;mso-hansi-font-family:Times New Roman;mso-bidi-font-family:Times New Roman;mso-font-kerning:1.0pt;mso-ansi-language:EN-US;mso-fareast-language:ZH-CN;mso-bidi-language:AR-SA;\">登录商城首页，点击右上角“用户中心</span><span lang=\"EN-US\" style=\"font-size:18px;color:#003399;font-family:SimSun;mso-bidi-font-size:12.0pt;mso-font-kerning:1.0pt;mso-ansi-language:EN-US;mso-fareast-language:ZH-CN;mso-bidi-language:AR-SA;mso-fareast-font-family:宋体;\">”</span></p>\r\n<p><span lang=\"EN-US\" style=\"font-size:18px;color:#003399;font-family:SimSun;mso-bidi-font-size:12.0pt;mso-font-kerning:1.0pt;mso-ansi-language:EN-US;mso-fareast-language:ZH-CN;mso-bidi-language:AR-SA;mso-fareast-font-family:宋体;\"><img alt=\"\" src=\"./upload/editor/20110209162925_19705.jpg\" border=\"0\" /></span></p>\r\n<p>&nbsp;</p>\r\n<p>&nbsp;</p>\r\n<p><span lang=\"EN-US\" style=\"font-size:18px;color:#003399;font-family:SimSun;mso-bidi-font-size:12.0pt;mso-font-kerning:1.0pt;mso-ansi-language:EN-US;mso-fareast-language:ZH-CN;mso-bidi-language:AR-SA;mso-fareast-font-family:宋体;\"><span style=\"color:#000000;\"><span style=\"font-size:18px;color:#003399;font-family:SimSun;mso-bidi-font-size:12.0pt;mso-ascii-font-family:Times New Roman;mso-hansi-font-family:Times New Roman;mso-bidi-font-family:Times New Roman;mso-font-kerning:1.0pt;mso-ansi-language:EN-US;mso-fareast-language:ZH-CN;mso-bidi-language:AR-SA;\">会员进入用户中心页面，点击下方</span><span style=\"font-size:18px;color:#003399;font-family:SimSun;mso-bidi-font-size:12.0pt;mso-ascii-font-family:Times New Roman;mso-hansi-font-family:Times New Roman;mso-bidi-font-family:Times New Roman;mso-font-kerning:1.0pt;mso-ansi-language:EN-US;mso-fareast-language:ZH-CN;mso-bidi-language:AR-SA;\">“申请开店”</span></span></span></p>\r\n<p><span lang=\"EN-US\" style=\"font-size:18px;color:#003399;font-family:SimSun;mso-bidi-font-size:12.0pt;mso-font-kerning:1.0pt;mso-ansi-language:EN-US;mso-fareast-language:ZH-CN;mso-bidi-language:AR-SA;mso-fareast-font-family:宋体;\"><span style=\"color:#000000;\"><span style=\"font-size:18px;color:#003399;font-family:SimSun;mso-bidi-font-size:12.0pt;mso-ascii-font-family:Times New Roman;mso-hansi-font-family:Times New Roman;mso-bidi-font-family:Times New Roman;mso-font-kerning:1.0pt;mso-ansi-language:EN-US;mso-fareast-language:ZH-CN;mso-bidi-language:AR-SA;\"><img alt=\"\" src=\"./upload/editor/20110209163229_20901.jpg\" border=\"0\" /></span></span></span></p>\r\n<p>&nbsp;</p>\r\n<p>&nbsp;</p>\r\n<p><span lang=\"EN-US\" style=\"font-size:18px;color:#003399;font-family:SimSun;mso-bidi-font-size:12.0pt;mso-font-kerning:1.0pt;mso-ansi-language:EN-US;mso-fareast-language:ZH-CN;mso-bidi-language:AR-SA;mso-fareast-font-family:宋体;\"><span style=\"color:#000000;\"><span style=\"font-size:18px;color:#003399;font-family:SimSun;mso-bidi-font-size:12.0pt;mso-ascii-font-family:Times New Roman;mso-hansi-font-family:Times New Roman;mso-bidi-font-family:Times New Roman;mso-font-kerning:1.0pt;mso-ansi-language:EN-US;mso-fareast-language:ZH-CN;mso-bidi-language:AR-SA;\"><span style=\"color:#000000;\"><span style=\"font-size:18px;color:#003399;font-family:宋体;mso-bidi-font-size:12.0pt;mso-ascii-font-family:Times New Roman;mso-hansi-font-family:Times New Roman;mso-bidi-font-family:Times New Roman;mso-font-kerning:1.0pt;mso-ansi-language:EN-US;mso-fareast-language:ZH-CN;mso-bidi-language:AR-SA;\">选择店铺类型，收费标准等，点击“立即开店”</span></span></span></span></span></p>\r\n<p><span lang=\"EN-US\" style=\"font-size:18px;color:#003399;font-family:SimSun;mso-bidi-font-size:12.0pt;mso-font-kerning:1.0pt;mso-ansi-language:EN-US;mso-fareast-language:ZH-CN;mso-bidi-language:AR-SA;mso-fareast-font-family:宋体;\"><span style=\"color:#000000;\"><span style=\"font-size:18px;color:#003399;font-family:SimSun;mso-bidi-font-size:12.0pt;mso-ascii-font-family:Times New Roman;mso-hansi-font-family:Times New Roman;mso-bidi-font-family:Times New Roman;mso-font-kerning:1.0pt;mso-ansi-language:EN-US;mso-fareast-language:ZH-CN;mso-bidi-language:AR-SA;\"><span style=\"color:#000000;\"><span style=\"font-size:18px;color:#003399;font-family:宋体;mso-bidi-font-size:12.0pt;mso-ascii-font-family:Times New Roman;mso-hansi-font-family:Times New Roman;mso-bidi-font-family:Times New Roman;mso-font-kerning:1.0pt;mso-ansi-language:EN-US;mso-fareast-language:ZH-CN;mso-bidi-language:AR-SA;\"><img alt=\"\" src=\"./upload/editor/20110209163457_37558.jpg\" border=\"0\" width=\"700px\" /></span></span></span></span></span></p>\r\n<p>&nbsp;</p>\r\n<p>&nbsp;</p>\r\n<p><span lang=\"EN-US\" style=\"font-size:18px;color:#003399;font-family:SimSun;mso-bidi-font-size:12.0pt;mso-font-kerning:1.0pt;mso-ansi-language:EN-US;mso-fareast-language:ZH-CN;mso-bidi-language:AR-SA;mso-fareast-font-family:宋体;\"><span style=\"color:#000000;\"><span style=\"font-size:18px;color:#003399;font-family:SimSun;mso-bidi-font-size:12.0pt;mso-ascii-font-family:Times New Roman;mso-hansi-font-family:Times New Roman;mso-bidi-font-family:Times New Roman;mso-font-kerning:1.0pt;mso-ansi-language:EN-US;mso-fareast-language:ZH-CN;mso-bidi-language:AR-SA;\"><span style=\"color:#000000;\"><span style=\"font-size:18px;color:#003399;font-family:宋体;mso-bidi-font-size:12.0pt;mso-ascii-font-family:Times New Roman;mso-hansi-font-family:Times New Roman;mso-bidi-font-family:Times New Roman;mso-font-kerning:1.0pt;mso-ansi-language:EN-US;mso-fareast-language:ZH-CN;mso-bidi-language:AR-SA;\">填写店主店铺信息，点击“立即开店”</span></span></span></span></span></p>\r\n<p><span lang=\"EN-US\" style=\"font-size:18px;color:#003399;font-family:SimSun;mso-bidi-font-size:12.0pt;mso-font-kerning:1.0pt;mso-ansi-language:EN-US;mso-fareast-language:ZH-CN;mso-bidi-language:AR-SA;mso-fareast-font-family:宋体;\"><span style=\"color:#000000;\"><span style=\"font-size:18px;color:#003399;font-family:SimSun;mso-bidi-font-size:12.0pt;mso-ascii-font-family:Times New Roman;mso-hansi-font-family:Times New Roman;mso-bidi-font-family:Times New Roman;mso-font-kerning:1.0pt;mso-ansi-language:EN-US;mso-fareast-language:ZH-CN;mso-bidi-language:AR-SA;\"><span style=\"color:#000000;\"><span style=\"font-size:18px;color:#003399;font-family:宋体;mso-bidi-font-size:12.0pt;mso-ascii-font-family:Times New Roman;mso-hansi-font-family:Times New Roman;mso-bidi-font-family:Times New Roman;mso-font-kerning:1.0pt;mso-ansi-language:EN-US;mso-fareast-language:ZH-CN;mso-bidi-language:AR-SA;\"><img alt=\"\" src=\"./upload/editor/20110209164206_43906.jpg\" border=\"0\" /></span></span></span></span></span></p>\r\n<p>&nbsp;</p>\r\n<p><span lang=\"EN-US\" style=\"font-size:18px;color:#003399;font-family:SimSun;mso-bidi-font-size:12.0pt;mso-font-kerning:1.0pt;mso-ansi-language:EN-US;mso-fareast-language:ZH-CN;mso-bidi-language:AR-SA;mso-fareast-font-family:宋体;\"><span style=\"color:#000000;\"><span style=\"font-size:18px;color:#003399;font-family:SimSun;mso-bidi-font-size:12.0pt;mso-ascii-font-family:Times New Roman;mso-hansi-font-family:Times New Roman;mso-bidi-font-family:Times New Roman;mso-font-kerning:1.0pt;mso-ansi-language:EN-US;mso-fareast-language:ZH-CN;mso-bidi-language:AR-SA;\"><span style=\"color:#000000;\"><span style=\"font-size:18px;color:#003399;font-family:宋体;mso-bidi-font-size:12.0pt;mso-ascii-font-family:Times New Roman;mso-hansi-font-family:Times New Roman;mso-bidi-font-family:Times New Roman;mso-font-kerning:1.0pt;mso-ansi-language:EN-US;mso-fareast-language:ZH-CN;mso-bidi-language:AR-SA;\">提示申请开店成功</span></span></span></span></span></p>\r\n<p><span lang=\"EN-US\" style=\"font-size:18px;color:#003399;font-family:SimSun;mso-bidi-font-size:12.0pt;mso-font-kerning:1.0pt;mso-ansi-language:EN-US;mso-fareast-language:ZH-CN;mso-bidi-language:AR-SA;mso-fareast-font-family:宋体;\"><span style=\"color:#000000;\"><span style=\"font-size:18px;color:#003399;font-family:SimSun;mso-bidi-font-size:12.0pt;mso-ascii-font-family:Times New Roman;mso-hansi-font-family:Times New Roman;mso-bidi-font-family:Times New Roman;mso-font-kerning:1.0pt;mso-ansi-language:EN-US;mso-fareast-language:ZH-CN;mso-bidi-language:AR-SA;\"><span style=\"color:#000000;\"><span style=\"font-size:18px;color:#003399;font-family:宋体;mso-bidi-font-size:12.0pt;mso-ascii-font-family:Times New Roman;mso-hansi-font-family:Times New Roman;mso-bidi-font-family:Times New Roman;mso-font-kerning:1.0pt;mso-ansi-language:EN-US;mso-fareast-language:ZH-CN;mso-bidi-language:AR-SA;\"><img alt=\"\" src=\"./upload/editor/20110209164344_55309.jpg\" border=\"0\" /></span></span></span></span></span></p>\r\n<p>&nbsp;</p>\r\n<p>&nbsp;</p>\r\n<p>&nbsp;</p>\r\n<p>&nbsp;</p>\r\n<p>&nbsp;</p>\r\n<p>&nbsp;</p>',1294709809),(16,4,'',1,255,'如何注册支付宝','<p style=\"margin:15pt 0cm 0pt;\"><span style=\"font-family:宋体;mso-ascii-font-family:Calibri;mso-hansi-font-family:Calibri;\"><span style=\"font-size:small;color:#335a89;\"><span style=\"font-size:18px;color:#003399;font-family:SimSun;\">会员登陆商城进入用户中心，在我是卖家板块找到“支付方式管理”点击</span></span></span></p>\r\n<p style=\"margin:15pt 0cm 0pt;\"><span style=\"font-family:宋体;mso-ascii-font-family:Calibri;mso-hansi-font-family:Calibri;\"><span style=\"font-size:small;color:#335a89;\"><span style=\"font-size:18px;color:#003399;font-family:SimSun;\"><img alt=\"\" src=\"./upload/editor/20110210083402_90837.jpg\" border=\"0\" /></span></span></span></p>\r\n<p style=\"margin:15pt 0cm 0pt;\">&nbsp;</p>\r\n<p style=\"margin:15pt 0cm 0pt;\"><span style=\"font-family:宋体;mso-ascii-font-family:Calibri;mso-hansi-font-family:Calibri;\"><span style=\"font-size:small;color:#335a89;\"><span style=\"font-size:18px;color:#003399;font-family:SimSun;\"><span style=\"font-size:18px;color:#003399;line-height:115%;font-family:宋体;mso-ascii-font-family:Calibri;mso-hansi-font-family:Calibri;mso-bidi-font-size:8.0pt;mso-bidi-font-family:Times New Roman;mso-ansi-language:EN-US;mso-fareast-language:ZH-CN;mso-bidi-language:AR-SA;\">点击需要的添加的支付方式后的“安装”</span></span></span></span></p>\r\n<p style=\"margin:15pt 0cm 0pt;\"><span style=\"font-family:宋体;mso-ascii-font-family:Calibri;mso-hansi-font-family:Calibri;\"><span style=\"font-size:small;color:#335a89;\"><span style=\"font-size:18px;color:#003399;font-family:SimSun;\"><span style=\"font-size:18px;color:#003399;line-height:115%;font-family:宋体;mso-ascii-font-family:Calibri;mso-hansi-font-family:Calibri;mso-bidi-font-size:8.0pt;mso-bidi-font-family:Times New Roman;mso-ansi-language:EN-US;mso-fareast-language:ZH-CN;mso-bidi-language:AR-SA;\"><img alt=\"\" src=\"./upload/editor/20110210084935_79853.jpg\" border=\"0\" /></span></span></span></span></p>\r\n<span style=\"font-family:宋体;mso-ascii-font-family:Calibri;mso-hansi-font-family:Calibri;\"><span style=\"font-size:small;color:#335a89;\"><span style=\"font-size:18px;color:#003399;font-family:SimSun;\"><span style=\"font-size:18px;color:#003399;line-height:115%;font-family:宋体;mso-ascii-font-family:Calibri;mso-hansi-font-family:Calibri;mso-bidi-font-size:8.0pt;mso-bidi-font-family:Times New Roman;mso-ansi-language:EN-US;mso-fareast-language:ZH-CN;mso-bidi-language:AR-SA;\"> <h3 style=\"margin:15pt 0cm 0pt;\">&nbsp;</h3>\r\n<p style=\"margin:15pt 0cm 0pt;\"><span style=\"font-family:宋体;mso-ascii-font-family:Calibri;mso-hansi-font-family:Calibri;\"><span style=\"font-size:small;color:#335a89;\">填写相关信息，点击“提交”</span></span></p>\r\n</span></span></span></span> <p style=\"margin:15pt 0cm 0pt;\"><span style=\"font-family:宋体;mso-ascii-font-family:Calibri;mso-hansi-font-family:Calibri;\"><span style=\"font-size:small;color:#335a89;\"><span style=\"font-size:18px;color:#003399;font-family:SimSun;\"><span style=\"font-size:18px;color:#003399;line-height:115%;font-family:宋体;mso-ascii-font-family:Calibri;mso-hansi-font-family:Calibri;mso-bidi-font-size:8.0pt;mso-bidi-font-family:Times New Roman;mso-ansi-language:EN-US;mso-fareast-language:ZH-CN;mso-bidi-language:AR-SA;\"><img alt=\"\" src=\"./upload/editor/20110210085515_46504.jpg\" border=\"0\" /></span></span></span></span></p>\r\n<p style=\"margin:15pt 0cm 0pt;\">&nbsp;</p>\r\n<p style=\"margin:15pt 0cm 0pt;\">&nbsp;</p>\r\n<p style=\"margin:15pt 0cm 0pt;\">&nbsp;</p>\r\n<p style=\"margin:15pt 0cm 0pt;\">&nbsp;</p>\r\n<p style=\"margin:15pt 0cm 0pt;\">&nbsp;</p>\r\n<p style=\"margin:15pt 0cm 0pt;\">&nbsp;</p>',1294709827),(17,4,'',1,255,'在线支付','在线支付',1294713631),(18,6,'',1,255,'会员修改密码','<p style=\"margin:15pt 0cm 0pt;\"><span style=\"font-family:宋体;mso-ascii-font-family:Calibri;mso-hansi-font-family:Calibri;\"><span style=\"font-size:small;color:#335a89;\"><span style=\"font-size:18px;color:#003399;font-family:SimSun;\">会员登录商城，点击右上角“用户中心”进入</span></span></span></p>\r\n<p style=\"margin:15pt 0cm 0pt;\"><span style=\"font-family:宋体;mso-ascii-font-family:Calibri;mso-hansi-font-family:Calibri;\"><span style=\"font-size:small;color:#335a89;\"><span style=\"font-size:18px;color:#003399;font-family:SimSun;\"><img alt=\"\" src=\"./upload/editor/20110210100016_71548.jpg\" border=\"0\" width=\"700px\" /></span></span></span></p>\r\n<p style=\"margin:15pt 0cm 0pt;\">&nbsp;</p>\r\n<p style=\"margin:15pt 0cm 0pt;\"><span style=\"font-family:宋体;mso-ascii-font-family:Calibri;mso-hansi-font-family:Calibri;\"><span style=\"font-size:small;color:#335a89;\"><span style=\"font-size:18px;color:#003399;font-family:SimSun;\">在我的账户板块点击“个人资料”进入</span></span></span></p>\r\n<p style=\"margin:15pt 0cm 0pt;\"><span style=\"font-family:宋体;mso-ascii-font-family:Calibri;mso-hansi-font-family:Calibri;\"><span style=\"font-size:small;color:#335a89;\"><span style=\"font-size:18px;color:#003399;font-family:SimSun;\"><img alt=\"\" src=\"./upload/editor/20110210100143_75461.jpg\" border=\"0\" /></span></span></span></p>\r\n<p style=\"margin:15pt 0cm 0pt;\">&nbsp;</p>\r\n<p style=\"margin:15pt 0cm 0pt;\"><span style=\"font-family:宋体;mso-ascii-font-family:Calibri;mso-hansi-font-family:Calibri;\"><span style=\"font-size:small;color:#335a89;\"><span style=\"font-size:18px;color:#003399;font-family:SimSun;\">在个人资料页面点击“修改密码”</span></span></span></p>\r\n<p style=\"margin:15pt 0cm 0pt;\"><span style=\"font-family:宋体;mso-ascii-font-family:Calibri;mso-hansi-font-family:Calibri;\"><span style=\"font-size:small;color:#335a89;\"><span style=\"font-size:18px;color:#003399;font-family:SimSun;\"><img alt=\"\" src=\"./upload/editor/20110210100354_81369.jpg\" border=\"0\" /></span></span></span></p>\r\n<p style=\"margin:15pt 0cm 0pt;\">&nbsp;</p>\r\n<p style=\"margin:15pt 0cm 0pt;\"><span style=\"font-family:宋体;mso-ascii-font-family:Calibri;mso-hansi-font-family:Calibri;\"><span style=\"font-size:small;color:#335a89;\"><span style=\"font-size:18px;color:#003399;font-family:SimSun;\">填写密码口令，点击“提交”</span></span></span></p>\r\n<p style=\"margin:15pt 0cm 0pt;\"><span style=\"font-family:宋体;mso-ascii-font-family:Calibri;mso-hansi-font-family:Calibri;\"><span style=\"font-size:small;color:#335a89;\"><span style=\"font-size:18px;color:#003399;font-family:SimSun;\"><img alt=\"\" src=\"./upload/editor/20110210100612_16845.jpg\" border=\"0\" /></span></span></span></p>\r\n<p style=\"margin:15pt 0cm 0pt;\">&nbsp;</p>\r\n<p style=\"margin:15pt 0cm 0pt;\">&nbsp;</p>\r\n<p style=\"margin:15pt 0cm 0pt;\">&nbsp;</p>',1294713819),(19,6,'',1,255,'会员修改个人资料','<p><span style=\"font-size:18px;color:#003399;line-height:115%;font-family:宋体;mso-ascii-font-family:Calibri;mso-hansi-font-family:Calibri;mso-bidi-font-size:8.0pt;mso-bidi-font-family:Times New Roman;mso-ansi-language:EN-US;mso-fareast-language:ZH-CN;mso-bidi-language:AR-SA;\">会员登录商城，点击右上角“用户中心”进入</span></p>\r\n<p><span style=\"font-size:18px;color:#003399;line-height:115%;font-family:宋体;mso-ascii-font-family:Calibri;mso-hansi-font-family:Calibri;mso-bidi-font-size:8.0pt;mso-bidi-font-family:Times New Roman;mso-ansi-language:EN-US;mso-fareast-language:ZH-CN;mso-bidi-language:AR-SA;\"><img alt=\"\" src=\"./upload/editor/20110210100846_23142.jpg\" border=\"0\" /></span></p>\r\n<p>&nbsp;</p>\r\n<span style=\"font-size:18px;color:#003399;line-height:115%;font-family:宋体;mso-ascii-font-family:Calibri;mso-hansi-font-family:Calibri;mso-bidi-font-size:8.0pt;mso-bidi-font-family:Times New Roman;mso-ansi-language:EN-US;mso-fareast-language:ZH-CN;mso-bidi-language:AR-SA;\"> <p style=\"margin:15pt 0cm 0pt;\"><span style=\"font-family:宋体;mso-ascii-font-family:Calibri;mso-hansi-font-family:Calibri;\"><span style=\"font-size:small;color:#335a89;\">在用户中心的我的账户板块点击“个人资料”</span></span></p>\r\n<p style=\"margin:15pt 0cm 0pt;\"><span style=\"font-family:宋体;mso-ascii-font-family:Calibri;mso-hansi-font-family:Calibri;\"><img alt=\"\" src=\"./upload/editor/20110210101042_74191.jpg\" border=\"0\" /></span></p>\r\n<p style=\"margin:15pt 0cm 0pt;\">&nbsp;</p>\r\n<span style=\"font-family:宋体;mso-ascii-font-family:Calibri;mso-hansi-font-family:Calibri;\"> <p style=\"margin:15pt 0cm 0pt;\"><span style=\"font-family:宋体;mso-ascii-font-family:Calibri;mso-hansi-font-family:Calibri;\"><span style=\"font-size:small;color:#335a89;\">也可在用户中心默认界面（账户概况）点击“编辑个人资料”</span></span></p>\r\n<p style=\"margin:15pt 0cm 0pt;\"><span style=\"font-family:宋体;mso-ascii-font-family:Calibri;mso-hansi-font-family:Calibri;\"><img alt=\"\" src=\"./upload/editor/20110210101209_36181.jpg\" border=\"0\" /></span></p>\r\n<span style=\"font-family:宋体;mso-ascii-font-family:Calibri;mso-hansi-font-family:Calibri;\"> <p style=\"margin:15pt 0cm 0pt;\">&nbsp;</p>\r\n<p style=\"margin:15pt 0cm 0pt;\"><span style=\"font-family:宋体;mso-ascii-font-family:Calibri;mso-hansi-font-family:Calibri;\"><span style=\"font-size:small;color:#335a89;\">填写个人信息资料，点击“更改头像”</span></span></p>\r\n<p style=\"margin:15pt 0cm 0pt;\"><span style=\"font-family:宋体;mso-ascii-font-family:Calibri;mso-hansi-font-family:Calibri;\"><span style=\"font-size:small;color:#335a89;\"><img alt=\"\" src=\"./upload/editor/20110210101333_62566.jpg\" border=\"0\" /></span></span></p>\r\n<span style=\"font-family:宋体;mso-ascii-font-family:Calibri;mso-hansi-font-family:Calibri;\"><span style=\"font-size:small;color:#335a89;\"> <p style=\"margin:15pt 0cm 0pt;\"><span style=\"font-family:宋体;mso-ascii-font-family:Calibri;mso-hansi-font-family:Calibri;\"><span style=\"font-size:small;\">进行本地上传图片更改头像</span></span></p>\r\n<p style=\"margin:15pt 0cm 0pt;\"><span style=\"font-family:宋体;mso-ascii-font-family:Calibri;mso-hansi-font-family:Calibri;\"><img alt=\"\" src=\"./upload/editor/20110210101528_80109.jpg\" border=\"0\" /></span></p>\r\n<p style=\"margin:15pt 0cm 0pt;\">&nbsp;</p>\r\n<p style=\"margin:15pt 0cm 0pt;\"><span style=\"font-family:宋体;mso-ascii-font-family:Calibri;mso-hansi-font-family:Calibri;\"><span style=\"font-size:18px;color:#003399;line-height:115%;font-family:宋体;mso-ascii-font-family:Calibri;mso-hansi-font-family:Calibri;mso-bidi-font-size:8.0pt;mso-bidi-font-family:Times New Roman;mso-ansi-language:EN-US;mso-fareast-language:ZH-CN;mso-bidi-language:AR-SA;\">修改头像、填写完成个人信息后点击“保存修改”</span></span></p>\r\n<p style=\"margin:15pt 0cm 0pt;\"><span style=\"font-family:宋体;mso-ascii-font-family:Calibri;mso-hansi-font-family:Calibri;\"><span style=\"font-size:18px;color:#003399;line-height:115%;font-family:宋体;mso-ascii-font-family:Calibri;mso-hansi-font-family:Calibri;mso-bidi-font-size:8.0pt;mso-bidi-font-family:Times New Roman;mso-ansi-language:EN-US;mso-fareast-language:ZH-CN;mso-bidi-language:AR-SA;\"><img alt=\"\" src=\"./upload/editor/20110210101632_31534.jpg\" border=\"0\" /></span></span></p>\r\n<p style=\"margin:15pt 0cm 0pt;\">&nbsp;</p>\r\n<span style=\"font-family:宋体;mso-ascii-font-family:Calibri;mso-hansi-font-family:Calibri;\"><span style=\"font-size:18px;color:#003399;line-height:115%;font-family:宋体;mso-ascii-font-family:Calibri;mso-hansi-font-family:Calibri;mso-bidi-font-size:8.0pt;mso-bidi-font-family:Times New Roman;mso-ansi-language:EN-US;mso-fareast-language:ZH-CN;mso-bidi-language:AR-SA;\"> <p style=\"margin:15pt 0cm 0pt;\"><span style=\"font-family:宋体;mso-ascii-font-family:Calibri;mso-hansi-font-family:Calibri;\"><span style=\"font-size:small;color:#335a89;\"><span style=\"color:#003399;\">完成个人信息修改</span></span></span></p>\r\n<p style=\"margin:15pt 0cm 0pt;\"><span style=\"font-family:宋体;mso-ascii-font-family:Calibri;mso-hansi-font-family:Calibri;\"><span style=\"font-size:small;color:#335a89;\"><span style=\"color:#003399;\"><img alt=\"\" src=\"./upload/editor/20110210101732_37374.jpg\" border=\"0\" /></span></span></span></p>\r\n</span></span> <p style=\"margin:15pt 0cm 0pt;\">&nbsp;</p>\r\n</span></span> <p style=\"margin:15pt 0cm 0pt;\">&nbsp;</p>\r\n<p style=\"margin:15pt 0cm 0pt;\">&nbsp;</p>\r\n<p style=\"margin:15pt 0cm 0pt;\">&nbsp;</p>\r\n</span></span></span>',1294713836),(20,6,'',1,255,'商品发布','<p style=\"margin:15pt 0cm 0pt;\"><span style=\"font-family:宋体;mso-ascii-font-family:Calibri;mso-hansi-font-family:Calibri;\"><span style=\"font-size:small;color:#335a89;\">会员登陆商城进入用户中心，在我是卖家板块找到“商品管理”点击</span></span></p>\r\n<p style=\"margin:15pt 0cm 0pt;\"><span style=\"font-family:宋体;mso-ascii-font-family:Calibri;mso-hansi-font-family:Calibri;\"><img alt=\"\" src=\"./upload/editor/20110210102523_43795.jpg\" border=\"0\" /></span></p>\r\n<p style=\"margin:15pt 0cm 0pt;\">&nbsp;</p>\r\n<span style=\"font-family:宋体;mso-ascii-font-family:Calibri;mso-hansi-font-family:Calibri;\"> <p style=\"margin:15pt 0cm 0pt;\"><span style=\"font-family:宋体;mso-ascii-font-family:Calibri;mso-hansi-font-family:Calibri;\"><span style=\"font-size:small;color:#335a89;\">在商品列表中点击“新增商品”</span></span></p>\r\n<p style=\"margin:15pt 0cm 0pt;\"><span style=\"font-family:宋体;mso-ascii-font-family:Calibri;mso-hansi-font-family:Calibri;\"><img alt=\"\" src=\"./upload/editor/20110210102626_41732.jpg\" border=\"0\" /></span></p>\r\n<p style=\"margin:15pt 0cm 0pt;\">&nbsp;</p>\r\n<span style=\"font-family:宋体;mso-ascii-font-family:Calibri;mso-hansi-font-family:Calibri;\"> <p style=\"margin:15pt 0cm 0pt;\"><span style=\"font-family:宋体;mso-ascii-font-family:Calibri;mso-hansi-font-family:Calibri;\"><span style=\"font-size:small;color:#335a89;\">填写商品的详细信息，点击“提交”发布</span></span></p>\r\n<p style=\"margin:15pt 0cm 0pt;\"><span style=\"font-family:宋体;mso-ascii-font-family:Calibri;mso-hansi-font-family:Calibri;\"><img alt=\"\" src=\"./upload/editor/20110210102729_99892.jpg\" border=\"0\" /></span></p>\r\n<p style=\"margin:15pt 0cm 0pt;\">&nbsp;</p>\r\n<span style=\"font-family:宋体;mso-ascii-font-family:Calibri;mso-hansi-font-family:Calibri;\"> <p style=\"margin:15pt 0cm 0pt;\"><span style=\"font-family:宋体;mso-ascii-font-family:Calibri;mso-hansi-font-family:Calibri;\"><span style=\"font-size:small;color:#335a89;\">提示添加商品成功</span></span></p>\r\n<p style=\"margin:15pt 0cm 0pt;\"><span style=\"font-family:宋体;mso-ascii-font-family:Calibri;mso-hansi-font-family:Calibri;\"><img alt=\"\" src=\"./upload/editor/20110210102812_84565.jpg\" border=\"0\" /></span></p>\r\n</span></span></span>',1294713852),(21,6,'',1,255,'修改收货地址','<p style=\"margin:15pt 0cm 0pt;\"><span style=\"font-family:宋体;mso-ascii-font-family:Calibri;mso-hansi-font-family:Calibri;\"><span style=\"font-size:small;color:#335a89;\"><span style=\"font-size:18px;color:#003399;\">会员登录商城进入用户中心，在我是买家板块找到“我的地址”点击进入</span></span></span></p>\r\n<p style=\"margin:15pt 0cm 0pt;\"><span style=\"font-family:宋体;mso-ascii-font-family:Calibri;mso-hansi-font-family:Calibri;\"><span style=\"font-size:small;color:#335a89;\"><span style=\"font-size:18px;color:#003399;\"><img alt=\"\" src=\"./upload/editor/20110210103254_50780.jpg\" border=\"0\" /></span></span></span></p>\r\n<p style=\"margin:15pt 0cm 0pt;\">&nbsp;</p>\r\n<p style=\"margin:15pt 0cm 0pt;\"><span style=\"font-family:宋体;mso-ascii-font-family:Calibri;mso-hansi-font-family:Calibri;\"><span style=\"font-size:small;color:#335a89;\"><span style=\"font-size:18px;color:#003399;\"><span style=\"font-size:18px;color:#003399;line-height:115%;font-family:SimSun;mso-ascii-font-family:Calibri;mso-hansi-font-family:Calibri;mso-bidi-font-size:8.0pt;mso-bidi-font-family:Times New Roman;mso-ansi-language:EN-US;mso-fareast-language:ZH-CN;mso-bidi-language:AR-SA;\">在地址列表页面点击该地址后的“编辑”</span></span></span></span></p>\r\n<p style=\"margin:15pt 0cm 0pt;\"><span style=\"font-family:宋体;mso-ascii-font-family:Calibri;mso-hansi-font-family:Calibri;\"><span style=\"font-size:small;color:#335a89;\"><span style=\"font-size:18px;color:#003399;\"><span style=\"font-size:18px;color:#003399;line-height:115%;font-family:SimSun;mso-ascii-font-family:Calibri;mso-hansi-font-family:Calibri;mso-bidi-font-size:8.0pt;mso-bidi-font-family:Times New Roman;mso-ansi-language:EN-US;mso-fareast-language:ZH-CN;mso-bidi-language:AR-SA;\"><img alt=\"\" src=\"./upload/editor/20110210103509_39444.jpg\" border=\"0\" /></span></span></span></span></p>\r\n<p style=\"margin:15pt 0cm 0pt;\">&nbsp;</p>\r\n<span style=\"font-family:宋体;mso-ascii-font-family:Calibri;mso-hansi-font-family:Calibri;\"><span style=\"font-size:small;color:#335a89;\"><span style=\"font-size:18px;color:#003399;\"><span style=\"font-size:18px;color:#003399;line-height:115%;font-family:SimSun;mso-ascii-font-family:Calibri;mso-hansi-font-family:Calibri;mso-bidi-font-size:8.0pt;mso-bidi-font-family:Times New Roman;mso-ansi-language:EN-US;mso-fareast-language:ZH-CN;mso-bidi-language:AR-SA;\"> <p style=\"margin:15pt 0cm 0pt;\"><span style=\"font-family:宋体;mso-ascii-font-family:Calibri;mso-hansi-font-family:Calibri;\">可对该地址的相关信息进行修改后，点击“编辑地址”保存</span></p>\r\n<p style=\"margin:15pt 0cm 0pt;\"><span style=\"font-family:宋体;mso-ascii-font-family:Calibri;mso-hansi-font-family:Calibri;\"><img alt=\"\" src=\"./upload/editor/20110210103710_47524.jpg\" border=\"0\" /></span></p>\r\n<p style=\"margin:15pt 0cm 0pt;\">&nbsp;</p>\r\n<p style=\"margin:15pt 0cm 0pt;\">&nbsp;</p>\r\n</span></span></span></span> <p style=\"margin:15pt 0cm 0pt;\">&nbsp;</p>\r\n<p style=\"margin:15pt 0cm 0pt;\">&nbsp;</p>',1294713910),(22,7,'',1,255,'关于ShopNC','<p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 天津市网城创想科技有限责任公司位于天津市南开区，是专业从事生产管理信息化领域技术咨询和软件开发的高新技术企业。公司拥有多名技术人才和资深的行业解决方案专家。</p>\r\n<p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 公司拥有一支勇于开拓、具有战略眼光和敏锐市场判断力的市场营销队伍，一批求实敬业，追求卓越的行政管理人才，一个能征善战，技术优秀，经验丰富的开发团队。公司坚持按现代企业制度和市场规律办事，在扩大经营规模的同时，注重企业经济运行质量，在自主产品研发及承接软件项目方面获得了很强的竞争力。 我公司也积极参与国内传统企业的信息化改造，引进国际化产品开发的标准，规范软件开发流程，通过提升各层面的软件开发人才的技术素质，打造国产软件精品，目前已经开发出具有自主知识产权的网络商城软件，还在积极开发基于电子商务平台高效能、高效益的管理系统。为今后进一步开拓国内市场打下坚实的基础。公司致力于构造一个开放、发展的人才平台，积极营造追求卓越、积极奉献的工作氛围，把“以人为本”的理念落实到每一项具体工作中，为那些锋芒内敛，激情无限的业界精英提供充分的发展空间，优雅自信、从容自得的工作环境，事业雄心与生活情趣两相兼顾的生活方式。并通过每个员工不断提升自我，以自己的独特价值观对工作与生活作最准确的判断，使我们每一个员工彰显出他们出色的自我品位，独有的工作个性和卓越的创新风格，让他们时刻保持振奋、不断鼓舞内心深处的梦想，永远走在时代潮流前端。公司发展趋势 励精图治，展望未来。公司把发展产业策略与发掘人才策略紧密结合，广纳社会精英，挖掘创新潜能，以人为本，凝聚人气，努力营造和谐宽松的工作氛围，为优秀人才的脱颖而出提供机遇。公司将在深入发展软件产业的同时，通过不懈的努力，来塑造大型软件公司的辉煌形象。 </p>',1294714215),(23,7,'',1,255,'联系我们','<p>欢迎您对我们的站点、工作、产品和服务提出自己宝贵的意见或建议。我们将给予您及时答复。同时也欢迎您到我们公司来洽商业务。</p>\r\n<p><br />\r\n<strong>公司名称</strong>： 天津市网城创想科技有限责任公司 <br />\r\n<strong>通信地址</strong>： 天津市南开区红旗路220号慧谷大厦712 <br />\r\n<strong>邮政编码</strong>： 300072 <br />\r\n<strong>电话</strong>： 400-611-5098 <br />\r\n<strong>商务洽谈</strong>： 86-022-87631069 <br />\r\n<strong>传真</strong>： 86-022-87631069 <br />\r\n<strong>软件企业编号</strong>： 120193000029441 <br />\r\n<strong>软件著作权登记号</strong>： 2008SR07843 <br />\r\n<strong>ICP备案号</strong>： 津ICP备08000171号 </p>',1294714228),(24,7,'',1,255,'招聘英才','<dl> <h3>PHP程序员</h3>\r\n<dt>职位要求： <dd>熟悉PHP5开发语言；<br />\r\n熟悉MySQL5数据库，同时熟悉sqlserver，oracle者优先；<br />\r\n熟悉面向对象思想，MVC三层体系，至少使用过目前已知PHP框架其中一种；<br />\r\n熟悉SERVER2003/Linux操作系统，熟悉常用Linux操作命令；<br />\r\n熟悉Mysql数据库应用开发，了解Mysql的数据库配置管理、性能优化等基本操作技能；<br />\r\n熟悉jquery，smarty等常用开源软件；<br />\r\n具备良好的代码编程习惯及较强的文档编写能力；<br />\r\n具备良好的团队合作能力；<br />\r\n熟悉设计模式者优先；<br />\r\n熟悉java，c++,c#,python其中一种者优先； </dd> <dt>学历要求： <dd>大本 </dd> <dt>工作经验： <dd>一年以上 </dd> <dt>工作地点： <dd>天津 </dd></dl> <dl> <h3>网页设计（2名）</h3>\r\n<dt>岗位职责： <dd>网站UI设计、 切片以及HTML制作。 </dd> <dt>职位要求： <dd>有大型网站设计经验；有网站改版、频道建设经验者优先考虑； <br />\r\n熟练掌握photoshop,fireworks,dreamwaver等设计软件； <br />\r\n熟练运用Div+Css制作网页，符合CSS2.0-W3C标准，并掌握不同浏览器下，不同版本下CSS元素的区别；<br />\r\n熟悉网站制作流程，能运用并修改简单JavaScript类程序； <br />\r\n积极向上，有良好的人际沟通能力，良好的工作协调能力，踏实肯干的工作精神；具有良好的沟通表达能力，<br />\r\n需求判断力，团队协作能力； <br />\r\n请应聘者在简历中提供个人近期作品连接。 </dd> <dt>学历要求： <dd>专科 </dd> <dt>工作经验： <dd>一年以上 </dd> <dt>工作地点： <dd>天津 </dd></dl> <dl> <h3>方案策划（1名）</h3>\r\n<dt>职位要求： <dd>2年以上的文案编辑类相关工作经验，具备一定的文字功底，有极强的语言表达和逻辑思维能力， 能独立完成项目方案的编写，拟草各种协议。熟悉使用办公软件。 </dd> <dt>学历要求： <dd>大专以上 </dd> <dt>工作经验： <dd>一年以上 </dd> <dt>工作地点： <dd>天津 </dd></dl>',1294714240),(25,7,'',1,255,'合作及洽谈','<p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; ShopNC希望与服务代理商、合作伙伴并肩合作，携手开拓日益广阔的网络购物软件市场。如果您拥有好的建议，拥有丰富渠道资源、拥有众多目标客户、拥有相应的市场资源，并希望与ShopNC进行深度业务合作， 欢迎成为ShopNC业务合作伙伴，请联系。</p>\r\n<p>&nbsp;</p>\r\n<p><strong>公司名称</strong>： 天津市网城创想科技有限责任公司 <br />\r\n<strong>通信地址</strong>： 天津市南开区红旗路220号慧谷大厦712 <br />\r\n<strong>邮政编码</strong>： 300072 <br />\r\n<strong>电话</strong>： 400-611-5098 <br />\r\n<strong>商务洽谈</strong>： 86-022-87631069 <br />\r\n<strong>传真</strong>： 86-022-87631069 <br />\r\n</p>',1294714257),(26,5,'',1,255,'联系卖家','联系卖家',1294714858),(28,4,'',1,255,'分期付款','分期付款<br />',1309835564),(29,4,'',1,255,'邮局汇款','邮局汇款<br />',1309835582),(30,4,'',1,255,'公司转账','公司转账<br />',1309835600),(31,5,'',1,255,'退换货政策','退换货政策',1309835651),(32,5,'',1,255,'退换货流程','退换货流程',1309835666),(33,5,'',1,255,'返修/退换货','返修/退换货',1309835679),(34,5,'',1,255,'退款申请','退款申请',1309835699),(35,1,'http://www.shopnc.net/',1,255,'2.2版火爆销售中','2.2版火爆销售中<br />',1342368000),(36,1,'',1,253,'新版“广告管理”功能说明','<h3 style=\"font-size:16px;text-align:center;\">ShopNC商城系统1.2版本之\"广告管理\"功能说明</h3>\r\n<p style=\"text-indent:2em;\">网站广告是一个站点的主要收入来源，也是站长运营网站的主要目的之一。一个功能强大，灵活自由，方便管理的广告系统是每一个站长迫切需要的。</p>\r\n<p style=\"text-indent:2em;\">ShopNC商城系统1.2版本较之前版本在广告管理这个模块上有了重大的改进，彻底颠覆了之前非常不灵活的广告系统，使站长可以自由地增减广告位，对其定价并放到网站的任何一个地方，系统提供了\"幻灯片\"、\"图片\"、\"文字\"、\"Flash\"等多种广告形式供站长选择。同时全新的广告系统也具有商户直接在线购买广告的功能（和1.2版本新增\"金币\"系统配合使用），使网站广告的购买变的非常简单、直接、便利，能极大程度地提高站长的收入。在线生成的广告统计图功能也是ShopNC商城系统1.2版本的一大特色，通过在线即时生成Flash形式的统计图表，广告主及站长可以直观地掌握广告点击率情况（系统提供了\"折线图\"、\"饼形图\"、\"柱形图\"等展示形式），同时站长在后台可以通过广告位点击率排序功能，迅速得知哪些广告位最容易被用户点击，进而调整站点的广告位价格，进一步增加站长的收入。</p>\r\n<p></p>\r\n<p style=\"color:red;\">注：详细使用方法请参阅安装包内document文件夹中的相关帮助文档</p>',1310198494),(37,1,'',1,251,'如何扩充水印字体库','<h3 style=\"font-size:16px;text-align:center;\">扩充水印功能字体库支持详解</h3>\r\n<p style=\"text-indent:2em;\"><b>使用方法：</b>将您下载的字体库上传到网站目录\"网站根目录\\resource\\font\\\"这个文件夹内，同时需要修改此文件夹下的font.info.php文件。例如：您下载了一个\"宋体\"字库simsun.ttf，将其放置于前面所述文件夹内，使用代码编辑工具（如EditPlus）打开font.info.php文件在其中的$fontInfo = array(\'arial\'=&gt;\'Arial\')数组后面添加宋体字库信息，\"=&gt;\"符号左边是文件名，右边是您想在网站上显示的文字信息。</p>\r\n<p style=\"text-indent:2em;\">添加后的样子如：array(\'arial\'=&gt;\'Arial\',\'simsun\'=&gt;\'宋体\')，所示。</p>\r\n<p></p>\r\n<p style=\"text-indent:2em;\">您可以在ShopNC官方网站下载字体库文件，下载地址：<a href=\"http://www.shopnc.net/downloads/product/multishop/fonts.zip\">字体库文件[fonts.zip]</a>。</p>\r\n<p></p>\r\n<p style=\"color:red;\">注：详细使用方法也可参阅安装包内upload文件夹中的readme文档</p>',1310200272),(38,1,'',1,252,'如何利用直通车让站长盈利','<h3 style=\"font-size:16px;text-align:center;\">ShopNC商城系统1.2版本之\"直通车\"功能说明</h3>\r\n<p style=\"text-indent:2em;\">直通车是我们经过对客户习惯的深入研究而制作的一种让站长轻松盈利的模式。商家（即平台卖家用户）使用直通车进行商品营销。商家通过支付金币使商品加入直通车后能够优先显示在商品列表中商品剩余金币越多排名越靠前，并在商品名称下方显示直通车标志。商品优先显示提高了用户点击和购买机会。站长通过直通车方便了平台的推广并且实现实实在在的盈利。站长更好的为商家服务，及商家之所需，使双方都得到相应的回报。并且直通车平台实现按时间自动扣除直通车金币功能，免除了站长繁杂的操作流程。</p>\r\n<p></p>\r\n<p style=\"color:red;\">注：详细使用方法请参阅安装包内document文件夹中的相关帮助文档</p>',1310203091),(39,1,'',1,254,'UCenter整合说明','<h3 style=\"font-size:16px;text-align:center;\">ShopNC商城系统1.2版本之\"Ucenter整合\"功能说明</h3>\r\n<p style=\"text-indent:2em;\">平台之间的会员互通是站长们的一大需求，会员的互通方便站长多种不同模式的平台共同进行运营，利用各种平台之间的微妙联系增强整体平台的用户粘度和商业价值。</p>\r\n<p style=\"text-indent:2em;\">ShopNC商城系统1.2版很好的与UCenter进行了整合，实现会员的互通，方便站长进行多种模式的系统进行协调运营。在进行UCenter整合的时候需要注意以下几点：</p>\r\n<ul>\r\n<li>如果Ucenter使用的是utf8，则商城的也要用utf8的，如果用gbk版的就会出问题。</li>\r\n<li>建议在使用前就整合，因为整合成功后，原系统中的会员将不能再登录，都以Ucenter的为主。</li>\r\n<li>在商城填完信息后就能在Ucenter中看到\"通信成功\"提示，说明整合完成。</li>\r\n<li>如果出现了不能同步登录问题，可以在论坛的后台更新缓存。</li>\r\n</ul>\r\n<p></p>\r\n<p style=\"color:red;\">注：详细使用方法请参阅安装包内document文件夹中的相关帮助文档</p>',1310262555),(40,1,'',1,254,'ShopNC官方使用提示信息','<p style=\"text-indent:2em;\"><b>官方提示：</b>ShopNC商城系统可供站长们免费下载使用，具体安装使用协议详见安装过程中的授权协议文档。如需获得更多帮助或更多使用权限，请联系官方购买授权。</p>\r\n<p style=\"text-indent:2em;\">ShopNC开发团队力争做最适合站长运营的社区化电子商城系统，欢迎各位站长与官方联系提出您的宝贵需求建议。您的需要就是我们的动力，您的回报正是我们所做的考虑。</p>\r\n<ul style=\"line-height:150%;\">\r\n<li>官方网址：<a href=\"http://www.shopnc.net\">http://www.shopnc.net</a></li>\r\n<li>官方论坛：<a href=\"http://www.shopnc.net/bbs/\">http://www.shopnc.net/bbs/</a></li>\r\n<li>在线咨询QQ：1045269763 、921362895</li>\r\n<li>免费咨询电话：<b>400-611-5098</b></li>\r\n</ul>',1310268647),(41,2,'',1,255,'积分细则','积分细则积分细则',1322621203),(42,2,'',1,255,'积分兑换说明','积分兑换说明积分兑换说明<br />',1322621243),(43,1,'',1,254,'新功能使用说明','<p>&nbsp;&nbsp;&nbsp; 新浪账号登录需要申请开通后才能在后台设置开启。</p>\r\n<p>&nbsp;&nbsp;&nbsp; 积分兑换、预存款、代金劵的使用在网站设置中可以选择是否开通相应功能。</p>\r\n<p>&nbsp;&nbsp;&nbsp; 其它的详细说明请参考安装包内document文件夹中的相关帮助文档。</p>',1322789334),(44,1,'',1,255,'促销功能限时折扣使用说明','<p>\r\n	一、<span style=\"font-size:10.5000pt;font-family:\'宋体\';\">卖家进入用户中心 <span>-&gt; </span><span>卖家 </span><span>-&gt; </span><span>促销管理 </span><span>-&gt; </span><span>商品促销 </span><span>-&gt; </span><span>限时折扣，如果当前没有可用套餐，系统会提示卖家首先购买套餐。</span></span><span style=\"font-size:10.5pt;font-family:\'宋体\';\"></span> \r\n</p>\r\n<p>\r\n	<img src=\"./upload/editor/20120504022113_65206.png\" alt=\"\" /> \r\n</p>\r\n<p>\r\n	二、<span style=\"font-size:10.5000pt;font-family:\'宋体\';\">卖家点击购买套餐按钮，进入套餐购买界面，填写要购买的数量，点击提交按钮完成套餐购买申请。</span><span style=\"font-size:10.5000pt;font-family:\'宋体\';\"></span> \r\n</p>\r\n<p>\r\n	<img src=\"./upload/editor/20120504022155_70768.png\" alt=\"\" /> \r\n</p>\r\n<p>\r\n	三、<span style=\"font-size:10.5000pt;font-family:\'宋体\';\">套餐申请后，在平台没有审核通过前，系统会提示卖家已经购买套餐但是没有通过审核。</span><span style=\"font-size:10.5000pt;font-family:\'宋体\';\"></span> \r\n</p>\r\n<p>\r\n	<img src=\"./upload/editor/20120504022223_45601.png\" alt=\"\" /> \r\n</p>\r\n<p>\r\n	四、<span style=\"font-size:10.5000pt;font-family:\'宋体\';\">套餐申请通过后，卖家可以发布限时折扣活动。点击添加活动按钮进入活动添加页面。点击套餐列表链接可以查看套餐记录。</span><span style=\"font-size:10.5000pt;font-family:\'宋体\';\"></span><br />\r\n<span style=\"font-size:10.5000pt;font-family:\'宋体\';\"></span> \r\n</p>\r\n<p>\r\n	<img src=\"./upload/editor/20120504022253_29124.png\" alt=\"\" /> \r\n</p>\r\n<p>\r\n	五、<span style=\"font-size:10.5000pt;font-family:\'宋体\';\">卖家填写活动名称、开始时间、结束时间和默认折扣，点击提交按钮进入商品选择页面。</span><span style=\"font-size:10.5000pt;font-family:\'宋体\';\"></span> \r\n</p>\r\n<p>\r\n	<img src=\"./upload/editor/20120504022317_65502.png\" alt=\"\" /> \r\n</p>\r\n<p>\r\n	六、<span style=\"font-size:10.5000pt;font-family:\'宋体\';\">点击添加商品按钮选择，想要参加活动的商品。</span><span style=\"font-size:10.5000pt;font-family:\'宋体\';\"></span> \r\n</p>\r\n<p>\r\n	<img src=\"./upload/editor/20120504022344_86225.png\" alt=\"\" /> \r\n</p>\r\n<p>\r\n	七、<span style=\"font-size:10.5000pt;font-family:\'宋体\';\">点击商品后边的添加按钮，把所选商品添加到当前活动中。商品选择完毕后点击下方的返回活动管理按钮，回到活动管理页面。</span><span style=\"font-size:10.5000pt;font-family:\'宋体\';\"></span> \r\n</p>\r\n<p>\r\n	<img src=\"./upload/editor/20120504022411_71122.png\" alt=\"\" /> \r\n</p>\r\n<p>\r\n	八、<span style=\"font-size:10.5pt;font-family:\'宋体\';\">在活动管理页面可以单独设置每个商品的折扣率，在确认无误后点击发布活动按钮完成限时折扣活动的发布。</span> \r\n</p>\r\n<p>\r\n	<span style=\"font-size:10.5pt;font-family:\'宋体\';\"><img src=\"./upload/editor/20120504022440_49064.png\" alt=\"\" /></span> \r\n</p>\r\n<p>\r\n	<span style=\"font-size:10.5000pt;font-family:\'宋体\';\">九、限时折扣商品会优先显示在商品列表中，在商品列表页还可以对限时折扣商品进行筛选。进入商品详细页面后会出现限时折扣标识，点击立刻购买按钮即可以限时折扣价购买商品。</span><span style=\"font-size:10.5000pt;font-family:\'宋体\';\"></span><span style=\"font-size:10.5pt;font-family:\'宋体\';\"></span> \r\n</p>\r\n<p>\r\n	<span style=\"font-size:10.5pt;font-family:\'宋体\';\"><img src=\"./upload/editor/20120504022517_84608.png\" alt=\"\" /></span> \r\n</p>\r\n<p>\r\n	<span style=\"font-size:10.5000pt;font-family:\'宋体\';\"><img src=\"./upload/editor/20120504022542_81820.png\" alt=\"\" /></span> \r\n</p>\r\n<br />',1336098353),(45,1,'',1,255,'促销功能满即送使用说明','<p>\r\n	一、<span style=\"font-size:10.5000pt;font-family:\'宋体\';\">卖家进入用户中心&nbsp;<span>-&gt;&nbsp;</span><span>卖家&nbsp;</span><span>-&gt;&nbsp;</span><span>促销管理&nbsp;</span><span>-&gt;&nbsp;</span><span>商品促销&nbsp;</span><span>-&gt;&nbsp;</span><span>满即送，如果当前没有可用套餐，系统会提示卖家首先购买套餐。</span></span><span style=\"font-size:10.5pt;font-family:\'宋体\';\"></span>\r\n</p>\r\n<p>\r\n	<img src=\"./upload/editor/20120504025039_83457.png\" alt=\"\" />\r\n</p>\r\n<p>\r\n	二、<span style=\"font-size:10.5000pt;font-family:\'宋体\';\">卖家点击购买套餐按钮，进入套餐购买界面，填写要购买的数量，点击提交按钮完成套餐购买申请。</span><span style=\"font-size:10.5000pt;font-family:\'宋体\';\"></span>\r\n</p>\r\n<p>\r\n	<img src=\"./upload/editor/20120504025105_16849.png\" alt=\"\" />\r\n</p>\r\n<p>\r\n	三、<span style=\"font-size:10.5000pt;font-family:\'宋体\';\">套餐申请后，在平台没有审核通过前，系统会提示卖家已经购买套餐但是没有通过审核。</span><span style=\"font-size:10.5000pt;font-family:\'宋体\';\"></span>\r\n</p>\r\n<p>\r\n	<img src=\"./upload/editor/20120504025127_50866.png\" alt=\"\" />\r\n</p>\r\n<p>\r\n	四、<span style=\"font-size:10.5000pt;font-family:\'宋体\';\">套餐申请通过后，卖家可以发布满即送活动。点击添加活动按钮进入活动添加页面。点击套餐列表链接可以查看套餐记录。</span>\r\n</p>\r\n<p>\r\n	<img src=\"./upload/editor/20120504025157_78499.png\" alt=\"\" />\r\n</p>\r\n<p>\r\n	五、<span style=\"font-size:10.5000pt;font-family:\'宋体\';\">首先填写活动名称、开始时间和结束时间</span><span style=\"font-size:10.5000pt;font-family:\'宋体\';\"></span><br />\r\n<span style=\"font-size:10.5000pt;font-family:\'宋体\';\"></span>\r\n</p>\r\n<p>\r\n	<img src=\"./upload/editor/20120504025227_23504.png\" alt=\"\" />\r\n</p>\r\n<p>\r\n	六、<span style=\"font-size:10.5000pt;font-family:\'宋体\';\">满即送活动最多可以设置三个级别，每个级别可以分别选择参加减现金、包邮和赠送礼品等形式。点击新增级别可以设置下一个级别。</span><span style=\"font-size:10.5000pt;font-family:\'宋体\';\"></span>\r\n</p>\r\n<p>\r\n	<img src=\"./upload/editor/20120504025246_12664.png\" alt=\"\" />\r\n</p>\r\n<p>\r\n	<img src=\"./upload/editor/20120504025255_72740.png\" alt=\"\" />\r\n</p>\r\n<p>\r\n	<img src=\"./upload/editor/20120504025305_38366.png\" alt=\"\" />\r\n</p>\r\n<p>\r\n	七、<span style=\"font-size:10.5000pt;font-family:\'宋体\';\">满即送规则设置完成后，填写备注信息，点击提交按钮完成满即送活动的发布。</span><span style=\"font-size:10.5000pt;font-family:\'宋体\';\"></span>\r\n</p>\r\n<p>\r\n	<img src=\"./upload/editor/20120504025316_42126.png\" alt=\"\" />\r\n</p>\r\n<p>\r\n	八、<span style=\"font-size:10.5000pt;font-family:\'宋体\';\">进入商品详细页面后参加满即送活动的店铺会出现满即送标识。购物车结算时如果符合满即送规则将按照满即送活动进行返利。</span><span style=\"font-size:10.5000pt;font-family:\'宋体\';\"></span>\r\n</p>\r\n<p>\r\n	<img src=\"./upload/editor/20120504025440_23068.png\" alt=\"\" />\r\n</p>\r\n<p>\r\n	<img src=\"./upload/editor/20120504025453_64906.png\" alt=\"\" />\r\n</p>\r\n<p>\r\n	<img src=\"./upload/editor/20120504025501_90167.png\" alt=\"\" />\r\n</p>\r\n<br />',1336100107);

/*Table structure for table `sys_article_category` */

DROP TABLE IF EXISTS `sys_article_category`;

CREATE TABLE `sys_article_category` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '索引ID',
  `ref_code` varchar(255) DEFAULT NULL COMMENT '分类标识码',
  `name` varchar(100) NOT NULL COMMENT '分类名称',
  `parent_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '父ID',
  `order` tinyint(1) unsigned NOT NULL DEFAULT '255' COMMENT '排序',
  PRIMARY KEY (`id`),
  KEY `ac_parent_id` (`parent_id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8 COMMENT='文章分类表';

/*Data for the table `sys_article_category` */

insert  into `sys_article_category`(`id`,`ref_code`,`name`,`parent_id`,`order`) values (1,'notice','商城公告',0,255),(2,'member','帮助中心',0,255),(3,'store','店主之家',0,255),(4,'payment','支付方式',0,255),(5,'sold','售后服务',0,255),(6,'service','客服中心',0,255),(7,'about','关于我们',0,255),(8,NULL,'kkk',1,255);

/*Table structure for table `sys_event` */

DROP TABLE IF EXISTS `sys_event`;

CREATE TABLE `sys_event` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `from_module` varchar(128) NOT NULL DEFAULT '''app''' COMMENT 'from unit name , equal to the module id',
  `action` varchar(125) NOT NULL DEFAULT 'none' COMMENT 'action name : n+v or v+n (deleteUser or userDelete)',
  PRIMARY KEY (`id`),
  UNIQUE KEY `alert_handler` (`from_module`,`action`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

/*Data for the table `sys_event` */

/*Table structure for table `sys_event2listener` */

DROP TABLE IF EXISTS `sys_event2listener`;

CREATE TABLE `sys_event2listener` (
  `event_id` int(11) NOT NULL,
  `listener_id` int(11) NOT NULL,
  PRIMARY KEY (`event_id`,`listener_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `sys_event2listener` */

/*Table structure for table `sys_event_listener` */

DROP TABLE IF EXISTS `sys_event_listener`;

CREATE TABLE `sys_event_listener` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(128) NOT NULL DEFAULT '',
  `class` varchar(128) NOT NULL DEFAULT '',
  `file` varchar(255) NOT NULL DEFAULT '',
  `eval` text,
  `from_module` varchar(50) NOT NULL DEFAULT '''app''' COMMENT 'from wich module default is app means from sys',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

/*Data for the table `sys_event_listener` */

/*Table structure for table `sys_friend_link` */

DROP TABLE IF EXISTS `sys_friend_link`;

CREATE TABLE `sys_friend_link` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL COMMENT '友链名称',
  `logo` varchar(255) DEFAULT NULL COMMENT '友链logo 可以是本地或者远程地址',
  `url` varchar(255) NOT NULL,
  `order` tinyint(3) NOT NULL DEFAULT '0' COMMENT '显示顺序',
  `enable` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否启用',
  PRIMARY KEY (`id`),
  KEY `xx_friend_link_order_list` (`order`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

/*Data for the table `sys_friend_link` */

insert  into `sys_friend_link`(`id`,`name`,`logo`,`url`,`order`,`enable`) values (1,'yunCart',NULL,'http://localhost/2013NotYiiProjects/yuncart-1.0/upload/index.php',0,1),(2,'大雁气功啊',NULL,'https://www.createspace.com/208935',0,1);

/*Table structure for table `sys_hook` */

DROP TABLE IF EXISTS `sys_hook`;

CREATE TABLE `sys_hook` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `host_module` varchar(80) NOT NULL DEFAULT '''app''' COMMENT '宿主模块id',
  `hook_name` varchar(255) NOT NULL COMMENT '钩子名称executionPoint执行点 键值而已 如blogCreate',
  `client_module` varchar(80) NOT NULL DEFAULT '''app''' COMMENT '挂接方模块id',
  `client_hook_name` varchar(255) NOT NULL COMMENT '挂接方hook名字 用来删除的如blogOnUserDelete',
  `hook_content` text NOT NULL COMMENT '序列化或其他格式存储的hook内容自己定义解析格式',
  `priority` tinyint(5) NOT NULL DEFAULT '0' COMMENT '优先级',
  `type` varchar(25) NOT NULL DEFAULT '''custom''' COMMENT 'custome,action,filter',
  `create_time` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQUE` (`client_hook_name`)
) ENGINE=InnoDB AUTO_INCREMENT=222 DEFAULT CHARSET=utf8;

/*Data for the table `sys_hook` */

insert  into `sys_hook`(`id`,`host_module`,`hook_name`,`client_module`,`client_hook_name`,`hook_content`,`priority`,`type`,`create_time`) values (142,'app','createUrl','photo','photo_appCreateUrl','{\"route\":[\"album\\/member\",\"\\/album\\/member\"],\"paramsExpression\":\"isset($_GET[\'u\'])?$params+array(\'u\'=>$_GET[\'u\']):$params;\"}',0,'\'custom\'',1355501348),(221,'app','test','app','appOnAppTest2','dddhi can not be blank ',0,'\'custom\'',1358013771);

/*Table structure for table `sys_menu` */

DROP TABLE IF EXISTS `sys_menu`;

CREATE TABLE `sys_menu` (
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
  `group_code` varchar(25) NOT NULL DEFAULT 'sys_menu' COMMENT '归类码表示用途的 也可以用来表示位置 一般只需要标记根的用途即可 也可以考虑用eav 但考虑到查询问题 所以引入了此字段',
  `label_en` varchar(125) DEFAULT '' COMMENT '英文菜单名',
  `link_to` varchar(60) DEFAULT 'route' COMMENT '如果是树的叶子那么链接到（page,pageList,route）',
  PRIMARY KEY (`id`),
  KEY `root` (`root`),
  KEY `lft` (`lft`),
  KEY `rgt` (`rgt`),
  KEY `level` (`level`)
) ENGINE=InnoDB AUTO_INCREMENT=55 DEFAULT CHARSET=utf8 COMMENT='用nestedset保存树关系';

/*Data for the table `sys_menu` */

insert  into `sys_menu`(`id`,`root`,`lft`,`rgt`,`level`,`label`,`url`,`params`,`ajaxoptions`,`htmloptions`,`is_visible`,`group_code`,`label_en`,`link_to`) values (49,49,1,6,1,'虚菜单',NULL,NULL,NULL,NULL,1,'main_bottom_menu',NULL,'route'),(50,49,2,5,2,'主布局底部菜单组',NULL,NULL,NULL,NULL,1,'main_bottom_menu',NULL,'route'),(51,51,1,6,1,'虚菜单',NULL,NULL,NULL,NULL,1,'main_top_menu',NULL,'route'),(52,51,4,5,2,'主布局顶部部菜单组',NULL,NULL,NULL,NULL,1,'main_top_menu',NULL,'route'),(53,51,2,3,2,'qq pi',NULL,NULL,NULL,NULL,1,'main_top_menu',NULL,'route'),(54,49,3,4,3,'嘻嘻嘻',NULL,NULL,NULL,NULL,1,'main_bottom_menu',NULL,'route');

/*Table structure for table `sys_menu_types` */

DROP TABLE IF EXISTS `sys_menu_types`;

CREATE TABLE `sys_menu_types` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `menutype` varchar(24) NOT NULL,
  `title` varchar(48) NOT NULL,
  `description` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_menutype` (`menutype`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

/*Data for the table `sys_menu_types` */

insert  into `sys_menu_types`(`id`,`menutype`,`title`,`description`) values (1,'mainmenu','Main Menu','The main menu for the site'),(2,'authormenu','Author Menu',''),(3,'bottommenu','Bottom Menu','');

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
) ENGINE=MyISAM AUTO_INCREMENT=51 DEFAULT CHARSET=utf8;

/*Data for the table `sys_module` */

insert  into `sys_module`(`id`,`module_id`,`title`,`vendor`,`version`,`dependencies`,`ctime`) values (44,'friend','','','','',1346038195),(50,'blog','','','','',1369221335);

/*Table structure for table `sys_slider` */

DROP TABLE IF EXISTS `sys_slider`;

CREATE TABLE `sys_slider` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pos_id` varchar(120) NOT NULL COMMENT '所属的位置 如index_bottom.',
  `link_url` varchar(255) NOT NULL DEFAULT '#' COMMENT '点击后跳转路径 也可以不跳',
  `img_src` varchar(255) NOT NULL COMMENT '图片路径',
  `img_title` varchar(120) DEFAULT NULL COMMENT '图片标题',
  `text` varchar(1000) DEFAULT '0' COMMENT '也可以是文字 如果有这个那么图片的设置忽略',
  `order` tinyint(3) NOT NULL DEFAULT '0' COMMENT '同一位置上出现的顺序',
  `active` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否启用',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `sys_slider` */

/*Table structure for table `sys_slider_position` */

DROP TABLE IF EXISTS `sys_slider_position`;

CREATE TABLE `sys_slider_position` (
  `id` varchar(120) NOT NULL COMMENT '系统页面中出现的slider位置键如index_top',
  `width` varchar(15) NOT NULL DEFAULT '100%' COMMENT '宽度 可以用百分比或像素整数',
  `height` varchar(15) NOT NULL DEFAULT '100%' COMMENT '高度设置 同宽度',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `sys_slider_position` */

/*Table structure for table `tbl_comments` */

DROP TABLE IF EXISTS `tbl_comments`;

CREATE TABLE `tbl_comments` (
  `object_name` varchar(50) NOT NULL,
  `object_id` int(12) NOT NULL,
  `cmt_id` int(12) NOT NULL AUTO_INCREMENT,
  `cmt_parent_id` int(12) DEFAULT NULL,
  `author_id` int(12) DEFAULT NULL,
  `user_name` varchar(128) DEFAULT NULL,
  `user_email` varchar(128) DEFAULT NULL,
  `cmt_text` text,
  `create_time` int(11) DEFAULT NULL,
  `update_time` int(11) DEFAULT NULL,
  `status` int(1) NOT NULL DEFAULT '0',
  `replies` int(6) NOT NULL DEFAULT '0',
  `mood` tinyint(4) NOT NULL DEFAULT '0' COMMENT '心情 0 表示natural',
  PRIMARY KEY (`cmt_id`),
  KEY `owner_name` (`object_name`,`object_id`)
) ENGINE=InnoDB AUTO_INCREMENT=303 DEFAULT CHARSET=utf8;

/*Data for the table `tbl_comments` */

insert  into `tbl_comments`(`object_name`,`object_id`,`cmt_id`,`cmt_parent_id`,`author_id`,`user_name`,`user_email`,`cmt_text`,`create_time`,`update_time`,`status`,`replies`,`mood`) values ('User',1,29,0,1,'亦清','yiqing@qq.com','sdfsdgdfgdsf',1354983552,1358757087,2,0,0),('User',1,30,0,1,'亦清','yiqing@qq.com','sdfsdgsdg',1354983558,1358757087,2,0,0),('User',1,35,0,1,'亦清','yiqing@qq.com','dfggg',1355042449,1358757087,2,0,0),('User',1,36,0,1,'亦清','yiqing@qq.com','sdfgdfg',1355042455,1358757087,2,0,0),('User',1,39,0,1,'亦清','yiqing@qq.com','ppjj',1355042480,1358757087,2,0,0),('User',1,43,42,1,'亦清','yiqing@qq.com','efsdgdsfgdfsgdsfgdsf',1355043391,1358757087,2,0,0),('User',1,44,43,1,'亦清','yiqing@qq.com','sdfsadgsdfgsd',1355043395,1358757087,2,0,0),('User',1,45,44,1,'亦清','yiqing@qq.com','sdgfdfgdfhgfdg',1355043400,1358757087,2,0,0),('User',1,46,45,1,'亦清','yiqing@qq.com','sdfsdfgdfgdfh',1355043406,1358757087,2,0,0),('User',1,48,47,1,'亦清','yiqing@qq.com','dfgdfghdfgh',1355043414,1358757087,2,0,0),('User',1,49,48,1,'yes  it works !',NULL,'dfghfghfgh',1355043418,1355113133,2,0,0),('User',1,50,49,1,'yes  it works !',NULL,'sfdfhfghfgh',1355043422,1355113161,2,0,0),('User',1,51,36,1,'yes  it works !',NULL,'ffffffffg',1355047450,1355047455,2,0,0),('User',2,52,0,2,'yes  it works !',NULL,'dsfgdfgdfg',1355106941,1355106945,2,0,0),('User',2,53,32,2,'yes  it works !',NULL,'eegdsfgdsfhg',1355106949,1355195257,2,0,0),('User',2,54,53,2,'yes  it works !',NULL,'dfgdfhfgh',1355106952,1355129458,2,0,0),('User',2,55,54,2,'yes  it works !',NULL,'fdgdfhfdgh',1355106956,1355106982,2,0,0),('User',2,56,0,2,'yes  it works !',NULL,'dfgfghfgjghjghj',1355106965,1355129458,2,0,0),('User',2,57,55,2,'yes  it works !',NULL,'xfgdfhfgh',1355106976,1355106980,2,0,0),('User',2,58,54,2,'yes  it works !',NULL,'sdsgdfg',1355107099,1355129457,2,0,0),('User',1,59,36,1,'hi ',NULL,'ghsdfsdf',1355107223,1358079382,2,0,0),('User',1,60,59,1,'hi ',NULL,'似懂非懂广告费',1355107284,1358079382,2,0,0),('User',1,61,0,1,'hi ',NULL,'法国的风格',1355107385,1358079382,2,0,0),('User',1,62,37,1,'hi ',NULL,'额度发鬼地方',1355107452,1358079382,2,0,0),('User',1,63,36,1,'hi ',NULL,'gggg',1355107483,1358079382,2,0,0),('User',1,64,61,1,'hi ',NULL,'efwetrewr',1355109849,1358079382,2,0,0),('User',1,65,61,1,'hi ',NULL,'fsdfdfgdfg',1355109859,1358079382,2,0,0),('User',1,66,36,1,'hi ',NULL,'sdfsdg',1355109866,1358079382,2,0,0),('User',1,68,NULL,1,'hi ',NULL,'zxdfgdfg',1355127026,1358079382,1,0,0),('User',1,69,NULL,1,'hi ',NULL,'zxdfgdfg',1355127104,1358079382,1,0,0),('User',1,70,NULL,1,NULL,NULL,'jjpp',1355127151,NULL,1,0,0),('User',1,71,NULL,1,NULL,NULL,'jjpp',1355127167,1355127508,2,0,0),('User',1,72,NULL,1,NULL,NULL,'fgdfgdfsdfsdffff',1355127306,1355127492,2,0,0),('User',1,73,NULL,1,NULL,NULL,'ppppppp',1355127345,1355127480,2,0,0),('User',1,74,NULL,1,NULL,NULL,'dsfgdfg',1355127531,1355127997,2,0,0),('User',2,75,NULL,2,NULL,NULL,'dfasdfdfgdfg',1355127628,1355129456,2,0,0),('User',2,76,NULL,2,NULL,NULL,'uuuuuuuu',1355127639,1355129455,2,0,0),('User',2,77,NULL,2,NULL,NULL,'woshi shei ',1355127660,1355129464,2,0,0),('User',2,78,32,2,NULL,NULL,'fsdgdfghdfhhfgh',1355127750,1355129464,2,0,0),('User',2,79,NULL,2,NULL,NULL,'woshi shei ',1355127761,1355129463,2,0,0),('User',2,80,53,2,NULL,NULL,'dsffdgdfg',1355127810,1355129462,2,0,0),('User',1,81,NULL,1,NULL,NULL,'sdgdfgdf',1355127950,1355127995,2,0,0),('User',1,82,NULL,1,NULL,NULL,'hoooo',1355127960,1355127991,2,0,0),('User',1,83,NULL,1,NULL,NULL,'hoooosdfsdfgdfg',1355127977,NULL,1,0,0),('User',1,84,NULL,1,NULL,NULL,'pppppp',1355128010,NULL,1,0,0),('User',1,85,NULL,1,NULL,NULL,'jjjjjj',1355128016,NULL,1,0,0),('User',1,86,68,1,NULL,NULL,'fdhfghfghj',1355128070,NULL,1,0,0),('User',1,87,NULL,1,NULL,NULL,'srgdfh',1355128371,NULL,1,0,0),('User',2,88,NULL,2,NULL,NULL,' $this->validatedComment :',1355128613,1355129462,2,0,0),('User',1,89,87,1,NULL,NULL,'fghfdgj',1355128822,NULL,1,0,0),('User',1,90,NULL,1,NULL,NULL,'grsdrgsd',1355128861,NULL,1,0,0),('User',1,91,NULL,1,NULL,NULL,'grsdrgsd',1355128900,NULL,1,0,0),('User',1,92,NULL,1,NULL,NULL,'asdfasdfg',1355128938,NULL,1,0,0),('User',2,93,NULL,2,NULL,NULL,'算计儿妈妈对司法斯蒂芬  ',1355129000,1355129443,2,0,0),('User',2,94,77,2,NULL,NULL,'范甘迪发覆盖',1355129010,1355129442,2,0,0),('User',2,95,79,2,NULL,NULL,'二姨太热通用',1355129042,1355129441,2,0,0),('User',2,96,NULL,2,NULL,NULL,'水云间哈',1355129109,1355129440,2,0,0),('User',2,97,95,2,NULL,NULL,'而特瑞',1355129121,1355129438,2,0,0),('User',2,98,96,2,NULL,NULL,'也是',1355129221,1355129452,2,0,0),('User',2,99,96,2,NULL,NULL,'呀呀呀呀呀呀',1355129237,1355129451,2,0,0),('User',2,100,98,2,NULL,NULL,'什么情况',1355129257,1355129450,2,0,0),('User',2,101,100,2,NULL,NULL,'顶顶顶顶 是么子意思',1355129279,1355129450,2,0,0),('User',2,102,96,2,NULL,NULL,'hi8u',1355129386,1355129449,2,0,0),('User',2,103,97,2,NULL,NULL,'哈iii爱i',1355129394,1355129432,2,0,0),('User',2,104,98,2,NULL,NULL,'平平平平平',1355129401,1355129430,2,0,0),('User',2,105,32,2,NULL,NULL,'速度飞洒地方',1355129414,1355129434,2,0,0),('User',2,106,105,2,NULL,NULL,'啥啊',1355129424,1355129429,2,0,0),('User',2,107,53,2,NULL,NULL,'水电费及速度快',1355129472,1355195217,2,0,0),('User',2,108,32,2,'神来',NULL,'收到发送到',1355129477,1357964348,2,0,0),('User',2,109,NULL,2,'神来',NULL,'速度飞洒地方',1355129480,1357964348,2,0,0),('User',2,110,NULL,2,'神来',NULL,'地发生地方',1355129486,1357964348,2,0,0),('User',2,111,32,2,'神来',NULL,'pppppp啦啦啦啦啦',1355129503,1357964348,2,0,0),('User',2,112,110,2,'神来',NULL,'大多数覆盖',1355129522,1357964348,2,0,0),('User',2,113,111,2,'神来',NULL,'来来来来来来来来来来来来来',1355129540,1357964348,2,0,0),('User',2,114,NULL,2,'神来',NULL,'sd敢达覆盖',1355129550,1357964348,2,0,0),('User',2,115,113,2,'神来',NULL,'回复啦啦啦啦',1355129563,1357964348,2,0,0),('User',2,116,111,2,'神来',NULL,'时好时坏是',1355129641,1357964348,2,0,0),('User',2,117,116,2,'神来',NULL,'信息',1355129652,1357964348,2,0,0),('User',2,118,NULL,2,NULL,NULL,'dapp大批\r\n\r\n',1355129787,1355129802,2,0,0),('User',2,119,109,2,NULL,NULL,'sheyyy',1355130291,1355130612,2,0,0),('User',2,120,119,2,NULL,NULL,'shshhshh',1355130301,1355130610,2,0,0),('User',2,121,NULL,2,NULL,NULL,'sdfsdf',1355130305,1355130606,2,0,0),('User',2,122,113,2,NULL,NULL,'fgfg',1355130619,1355130650,2,0,0),('User',2,123,122,2,NULL,NULL,'gfhjghjghj',1355130633,1355130647,2,0,0),('User',2,124,107,2,NULL,NULL,'ergrfhfdh',1355130661,1355195211,2,0,0),('User',2,125,124,2,NULL,NULL,'dfdghdfgh',1355130666,1355195209,2,0,0),('User',2,126,NULL,2,NULL,NULL,'sdfhdfgjfgj',1355130675,1355139677,2,0,0),('User',2,127,NULL,2,NULL,NULL,'相册法国的风格',1355139548,1355139674,2,0,0),('User',2,128,127,2,'亦清',NULL,'啪啪啪 减肥减肥姐姐姐夫',1355139570,1357964200,2,0,0),('User',2,129,32,2,'亦清',NULL,'静境多独得',1355139650,1357964200,2,0,0),('User',2,130,129,2,'亦清',NULL,'反反复复',1355139665,1357964200,2,0,0),('User',2,131,NULL,2,'亦清',NULL,'踩踩踩的东东',1355139692,1357964200,2,0,0),('User',2,132,124,2,'亦清',NULL,'国等复合弓',1355139699,1357964200,2,0,0),('User',2,133,NULL,2,'亦清',NULL,'asdasdfsdf',1355195228,1357964200,2,0,0),('User',2,134,NULL,2,'亦清',NULL,'fdgdfg',1355195233,1357964200,2,0,0),('User',2,135,134,2,'亦清',NULL,'sdfsdfgdfg',1355195239,1357964200,2,0,0),('User',2,136,NULL,2,'亦清',NULL,'fdgdfg',1355195260,1357964200,2,0,0),('User',2,138,NULL,2,'亦清',NULL,'dfgfghfgh',1355195274,1357964200,2,0,0),('User',2,139,NULL,2,NULL,NULL,'gfhghj',1355195277,1355195283,2,0,0),('User',2,140,137,2,NULL,NULL,'rthgfhjghj',1355195288,1355195326,2,0,0),('User',2,141,140,2,NULL,NULL,'oip;klop\'o[o',1355195293,1355195324,2,0,0),('User',2,142,140,2,NULL,NULL,'ytjhgjkhjljhkl',1355195301,1355195322,2,0,0),('User',2,143,NULL,2,NULL,NULL,'dsgfhfdghgfj',1355195315,1355195319,2,0,0),('User',2,144,NULL,2,NULL,NULL,'dsgdfg',1355195329,NULL,1,0,0),('User',2,145,144,2,NULL,NULL,'dsgdfghfg',1355195365,NULL,1,0,0),('User',2,146,NULL,2,NULL,NULL,'fgfghghj',1355195368,NULL,1,0,0),('User',1,147,87,1,NULL,NULL,'dfsdafasdgdg',1355217892,NULL,1,0,0),('User',1,148,147,1,NULL,NULL,'uuuuuuu',1355217900,NULL,1,0,0),('Photo',22,149,NULL,2,NULL,NULL,'太漂亮了',1355236712,1356863835,2,0,0),('Photo',22,150,NULL,2,NULL,NULL,'太漂亮了',1355237068,1356859148,2,0,0),('Photo',22,151,NULL,1,NULL,NULL,'凤飞飞',1355237216,1356863825,2,0,0),('Photo',22,152,NULL,1,NULL,NULL,'凤飞飞',1355237279,1356859141,2,0,0),('Photo',22,153,NULL,1,NULL,NULL,'凤飞飞',1355237362,1356857509,2,0,0),('Photo',22,154,NULL,1,NULL,NULL,'fdsfsdgdf',1355237455,1355495528,2,0,0),('Photo',22,155,NULL,1,NULL,NULL,'在说地方撒旦个',1355239240,1355494798,2,0,0),('Photo',22,156,NULL,1,NULL,NULL,'解决跑跑',1355239302,1355241955,2,0,0),('Photo',22,157,156,1,NULL,NULL,'蛋疼的真慢',1355239346,1355241954,2,0,0),('Photo',22,158,150,2,NULL,NULL,'地方规定发给和法国和',1355241893,1355241939,2,0,0),('Photo',22,159,157,NULL,'解决','iqng@qq.com','dfjasdjgdfjgjsdfg',1355242346,1356857503,2,0,0),('Photo',15,160,NULL,1,NULL,NULL,'黄瓜是屌丝女的最爱',1355242488,1356792425,2,0,0),('Photo',15,161,160,1,NULL,NULL,'太搞了那个是丝瓜哦',1355242514,1356792417,2,0,0),('Photo',23,162,NULL,2,NULL,NULL,'sfdsdgdf',1355282351,1355368097,2,0,0),('Photo',23,163,162,2,NULL,NULL,'dfgfsdghfgh',1355282510,NULL,1,0,0),('Photo',23,164,NULL,2,NULL,NULL,'dfgdfs',1355283131,NULL,1,0,0),('Photo',23,165,NULL,2,NULL,NULL,';;;;',1355295641,NULL,1,0,0),('Photo',14,166,NULL,2,NULL,NULL,';;;jjjjjjj',1355295876,1355369359,2,0,0),('Photo',14,167,166,2,NULL,NULL,'lkl;kjhjhggh',1355295887,1355369356,2,0,0),('Photo',14,168,167,2,NULL,NULL,'l;lll;;;;',1355295900,1355369361,2,0,0),('photo',3,169,NULL,1,NULL,NULL,'速度感',1355299204,1355369807,2,0,0),('photo',3,170,NULL,1,NULL,NULL,'jjj',1355299394,1355371390,2,0,0),('photo',3,171,NULL,1,NULL,NULL,'kkk',1355299575,1355369802,2,0,0),('Photo',6,172,NULL,1,NULL,NULL,'kkk',1355299683,NULL,1,0,0),('Photo',6,173,NULL,1,NULL,NULL,'kkk',1355299684,NULL,1,0,0),('photo',3,174,NULL,1,NULL,NULL,'速度飞洒地方',1355331255,1355369806,2,0,0),('photo',3,175,169,1,NULL,NULL,'dangting ',1355331289,1355369800,2,0,0),('photo',3,176,0,1,NULL,NULL,'地方规定发给',1355331863,1355369798,2,0,0),('photo',3,177,176,1,NULL,NULL,'地发生地方个',1355331891,1355371388,2,0,0),('photo',3,178,0,1,NULL,NULL,'sd敢达覆盖',1355332077,1355369813,2,0,0),('photo',3,179,0,1,NULL,NULL,'sd敢达覆盖',1355332096,1355402881,2,0,0),('photo',3,180,179,1,NULL,NULL,'法国队',1355332134,1355369795,2,0,0),('photo',3,181,NULL,1,NULL,NULL,'wet',1355332219,1355402875,2,0,0),('photo',3,182,181,1,NULL,NULL,'类品牌',1355332271,1355369792,2,0,0),('photo',3,183,NULL,1,NULL,NULL,'大幅度覆盖',1355333063,1355369853,2,0,0),('photo',3,184,0,1,NULL,NULL,'生非个人',1355333153,1355369790,2,0,0),('photo',3,185,184,1,NULL,NULL,'而特特',1355333315,1355371315,2,0,0),('photo',3,186,175,2,NULL,NULL,'儿无污染',1355366548,1355371322,2,0,0),('photo',3,187,NULL,2,NULL,NULL,'但司法第三方',1355366573,1355371301,2,0,0),('photo',3,188,184,2,NULL,NULL,'为二位容器',1355366659,1355369846,2,0,0),('Photo',23,189,165,2,NULL,NULL,'凤飞飞',1355366722,NULL,1,0,0),('Photo',23,190,164,2,NULL,NULL,'方法',1355366754,1355368087,2,0,0),('Photo',23,191,164,2,NULL,NULL,'方法',1355366766,1355369876,2,0,0),('Photo',23,192,165,2,NULL,NULL,'人法地覆盖',1355366944,1355368084,2,0,0),('photo',3,193,183,2,NULL,NULL,'hhahha',1355367551,1355369844,2,0,0),('photo',3,194,174,2,NULL,NULL,'dsfsdaf',1355367589,1355369785,2,0,0),('Photo',23,195,192,2,NULL,NULL,'sdgdsfgdsfg',1355368065,1355368079,2,0,0),('Photo',23,196,195,2,NULL,NULL,'jjjjj',1355368071,1355368077,2,0,0),('Photo',23,197,191,2,NULL,NULL,'kkk',1355368105,NULL,1,0,0),('Photo',23,198,189,1,NULL,NULL,'斪',1355369294,1355369299,2,0,0),('Photo',23,199,0,1,NULL,NULL,'急急急',1355369310,NULL,1,0,0),('Photo',23,200,199,1,NULL,NULL,'家斤斤计较',1355369317,1356868439,2,0,0),('Photo',14,201,168,1,NULL,NULL,'红色\r\n',1355369350,1355369353,2,0,0),('Photo',14,202,0,1,NULL,NULL,'收到发送到覆盖',1355369366,NULL,1,0,0),('photo',3,203,0,2,NULL,NULL,'efdfgdfg',1355369403,1355369783,2,0,0),('photo',3,204,179,2,NULL,NULL,'dfsgsdfgdsfg',1355369818,1355369851,2,0,0),('photo',3,205,0,2,NULL,NULL,'kkkkk',1355369826,1355369842,2,0,0),('photo',3,206,204,2,NULL,NULL,'lllll',1355369833,1355369839,2,0,0),('Photo',14,207,202,1,NULL,NULL,'撒旦法撒旦个',1355369954,1355370015,2,0,0),('Photo',14,208,0,1,NULL,NULL,'是打发斯蒂芬感受到',1355369960,1356859364,2,0,0),('Photo',14,209,0,1,NULL,NULL,'果然是电饭锅的发生过',1355369969,1356859362,2,0,0),('Photo',14,210,207,1,NULL,NULL,'大幅度覆盖电饭锅',1355369978,NULL,1,0,0),('Photo',14,211,207,1,NULL,NULL,'快快快快快',1355369985,1355370010,2,0,0),('Photo',14,212,0,1,NULL,NULL,'加加减减',1355369992,1356859358,2,0,0),('Photo',14,213,212,1,NULL,NULL,'呵呵呵',1355370001,1355370020,2,0,0),('Photo',14,214,208,1,NULL,NULL,'sd敢达双方各',1355370685,1356859352,2,0,0),('Photo',14,215,214,1,NULL,NULL,'sd敢达覆盖电饭锅',1355370700,1356859350,2,0,0),('photo',3,216,187,2,NULL,NULL,'kkkk',1355370742,1355371398,2,0,0),('Photo',14,217,212,1,NULL,NULL,'地方规定发给',1355370765,1356859334,2,0,0),('Photo',14,218,0,1,NULL,NULL,'而格外让他',1355370773,1356859328,2,0,0),('Photo',14,219,218,1,NULL,NULL,'0额发生地批发速配而撒旦解放路撒大口径',1355370782,1355371270,2,0,0),('Photo',14,220,218,1,NULL,NULL,'iii看',1355371256,1355371643,2,0,0),('Photo',14,221,210,1,NULL,NULL,'哦哦哦',1355371265,1355371645,2,0,0),('photo',3,222,NULL,2,NULL,NULL,'kk',1355371292,1355371396,2,0,0),('photo',3,223,186,2,NULL,NULL,'kkk ',1355371298,1355371327,2,0,0),('photo',3,224,216,1,NULL,NULL,'快快快快',1355371335,1355402875,2,0,0),('photo',3,225,NULL,1,NULL,NULL,'kkk',1355371341,1355371381,2,0,0),('photo',3,226,179,1,NULL,NULL,'；；平',1355371355,1355371383,2,0,0),('photo',3,227,181,1,NULL,NULL,'；；；；； ',1355371365,1355371379,2,0,0),('photo',3,228,224,1,NULL,NULL,'啦啦啦啦啦',1355371374,1355371394,2,0,0),('photo',3,229,224,1,NULL,NULL,'噢噢噢噢',1355371404,1355402879,2,0,0),('photo',3,230,229,1,NULL,NULL,'快快快快',1355371424,1355371873,2,0,0),('photo',3,231,NULL,1,NULL,NULL,'噢噢噢噢',1355371430,1355371713,2,0,0),('photo',3,232,179,1,NULL,NULL,'啦啦啦啦',1355371438,1355371875,2,0,0),('photo',3,233,181,1,NULL,NULL,'；；；； ',1355371458,1355371831,2,0,0),('photo',3,234,179,1,NULL,NULL,'啦啦啦啦',1355371466,1355371824,2,0,0),('photo',3,235,179,1,NULL,NULL,'啦啦啦啦啦',1355371478,1355371809,2,0,0),('photo',3,236,235,1,NULL,NULL,'啦啦啦啦啦',1355371497,1355371792,2,0,0),('Photo',14,237,218,1,NULL,NULL,'哦了',1355371543,1355371641,2,0,0),('Photo',14,238,0,1,NULL,NULL,'婆婆哦',1355371548,1355371639,2,0,0),('Photo',14,239,237,1,NULL,NULL,'噢噢噢噢',1355371633,1355371636,2,0,0),('Photo',14,240,0,1,NULL,NULL,'婆婆哦ip',1355371651,1356859325,2,0,0),('Photo',14,241,240,1,NULL,NULL,'来来来',1355371658,1356859322,2,0,0),('photo',3,242,236,1,NULL,NULL,'哦哦看看',1355371668,1355371787,2,0,0),('photo',3,243,235,1,NULL,NULL,'噢噢噢噢年',1355371797,1355371821,2,0,0),('photo',3,244,0,1,NULL,NULL,'i急吼吼即可',1355371815,1355371819,2,0,0),('photo',3,245,232,1,NULL,NULL,'浏览',1355371843,1355371871,2,0,0),('photo',3,246,245,1,NULL,NULL,'哦啦啦啦',1355371863,1355371870,2,0,0),('Photo',7,247,0,1,NULL,NULL,'啦啦啦啦',1355371914,NULL,1,0,0),('Photo',7,248,247,1,NULL,NULL,'咯哦哦哦',1355371920,1355553433,2,0,0),('Photo',7,249,247,1,NULL,NULL,'来批评批评',1355371928,1355553430,2,0,0),('Photo',8,250,0,1,NULL,NULL,'平平平平平',1355371967,NULL,1,0,0),('Photo',8,251,250,1,NULL,NULL,'当事人对分公司覆盖',1355376139,1355376147,2,0,0),('Photo',8,252,250,1,NULL,NULL,'如法国会引发国际化',1355376154,NULL,1,0,0),('Photo',5,253,0,1,NULL,NULL,'hhh',1355379259,NULL,1,0,0),('photo',3,254,224,2,NULL,NULL,'速度飞洒地方\r\n',1355401171,1355402875,2,0,0),('photo',3,255,0,2,NULL,NULL,'iiii',1355402891,1356859226,2,0,0),('photo',3,256,255,2,NULL,NULL,'噢噢噢噢',1355402900,NULL,1,0,0),('photo',3,257,255,2,NULL,NULL,'uuuuu',1355403727,NULL,1,0,0),('photo',3,258,255,2,NULL,NULL,'uuuuu',1355403738,NULL,1,0,0),('photo',3,259,NULL,1,NULL,NULL,'ooo',1355403847,NULL,1,0,0),('photo',3,260,NULL,1,NULL,NULL,'ooo',1355403856,NULL,1,0,0),('photo',3,261,259,1,NULL,NULL,'sdgdfg',1355405740,NULL,1,0,0),('photo',3,262,259,1,NULL,NULL,'sdfsdf',1355406321,1356859216,2,0,0),('photo',3,263,262,1,NULL,NULL,'dfgdfgsdfg',1355406327,1356860789,2,0,0),('photo',3,264,262,1,NULL,NULL,'sdfsdfgsadg',1355407425,NULL,1,0,0),('Photo',23,265,199,2,NULL,NULL,'对司法斯蒂芬',1355407571,1356863859,2,0,0),('photo',3,266,0,1,NULL,NULL,'dfdsff',1355467793,1356859168,2,0,0),('photo',3,267,266,1,NULL,NULL,'sd敢达覆盖',1355467799,NULL,1,0,0),('Photo',7,268,249,1,NULL,NULL,'ppppppp噼噼啪啪',1355509123,1355553427,2,0,0),('Photo',15,269,161,2,NULL,NULL,'xzdgdfg',1355561009,1356792415,2,0,0),('Photo',24,270,0,1,NULL,NULL,'rgdfg',1356330006,NULL,1,0,0),('Photo',24,271,270,1,NULL,NULL,'奥么',1356330017,NULL,1,0,0),('Photo',21,272,0,1,NULL,NULL,'xcgdfg',1356366245,NULL,1,0,0),('Photo',15,273,269,2,NULL,NULL,'地方规定发给凤飞飞方法',1356792408,1356792412,2,0,0),('Photo',15,274,0,2,NULL,NULL,'二鬼地方',1356792432,NULL,1,0,0),('photo',17,275,NULL,1,NULL,NULL,'速度飞洒地方',1356850470,NULL,1,0,0),('photo',17,276,NULL,1,NULL,NULL,'在速度飞洒地方',1356850486,NULL,1,0,0),('photo',16,277,NULL,1,NULL,NULL,'dddd',1356856346,1356857252,2,0,0),('photo',3,278,262,1,NULL,NULL,'dsfgdfdg',1356856357,1356859159,2,0,0),('photo',16,279,NULL,1,NULL,NULL,'dsfdsfdfg',1356856434,1356856441,2,0,0),('photo',17,280,NULL,1,NULL,NULL,'xcvdsafgdfg',1356857065,NULL,1,0,0),('photo',17,281,275,1,NULL,NULL,'regrwthyrty',1356857071,NULL,1,0,0),('photo',17,282,280,1,NULL,NULL,'wertweryrety',1356857081,NULL,1,0,0),('photo',17,283,NULL,1,NULL,NULL,'dfgerthtyhj',1356857088,NULL,1,0,0),('photo',17,284,283,1,NULL,NULL,'wretertyrtu',1356857102,NULL,1,0,0),('photo',16,285,277,1,NULL,NULL,'regtythretyufgdfgh',1356857237,1356857245,2,0,0),('photo',16,286,0,1,NULL,NULL,'retewrytrety',1356857258,1356857488,2,0,0),('photo',16,287,286,1,NULL,NULL,'ddthyryuyt',1356857264,1356857378,2,0,0),('Photo',14,288,215,2,NULL,NULL,'人发过东方红',1356859343,1356859347,2,0,0),('Photo',14,289,210,2,NULL,NULL,'东方饭店个人',1356859372,NULL,1,0,0),('Photo',25,290,NULL,2,NULL,NULL,'梵蒂冈的发挥的发挥',1356859551,NULL,1,0,0),('Photo',22,291,NULL,1,NULL,NULL,'dfedf',1356869059,NULL,1,0,0),('Photo',22,292,291,1,NULL,NULL,'asdfsdfg',1356869068,NULL,1,0,0),('Photo',22,293,292,1,NULL,NULL,'sdgedfg',1356869078,NULL,1,0,0),('photo',16,294,NULL,1,NULL,NULL,'rgdfg',1356869100,NULL,1,0,0),('photo',10,295,NULL,1,NULL,NULL,'但司法第三方个电饭锅',1356939345,1356939357,2,0,0),('photo',21,296,NULL,1,NULL,NULL,'dsfdfgdf',1357118113,1357118119,2,0,0),('photo',21,297,272,1,NULL,NULL,'ooo',1357118129,NULL,1,0,0),('photo',21,298,272,1,NULL,NULL,'pppp;p;;;',1357118136,NULL,1,0,0),('photo',20,299,NULL,1,NULL,NULL,'sdgsadfg',1357144055,1357144080,2,0,0),('Photo',24,300,NULL,NULL,'yiqing','yiqing_95@qq.com','牛逼的小屁孩',1357469537,NULL,1,0,0),('Photo',24,301,300,NULL,'好','jj@jj.com','确实牛逼的',1357469562,NULL,1,0,0),('photo',21,302,NULL,1,NULL,NULL,'太漂亮了',1357469633,NULL,1,0,0);

/*Table structure for table `tbl_migration` */

DROP TABLE IF EXISTS `tbl_migration`;

CREATE TABLE `tbl_migration` (
  `version` varchar(255) NOT NULL,
  `apply_time` int(11) DEFAULT NULL,
  PRIMARY KEY (`version`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `tbl_migration` */

insert  into `tbl_migration`(`version`,`apply_time`) values ('m000000_000000_base',1343115971),('m110805_153437_installYiiUser',1343115996),('m110810_162301_userTimestampFix',1343115996);

/*Table structure for table `u_user_data` */

DROP TABLE IF EXISTS `u_user_data`;

CREATE TABLE `u_user_data` (
  `user_id` int(11) unsigned NOT NULL COMMENT '用户主键id',
  `attr` varchar(250) NOT NULL COMMENT '属性 用户键',
  `val` text NOT NULL COMMENT '对应的值 格式自己决定 推荐用json',
  UNIQUE KEY `user-key` (`user_id`,`attr`),
  KEY `ikEntity` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `u_user_data` */

insert  into `u_user_data`(`user_id`,`attr`,`val`) values (5008,'reg_as','supplier,clerk'),(5008,'reg_state','1');

/*Table structure for table `user` */

DROP TABLE IF EXISTS `user`;

CREATE TABLE `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(20) NOT NULL DEFAULT '',
  `password` varchar(128) NOT NULL DEFAULT '',
  `icon_uri` varchar(255) DEFAULT NULL COMMENT 'the user icon url path',
  `email` varchar(128) NOT NULL DEFAULT '',
  `activkey` varchar(128) NOT NULL DEFAULT '',
  `superuser` int(1) NOT NULL DEFAULT '0',
  `status` int(1) NOT NULL DEFAULT '0',
  `create_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `lastvisit_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_username` (`username`),
  UNIQUE KEY `user_email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

/*Data for the table `user` */

insert  into `user`(`id`,`username`,`password`,`icon_uri`,`email`,`activkey`,`superuser`,`status`,`create_at`,`lastvisit_at`) values (1,'yiqing','32ee7d239bcc2eff68f47ca70bd6e4a7','uploads/user/13678133261367813326.jpg','webmaster@example.com','8abda1b47ccc436c2aaf3eb5912d3971',1,1,'2012-07-24 15:46:36','2013-06-17 08:22:06'),(2,'yiqing2','32ee7d239bcc2eff68f47ca70bd6e4a7','uploads/user/13676024711367602471.jpg','66104992@qq.com','c1a5a5f7d1cc198d132d2d367ca08877',0,1,'2012-07-27 10:29:01','2013-05-19 16:37:57'),(3,'yiqing3','32ee7d239bcc2eff68f47ca70bd6e4a7','uploads/user/13686870341368687034.jpg','yiqing_95@qq.com','f07550b610f3388b17ffc6b62aecb1ad',0,1,'2012-08-11 14:15:02','2013-06-07 16:42:00'),(4,'tewt','test',NULL,'ceshi@qq.com','',0,0,'2013-03-24 18:53:49','0000-00-00 00:00:00');

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

insert  into `user_profile`(`user_id`,`first_name`,`last_name`,`photo`) values (1,'Administrator','Admin','uploads/user/13675965181367596518.jpg'),(2,'qing','yii','uploads/user/13515238331351523833.jpg'),(3,'qing','yii','uploads/user/13518713151351871315.gif');

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

/*Table structure for table `user_space_visit_stat` */

DROP TABLE IF EXISTS `user_space_visit_stat`;

CREATE TABLE `user_space_visit_stat` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `target` int(11) DEFAULT NULL,
  `day` date NOT NULL,
  `times` int(5) unsigned NOT NULL DEFAULT '0' COMMENT 'quantity',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=8315 DEFAULT CHARSET=utf8 COMMENT='空间访问量日统计';

/*Data for the table `user_space_visit_stat` */

insert  into `user_space_visit_stat`(`id`,`target`,`day`,`times`) values (8274,1,'2013-04-26',4),(8275,2,'2013-04-26',3),(8276,1,'2013-04-27',6),(8277,1,'2013-04-28',1),(8278,1,'2013-04-29',2),(8279,1,'2013-04-30',1),(8280,1,'2013-05-01',1),(8281,1,'2013-05-02',1),(8282,2,'2013-05-03',1),(8283,3,'2013-05-03',1),(8284,2,'2013-05-06',1),(8285,3,'2013-05-06',1),(8286,1,'2013-05-06',1),(8287,1,'2013-05-07',2),(8288,3,'2013-05-07',2),(8289,2,'2013-05-08',1),(8290,1,'2013-05-08',1),(8291,1,'2013-05-09',1),(8292,3,'2013-05-09',1),(8293,2,'2013-05-09',1),(8294,1,'2013-05-10',1),(8295,2,'2013-05-10',2),(8296,3,'2013-05-10',2),(8297,1,'2013-05-11',1),(8298,1,'2013-05-12',1),(8299,1,'2013-05-15',1),(8300,3,'2013-05-15',1),(8301,2,'2013-05-15',1),(8302,1,'2013-05-16',1),(8303,2,'2013-05-16',1),(8304,1,'2013-05-17',1),(8305,1,'2013-05-18',1),(8306,3,'2013-05-18',1),(8307,3,'2013-05-19',1),(8308,3,'2013-05-22',1),(8309,1,'2013-05-24',1),(8310,3,'2013-05-30',1),(8311,1,'2013-06-07',1),(8312,3,'2013-06-07',1),(8313,2,'2013-06-07',1),(8314,1,'2013-06-17',1);

/*Table structure for table `user_space_visitor` */

DROP TABLE IF EXISTS `user_space_visitor`;

CREATE TABLE `user_space_visitor` (
  `space_id` int(11) NOT NULL COMMENT '被访问者的uid',
  `visitor_id` int(11) NOT NULL COMMENT '当前访客的uid 参考user.uid',
  `vtime` int(11) NOT NULL COMMENT '高效的查询仍旧需要 在三个字段上建立索引',
  PRIMARY KEY (`space_id`,`visitor_id`),
  KEY `space_id` (`space_id`,`visitor_id`,`vtime`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='用户空间访客记录';

/*Data for the table `user_space_visitor` */

insert  into `user_space_visitor`(`space_id`,`visitor_id`,`vtime`) values (1,2,1368759929),(1,3,1368981353),(2,1,1370623297),(2,3,1368687534),(3,1,1370623303),(3,2,1368981614);

/*Table structure for table `yiisession` */

DROP TABLE IF EXISTS `yiisession`;

CREATE TABLE `yiisession` (
  `id` char(32) NOT NULL,
  `expire` int(11) DEFAULT NULL,
  `data` longblob,
  `user_id` int(11) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `yiisession` */

insert  into `yiisession`(`id`,`expire`,`data`,`user_id`) values ('bfsl10jr631aqnl0p65g59r215',1371609884,'sina_state|s:32:\"e4da3b7fbbce2345d7772b0674a318d5\";back_url|s:63:\"/my5newYiiTry/rcyxw2013/?state=d3d9446802a44259755d38e6d163e820\";qq_state|s:32:\"d3d9446802a44259755d38e6d163e820\";',0),('r9pei0n7kd8443tdirk0a1usi5',1371610695,'9dc10f659ab21e9f2da8c93481cba673__returnUrl|s:38:\"/my5newYiiTry/rcyxw2013/blog/my/create\";9dc10f659ab21e9f2da8c93481cba673__id|s:1:\"1\";9dc10f659ab21e9f2da8c93481cba673__name|s:6:\"yiqing\";9dc10f659ab21e9f2da8c93481cba673__states|a:0:{}Yii.CCaptchaAction.6e2d58b8.site.captcha|s:7:\"bilelof\";Yii.CCaptchaAction.6e2d58b8.site.captchacount|i:2;admin__id|s:1:\"6\";admin__name|s:6:\"yiqing\";admin__states|a:0:{}admin__userInfo|a:10:{s:2:\"id\";s:1:\"6\";s:8:\"username\";s:6:\"yiqing\";s:8:\"password\";s:32:\"a4b54fec26e9075c6447c436fef27cfc\";s:4:\"name\";s:6:\"yiqing\";s:7:\"encrypt\";s:6:\"uvcOSn\";s:7:\"role_id\";s:1:\"1\";s:8:\"disabled\";s:1:\"0\";s:7:\"setting\";N;s:11:\"create_time\";s:10:\"1357745292\";s:11:\"update_time\";s:10:\"1357745292\";}adminis_admin_action|b:1;adminactiveTopMenuId|s:2:\"50\";adminadminTopMenu|a:2:{s:6:\"博文\";s:0:\"\";s:12:\"日志管理\";s:59:\"/my5newYiiTry/rcyxw2013/admin.php/blog/post/admin/menuId/51\";}',1);

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
