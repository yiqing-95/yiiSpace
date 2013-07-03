<?php
$this->breadcrumbs=array(
	'News Entries'=>array('index'),
	$model->title,
);

$this->menu=array(
	array('label'=>'List NewsEntry','url'=>array('index')),
	array('label'=>'Create NewsEntry','url'=>array('create')),
	array('label'=>'Update NewsEntry','url'=>array('update','id'=>$model->id)),
	array('label'=>'Delete NewsEntry','url'=>'#','linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage NewsEntry','url'=>array('admin')),
);
?>

<h1>View NewsEntry #<?php echo $model->id; ?></h1>

<?php $this->widget('bootstrap.widgets.TbDetailView',array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'creator',
		'cate_id',
		'title',
		'order',
		'deleted',
		'create_time',
	),
)); ?>

<?php $this->widget('bootstrap.widgets.TbDetailView',array(
    'data'=>$model->post,
    'attributes'=>array(
        'nid',
        'content',
        'keywords',
        'meta_title',
        'meta_description',
        'meta_keywords',
    ),
)); ?>

