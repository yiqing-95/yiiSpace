<?php
$this->breadcrumbs=array(
	'Comments'=>array('index'),
	$model->name,
);

$this->menu=array(
	array('label'=>'List Comment','url'=>array('index')),
	array('label'=>'Create Comment','url'=>array('create')),
	array('label'=>'Update Comment','url'=>array('update','id'=>$model->id)),
	array('label'=>'Delete Comment','url'=>'#','linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Comment','url'=>array('admin')),
);
?>

<h1>View Comment #<?php echo $model->id; ?></h1>

<?php $this->widget('bootstrap.widgets.TbDetailView',array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'parent_id',
		'user_id',
		'model',
		'model_id',
		'url',
		'create_time',
		'name',
		'email',
		'text',
		'status',
		'ip',
		'level',
		'root',
		'lft',
		'rgt',
	),
)); ?>
