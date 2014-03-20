<?php
$this->breadcrumbs=array(
	'Group Categories',
);

$this->menu=array(
	array('label'=>'Create GroupCategory','url'=>array('create'),'linkOptions'=>array('class'=>'ajax_create')),
	array('label'=>'Manage GroupCategory','url'=>array('admin')),
);
?>

<h1>Group Categories</h1>

<?php $this->widget('bootstrap.widgets.TbListView',array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
