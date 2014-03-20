<?php
$this->breadcrumbs=array(
	'Blog Recommends'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List BlogRecommend','url'=>array('index')),
	array('label'=>'Create BlogRecommend','url'=>array('create'),'linkOptions'=>array('class'=>'ajax_create')),
	array('label'=>'View BlogRecommend','url'=>array('view','id'=>$model->id)),
	array('label'=>'Manage BlogRecommend','url'=>array('admin')),
);
?>

<h1>Update BlogRecommend <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form',array('model'=>$model)); ?>