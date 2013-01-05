<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="language" content="zh-cn" />
	<meta name="robots" content="none" />
	<!-- blueprint CSS framework -->
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/screen.css" media="screen, projection" />
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/print.css" media="print" />
	<!--[if lt IE 8]>
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/ie.css" media="screen, projection" />
	<![endif]-->

	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/main.css" />
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/form.css" />

	<title><?php echo CHtml::encode($this->pageTitle); ?></title>
</head>

<body>

<div class="container" id="page">
	
	<div id="mainmenu">
		<?php $this->widget('bootstrap.widgets.BootNavbar', array(
		    'fixed'=>false,
		    'brand'=>Yii::app()->name,
		    'brandUrl'=>'#',
		    'collapse'=>true, // requires bootstrap-responsive.css
		    'items'=>array(
		        array(
		            'class'=>'bootstrap.widgets.BootMenu',
		            'items'=>array(
		                array('label'=>Yii::t('backend', 'Site Setting'), 'url'=>array('/setting/update')),
						array('label'=>Yii::t('backend', 'Manage Users'), 'url'=>array('/user/admin')),
						array('label'=>Yii::t('backend', 'Manage Categories'), 'url'=>array('/category/admin')),
						array('label'=>Yii::t('backend', 'Manage Posts'), 'url'=>array('/post/admin')),
						array('label'=>Yii::t('backend', 'Manage Tags'), 'url'=>array('/tag/admin')),
						array('label'=>Yii::t('backend', 'Manage Comments'), 'url'=>array('/comment/admin')),
						array('label'=>Yii::t('backend', 'Manage Links'), 'url'=>array('/link/admin')),
						array('label'=>Yii::t('backend', 'Manage Attachments'), 'url'=>array('/attachment/admin')),	
                        array('label'=>Yii::t('backend', 'Manage Widgets'), 'url'=>array('/widgets/admin')),	
                    ),
				),
		       
		        array(
		            'class'=>'bootstrap.widgets.BootMenu',
		            'htmlOptions'=>array('class'=>'pull-right'),
		            'items'=>array(
		                array('label'=>Yii::app()->user->username, 'url'=>array('user/view','id'=>Yii::app()->user->id), 
							'items'=>array(
			                    array('label'=>Yii::t('backend', 'My Detail'), 'url'=>array('user/view','id'=>Yii::app()->user->id)),
			                    array('label'=>Yii::t('backend', 'Update').Yii::t('backend', 'Password'), 'url'=>array('user/update','id'=>Yii::app()->user->id)),
			                    '---',
			                    array('label'=>Yii::t('backend', 'Logout'), 'url'=>array('site/logout')),
		                )),
		            ),
		        ),
		    ),
		)); ?>
	</div><!-- mainmenu -->
	<?php if(isset($this->breadcrumbs)):?>
		<?php $this->widget('bootstrap.widgets.BootBreadcrumbs', array(
    			'links'=>$this->breadcrumbs,
			)); 
		?><!-- breadcrumbs -->
	<?php endif?>

	<?php echo $content; ?>

	<div id="footer">
		Copyright &copy; <?php echo date('Y'); ?> by 林锋工作室.
		All Rights Reserved.
		Powered by <a href="http://www.dlf5.net/" rel="external">dlf.net</a>.
        <script type="text/javascript" src="http://js.tongji.linezing.com/2975299/tongji.js"></script>
	</div><!-- footer -->

</div><!-- page -->

</body>
</html>
