<?php $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
    'id' => 'admin-user-form',
    'enableAjaxValidation' => false,
    'type' => 'horizontal',
)); ?>

<p class="help-block">Fields with <span class="required">*</span> are required.</p>

<?php echo $form->errorSummary($model); ?>

<?php echo $form->textFieldRow($model, 'username', array('class' => 'span5', 'maxlength' => 50)); ?>

<?php echo $form->passwordFieldRow($model, 'password', array('class' => 'span5', 'maxlength' => 40)); ?>

<?php echo $form->textFieldRow($model, 'name', array('class' => 'span5', 'maxlength' => 50)); ?>

<?php echo $form->textFieldRow($model, 'encrypt', array('class' => 'span5', 'maxlength' => 6)); ?>

<?php
// 这里可能做到很复杂  只允许当前用户的角色集中
$criteria = new CDbCriteria();
$criteria->index = 'id';
$criteria->select = 'id,name';
$criteria->order = 'id DESC';
echo $form->dropDownListRow($model, 'role_id',
    CHtml::listData(AdminRole::model()->findAll($criteria), 'id', 'name'),
    array('class' => 'span5', 'prompt' => 'Selecting', 'maxlength' => 10)); ?>

<?php // echo $form->textFieldRow($model, 'disabled', array('class' => 'span5'));
    echo  $form->radioButtonListInlineRow($model, 'disabled', array(
        0 => Yii::t('base', 'False'),
        1 => Yii::t('base', 'True')
    ));
?>

<?php echo $form->textAreaRow($model, 'setting', array('rows' => 6, 'cols' => 50, 'class' => 'span8')); ?>

<?php //echo $form->textFieldRow($model, 'create_time', array('class' => 'span5', 'maxlength' => 10)); ?>

<?php //echo $form->textFieldRow($model, 'update_time', array('class' => 'span5', 'maxlength' => 10)); ?>

<div class="form-actions">
    <?php $this->widget('bootstrap.widgets.TbButton', array(
    'buttonType' => 'submit',
    'type' => 'primary',
    'label' => $model->isNewRecord ? 'Create' : 'Save',
)); ?>
</div>

<?php $this->endWidget(); ?>
