<?php
$this->breadcrumbs=array(
	'Api Clients'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List ApiClient','url'=>array('index')),
	array('label'=>'Create ApiClient','url'=>array('create')),
	array('label'=>'View ApiClient','url'=>array('view','id'=>$model->id)),
	array('label'=>'Manage ApiClient','url'=>array('admin')),
);
?>

<h1>Update ApiClient <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form',array('model'=>$model)); ?>