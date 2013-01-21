<?php
/**
 * The following variables are available in this template:
 * - $this: the BootCrudCode object
 */
?>

<?php echo "<?php "; ?>

        $this->widget('bootstrap.widgets.TbThumbnails', array(
        'dataProvider'=>$dataProvider,
        'pager'=> array('class'=>'my.widgets.TbMixPager'),
        'template'=>"\n{pager}\n{items}\n{pager}",
        'itemView'=>'viewType_thumb',
        ));

?>