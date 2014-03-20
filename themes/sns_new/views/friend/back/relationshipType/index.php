<?php
$this->breadcrumbs=array(
	'Relationship Types',
);

$this->menu=array(
	array('label'=>'Create RelationshipType','url'=>array('create')),
	array('label'=>'Manage RelationshipType','url'=>array('admin')),
);
?>

<h1>Relationship Types</h1>

<?php $this->widget('bootstrap.widgets.TbListView',array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
