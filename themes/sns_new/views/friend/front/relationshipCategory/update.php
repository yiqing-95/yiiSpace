<?php
/* @var $this RelationshipCategoryController */
/* @var $model RelationshipCategory */

$this->breadcrumbs=array(
	'Relationship Categories'=>array('index'),
	$model->name=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List RelationshipCategory', 'url'=>array('index')),
	array('label'=>'Create RelationshipCategory', 'url'=>array('create')),
	array('label'=>'View RelationshipCategory', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage RelationshipCategory', 'url'=>array('admin')),
);
?>

<h1>Update RelationshipCategory <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>