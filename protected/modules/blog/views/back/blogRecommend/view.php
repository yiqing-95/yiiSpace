<?php
$this->breadcrumbs=array(
	'Blog Recommends'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List BlogRecommend','url'=>array('index')),
	array('label'=>'Create BlogRecommend','url'=>array('create')),
	array('label'=>'Update BlogRecommend','url'=>array('update','id'=>$model->id)),
	array('label'=>'Delete BlogRecommend','url'=>'#','linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage BlogRecommend','url'=>array('admin')),
);
?>

<h1>View BlogRecommend #<?php echo $model->id; ?></h1>

<?php $this->widget('bootstrap.widgets.TbDetailView',array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'user_id',
		'object_id',
		'grade',
	),
)); ?>
