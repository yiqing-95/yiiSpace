<?php
$this->breadcrumbs=array(
	Yii::t('backend', 'Users'),
);

$this->menu=array(
	array('label'=>Yii::t('backend', 'Create User'),'url'=>array('create')),
	array('label'=>Yii::t('backend', 'Manage Users'),'url'=>array('admin')),
);
?>

<h1>Users</h1>

<?php $this->widget('bootstrap.widgets.BootListView',array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
