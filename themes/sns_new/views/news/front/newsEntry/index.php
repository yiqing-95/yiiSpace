<?php
/* @var $this NewsEntryController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'News Entries',
);

$this->menu=array(
	array('label'=>'Create NewsEntry', 'url'=>array('create')),
	array('label'=>'Manage NewsEntry', 'url'=>array('admin')),
);
?>

<h1>News Entries</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
    'itemsCssClass'=>'items',
)); ?>
