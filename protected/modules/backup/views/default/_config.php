<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'config-form-_config-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'host'); ?>
		<?php echo $form->textField($model,'host'); ?>
		<?php echo $form->error($model,'host'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'dbname'); ?>
		<?php echo $form->textField($model,'dbname'); ?>
		<?php echo $form->error($model,'dbname'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'username'); ?>
		<?php echo $form->textField($model,'username'); ?>
		<?php echo $form->error($model,'username'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'password'); ?>
		<?php echo $form->passwordField($model,'password'); ?>
		<?php echo $form->error($model,'password'); ?>
	</div>

	<div class="row">
		<?php echo $form->checkBox($model,'clean_install'); ?>	
		<?php echo $form->labelEx($model,'clean_install'); ?>
		<?php echo $form->error($model,'clean_install'); ?>
	</div>


	<div class="row buttons">
		<?php echo CHtml::submitButton('Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->