<?php
$this->breadcrumbs=array(
	'Group Categories'=>array('index'),
	$model->title=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List GroupCategory','url'=>array('index')),
	array('label'=>'Create GroupCategory','url'=>array('create'),'linkOptions'=>array('class'=>'ajax_create')),
	array('label'=>'View GroupCategory','url'=>array('view','id'=>$model->id)),
	array('label'=>'Manage GroupCategory','url'=>array('admin')),
);
?>

<h1>Update GroupCategory <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form',array('model'=>$model)); ?>