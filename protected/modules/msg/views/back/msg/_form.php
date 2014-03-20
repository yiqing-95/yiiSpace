<?php $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
    'id' => 'msg-form',
    'enableAjaxValidation' => false,
    'type' => 'horizontal',
)); ?>

<p class="help-block">Fields with <span class="required">*</span> are required.</p>

<?php echo $form->errorSummary($model); ?>

<?php echo $form->dropDownListRow($model, 'type', Msg::getMsgTypeOptions(), array('class' => 'span5', 'maxlength' => 50)); ?>

<?php echo $form->textFieldRow($model, 'toUserName', array('class' => 'span5')); ?>
<?php echo $form->hiddenField($model, 'to_id', array('class' => 'span5')); ?>

<?php echo $form->textFieldRow($model, 'uid', array('class' => 'span5')); ?>

<?php echo $form->textAreaRow($model, 'data', array('rows' => 6, 'cols' => 50, 'class' => 'span8')); ?>

<?php // echo $form->textFieldRow($model,'type',array('class'=>'span5','maxlength'=>50)); ?>


<?php echo $form->textFieldRow($model, 'snd_type', array('class' => 'span5')); ?>

<?php echo $form->textFieldRow($model, 'snd_status', array('class' => 'span5')); ?>

<?php echo $form->textFieldRow($model, 'priority', array('class' => 'span5')); ?>



<?php echo $form->textFieldRow($model, 'msg_pid', array('class' => 'span5', 'maxlength' => 20)); ?>

<?php echo $form->textFieldRow($model, 'create_time', array('class' => 'span5')); ?>

<?php echo $form->textFieldRow($model, 'sent_time', array('class' => 'span5')); ?>

<?php echo $form->textFieldRow($model, 'delete_time', array('class' => 'span5')); ?>

<div class="form-actions">
    <?php $this->widget('bootstrap.widgets.TbButton', array(
        'buttonType' => 'submit',
        'type' => 'primary',
        'label' => $model->isNewRecord ? 'Create' : 'Save',
    )); ?>
</div>

<?php $this->endWidget(); ?>
