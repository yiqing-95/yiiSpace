
<?php 
        $this->widget('bootstrap.widgets.TbListView',array(
        'pager'=> array('class'=>'my.widgets.TbMixPager'),
        'dataProvider'=>$dataProvider,
        'itemView'=>'viewType_media',
        ));

?>