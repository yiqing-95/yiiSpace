<?php
$this->breadcrumbs=array(
	'Group Categories'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List GroupCategory','url'=>array('index')),
	array('label'=>'Manage GroupCategory','url'=>array('admin')),
);
?>

<h1>Create GroupCategory</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>