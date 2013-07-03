<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm',array(
	'id'=>'admin-menu-form',
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

	<?php echo $form->hiddenField($model,'root',array('class'=>'span5','maxlength'=>10)); ?>

	<?php echo $form->hiddenField($model,'lft',array('class'=>'span5','maxlength'=>10)); ?>

	<?php echo $form->hiddenField($model,'rgt',array('class'=>'span5','maxlength'=>10)); ?>

	<?php echo $form->hiddenField($model,'level',array('class'=>'span5')); ?>

	<?php echo $form->textFieldRow($model,'label',array('class'=>'span5','maxlength'=>255)); ?>

	<?php echo $form->textFieldRow($model,'url',array('class'=>'span5','maxlength'=>255)); ?>

	<?php echo $form->textAreaRow($model,'params',array('rows'=>6, 'cols'=>50, 'class'=>'span8')); ?>

    <?php //echo $form->textFieldRow($model,'is_visible',array('class'=>'span5')); ?>
    <?php echo $form->radioButtonListRow($model, 'is_visible', array(
       '1'=> '启用',
       '0'=> '禁用',
    )); ?>

    <?php echo $form->textFieldRow($model,'group_code',array('class'=>'span5','maxlength'=>25)); ?>

	<?php echo $form->textAreaRow($model,'ajaxoptions',array('rows'=>6, 'cols'=>50, 'class'=>'span8')); ?>

	<?php echo $form->textAreaRow($model,'htmloptions',array('rows'=>6, 'cols'=>50, 'class'=>'span8')); ?>



	<?php echo $form->hiddenField($model,'uid',array('class'=>'span5')); ?>



	<div class="form-actions">
		<?php $this->widget('bootstrap.widgets.TbButton', array(
			'buttonType'=>'submit',
			'type'=>'primary',
			'label'=>$model->isNewRecord ? 'Create' : 'Save',
		)); ?>
	</div>

<?php $this->endWidget(); ?>
