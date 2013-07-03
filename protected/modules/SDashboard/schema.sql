-- 
-- Structure for table `tbl_dashboard`
-- 

DROP TABLE IF EXISTS `tbl_dashboard`;
CREATE TABLE IF NOT EXISTS `tbl_dashboard` (
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
) ENGINE=InnoDB AUTO_INCREMENT=38 DEFAULT CHARSET=utf8;

-- 
-- Data for table `tbl_dashboard`
-- 

INSERT INTO `tbl_dashboard` (`id`, `user_id`, `title`, `content`, `position`, `render`, `active`, `size`, `allowdelete`, `ajaxrequest`) VALUES
  ('32', '0', 'Ajax demo1', '', '170.5500030517578,123.63333129882812', '', '1', '207,41', '0', '/sdashboard/dashboard/demoAjax'),
  ('35', '0', 'A second portlet', 'with some content but [b]no ajax[/b]', '178,367.08331298828125', '', '1', '218,51', '0', ''),
  ('36', '0', 'Default portlet', '[size=200]A default portlet[/size]', '159,628.0908813476562', '', '1', '296,87', '0', '');

