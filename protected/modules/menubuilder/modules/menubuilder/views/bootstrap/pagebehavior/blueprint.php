<fieldset class="emb-pagebehavior">
    <legend><?php echo Yii::t('MenubuilderModule.messages', 'Menu item') ?></legend>
    <?php

    $menuItem = $pageBehavior->getMenuItem();
    if (empty($menuItem))
        $menuItem = $pageBehavior->createMenuItem();

    $pageBehavior->renderHiddenFields();

    ?>
    <?php
    $curLanguage = Yii::app()->language;
    $labelText = Yii::t('MenubuilderModule.messages', 'Label');

    foreach (MenubuilderModule::getInstance()->getLanguages() as $language):

        $label = $language != $curLanguage ? $labelText . " [$language]" : $labelText;
        $langAttributes = $menuItem->labels;
        $value = isset($langAttributes[$language]) ? $langAttributes[$language] : '';

        ?>
        <div class="row">
            <?php echo CHtml::label($label, ''); ?>
            <?php echo CHtml::textField($pageBehavior->getFieldName('labels') . "[$language]", $value, array('size' => 40, 'maxlength' => 80)); ?>
        </div>
    <?php
    endforeach;
    ?>

    <div class="row">
        <?php echo CHtml::label(Yii::t('MenubuilderModule.messages', 'Insert before'), EMBConst::FIELDNAME_ITEMBEFORE); ?>
        <?php echo CHtml::checkBox($pageBehavior->getFieldName('itemBefore'), $pageBehavior->getInsertBefore()); ?>
    </div>

    <div class="row">
        <?php echo CHtml::label(Yii::t('MenubuilderModule.messages', 'Menu item'), EMBConst::FIELDNAME_MENUITEM); ?>
        <?php echo $pageBehavior->getMenuItemsDropDownList(); ?>
    </div>
</fieldset>