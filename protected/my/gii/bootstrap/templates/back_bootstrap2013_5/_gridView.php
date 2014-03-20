<?php
/**
 * The following variables are available in this template:
 * - $this: the BootCrudCode object
 */
?>

<?php echo "<?php \n"; ?>
        $dataProvider = $model->search();
        $pageSize = Yii::app()->user->getState('pageSize',20/*Yii::app()->params['defaultPageSize']*/);
        $pagination = $dataProvider->getPagination();
        $pagination->setPageSize($pageSize);

        $gridColumns = array(
        array(
        'class'=>'CCheckBoxColumn',
        'headerTemplate'=>'',// do not render the default checkAll checkBox
        'id'=>'items', // ids is used by AdminBulkActionForm
        'selectableRows'=>2, // must be greater than 2 to allow multiple row can be checked
        ),
        <?php
        $count = 0;
        foreach ($this->tableSchema->columns as $column) {
            if (++$count == 7)
                echo "\t\t/*\n";
            echo "\t\t'" . $column->name . "',\n";
        }
        if ($count >= 7)
            echo "\t\t*/\n";
        ?>
        array(
        'class'=>'bootstrap.widgets.TbButtonColumn',
        'template' => '{update}  {delete}',
        'buttons'=>array(
        'update'=>array(
        'options'=>array('target'=>'_blank')
        ))
        ),);

        $gridView  =  $this->widget('bootstrap.widgets.TbGridView',array(
        'id'=>'<?php echo $this->class2id($this->modelClass); ?>-items-view', // same as list view
        'type' => 'striped bordered',
        //'summaryCssClass'=>summary',
        'pager'=> array('class'=>'my.widgets.TbMixPager'),
        //'template' => "{summary}{pager}\n{items}\n{pager}\n" ,
        'dataProvider'=>$dataProvider, // do not use $model->search() if you want use pageSize widget
        //'filter'=>$model,
        'columns'=>$gridColumns,
        ));
?>