<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="language" content="zh-cn" />
	<meta name="generator" content="林锋工作室 http://www.dlf5.net" />
	<meta name="author" content="<?php echo $this->settings->author ?>" />
	<meta name="description" content="<?php echo $this->settings->description ?>">
	<meta name="Keywords" content="<?php echo $this->settings->keywords ?>">
	<!-- blueprint CSS framework -->
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/print.css" media="print" />
	<!--[if lt IE 8]>
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/ie.css" media="screen, projection" />
	<![endif]-->
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/style.css" />
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/form.css" />
	<?php Yii::app()->clientScript->registerCoreScript('jquery'); ?>
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/shCoreDefault.css" />
	<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/shCore.js"></script>
	<script type="text/javascript"> SyntaxHighlighter.all()</script>
	
	<title><?php echo CHtml::encode($this->pageTitle); ?></title>
</head>
<body>

<div class="container" id="page">
	<!--  <div id="top-bar">
		<h1>WindsDeng's Blog - <?php echo $this->settings->blogdescription ?></h1>
	</div>-->
	<div id="header">
            <div id="logo"><a href="<?php echo Yii::app()->createUrl('post/index') ?>" title="<?php echo CHtml::encode($this->settings->site_name); ?>"></a></div>
		<div id="top-ad">
			ads
		</div>
		<div class="clear"></div>
	</div><!-- header -->
	
	<div id="mainmenu">
		<?php $this->widget('zii.widgets.CMenu',array(
			'id'=>'menu',
			'items'=>array(
				array('label'=>'Home', 'url'=>array('post/index')),
				array('label'=>'About', 'url'=>array('site/page', 'view'=>'about')),
				array('label'=>'Contact', 'url'=>array('site/contact')),
			),
		)); ?>
	</div><!-- mainmenu -->
	
	<!--<div id="banner">
		
	</div>-->

	<?php $this->widget('zii.widgets.CBreadcrumbs', array(
		'links'=>$this->breadcrumbs,
	)); ?><!-- breadcrumbs -->

	<?php echo $content; ?>

	<div id="footer">
		Copyright &copy; <?php echo date('Y'); ?> by <?php echo CHtml::link(CHtml::encode($this->settings->copyright),$this->settings->site_url,array('title'=>$this->settings->site_name.'-'.$this->settings->blogdescription)) ?>.
		All Rights Reserved.
		<?php echo Yii::powered(); ?>
		<?php echo $this->settings->icp?>
		<?php echo $this->settings->footer_info?>
        <script type="text/javascript" src="http://js.tongji.linezing.com/2975299/tongji.js"></script>
	</div><!-- footer -->

</div><!-- page -->

</body>
</html>