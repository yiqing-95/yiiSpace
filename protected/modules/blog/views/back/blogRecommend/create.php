<?php
$this->breadcrumbs=array(
	'Blog Recommends'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List BlogRecommend','url'=>array('index')),
	array('label'=>'Manage BlogRecommend','url'=>array('admin')),
);
?>

<h1>Create BlogRecommend</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>