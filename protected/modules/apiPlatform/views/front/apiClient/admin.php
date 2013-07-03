<?php
$this->breadcrumbs=array(
	'Api Clients'=>array('index'),
	'Manage',
);

$this->menu=array(
	array('label'=>'List ApiClient','url'=>array('index')),
	array('label'=>'Create ApiClient','url'=>array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$.fn.yiiGridView.update('api-client-grid', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>Manage Api Clients</h1>

<p>
You may optionally enter a comparison operator (<b>&lt;</b>, <b>&lt;=</b>, <b>&gt;</b>, <b>&gt;=</b>, <b>&lt;&gt;</b>
or <b>=</b>) at the beginning of each of your search values to specify how the comparison should be done.
</p>

<?php echo CHtml::link('Advanced Search','#',array('class'=>'search-button btn')); ?>
<div class="search-form" style="display:none">
<?php $this->renderPartial('_search',array(
	'model'=>$model,
)); ?>
</div><!-- search-form -->

<?php $this->widget('bootstrap.widgets.TbGridView',array(
	'id'=>'api-client-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		'id',
		'app_id',
		'app_key',
		'app_name',
		'app_domain',
		'app_description',
		/*
		'status',
		'create_time',
		'update_time',
		'modifier_id',
		*/
		array(
			'class'=>'bootstrap.widgets.TbButtonColumn',
		),
	),
)); ?>
