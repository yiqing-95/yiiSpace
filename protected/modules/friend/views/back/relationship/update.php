<?php
$this->breadcrumbs=array(
	'Relationships'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List Relationship','url'=>array('index')),
	array('label'=>'Create Relationship','url'=>array('create')),
	array('label'=>'View Relationship','url'=>array('view','id'=>$model->id)),
    array('label'=>'Manage Relationship(advance mode) ','url'=>array('adminAdv')),
);
?>

<h1>Update Relationship <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form',array('model'=>$model)); ?>