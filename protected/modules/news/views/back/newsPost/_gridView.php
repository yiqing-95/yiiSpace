
<?php 
        $gridView  =  $this->widget('bootstrap.widgets.TbGridView',array(
        'id'=>'news-post-items-view', // same as list view
         'summaryCssClass'=>'pull-right',
        'pager'=> array('class'=>'my.widgets.TbMixPager'),
        'template'=>"{summary}{pager}\n{items}\n{pager}\n",
        'dataProvider'=>$dataProvider, // do not use $model->search() if you want use pageSize widget
        'filter'=>$model,
        'columns'=>array(
        array(
        'class'=>'CCheckBoxColumn',
        'headerTemplate'=>'',// do not render the default checkAll checkBox
        'id'=>'items', // ids is used by AdminBulkActionForm
        'selectableRows'=>2, // must be greater than 2 to allow multiple row can be checked
        ),
		'nid',
		'content',
		'keywords',
		'meta_title',
		'meta_description',
		'meta_keywords',
    array(
         'class'=>'bootstrap.widgets.TbButtonColumn',
        ),
    ),
));
?>