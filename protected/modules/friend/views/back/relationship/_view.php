<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id),array('view','id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('type')); ?>:</b>
	<?php echo CHtml::encode($data->type); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('user_a')); ?>:</b>
	<?php echo CHtml::encode($data->user_a); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('user_b')); ?>:</b>
	<?php echo CHtml::encode($data->user_b); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('accepted')); ?>:</b>
	<?php echo CHtml::encode($data->accepted); ?>
	<br />


</div>