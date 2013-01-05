<?php
$this->breadcrumbs=array(
	Yii::t('backend', 'Categories'),
);

$this->menu=array(
	array('label'=>Yii::t('backend', 'Create Category'),'url'=>array('create')),
	array('label'=>Yii::t('backend', 'Manage Category'),'url'=>array('admin')),
);
?>

<h1>Categories</h1>

<?php $this->widget('bootstrap.widgets.BootListView',array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
