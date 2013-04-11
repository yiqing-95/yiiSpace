<?php
$this->breadcrumbs=array(
	'Api Clients',
);

$this->menu=array(
	array('label'=>'Create ApiClient','url'=>array('create')),
	array('label'=>'Manage ApiClient','url'=>array('admin')),
);
?>

<h1>Api Clients</h1>

<?php $this->widget('bootstrap.widgets.TbListView',array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
