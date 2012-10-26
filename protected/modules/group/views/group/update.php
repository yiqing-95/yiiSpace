<?php
$this->breadcrumbs=array(
	'Groups'=>array('index'),
	$model->name=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List Group','url'=>array('index')),
	array('label'=>'Create Group','url'=>array('create')),
	array('label'=>'View Group','url'=>array('view','id'=>$model->id)),
    array('label'=>'Manage Group(advance mode) ','url'=>array('adminAdv')),
);
?>

<h1>Update Group <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form',array('model'=>$model)); ?>