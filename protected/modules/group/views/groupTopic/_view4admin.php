<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id),array('view','id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('name')); ?>:</b>
	<?php echo CHtml::encode($data->name); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('creator')); ?>:</b>
	<?php echo CHtml::encode($data->creator); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('created')); ?>:</b>
	<?php echo CHtml::encode($data->created); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('active')); ?>:</b>
	<?php echo CHtml::encode($data->active); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('group')); ?>:</b>
	<?php echo CHtml::encode($data->group); ?>
	<br />


	<?php echo CHtml::checkBox('ids[]',false,array('class'=>'batch-op-item','value'=>$data->id)); ?>		 	<?php echo CHtml::link(CHtml::encode('查看'),array('view','id'=>$data->id)); ?>		 	<?php echo CHtml::link(CHtml::encode('创建'),array('create')); ?>		 	<?php echo CHtml::link(CHtml::encode('编辑'),array('update','id'=>$data->id)); ?>		 	<?php echo CHtml::link(CHtml::encode('删除'),array('delete','id'=>$data->id),array('class'=>'delete')); ?>		 
</div>