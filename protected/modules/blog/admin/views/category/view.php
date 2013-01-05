<?php
$this->breadcrumbs=array(
	Yii::t('backend', 'Categories')=>array('index'),
	$model->name,
);

$this->menu=array(
	array('label'=>'List Category','url'=>array('index')),
	array('label'=>Yii::t('backend', 'Create Category'),'url'=>array('create')),
	array('label'=>'Update Category','url'=>array('update','id'=>$model->id)),
	array('label'=>'Delete Category','url'=>'#','linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>Yii::t('backend', 'Manage Category'),'url'=>array('admin')),
);
?>

<h1>View Category #<?php echo $model->id; ?></h1>

<?php $this->widget('bootstrap.widgets.BootDetailView',array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'pid',
		'name',
		'alias',
		'position',
	),
)); ?>
