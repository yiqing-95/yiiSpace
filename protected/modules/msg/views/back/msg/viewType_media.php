
<div class="media view">
    <a class="pull-left" href="#">
        <img class="media-object" data-src="http://twitter.github.com/bootstrap/holder.js/64x64"
             style="width: 64px; height: 64px;"
             src="<?php echo bu('public/images/yii.png');?>">
        	<?php echo CHtml::checkBox('items[]',false,array('class'=>'batch-op-item','value'=>$data->id)); ?>		     </a>
    <div class="media-body">
        <h4 class="media-heading">(Media heading)
                        	<b><?php echo CHtml::encode($data->id); ?>:</b>
        </h4>
        ...

        	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id),array('view','id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('uid')); ?>:</b>
	<?php echo CHtml::encode($data->uid); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('data')); ?>:</b>
	<?php echo CHtml::encode($data->data); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('type')); ?>:</b>
	<?php echo CHtml::encode($data->type); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('snd_type')); ?>:</b>
	<?php echo CHtml::encode($data->snd_type); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('snd_status')); ?>:</b>
	<?php echo CHtml::encode($data->snd_status); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('priority')); ?>:</b>
	<?php echo CHtml::encode($data->priority); ?>
	<br />

	<?php /*
	<b><?php echo CHtml::encode($data->getAttributeLabel('to_id')); ?>:</b>
	<?php echo CHtml::encode($data->to_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('msg_pid')); ?>:</b>
	<?php echo CHtml::encode($data->msg_pid); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('create_time')); ?>:</b>
	<?php echo CHtml::encode($data->create_time); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('sent_time')); ?>:</b>
	<?php echo CHtml::encode($data->sent_time); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('delete_time')); ?>:</b>
	<?php echo CHtml::encode($data->delete_time); ?>
	<br />

	*/ ?>

        <br/>
        	<?php echo CHtml::link(CHtml::encode('查看'),array('view','id'=>$data->id)); ?>		 	<?php echo CHtml::link(CHtml::encode('创建'),array('create')); ?>		 	<?php echo CHtml::link(CHtml::encode('编辑'),array('update','id'=>$data->id)); ?>		 	<?php echo CHtml::link(CHtml::encode('删除'),array('delete','id'=>$data->id),array('class'=>'delete')); ?>		     </div>
</div>