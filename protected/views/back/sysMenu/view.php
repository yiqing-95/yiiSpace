<?php
$this->breadcrumbs=array(
	'Sys Menus'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List SysMenu','url'=>array('index')),
	array('label'=>'Create SysMenu','url'=>array('create')),
	array('label'=>'Update SysMenu','url'=>array('update','id'=>$model->id)),
	array('label'=>'Delete SysMenu','url'=>'#','linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage SysMenu','url'=>array('admin')),
);
?>

<h1>View SysMenu #<?php echo $model->id; ?></h1>

<?php $this->widget('bootstrap.widgets.TbDetailView',array(
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
		'group_code',
		'label_en',
		'link_to',
	),
)); ?>
