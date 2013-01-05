<?php
$this->breadcrumbs=array(
	'Links'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List Link','url'=>array('index')),
	array('label'=>'Create Link','url'=>array('create')),
	array('label'=>'Update Link','url'=>array('update','id'=>$model->id)),
	array('label'=>'Delete Link','url'=>'#','linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Link','url'=>array('admin')),
);
?>

<h1>View Link #<?php echo $model->id; ?></h1>

<?php $this->widget('bootstrap.widgets.BootDetailView',array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'sitename',
		'logo',
		'siteurl',
		'description',
		'target',
		'status',
		'position',
		'created',
		'updated',
	),
)); ?>
