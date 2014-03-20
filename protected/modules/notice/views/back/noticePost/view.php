<?php
$this->breadcrumbs=array(
	'Notice Posts'=>array('index'),
	$model->title,
);

$this->menu=array(
	array('label'=>'List NoticePost','url'=>array('index')),
	array('label'=>'Create NoticePost','url'=>array('create')),
	array('label'=>'Update NoticePost','url'=>array('update','id'=>$model->id)),
	array('label'=>'Delete NoticePost','url'=>'#','linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage NoticePost','url'=>array('admin')),
);
?>

<h1>View NoticePost #<?php echo $model->id; ?></h1>

<?php $this->widget('bootstrap.widgets.TbDetailView',array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'cate_id',
		'title',
		'content',
		'create_time',
		'creator_id',
	),
)); ?>
