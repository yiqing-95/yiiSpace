<?php
$this->breadcrumbs=array(
	'Photos'=>array('index'),
	$model->title=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List Photo','url'=>array('index')),
	array('label'=>'Create Photo','url'=>array('create')),
	array('label'=>'View Photo','url'=>array('view','id'=>$model->id)),
	array('label'=>'Manage Photo','url'=>array('admin')),
);
?>

<h1>Update Photo <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form',array('model'=>$model)); ?>