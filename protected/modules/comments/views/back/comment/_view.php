<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('cmt_id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->cmt_id),array('view','id'=>$data->cmt_id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('object_name')); ?>:</b>
	<?php echo CHtml::encode($data->object_name); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('object_id')); ?>:</b>
	<?php echo CHtml::encode($data->object_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('cmt_parent_id')); ?>:</b>
	<?php echo CHtml::encode($data->cmt_parent_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('author_id')); ?>:</b>
	<?php echo CHtml::encode($data->author_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('user_name')); ?>:</b>
	<?php echo CHtml::encode($data->user_name); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('user_email')); ?>:</b>
	<?php echo CHtml::encode($data->user_email); ?>
	<br />

	<?php /*
	<b><?php echo CHtml::encode($data->getAttributeLabel('cmt_text')); ?>:</b>
	<?php echo CHtml::encode($data->cmt_text); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('create_time')); ?>:</b>
	<?php echo CHtml::encode($data->create_time); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('update_time')); ?>:</b>
	<?php echo CHtml::encode($data->update_time); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('status')); ?>:</b>
	<?php echo CHtml::encode($data->status); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('replies')); ?>:</b>
	<?php echo CHtml::encode($data->replies); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('mood')); ?>:</b>
	<?php echo CHtml::encode($data->mood); ?>
	<br />

	*/ ?>

</div>