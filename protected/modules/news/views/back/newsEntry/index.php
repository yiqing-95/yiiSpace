<?php
$this->breadcrumbs=array(
	'News Entries',
);

$this->menu=array(
	array('label'=>'Create NewsEntry','url'=>array('create'),'linkOptions'=>array('class'=>'ajax_create')),
	array('label'=>'Manage NewsEntry','url'=>array('admin')),
);
?>

<h1>News Entries</h1>

<?php $this->widget('bootstrap.widgets.TbListView',array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
