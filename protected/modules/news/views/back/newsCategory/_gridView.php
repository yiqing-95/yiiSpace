<?php
$dataProvider->getCriteria()->order = 'lft' ;

$gridView = $this->widget('bootstrap.widgets.TbGridView', array(
    'id' => 'news-category-items-view', // same as list view
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
        //'root',
        //'lft',
        //'rgt',
        //'level',
        array(
            'name' => 'name',
            'value' => array('NewsCategory', 'renderIndentName'),
        ),
        array(
            'header' => '操作',
            'name' => 'id',
            'type' => 'raw',
            'value' => '$this->grid->getOwner()->renderPartial(\'_view4op\',array(\'data\'=>$data),true)',
        ),
        /*
        'enable',
        'group_code',
        'mbr_count',
        'link_to',
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
                        $.fn.yiiGridView.update("news-category-items-view");
                    }else{
                        alert(resp.msg);
                    }
                }
            });
            return false;
        });

    })
</script>