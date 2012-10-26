<?php 

    $this->widget('my.widgets.EMediaGridView',array(
        'id'=>'relationship-type-grid',
        'template'=>"{pager}\n{summary}\n{items}\n{summary}\n{pager}",
        'dataProvider'=>$dataProvider,
         'itemView'=>'_view4admin',
        // Remove the existing tooltips and rebind the plugin after each ajax-call.
        'afterAjaxUpdate'=>'js:function() {
                // 清空翻页前选择后遗留到隐藏域中的东西：
                $(".batch-op-targets").val("");
                $("#msg").html("您共选中了 0 项");
                //选中所有的点击一下： 参考ECheckAll
                $(".checkbox-all").attr("checked",false);
                // jQuery(".tooltip").remove();
                // jQuery("a[rel=tooltip]").tooltip();
        }',
        'gridCss'=>'{background: #28dc94; border-spacing: 8px;}',
        'gridCellCss'=>'{background: #d0f368;}',
    )); ?>
