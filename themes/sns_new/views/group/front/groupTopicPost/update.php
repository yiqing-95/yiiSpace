<?php
/* @var $this GroupTopicPostController */
/* @var $model GroupTopicPost */

$this->breadcrumbs=array(
	'Group Topic Posts'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List GroupTopicPost', 'url'=>array('index')),
	array('label'=>'Create GroupTopicPost', 'url'=>array('create')),
	array('label'=>'View GroupTopicPost', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage GroupTopicPost', 'url'=>array('admin')),
);
?>

<h1>Update GroupTopicPost <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>