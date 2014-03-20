<?php
$this->breadcrumbs=array(
	'Seos',
);

$this->menu=array(
	array('label'=>'Create Seo','url'=>array('create'),'linkOptions'=>array('class'=>'ajax_create')),
	array('label'=>'Manage Seo','url'=>array('admin')),
);
?>

<h1>Seos</h1>

<?php $this->widget('bootstrap.widgets.TbListView',array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
