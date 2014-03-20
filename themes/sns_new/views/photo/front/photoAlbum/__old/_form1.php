<?php $form=$this->beginWidget('CActiveForm',array(
	'id'=>'photo-album-form',
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

	<?php  echo $form->hiddenField($model,'uid',array('class'=>'span3')); ?>

	<?php echo $form->textFieldRow($model,'name',array('class'=>'span4','maxlength'=>25)); ?>

     <?php echo $form->textareaRow($model,'desc',array('class'=>'span4','maxlength'=>25)); ?>
	<?php //echo $form->textFieldRow($model,'create_time',array('class'=>'span5','maxlength'=>11)); ?>

	<?php //echo $form->textFieldRow($model,'update_time',array('class'=>'span5','maxlength'=>11)); ?>

	<?php //echo $form->textFieldRow($model,'cover_uri',array('class'=>'span5','maxlength'=>255)); ?>

	<?php //echo $form->textFieldRow($model,'mbr_count',array('class'=>'span5')); ?>

	<?php //echo $form->textFieldRow($model,'views',array('class'=>'span5')); ?>

	<?php //echo $form->textFieldRow($model,'status',array('class'=>'span5')); ?>

	<?php //echo $form->textFieldRow($model,'is_hot',array('class'=>'span5','maxlength'=>1)); ?>

	<?php echo $form->dropdownListRow($model,'privacy',PhotoAlbum::privacyGroup()); ?>

	<?php //echo $form->textAreaRow($model,'privacy_data',array('rows'=>6, 'cols'=>50, 'class'=>'span8')); ?>

	<div class="form-actions">
		<?php $this->widget('bootstrap.widgets.TbButton', array(
			'buttonType'=>'submit',
			'type'=>'primary',
			'label'=>$model->isNewRecord ? 'Create' : 'Save',
		)); ?>
	</div>

     </fieldset>
<?php $this->endWidget(); ?>
