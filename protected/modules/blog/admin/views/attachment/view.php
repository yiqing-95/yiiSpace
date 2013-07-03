<?php
$this->breadcrumbs=array(
	Yii::t('backend', 'Attachments')=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List Attachment','url'=>array('index')),
	array('label'=>Yii::t('backend', 'Create Attachment'),'url'=>array('create')),
	array('label'=>Yii::t('backend', 'Update Attachment'),'url'=>array('update','id'=>$model->id)),
	array('label'=>'Delete Attachment','url'=>'#','linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>Yii::t('backend', 'Manage Attachment'),'url'=>array('admin')),
);
?>

<h1>View Attachment #<?php echo $model->id; ?></h1>

<?php $this->widget('bootstrap.widgets.BootDetailView',array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'post_id',
		'filename',
		'filesize',
		'filepath',
		'created',
		'updated',
	),
)); ?>
