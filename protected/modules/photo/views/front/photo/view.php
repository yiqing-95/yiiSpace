<?php
$this->breadcrumbs=array(
	'Photos'=>array('index'),
	$model->title,
);

$this->menu=array(
	array('label'=>'List Photo','url'=>array('index')),
	array('label'=>'Create Photo','url'=>array('create')),
	array('label'=>'Update Photo','url'=>array('update','id'=>$model->id)),
	array('label'=>'Delete Photo','url'=>'#','linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Photo','url'=>array('admin')),
);
?>

<h1>View Photo #<?php echo $model->id; ?></h1>

<?php $this->widget('bootstrap.widgets.TbDetailView',array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'uid',
		'title',
		'desc',
		'path',
		'orig_path',
		'ext',
		'size',
		'tags',
		'create_time',
		'views',
		'rate',
		'rate_count',
		'cmt_count',
		'is_featured',
		'status',
		'hash',
		'categories',
	),
)); ?>
