
<?php echo CHtml::link(CHtml::encode('创建子类'),array('create','pid'=>$data->id ) ,array('class'=>'btn')); ?>
<?php echo CHtml::link('&uarr;',array('move','id'=>$data->id,'mode'=>'up'),array('class'=>'move')); ?>
<?php echo CHtml::link('&darr;',array('move','id'=>$data->id,'mode'=>'down'),array('class'=>'move'));  ?>