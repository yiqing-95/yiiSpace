<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm',array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); ?>

	<?php echo $form->textFieldRow($model,'id',array('class'=>'span5','maxlength'=>10)); ?>

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
			'buttonType' => 'submit',
			'type'=>'primary',
			'label'=>'Search',
		)); ?>
	</div>

<?php $this->endWidget(); ?>
