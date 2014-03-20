<?php
/* @var $this NewsEntryController */
/* @var $model NewsEntry */

$this->breadcrumbs=array(
	'News Entries'=>array('index'),
	$model->title=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List NewsEntry', 'url'=>array('index')),
	array('label'=>'Create NewsEntry', 'url'=>array('create')),
	array('label'=>'View NewsEntry', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage NewsEntry', 'url'=>array('admin')),
);
?>

<h1>Update NewsEntry <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>