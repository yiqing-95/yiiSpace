<?php echo CHtml::link('创建顶级导航',array('create'),array('class'=>'btn')) ;?>
<?php
$dataProvider->getCriteria()->order = 'lft' ;

$gridView = $this->widget('bootstrap.widgets.TbGridView', array(
    'id' => 'sys-menu-items-view', // same as list view
    'summaryCssClass' => 'pull-right',
    'pager' => array('class' => 'my.widgets.TbMixPager'),
    'template' => "{summary}{pager}\n{items}\n{pager}\n",
    'dataProvider' => $dataProvider, // do not use $model->search() if you want use pageSize widget
    'filter' => $model,
    'columns' => array(
        array(
            'class' => 'CCheckBoxColumn',
            'headerTemplate' => '', // do not render the default checkAll checkBox
            'id' => 'items', // ids is used by AdminBulkActionForm
            'selectableRows' => 2, // must be greater than 2 to allow multiple row can be checked
        ),
        'id',
        array(
            'name' => 'label',
            'value' => array(get_class($model), 'renderIndentName'),
        ),
        'level',

        array(
            'header'=>'操作',
            'name'=>'id',
            'type'=>'raw',
            'value'=>'$this->grid->getOwner()->renderPartial(\'_view4op\',array(\'data\'=>$data),true)',
        ),
        'link_to',
        'group_code',
        /*
        'url',
        'params',
        'ajaxoptions',
        'htmloptions',
        'is_visible',

        'label_en',

        */
        array(
            'class' => 'bootstrap.widgets.TbButtonColumn',
        ),
    ),
));
?>


<script type="text/javascript">
    $(function(){

        jQuery("a.move").live("click",function() {
            if(!confirm("确定要移动顺序么?")) return false;
            var url = $(this).attr('href');
            var params = {};
            $.ajax({
                type: "POST",
                url: url,
                data: params,
                dataType: "json",
                success: function(resp){
                    // alert(resp);
                    if(resp.status == 'success'){
                        $.fn.yiiGridView.update("sys-menu-items-view");
                    }else{
                        alert(resp.msg);
                    }
                }
            });
            return false;
        });

    })
</script>