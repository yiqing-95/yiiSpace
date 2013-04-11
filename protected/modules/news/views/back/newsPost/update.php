<?php
$this->breadcrumbs=array(
	'News Posts'=>array('index'),
	$model->nid=>array('view','id'=>$model->nid),
	'Update',
);

$this->menu=array(
	array('label'=>'List NewsPost','url'=>array('index')),
	array('label'=>'Create NewsPost','url'=>array('create'),'linkOptions'=>array('class'=>'ajax_create')),
	array('label'=>'View NewsPost','url'=>array('view','id'=>$model->nid)),
	array('label'=>'Manage NewsPost','url'=>array('admin')),
);
?>

<h1>Update NewsPost <?php echo $model->nid; ?></h1>

<?php echo $this->renderPartial('_form',array('model'=>$model)); ?>