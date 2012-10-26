<?php
$this->breadcrumbs=array(
	'Action Feeds'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List ActionFeed','url'=>array('index')),
	array('label'=>'Create ActionFeed','url'=>array('create')),
	array('label'=>'Update ActionFeed','url'=>array('update','id'=>$model->id)),
	array('label'=>'Delete ActionFeed','url'=>'#','linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
    array('label'=>'Manage ActionFeed(advanced mode) ','url'=>array('adminAdv')),
);
?>

<h1>View ActionFeed #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView',array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'uid',
		'action_type',
		'action_time',
		'data',
		'object_type',
		'object_id',
		'target_type',
		'target_id',
		'target_owner',
	),
)); ?>
