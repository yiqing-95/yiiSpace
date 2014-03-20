<?php
$gridView = $this->widget('bootstrap.widgets.TbGridView', array(
    'id' => 'blog-sys-category-items-view', // same as list view
    'summaryCssClass' => 'pull-right',
    'pager' => array('class' => 'my.widgets.TbMixPager'),
    'template' => "{summary}{pager}\n{items}\n{pager}\n",
    'afterAjaxUpdate' => 'js:function(){ parent.risizeIframe();}',
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
        array(
            'class' => 'editable.EditableColumn',
            'name' => 'name',
            'headerHtmlOptions' => array('style' => 'width: 110px'),
            'editable' => array( //editable section
                'url' => $this->createUrl('editableSaver'),
                'placement' => 'right',
            )
        ),
        array(
            'class' => 'editable.EditableColumn',
            'name' => 'position',
            'headerHtmlOptions' => array('style' => 'width: 110px'),
            'editable' => array( //editable section
                'url' => $this->createUrl('editableSaver'),
                'placement' => 'right',
            )
        ),
        array(
            'class' => 'editable.EditableColumn',
            'name' => 'enable',
            'headerHtmlOptions' => array('style' => 'width: 100px'),
            'editable' => array(
                'type'     => 'select',
                'url'      =>  $this->createUrl('editableSaver'),
                'source'   => array('1'=>'启用','0'=>'禁用'),
                //onsave event handler
                'onSave' => 'js: function(e, params) {
                      console && console.log("saved value: "+params.newValue);
                  }'
            )
        ),
        // 'enable',
        array(
            'class' => 'bootstrap.widgets.TbButtonColumn',
        ),
    ),
));
?>