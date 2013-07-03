<?php
$this->breadcrumbs=array(
	'Statuses'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List Status','url'=>array('index')),
    array('label'=>'Manage Status(advance mode) ','url'=>array('adminAdv')),
);
?>

<style type="text/css">
    .status-item {padding: 10px 0 10px 0;}
    .divider {margin-top: 20px; border-bottom: 1px solid
    #ccc;}
    .status-item .span1 a {color:red;}
</style>

<h1>Create Status ddd</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>
<div id="recent_statuses">
    <p class="divider"></p>
    <?php
    Yii::app()->runController('/status/status/listRecentStatus');
    ?>
</div>