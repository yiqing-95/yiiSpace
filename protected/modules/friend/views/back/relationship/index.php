<?php
$this->breadcrumbs=array(
	'Relationships',
);

$this->menu=array(
	array('label'=>'Create Relationship','url'=>array('create')),
	array('label'=>'Manage Relationship','url'=>array('admin')),
);
?>

<h1>Relationships</h1>

<?php $this->widget('bootstrap.widgets.TbListView',array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
