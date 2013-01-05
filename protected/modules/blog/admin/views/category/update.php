<?php
$this->breadcrumbs=array(
	Yii::t('backend', 'Categories')=>array('index'),
	$model->name=>array('view','id'=>$model->id),
	Yii::t('backend', 'Update'),
);

$this->menu=array(
	array('label'=>'List Category','url'=>array('index')),
	array('label'=>Yii::t('backend', 'Create Category'),'url'=>array('create')),
	array('label'=>'View Category','url'=>array('view','id'=>$model->id)),
	array('label'=>Yii::t('backend', 'Manage Category'),'url'=>array('admin')),
);
?>

<h1><?php echo Yii::t('backend', 'Update Category') ?> <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form',array('model'=>$model)); ?>