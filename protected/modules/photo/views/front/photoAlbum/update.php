<?php
$this->breadcrumbs=array(
	'Photo Albums'=>array('index'),
	$model->name=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List PhotoAlbum','url'=>array('index')),
	array('label'=>'Create PhotoAlbum','url'=>array('create')),
	array('label'=>'View PhotoAlbum','url'=>array('view','id'=>$model->id)),
	array('label'=>'Manage PhotoAlbum','url'=>array('admin')),
);
?>

<h1>Update PhotoAlbum <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form',array('model'=>$model)); ?>