<?php
$this->breadcrumbs=array(
	'Sys Friend Links'=>array('index'),
	$model->name,
);

$this->menu=array(
	array('label'=>'List SysFriendLink','url'=>array('index')),
	array('label'=>'Create SysFriendLink','url'=>array('create')),
	array('label'=>'Update SysFriendLink','url'=>array('update','id'=>$model->id)),
	array('label'=>'Delete SysFriendLink','url'=>'#','linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage SysFriendLink','url'=>array('admin')),
);
?>

<h1>View SysFriendLink #<?php echo $model->id; ?></h1>

<?php $this->widget('bootstrap.widgets.TbDetailView',array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'name',
		'logo',
		'url',
		'order',
		'enable',
	),
)); ?>
