<!DOCTYPE html> 
<html> 
<head> 
<meta  http-equiv="content-type" content="text/html; charset=UTF-8" />
<meta name="language" content="zh-cn" />
<meta name="generator" content="WordPress 3.0.4" />
<meta name="author" content="<?php echo $this->settings->author ?>" />
<meta name="description" content="<?php echo $this->settings->description ?>">
<meta name="Keywords" content="<?php echo $this->settings->keywords ?>">
<title><?php echo CHtml::encode($this->pageTitle); ?></title>
<link rel="profile" href="http://gmpg.org/xfn/11" />
<link rel="stylesheet" type="text/css" media="all" href="<?php echo Yii::app()->theme->baseUrl; ?>/css/style.css" />
<link rel="pingback" href="http://www.dlf5.com/xmlrpc.php" />
<link rel="alternate" type="application/rss+xml" title="特殊的表白-邓林锋个人官方网 &raquo; feed" href="http://www.dlf5.com/?feed=rss2" />
<link rel="alternate" type="application/rss+xml" title="特殊的表白-邓林锋个人官方网 &raquo; 评论 feed" href="http://www.dlf5.com/?feed=comments-rss2" />
<link rel="canonical" href="http://www.dlf5.com/" />
<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/form.css" />
<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/shCoreDefault.css" />
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/shCore.js"></script>
<script type="text/javascript"> SyntaxHighlighter.all()</script>
</head> 
<body> 
	<!-- 头部开始 --> 
	<div id="header">
        <div id="top">
            <div id="header-info">
                <a href="<?php echo Yii::app()->createUrl('post/index') ?>" id="header-logo" title="<?php echo CHtml::encode($this->settings->site_name); ?>"><span><?php echo CHtml::encode($this->settings->site_name); ?></span></a>
                   
                    <?php $this->widget('zii.widgets.CMenu',array(
                        'id'=>'menu',
                        'items'=>array(
                            array('label'=>'Home', 'url'=>array('post/index')),
                            array('label'=>'About', 'url'=>array('site/page', 'view'=>'about')),
                            array('label'=>'Contact', 'url'=>array('site/contact')),
                        ),
                    )); ?>
            </div>
        </div>
    </div>
	<!-- 头部结束 --> 
    
    <div id="banner"></div>    
    
    <!-- 主体部份开始 --> 
    <div id="main">
    	<?php echo $content; ?>
    </div>
    <!-- 主体部份结束 --> 
    
     <!-- 脚部开始 -->
    <div id="footer">
    	<div id="colophon">
            <ul id="footer-nav">
            	<li><a href="#" title="首页">首页</a> |</li>
                <li><a href="#" title="关于">关于</a> |</li>
                <li><a href="#" title="日志">日志</a> |</li>
                <li><a href="#" title="相片">相片</a> |</li>
                <li><a href="#" title="留言">留言</a> |</li>
                <li><a href="#" title="Sitemap">Sitemap</a> |</li>
                <li><a href="#" title="Rss">Rss</a> <div class="clear"></div></li>
            </ul>
            <div id="copyright">
            	Copyright &copy; 2011 <a href="http://www.dlf5.com" target="_blank" title="WindsDeng">WindsDeng</a> All Rights Reserved. Powered By WindsDeng.
            </div>
        </div>
    </div>
    <!-- 脚部结束 --> 
    <script type="text/javascript" src="http://js.tongji.linezing.com/2975299/tongji.js"></script>
        <!--[if IE 6]>
        <script type="text/javascript" src="<?php echo Yii::app()->theme->baseUrl; ?>/js/png24.js" ></script>
        <script type="text/javascript">DD_belatedPNG.fix('a#header-logo,.up,.top');</script>
        <![endif]-->
</body> 
</html> 