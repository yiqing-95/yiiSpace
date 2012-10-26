<?php
$this->breadcrumbs=array(
	'Msgs'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List Msg','url'=>array('index')),
	array('label'=>'Create Msg','url'=>array('create')),
	array('label'=>'View Msg','url'=>array('view','id'=>$model->id)),
    array('label'=>'Manage Msg(advance mode) ','url'=>array('adminAdv')),
);
?>

<h1>Update Msg <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form',array('model'=>$model)); ?>