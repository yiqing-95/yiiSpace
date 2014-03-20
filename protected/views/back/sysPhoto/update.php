<?php
$this->breadcrumbs=array(
	'Sys Photos'=>array('index'),
	$model->title=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List SysPhoto','url'=>array('index')),
	array('label'=>'Create SysPhoto','url'=>array('create'),'linkOptions'=>array('class'=>'ajax_create')),
	array('label'=>'View SysPhoto','url'=>array('view','id'=>$model->id)),
	array('label'=>'Manage SysPhoto','url'=>array('admin')),
);
?>

<h1>Update SysPhoto <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form',array('model'=>$model)); ?>