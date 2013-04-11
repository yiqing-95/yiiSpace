<?php
$this->breadcrumbs=array(
	'News Categories'=>array('index'),
	$model->name=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List NewsCategory','url'=>array('index')),
	array('label'=>'Create NewsCategory','url'=>array('create'),'linkOptions'=>array('class'=>'ajax_create')),
	array('label'=>'View NewsCategory','url'=>array('view','id'=>$model->id)),
	array('label'=>'Manage NewsCategory','url'=>array('admin')),
);
?>

<h1>Update NewsCategory <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form',array('model'=>$model)); ?>