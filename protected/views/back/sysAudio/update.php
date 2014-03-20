<?php
$this->breadcrumbs=array(
	'Sys Audios'=>array('index'),
	$model->name=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List SysAudio','url'=>array('index')),
	array('label'=>'Create SysAudio','url'=>array('create'),'linkOptions'=>array('class'=>'ajax_create')),
	array('label'=>'View SysAudio','url'=>array('view','id'=>$model->id)),
	array('label'=>'Manage SysAudio','url'=>array('admin')),
);
?>

<h1>Update SysAudio <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form',array('model'=>$model)); ?>