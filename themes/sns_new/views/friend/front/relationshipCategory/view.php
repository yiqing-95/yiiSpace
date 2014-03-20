<?php
/* @var $this RelationshipCategoryController */
/* @var $model RelationshipCategory */

$this->breadcrumbs=array(
	'Relationship Categories'=>array('index'),
	$model->name,
);

$this->menu=array(
	array('label'=>'List RelationshipCategory', 'url'=>array('index')),
	array('label'=>'Create RelationshipCategory', 'url'=>array('create')),
	array('label'=>'Update RelationshipCategory', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete RelationshipCategory', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage RelationshipCategory', 'url'=>array('admin')),
);
?>

<h1>View RelationshipCategory #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'user_id',
		'name',
		'display_order',
		'mbr_count',
	),
)); ?>
