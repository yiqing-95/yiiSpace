<?php
$this->breadcrumbs=array(
	'Seos'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List Seo','url'=>array('index')),
	array('label'=>'Manage Seo','url'=>array('admin')),
);
?>

<h1>Create Seo</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>