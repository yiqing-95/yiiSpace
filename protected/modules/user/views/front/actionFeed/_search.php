<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm',array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
	'htmlOptions'=>array('class'=>'well'),
)); ?>

	<?php echo $form->textFieldRow($model,'id',array('class'=>'span5','maxlength'=>20)); ?>

	<?php echo $form->textFieldRow($model,'uid',array('class'=>'span5')); ?>

	<?php echo $form->textFieldRow($model,'action_type',array('class'=>'span5')); ?>

	<?php echo $form->textFieldRow($model,'action_time',array('class'=>'span5')); ?>

	<?php echo $form->textAreaRow($model,'data',array('rows'=>6, 'cols'=>50, 'class'=>'span8')); ?>

	<?php echo $form->textFieldRow($model,'object_type',array('class'=>'span5','maxlength'=>25)); ?>

	<?php echo $form->textFieldRow($model,'object_id',array('class'=>'span5')); ?>

	<?php echo $form->textFieldRow($model,'target_type',array('class'=>'span5','maxlength'=>25)); ?>

	<?php echo $form->textFieldRow($model,'target_id',array('class'=>'span5')); ?>

	<?php echo $form->textFieldRow($model,'target_owner',array('class'=>'span5')); ?>

	<div class="actions">
		<?php echo CHtml::submitButton('Search',array('class'=>'btn primary')); ?>
	</div>

<?php $this->endWidget(); ?>
