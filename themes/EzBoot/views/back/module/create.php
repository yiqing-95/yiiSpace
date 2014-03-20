<?php
$this->breadcrumbs=array(
	'Sys Modules'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List SysModule','url'=>array('index')),
    array('label'=>'Manage SysModule(advance mode) ','url'=>array('adminAdv')),
);
?>

<h1>Create SysModule</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>