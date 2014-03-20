<?php
$this->breadcrumbs=array(
	'Notice Posts'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List NoticePost','url'=>array('index')),
	array('label'=>'Manage NoticePost','url'=>array('admin')),
);
?>

<h1>Create NoticePost</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>