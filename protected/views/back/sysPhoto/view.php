<?php
$this->breadcrumbs=array(
	'Sys Photos'=>array('index'),
	$model->title,
);

$this->menu=array(
	array('label'=>'List SysPhoto','url'=>array('index')),
	array('label'=>'Create SysPhoto','url'=>array('sysAlbum/upload','albumId'=>$album->primaryKey)),

	array('label'=>'Update SysPhoto','url'=>array('update','id'=>$model->id)),
	array('label'=>'Delete SysPhoto','url'=>'#','linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage SysPhoto','url'=>array('admin')),
);
?>

<h1>View SysPhoto #<?php echo $model->id; ?></h1>

<?php $this->widget('bootstrap.widgets.TbDetailView',array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'categories',
		'uid',
		'ext',
		'size',
		'title',
		'uri',
		'desc',
		'tags',
		'create_time',
		'views',
		'rate',
		'rate_count',
		'cmt_count',
		'featured',
		'status',
		'hash',
	),
)); ?>
