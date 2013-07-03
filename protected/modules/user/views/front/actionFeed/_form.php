<div class="form well">
    <?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm',array(
	'id'=>'action-feed-form',
	'enableAjaxValidation'=>false,
        'method'=>'post',
	'htmlOptions'=>array(
		'enctype'=>'multipart/form-data'
	)
)); ?>
    <fieldset>
        <legend>
            <p class="note">Fields with <span class="required">*</span> are required.</p>
        </legend>

        <?php echo $form->errorSummary($model,'Opps!!!', null,array('class'=>'alert alert-error row')); ?>

        <div class="control-group">
            <div class="span4">

                                    <?php echo $form->textFieldRow($model,'uid',array('class'=>'span5')); ?>

                                        <?php echo $form->textFieldRow($model,'action_type',array('class'=>'span5')); ?>

                                        <?php echo $form->textFieldRow($model,'action_time',array('class'=>'span5')); ?>

                                        <?php echo $form->textAreaRow($model,'data',array('rows'=>6, 'cols'=>50, 'class'=>'span8')); ?>

                                        <?php echo $form->textFieldRow($model,'object_type',array('class'=>'span5','maxlength'=>25)); ?>

                                        <?php echo $form->textFieldRow($model,'object_id',array('class'=>'span5')); ?>

                                        <?php echo $form->textFieldRow($model,'target_type',array('class'=>'span5','maxlength'=>25)); ?>

                                        <?php echo $form->textFieldRow($model,'target_id',array('class'=>'span5')); ?>

                                        <?php echo $form->textFieldRow($model,'target_owner',array('class'=>'span5')); ?>

                                </div>
        </div>

        <div class="form-actions">
            <?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
        </div>
    </fieldset>

    <?php $this->endWidget(); ?>

</div>
