
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

        <br/>
        	<?php echo CHtml::link(CHtml::encode('查看'),array('view','id'=>$data->id)); ?>		 	<?php echo CHtml::link(CHtml::encode('创建'),array('create')); ?>		 	<?php echo CHtml::link(CHtml::encode('编辑'),array('update','id'=>$data->id)); ?>		 	<?php echo CHtml::link(CHtml::encode('删除'),array('delete','id'=>$data->id),array('class'=>'delete')); ?>		     </div>
</div>