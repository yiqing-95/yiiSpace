
<div class="col cell">

    <?php $form=$this->beginWidget('CActiveForm', array(
        'id'=>'seo-form',
        'enableAjaxValidation'=>false,
        'htmlOptions'=>array(
            'class'=>'cell'
        ),
    )); ?>

    <div class="col">
        <p class="note">Fields with <span class="required">*</span> are required.</p>

        <?php echo $form->errorSummary($model); ?>
    </div>
