<div class="form well">
    <?php $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
    'id' => 'relationship-form',
    'enableAjaxValidation' => false,
    'method' => 'post',
    'htmlOptions' => array(
        'enctype' => 'multipart/form-data'
    )
)); ?>
    <fieldset>
        <legend>
            <p class="note">please upload you photo </p>
            <?php
            if(!empty($model->photo)){
                echo "<span class='span3'>当前图像：</span>";
                echo CHtml::image(Yii::app()->request->getBaseUrl().'/'.$model->photo,'user profile',array('class'=>'thumbnail span3'));
            }
            ?>
        </legend>
        <?php echo $form->errorSummary($model, 'Opps!!!', null, array('class' => 'alert alert-error row')); ?>
                <?php echo $form->fileFieldRow($model, 'photo', array('class' => 'span5','name'=>'photo')); ?>

        <div class="form-actions">
            <?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
        </div>
    </fieldset>
    <?php $this->endWidget(); ?>
</div>
