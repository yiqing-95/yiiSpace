
<?php 

   $this->widget('zii.widgets.grid.CGridView',array(
        'id'=>'relationship-type-grid',
        'afterAjaxUpdate'=>'js:function(id, data){
                         // 清空翻页前选择后遗留到隐藏域中的东西：
                       // $(".batch-op-targets").val("");
                        $("#msg").html("您共选中了 0 项");
                }',
        'itemsCssClass'=>'table  ',
        'dataProvider'=>$dataProvider,
        'filter'=>$model,
        'selectableRows'=>2,
        'summaryCssClass'=>'',// summary
        'template'=>"{pager}<div class='row-fluid'><div>{summary}</div></div>\n{items}\n{summary}\n{pager}",
        'columns'=>array(
            array(
            'class'=>'CCheckBoxColumn',
            'id'=>'ids',
            'selectableRows'=>2,
            ),
		'id',
		'name',
		'plural_name',
		'active',
		'mutual',
		array(
			'class'=>'CButtonColumn',
            'htmlOptions' => array('class' => 'span2'),
		),
	),
)); ?>


<?php 
$this->widget('my.widgets.EOnGridChecked', array(
   'callback'=>'js:function(ids){
        //ids  is an array which hold the all checked value of the checkbox
        //给隐藏域赋值
        //$(".batch-op-targets").val(ids.toString());
        $("#msg").html("您共选中了 "+ids.length+" 项");
    } ',
));
?>