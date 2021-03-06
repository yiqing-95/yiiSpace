<?php
$this->breadcrumbs=array(
	'Posts'=>array('index'),
	'Manage',
);

$this->menu=array(
array('label'=>'List Post','url'=>array('index')),
array('label'=>'Create Post','url'=>array('create'),'linkOptions'=>array('class'=>'ajax_create')),
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
        var viewContainerObj  = $('body');
        var XViewId ;
        if($(listViewClass,viewContainerObj).size()>0){
             XViewId = $(listViewClass,viewContainerObj).attr('id');
                    $.fn.yiiListView.update(XViewId, {
                     data: $(this).serialize()
              });
        }else if($(gridViewClass,viewContainerObj).size()>0){
              XViewId = $(gridViewClass,viewContainerObj).attr('id');
              $.fn.yiiGridView.update(XViewId, {
                  data: $(this).serialize()
              });
        }
    return false;
});
");
?>


<?php $this->beginClip('searchForm'); ?>

<h1>Manage Posts</h1>

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


<?php $this->beginClip('quickLinks'); ?>
<?php $this->widget('EAdminQuickLink',array(
    'label'=>'所有推荐的博文',
    'model'=>$model,
    'attributes'=>array(
        'recommendGrade'=>'1'
    )
));
?>
<?php $this->endClip(); ?>

<?php $this->widget('bootstrap.widgets.TbTabs', array(
    'htmlOptions' => array(
        'class' => 'controls alert alert-info', //'controls',
    ),
    'type'=>'tabs', // 'tabs' or 'pills'
    'placement' => 'above', // 'above', 'right', 'below' or 'left'
    'tabs' => array(
        array('label' => 'search', 'content' => $this->clips['searchForm'], 'active' => true),
        array('label' => 'quickLinks', 'content' => $this->clips['quickLinks']),
//array('label'=>'Tags', 'content'=>'<p>search with tags , here you prepare the available tags</p>'),
    ),
)); ?><!-- search-form -->
<script type="text/javascript">
    $(function(){
        //
        $(".controls ul").find("li").addClass('pull-right');
    });
</script>

<?php echo CHtml::beginForm(); ?>

<?php $this->beginWidget('my.widgets.ETbBox', array(
    'title' => 'Posts',
    'headerIcon' => 'icon-list',
   // 'htmlOptions' => array('class'=>'bootstrap-widget-table'),
    'id'=>'tb_box_items_views', // this it will used for the tb_box content !
    'headerButtons' => array(
        array(
            'class' => 'ext.PageSize.TbButtonGroupPageSize',
            'type' => 'info', // '', 'primary', 'info', 'success', 'warning', 'danger' or 'inverse'
            'pageSize' => Yii::app()->request->getParam('pageSize',null),
            'defaultPageSize' =>  10 ,   // may use this :  Yii::app()->params['defaultPageSize'],
            'pageSizeOptions'=> array(5=>5, 10=>10, 25=>25, 50=>50, 75=>75, 100=>100), // you can config it in main.php under the config dir . Yii::app()->params['pageSizeOptions'],// Optional, you can use with the widget default
        ),

    )));  ?>

<?php  $dataProvider = $model->search();
        $pageSize = Yii::app()->user->getState('pageSize',10/*Yii::app()->params['defaultPageSize']*/);
        $pagination = $dataProvider->getPagination();
        $pagination->setPageSize($pageSize);

        //  switch different view type can be achieved by pass a viewType param (_gridView|_columnView|_mediaView)
        $viewType = Yii::app()->request->getParam('viewType','_gridView');
        $this->renderPartial($viewType,array(
        'model'=> $model,
        'dataProvider'=> $dataProvider,
        ));

?>

<?php $this->endWidget();?>

<div class="alert alert-info">
    <div class="row-fluid">
        <div class="span2">
            <?php echo CHtml::hiddenField('ids', '', array('class' => 'batch-op-targets')); ?>
            <?php            $this->widget('my.widgets.ECheckAllWidget', array(
            'id' => 'parent',
            'label' => '全选<span id="msg"></span>',
            'labelPosition' => 'after',
            'childrenSelector' => '.checkbox-column :checkbox[name]:not([name$="_all"]),.batch-op-item',
            'triggerFunctions'=>'js:[function(recalculateState){checkAllReCalculateFunction=recalculateState}]',
            'callback' => 'js:function (checkedValues,childrenCount) {
                        $(".batch-op-targets").val(checkedValues.toString());
                        $("#msg").html("("+checkedValues.length+"/"+childrenCount+")");
                    }'
            ));
            ?>
        </div>
        <div class="span10">
            <?php  echo CHtml::ajaxSubmitButton('删除', array('batchDelete'), array(
                'success' => 'js:batchOpSuccess',
                'dataType' => 'json',
            ),
            array(
                'class' => 'btn btn-danger', // btn-primary| btn-success |btn-warning| btn-danger| btn-inverse|btn-info
            )  );

            $this->widget('bootstrap.widgets.TbButton', array(
            'buttonType'=>'link', // link, button, submit, submitLink, reset, ajaxLink, ajaxButton and ajaxSubmit.
            'label'=>CHtml::encode('批更新'),
            'type'=>'primary', // null, 'primary', 'info', 'success', 'warning', 'danger' or 'inverse'
            'size'=>'small', // null, 'large', 'small' or 'mini'
            'url'=>array('batchUpdateAjax'),
            'htmlOptions'=>array('class'=>'batch-update'),
            ));

            $this->widget('application.extensions.formDialog2.FormDialog2', array(
                    'link' => 'a.batch-update',
                    'options' => array(
                        'onSuccess' => 'js:function(data, e){alert(data.message);
                           reloadItemsView("body");
                }',
                    ),
                    'dialogOptions' => array(
                        'title'=>'batch update',
                        'width' => 600,
                        'height' => 470,

                    )
                )
            );
            ?>

        </div>
    </div>
</div>

<?php echo CHtml::endForm(); ?>

<?php 
    $this->widget('application.extensions.formDialog2.FormDialog2', array(
            'link' => 'a.ajax_create',
            'options' => array(
                'onSuccess' => 'js:function(data, e){alert(data.message);
                        reloadItemsView("body");
                    }',
            ),
            'dialogOptions' => array(
                'title'=>'创建 Post',
                'width' => 600,
                'height' => 470,

            )
        )
    );
    $this->widget('application.extensions.formDialog2.FormDialog2', array(
            'link' => 'a.update',
            'options' => array(
                'onSuccess' => 'js:function(data, e){alert(data.message);
                            reloadItemsView("body");
                    }',
            ),
            'dialogOptions' => array(
                'title'=>'编辑Post',
                'width' => 600,
                'height' => 470,

            )
        )
    );
?>

<?php
$this->widget('application.extensions.formDialog2.FormDialog2', array(
        'link' => 'a.recommend',
        'options' => array(
            'onSuccess' => 'js:function(data, e){alert(data.message);
                           reloadItemsView("body");
                }',
        ),
        'dialogOptions' => array(
            'title'=>'推荐',
            'width' => 600,
            'height' => 470,

        )
    )
);
?>

   <script type="text/javascript">
    /**
     * the checkAll plugin will pass a function object to this callBack var !
     */
    var checkAllReCalculateFunction ;
    $("#msg").ajaxSuccess(function(evt, request, settings){
        if(jQuery.isFunction(checkAllReCalculateFunction)){
            checkAllReCalculateFunction();
        }
    });

    function batchOpSuccess(respnose, textStatus, jqXHR){
        if(respnose.status == 'success'){
            jSuccess('操作成功!',{HorizontalPosition:'center',VerticalPosition:'center'});
            reloadItemsView("body");
            // clear out the selectedIds holder !
            $(".batch-op-targets").val("");
        }else{
            jError(respnose.msg,{HorizontalPosition:'center',VerticalPosition:'center'});
            //alert(respnose.msg);
        }
    }

    /**
     * used for ajax update
     * @return {*}
     */
    function getSelectedIds(){
        return  $(".batch-op-targets").val();
    }


</script>