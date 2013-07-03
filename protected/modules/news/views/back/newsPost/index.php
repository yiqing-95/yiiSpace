<?php
$this->breadcrumbs=array(
	'News Posts',
);

$this->menu=array(
	array('label'=>'Create NewsPost','url'=>array('create'),'linkOptions'=>array('class'=>'ajax_create')),
	array('label'=>'Manage NewsPost','url'=>array('admin')),
);
?>

<h1>News Posts</h1>

<?php $this->widget('bootstrap.widgets.TbListView',array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
