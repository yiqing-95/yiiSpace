<div class="form well">
    <?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm',array(
	'id'=>'sys-module-form',
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

                                    <?php echo $form->textFieldRow($model,'module_id',array('class'=>'span5','maxlength'=>32)); ?>

                                        <?php echo $form->textFieldRow($model,'title',array('class'=>'span5','maxlength'=>255)); ?>

                                        <?php echo $form->textFieldRow($model,'vendor',array('class'=>'span5','maxlength'=>64)); ?>

                                        <?php echo $form->textFieldRow($model,'version',array('class'=>'span5','maxlength'=>32)); ?>

                                        <?php echo $form->textFieldRow($model,'dependencies',array('class'=>'span5','maxlength'=>255)); ?>

                                        <?php echo $form->textFieldRow($model,'ctime',array('class'=>'span5','maxlength'=>11)); ?>

                                </div>
        </div>

        <div class="form-actions">
            <?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
        </div>
    </fieldset>

    <?php $this->endWidget(); ?>

</div>
