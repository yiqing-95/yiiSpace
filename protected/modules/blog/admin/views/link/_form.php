<?php $form=$this->beginWidget('bootstrap.widgets.BootActiveForm',array(
	'id'=>'link-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="help-block">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<?php echo $form->textFieldRow($model,'sitename',array('class'=>'span5','maxlength'=>128)); ?>

	<?php echo $form->textFieldRow($model,'logo',array('class'=>'span5','maxlength'=>128)); ?>

	<?php echo $form->textFieldRow($model,'siteurl',array('class'=>'span5','maxlength'=>255)); ?>

	<?php echo $form->textFieldRow($model,'description',array('class'=>'span5','maxlength'=>255)); ?>

	<?php echo $form->textFieldRow($model,'target',array('class'=>'span5','maxlength'=>7)); ?>

	<?php echo $form->textFieldRow($model,'status',array('class'=>'span5','maxlength'=>11)); ?>

	<?php echo $form->textFieldRow($model,'position',array('class'=>'span5','maxlength'=>11)); ?>

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
