<?php
$this->breadcrumbs=array(
	'Groups'=>array('index'),
	$model->name,
);
/*
$this->menu=array(
	array('label'=>'List Group','url'=>array('index')),
	array('label'=>'Create Group','url'=>array('create')),
	array('label'=>'Update Group','url'=>array('update','id'=>$model->id)),
	array('label'=>'Delete Group','url'=>'#','linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
    array('label'=>'Manage Group(advanced mode) ','url'=>array('adminAdv')),
);*/
?>

<h1>View Group #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView',array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'name',
		'description',
		'creator',
		'created',
		'type',
		'active',
	),
)); ?>

<div id="topics">
    <?php
    WebUtil::ajaxLoad("#topics",Yii::app()->createUrl('group/groupTopic/admin4group',array('group'=>$model->id)));
  ?>
</div>

