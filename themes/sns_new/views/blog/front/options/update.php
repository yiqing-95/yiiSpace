<?php
$this->breadcrumbs=array(
	'Options'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List Options', 'url'=>array('index')),
	array('label'=>'Create Options', 'url'=>array('create')),
	array('label'=>'View Options', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage Options', 'url'=>array('admin')),
);
?>

<h1>Update Options <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>