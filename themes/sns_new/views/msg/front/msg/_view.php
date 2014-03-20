<?php
/* @var $this MsgController */
/* @var $data Msg */

$data = $data->msg ;
?>

<div class="col">
<div class="cell panel">
    <div class="body">
        <div class="cell">
            <div class="col">
                <div class="cell">
                    <div class="col width-fit">
                        	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>

	<br />
                        <?php if($data->type == Msg::TYPE_MBR_PERSONAL): ?>
                        <?php
                           $senderList = Registry::instance()->get('senderList');
                            if($senderList[$data->uid]): ?>

                        <div class="cell">
                            <a href="<?php echo UserHelper::getUserSpaceUrl($data->uid); ?>">

                                <img src="<?php  echo UserHelper::getUserIconUrl($senderList[$data->uid]); ?>" width="75" height="75" alt="">
                            </a>
                        </div>
                         <?php endif ; ?>
                         <?php endif ; ?>
                    </div>
                    <div class="col width-fill">
                        <div class="cell">
                            	<b><?php echo CHtml::encode($data->getAttributeLabel('type')); ?>:</b>
	<?php echo CHtml::encode($data->type); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('uid')); ?>:</b>
	<?php echo CHtml::encode($data->uid); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('data')); ?>:</b>
	<?php echo CHtml::encode($data->data); ?>
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
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
    </div>

</div>
