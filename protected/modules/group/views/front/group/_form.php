<div class="form well">
    <?php $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
    'id' => 'group-form',
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

                <?php echo $form->textFieldRow($model, 'name', array('class' => 'span5', 'maxlength' => 255)); ?>

                <?php echo $form->textAreaRow($model, 'description', array('rows' => 6, 'cols' => 50, 'class' => 'span8')); ?>

                <?php echo $form->textFieldRow($model, 'creator', array('class' => 'span5')); ?>

                <?php echo $form->textFieldRow($model, 'created', array('class' => 'span5')); ?>

                <?php //echo $form->textFieldRow($model, 'type', array('class' => 'span5', 'maxlength' => 21)); ?>
                <?php echo $form->dropDownListRow($model, 'type', array_combine(Group::$types,Group::$types),array('class' => 'span5')); ?>

                <?php echo $form->textFieldRow($model, 'active', array('class' => 'span5')); ?>

            </div>
        </div>

        <div class="form-actions">
            <?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
        </div>
    </fieldset>

    <?php $this->endWidget(); ?>

</div>
