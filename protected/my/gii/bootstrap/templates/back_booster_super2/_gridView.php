<?php
/**
 * The following variables are available in this template:
 * - $this: the BootCrudCode object
 */
?>

<?php echo "<?php "; ?>

        $gridView  =  $this->widget('bootstrap.widgets.TbGridView',array(
        'id'=>'<?php echo $this->class2id($this->modelClass); ?>-grid',
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
        ),
    ),
));
?>