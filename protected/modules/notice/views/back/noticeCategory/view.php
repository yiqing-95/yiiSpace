<?php
$this->breadcrumbs=array(
	'Notice Categories'=>array('index'),
	$model->name,
);

$this->menu=array(
	array('label'=>'List NoticeCategory','url'=>array('index')),
	array('label'=>'Create NoticeCategory','url'=>array('create')),
	array('label'=>'Update NoticeCategory','url'=>array('update','id'=>$model->id)),
	array('label'=>'Delete NoticeCategory','url'=>'#','linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage NoticeCategory','url'=>array('admin')),
);
?>

<h1>View NoticeCategory #<?php echo $model->id; ?></h1>

<?php $this->widget('bootstrap.widgets.TbDetailView',array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'name',
		'order',
		'enable',
	),
)); ?>
