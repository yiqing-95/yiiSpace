<?php
$this->breadcrumbs=array(
	'Yiisessions',
);

$this->menu=array(
	array('label'=>'Create Yiisession','url'=>array('create'),'linkOptions'=>array('class'=>'ajax_create')),
	array('label'=>'Manage Yiisession','url'=>array('admin')),
);
?>

<h1>Yiisessions</h1>

<?php $this->widget('bootstrap.widgets.TbListView',array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
