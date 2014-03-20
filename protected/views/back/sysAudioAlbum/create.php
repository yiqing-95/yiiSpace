<?php
$this->breadcrumbs=array(
	'Sys Audio Albums'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List SysAudioAlbum','url'=>array('index')),
	array('label'=>'Manage SysAudioAlbum','url'=>array('admin')),
);
?>

<h1>Create SysAudioAlbum</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>