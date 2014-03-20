<?php
/* @var $this GroupTopicPostController */
/* @var $model GroupTopicPost */

$this->breadcrumbs=array(
	'Group Topic Posts'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List GroupTopicPost', 'url'=>array('index')),
	array('label'=>'Manage GroupTopicPost', 'url'=>array('admin')),
);
?>

<h1>Create GroupTopicPost</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>