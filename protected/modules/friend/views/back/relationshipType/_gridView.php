
<?php 
        $gridView  =  $this->widget('bootstrap.widgets.TbGridView',array(
        'id'=>'relationship-type-grid',
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
		'id',
		'name',
		'plural_name',
		'active',
		'mutual',
    array(
         'class'=>'bootstrap.widgets.TbButtonColumn',
        ),
    ),
));
?>