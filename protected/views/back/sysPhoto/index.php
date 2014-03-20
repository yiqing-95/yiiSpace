<?php
$this->breadcrumbs=array(
	'Sys Photos',
);

$this->menu=array(
	array('label'=>'Create SysPhoto','url'=>array('create'),'linkOptions'=>array('class'=>'ajax_create')),
	array('label'=>'Manage SysPhoto','url'=>array('admin')),
);
?>

<h1>Sys Photos</h1>

<?php $this->widget('bootstrap.widgets.TbListView',array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
