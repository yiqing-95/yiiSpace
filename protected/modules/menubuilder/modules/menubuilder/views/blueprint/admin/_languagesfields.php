<?php
$modelClass = get_class($model);
$curLanguage = Yii::app()->language;
foreach ($this->getModule()->getLanguages() as $language):

    $label = $language != $curLanguage ? $labelText ." [$language]" : $labelText.CHtml::$afterRequiredLabel;
    $langAttributes = $model->$attribute;
    $value = isset($langAttributes[$language]) ?$langAttributes[$language] : '';

    ?>
    <div class="row">
        <?php echo CHtml::label($label, ''); ?>
        <?php echo CHtml::textField($modelClass . "[$attribute][$language]", $value, array('size' => 40, 'maxlength' => 80)); ?>
        <?php if ($language == $curLanguage) echo $form->error($model, $attribute); ?>
    </div>
<?php
endforeach;