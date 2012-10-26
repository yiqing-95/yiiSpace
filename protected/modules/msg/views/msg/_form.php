
<div class="form well">
    <?php $form = $this->beginWidget('foundation.widgets.FounActiveForm', array(
    'id' => 'msg-form',
    'enableAjaxValidation' => false,
    'method' => 'post',
    'htmlOptions' => array(
        'enctype' => 'multipart/form-data'
    )
)); ?>
    <fieldset>
        <legend>
            <p class="note">Fields with <span class="required">*</span> are required.</p>
        </legend>

        <?php echo $form->errorSummary($model, 'Opps!!!', null, array('class' => 'alert alert-error row')); ?>

        <div class="control-group">
            <div class="span4">

                <?php echo $form->textFieldRow($model, 'sender', array('class' => 'span5')); ?>

                <?php

                echo $form->dropDownListRow($model, 'recipient',
                CHtml::listData(Relationship::getByUser(user()->getId())->getData(),'id','users_name'),
                array('class' => 'span5'));

                ?>

                <?php //echo $form->textFieldRow($model, 'recipient', array('class' => 'span5')); ?>

                <?php echo $form->textFieldRow($model, 'sent', array('class' => 'span5')); ?>

                <?php echo $form->textFieldRow($model, 'read', array('class' => 'span5')); ?>

                <?php echo $form->textFieldRow($model, 'subject', array('class' => 'span5', 'maxlength' => 255)); ?>

                <?php echo $form->textAreaRow($model, 'message', array('rows' => 6, 'cols' => 50, 'class' => 'span8')); ?>

            </div>
        </div>

        <div class="form-actions">
            <?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
        </div>
    </fieldset>

    <?php $this->endWidget(); ?>

</div>
