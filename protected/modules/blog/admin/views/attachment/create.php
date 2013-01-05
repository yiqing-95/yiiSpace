<?php
$this->breadcrumbs=array(
	Yii::t('backend', 'Attachments')=>array('index'),
	Yii::t('backend', 'Create'),
);

$this->menu=array(
	array('label'=>'List Attachment','url'=>array('index')),
	array('label'=>Yii::t('backend', 'Manage Attachment'),'url'=>array('admin')),
);
?>

<h1><?php echo Yii::t('backend', 'Create Attachment')?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>