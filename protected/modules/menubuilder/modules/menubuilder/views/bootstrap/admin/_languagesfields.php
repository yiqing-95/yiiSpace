<?php
$modelClass = get_class($model);
$curLanguage = Yii::app()->language;
foreach ($this->getModule()->getLanguages() as $language):

    $requiredChar = $attribute != 'descriptions' ? CHtml::$afterRequiredLabel : '';
    $label = $language != $curLanguage ? $labelText . " [$language]" : $labelText . $requiredChar;
    $langAttributes = $model->$attribute;
    $value = isset($langAttributes[$language]) ? $langAttributes[$language] : '';
    ?>
    <div class="control-group">
        <?php echo CHtml::label($label, '',array('class'=>'control-label')); ?>
        <div class="controls">
            <?php
            echo CHtml::textField($modelClass . "[$attribute][$language]", $value, array('class' => 'span6', 'maxlength' => 80));
            if ($language == $curLanguage) echo $form->error($model, $attribute);
            ?>
        </div>
    </div>
<?php
endforeach;