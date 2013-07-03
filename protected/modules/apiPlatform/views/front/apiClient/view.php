<?php
$this->breadcrumbs=array(
	'Api Clients'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List ApiClient','url'=>array('index')),
	array('label'=>'Create ApiClient','url'=>array('create')),
	array('label'=>'Update ApiClient','url'=>array('update','id'=>$model->id)),
	array('label'=>'Delete ApiClient','url'=>'#','linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage ApiClient','url'=>array('admin')),
);
?>

<h1>View ApiClient #<?php echo $model->id; ?></h1>

<?php $this->widget('bootstrap.widgets.TbDetailView',array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'app_id',
		'app_key',
		'app_name',
		'app_domain',
		'app_description',
		'status',
		'create_time',
		'update_time',
		'modifier_id',
	),
)); ?>
