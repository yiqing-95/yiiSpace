<?php
$this->breadcrumbs=array(
	'Relationship Types'=>array('index'),
	$model->name,
);

$this->menu=array(
	array('label'=>'List RelationshipType','url'=>array('index')),
	array('label'=>'Create RelationshipType','url'=>array('create')),
	array('label'=>'Update RelationshipType','url'=>array('update','id'=>$model->id)),
	array('label'=>'Delete RelationshipType','url'=>'#','linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
    array('label'=>'Manage RelationshipType(advanced mode) ','url'=>array('adminAdv')),
);
?>

<h1>View RelationshipType #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView',array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'name',
		'plural_name',
		'active',
		'mutual',
	),
)); ?>
