<?php
$this->breadcrumbs=array(
	'Comments'=>array('index'),
	$model->cmt_id,
);

$this->menu=array(
	array('label'=>'List Comment','url'=>array('index')),
	array('label'=>'Create Comment','url'=>array('create')),
	array('label'=>'Update Comment','url'=>array('update','id'=>$model->cmt_id)),
	array('label'=>'Delete Comment','url'=>'#','linkOptions'=>array('submit'=>array('delete','id'=>$model->cmt_id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Comment','url'=>array('admin')),
);
?>

<h1>View Comment #<?php echo $model->cmt_id; ?></h1>

<?php $this->widget('bootstrap.widgets.TbDetailView',array(
	'data'=>$model,
	'attributes'=>array(
		'object_name',
		'object_id',
		'cmt_id',
		'cmt_parent_id',
		'author_id',
		'user_name',
		'user_email',
		'cmt_text',
		'create_time',
		'update_time',
		'status',
		'replies',
		'mood',
	),
)); ?>
