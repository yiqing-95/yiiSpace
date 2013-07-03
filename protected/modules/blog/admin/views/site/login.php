<?php
$this->pageTitle=Yii::app()->name . ' - Login';
$this->breadcrumbs=array(
	'Login',
);
?>

<h1><a title="基于 WindsDeng" href="http://www.dlf5.com/">林锋博客</a></h1>

<div class="form">
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'login-form',
	'enableClientValidation'=>true,
	'clientOptions'=>array(
		'validateOnSubmit'=>true,
	),
)); 
?>

	<div class="row">
		<?php echo $form->labelEx($model,'username'); ?>
		<?php echo $form->textField($model,'username',array('class'=>'input')); ?>
		<?php echo $form->error($model,'username'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'password'); ?>
		<?php echo $form->passwordField($model,'password',array('class'=>'input')); ?>
		<?php echo $form->error($model,'password'); ?>
		<p class="hint">
			Hint: You may login with  <tt>admin/123456</tt>.
		</p>
	</div>
    <div class="row">
        <div class="left rememberMe"style="margin-top: 6px;" >
            <?php echo $form->checkBox($model,'rememberMe'); ?>
            <?php echo $form->label($model,'rememberMe'); ?>
            <?php echo $form->error($model,'rememberMe'); ?>
        </div>

        <div class="left buttons" style="margin-left: 30px;" >
            <?php $this->widget('bootstrap.widgets.BootButton', array(
                'buttonType'=>'submit',
                'type'=>'primary',
                'label'=>Yii::t('backend', 'Login'),
                'loadingText'=>'Login...',
                'htmlOptions'=>array('id'=>'buttonStateful'),
            )); ?>
        </div>
        <div class="clear"></div>
    </div>
   
<?php $this->endWidget(); ?>
</div><!-- form -->

<div class="row" style="margin-left: 8px; padding-top: 10px;">
     <p id="backtoblog"><?php echo CHtml::link('← 回到 '.Yii::app()->name, $this->settings->site_url, array('title'=>'不知道自己在哪？')) ?></p>
</div>