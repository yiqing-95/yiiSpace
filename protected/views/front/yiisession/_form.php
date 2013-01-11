<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm',array(
	'id'=>'yiisession-form',
	'type'=> 'horizontal', // TbActiveForm::TYPE_HORIZONTAL,
	'enableAjaxValidation'=>false,
	'enableClientValidation'=>true,
    //'focus'=>array($model,''),
	 'htmlOptions' => array(
                    // 'class' => ' form-horizontal'
                    'class'=>'well',
                    ),
     'clientOptions' => array(
                    'validateOnSubmit'=>true
                    ),

)); ?>

	<p class="help-block">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

 <fieldset>

     <legend>Legend</legend>

	<?php echo $form->textFieldRow($model,'id',array('class'=>'span5','maxlength'=>32)); ?>

	<?php echo $form->textFieldRow($model,'expire',array('class'=>'span5')); ?>

	<?php echo $form->textFieldRow($model,'data',array('class'=>'span5')); ?>

	<?php echo $form->textFieldRow($model,'user_id',array('class'=>'span5')); ?>

	<div class="form-actions">
		<?php $this->widget('bootstrap.widgets.TbButton', array(
			'buttonType'=>'submit',
			'type'=>'primary',
			'label'=>$model->isNewRecord ? 'Create' : 'Save',
		)); ?>
	</div>

     </fieldset>
<?php $this->endWidget(); ?>
