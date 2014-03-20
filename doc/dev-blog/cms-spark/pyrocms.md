#  pyrocms
=============

导航设计
===============
    导航组：  网站无非就是有若干页面构成 每个页面都有导航 但有些是共用的  比如头部导航 底部
    侧边导航 对于非共用性导航 可以根据条件决定是否显示 比如visible_expression 动态执行该表达式
    在决定是否显示它

    导航链接：
    导航链接即为导航组内的html链接 看sql：
    ~~~
    [sql]

Create Table

CREATE TABLE `default_navigation_links` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `parent` int(11) DEFAULT NULL,
  `link_type` varchar(20) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'uri',
  `page_id` int(11) DEFAULT NULL,
  `module_name` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `url` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `uri` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `navigation_group_id` int(11) NOT NULL DEFAULT '0',
  `position` int(11) NOT NULL DEFAULT '0',
  `target` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `restricted_to` int(11) DEFAULT NULL,
  `class` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `navigation_links_navigation_group_id_index` (`navigation_group_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci


    ~~~
    里面的module_name 是给模块安装预留的 每个模块其实可以看做事个子站 或者看为是总站的组成部分
    不过其在页面上出现的区块不是那么一刀切，可能是交织出现的,
