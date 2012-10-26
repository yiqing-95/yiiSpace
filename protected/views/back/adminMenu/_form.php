<div class="form well">
    <?php $form = $this->beginWidget('foundation.widgets.FounActiveForm', array(
    'id' => 'admin-menu-form',
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


                <?php echo $form->textFieldRow($model, 'label', array('class' => 'span5', 'maxlength' => 255)); ?>

                <?php echo $form->textFieldRow($model, 'url', array('class' => 'span5', 'maxlength' => 255)); ?>

                <?php echo $form->textAreaRow($model, 'params', array('rows' => 6, 'cols' => 50, 'class' => 'span8')); ?>

                <?php echo $form->textAreaRow($model, 'ajaxoptions', array('rows' => 6, 'cols' => 50, 'class' => 'span8')); ?>

                <?php echo $form->textAreaRow($model, 'htmloptions', array('rows' => 6, 'cols' => 50, 'class' => 'span8')); ?>

                <?php echo $form->textFieldRow($model, 'is_visible', array('class' => 'span5')); ?>


                <?php echo $form->textFieldRow($model, 'group_code', array('class' => 'span5', 'maxlength' => 25)); ?>

            </div>
        </div>

        <div class="form-actions">
            <?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
        </div>
    </fieldset>

    <?php $this->endWidget(); ?>

</div>
