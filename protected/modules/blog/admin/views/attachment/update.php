<?php
$this->breadcrumbs=array(
	Yii::t('backend', 'Attachments')=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	Yii::t('backend', 'Update'),
);

$this->menu=array(
	array('label'=>'List Attachment','url'=>array('index')),
	array('label'=>'View Attachment','url'=>array('view','id'=>$model->id)),
	array('label'=>Yii::t('backend', 'Create Attachment'),'url'=>array('create')),
	array('label'=>Yii::t('backend', 'Manage Attachment'),'url'=>array('admin')),
);
?>

<h1>Update Attachment <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form',array('model'=>$model)); ?>