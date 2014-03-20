<?php
$this->breadcrumbs=array(
	'Blog Sys Categories'=>array('index'),
	$model->name,
);

$this->menu=array(
	array('label'=>'List BlogSysCategory','url'=>array('index')),
	array('label'=>'Create BlogSysCategory','url'=>array('create')),
	array('label'=>'Update BlogSysCategory','url'=>array('update','id'=>$model->id)),
	array('label'=>'Delete BlogSysCategory','url'=>'#','linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage BlogSysCategory','url'=>array('admin')),
);
?>

<h1>View BlogSysCategory #<?php echo $model->id; ?></h1>

<?php $this->widget('bootstrap.widgets.TbDetailView',array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'name',
		'position',
		'enable',
	),
)); ?>
