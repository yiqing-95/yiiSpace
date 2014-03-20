<?php
$this->breadcrumbs=array(
	'Notice Posts'=>array('index'),
	$model->title=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List NoticePost','url'=>array('index')),
	array('label'=>'Create NoticePost','url'=>array('create'),'linkOptions'=>array('class'=>'ajax_create')),
	array('label'=>'View NoticePost','url'=>array('view','id'=>$model->id)),
	array('label'=>'Manage NoticePost','url'=>array('admin')),
);
?>

<h1>Update NoticePost <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form',array('model'=>$model)); ?>