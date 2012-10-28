<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title><?php echo CHtml::encode(Yii::app()->name); ?></title>
    <?php WebUtil::registerRandomBootstrapTheme() ;?>

</head>

<body>

<?php

$topUserMenus = array();
if(Yii::app()->user->isGuest){
  $topUserMenus = array(
        array('label' => 'Home', 'url' => array('/site/index')),
        array('url' => Yii::app()->getModule('user')->loginUrl, 'label' => Yii::app()->getModule('user')->t("Login"), ),
        array('url' => Yii::app()->getModule('user')->registrationUrl, 'label' => Yii::app()->getModule('user')->t("Register"),),
        array('label' => 'About', 'url' => array('/site/page', 'view' => 'about')),
    );
}else{

 $topUserMenus =  array(
       array('label' =>  Yii::app()->user->name, 'url' =>  Yii::app()->getModule('user')->returnUrl,),
       array('url' => Yii::app()->getModule('user')->profileUrl, 'label' => Yii::app()->getModule('user')->t("Profile"), 'visible' => !Yii::app()->user->isGuest),
       array('url' => Yii::app()->getModule('user')->logoutUrl, 'label' => Yii::app()->getModule('user')->t("Logout") . ' (' . Yii::app()->user->name . ')', 'visible' => !Yii::app()->user->isGuest),
   ) ;
}


$this->widget('bootstrap.widgets.TbNavbar',array(
    'type'=>'inverse',
    'brand'=>CHtml::encode(Yii::app()->name),
    'collapse'=>true,
    'items'=>array(
        array(
            'class'=>'bootstrap.widgets.TbMenu',
            'items'=>array(
                array('label'=>'index', 'url'=>Yii::app()->homeUrl,
                    'active'=>Yii::app()->controller->id === 'site' && Yii::app()->controller->action->id === 'index'),
                array('label'=>'user', 'url'=>array('/user/user/index'),
                    'active'=>Yii::app()->controller->id === 'site' && Yii::app()->controller->action->id === 'index'),
                array('label'=>'blog', 'url'=>Yii::app()->homeUrl,
                    'active'=>Yii::app()->controller->id === 'blog' && Yii::app()->controller->action->id === 'index'),
            ),
            'htmlOptions'=>array('class'=>'pull-left'),
        ),
        '<div class="add-this pull-right">
                        <!-- AddThis Button BEGIN -->
                        <div class="addthis_toolbox addthis_default_style">
                        <a class="addthis_button_facebook"></a>
                        <a class="addthis_button_twitter"></a>
                        <a class="addthis_button_google"></a>
                        <a class="addthis_button_email"></a>
                        <a class="addthis_button_compact"></a>
                        <a class="addthis_counter addthis_bubble_style"></a>
                        </div>
                        <!-- AddThis Button END -->
                </div>',
        array(
            'class'=>'bootstrap.widgets.TbMenu',
            'items'=>$topUserMenus,
            'htmlOptions'=>array('class'=>'pull-right'),
        ),
    ),
)); ?>
<div style="margin-top: 50px;">

</div>
<!--    主布局不做宽度限制 交由下面的子布局决定-->
    <?php echo $content; ?>

<hr />
<div class="row" style="text-align: center;">
    <footer>
        <p class="powered">
            Powered by <?php echo CHtml::link('Yii PHP framework', 'http://www.yiiframework.com', array('target'=>'_blank')); ?> /
            <?php echo CHtml::link('jQuery', 'http://www.jquery.com', array('target'=>'_blank')); ?> /
            <?php echo CHtml::link('YiiBooster', 'http://yii-booster.clevertech.biz/', array('target'=>'_blank')); ?> /
        </p>

        <p class="copy">
            &copy;YiiSpace <?php echo date('Y'); ?>
        </p>

    </footer>
</div>
<?php
$this->widget('ext.scrolltop.ScrollTop',
    array(
        //Default values
        'fadeTransitionStart' => 10,
        'fadeTransitionEnd' => 200,
        'speed' => 'slow'
    ));
?>

</body>
</html>