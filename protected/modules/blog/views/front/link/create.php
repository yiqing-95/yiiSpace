<?php
$this->breadcrumbs=array(
	'Links'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List Link', 'url'=>array('index')),
	array('label'=>'Manage Link', 'url'=>array('admin')),
);
?>

<h1>Create Link</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>