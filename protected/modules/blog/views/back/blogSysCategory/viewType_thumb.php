<li class="span3">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id),array('view','id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('name')); ?>:</b>
	<?php echo CHtml::encode($data->name); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('position')); ?>:</b>
	<?php echo CHtml::encode($data->position); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('enable')); ?>:</b>
	<?php echo CHtml::encode($data->enable); ?>
	<br />


	<?php echo CHtml::checkBox('ids[]',false,array('class'=>'batch-op-item','value'=>$data->id)); ?>		     <?php $this->widget('bootstrap.widgets.TbButtonGroup', array(
        'buttons'=>array(
            array('label'=>CHtml::encode('查看'), 'url'=>array('view','id'=>$data->id)),
            array('label'=>CHtml::encode('编辑'), 'url'=>array('update','id'=>$data->id)),
            array('label'=>CHtml::encode('删除'), 'url'=>array('delete','id'=>$data->id),'htmlOptions'=>array('class'=>'delete')),
        ),
    ));
    ?>
</li>