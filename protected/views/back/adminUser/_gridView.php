<?php

$dataProvider->sort->attributes = array(
    'roleName' => array(
        'asc' => 'role.name',
        'desc' => 'role.name DESC',
    ),
    '*',
);

$gridView = $this->widget('bootstrap.widgets.TbGridView', array(
    'id' => 'admin-user-items-view', // same as list view
    'summaryCssClass' => 'pull-right',
    'pager' => array('class' => 'my.widgets.TbMixPager'),
    'template' => "{summary}{pager}\n{items}\n{pager}\n",
    'dataProvider' => $dataProvider, // do not use $model->search() if you want use pageSize widget
    'filter' => $model,
    'columns' => array(
        array(
            'class' => 'CCheckBoxColumn',
            'headerTemplate' => '', // do not render the default checkAll checkBox
            'id' => 'items', // ids is used by AdminBulkActionForm
            'selectableRows' => 2, // must be greater than 2 to allow multiple row can be checked
        ),
        //'id',
        'username',
        //'password',
        'name',
        //'encrypt',
        //'role_id',
        array('name' => 'roleName', 'value' => 'empty($data->role)?: $data->role->name'),
        /*
        'disabled',
        'setting',
        'create_time',
        'update_time',
        */
        array(
            'class' => 'bootstrap.widgets.TbButtonColumn',
        ),
    ),
));
?>