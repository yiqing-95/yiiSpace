<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="language" content="en" />


	<title><?php echo CHtml::encode($this->pageTitle); ?></title>
</head>

<body style="margin-top:40px;">

<div class="container" id="page">
		<?php

        $items=$this->createWidget('EMBMenu', array(
                'scenarios' => 'frontend',
                'menuBuilderItemsBefore' => false,
                'menuOptions'=> array(
                    'items'=>array(
                        array('url'=>array('/menubuilder'),'label'=>'Menubuilder'),
                        array('label'=>'Login', 'url'=>array('/site/login'), 'visible'=>Yii::app()->user->isGuest),
                        array('label'=>'Logout ('.Yii::app()->user->name.')', 'url'=>array('/site/logout'), 'visible'=>!Yii::app()->user->isGuest)
                    )
                ),
            )
        )->getItems();


        $this->widget('bootstrap.widgets.TbNavbar', array(
            'collapse' => true,
            'items' => array(
                array(
                    'class' => 'bootstrap.widgets.TbMenu',
                    'encodeLabel'=>false,
                    'items' => $items,
                )
            )
        ));
        ?>

    <div class="clear" style="padding: 10px;">
        The menu above is the merged result of <b>all menus with scenario "frontend"</b> merged with the <b>hardcoded</b> items Menubuilder / Login or Logout.
    </div>
	<?php echo $content; ?>

	<div id="footer">
		Copyright &copy; <?php echo date('Y'); ?> by My Company.<br/>
		All Rights Reserved.<br/>
		<?php echo Yii::powered(); ?>
	</div><!-- footer -->

</div><!-- page -->

</body>
</html>