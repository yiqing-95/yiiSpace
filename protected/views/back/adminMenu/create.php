<?php
$this->breadcrumbs=array(
	'Admin Menus'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List AdminMenu','url'=>array('index')),
	array('label'=>'Manage AdminMenu','url'=>array('admin')),
);
?>

<h1>Create AdminMenu</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>