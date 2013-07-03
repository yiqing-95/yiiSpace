<?php 	$this->widget('my.widgets.TbColumnView', array(
    'id'=>'sys-menu-items-view', // same as grid view
    'dataProvider' => $dataProvider,
    'pager'=> array('class'=>'my.widgets.TbMixPager'),
    'template'=>"{summary}\n{pager}\n{items}\n{pager}",
    'itemView' => 'viewType_column',
    'cols' => 3
)); ?>