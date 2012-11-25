<?php
$this->breadcrumbs=array(
	'Photos'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List Photo','url'=>array('index')),
	array('label'=>'Manage Photo','url'=>array('admin')),
);
?>

<h1>Create Photo</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>