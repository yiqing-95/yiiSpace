<?php
$this->breadcrumbs=array(
	'Sys Modules'=>array('index'),
	$model->title=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List SysModule','url'=>array('index')),
	array('label'=>'Create SysModule','url'=>array('create')),
	array('label'=>'View SysModule','url'=>array('view','id'=>$model->id)),
    array('label'=>'Manage SysModule(advance mode) ','url'=>array('adminAdv')),
);
?>

<h1>Update SysModule <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form',array('model'=>$model)); ?>