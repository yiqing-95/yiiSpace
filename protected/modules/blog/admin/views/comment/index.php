<?php
$this->breadcrumbs=array(
	'Comments',
);

$this->menu=array(
	array('label'=>'Manage Comment','url'=>array('admin')),
);
?>

<h1>Comments</h1>

<?php $this->widget('bootstrap.widgets.BootListView',array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
