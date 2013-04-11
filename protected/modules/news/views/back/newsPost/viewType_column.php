<li class="span3">

	<b><?php echo CHtml::encode($data->getAttributeLabel('nid')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->nid),array('view','id'=>$data->nid)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('content')); ?>:</b>
	<?php echo CHtml::encode($data->content); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('keywords')); ?>:</b>
	<?php echo CHtml::encode($data->keywords); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('meta_title')); ?>:</b>
	<?php echo CHtml::encode($data->meta_title); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('meta_description')); ?>:</b>
	<?php echo CHtml::encode($data->meta_description); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('meta_keywords')); ?>:</b>
	<?php echo CHtml::encode($data->meta_keywords); ?>
	<br />


	<?php echo CHtml::checkBox('ids[]',false,array('class'=>'batch-op-item','value'=>$data->nid)); ?>		     <?php $this->widget('bootstrap.widgets.TbButtonGroup', array(
        'size'=>'mini',
        'buttons'=>array(
            array('label'=>CHtml::encode('查看'), 'url'=>array('view','id'=>$data->nid)),
            array('label'=>CHtml::encode('编辑'), 'url'=>array('update','id'=>$data->nid)),
            array('label'=>CHtml::encode('删除'), 'url'=>array('delete','id'=>$data->nid),'htmlOptions'=>array('class'=>'delete')),
        ),
    ));
    ?>
</li>