<?php
$this->breadcrumbs=array(
	'Sys Audio Albums'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List SysAudioAlbum','url'=>array('index')),
	array('label'=>'Create SysAudioAlbum','url'=>array('create'),'linkOptions'=>array('class'=>'ajax_create')),
	array('label'=>'View SysAudioAlbum','url'=>array('view','id'=>$model->id)),
	array('label'=>'Manage SysAudioAlbum','url'=>array('admin')),
);
?>

<h1>Update SysAudioAlbum <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form',array('model'=>$model)); ?>