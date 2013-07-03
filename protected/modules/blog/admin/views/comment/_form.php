<?php $form=$this->beginWidget('bootstrap.widgets.BootActiveForm',array(
	'id'=>'comment-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="help-block">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<?php echo $form->textAreaRow($model,'content',array('rows'=>6, 'cols'=>50, 'class'=>'span8')); ?>

	<?php echo $form->textFieldRow($model,'status',array('class'=>'span5','maxlength'=>11)); ?>

	<?php echo $form->textFieldRow($model,'created',array('class'=>'span5','maxlength'=>11)); ?>

	<?php echo $form->textFieldRow($model,'author',array('class'=>'span5','maxlength'=>128)); ?>

	<?php echo $form->textFieldRow($model,'email',array('class'=>'span5','maxlength'=>128)); ?>

	<?php echo $form->textFieldRow($model,'url',array('class'=>'span5','maxlength'=>128)); ?>

	<?php echo $form->textFieldRow($model,'ip',array('class'=>'span5','maxlength'=>128)); ?>

	<?php echo $form->textFieldRow($model,'post_id',array('class'=>'span5','maxlength'=>11)); ?>

	<div class="form-actions">
		<?php $this->widget('bootstrap.widgets.BootButton', array(
			'buttonType'=>'submit',
			'type'=>'primary',
			'label'=>$model->isNewRecord ? Yii::t('backend', 'Create') : Yii::t('backend', 'Save'),
		)); ?>
	</div>

<?php $this->endWidget(); ?>
