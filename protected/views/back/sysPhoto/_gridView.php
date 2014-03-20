
<?php 
        $gridView  =  $this->widget('bootstrap.widgets.TbGridView',array(
        'id'=>'sys-photo-items-view', // same as list view
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
		'categories',
		'uid',
		'ext',
		'size',
		'title',
		/*
		'uri',
		'desc',
		'tags',
		'create_time',
		'views',
		'rate',
		'rate_count',
		'cmt_count',
		'featured',
		'status',
		'hash',
		*/
    array(
         'class'=>'bootstrap.widgets.TbButtonColumn',
        ),
    ),
));
?>
<?php  if(!Yii::app()->getRequest()->getIsAjaxRequest()): ?>
<script type="text/javascript">
    jQuery(function(){
        setTimeout(function(){
            $.fn.yiiGridView.update('sys-photo-items-view');
        },1500);
    });
</script>
<?php  endif; ?>