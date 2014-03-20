
<?php 
        $gridView  =  $this->widget('bootstrap.widgets.TbGridView',array(
        'id'=>'sys-album-items-view', // same as list view
         'summaryCssClass'=>'pull-right',
        'pager'=> array('class'=>'my.widgets.TbMixPager'),
        'template' =>  Yii::app()->request->getIsAjaxRequest()? "{summary}{pager}\n{items}\n{pager}\n" : "",
         'afterAjaxUpdate'=>'js:function(){ parent.risizeIframe();}',
        //  'dataProvider'=>$dataProvider, // do not use $model->search() if you want use pageSize widget
        'dataProvider'=>Yii::app()->request->getIsAjaxRequest()? $dataProvider : new CArrayDataProvider(array()) , // 使用假数据提供者
        'filter'=>$model,
        'columns'=>array(
        array(
        'class'=>'CCheckBoxColumn',
        'headerTemplate'=>'',// do not render the default checkAll checkBox
        'id'=>'items', // ids is used by AdminBulkActionForm
        'selectableRows'=>2, // must be greater than 2 to allow multiple row can be checked
        ),
		'id',
		'caption',
		'cover_uri',
		'location',
		'description',
		'type',
		/*
		'uid',
		'status',
		'create_time',
		'obj_count',
		'last_obj_id',
		'allow_view',
		*/
    array(
         'class'=>'bootstrap.widgets.TbButtonColumn',
        'template' => '{delete}<br/>{update}| {upload}',
        'header' => '操作',
        //'headerHtmlOptions' => array('class' => 'span1'),
        'htmlOptions' => array('class' => 'span1 font-arial text-center nowrap'),
        'buttons' => array(
            'upload' => array(
                'label' => '上传',
               // 'icon' => 'image',
                'url' => 'Yii::app()->controller->createUrl("upload",array("albumId"=>$data->primaryKey))',
                'options' => array(
                    'class' => 'edit text-info',
                   // 'onclick' => 'openEditor(this);return false;'
                ),
            ),
        ),

        ),
    ),
));
?>
<?php  if(!Yii::app()->getRequest()->getIsAjaxRequest()): ?>
<script type="text/javascript">
    jQuery(function(){
        setTimeout(function(){
            $.fn.yiiGridView.update('sys-album-items-view');
        },1500);
    });
</script>
<?php  endif; ?>