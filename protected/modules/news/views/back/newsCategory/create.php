<?php
$this->breadcrumbs=array(
	'News Categories'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List NewsCategory','url'=>array('index')),
	array('label'=>'Manage NewsCategory','url'=>array('admin')),
);
?>

<h1>Create NewsCategory</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>