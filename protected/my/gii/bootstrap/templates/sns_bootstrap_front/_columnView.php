<?php echo "<?php \t"; ?>
$this->widget('my.widgets.TbColumnView', array(
    'id'=>'<?php echo $this->class2id($this->modelClass); ?>-items-view', // same as grid view
    'dataProvider' => $dataProvider,
    'pager'=> array('class'=>'my.widgets.TbMixPager'),
    'template'=>"{summary}\n{pager}\n{items}\n{pager}",
    'itemView' => 'viewType_column',
    'cols' => 3
)); ?>