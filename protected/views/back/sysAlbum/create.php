<?php
$this->breadcrumbs=array(
	'Sys Albums'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List SysAlbum','url'=>array('index')),
	array('label'=>'Manage SysAlbum','url'=>array('admin')),
);
?>

<h1>Create SysAlbum</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>