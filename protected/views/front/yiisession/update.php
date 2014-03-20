<?php
$this->breadcrumbs=array(
	'Yiisessions'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List Yiisession','url'=>array('index')),
	array('label'=>'Create Yiisession','url'=>array('create'),'linkOptions'=>array('class'=>'ajax_create')),
	array('label'=>'View Yiisession','url'=>array('view','id'=>$model->id)),
	array('label'=>'Manage Yiisession','url'=>array('admin')),
);
?>

<h1>Update Yiisession <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form',array('model'=>$model)); ?>