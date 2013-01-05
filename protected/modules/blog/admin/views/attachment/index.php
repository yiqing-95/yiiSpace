<?php
$this->breadcrumbs=array(
	Yii::t('backend', 'Attachments'),
);

$this->menu=array(
	array('label'=>Yii::t('backend', 'Create Attachment'),'url'=>array('create')),
	array('label'=>Yii::t('backend', 'Manage Attachment'),'url'=>array('admin')),
);
?>

<h1>Attachments</h1>

<?php $this->widget('bootstrap.widgets.BootListView',array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
