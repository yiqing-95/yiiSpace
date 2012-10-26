<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id),array('view','id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('uid')); ?>:</b>
	<?php echo CHtml::encode($data->uid); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('action_type')); ?>:</b>
	<?php echo CHtml::encode($data->action_type); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('action_time')); ?>:</b>
	<?php echo CHtml::encode($data->action_time); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('data')); ?>:</b>
	<?php echo CHtml::encode($data->data); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('object_type')); ?>:</b>
	<?php echo CHtml::encode($data->object_type); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('object_id')); ?>:</b>
	<?php echo CHtml::encode($data->object_id); ?>
	<br />

	<?php /*
	<b><?php echo CHtml::encode($data->getAttributeLabel('target_type')); ?>:</b>
	<?php echo CHtml::encode($data->target_type); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('target_id')); ?>:</b>
	<?php echo CHtml::encode($data->target_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('target_owner')); ?>:</b>
	<?php echo CHtml::encode($data->target_owner); ?>
	<br />

	*/ ?>

	<?php echo CHtml::checkBox('ids[]',false,array('class'=>'batch-op-item','value'=>$data->id)); ?>		 	<?php echo CHtml::link(CHtml::encode('查看'),array('view','id'=>$data->id)); ?>		 	<?php echo CHtml::link(CHtml::encode('创建'),array('create')); ?>		 	<?php echo CHtml::link(CHtml::encode('编辑'),array('update','id'=>$data->id)); ?>		 	<?php echo CHtml::link(CHtml::encode('删除'),array('delete','id'=>$data->id),array('class'=>'delete')); ?>		 
</div>