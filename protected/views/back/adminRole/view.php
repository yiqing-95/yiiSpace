<?php
$this->breadcrumbs=array(
	'Admin Roles'=>array('index'),
	$model->name,
);

$this->menu=array(
	array('label'=>'List AdminRole','url'=>array('index')),
	array('label'=>'Create AdminRole','url'=>array('create')),
	array('label'=>'Update AdminRole','url'=>array('update','id'=>$model->id)),
	array('label'=>'Delete AdminRole','url'=>'#','linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage AdminRole','url'=>array('admin')),
);
?>

<h1>View AdminRole #<?php echo $model->id; ?></h1>

<?php $this->widget('bootstrap.widgets.TbDetailView',array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'name',
		'description',
		'disabled',
		'create_time',
		'update_time',
	),
)); ?>
