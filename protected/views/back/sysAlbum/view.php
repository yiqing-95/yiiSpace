<?php
$this->breadcrumbs=array(
	'Sys Albums'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List SysAlbum','url'=>array('index')),
	array('label'=>'Create SysAlbum','url'=>array('create')),
	array('label'=>'Update SysAlbum','url'=>array('update','id'=>$model->id)),
	array('label'=>'Delete SysAlbum','url'=>'#','linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage SysAlbum','url'=>array('admin')),
);
?>

<h1>View SysAlbum #<?php echo $model->id; ?></h1>

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
