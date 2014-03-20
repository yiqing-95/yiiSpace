<?php
/* @var $this RelationshipCategoryController */
/* @var $model RelationshipCategory */

$this->breadcrumbs=array(
	'Relationship Categories'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List RelationshipCategory', 'url'=>array('index')),
	array('label'=>'Manage RelationshipCategory', 'url'=>array('admin')),
);
?>

<h1>Create RelationshipCategory</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>