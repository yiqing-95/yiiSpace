<?php
/* @var $this GroupTopicPostController */
/* @var $model GroupTopicPost */

$this->breadcrumbs=array(
	'Group Topic Posts'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List GroupTopicPost', 'url'=>array('index')),
	array('label'=>'Create GroupTopicPost', 'url'=>array('create')),
	array('label'=>'Update GroupTopicPost', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete GroupTopicPost', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage GroupTopicPost', 'url'=>array('admin')),
);
?>

<h1>View GroupTopicPost #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'group_id',
		'topic_id',
		'user_id',
		'content',
		'ip',
		'create_time',
		'status',
		'is_del',
	),
)); ?>
