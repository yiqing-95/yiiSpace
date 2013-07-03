<?php
$this->breadcrumbs=array(
	'Options',
);

$this->menu=array(
	array('label'=>'Create Options', 'url'=>array('create')),
	array('label'=>'Manage Options', 'url'=>array('admin')),
);
?>

<h1>Options</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
