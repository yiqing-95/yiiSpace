<?php
$this->breadcrumbs=array(
	'Notice Categories'=>array('index'),
	$model->name=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List NoticeCategory','url'=>array('index')),
	array('label'=>'Create NoticeCategory','url'=>array('create'),'linkOptions'=>array('class'=>'ajax_create')),
	array('label'=>'View NoticeCategory','url'=>array('view','id'=>$model->id)),
	array('label'=>'Manage NoticeCategory','url'=>array('admin')),
);
?>

<h1>Update NoticeCategory <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form',array('model'=>$model)); ?>