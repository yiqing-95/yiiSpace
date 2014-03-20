<?php
/* @var $this SearchController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Users',
);

$this->menu=array(
	array('label'=>'Create User', 'url'=>array('create')),
	array('label'=>'Manage User', 'url'=>array('admin')),
);
?>

<h1>Users</h1>
<?php

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
   //	$('#user-grid').yiiGridView('update', {
	$.fn.yiiListView.update('user-list-view', {
		data: $(this).serialize()
	}
	);
	return false;
});
");
?>

<?php echo CHtml::link('Advanced Search','#',array('class'=>'search-button button icon-button')); ?>
<div class="search-form" style="display:none">
    <?php $this->renderPartial('_search',array(
        'model'=>$model,
    )); ?>
</div><!-- search-form -->

<?php
$dataProvider = $model->search();
$this->widget('zii.widgets.CListView', array(
    'id'=>'user-list-view',
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
    'itemsCssClass'=>'items',
)); ?>
