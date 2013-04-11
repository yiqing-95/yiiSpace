<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm',array(
	'id'=>'news-category-form',
	'enableAjaxValidation'=>false,
	 'type'=>'horizontal',
)); ?>

	<p class="help-block">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<?php echo $form->textFieldRow($model,'root',array('class'=>'span5','maxlength'=>10)); ?>

	<?php echo $form->textFieldRow($model,'lft',array('class'=>'span5','maxlength'=>10)); ?>

	<?php echo $form->textFieldRow($model,'rgt',array('class'=>'span5','maxlength'=>10)); ?>

	<?php echo $form->textFieldRow($model,'level',array('class'=>'span5')); ?>

	<?php echo $form->textFieldRow($model,'name',array('class'=>'span5','maxlength'=>255)); ?>

	<?php echo $form->textFieldRow($model,'enable',array('class'=>'span5')); ?>

	<?php echo $form->textFieldRow($model,'group_code',array('class'=>'span5','maxlength'=>100)); ?>

	<?php echo $form->textFieldRow($model,'mbr_count',array('class'=>'span5')); ?>

	<?php echo $form->textFieldRow($model,'link_to',array('class'=>'span5','maxlength'=>60)); ?>

	<div class="form-actions">
		<?php $this->widget('bootstrap.widgets.TbButton', array(
			'buttonType'=>'submit',
			'type'=>'primary',
			'label'=>$model->isNewRecord ? 'Create' : 'Save',
		)); ?>
	</div>

<?php $this->endWidget(); ?>
