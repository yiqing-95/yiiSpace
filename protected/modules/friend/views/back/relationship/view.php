<?php
$this->breadcrumbs=array(
	'Relationships'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List Relationship','url'=>array('index')),
	array('label'=>'Create Relationship','url'=>array('create')),
	array('label'=>'Update Relationship','url'=>array('update','id'=>$model->id)),
	array('label'=>'Delete Relationship','url'=>'#','linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Relationship','url'=>array('admin')),
);
?>

<h1>View Relationship #<?php echo $model->id; ?></h1>

<?php $this->widget('bootstrap.widgets.TbDetailView',array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'type',
		'user_a',
		'user_b',
		'accepted',
	),
)); ?>
