<?php
/**
 * The following variables are available in this template:
 * - $this: the BootCrudCode object
 */
?>
<?php
echo "<?php\n";
$label = $this->pluralize($this->class2name($this->modelClass));
echo "\$this->breadcrumbs=array(
	'$label'=>array('index'),
	'Manage',
);\n";
?>

$this->menu=array(
array('label'=>'List <?php echo $this->modelClass; ?>','url'=>array('index')),
array('label'=>'Create <?php echo $this->modelClass; ?>','url'=>array('create')),
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


<?php echo "<?php"; ?> $this->beginClip('searchForm'); ?>

<h1>Manage <?php echo $this->pluralize($this->class2name($this->modelClass)); ?></h1>

<p>
    You may optionally enter a comparison operator (<b>&lt;</b>, <b>&lt;=</b>, <b>&gt;</b>, <b>&gt;=</b>, <b>
    &lt;&gt;</b>
    or <b>=</b>) at the beginning of each of your search values to specify how the comparison should be done.
</p>

<?php echo "<?php echo CHtml::link('Advanced Search','#',array('class'=>'search-button btn')); ?>"; ?>

<div class="search-form" style="display:none">
    <?php echo "<?php \$this->renderPartial('_search',array(
	'model'=>\$model,
)); ?>\n"; ?>
</div><!-- search-form -->
<?php echo "<?php"; ?> $this->endClip(); ?>

<?php echo "<?php"; ?> $this->widget('bootstrap.widgets.TbTabs', array(
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

<?php echo "<?php"; ?> echo CHtml::beginForm(); ?>

<?php echo "<?php"; ?> $this->beginWidget('my.widgets.ETbBox', array(
    'title' => '<?php echo $this->pluralize($this->class2name($this->modelClass)); ?>',
    'headerIcon' => 'icon-list',
   // 'htmlOptions' => array('class'=>'bootstrap-widget-table'),
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

<?php echo "<?php"; ?>  $dataProvider = $model->search();
        $pageSize = Yii::app()->user->getState('pageSize',10/*Yii::app()->params['defaultPageSize']*/);
        $pagination = $dataProvider->getPagination();
        $pagination->setPageSize($pageSize);


        $this->widget('bootstrap.widgets.TbThumbnails', array(
        'dataProvider'=>$dataProvider,
        'pager'=> array('class'=>'my.widgets.TbMixPager'),
        'template'=>"\n{pager}\n{items}\n{pager}",
        'itemView'=>'_thumb4admin',
        ));

?>

<?php echo "<?php"; ?> $this->endWidget();?>

<div class="alert alert-info">
    <div class="row-fluid">
        <div class="span2">
            <?php echo "<?php"; ?> echo CHtml::hiddenField('ids', '', array('class' => 'batch-op-targets')); ?>
            <?php echo "<?php"; ?>
            $this->widget('my.widgets.ECheckAllWidget', array(
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
            <?php echo "<?php"; ?>  echo CHtml::ajaxSubmitButton('删除', array('batchDelete'), array(
                'success' => 'js:batchOpSuccess',
                'dataType' => 'json',
            ),
            array(
                'class' => 'btn btn-danger', // btn-primary| btn-success |btn-warning| btn-danger| btn-inverse|btn-info
            )  );
            ?>

        </div>
    </div>
</div>

<?php echo "<?php"; ?> echo CHtml::endForm(); ?>

   <?php
  /**
   * 在后台admin视图实现批处理 可以参考下面的
   * @see http://www.yiiframework.com/wiki/353/working-with-cgridview-in-admin-panel/
   * @see TbExtendedGridView
   * @see TbBulkActions
   * @see http://yii-booster.clevertech.biz/extended-grid.html 这个是booster中如何实现做 参考Bulk Actions 段落
   */
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
    function reloadItemsView(viewContainerSelector) {
        //probe the gridView or listView id
        var listViewClass = '.list-view';
        var gridViewClass = '.grid-view';
        var XViewId;
        var $viewContainer = $(viewContainerSelector);
        if ($(listViewClass, $viewContainer).size() > 0) {
            XViewId = $(listViewClass, $viewContainer).attr('id');
            $.fn.yiiListView.update(XViewId);
        } else if ($(gridViewClass, $viewContainer).size() > 0) {
            XViewId = $(gridViewClass, $viewContainer).attr('id');
            $.fn.yiiGridView.update(XViewId);
        }
    }
</script>