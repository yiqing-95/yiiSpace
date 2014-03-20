<?php
$this->breadcrumbs=array(
	'Photo Albums'=>array('index'),
	$model->name,
);

$this->menu=array(
	array('label'=>'List PhotoAlbum','url'=>array('index')),
	array('label'=>'Create PhotoAlbum','url'=>array('create')),
	array('label'=>'Update PhotoAlbum','url'=>array('update','id'=>$model->id)),
	array('label'=>'Delete PhotoAlbum','url'=>'#','linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage PhotoAlbum','url'=>array('admin')),
);
?>

<h1>View PhotoAlbum #<?php echo $model->id; ?></h1>

<?php $this->widget('bootstrap.widgets.TbDetailView',array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'uid',
		'name',
		'create_time',
		'update_time',
		'cover_uri',
		'mbr_count',
		'views',
		'status',
		'is_hot',
		'privacy',
		'privacy_data',
	),
)); ?>
