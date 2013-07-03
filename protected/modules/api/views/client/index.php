<?php
$this->breadcrumbs=array(
        'Oauth客户端'=>array('/api/client'),
        'test'=>array('/api/client/test'),
);
?>

<?php echo CHtml::link('web跳转方式 验证',array('client/web'),array('class'=>'btn primary'))?>

<?php echo CHtml::link('客户端方式 验证',array('client/app'),array('class'=>'btn primary'))?>


