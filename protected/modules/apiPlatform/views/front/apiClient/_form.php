<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm',array(
	'id'=>'api-client-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="help-block">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<?php echo $form->textFieldRow($model,'app_id',array('class'=>'span5','maxlength'=>120)); ?>

	<?php echo $form->textFieldRow($model,'app_key',array('class'=>'span5','maxlength'=>64)); ?>

	<?php echo $form->textFieldRow($model,'app_name',array('class'=>'span5','maxlength'=>120)); ?>

	<?php echo $form->textFieldRow($model,'app_domain',array('class'=>'span5','maxlength'=>255)); ?>

	<?php echo $form->textFieldRow($model,'app_description',array('class'=>'span5','maxlength'=>255)); ?>

	<?php echo $form->textFieldRow($model,'status',array('class'=>'span5')); ?>

	<?php echo $form->textFieldRow($model,'create_time',array('class'=>'span5')); ?>

	<?php echo $form->textFieldRow($model,'update_time',array('class'=>'span5')); ?>

	<?php echo $form->textFieldRow($model,'modifier_id',array('class'=>'span5')); ?>

	<div class="form-actions">
		<?php $this->widget('bootstrap.widgets.TbButton', array(
			'buttonType'=>'submit',
			'type'=>'primary',
			'label'=>$model->isNewRecord ? 'Create' : 'Save',
		)); ?>
	</div>

<?php $this->endWidget(); ?>
