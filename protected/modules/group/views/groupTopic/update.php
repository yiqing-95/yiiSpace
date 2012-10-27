<?php
$this->breadcrumbs=array(
	'Group Topics'=>array('index'),
	$model->name=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List GroupTopic','url'=>array('index')),
	array('label'=>'Create GroupTopic','url'=>array('create')),
	array('label'=>'View GroupTopic','url'=>array('view','id'=>$model->id)),
    array('label'=>'Manage GroupTopic(advance mode) ','url'=>array('adminAdv')),
);
?>

<h1>Update GroupTopic <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form',array('model'=>$model)); ?>