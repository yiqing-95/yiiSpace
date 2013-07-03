<?php
$this->breadcrumbs=array(
	'Sys Menus'=>array('index'),
	'Manage',
);

$this->menu=array(
array('label'=>'List SysMenu','url'=>array('index')),
array('label'=>'Create SysMenu','url'=>array('create'),'linkOptions'=>array('class'=>'ajax_create')),
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

<h1>Manage Sys Menus</h1>

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
    'label'=>'系统底部菜单',
    'model'=>$model,
    'attributes'=>array(
        'group_code'=>'main_bottom_menu'
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
    'title' => 'Sys Menus',
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
        array(
            'class' => 'bootstrap.widgets.TbButtonGroup',
            'type' => 'primary', // '', 'primary', 'info', 'success', 'warning', 'danger' or 'inverse',
            'htmlOptions'=>array('class'=>'style-switcher'),
            'buttons'=>array(
                    array('label' => CHtml::encode('切换显示风格'), 'url' => '#'), // this makes it split :)
                    array('items'=>array(
                        array('label'=>'grid','icon'=>'blank', 'url'=>array('','viewType'=>'_gridView'),'linkOptions'=>array('class'=>'switchViewType','view-type'=>'_gridView')),
                        array('label'=>'column','icon'=>'blank', 'url'=>array('','viewType'=>'_columnView'),'linkOptions'=>array('class'=>'switchViewType','view-type'=>'_columnView')),
                        array('label'=>'thumbnails','icon'=>'blank', 'url'=>array('','viewType'=>'_thumbnails'),'linkOptions'=>array('class'=>'switchViewType','view-type'=>'_thumbnails')),
                        array('label'=>'media','icon'=>'blank', 'url'=>array('','viewType'=>'_mediaView'),'linkOptions'=>array('class'=>'switchViewType','view-type'=>'_mediaView'))
                      )),
            ),
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
        // load a empty listView  just for register the nessesary js and css files:
        $this->widget('my.widgets.TbColumnView', array(
                'id'=>'sys-menu-items-view', // same as grid view
                'dataProvider' => $dataProvider,
                'pager'=> array('class'=>'my.widgets.TbMixPager'),
                'template'=>"", // use empty template
                'itemView' => 'viewType_column',
                //'cols' => 3
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
                'title'=>'创建 SysMenu',
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
                'title'=>'编辑SysMenu',
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
     * the container selector which contian the
     * gridView or listView
     * @param viewContainerSelector batchOpForm
     */
    function reloadItemsView(viewContainerSelector,options) {
        //probe the gridView or listView id
        var listViewClass = '.list-view';
        var gridViewClass = '.grid-view';
        var XViewId;
        var $viewContainer = $(viewContainerSelector);
        if ($(listViewClass, $viewContainer).size() > 0) {
            XViewId = $(listViewClass, $viewContainer).attr('id');
            $.fn.yiiListView.update(XViewId,options);
        } else if ($(gridViewClass, $viewContainer).size() > 0) {
            XViewId = $(gridViewClass, $viewContainer).attr('id');
            $.fn.yiiGridView.update(XViewId,options);
        }
    }

    /**
     * return the current grid or list view url
     * @param viewContainerSelector
     */
    function getItemsViewUrl(viewContainerSelector){
        //probe the gridView or listView id
        var listViewClass = '.list-view';
        var gridViewClass = '.grid-view';
        var XViewId,currentItemsViewUrl;
        var $viewContainer = $(viewContainerSelector);
        if ($(listViewClass, $viewContainer).size() > 0) {
            XViewId = $(listViewClass, $viewContainer).attr('id');
            currentItemsViewUrl = $.fn.yiiListView.getUrl(XViewId);
        } else if ($(gridViewClass, $viewContainer).size() > 0) {
            XViewId = $(gridViewClass, $viewContainer).attr('id');
            currentItemsViewUrl = $.fn.yiiGridView.getUrl(XViewId);
        }
        return currentItemsViewUrl
    }
    function getItemsViewId(viewContainerSelector){
        //probe the gridView or listView id
        var listViewClass = '.list-view';
        var gridViewClass = '.grid-view';
        var XViewId,currentItemsViewUrl;
        var $viewContainer = $(viewContainerSelector);
        if ($(listViewClass, $viewContainer).size() > 0) {
            XViewId = $(listViewClass, $viewContainer).attr('id');
        } else if ($(gridViewClass, $viewContainer).size() > 0) {
            XViewId = $(gridViewClass, $viewContainer).attr('id');
        }
        return XViewId;
    }
    function getIsGridView(viewContainerSelector){
        //probe the gridView or listView id
        var listViewClass = '.list-view';
        var gridViewClass = '.grid-view';
        var XViewId,currentItemsViewUrl;
        var $viewContainer = $(viewContainerSelector);
        return $(gridViewClass, $viewContainer).size() > 0 ;
    }


    /**
     * used for ajax update
     * @return {*}
     */
    function getSelectedIds(){
        return  $(".batch-op-targets").val();
    }

    $(function(){
        var $styleSwitcherMenus = $(".style-switcher .dropdown-menu");
        // make the first one as the checked !
        $('a',$styleSwitcherMenus).first().children('i').attr("class",'').addClass('icon-ok');
         /**
         *  切换状态 选中的下拉状态！
         */
        $('a',$styleSwitcherMenus).click(function (e) {
            e.preventDefault();
            $('i.icon-ok',$styleSwitcherMenus).removeClass('icon-ok').addClass('icon-blank');
            $(this).find('i').attr('class','').addClass('icon-ok');
            var itemsViewUrl = getItemsViewUrl('body');
            var selectedViewType = $(this).attr('view-type');
            if(itemsViewUrl.indexOf('viewType')>0){
                itemsViewUrl = itemsViewUrl.replace(/viewType\/\w+/, "viewType/"+selectedViewType); // for pathInfo
                itemsViewUrl = itemsViewUrl.replace(/viewType=\w+/, "viewType="+selectedViewType);  // for queryString url pattern
            }else{
                itemsViewUrl =  $.param.querystring(itemsViewUrl,{"viewType":selectedViewType});
            }
           // alert(itemsViewUrl);
            $.get(itemsViewUrl,function(data){
                var $data = $('<div>' + data + '</div>');
                var updateId = "#tb_box_items_views";
                $(updateId).replaceWith($(updateId,$data ));
            });
            /*

            */
        });
    });
</script>