<div class="form well">
    <?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm',array(
	'id'=>'relationship-form',
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

                                    <?php echo $form->textFieldRow($model,'type',array('class'=>'span5')); ?>

                                        <?php echo $form->textFieldRow($model,'user_a',array('class'=>'span5')); ?>

                                        <?php echo $form->textFieldRow($model,'user_b',array('class'=>'span5')); ?>

                                        <?php echo $form->textFieldRow($model,'accepted',array('class'=>'span5')); ?>

                                </div>
        </div>

        <div class="form-actions">
            <?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
        </div>
    </fieldset>

    <?php $this->endWidget(); ?>

</div>
