<?php
$this->breadcrumbs=array(
	'Blog Sys Categories',
);

$this->menu=array(
	array('label'=>'Create BlogSysCategory','url'=>array('create'),'linkOptions'=>array('class'=>'ajax_create')),
	array('label'=>'Manage BlogSysCategory','url'=>array('admin')),
);
?>

<h1>Blog Sys Categories</h1>

<?php $this->widget('bootstrap.widgets.TbListView',array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
