<!DOCTYPE html>
<html lang="en">
<head>
    <?php $this->widget('my.widgets.reuze.EReuze');?>
    <meta charset="utf-8">
    <title><?php echo CHtml::encode(Yii::app()->name); ?></title>

</head>

<body>

<div style="margin-top: 90px;">

</div>
<!--    主布局不做宽度限制 交由下面的子布局决定-->
<?php echo $content; ?>

<hr/>
<div class="row" style="text-align: center;">
    <footer>
        <p class="powered">
            Powered
            by <?php echo CHtml::link('Yii PHP framework', 'http://www.yiiframework.com', array('target' => '_blank')); ?>
            /
            <?php echo CHtml::link('jQuery', 'http://www.jquery.com', array('target' => '_blank')); ?> /
            <?php echo CHtml::link('YiiBooster', 'http://yii-booster.clevertech.biz/', array('target' => '_blank')); ?>
            /
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
$this->widget('application.my.widgets.jnotify.JNotify',
    array(
    ));


?>
</body>

</html>