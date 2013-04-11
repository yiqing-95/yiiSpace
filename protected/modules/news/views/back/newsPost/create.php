<?php
$this->breadcrumbs=array(
	'News Posts'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List NewsPost','url'=>array('index')),
	array('label'=>'Manage NewsPost','url'=>array('admin')),
);
?>

<h1>Create NewsPost</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>