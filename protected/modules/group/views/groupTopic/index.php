<?php
$this->breadcrumbs=array(
	'Group Topics',
);

$this->menu=array(
	array('label'=>'Create GroupTopic','url'=>array('create')),
    array('label'=>'Manage GroupTopic(advance mode) ','url'=>array('adminAdv')),
);
?>

<h1>Group Topics</h1>

<?php $this->widget('zii.widgets.CListView',array(
     'id'=>'group-topic-list',
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
