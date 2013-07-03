<?php $form=$this->beginWidget('bootstrap.widgets.BootActiveForm',array(
	'id'=>'attachment-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="help-block">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<?php echo $form->textFieldRow($model,'post_id',array('class'=>'span5','maxlength'=>11)); ?>

	<?php echo $form->textFieldRow($model,'filename',array('class'=>'span5','maxlength'=>255)); ?>

	<?php echo $form->textFieldRow($model,'filesize',array('class'=>'span5','maxlength'=>11)); ?>

	<?php echo $form->textFieldRow($model,'filepath',array('class'=>'span5','maxlength'=>255)); ?>

	<?php echo $form->textFieldRow($model,'created',array('class'=>'span5','maxlength'=>11)); ?>

	<?php echo $form->textFieldRow($model,'updated',array('class'=>'span5','maxlength'=>11)); ?>

	<div class="form-actions">
		<?php $this->widget('bootstrap.widgets.BootButton', array(
			'buttonType'=>'submit',
			'type'=>'primary',
			'label'=>$model->isNewRecord ? Yii::t('backend', 'Create') : Yii::t('backend', 'Save'),
		)); ?>
	</div>

<?php $this->endWidget(); ?>
