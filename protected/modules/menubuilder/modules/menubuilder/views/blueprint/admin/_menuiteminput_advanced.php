<?php
/**
 * The form input for a menuitem without begin/end form
 */
?>
<?php if ($this->menuitemFieldAllowed('active')): ?>
    <div class="row">
        <?php echo $form->labelEx($model, 'active'); ?>
        <?php echo $form->checkBox($model, 'active'); ?>
        <?php echo $form->error($model, 'active'); ?>
    </div>
<?php endif; ?>
<?php if ($this->menuitemFieldAllowed('descriptions')): ?>
    <div class="row">
        <?php echo $form->labelEx($model, 'descriptionashint'); ?>
        <?php echo $form->checkBox($model, 'descriptionashint'); ?>
        <?php echo $form->error($model, 'descriptionashint'); ?>
    </div>
    <?php
    $this->renderPartial('_languagesfields', array('form' => $form, 'model' => $model, 'attribute' => 'descriptions', 'labelText' => Yii::t('MenubuilderModule.messages', 'Description')));
    ?>
<?php endif; ?>
<?php if ($this->menuitemFieldAllowed('ajaxOptions')): ?>
    <div class="row">
        <?php echo $form->labelEx($model, 'ajaxOptions'); ?>
        <?php echo $form->textField($model, 'ajaxOptions', array('size' => 100, 'maxlength' => 300)); ?>
        <p class="hint"><?php echo Yii::t('MenubuilderModule.messages', 'Separate with ; update=#ajaxContent;...'); ?></p>
        <?php echo $form->error($model, 'ajaxOptions'); ?>
    </div>
<?php endif; ?>

<?php if ($this->menuitemFieldAllowed('linkOptions')): ?>
    <div class="row">
        <?php echo $form->labelEx($model, 'linkOptions'); ?>
        <?php echo $form->textField($model, 'linkOptions', array('size' => 100, 'maxlength' => 300)); ?>
        <p
            class="hint"><?php echo Yii::t('MenubuilderModule.messages', 'Separate with ; class=link-class;confirm=Please confirm;...'); ?></p>
        <?php echo $form->error($model, 'linkOptions'); ?>
    </div>
<?php endif; ?>

<?php if ($this->menuitemFieldAllowed('itemOptions')): ?>
    <div class="row">
        <?php echo $form->labelEx($model, 'itemOptions'); ?>
        <?php echo $form->textField($model, 'itemOptions', array('size' => 100, 'maxlength' => 300)); ?>
        <p
            class="hint"><?php echo Yii::t('MenubuilderModule.messages', 'Separate with ; class=item-container;...'); ?></p>
        <?php echo $form->error($model, 'itemOptions'); ?>
    </div>
<?php endif; ?>

<?php if ($this->menuitemFieldAllowed('submenuOptions')): ?>
    <div class="row">
        <?php echo $form->labelEx($model, 'submenuOptions'); ?>
        <?php echo $form->textField($model, 'submenuOptions', array('size' => 100, 'maxlength' => 300)); ?>
        <p
            class="hint"><?php echo Yii::t('MenubuilderModule.messages', 'Separate with ; class=submenu-container;...'); ?></p>
        <?php echo $form->error($model, 'submenuOptions'); ?>
    </div>
<?php endif; ?>

<?php if ($this->menuitemFieldAllowed('template')): ?>
    <?php
    $templates = $this->getModule()->itemTemplates;
    if (!empty($templates)) {
        ?>
        <div class="row">
            <?php echo $form->labelEx($model, 'template'); ?>
            <?php echo $form->dropDownList($model, 'template', $templates, array('prompt' => '-', 'encode' => false)); ?>
            <?php echo $form->error($model, 'template'); ?>
        </div>
    <?php } else {?>
        <div class="row">
            <?php echo $form->labelEx($model, 'template'); ?>
            <?php echo $form->textField($model, 'template', array('size' => 100, 'maxlength' => 300)); ?>
            <?php echo $form->error($model, 'template'); ?>
        </div>
    <?php } ?>
<?php endif; ?>

