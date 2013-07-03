<?php
$this->breadcrumbs=array(
	'Inbox',
);

$this->menu=array(
	array('label'=>'Create Msg','url'=>array('create')),
    array('label'=>'Manage Msg(advance mode) ','url'=>array('adminAdv')),
);
?>

<h1>Msgs</h1>

<?php $this->widget('zii.widgets.CListView',array(
     'id'=>'msg-list',
	'dataProvider'=>$dataProvider,
	'itemView'=>'_inboxView',
)); ?>
