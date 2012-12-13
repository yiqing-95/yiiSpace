<?php
$this->breadcrumbs=array(
	'Yiisessions'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List Yiisession','url'=>array('index')),
	array('label'=>'Manage Yiisession','url'=>array('admin')),
);
?>

<h1>Create Yiisession</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>