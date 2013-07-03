<?php
$this->breadcrumbs=array(
	'Links',
);

$this->menu=array(
	array('label'=>'Create Link','url'=>array('create')),
	array('label'=>'Manage Link','url'=>array('admin')),
);
?>

<h1>Links</h1>

<?php $this->widget('bootstrap.widgets.BootListView',array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
