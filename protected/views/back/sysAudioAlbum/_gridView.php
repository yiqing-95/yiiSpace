
<?php 
        $gridView  =  $this->widget('bootstrap.widgets.TbGridView',array(
        'id'=>'sys-audio-album-items-view', // same as list view
         'summaryCssClass'=>'pull-right',
        'pager'=> array('class'=>'my.widgets.TbMixPager'),
        //  使用下面的模板来做ajax延迟加载
        //'template' =>  Yii::app()->request->getIsAjaxRequest()? "{summary}{pager}\n{items}\n{pager}\n" : "",
        'template' => "{summary}{pager}\n{items}\n{pager}\n",
         'afterAjaxUpdate'=>'js:function(){ parent.risizeIframe();}',
        //  'dataProvider'=>$dataProvider, // do not use $model->search() if you want use pageSize widget
        // 使用下面的数据提供者配置做ajax延迟加载！
        //'dataProvider'=>Yii::app()->request->getIsAjaxRequest()? $dataProvider : new CArrayDataProvider(array()) , // 使用假数据提供者
        'dataProvider'=>  $dataProvider  ,
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
        ),
    ),
));
?>
<?php  if(!Yii::app()->getRequest()->getIsAjaxRequest()): ?>
<script type="text/javascript">
   /*
    jQuery(function(){
        setTimeout(function(){
            $.fn.yiiGridView.update('sys-audio-album-items-view');
        },1500);
    });
    */
</script>
<?php  endif; ?>