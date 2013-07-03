<?php
$this->breadcrumbs=array(
	Yii::t('backend', 'Categories')=>array('index'),
	Yii::t('backend', 'Create'),
);

$this->menu=array(
	array('label'=>'List Category','url'=>array('index')),
	array('label'=>Yii::t('backend', 'Manage Category'),'url'=>array('admin')),
);
?>

<h1>Create Category</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>