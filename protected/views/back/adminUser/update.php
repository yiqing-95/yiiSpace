<?php
$this->breadcrumbs=array(
	'Admin Users'=>array('index'),
	$model->name=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List AdminUser','url'=>array('index')),
	array('label'=>'Create AdminUser','url'=>array('create'),'linkOptions'=>array('class'=>'ajax_create')),
	array('label'=>'View AdminUser','url'=>array('view','id'=>$model->id)),
	array('label'=>'Manage AdminUser','url'=>array('admin')),
);
?>

<h1>Update AdminUser <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form',array('model'=>$model)); ?>