<?php
$this->breadcrumbs=array(
	'Admin Roles',
);

$this->menu=array(
	array('label'=>'Create AdminRole','url'=>array('create'),'linkOptions'=>array('class'=>'ajax_create')),
	array('label'=>'Manage AdminRole','url'=>array('admin')),
);
?>

<h1>Admin Roles</h1>

<?php $this->widget('bootstrap.widgets.TbListView',array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
