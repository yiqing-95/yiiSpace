<?php
$this->breadcrumbs=array(
	'Notice Posts',
);

$this->menu=array(
	array('label'=>'Create NoticePost','url'=>array('create'),'linkOptions'=>array('class'=>'ajax_create')),
	array('label'=>'Manage NoticePost','url'=>array('admin')),
);
?>

<h1>Notice Posts</h1>

<?php $this->widget('bootstrap.widgets.TbListView',array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
