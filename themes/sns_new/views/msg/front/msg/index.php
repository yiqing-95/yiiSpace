<?php
/* @var $this MsgController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Msgs',
);

$this->menu=array(
	array('label'=>'Create Msg', 'url'=>array('create')),
	array('label'=>'Manage Msg', 'url'=>array('admin')),
);
?>

<h1>Msgs</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
    'itemsCssClass'=>'items',
)); ?>
