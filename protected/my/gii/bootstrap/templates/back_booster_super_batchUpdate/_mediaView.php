<?php
/**
 * The following variables are available in this template:
 * - $this: the BootCrudCode object
 */
?>

<?php echo "<?php "; ?>

        $this->widget('bootstrap.widgets.TbListView',array(
        'pager'=> array('class'=>'my.widgets.TbMixPager'),
        'dataProvider'=>$dataProvider,
        'itemView'=>'viewType_media',
        ));

?>