<?php
$this->breadcrumbs=array(
        'Oauth客户端'=>array('/api/client'),
        'WEB',
);
?>


<div class="form">
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'login-form',
	'enableAjaxValidation'=>true,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<div class="row">
		<?php echo $form->labelEx($model,'username'); ?>
		<?php echo $form->textField($model,'username'); ?>
		<?php echo $form->error($model,'username'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'password'); ?>
		<?php echo $form->passwordField($model,'password'); ?>
		<?php echo $form->error($model,'password'); ?>
		<p class="hint">
			Hint: You may login with <tt>demo/demo</tt>.
		</p>
	</div>

	<div class="row submit">
		<?php echo CHtml::submitButton('登录并授权',array('class'=>'btn primary')); ?>
	</div>

<?php $this->endWidget(); ?>
    </div>
