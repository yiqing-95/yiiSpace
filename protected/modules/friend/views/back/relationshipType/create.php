<?php
$this->breadcrumbs=array(
	'Relationship Types'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List RelationshipType','url'=>array('index')),
    array('label'=>'Manage RelationshipType(advance mode) ','url'=>array('adminAdv')),
);
?>

<h1>Create RelationshipType</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>