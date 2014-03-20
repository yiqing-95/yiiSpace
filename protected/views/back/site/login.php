<?php
$this->pageTitle = Yii::app()->name . ' - Login';
$this->breadcrumbs = array(
    'Login',
);
?>


<div class="form">
    <?php $form = $this->beginWidget('CActiveForm', array(
    'id' => 'login-form',
    'enableClientValidation' => true,
    'clientOptions' => array(
        'validateOnSubmit' => true,
    ),
)); ?>
    <fieldset>
<p>
    <i class="icon-user"></i>
     <strong><?php echo Yii::app()->name ; ?></strong> &nbsp;
    后台管理系统

</p>


        <div class="row">
            <?php echo $form->labelEx($model, 'username'); ?>
            <?php echo $form->textField($model, 'username'); ?>
            <?php echo $form->error($model, 'username'); ?>
        </div>

        <div class="row">
            <?php echo $form->labelEx($model, 'password'); ?>
            <?php echo $form->passwordField($model, 'password'); ?>
            <?php echo $form->error($model, 'password'); ?>

        </div>
        <?php if(CCaptcha::checkRequirements()): ?>
        <div class="row">
            <?php // echo $form->labelEx($model,'verifyCode'); ?>
            <div>
                <?php $this->widget('CCaptcha'); ?>
                <?php echo $form->textField($model,'verifyCode'); ?>
            </div>

            <?php echo $form->error($model,'verifyCode'); ?>
        </div>
        <?php endif; ?>
        <!--
        <?php /* ?>
        <div class="row rememberMe">
            <?php echo $form->checkBox($model, 'rememberMe'); ?>
            <?php echo $form->label($model, 'rememberMe'); ?>
            <?php echo $form->error($model, 'rememberMe'); ?>
         <?php */ ?>
        </div> -->

        <div class="row buttons">
            <?php // echo CHtml::submitButton('Login'); ?>
            <?php $this->widget('bootstrap.widgets.TbButton', array('buttonType' => 'submit', 'label' => 'Login')); ?>
        </div>

    </fieldset>

    <?php $this->endWidget(); ?>
</div><!-- form -->
