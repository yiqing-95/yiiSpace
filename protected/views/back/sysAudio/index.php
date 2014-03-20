<?php
$this->breadcrumbs=array(
	'Sys Audios',
);

$this->menu=array(
	array('label'=>'Create SysAudio','url'=>array('create'),'linkOptions'=>array('class'=>'ajax_create')),
	array('label'=>'Manage SysAudio','url'=>array('admin')),
);
?>

<h1>Sys Audios</h1>

<?php $this->widget('bootstrap.widgets.TbListView',array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
