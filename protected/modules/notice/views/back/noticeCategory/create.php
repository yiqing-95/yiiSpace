<?php
$this->breadcrumbs=array(
	'Notice Categories'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List NoticeCategory','url'=>array('index')),
	array('label'=>'Manage NoticeCategory','url'=>array('admin')),
);
?>

<h1>Create NoticeCategory</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>