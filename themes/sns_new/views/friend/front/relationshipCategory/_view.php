<?php
/* @var $this RelationshipCategoryController */
/* @var $data RelationshipCategory */
?>

<div class="view border-bottom">

    <div class="cell">
   <h3>
       	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>
	<br />

   </h3>
        <div class="col content">
	<b><?php echo CHtml::encode($data->getAttributeLabel('user_id')); ?>:</b>
	<?php echo CHtml::encode($data->user_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('name')); ?>:</b>
	<?php echo CHtml::encode($data->name); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('display_order')); ?>:</b>
	<?php echo CHtml::encode($data->display_order); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('mbr_count')); ?>:</b>
	<?php echo CHtml::encode($data->mbr_count); ?>
	<br />

        </div>
    </div>
</div>