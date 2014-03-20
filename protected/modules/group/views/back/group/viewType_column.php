<li class="span3">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id),array('view','id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('name')); ?>:</b>
	<?php echo CHtml::encode($data->name); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('description')); ?>:</b>
	<?php echo CHtml::encode($data->description); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('creator_id')); ?>:</b>
	<?php echo CHtml::encode($data->creator_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('created')); ?>:</b>
	<?php echo CHtml::encode($data->created); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('type')); ?>:</b>
	<?php echo CHtml::encode($data->type); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('active')); ?>:</b>
	<?php echo CHtml::encode($data->active); ?>
	<br />

	<?php /*
	<b><?php echo CHtml::encode($data->getAttributeLabel('recommend_grade')); ?>:</b>
	<?php echo CHtml::encode($data->recommend_grade); ?>
	<br />

	*/ ?>

	<?php echo CHtml::checkBox('ids[]',false,array('class'=>'batch-op-item','value'=>$data->id)); ?>		     <?php $this->widget('bootstrap.widgets.TbButtonGroup', array(
        'size'=>'mini',
        'buttons'=>array(
            array('label'=>CHtml::encode('查看'), 'url'=>array('view','id'=>$data->id)),
            array('label'=>CHtml::encode('编辑'), 'url'=>array('update','id'=>$data->id)),
            array('label'=>CHtml::encode('删除'), 'url'=>array('delete','id'=>$data->id),'htmlOptions'=>array('class'=>'delete')),
        ),
    ));
    ?>
</li>