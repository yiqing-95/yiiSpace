<?php
//$dataProvider = new CActiveDataProvider('');

$dataProvider->getSort()->defaultOrder = 't.order asc';

$gridView = $this->widget('bootstrap.widgets.TbExtendedGridView', array(
    'id' => 'notice-category-items-view', // same as list view
    'summaryCssClass' => 'pull-right',
    'pager' => array('class' => 'my.widgets.TbMixPager'),
    'template' => "{summary}{pager}\n{items}\n{pager}\n",
    'dataProvider' => $dataProvider, // do not use $model->search() if you want use pageSize widget
    'filter' => $model,

    /*
     * 排序功能有问题 ！
    'sortableRows'=>true,
    'sortableAttribute'=>'order',
    'sortableAjaxSave'=>true,
    'sortableAction'=> '/'.$this->module->id.'/'.$this->id.'/sortable',
    */

    'afterSortableUpdate' => 'js:function(id, position){
            alter("id: "+id+", position:"+position);
     }',

    'columns' => array(
        array(
            'class' => 'CCheckBoxColumn',
            'headerTemplate' => '', // do not render the default checkAll checkBox
            'id' => 'items', // ids is used by AdminBulkActionForm
            'selectableRows' => 2, // must be greater than 2 to allow multiple row can be checked
        ),
        'id',
        'name',
        array(
            'class' => 'bootstrap.widgets.TbEditableColumn',
            'name' => 'order',
            'sortable'=>false,
            'editable' => array(
                'url' => $this->createUrl('updateOrder'),
                'placement' => 'right',
                'inputclass' => 'span3'
            )
        ),
        array(
            'header' => 'Order',
            'name' => 'order',
            'class' => 'ext.OrderColumn.OrderColumn',
        ),
        'enable',
        array(
            'class' => 'bootstrap.widgets.TbButtonColumn',
        ),
    ),
));
?>