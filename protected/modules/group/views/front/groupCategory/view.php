<?php
$this->breadcrumbs=array(
	'Group Categories'=>array('index'),
	$model->title,
);

$this->menu=array(
	array('label'=>'List GroupCategory','url'=>array('index')),
	array('label'=>'Create GroupCategory','url'=>array('create')),
	array('label'=>'Update GroupCategory','url'=>array('update','id'=>$model->id)),
	array('label'=>'Delete GroupCategory','url'=>'#','linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage GroupCategory','url'=>array('admin')),
);
?>

<h1>View GroupCategory #<?php echo $model->id; ?></h1>

<?php $this->widget('bootstrap.widgets.TbDetailView',array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'title',
		'type',
		'pid',
		'module',
	),
)); ?>
