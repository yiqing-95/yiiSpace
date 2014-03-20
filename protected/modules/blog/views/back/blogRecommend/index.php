<?php
$this->breadcrumbs=array(
	'Blog Recommends',
);

$this->menu=array(
	array('label'=>'Create BlogRecommend','url'=>array('create'),'linkOptions'=>array('class'=>'ajax_create')),
	array('label'=>'Manage BlogRecommend','url'=>array('admin')),
);
?>

<h1>Blog Recommends</h1>

<?php $this->widget('bootstrap.widgets.TbListView',array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
