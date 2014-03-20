<?php
$this->breadcrumbs=array(
	'Sys Albums',
);

$this->menu=array(
	array('label'=>'Create SysAlbum','url'=>array('create'),'linkOptions'=>array('class'=>'ajax_create')),
	array('label'=>'Manage SysAlbum','url'=>array('admin')),
);
?>

<h1>Sys Albums</h1>

<?php $this->widget('bootstrap.widgets.TbListView',array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
