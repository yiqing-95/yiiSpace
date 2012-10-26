<?php
$this->breadcrumbs=array(
	'Sys Modules'=>array('index'),
	$model->title,
);

$this->menu=array(
	array('label'=>'List SysModule','url'=>array('index')),
	array('label'=>'Create SysModule','url'=>array('create')),
	array('label'=>'Update SysModule','url'=>array('update','id'=>$model->id)),
	array('label'=>'Delete SysModule','url'=>'#','linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
    array('label'=>'Manage SysModule(advanced mode) ','url'=>array('adminAdv')),
);
?>

<h1>View SysModule #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView',array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'module_id',
		'title',
		'vendor',
		'version',
		'dependencies',
		'ctime',
	),
)); ?>
