<?php
/* @var $this SeoController */
/* @var $model Seo */
/* @var $form CActiveForm */
?>


<?php
// 创建跟更新共用同一个form片段 所以带这个标识是更新还是创建
echo $form->hiddenField($model, 'id'); ?>

<div class="col">
    <div class="col width-1of4">
        <div class="cell">
            <?php echo $form->labelEx($model, 'title'); ?>
        </div>
    </div>
    <div class="col width-2of4">
        <div class="cell">
            <?php echo $form->textField($model, 'title', array('size' => 60, 'maxlength' => 255)); ?>
        </div>
    </div>

    <div class="col width-fill">
        <div class="cell">
            <?php echo $form->error($model, 'title'); ?>
        </div>
    </div>

</div>

<div class="col">
    <div class="col width-1of4">
        <div class="cell">
            <?php echo $form->labelEx($model, 'description'); ?>
        </div>
    </div>
    <div class="col width-2of4">
        <div class="cell">
            <?php echo $form->textField($model, 'description', array('size' => 60, 'maxlength' => 255)); ?>
        </div>
    </div>

    <div class="col width-fill">
        <div class="cell">
            <?php echo $form->error($model, 'description'); ?>
        </div>
    </div>

</div>

<div class="col">
    <div class="col width-1of4">
        <div class="cell">
            <?php echo $form->labelEx($model, 'keywords'); ?>
        </div>
    </div>
    <div class="col width-2of4">
        <div class="cell">
            <?php echo $form->textField($model, 'keywords', array('size' => 60, 'maxlength' => 255)); ?>
        </div>
    </div>

    <div class="col width-fill">
        <div class="cell">
            <?php echo $form->error($model, 'keywords'); ?>
        </div>
    </div>

</div>
<?php echo $form->hiddenField($model, 'seoble_id'); ?>

<?php echo $form->hiddenField($model, 'seoble_type', array('size' => 60, 'maxlength' => 255)); ?>

<?php echo $form->hiddenField($model, 'created_at'); ?>

<?php echo $form->hiddenField($model, 'updated_at'); ?>




