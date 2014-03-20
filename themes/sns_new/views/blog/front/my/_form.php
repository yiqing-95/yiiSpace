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


<div class="col">
<div class="cell panel">
<div class="body">
<div class="cell">
<div class="col">
<div class="cell">

<?php $form = $this->beginWidget('CActiveForm', array(
    'id' => 'post-form',
    'enableAjaxValidation' => false,
)); ?>

<div class="col">
    <p class="note">Fields with <span class="required">*</span> are required.</p>

    <?php echo $form->errorSummary($model); ?>
</div>


<div class="col">
    <div class="col width-1of5">
        <div class="cell">
            <?php echo $form->labelEx($model, 'title'); ?>
        </div>
    </div>
    <div class="col width-2of5">
        <div class="cell">
            <?php echo $form->textField($model, 'title', array('size' => 60, 'maxlength' => 128)); ?>
        </div>
    </div>

    <div class="col width-fill">
        <div class="cell">
            <?php echo $form->error($model, 'title'); ?>
        </div>
    </div>

</div>

<div class="col">
    <div class="col width-1of5">
        <div class="cell">
            <?php echo $form->labelEx($model, 'category_id'); ?>
        </div>
    </div>
    <div class="col width-2of5">
        <div class="cell">
            <?php echo $form->dropDownList($model, 'category_id', Category::CategoryList(user()->getId())); ?>
        </div>
        <a href="<?php echo $this->createUrl('category/create'); ?>" class="create">创建分类</a>
        <?php
        $categoryListId = CHtml::activeId($model, 'category_id');
        $onCategoryCreateSuccess = <<<CB
                function(data, e){
                    var category = data.category;
                    var newOption = "<option value='"+category.id+"'>"+category.name+"</option>";

                    $("#{$categoryListId}").prepend(newOption).val(category.id);
                     //alert(data.message);
                     $.alert(data.message);
                }
CB;

        $this->widget('my.widgets.artDialog.ArtFormDialog', array(
                'link' => 'a.create',
                'options' => array(
                    'onSuccess' => 'js:' . $onCategoryCreateSuccess,
                ),
                'dialogOptions' => array(
                    'title' => '创建相册',
                    'width' => 500,
                    'height' => 370,

                )
            )
        );
        ?>

    </div>

    <div class="col width-fill">
        <div class="cell">
            <?php echo $form->error($model, 'category_id'); ?>
        </div>
    </div>

</div>

<div class="col">
    <div class="col width-1of5">
        <div class="cell">
            <?php echo $form->labelEx($model, 'content'); ?>
        </div>
    </div>
    <div class="col width-3of5">
        <div class="cell">
            <?php echo $form->textArea($model, 'content', array('rows' => 6, 'cols' => 50)); ?>
        </div>
    </div>

    <div class="col width-fill">
        <div class="cell">
            <?php echo $form->error($model, 'content'); ?>
        </div>
    </div>

</div>

<div class="col">
    <div class="col width-1of5">
        <div class="cell">
            <?php echo $form->labelEx($model, 'summary'); ?>
        </div>
    </div>
    <div class="col width-2of5">
        <div class="cell">
            <?php echo CHtml::activeTextArea($model, 'summary', array('rows' => 5, 'cols' => 60,)); ?>
        </div>
    </div>

    <div class="col width-fill">
        <div class="cell">
            <?php echo $form->error($model, 'summary'); ?>
        </div>
    </div>

</div>

<div class="col">
    <div class="col width-1of5">
        <div class="cell">
            <?php echo $form->labelEx($model, 'tags'); ?>
        </div>
    </div>
    <div class="col width-2of5">
        <div class="cell">
            <?php $this->widget('CAutoComplete', array(
                'model' => $model,
                'attribute' => 'tags',
                'url' => array('suggestTags'),
                'multiple' => true,
                'htmlOptions' => array('size' => 50),
            )); ?>
            <p class="hint">Please separate different tags with commas.</p>
        </div>
    </div>

    <div class="col width-fill">
        <div class="cell">
            <?php echo $form->error($model, 'tags'); ?>
        </div>
    </div>

</div>

<div class="col">
    <div class="col width-1of5">
        <div class="cell">
            <?php echo $form->labelEx($model, 'status'); ?>
        </div>
    </div>
    <div class="col width-2of5">
        <div class="cell">
            <?php echo $form->dropDownList($model, 'status', Lookup::items('PostStatus')); ?>
        </div>
    </div>

    <div class="col width-fill">
        <div class="cell">
            <?php echo $form->error($model, 'status'); ?>
        </div>
    </div>

</div>

<div class="col">
    <div class="col width-1of5">
        <div class="cell">
            <?php echo $form->labelEx($model, 'sysCategories'); ?>
        </div>
    </div>
    <div class="col width-2of5">
        <div class="cell">
            <?php echo $form->checkBoxList($model, 'sysCategories',
                CHtml::listData(BlogSysCategory::model()->findAllByAttributes(
                        array('enable' => 1),
                        array('order' => 'position')),
                    'id', 'name')
            ); ?>
        </div>
    </div>

    <div class="col width-fill">
        <div class="cell">
            <?php echo $form->error($model, 'sysCategories'); ?>
        </div>
    </div>

</div>

<?php  $this->widget('my.seo.SeoFormWidget',array(
    'seobleType'=>get_class($model),
    'seobleId'=>$model->primaryKey ,
    'parentForm'=>$form ,
    'isNew'=>$model->getIsNewRecord(),
)); ?>

<div class="col">
    <div class="col width-1of5">
    </div>
    <div class="col width-fill">
        <div class="cell">
            <?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save', array('class' => 'button')); ?>
        </div>
    </div>
</div>



<?php $this->endWidget(); ?>



</div>
</div>
</div>
</div>
</div>
</div>

