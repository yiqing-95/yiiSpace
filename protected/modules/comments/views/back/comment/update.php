<?php
$this->breadcrumbs=array(
	'Comments'=>array('index'),
	$model->cmt_id=>array('view','id'=>$model->cmt_id),
	'Update',
);

$this->menu=array(
	array('label'=>'List Comment','url'=>array('index')),
	array('label'=>'Create Comment','url'=>array('create')),
	array('label'=>'View Comment','url'=>array('view','id'=>$model->cmt_id)),
	array('label'=>'Manage Comment','url'=>array('admin')),
);
?>

<h1>Update Comment <?php echo $model->cmt_id; ?></h1>

<?php echo $this->renderPartial('_form',array('model'=>$model)); ?>