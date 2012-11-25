<?php
$this->breadcrumbs=array(
	'Photos',
);

$this->menu=array(
	array('label'=>'Create Photo','url'=>array('create')),
	array('label'=>'Manage Photo','url'=>array('admin')),
);
?>

<h1>Photos</h1>

<?php $this->widget('bootstrap.widgets.TbListView',array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
