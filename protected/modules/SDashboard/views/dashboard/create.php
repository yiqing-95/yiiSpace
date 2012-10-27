<?php
$this->breadcrumbs=array(
	'Dashboards'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List Dashboard','url'=>array('index')),
	array('label'=>'Manage Dashboard','url'=>array('admin')),
);
?>

<h1>Create Dashboard</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>