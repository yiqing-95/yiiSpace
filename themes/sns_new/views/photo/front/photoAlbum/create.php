<?php
$this->breadcrumbs=array(
	'Photo Albums'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List PhotoAlbum','url'=>array('index')),
	array('label'=>'Manage PhotoAlbum','url'=>array('admin')),
);
?>

<h1>Create PhotoAlbum</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>