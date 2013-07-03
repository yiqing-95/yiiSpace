/*
SQLyog Trial v10.2 
MySQL - 5.5.16-log : Database - api_doc
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
USE `api_doc`;

/*Table structure for table `api` */

DROP TABLE IF EXISTS `api`;

CREATE TABLE `api` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `method` varchar(255) NOT NULL COMMENT '方法名 rpc中所有api都是以函数形式暴露',
  `description` tinytext NOT NULL COMMENT '方法简要描述',
  `scenario` tinytext NOT NULL COMMENT '应用场景 所处的用例场景 暂时用text 其实每个场景可以单独建表',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='表示单个api方法';

/*Data for the table `api` */

/*Table structure for table `api_in_param` */

DROP TABLE IF EXISTS `api_in_param`;

CREATE TABLE `api_in_param` (
  `param_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键id',
  `api_id` int(11) NOT NULL COMMENT '外键关联到api表的主键',
  `name` varchar(120) NOT NULL COMMENT '参数名字',
  `type` varchar(120) NOT NULL COMMENT '参数类型 可以是自定义的！',
  `is_mandatory` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否是强制需要 is_option的反义',
  `demo_val` varchar(120) DEFAULT NULL COMMENT '示例值',
  `default` varchar(255) DEFAULT NULL COMMENT '默认值',
  `description` tinytext NOT NULL COMMENT '描述 可以不同场景不同描述哦',
  KEY `id` (`param_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='系统级api调用参数';

/*Data for the table `api_in_param` */

/*Table structure for table `api_sys_in_param` */

DROP TABLE IF EXISTS `api_sys_in_param`;

CREATE TABLE `api_sys_in_param` (
  `param_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键id',
  `api_id` int(11) NOT NULL COMMENT '外键关联到api表的主键',
  `name` varchar(120) NOT NULL COMMENT '参数名字',
  `type` varchar(120) NOT NULL COMMENT '参数类型 可以是自定义的！',
  `is_mandatory` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否是强制需要 is_option的反义',
  `description` varchar(255) NOT NULL COMMENT '参数描述',
  `category` varchar(120) NOT NULL DEFAULT 'http_signed_mode' COMMENT '组别 默认是http签名调用模式其实等同于场景调用',
  KEY `id` (`param_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='系统级api调用参数';

/*Data for the table `api_sys_in_param` */

/*Table structure for table `param_type` */

DROP TABLE IF EXISTS `param_type`;

CREATE TABLE `param_type` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `parent_id` int(11) NOT NULL DEFAULT '0' COMMENT '类型可以组合成自定义的类型 暂时忽略之',
  `name` varchar(120) NOT NULL COMMENT '数据类型名称 注意类型可以是自定义的',
  `description` tinytext NOT NULL COMMENT '类型描述 就不建立类型示例了',
  `position` tinyint(3) NOT NULL DEFAULT '0' COMMENT '出现顺序 比如定义一个类属性会有先后',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `param_type` */

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
