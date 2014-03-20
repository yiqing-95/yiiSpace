

<?php $form=$this->beginWidget('CActiveForm', array(
    'id'=>'photo-album-form',
    'enableAjaxValidation'=>false,
    'htmlOptions'=>array(
        'class'=>'cell'
    ),
)); ?>

<div class="col">
    <p class="note">Fields with <span class="required">*</span> are required.</p>

    <?php echo $form->errorSummary($model); ?>
</div>


<div class="col">
    <div class="col width-1of4">
        <div class="cell">
            <?php echo $form->labelEx($model,'uid'); ?>
        </div>
    </div>
    <div class="col width-2of4">
        <div class="cell">
            <?php echo $form->textField($model,'uid'); ?>
        </div>
    </div>

    <div class="col width-fill">
        <div class="cell">
            <?php echo $form->error($model,'uid'); ?>
        </div>
    </div>

</div>

<div class="col">
    <div class="col width-1of4">
        <div class="cell">
            <?php echo $form->labelEx($model,'name'); ?>
        </div>
    </div>
    <div class="col width-2of4">
        <div class="cell">
            <?php echo $form->textField($model,'name',array('size'=>60,'maxlength'=>60)); ?>
        </div>
    </div>

    <div class="col width-fill">
        <div class="cell">
            <?php echo $form->error($model,'name'); ?>
        </div>
    </div>

</div>

<div class="col">
    <div class="col width-1of4">
        <div class="cell">
            <?php echo $form->labelEx($model,'desc'); ?>
        </div>
    </div>
    <div class="col width-2of4">
        <div class="cell">
            <?php echo $form->textField($model,'desc',array('size'=>60,'maxlength'=>255)); ?>
        </div>
    </div>

    <div class="col width-fill">
        <div class="cell">
            <?php echo $form->error($model,'desc'); ?>
        </div>
    </div>

</div>



<div class="col">
    <div class="col width-1of4">
        <div class="cell">
            <?php echo $form->labelEx($model,'cover_uri'); ?>
        </div>
    </div>
    <div class="col width-2of4">
        <div class="cell">
            <?php echo $form->textField($model,'cover_uri',array('size'=>60,'maxlength'=>255)); ?>
        </div>
    </div>

    <div class="col width-fill">
        <div class="cell">
            <?php echo $form->error($model,'cover_uri'); ?>
        </div>
    </div>

</div>


<div class="col">
    <div class="col width-1of4">
        <div class="cell">
            <?php echo $form->labelEx($model,'privacy'); ?>
        </div>
    </div>
    <div class="col width-2of4">
        <div class="cell">
            <?php echo $form->dropdownList($model,'privacy',PhotoAlbum::privacyGroup()); ?>
            <?php // echo $form->textField($model,'privacy'); ?>
        </div>
    </div>

    <div class="col width-fill">
        <div class="cell">
            <?php echo $form->error($model,'privacy'); ?>
        </div>
    </div>

</div>


<div class="col">
    <div class="col width-1of4">
    </div>
    <div class="col width-fill">
        <div class="cell">
            <?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save',array('class'=>'button')); ?>
        </div>
    </div>
</div>
<?php $this->endWidget(); ?>



