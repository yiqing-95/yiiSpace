<?php
/* @var $this RelationshipCategoryController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Relationship Categories',
);

$this->menu=array(
	array('label'=>'Create RelationshipCategory', 'url'=>array('create')),
	array('label'=>'Manage RelationshipCategory', 'url'=>array('admin')),
);
?>

<h1>Relationship Categories</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
