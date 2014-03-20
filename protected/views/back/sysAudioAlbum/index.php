<?php
$this->breadcrumbs=array(
	'Sys Audio Albums',
);

$this->menu=array(
	array('label'=>'Create SysAudioAlbum','url'=>array('create'),'linkOptions'=>array('class'=>'ajax_create')),
	array('label'=>'Manage SysAudioAlbum','url'=>array('admin')),
);
?>

<h1>Sys Audio Albums</h1>

<?php $this->widget('bootstrap.widgets.TbListView',array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
