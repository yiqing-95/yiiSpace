<?php
$this->breadcrumbs=array(
	'Relationship Types'=>array('index'),
	'Manage',
);

$viewStyles = array(
     'gridView'=>'_adminGridView',
     'listView'=>'_adminListView',
     'mediaView'=>'_adminMediaView',
   );
$requestViewStyle = isset($_GET['viewStyle']) ? $_GET['viewStyle'] : 'gridView' ;
$jsFnViewStyleName = ($requestViewStyle != 'gridView') ? 'yiiListView' : 'yiiGridView';

$this->menu=array(
	array('label'=>'List RelationshipType','url'=>array('index')),
	array('label'=>'Create RelationshipType','url'=>array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
   //probe the gridView or listView id
    var listViewClass = '.list-view';
    var gridViewClass = '.grid-view';
    var viewContainerSelector  = '#batchOpForm';
    var XViewId ;
    if($(listViewClass,viewContainerSelector).size()>0){
             XViewId = $(listViewClass,viewContainerSelector).attr('id');
            $.fn.yiiListView.update(XViewId, {
                  data: $(this).serialize()
            });
    }else if($(gridViewClass,viewContainerSelector).size()>0){
             XViewId = $(gridViewClass,viewContainerSelector).attr('id');
            $.fn.yiiGridView.update(XViewId, {
                   data: $(this).serialize()
            });
    }
    /*
	$.fn.{$jsFnViewStyleName}.update(XViewId, {
		data: $(this).serialize()
	}); */
	return false;
});
");
?>

<?php $this->beginClip('searchForm'); ?>

<h1>Manage Relationship Types</h1>

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

<span id="msg"></span>

<?php 

$XViewName = ($requestViewStyle != 'gridView') ? 'listViewId' : 'gridViewId';

$this->widget('ext.PageSize.EPageSize', array(
        $XViewName => 'relationship-type-grid',
        'pageSize' => Yii::app()->request->getParam('pageSize',null),
        'defaultPageSize' =>  10 ,   // may use this :  Yii::app()->params['defaultPageSize'],
        'pageSizeOptions'=> array(5=>5, 10=>10, 25=>25, 50=>50, 75=>75, 100=>100), // you can config it in main.php under the config dir . Yii::app()->params['pageSizeOptions'],// Optional, you can use with the widget default
        ));

        $dataProvider = $model->search();
        $pageSize = Yii::app()->user->getState('pageSize',10/*Yii::app()->params['defaultPageSize']*/);
        $pagination = $dataProvider->getPagination();
        $pagination->setPageSize($pageSize);
?>

<?php echo CHtml::beginForm($this->createUrl('batchOperation'),'post',array('id'=>'batchOpForm')); ?>

<?php $this->renderPartial($viewStyles[$requestViewStyle],array(
    'model'=>$model,
	'dataProvider'=>$dataProvider,
)); ?>


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
                'class' => 'btn btn-danger', //
            )
        ); ?>

        </div>
    </div>
</div>
<script type="text/javascript">
    function batchOpSuccess(data, textStatus, jqXHR){
        if(data.success == true){
            reloadItemsView("#batchOpForm");
        }else{
            alert(data.msg);
        }
    }

    /**
     * the container selector which contian the
     * gridView or listView
     * @param viewContainerSelector batchOpForm
     */
    function reloadItemsView(viewContainerSelector) {
        //probe the gridView or listView id
        var listViewClass = '.list-view';
        var gridViewClass = '.grid-view';
        //var viewContainerSelector  = '#batchOpForm';
        var XViewId;
        if ($(listViewClass, viewContainerSelector).size() > 0) {
            XViewId = $(listViewClass, viewContainerSelector).attr('id');
            $.fn.yiiListView.update(XViewId);
            //alert('listView a');
        } else if ($(gridViewClass, viewContainerSelector).size() > 0) {
            XViewId = $(gridViewClass, viewContainerSelector).attr('id');
            $.fn.yiiGridView.update(XViewId);
            // alert('gridView a');
        }
    }
</script>

<?php CHtml::endForm(); ?>