
<div class="media view">
    <a class="pull-left" href="#">
        <img class="media-object" data-src="http://twitter.github.com/bootstrap/holder.js/64x64"
             style="width: 64px; height: 64px;"
             src="<?php echo bu('public/images/yii.png');?>">
        	<?php echo CHtml::checkBox('items[]',false,array('class'=>'batch-op-item','value'=>$data->nid)); ?>		     </a>
    <div class="media-body">
        <h4 class="media-heading">(Media heading)
                        	<b><?php echo CHtml::encode($data->nid); ?>:</b>
        </h4>
        ...

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


        <br/>
        	<?php echo CHtml::link(CHtml::encode('查看'),array('view','id'=>$data->nid)); ?>		 	<?php echo CHtml::link(CHtml::encode('创建'),array('create')); ?>		 	<?php echo CHtml::link(CHtml::encode('编辑'),array('update','id'=>$data->nid)); ?>		 	<?php echo CHtml::link(CHtml::encode('删除'),array('delete','id'=>$data->nid),array('class'=>'delete')); ?>		     </div>
</div>