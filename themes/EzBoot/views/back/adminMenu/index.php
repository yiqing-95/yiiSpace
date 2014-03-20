<?php
$this->breadcrumbs=array(
	'Admin Menus',
);

$this->menu=array(
	array('label'=>'Create AdminMenu','url'=>array('create')),
	array('label'=>'Manage AdminMenu','url'=>array('admin')),
);
?>

<h1>Admin Menus</h1>

<?php $this->widget('bootstrap.widgets.TbListView',array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
