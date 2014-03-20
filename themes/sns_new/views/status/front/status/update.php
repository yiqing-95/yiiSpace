<?php
$this->breadcrumbs=array(
	'Statuses'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List Status','url'=>array('index')),
	array('label'=>'Create Status','url'=>array('create')),
	array('label'=>'View Status','url'=>array('view','id'=>$model->id)),
    array('label'=>'Manage Status(advance mode) ','url'=>array('adminAdv')),
);
?>

<h1>Update Status <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form',array('model'=>$model)); ?>