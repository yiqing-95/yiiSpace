<?php
$this->breadcrumbs=array(
	'Admin Menus'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List AdminMenu','url'=>array('index')),
	array('label'=>'Create AdminMenu','url'=>array('create')),
	array('label'=>'Update AdminMenu','url'=>array('update','id'=>$model->id)),
	array('label'=>'Delete AdminMenu','url'=>'#','linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
    array('label'=>'Manage AdminMenu(advanced mode) ','url'=>array('adminAdv')),
);
?>

<h1>View AdminMenu #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView',array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'root',
		'lft',
		'rgt',
		'level',
		'label',
		'url',
		'params',
		'ajaxoptions',
		'htmloptions',
		'is_visible',
		'uid',
		'group_code',
	),
)); ?>
