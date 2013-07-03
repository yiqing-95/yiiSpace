<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm',array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); ?>

	<?php echo $form->textFieldRow($model,'id',array('class'=>'span5')); ?>

	<?php echo $form->textFieldRow($model,'uid',array('class'=>'span5')); ?>

	<?php echo $form->textFieldRow($model,'name',array('class'=>'span5','maxlength'=>255)); ?>

	<?php echo $form->textFieldRow($model,'create_time',array('class'=>'span5','maxlength'=>11)); ?>

	<?php echo $form->textFieldRow($model,'update_time',array('class'=>'span5','maxlength'=>11)); ?>

	<?php echo $form->textFieldRow($model,'cover_uri',array('class'=>'span5','maxlength'=>255)); ?>

	<?php echo $form->textFieldRow($model,'mbr_count',array('class'=>'span5')); ?>

	<?php echo $form->textFieldRow($model,'views',array('class'=>'span5')); ?>

	<?php echo $form->textFieldRow($model,'status',array('class'=>'span5')); ?>

	<?php echo $form->textFieldRow($model,'is_hot',array('class'=>'span5','maxlength'=>1)); ?>

	<?php echo $form->textFieldRow($model,'privacy',array('class'=>'span5')); ?>

	<?php echo $form->textAreaRow($model,'privacy_data',array('rows'=>6, 'cols'=>50, 'class'=>'span8')); ?>

	<div class="form-actions">
		<?php $this->widget('bootstrap.widgets.TbButton', array(
			'buttonType' => 'submit',
			'type'=>'primary',
			'label'=>'Search',
		)); ?>
	</div>

<?php $this->endWidget(); ?>
