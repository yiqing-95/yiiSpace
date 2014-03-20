<?php
$this->breadcrumbs=array(
	'Blog Sys Categories'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List BlogSysCategory','url'=>array('index')),
	array('label'=>'Manage BlogSysCategory','url'=>array('admin')),
);
?>

<h1>Create BlogSysCategory</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>