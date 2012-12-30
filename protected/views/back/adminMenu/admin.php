<?php
$this->breadcrumbs=array(
	'Admin Menus'=>array('index'),
	'Manage',
);

$this->menu=array(
	array('label'=>'List AdminMenu','url'=>array('index')),
	array('label'=>'Create AdminMenu','url'=>array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$.fn.yiiGridView.update('admin-menu-grid', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>Manage Admin Menus</h1>

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

<?php $this->widget('bootstrap.widgets.TbExtendedGridView',array(
	'type' => 'striped bordered',
	'id'=>'admin-menu-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'bulkActions' => array(
		'actionButtons' => array(
			array(
				'buttonType' => 'link',
                'htmlOptions' => array(
                            'class'=>'bulk-action'
                         ),
				'type' => 'primary',
				'size' => 'small',
				'label' => 'bulkDeletion',
                'url' => array('batchDelete');
				'click' => 'js:function(values){console.log(values);}'
				)
			),
			// if grid doesn't have a checkbox column type, it will attach
			// one and this configuration will be part of it
			'checkBoxColumnConfig' => array(
				'name' => 'id'
			),
	),
	'columns'=>array(
		'id',
		'root',
		'lft',
		'rgt',
		'level',
		'label',
		/*
		'url',
		'params',
		'ajaxoptions',
		'htmloptions',
		'is_visible',
		'uid',
		'group_code',
		*/
		array(
			'class'=>'bootstrap.widgets.TbButtonColumn',
		),
	),
)); ?>
