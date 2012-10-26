<?php
$this->breadcrumbs=array(
	'Msgs'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List Msg','url'=>array('index')),
	array('label'=>'Create Msg','url'=>array('create')),
	array('label'=>'Update Msg','url'=>array('update','id'=>$model->id)),
	array('label'=>'Delete Msg','url'=>'#','linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
    array('label'=>'Manage Msg(advanced mode) ','url'=>array('adminAdv')),
);
?>

<h1>View Msg #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView',array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'sender',
		'recipient',
		'sent',
		'read',
		'subject',
		'message',
	),
)); ?>
