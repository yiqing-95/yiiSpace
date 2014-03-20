<?php
$this->breadcrumbs=array(
	'Sys Photos'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List SysPhoto','url'=>array('index')),
	array('label'=>'Manage SysPhoto','url'=>array('admin')),
);
?>

<h1>Create SysPhoto</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>