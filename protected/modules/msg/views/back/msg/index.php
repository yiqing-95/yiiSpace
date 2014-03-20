<?php
$this->breadcrumbs=array(
	'Msgs',
);

$this->menu=array(
	array('label'=>'Create Msg','url'=>array('create'),'linkOptions'=>array('class'=>'ajax_create')),
	array('label'=>'Manage Msg','url'=>array('admin')),
);
?>

<h1>Msgs</h1>

<?php $this->widget('bootstrap.widgets.TbListView',array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
