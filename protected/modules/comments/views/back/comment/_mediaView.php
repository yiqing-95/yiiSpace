
<?php 
        $this->widget('bootstrap.widgets.TbListView',array(
            'id'=>'comment-items-view', // same as grid view
            'pager'=> array('class'=>'my.widgets.TbMixPager'),
            'dataProvider'=>$dataProvider,
            'itemView'=>'viewType_media',
        ));

?>