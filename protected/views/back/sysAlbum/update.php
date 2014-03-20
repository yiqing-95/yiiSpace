<?php
$this->breadcrumbs=array(
	'Sys Albums'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List SysAlbum','url'=>array('index')),
	array('label'=>'Create SysAlbum','url'=>array('create'),'linkOptions'=>array('class'=>'ajax_create')),
	array('label'=>'View SysAlbum','url'=>array('view','id'=>$model->id)),
	array('label'=>'Manage SysAlbum','url'=>array('admin')),
);
?>

<h1>Update SysAlbum <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form',array('model'=>$model)); ?>