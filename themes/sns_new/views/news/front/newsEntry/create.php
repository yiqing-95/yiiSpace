<?php
/* @var $this NewsEntryController */
/* @var $model NewsEntry */

$this->breadcrumbs=array(
	'News Entries'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List NewsEntry', 'url'=>array('index')),
	array('label'=>'Manage NewsEntry', 'url'=>array('admin')),
);
?>

<h1>Create NewsEntry</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>