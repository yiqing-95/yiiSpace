<?php $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
    'id' => 'news-entry-form',
    'enableAjaxValidation' => false,
    'type' => 'horizontal',
)); ?>

<p class="help-block">Fields with <span class="required">*</span> are required.</p>

<?php echo $form->errorSummary(array($model, $newsPost)); ?>

<?php echo $form->hiddenField($model, 'id', array('class' => 'span5', 'maxlength' => 10)); ?>

<?php echo $form->hiddenField($model, 'creator', array('class' => 'span5')); ?>

<?php
 echo $form->dropDownListRow($model, 'cate_id',NewsCategory::getCategories4select()  );
  // $form->textFieldRow($model, 'cate_id', array('class' => 'span5')); ?>

<?php echo $form->textFieldRow($model, 'title', array('class' => 'span5', 'maxlength' => 255)); ?>

<!--以下是关联模型中的东西-->

<?php echo $form->redactorRow($newsPost, 'content', array('rows' => 30, 'class' => 'span8')); ?>

<?php echo $form->textFieldRow($newsPost, 'keywords', array('class' => 'span5', 'maxlength' => 100)); ?>

<?php echo $form->textFieldRow($newsPost, 'meta_title', array('class' => 'span5', 'maxlength' => 255)); ?>

<?php echo $form->textFieldRow($newsPost, 'meta_description', array('class' => 'span5', 'maxlength' => 255)); ?>

<?php echo $form->textFieldRow($newsPost, 'meta_keywords', array('class' => 'span5', 'maxlength' => 255)); ?>

<!--以上是关联模型中的东西/-->

<?php echo $form->hiddenField($model, 'order', array('class' => 'span5')); ?>

<?php echo $form->hiddenField($model, 'deleted', array('class' => 'span5')); ?>

<?php echo $form->hiddenField($model, 'create_time', array('class' => 'span5')); ?>

<div class="form-actions">
    <?php $this->widget('bootstrap.widgets.TbButton', array(
        'buttonType' => 'submit',
        'type' => 'primary',
        'label' => $model->isNewRecord ? 'Create' : 'Save',
    )); ?>
</div>

<?php $this->endWidget(); ?>
