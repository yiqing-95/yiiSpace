<?php
$this->breadcrumbs=array(
	'Sys Audios'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List SysAudio','url'=>array('index')),
	array('label'=>'Manage SysAudio','url'=>array('admin')),
);
?>

<h1>Create SysAudio</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>