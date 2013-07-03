<?php
$this->breadcrumbs=array(
	'Sys Menus'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List SysMenu','url'=>array('index')),
	array('label'=>'Manage SysMenu','url'=>array('admin')),
);
?>

<h1>Create SysMenu</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>