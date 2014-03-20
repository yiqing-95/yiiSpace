<?php
$this->breadcrumbs=array(
	'Sys Friend Links'=>array('index'),
	$model->name=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List SysFriendLink','url'=>array('index')),
	array('label'=>'Create SysFriendLink','url'=>array('create'),'linkOptions'=>array('class'=>'ajax_create')),
	array('label'=>'View SysFriendLink','url'=>array('view','id'=>$model->id)),
	array('label'=>'Manage SysFriendLink','url'=>array('admin')),
);
?>

<h1>Update SysFriendLink <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form',array('model'=>$model)); ?>