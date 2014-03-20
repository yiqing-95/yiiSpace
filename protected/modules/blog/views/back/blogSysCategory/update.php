<?php
$this->breadcrumbs=array(
	'Blog Sys Categories'=>array('index'),
	$model->name=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List BlogSysCategory','url'=>array('index')),
	array('label'=>'Create BlogSysCategory','url'=>array('create'),'linkOptions'=>array('class'=>'ajax_create')),
	array('label'=>'View BlogSysCategory','url'=>array('view','id'=>$model->id)),
	array('label'=>'Manage BlogSysCategory','url'=>array('admin')),
);
?>

<h1>Update BlogSysCategory <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form',array('model'=>$model)); ?>