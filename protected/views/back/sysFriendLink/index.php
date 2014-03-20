<?php
$this->breadcrumbs=array(
	'Sys Friend Links',
);

$this->menu=array(
	array('label'=>'Create SysFriendLink','url'=>array('create'),'linkOptions'=>array('class'=>'ajax_create')),
	array('label'=>'Manage SysFriendLink','url'=>array('admin')),
);
?>

<h1>Sys Friend Links</h1>

<?php $this->widget('bootstrap.widgets.TbListView',array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
