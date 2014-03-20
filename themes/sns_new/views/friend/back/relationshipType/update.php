<?php
$this->breadcrumbs=array(
	'Relationship Types'=>array('index'),
	$model->name=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List RelationshipType','url'=>array('index')),
	array('label'=>'Create RelationshipType','url'=>array('create')),
	array('label'=>'View RelationshipType','url'=>array('view','id'=>$model->id)),
	array('label'=>'Manage RelationshipType','url'=>array('admin')),
);
?>

<h1>Update RelationshipType <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form',array('model'=>$model)); ?>