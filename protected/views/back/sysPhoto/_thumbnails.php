
<?php 
        $this->widget('bootstrap.widgets.TbThumbnails', array(
            'id'=>'sys-photo-items-view', // same as grid view
            'dataProvider'=>$dataProvider,
            'pager'=> array('class'=>'my.widgets.TbMixPager'),
            'template'=>"\n{pager}\n{items}\n{pager}",
            'itemView'=>'viewType_thumb',
            'viewData'=>array(
                // 很蛋疼 一层层传递下来这个变量！
                'albumId'=>$albumId,
            )
        ));

?>