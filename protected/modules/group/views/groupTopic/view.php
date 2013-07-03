<?php
$this->breadcrumbs=array(
	'Group Topics'=>array('index'),
	$model->name,
);

$this->menu=array(
	array('label'=>'List GroupTopic','url'=>array('index')),
	array('label'=>'Create GroupTopic','url'=>array('create')),
	array('label'=>'Update GroupTopic','url'=>array('update','id'=>$model->id)),
	array('label'=>'Delete GroupTopic','url'=>'#','linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
    array('label'=>'Manage GroupTopic(advanced mode) ','url'=>array('adminAdv')),
);
?>

<h1>View GroupTopic #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView',array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'name',
		'creator',
		'created',
		'active',
		'group',
	),
)); ?>
