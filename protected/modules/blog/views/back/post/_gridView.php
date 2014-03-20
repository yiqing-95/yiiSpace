
<?php 
        $gridView  =  $this->widget('bootstrap.widgets.TbGridView',array(
        'id'=>'post-items-view', // same as list view
         'summaryCssClass'=>'pull-right',
        'pager'=> array('class'=>'my.widgets.TbMixPager'),
        'template' =>  "{summary}{pager}\n{items}\n{pager}\n" ,
         'afterAjaxUpdate'=>'js:function(){ parent.risizeIframe();}',
        'dataProvider'=>$dataProvider, // do not use $model->search() if you want use pageSize widget
        'filter'=>$model,
        'columns'=>array(
        array(
        'class'=>'CCheckBoxColumn',
        'headerTemplate'=>'',// do not render the default checkAll checkBox
        'id'=>'items', // ids is used by AdminBulkActionForm
        'selectableRows'=>2, // must be greater than 2 to allow multiple row can be checked
        ),
		'id',
		'title',
		//'content',
		'summary',
		'tags',
		'status',
            array(
                'header' => '操作',
                'name' => 'id',
                'type' => 'raw',
                'value' => 'Yii::app()->controller->renderPartial(\'_view4op\',array(\'data\'=>$data),true)',
            ),
		/*
		'created',
		'updated',
		'author_id',
		'category_id',
		*/
    array(
         'class'=>'bootstrap.widgets.TbButtonColumn',
        ),
    ),
));
?>