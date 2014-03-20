<?php
/* @var $this GroupController */
/* @var $data Group */
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

                        </div>
                        <div class="col width-fill">
                            <div class="cell">
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
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

</div>
