<li class="span3">

    <b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
    <?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id' => $data->id)); ?>
    <br/>

    <b><?php echo CHtml::encode($data->getAttributeLabel('caption')); ?>:</b>
    <?php echo CHtml::encode($data->caption); ?>
    <br/>

    <b><?php


        echo CHtml::encode($data->getAttributeLabel('cover_uri')); ?>:</b>
    <?php
    if (empty($data->cover_uri)) {
        // 这里输出默认图片
        echo CHtml::encode($data->cover_uri);
    } else {

        $htmlImg = CHtml::image(Ys::thumbUrl($data->cover_uri, 200, 200), $data->caption, array(
            'width' => '100px',
            'height' => '100px',

        ));
        echo CHtml::link($htmlImg, array('sysPhoto/admin', 'albumId' => $data->id));
    }
    ?>
    <br/>

    <b><?php echo CHtml::encode($data->getAttributeLabel('location')); ?>:</b>
    <?php echo CHtml::encode($data->location); ?>
    <br/>

    <b><?php echo CHtml::encode($data->getAttributeLabel('description')); ?>:</b>
    <?php echo CHtml::encode($data->description); ?>
    <br/>

    <b><?php echo CHtml::encode($data->getAttributeLabel('type')); ?>:</b>
    <?php echo CHtml::encode($data->type); ?>
    <br/>

    <b><?php echo CHtml::encode($data->getAttributeLabel('uid')); ?>:</b>
    <?php echo CHtml::encode($data->uid); ?>
    <br/>

    <?php /*
	<b><?php echo CHtml::encode($data->getAttributeLabel('status')); ?>:</b>
	<?php echo CHtml::encode($data->status); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('create_time')); ?>:</b>
	<?php echo CHtml::encode($data->create_time); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('obj_count')); ?>:</b>
	<?php echo CHtml::encode($data->obj_count); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('last_obj_id')); ?>:</b>
	<?php echo CHtml::encode($data->last_obj_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('allow_view')); ?>:</b>
	<?php echo CHtml::encode($data->allow_view); ?>
	<br />

	*/
    ?>

    <?php echo CHtml::checkBox('ids[]', false, array('class' => 'batch-op-item', 'value' => $data->id)); ?>
    <?php $this->widget('bootstrap.widgets.TbButtonGroup', array(
        'buttons' => array(
            array('label' => CHtml::encode('查看'), 'url' => array('sysPhoto/admin', 'albumId' => $data->id)),
            array('label' => CHtml::encode('编辑'), 'url' => array('update', 'id' => $data->id)),
            array('label' => CHtml::encode('上传'), 'url' => array('upload', 'albumId' => $data->id)),
            array('label' => CHtml::encode('删除'), 'url' => array('delete', 'id' => $data->id), 'htmlOptions' => array('class' => 'delete')),
        ),
    ));
    ?>
</li>