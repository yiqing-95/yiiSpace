<?php
$this->breadcrumbs=array(
	'Sys Audio Albums'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List SysAudioAlbum','url'=>array('index')),
	array('label'=>'Create SysAudioAlbum','url'=>array('create')),
	array('label'=>'Update SysAudioAlbum','url'=>array('update','id'=>$model->id)),
	array('label'=>'Delete SysAudioAlbum','url'=>'#','linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage SysAudioAlbum','url'=>array('admin')),
);
?>

<h1>View SysAudioAlbum #<?php echo $model->id; ?></h1>

<?php $this->widget('bootstrap.widgets.TbDetailView',array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'caption',
		'cover_uri',
		'location',
		'description',
		'type',
		'uid',
		'status',
		'create_time',
		'obj_count',
		'last_obj_id',
		'allow_view',
	),
)); ?>
