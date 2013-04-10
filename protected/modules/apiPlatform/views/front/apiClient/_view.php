<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id),array('view','id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('app_id')); ?>:</b>
	<?php echo CHtml::encode($data->app_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('app_key')); ?>:</b>
	<?php echo CHtml::encode($data->app_key); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('app_name')); ?>:</b>
	<?php echo CHtml::encode($data->app_name); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('app_domain')); ?>:</b>
	<?php echo CHtml::encode($data->app_domain); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('app_description')); ?>:</b>
	<?php echo CHtml::encode($data->app_description); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('status')); ?>:</b>
	<?php echo CHtml::encode($data->status); ?>
	<br />

	<?php /*
	<b><?php echo CHtml::encode($data->getAttributeLabel('create_time')); ?>:</b>
	<?php echo CHtml::encode($data->create_time); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('update_time')); ?>:</b>
	<?php echo CHtml::encode($data->update_time); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('modifier_id')); ?>:</b>
	<?php echo CHtml::encode($data->modifier_id); ?>
	<br />

	*/ ?>

</div>