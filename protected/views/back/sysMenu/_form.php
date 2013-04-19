<?php $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
    'id' => 'sys-menu-form',
    'enableAjaxValidation' => false,
    'type' => 'horizontal',
)); ?>

<p class="help-block">Fields with <span class="required">*</span> are required.</p>

<?php echo $form->errorSummary($model); ?>

<?php echo $form->textFieldRow($model, 'group_code', array('class' => 'span5', 'maxlength' => 25)); ?>

<?php echo $form->textFieldRow($model, 'label', array('class' => 'span5', 'maxlength' => 255)); ?>

<?php echo $form->textFieldRow($model, 'url', array('class' => 'span5', 'maxlength' => 255)); ?>

<?php echo $form->textAreaRow($model, 'params', array('rows' => 6, 'cols' => 50, 'class' => 'span8')); ?>

<?php echo $form->textAreaRow($model, 'ajaxoptions', array('rows' => 6, 'cols' => 50, 'class' => 'span8')); ?>

<?php echo $form->textAreaRow($model, 'htmloptions', array('rows' => 6, 'cols' => 50, 'class' => 'span8')); ?>

<?php echo $form->textFieldRow($model, 'is_visible', array('class' => 'span5')); ?>



<?php echo $form->textFieldRow($model, 'label_en', array('class' => 'span5', 'maxlength' => 125)); ?>

<?php echo $form->textFieldRow($model, 'link_to', array('class' => 'span5', 'maxlength' => 60)); ?>

<div class="form-actions">
    <?php $this->widget('bootstrap.widgets.TbButton', array(
        'buttonType' => 'submit',
        'type' => 'primary',
        'label' => $model->isNewRecord ? 'Create' : 'Save',
    )); ?>
</div>

<?php $this->endWidget(); ?>
