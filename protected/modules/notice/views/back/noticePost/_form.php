<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm',array(
	'id'=>'notice-post-form',
	'enableAjaxValidation'=>false,
	 'type'=>'horizontal',
)); ?>

	<p class="help-block">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<?php
echo $form->dropDownListRow($model, 'cate_id',NoticeCategory::getCategories4select()  );
 //echo $form->textFieldRow($model,'cate_id',array('class'=>'span5'));
?>

	<?php echo $form->textFieldRow($model,'title',array('class'=>'span5','maxlength'=>120)); ?>

	<?php echo $form->textAreaRow($model,'content',array('rows'=>6, 'cols'=>50, 'class'=>'span8')); ?>

	<?php echo $form->hiddenField($model,'create_time',array('class'=>'span5')); ?>

	<?php echo $form->hiddenField($model,'creator_id',array('class'=>'span5')); ?>

	<div class="form-actions">
		<?php $this->widget('bootstrap.widgets.TbButton', array(
			'buttonType'=>'submit',
			'type'=>'primary',
			'label'=>$model->isNewRecord ? 'Create' : 'Save',
		)); ?>
	</div>

<?php $this->endWidget(); ?>
