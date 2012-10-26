<div class="form well">
    <?php $form = $this->beginWidget('foundation.widgets.FounActiveForm', array(
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
        </legend>
        <?php echo $form->errorSummary($model, 'Opps!!!', null, array('class' => 'alert alert-error row')); ?>
        <div class="control-group">
            <div class="span4">
                <?php echo $form->fileFieldRow($model, 'photo', array('class' => 'span5','name'=>'photo')); ?>
            </div>
        </div>
        <div class="control-group">
            <div class="span4">
                <?php
                  if(!empty($model->photo)){
                      echo CHtml::image(Yii::app()->request->getBaseUrl().'/'.$model->photo,'user profile');
                      echo $model->photo ;
                  }
                ?>
            </div>
        </div>
        <div class="form-actions">
            <?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
        </div>
    </fieldset>
    <?php $this->endWidget(); ?>
</div>
