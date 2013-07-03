<?php
$this->breadcrumbs=array(
	'Admin Menus'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List AdminMenu','url'=>array('index')),
	array('label'=>'Create AdminMenu','url'=>array('create')),
	array('label'=>'View AdminMenu','url'=>array('view','id'=>$model->id)),
    array('label'=>'Manage AdminMenu(advance mode) ','url'=>array('adminAdv')),
);
?>

<h1>Update AdminMenu <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form',array('model'=>$model)); ?>