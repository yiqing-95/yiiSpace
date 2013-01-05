<?php
$this->breadcrumbs=array(
	'Posts'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List Post','icon'=>'book'),
	array('label'=>'Manage Post','url'=>array('admin')),
	array('label'=>'Create Post', 'url'=>array('create'), 'active'=>true),
);
?>
<h1>Create Post</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>