<?php
$this->breadcrumbs=array(
	'Yiisessions'=>array('index'),
	'Manage',
);

$this->menu=array(
array('label'=>'List Yiisession','url'=>array('index')),
array('label'=>'Create Yiisession','url'=>array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
$('.search-form').toggle();
return false;
});
$('.search-form form').submit(function(){
$.fn.yiiGridView.update('yiisession-grid', {
data: $(this).serialize()
});
return false;
});
");
?>


<?php $this->beginClip('searchForm'); ?>

<h1>Manage Yiisessions</h1>

<p>
    You may optionally enter a comparison operator (<b>&lt;</b>, <b>&lt;=</b>, <b>&gt;</b>, <b>&gt;=</b>, <b>
    &lt;&gt;</b>
    or <b>=</b>) at the beginning of each of your search values to specify how the comparison should be done.
</p>

<?php echo CHtml::link('Advanced Search','#',array('class'=>'search-button btn')); ?>
<div class="search-form" style="display:none">
    <?php $this->renderPartial('_search',array(
	'model'=>$model,
)); ?>
</div><!-- search-form -->
<?php $this->endClip(); ?>

<?php $this->widget('bootstrap.widgets.TbTabs', array(
    'htmlOptions' => array(
        'class' => 'controls',
    ),
    'type'=>'tabs', // 'tabs' or 'pills'
    'placement' => 'right', // 'above', 'right', 'below' or 'left'
    'tabs' => array(
        array('label' => 'search', 'content' => $this->clips['searchForm'], 'active' => true),
        array('label' => 'quickLinks', 'content' => '<p>all quick links for searching the different status (such as :active ,deleted,...)</p>'),
//array('label'=>'Tags', 'content'=>'<p>search with tags , here you prepare the available tags</p>'),
    ),
)); ?><!-- search-form -->


<?php $this->beginWidget('my.widgets.ETbBox', array(
    'title' => 'Yiisessions',
    'headerIcon' => 'icon-list',
    'htmlOptions' => array('class'=>'bootstrap-widget-table'),
    'headerButtons' => array(
        array(
            'class' => 'ext.PageSize.TbButtonGroupPageSize',
            'type' => 'info', // '', 'primary', 'info', 'success', 'warning', 'danger' or 'inverse'
            'pageSize' => Yii::app()->request->getParam('pageSize',null),
            'defaultPageSize' =>  10 ,   // may use this :  Yii::app()->params['defaultPageSize'],
            'pageSizeOptions'=> array(5=>5, 10=>10, 25=>25, 50=>50, 75=>75, 100=>100), // you can config it in main.php under the config dir . Yii::app()->params['pageSizeOptions'],// Optional, you can use with the widget default
        ),
        array(
            'class' => 'bootstrap.widgets.TbButtonGroup',
            'type' => 'primary', // '', 'primary', 'info', 'success', 'warning', 'danger' or 'inverse'
            'buttons' => array(
                array('label' => 'Action', 'url' => '#'), // this makes it split :)
                array('items' => array(
                    array('label' => 'Action', 'url' => '#'),
                    array('label' => 'Another action', 'url' => '#'),
                    array('label' => 'Something else', 'url' => '#'),
                    '---',
                    array('label' => 'Separate link', 'url' => '#'),
                )),
            )
        ),
    )));  ?>

<?php  $dataProvider = $model->search();
        $pageSize = Yii::app()->user->getState('pageSize',10/*Yii::app()->params['defaultPageSize']*/);
        $pagination = $dataProvider->getPagination();
        $pagination->setPageSize($pageSize);

         $this->widget('bootstrap.widgets.TbGridView',array(
        'id'=>'yiisession-grid',
        'dataProvider'=>$dataProvider, // do not use $model->search() if you want use pageSize widget
        'filter'=>$model,
        'columns'=>array(
        array(
        'class'=>'CCheckBoxColumn',
        'headerTemplate'=>'',// do not render the default checkAll checkBox
        'id'=>'ids',
        'selectableRows'=>1,
        ),
		'id',
		'expire',
		'data',
		'user_id',
    array(
         'class'=>'bootstrap.widgets.TbButtonColumn',
        ),
    ),
)); ?>

<?php $this->endWidget();?>

<div class="alert alert-info">
    <div class="row-fluid">
        <div class="span2">
            <?php echo CHtml::hiddenField('items', '', array('class' => 'batch-op-targets')); ?>
            <?php            $this->widget('my.widgets.ECheckAllWidget', array(
            'id' => 'parent',
            'label' => '全选',
            'labelPosition' => 'before',
            'childrenSelector' => '.checkbox-column :checkbox[name]:not([name$="_all"]),.batch-op-item',
            'callback' => 'js:function (checkedValues) {
            $(".batch-op-targets").val(checkedValues.toString());
            $("#msg").html("您共选中了 "+checkedValues.length+" 项");
            /*checkedValues.forEach(function (val) {
            // $("#msg").html($("#msg").html() + "|" + val);
            });
            checkedValues.toString();*/
            }'
            ));
            ?>
        </div>
        <div class="span10">
            <?php echo CHtml::ajaxSubmitButton('删除', array('batchDelete'), array(
            'success' => 'js:batchOpSuccess',
            'dataType' => 'json',
            ), array(
            'class' => 'btn btn-danger', // btn-primary| btn-success |btn-warning| btn-danger| btn-inverse|btn-info
            )
            ); ?>

        </div>
    </div>
</div>
