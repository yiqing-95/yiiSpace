<?php
$this->breadcrumbs=array(
	'Sys Audios'=>array('index'),
	$model->name,
);

$this->menu=array(
	array('label'=>'List SysAudio','url'=>array('index')),
	array('label'=>'Create SysAudio','url'=>array('create')),
	array('label'=>'Update SysAudio','url'=>array('update','id'=>$model->id)),
	array('label'=>'Delete SysAudio','url'=>'#','linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage SysAudio','url'=>array('admin')),
);
?>

<h1>View SysAudio #<?php echo $model->id; ?></h1>

<?php $this->widget('bootstrap.widgets.TbDetailView',array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'uid',
		'name',
		'singer',
		'summary',
		'uri',
		'source_type',
		'play_order',
		'listens',
		'create_time',
		'cmt_count',
		'glean_count',
		'file_size',
		'status',
	),
)); ?>
