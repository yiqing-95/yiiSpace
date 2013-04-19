<?php
$this->breadcrumbs=array(
	'Sys Menus',
);

$this->menu=array(
	array('label'=>'Create SysMenu','url'=>array('create'),'linkOptions'=>array('class'=>'ajax_create')),
	array('label'=>'Manage SysMenu','url'=>array('admin')),
);
?>

<h1>Sys Menus</h1>

<?php $this->widget('bootstrap.widgets.TbListView',array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
