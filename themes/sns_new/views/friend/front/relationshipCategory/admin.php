<?php
/* @var $this RelationshipCategoryController */
/* @var $model RelationshipCategory */

$this->breadcrumbs=array(
	'Relationship Categories'=>array('index'),
	'Manage',
);

$this->menu=array(
	array('label'=>'List RelationshipCategory', 'url'=>array('index')),
	array('label'=>'Create RelationshipCategory', 'url'=>array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#relationship-category-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>Manage Relationship Categories</h1>

<p>
You may optionally enter a comparison operator (<b>&lt;</b>, <b>&lt;=</b>, <b>&gt;</b>, <b>&gt;=</b>, <b>&lt;&gt;</b>
or <b>=</b>) at the beginning of each of your search values to specify how the comparison should be done.
</p>

<?php echo CHtml::link('Advanced Search','#',array('class'=>'search-button button icon-button')); ?>
<div class="search-form" style="display:none">
<?php $this->renderPartial('_search',array(
	'model'=>$model,
)); ?>
</div><!-- search-form -->
<div class="col">
    <div class="cell panel">
        <div class="body">
            <div class="cell">
                <div class="col">
                    <?php $this->widget('zii.widgets.grid.CGridView', array(
                    'id'=>'relationship-category-grid',
                    'dataProvider'=>$model->search(),
                    'filter'=> null ,// $model,
                    'itemsCssClass'=>'items uppercase-header border',
                    'htmlOptions'=>array(
                    'class'=>'cell',// 'outline-header',// 'box-header',
                    ),
                    'columns'=>array(
                    		'id',
		'user_id',
		'name',
		'display_order',
		'mbr_count',
                    array(
                    'class'=>'CButtonColumn',
                    ),
                    ),
                    )); ?>
                </div>

            </div>

        </div>
    </div>
</div>

