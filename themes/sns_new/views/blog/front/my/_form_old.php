<?php
$this->widget('ext.ueditor.Ueditor',
    array(
        'getId' => 'Post_content',
        'UEDITOR_HOME_URL' => "/",
        'options' => 'toolbars:[["fontfamily","fontsize","forecolor","bold","italic","strikethrough","|","insertunorderedlist","insertorderedlist","blockquote","|","link","unlink","highlightcode","|","undo","redo","source"]],
                 	wordCount:false,
                 	elementPathEnabled:false,
                 	imagePath:"",
                 	initialContent:"",
                 	',
    ));
?>

<div class="form">

    <?php $form = $this->beginWidget('CActiveForm'); ?>

    <p class="note">Fields with <span class="required">*</span> are required.</p>

    <?php echo CHtml::errorSummary($model); ?>

    <div class="row">
        <?php echo $form->labelEx($model, 'title'); ?>
        <?php echo $form->textField($model, 'title', array('size' => 80, 'maxlength' => 128, 'style' => 'width: 630px;padding: 5px;')); ?>
        <?php echo $form->error($model, 'title'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model, 'category_id'); ?>
        <?php echo $form->dropDownList($model, 'category_id', Category::CategoryList()); ?>
        <?php echo $form->error($model, 'category_id'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model, 'summary'); ?>
        <?php echo CHtml::activeTextArea($model, 'summary', array('rows' => 5, 'cols' => 89, 'style' => 'width: 630px;padding: 5px;')); ?>
        <?php echo $form->error($model, 'summary'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model, 'content'); ?>
        <?php echo CHtml::activeTextArea($model, 'content', array('rows' => 10, 'cols' => 89)); ?>
        <?php echo $form->error($model, 'content'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model, 'tags'); ?>
        <?php $this->widget('CAutoComplete', array(
            'model' => $model,
            'attribute' => 'tags',
            'url' => array('suggestTags'),
            'multiple' => true,
            'htmlOptions' => array('size' => 50),
        )); ?>
        <p class="hint">Please separate different tags with commas.</p>
        <?php echo $form->error($model, 'tags'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model, 'status'); ?>
        <?php echo $form->dropDownList($model, 'status', Lookup::items('PostStatus')); ?>
        <?php echo $form->error($model, 'status'); ?>
    </div>

    <div class="row buttons">
        <?php $this->widget('zii.widgets.jui.CJuiButton', array(
            'name' => 'submit',
            'caption' => $model->isNewRecord ? 'Create' : 'Save',
            'options' => array(
                'onclick' => 'js:function(){alert("Yes");}',
            ),
        ));
        ?>
    </div>

    <?php $this->endWidget(); ?>

</div><!-- form -->
