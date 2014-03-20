<?php
$this->breadcrumbs=array(
	'Notice Categories',
);

$this->menu=array(
	array('label'=>'Create NoticeCategory','url'=>array('create'),'linkOptions'=>array('class'=>'ajax_create')),
	array('label'=>'Manage NoticeCategory','url'=>array('admin')),
);
?>

<h1>Notice Categories</h1>

<?php $this->widget('bootstrap.widgets.TbListView',array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
