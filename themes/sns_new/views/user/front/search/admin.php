<?php
/* @var $this SearchController */
/* @var $model User */

$this->breadcrumbs=array(
	'Users'=>array('index'),
	'Manage',
);

$this->menu=array(
	array('label'=>'List User', 'url'=>array('index')),
	array('label'=>'Create User', 'url'=>array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#user-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>Manage Users</h1>

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
                    'id'=>'user-grid',
                    'dataProvider'=>$model->search(),
                    'filter'=> null ,  //$model,  不需要使用过滤功能！
                    'itemsCssClass'=>'items uppercase-header border',
                    'htmlOptions'=>array(
                    'class'=>'cell',// 'outline-header',// 'box-header',
                    ),
                    'columns'=>array(
                    		'id',
		'username',
		'password',
		'icon_uri',
		'email',
		'activkey',
		/*
		'superuser',
		'status',
		'create_at',
		'lastvisit_at',
		*/
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

