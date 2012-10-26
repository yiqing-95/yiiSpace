<?php
$this->breadcrumbs=array(
	'Relationships'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List Relationship','url'=>array('index')),
    array('label'=>'Manage Relationship(advance mode) ','url'=>array('adminAdv')),
);
?>

<h1>Create Relationship</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>