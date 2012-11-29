<?php $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
    'id' => 'photo-form',
    'type' => 'horizontal', // TbActiveForm::TYPE_HORIZONTAL,
    'enableAjaxValidation' => false,
    'enableClientValidation' => true,
    //'focus'=>array($model,''),
    'htmlOptions' => array(
        // 'class' => ' form-horizontal'
        'class' => 'well',
        'enctype' => 'multipart/form-data'
    ),
    'clientOptions' => array(
        'validateOnSubmit' => true
    ),

)); ?>

<p class="help-block">Fields with <span class="required">*</span> are required.</p>

<?php echo $form->errorSummary($model); ?>

<fieldset>

    <legend>Legend</legend>

    <?php echo $form->hiddenField($model, 'uid', array('class' => 'span5', 'maxlength' => 10)); ?>

    <?php echo $form->dropDownListRow($model, 'album_id', CHtml::listData(PhotoAlbum::getUserAlbum($model->uid), 'id', 'name')); ?>

    <?php echo $form->textFieldRow($model, 'title', array('class' => 'span5', 'maxlength' => 255)); ?>

    <?php echo $form->textAreaRow($model, 'desc', array('rows' => 6, 'cols' => 50, 'class' => 'span8')); ?>

    <div class="control-group">
        <label class="control-label" for="">images:</label>

        <div class="controls">
            <?php $this->widget('CMultiFileUpload', array(
            'name' => 'images',
            'accept' => 'jpeg|jpg|gif|png', // useful for verifying files
            'duplicate' => 'Duplicate file!', // useful, i think
            'denied' => 'Invalid file type', // useful, i think
        ));?>
        </div>
    </div>
    <?php /*
     <?php echo $form->textFieldRow($model,'path',array('class'=>'span5','maxlength'=>255)); ?>


	<?php echo $form->textFieldRow($model,'orig_path',array('class'=>'span5','maxlength'=>255)); ?>

	<?php echo $form->textFieldRow($model,'ext',array('class'=>'span5','maxlength'=>4)); ?>

	<?php echo $form->textFieldRow($model,'size',array('class'=>'span5','maxlength'=>10)); ?>

	<?php echo $form->textFieldRow($model,'tags',array('class'=>'span5','maxlength'=>255)); ?>

	<?php echo $form->textFieldRow($model,'create_time',array('class'=>'span5')); ?>

	<?php echo $form->textFieldRow($model,'views',array('class'=>'span5')); ?>

	<?php echo $form->textFieldRow($model,'rate',array('class'=>'span5')); ?>

	<?php echo $form->textFieldRow($model,'rate_count',array('class'=>'span5')); ?>

	<?php echo $form->textFieldRow($model,'cmt_count',array('class'=>'span5')); ?>

	<?php echo $form->textFieldRow($model,'is_featured',array('class'=>'span5')); ?>

	<?php echo $form->textFieldRow($model,'status',array('class'=>'span5','maxlength'=>11)); ?>

	<?php echo $form->textFieldRow($model,'hash',array('class'=>'span5','maxlength'=>32)); ?>

	<?php echo $form->textAreaRow($model,'categories',array('rows'=>6, 'cols'=>50, 'class'=>'span8')); ?>
     */ ?>
    <div class="form-actions">
        <?php $this->widget('bootstrap.widgets.TbButton', array(
        'buttonType' => 'submit',
        'type' => 'primary',
        'label' => $model->isNewRecord ? 'Create' : 'Save',
    )); ?>
    </div>

</fieldset>
<?php $this->endWidget(); ?>
