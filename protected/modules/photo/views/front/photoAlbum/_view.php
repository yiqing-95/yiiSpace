<div class="view">
    <li class="span3">
        <div class="thumbnail">
            <div align="center">
                <?php echo CHtml::link(CHtml::image($data->getAlbumCoverUrl(), $data->name, array()),$data->getUrl()); ?>
            </div>
            <div >
            <span class="pull-left"><?php echo $data->name,'(',$data->mbr_count,')'; ?> </span>
            <?php if (UserHelper::getIsOwnSpace()): ?>
                <?php
                $this->widget('bootstrap.widgets.TbButtonGroup', array(
                   'htmlOptions'=>array(),
                    'buttons' => array(
                        array('label' => '编辑', 'url' => array(PhotoHelper::getEditAlbumRoute(), 'id' => $data->id),
                            'htmlOptions' => array('class' => 'update btn-mini',)
                        ),
                        array('label' => '删除', 'url' => array(PhotoHelper::getDeleteAlbumRoute(), 'id' => $data->id),
                            'htmlOptions' => array('class' => 'delete btn-mini'),),
                    )));

                ?>
            </div>
            <?php endif; ?>
        </div>
    </li>


    <?php /*
	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id),array('view','id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('uid')); ?>:</b>
	<?php echo CHtml::encode($data->uid); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('name')); ?>:</b>
	<?php echo CHtml::encode($data->name); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('create_time')); ?>:</b>
	<?php echo CHtml::encode($data->create_time); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('update_time')); ?>:</b>
	<?php echo CHtml::encode($data->update_time); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('cover_uri')); ?>:</b>
	<?php echo CHtml::encode($data->cover_uri); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('mbr_count')); ?>:</b>
	<?php echo CHtml::encode($data->mbr_count); ?>
	<br />


	<b><?php echo CHtml::encode($data->getAttributeLabel('views')); ?>:</b>
	<?php echo CHtml::encode($data->views); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('status')); ?>:</b>
	<?php echo CHtml::encode($data->status); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('is_hot')); ?>:</b>
	<?php echo CHtml::encode($data->is_hot); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('privacy')); ?>:</b>
	<?php echo CHtml::encode($data->privacy); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('privacy_data')); ?>:</b>
	<?php echo CHtml::encode($data->privacy_data); ?>
	<br />

	*/ ?>

</div>