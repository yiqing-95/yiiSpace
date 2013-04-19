<?php
$this->breadcrumbs=array(
	'Sys Menus'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List SysMenu','url'=>array('index')),
	array('label'=>'Create SysMenu','url'=>array('create'),'linkOptions'=>array('class'=>'ajax_create')),
	array('label'=>'View SysMenu','url'=>array('view','id'=>$model->id)),
	array('label'=>'Manage SysMenu','url'=>array('admin')),
);
?>

<h1>Update SysMenu <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form',array('model'=>$model)); ?>