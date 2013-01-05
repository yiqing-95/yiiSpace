<?php
$this->breadcrumbs=array(
	'Options'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List Options', 'url'=>array('index')),
	array('label'=>'Create Options', 'url'=>array('create')),
	array('label'=>'Update Options', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete Options', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Options', 'url'=>array('admin')),
);
?>

<h1>View Options #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'object_id',
		'option_name',
		'option_value',
	),
)); ?>
