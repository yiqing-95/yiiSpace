<?php
$this->breadcrumbs=array(
	'Yiisessions'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List Yiisession','url'=>array('index')),
	array('label'=>'Create Yiisession','url'=>array('create')),
	array('label'=>'Update Yiisession','url'=>array('update','id'=>$model->id)),
	array('label'=>'Delete Yiisession','url'=>'#','linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Yiisession','url'=>array('admin')),
);
?>

<h1>View Yiisession #<?php echo $model->id; ?></h1>

<?php $this->widget('bootstrap.widgets.TbDetailView',array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'expire',
		'data',
		'user_id',
	),
)); ?>
