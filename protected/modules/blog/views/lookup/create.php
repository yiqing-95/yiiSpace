<?php
$this->breadcrumbs=array(
	'Lookups'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List Lookup', 'url'=>array('index')),
	array('label'=>'Manage Lookup', 'url'=>array('admin')),
);
?>

<h1>Create Lookup</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>