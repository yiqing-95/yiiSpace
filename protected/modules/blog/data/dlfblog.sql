-- phpMyAdmin SQL Dump
-- version 3.4.8
-- http://www.phpmyadmin.net
--
-- 主机: localhost
-- 生成日期: 2012 年 09 月 24 日 17:22
-- 服务器版本: 5.1.60
-- PHP 版本: 5.2.17p1

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- 数据库: `dlfblog`
--

-- --------------------------------------------------------

--
-- 表的结构 `dlf_attachment`
--

CREATE TABLE IF NOT EXISTS `dlf_attachment` (
  `id` bigint(32) unsigned NOT NULL AUTO_INCREMENT COMMENT '序号',
  `post_id` int(11) unsigned NOT NULL COMMENT '博客序号',
  `filename` varchar(255) NOT NULL COMMENT '附件名称',
  `filesize` int(11) unsigned NOT NULL DEFAULT '0',
  `filepath` varchar(255) NOT NULL COMMENT '附件路径',
  `created` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `updated` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`),
  KEY `post_id` (`post_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='附件表' AUTO_INCREMENT=2 ;

--
-- 转存表中的数据 `dlf_attachment`
--

INSERT INTO `dlf_attachment` (`id`, `post_id`, `filename`, `filesize`, `filepath`, `created`, `updated`) VALUES
(1, 66, '66', 6, '6', 0, 0);

-- --------------------------------------------------------

--
-- 表的结构 `dlf_category`
--

CREATE TABLE IF NOT EXISTS `dlf_category` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '序号',
  `pid` int(11) unsigned NOT NULL DEFAULT '0',
  `name` varchar(255) NOT NULL,
  `alias` varchar(255) DEFAULT NULL COMMENT '别名',
  `position` int(11) unsigned DEFAULT '0' COMMENT '排序序号',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='分类表' AUTO_INCREMENT=3 ;

--
-- 转存表中的数据 `dlf_category`
--

INSERT INTO `dlf_category` (`id`, `pid`, `name`, `alias`, `position`) VALUES
(1, 0, 'yii', 'yii', 0),
(2, 0, 'php', 'php', 0);

-- --------------------------------------------------------

--
-- 表的结构 `dlf_comment`
--

CREATE TABLE IF NOT EXISTS `dlf_comment` (
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
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=61 ;

--
-- 转存表中的数据 `dlf_comment`
--

INSERT INTO `dlf_comment` (`id`, `content`, `status`, `created`, `author`, `email`, `url`, `ip`, `post_id`) VALUES
(1, 'This is a test comment.', 2, 1230952187, 'Tester', 'tester@example.com', NULL, NULL, 2),
(58, '<a href="http://beats-by-dre8.webnode.fr/">casque beats by dre</a> EcLaGbB<a href=http://beats-by-dre8.webnode.fr/>beats by dre detox</a> RzRmYk http://beats-by-dre8.webnode.fr/ <a href="http://christianlouboutinpas-cher.webnode.fr/">louboutin soldes</a> XmCiDnI<a href=http://christianlouboutinpas-cher.webnode.fr/>christian louboutin pas cher</a> HfOxXq http://christianlouboutinpas-cher.webnode.fr/ <a href="http://beats-by-dre-pas-cher8.webnode.fr/">Casque Dr Dre</a> IrFaLsU<a href=http://beats-by-dre-pas-cher8.webnode.fr/>casque beats by dre</a> WrBpEy http://beats-by-dre-pas-cher8.webnode.fr/ <a href="http://christianlouboutinhomme2.webnode.fr/">christian louboutin soldes</a> LdKfVqM<a href=http://christianlouboutinhomme2.webnode.fr/>chaussures louboutin soldes</a> GxBoSu http://christianlouboutinhomme2.webnode.fr/ <a href="http://beats-by-dre-studio0.webnode.fr/">casque beats</a> CjKrNwV<a href=http://beats-by-dre-studio0.webnode.fr/>casque beats</a> UiByBt http://beats-by-dre-studio0.webnode.fr/', 1, 1348470973, 'jqumatrw', 'christiubousoldes@gmail.com', 'http://christiubousoldes.webnode.fr/', '216.244.78.98', 11),
(4, '新版感觉怎么样呀！', 2, 1322327794, 'winds', 'windsdeng@hotmail.com', 'http://www.dlf5.com', NULL, 3),
(5, 'This blog system is developed using Yii. ', 2, 1322327830, 'winds', 'windsdeng@hotmail.com', 'http://www.dlf5.com', NULL, 1),
(7, '磊', 2, 1333334787, 'winds', 'winds', '', NULL, 11),
(8, 'tests', 2, 1342664286, 'winds', 'winds@dlf5.com', '', NULL, 18),
(9, 'aaaaaaaaaaaaa', 2, 1342670342, 'winds', 'winds@dlf5.com', '', '14.151.160.139', 18),
(12, 'IP所在地演示', 2, 1343036021, 'winds', 'winds@dlf5.com', 'http://dlf5.com', '14.151.136.184', 1),
(13, 'teste', 2, 1343170499, 'teste', 'teste@teste.com', 'http://teste.com', '186.204.167.230', 1),
(14, 'This blog system is developed using Yii.', 2, 1343180043, 'a', 'a@yahoo.com', '', '110.139.119.47', 1),
(15, 'laskdjfasdf', 2, 1343227116, 'qwer', 'qwer@ss.cc', '', '122.87.148.182', 18),
(17, '192.168.10.1 Yeah.........', 2, 1343520946, 'melengo', 'ozan.rock@yahoo.co.id', 'http://melengo.wordpress.com', '103.3.222.228', 1),
(18, 'test', 2, 1343541617, 'preketek', 'admin@google.co.id', '', '125.163.104.55', 1),
(19, 'asd', 2, 1343786964, '123', 'asd@asd.asd', '', '211.144.84.242', 1),
(20, 'test', 2, 1343824371, 'test', 'test@test.com', 'http://www.test.com', '101.109.214.68', 1),
(21, 'asasd', 2, 1344031521, 'sephiroth', 'plop@gmail.com', '', '190.201.47.138', 1),
(24, 'test', 2, 1345530860, 'winds', 'winds@dlf5.com', 'http://dlf5.com', '59.41.95.95', 1),
(25, 'winds', 2, 1345530897, 'winds', 'winds@dlf5.com', 'http://dlf5.com', '59.41.95.95', 1),
(26, 'to to to ', 2, 1345530935, 'winds', 'winds@dlf5.com', 'http://dlf5.com', '59.41.95.95', 1),
(27, 'Test', 2, 1345618336, 'mbahsomo', 'mbahsomo@do-event.com', '', '111.196.127.133', 1),
(59, '<a href="http://casque-dr-dre1.webnode.com/">beats by dre pas cher</a> IyDlLqJ<a href=http://casque-dr-dre1.webnode.com/>beats by dre</a> LdMwKy http://casque-dr-dre1.webnode.com/ <a href="http://louboutin-pas-cher1.webnode.fr/">christian louboutin pas cher</a> OdIvEpH<a href=http://louboutin-pas-cher1.webnode.fr/>christian louboutin pas cher</a> MgHqQt http://louboutin-pas-cher1.webnode.fr/ <a href="http://monster-beats11.webnode.fr/">casque beats by dre</a> QgHmFzA<a href=http://monster-beats11.webnode.fr/>casque beats by dre</a> PrGqKd http://monster-beats11.webnode.fr/ <a href="http://louboutin-soldes1.webnode.fr/">chaussures louboutin pas cher</a> WhZkRrA<a href=http://louboutin-soldes1.webnode.fr/>christian louboutin soldes</a> ZsBtFh http://louboutin-soldes1.webnode.fr/ <a href="http://casque-beats11.webnode.fr/">beats by dre test</a> AbViOoA<a href=http://casque-beats11.webnode.fr/>monster beats</a> FyPbIn http://casque-beats11.webnode.fr/', 1, 1348471006, 'rzluzowz', 'christianpnrp@gmail.com', 'http://beats-by-dre11.webnode.fr/', '216.244.78.98', 11),
(60, '<a href="http://louboutinmouche.webnode.fr/">christian louboutin soldes</a> TrEmPkP<a href=http://louboutinmouche.webnode.fr/>chaussures louboutin soldes</a> QfOzUw http://louboutinmouche.webnode.fr/ <a href="http://monsterbeatsbstudio5.webnode.fr/">beats by dre detox</a> IxFjZbN<a href=http://monsterbeatsbstudio5.webnode.fr/>casque beats</a> CfGzNv http://monsterbeatsbstudio5.webnode.fr/ <a href="http://louboutinny22.webnode.fr/">christian louboutin soldes</a> TvIaGsS<a href=http://louboutinny22.webnode.fr/>christian louboutin france</a> KzYpQb http://louboutinny22.webnode.fr/ <a href="http://casquebeatsbycher8.webnode.fr/">casque monster beats</a> OnHhAwZ<a href=http://casquebeatsbycher8.webnode.fr/>monster beats</a> RvNwOh http://casquebeatsbycher8.webnode.fr/ <a href="http://christianlouboutinfr.webnode.fr/">christian louboutin france</a> DxOgIjI<a href=http://christianlouboutinfr.webnode.fr/>louboutin soldes</a> IqJvBz http://christianlouboutinfr.webnode.fr/', 1, 1348472008, 'guwvphuw', 'casqeabdreqep@gmail.com', 'http://louboutinmouche.webnode.fr/', '216.244.78.98', 11);

-- --------------------------------------------------------

--
-- 表的结构 `dlf_link`
--

CREATE TABLE IF NOT EXISTS `dlf_link` (
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
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `dlf_lookup`
--

CREATE TABLE IF NOT EXISTS `dlf_lookup` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(128) NOT NULL,
  `code` int(11) unsigned NOT NULL,
  `type` varchar(128) NOT NULL,
  `position` int(11) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;

--
-- 转存表中的数据 `dlf_lookup`
--

INSERT INTO `dlf_lookup` (`id`, `name`, `code`, `type`, `position`) VALUES
(1, 'Draft', 1, 'PostStatus', 1),
(2, 'Published', 2, 'PostStatus', 2),
(3, 'Archived', 3, 'PostStatus', 3),
(4, 'Pending Approval', 1, 'CommentStatus', 1),
(5, 'Approved', 2, 'CommentStatus', 2);

-- --------------------------------------------------------

--
-- 表的结构 `dlf_options`
--

CREATE TABLE IF NOT EXISTS `dlf_options` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `object_id` int(11) unsigned NOT NULL DEFAULT '0',
  `option_name` varchar(255) NOT NULL COMMENT '选项名称',
  `option_value` text NOT NULL COMMENT '值',
  PRIMARY KEY (`id`),
  KEY `option_name` (`option_name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='选项设置表' AUTO_INCREMENT=2 ;

--
-- 转存表中的数据 `dlf_options`
--

INSERT INTO `dlf_options` (`id`, `object_id`, `option_name`, `option_value`) VALUES
(1, 0, 'settings', '{"site_name":"windsdeng''s blog","site_closed":"no","close_information":"\\u7f51\\u7ad9\\u5728\\u7ef4\\u62a4\\u4e2d\\u3002<br \\/> \\u8bf7\\u7a0d\\u5019\\u8bbf\\u95ee\\u3002","site_url":"http:\\/\\/demo.dlf5.net\\/","keywords":"\\u9093\\u6797\\u950b\\u7684\\u535a\\u5ba2","description":"\\u9093\\u6797\\u950b\\u7684\\u535a\\u5ba2http:\\/\\/www.dlf5.com","copyright":"windsdeng''s blog","author":"winds","blogdescription":"\\u9093\\u6797\\u950b\\u7684\\u535a\\u5ba2","default_editor":"ueditor","theme":"classic","email":"winds@dlf5.com","rss_output_num":"10","rss_output_fulltext":"yes","post_num":"10","time_zone":"\\u4e0a\\u6d77","icp":"","footer_info":"","rewrite":"no","showScriptName":"false","urlSuffix":".html"}');

-- --------------------------------------------------------

--
-- 表的结构 `dlf_post`
--

CREATE TABLE IF NOT EXISTS `dlf_post` (
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
  PRIMARY KEY (`id`),
  KEY `FK_post_author` (`author_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=27 ;

--
-- 转存表中的数据 `dlf_post`
--

INSERT INTO `dlf_post` (`id`, `title`, `content`, `summary`, `tags`, `status`, `created`, `updated`, `author_id`, `category_id`) VALUES
(1, 'Welcome!', 'This blog system is developed using Yii. It is meant to demonstrate how to use Yii to build a complete real-world application. Complete source code may be found in the Yii releases.\n\nFeel free to try this system by writing new posts and posting comments.', 'This blog system is developed using Yii. It is meant to demonstrate how to use Yii to build a complete real-world application. Complete source code may be found in the Yii releases.', 'yii, blog', 2, 1230952187, 1230952187, 1, 0),
(2, 'A Test Post', 'Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.', 'Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. ', 'test', 2, 1230952187, 1230952187, 1, 0),
(3, '我的博客', '最新更新我的博客，新版感觉怎么样呀！', '最新更新我的博客，新版感觉怎么样呀！', '', 2, 1322064648, 1322064686, 1, 0),
(7, 'ueditor-for-yii 所见即所得富文本web编辑器', '<p><a style="text-decoration:underline;" href="http://code.google.com/p/ueditor-for-yii/"><span style="font-size:16px">ueditor-for-yii</span></a></p><div id="psum"><a id="project_summary_link" href="http://code.google.com/p/ueditor-for-yii/">Ueditor是由百度web前端研发部开发的所见即所得富文本web编辑器，开源基于BSD协议。 &nbsp; &nbsp; &nbsp; &nbsp;</a></div><div>最近看到百度开源的一个产品Ueditor 所见即所得富文本web编辑器，感觉很不错，最近又有一个项目，是用YiiFramework 开发的，就把Ueditor 用在这项目里了，于是就把它写成了extensions形式提供给大家下载！yii 地址：<a href="http://www.yiiframework.com/extension/ueditor-for-yii/">http://www.yiiframework.com/extension/ueditor-for-yii/ &nbsp; &nbsp; &nbsp; &nbsp;</a></div><div>由于文件大过2M上传不了到YII官方网站的extensions库里！不一会就好几个差评了！最来就把它上传到谷歌上面了！</div><div>如果有用到的话大家拿去吧！下载地址：<a href="http://code.google.com/p/ueditor-for-yii/downloads/list">http://code.google.com/p/ueditor-for-yii/downloads/list &nbsp; &nbsp; &nbsp; &nbsp;</a></div><div>使用方法：</div><p>…how to use this extension…</p><p>把ueditor插件放到 extensions/ 在Html 调用</p><pre class="brush:php;toolbar:false;">&lt;?php    \r\n    $this-&gt;widget(''ext.ueditor.Ueditor'',    \r\n            array(    \r\n                ''getId''=&gt;''Article_content'',    \r\n                ''textarea''=&gt;"Article[content]",    \r\n                ''imagePath''=&gt;''/attachment/ueditor/'',    \r\n                ''UEDITOR_HOME_URL''=&gt;''/'',    \r\n            ));    \r\n?&gt;</pre><p>订制Toolbars 方法</p><pre class="brush:php;toolbar:false;">&lt;?php    \r\n    $this-&gt;widget(''ext.ueditor.Ueditor'',    \r\n            array(    \r\n                ''getId''=&gt;''Settings_about'',    \r\n                ''minFrameHeight''=&gt;180,    \r\n                ''textarea''=&gt;"Article[content]",    \r\n                ''imagePath''=&gt;''/attachment/ueditor/'',    \r\n                ''UEDITOR_HOME_URL''=&gt;''/'',    \r\n                ''toolbars''=&gt;"''Undo'',''Redo'',''ForeColor'', ''BackColor'', ''Bold'',''Italic'',''Underline'', ''JustifyLeft'',''JustifyCenter'',''JustifyRight'', ,''InsertImage'',''ImageNone'',''ImageLeft'',''ImageRight'',''ImageCenter'',",    \r\n            ));    \r\n?&gt;</pre><p><br /></p><p>关于UEditor</p><p>Ueditor概述 Ueditor是由百度web前端研发部开发的所见即所得富文本web编辑器，具有轻量，可定制，注重用户体验等特点，开源基于BSD协议，允许自由使用和使用代码 为什么使用Ueditor 体积小巧，性能优良，使用简单 分层架构，方便定制与扩展 满足不同层次用户需求，更加适合团队开发 丰富完善的中文文档 多个浏览器支持：Mozilla, MSIE, FireFox?, Maxthon,Safari 和Chrome 更好的使用体验 拥有专业QA团队持续支持，已应用在百度各大产品线上</p><p><br /></p>', 'Ueditor是由百度web前端研发部开发的所见即所得富文本web编辑器，开源基于BSD协议。</a></div><div>最近看到百度开源的一个产品Ueditor 所见即所得富文本web编辑器，感觉很不错，最近又有一个项目，是用YiiFramework 开发的，就把Ueditor 用在这项目里了，于是就把它写成了extensions形式提供给大家下载！', 'UEditor', 2, 1322404562, 1342545530, 1, 1),
(8, 'Your title here', 'break-all', 'Your title here', 'yii', 2, 1322580959, 1342664214, 1, 1),
(24, '新增文章归档功能', '<p>新增文章归档功能<br /></p><p><span style="color:#ffffff;font-family:&#39;helvetica neue&#39;, helvetica, arial, sans-serif;font-size:18px;font-weight:bold;line-height:18px;background-color:#1187dc;">Archives</span><br /></p>', '新增文章归档功能', 'Archives,文章归档', 2, 1347253302, 1347253302, 1, 1),
(9, 'Your Code here', 'Your&nbsp;title&nbsp;here...\r\n==================Y\r\nour&nbsp;title&nbsp;here...\r\n------------------\r\n###&nbsp;Your&nbsp;title&nbsp;here...\r\n###&nbsp;Your&nbsp;title&nbsp;here...\r\n#####&nbsp;Your&nbsp;title&nbsp;here...\r\n######&nbsp;Your&nbsp;title&nbsp;here...\r\n~~~\r\n[php]\r\nYour&nbsp;Code&nbsp;here...\r\n~~~', 'Your Code here', 'Your Code here', 2, 1323511144, 1342663980, 1, 1),
(11, '标题 cannot be blank. ', 'Your title here...\r\n==================\r\nYour title here...\r\n------------------\r\n### Your title here...\r\n#### Your title here...\r\n##### Your title here...\r\n###### Your title here...\r\n\r\n', '', '', 2, 1323705679, 1323705679, 1, 0),
(12, '这在测试中', '<p id="initContent">这在测试中</p><p id="initContent">这在测试中<br /></p><ol style="list-style-type:decimal;"><li><p id="initContent">这在测试中</p></li><li><p id="initContent">这在测试中</p></li><li><p id="initContent">这在测试中</p></li><li><p id="initContent"><span>这在测试中</span><br /></p></li></ol>', 'test\r\n', 'test', 2, 1342540719, 1342593593, 1, 1),
(13, '我要做测试', '<p id="initContent">我要做测试<br /></p><p><strong>我要做测试</strong><br /></p><ol style="list-style-type:decimal;"><li><p><strong>我要做测试</strong></p></li><li><p><strong>我要做测试</strong></p></li><li><p><strong>我要做测试</strong></p></li><li><p><strong>我要做测试<br /></strong></p></li></ol><p><br /></p>', 'test', 'test', 2, 1342540786, 1342593609, 1, 1),
(14, 'ueditor-for-yii ', '<p>it is code </p><p><span style="color:#222222;font-family:arial, sans-serif;font-size:13px;font-style:italic;line-height:19px;background-color:#ffffff;">Ueditor是由百度web前端研发部开发的所见即所得富文本web编辑器，开源基于BSD协议。</span><br /></p><pre class="brush:php;toolbar:false;">&lt;?php\r\n    $this-&gt;widget(''ext.ueditor.Ueditor'',\r\n            array(\r\n                ''getId''=&gt;''Post_content'',\r\n                ''UEDITOR_HOME_URL''=&gt;"/",\r\n                ''options''=&gt;''toolbars:[["fontfamily","fontsize","forecolor","bold","italic","strikethrough","|",\r\n"insertunorderedlist","insertorderedlist","blockquote","|",\r\n"link","unlink","highlightcode","|","undo","redo","source"]],\r\n                    wordCount:false,\r\n                    elementPathEnabled:false,\r\n                    imagePath:"/attachment/ueditor/",\r\n                    '',\r\n            ));\r\n?&gt;</pre><p><br /></p>', 'Ueditor是由百度web前端研发部开发的所见即所得富文本web编辑器，开源基于BSD协议。', 'php', 2, 1342541883, 1342606184, 1, 1),
(17, 'php递归实现99乘法表', '<p>代码如下：</p><p><span style="color:#333333;font-family:georgia, &#39;bitstream charter&#39;, serif;line-height:24px;background-color:#ffffff;"></span></p><pre class="brush:php;toolbar:false;">&lt;?php\r\nfunction _99 ($n) {\r\nfor ($i=1;$i&lt;=$n;$i++) {\r\necho $i.’*’.$n.’=’.$n*$i.’&amp;nbsp;’;\r\n}\r\necho ‘&lt;br/&gt;’;\r\n$pre = $n – 1;\r\nif ($pre &lt; $n &amp;&amp; $pre &gt; 0) {\r\n_99 ($pre);\r\n}   \r\n}\r\n_99 (9);  \r\n?&gt;</pre><p><span style="color:#333333;font-family:georgia, bitstream charter, serif;"><span style="line-height:24px;"><span style="color:#333333;font-family:georgia, &#39;bitstream charter&#39;, serif;line-height:24px;background-color:#ffffff;">结果如下：</span><br /></span></span></p><p><span style="color:#333333;font-family:georgia, bitstream charter, serif;"><span style="line-height:24px;"><span style="color:#333333;font-family:georgia, &#39;bitstream charter&#39;, serif;line-height:24px;background-color:#ffffff;"><br /></span></span></span></p><blockquote><p><span style="color:#333333;font-family:georgia, bitstream charter, serif;"><span style="line-height:24px;"><span style="color:#333333;font-family:georgia, &#39;bitstream charter&#39;, serif;line-height:24px;background-color:#ffffff;"><span style="color:#333333;font-family:georgia, &#39;bitstream charter&#39;, serif;line-height:24px;background-color:#ffffff;">1*9=9 2*9=18 3*9=27 4*9=36 5*9=45 6*9=54 7*9=63 8*9=72 9*9=81</span><br style="color:#333333;font-family:georgia, &#39;bitstream charter&#39;, serif;line-height:24px;background-color:#ffffff;" /><span style="color:#333333;font-family:georgia, &#39;bitstream charter&#39;, serif;line-height:24px;background-color:#ffffff;">1*8=8 2*8=16 3*8=24 4*8=32 5*8=40 6*8=48 7*8=56 8*8=64</span><br style="color:#333333;font-family:georgia, &#39;bitstream charter&#39;, serif;line-height:24px;background-color:#ffffff;" /><span style="color:#333333;font-family:georgia, &#39;bitstream charter&#39;, serif;line-height:24px;background-color:#ffffff;">1*7=7 2*7=14 3*7=21 4*7=28 5</span><br /></span></span></span></p></blockquote><p><span style="color:#333333;font-family:georgia, &#39;bitstream charter&#39;, serif;line-height:24px;background-color:#ffffff;"><br /></span></p>', '代码如下：', 'php', 2, 1342606433, 1342606475, 1, 2),
(18, 'Yii在Nginx下的rewrite配置', '<p><span style="color:#333333;font-family:georgia, &#39;bitstream charter&#39;, serif;line-height:24px;background-color:#ffffff;">1. Nginx配置</span><br style="color:#333333;font-family:georgia, &#39;bitstream charter&#39;, serif;line-height:24px;background-color:#ffffff;" /><span style="color:#333333;font-family:georgia, &#39;bitstream charter&#39;, serif;line-height:24px;background-color:#ffffff;">在nginx.conf的server {段添加类似如下代码：</span><br style="color:#333333;font-family:georgia, &#39;bitstream charter&#39;, serif;line-height:24px;background-color:#ffffff;" /><span style="color:#333333;font-family:georgia, &#39;bitstream charter&#39;, serif;line-height:24px;background-color:#ffffff;">Nginx.conf代码:</span><br style="color:#333333;font-family:georgia, &#39;bitstream charter&#39;, serif;line-height:24px;background-color:#ffffff;" /><font color="#333333" face="monaco, consolas, andale mono, dejavu sans mono, monospace" size="2"><span style="line-height:24px;"></span></font></p><pre class="brush:bash;toolbar:false;">location / {\r\nif (!-e $request_filename){\r\nrewrite ^/(.*) /index.php last;\r\n}\r\n}</pre><p><font color="#333333" face="monaco, consolas, andale mono, dejavu sans mono, monospace" size="2"><span style="line-height:24px;"><br style="background-color:#FFFFFF;" /></span></font><span style="color:#333333;font-family:georgia, &#39;bitstream charter&#39;, serif;line-height:24px;background-color:#ffffff;">2. 在Yii的protected/conf/main.php去掉如下的注释</span><br style="color:#333333;font-family:georgia, &#39;bitstream charter&#39;, serif;line-height:24px;background-color:#ffffff;" /><span style="color:#333333;font-family:georgia, &#39;bitstream charter&#39;, serif;line-height:24px;background-color:#ffffff;">Php代码:</span><br style="color:#333333;font-family:georgia, &#39;bitstream charter&#39;, serif;line-height:24px;background-color:#ffffff;" /><font color="#333333" face="monaco, consolas, andale mono, dejavu sans mono, monospace" size="2"><span style="line-height:24px;"></span></font></p><pre class="brush:php;toolbar:false;">''urlManager''=&gt;array(\r\n''urlFormat''=&gt;''path'',\r\n''rules''=&gt;array(\r\n''/''=&gt;''/view'',\r\n''//''=&gt;''/'',\r\n''/''=&gt;''/'',\r\n),\r\n),</pre><p><font color="#333333" face="monaco, consolas, andale mono, dejavu sans mono, monospace" size="2"><span style="line-height:24px;"><br /></span></font></p>', 'Nginx配置', 'yii', 2, 1342606936, 1342606936, 1, 1),
(25, '钓鱼岛是中国的', '<h2 style="margin:0px;color:#555555;font-family:arial, helvetica, sans-serif;background-color:#ffffff;padding-bottom:12px;">钓鱼岛是中国的</h2><h2 style="margin:0px;color:#555555;font-family:arial, helvetica, sans-serif;background-color:#ffffff;padding-bottom:12px;">钓鱼岛是中国的</h2><h2 style="margin:0px;color:#555555;font-family:arial, helvetica, sans-serif;background-color:#ffffff;padding-bottom:12px;">钓鱼岛是中国的</h2><h2 style="margin:0px;color:#555555;font-family:arial, helvetica, sans-serif;background-color:#ffffff;padding-bottom:12px;">钓鱼岛是中国的</h2><p><br /><br /></p><p><br /></p>', '钓鱼岛是中国的', '钓鱼岛', 2, 1347357597, 1347357597, 1, 1),
(26, '新增皮肤功能', '<p>新增皮肤功能</p><p>修改<br />在config/main.php</p><pre class="brush:php;toolbar:false;">''theme''=&gt;''classic'',     //皮肤配置 default为默认或注释掉</pre><p>欢迎大家下载学习。</p><p><a href="https://github.com/windsdeng/dlfblog">https://github.com/windsdeng/dlfblog</a></p><p><br /></p><h2><a name="qq交流群" class="anchor" href="https://github.com/windsdeng/dlfblog#qq%E4%BA%A4%E6%B5%81%E7%BE%A4"></a>QQ交流群</h2><p><code>1、185207750</code></p><p><br /></p><p>有什么建议可以提出来</p><p>所有功能都先是架起一个大至的框架，到时慢慢细致。<br /></p>', '新增皮肤功能\r\n有什么建议可以提出来', 'classic,theme', 2, 1348392308, 1348398138, 1, 1);

-- --------------------------------------------------------

--
-- 表的结构 `dlf_tag`
--

CREATE TABLE IF NOT EXISTS `dlf_tag` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(128) NOT NULL,
  `frequency` int(11) unsigned DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=16 ;

--
-- 转存表中的数据 `dlf_tag`
--

INSERT INTO `dlf_tag` (`id`, `name`, `frequency`) VALUES
(1, 'yii', 3),
(2, 'blog', 1),
(3, 'test', 3),
(6, 'UEditor', 1),
(7, 'php', 2),
(14, 'classic', 1),
(11, 'Archives', 1),
(12, '文章归档', 1),
(13, '钓鱼岛', 1),
(15, 'theme', 1);

-- --------------------------------------------------------

--
-- 表的结构 `dlf_user`
--

CREATE TABLE IF NOT EXISTS `dlf_user` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(128) NOT NULL,
  `nickname` varchar(32) NOT NULL COMMENT '昵称',
  `password` varchar(128) NOT NULL,
  `avatar` varchar(128) NOT NULL COMMENT '头像',
  `salt` varchar(128) NOT NULL,
  `email` varchar(128) NOT NULL,
  `profile` text,
  `counts` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '登录次数',
  `created` int(11) unsigned NOT NULL DEFAULT '0',
  `updated` int(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- 转存表中的数据 `dlf_user`
--

INSERT INTO `dlf_user` (`id`, `username`, `nickname`, `password`, `avatar`, `salt`, `email`, `profile`, `counts`, `created`, `updated`) VALUES
(1, 'admin', '演示', '8cf529a608d0fc7edc35fb130ffea391', '', '28b206548469ce62182048fd9cf91760', 'webmaster@example.com', '', 112, 0, 1348395537);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
