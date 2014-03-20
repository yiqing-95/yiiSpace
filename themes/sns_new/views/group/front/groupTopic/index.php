<?php
/* @var $this GroupTopicController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Group Topics',
);

$this->menu=array(
	array('label'=>'Create GroupTopic', 'url'=>array('create')),
	array('label'=>'Manage GroupTopic', 'url'=>array('admin')),
);
?>

<h1>Group Topics</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
