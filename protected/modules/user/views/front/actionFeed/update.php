<?php
$this->breadcrumbs=array(
	'Action Feeds'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List ActionFeed','url'=>array('index')),
	array('label'=>'Create ActionFeed','url'=>array('create')),
	array('label'=>'View ActionFeed','url'=>array('view','id'=>$model->id)),
    array('label'=>'Manage ActionFeed(advance mode) ','url'=>array('adminAdv')),
);
?>

<h1>Update ActionFeed <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form',array('model'=>$model)); ?>