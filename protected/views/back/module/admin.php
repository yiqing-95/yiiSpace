<?php
$this->breadcrumbs=array(
	'Sys Modules'=>array('index'),
	'Manage',
);

$this->menu=array(
	array('label'=>'List SysModule','url'=>array('index')),
	array('label'=>'Create SysModule','url'=>array('create')),
    array('label'=>'Manage SysModule(advance mode) ','url'=>array('adminAdv')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$.fn.yiiGridView.update('sys-module-grid', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<?php $this->beginClip('searchForm'); ?>

<h1>Manage Sys Modules</h1>

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
<?php $this->endClip(); ?>

<?php $this->widget('wij.WijTabs', array(
          'theme'=>EWijmoWidget::THEME_ARISTO,
          'htmlOptions'=>array(
          'class'=>'controls',
      ),
    'tabs'=>array(
        'search'=> array('content'=>$this->clips['searchForm'], 'active'=>true),
        'quickLinks'=> array('content'=>'<p>all quick links for searching the different status (such as :active ,deleted,...)</p>'),
        //'Tags'=> array('content'=>'<p>search with tags , here you prepare the available tags</p>'),
         ),
    )); ?>

<?php $this->widget('zii.widgets.grid.CGridView',array(
	'id'=>'sys-module-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		'id',
		'module_id',
		'title',
		'vendor',
		'version',
		'dependencies',
		/*
		'ctime',
		*/
		array(
			'class'=>'CButtonColumn',
		),
	),
)); ?>
