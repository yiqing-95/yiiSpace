<?php
$this->breadcrumbs=array(
	'News Posts'=>array('index'),
	$model->nid,
);

$this->menu=array(
	array('label'=>'List NewsPost','url'=>array('index')),
	array('label'=>'Create NewsPost','url'=>array('create')),
	array('label'=>'Update NewsPost','url'=>array('update','id'=>$model->nid)),
	array('label'=>'Delete NewsPost','url'=>'#','linkOptions'=>array('submit'=>array('delete','id'=>$model->nid),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage NewsPost','url'=>array('admin')),
);
?>

<h1>View NewsPost #<?php echo $model->nid; ?></h1>

<?php $this->widget('bootstrap.widgets.TbDetailView',array(
	'data'=>$model,
	'attributes'=>array(
		'nid',
		'content',
		'keywords',
		'meta_title',
		'meta_description',
		'meta_keywords',
	),
)); ?>
