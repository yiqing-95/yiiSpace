<?php
/**
 * The following variables are available in this template:
 * - $this: the BootCrudCode object
 */
?>

<?php echo "<?php "; ?>

        $this->widget('bootstrap.widgets.TbListView',array(
            'id'=>'<?php echo $this->class2id($this->modelClass); ?>-items-view', // same as grid view
            'pager'=> array('class'=>'my.widgets.TbMixPager'),
            'dataProvider'=>$dataProvider,
            'itemView'=>'viewType_media',
        ));

?>