<?php
$this->pageTitle = Yii::app()->name . ' - ' . UserModule::t("Login");
$this->breadcrumbs = array(
    UserModule::t("Login"),
);

?>

<h1><?php echo UserModule::t("Login"); ?></h1>

<?php if (Yii::app()->user->hasFlash('loginMessage')): ?>

<div class="success">
    <?php echo Yii::app()->user->getFlash('loginMessage'); ?>
</div>

<?php endif; ?>

<p><?php echo UserModule::t("Please fill out the following form with your login credentials:"); ?></p>

<?php
$form = new CForm(array(
    'elements' => array(
        'username' => array(
            'type' => 'text',
            'maxlength' => 32,
        ),
        'password' => array(
            'type' => 'password',
            'maxlength' => 32,
        ),
        'rememberMe' => array(
            'type' => 'checkbox',
        )
    ),

    'buttons' => array(
        'login' => array(
            'type' => 'submit',
            'label' => 'Login',
        ),
    ),
), $model);
?>


<?php $form = $this->beginWidget('bootstrap.widgets.TbActiveForm'); ?>
<p class="note"><?php echo UserModule::t('Fields with <span class="required">*</span> are required.'); ?></p>

<?php echo CHtml::errorSummary($model); ?>

<div class="row">
    <div class="two mobile-one columns">
        <?php echo $form->labelEx($model, "username", array("class" => 'right')); ?>
    </div>
    <div class="ten mobile-three columns">
        <?php echo $form->textField($model, "username", array(
        //"placeholder" => "e.g. Home",
        "class" => 'eight')); ?>
    </div>
</div>
<div class="row">
    <div class="two mobile-one columns">
        <?php echo $form->labelEx($model, "password", array("class" => 'right')); ?>
    </div>
    <div class="ten mobile-three columns">
        <?php echo $form->passwordField($model, "password", array("class" => 'eight')); ?>
    </div>
</div>
<div class="row">
    <p class="hint">
        <?php echo CHtml::link(UserModule::t("Register"), Yii::app()->getModule('user')->registrationUrl); ?>
        | <?php echo CHtml::link(UserModule::t("Lost Password?"), Yii::app()->getModule('user')->recoveryUrl); ?>
    </p>
</div>
<div class="row">
    <div class="two mobile-one columns">
        <?php echo CHtml::activeCheckBox($model, 'rememberMe'); ?>
    </div>
    <div class="ten mobile-three columns">
        <?php echo CHtml::activeLabelEx($model, 'rememberMe'); ?>
    </div>
</div>
<div class="row submit">
    <?php echo CHtml::submitButton(UserModule::t("Login")); ?>
</div>
<?php $this->endWidget(); ?>