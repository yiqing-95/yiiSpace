<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm',array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); ?>

	<?php echo $form->textFieldRow($model,'object_name',array('class'=>'span5','maxlength'=>50)); ?>

	<?php echo $form->textFieldRow($model,'object_id',array('class'=>'span5')); ?>

	<?php echo $form->textFieldRow($model,'cmt_id',array('class'=>'span5')); ?>

	<?php echo $form->textFieldRow($model,'cmt_parent_id',array('class'=>'span5')); ?>

	<?php echo $form->textFieldRow($model,'author_id',array('class'=>'span5')); ?>

	<?php echo $form->textFieldRow($model,'user_name',array('class'=>'span5','maxlength'=>128)); ?>

	<?php echo $form->textFieldRow($model,'user_email',array('class'=>'span5','maxlength'=>128)); ?>

	<?php echo $form->textAreaRow($model,'cmt_text',array('rows'=>6, 'cols'=>50, 'class'=>'span8')); ?>

	<?php echo $form->textFieldRow($model,'create_time',array('class'=>'span5')); ?>

	<?php echo $form->textFieldRow($model,'update_time',array('class'=>'span5')); ?>

	<?php echo $form->textFieldRow($model,'status',array('class'=>'span5')); ?>

	<?php echo $form->textFieldRow($model,'replies',array('class'=>'span5')); ?>

	<?php echo $form->textFieldRow($model,'mood',array('class'=>'span5')); ?>

	<div class="form-actions">
		<?php $this->widget('bootstrap.widgets.TbButton', array(
			'buttonType' => 'submit',
			'type'=>'primary',
			'label'=>'Search',
		)); ?>
	</div>

<?php $this->endWidget(); ?>
