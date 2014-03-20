<?php
$this->breadcrumbs=array(
	'Sys Friend Links'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List SysFriendLink','url'=>array('index')),
	array('label'=>'Manage SysFriendLink','url'=>array('admin')),
);
?>

<h1>Create SysFriendLink</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>