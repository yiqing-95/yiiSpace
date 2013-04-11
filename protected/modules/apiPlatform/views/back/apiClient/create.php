<?php
$this->breadcrumbs=array(
	'Api Clients'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List ApiClient','url'=>array('index')),
	array('label'=>'Manage ApiClient','url'=>array('admin')),
);
?>

<h1>Create ApiClient</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>