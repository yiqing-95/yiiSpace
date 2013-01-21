
<div class="media view">
    <a class="pull-left" href="#">
        <img class="media-object" data-src="http://twitter.github.com/bootstrap/holder.js/64x64"
             style="width: 64px; height: 64px;"
             src="<?php echo bu('public/images/yii.png');?>">
        	<?php echo CHtml::checkBox('items[]',false,array('class'=>'batch-op-item','value'=>$data->cmt_id)); ?>		     </a>
    <div class="media-body">
        <h4 class="media-heading">(Media heading)
                        	<b><?php echo CHtml::encode($data->cmt_id); ?>:</b>
        </h4>
        ...

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

        <br/>
        	<?php echo CHtml::link(CHtml::encode('查看'),array('view','id'=>$data->cmt_id)); ?>		 	<?php echo CHtml::link(CHtml::encode('创建'),array('create')); ?>		 	<?php echo CHtml::link(CHtml::encode('编辑'),array('update','id'=>$data->cmt_id)); ?>		 	<?php echo CHtml::link(CHtml::encode('删除'),array('delete','id'=>$data->cmt_id),array('class'=>'delete')); ?>		     </div>
</div>