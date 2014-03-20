<?php
/**
 * The form input for a menuitem without begin/end form
 */
$model = $viewParams['menuItemModel'];
$isNew = $model->getIsNewRecord();
$currentmenuOnlyChecked = $isNew ? true : $viewParams['menuModel']->menuid == $model->menuid;
?>

    <p class="note"><?php echo Yii::t('MenubuilderModule.messages', 'Fields with <span class="required">*</span> are required.'); ?></p>

<?php
if ($this->menuitemFieldAllowed('visible'))
    echo $form->checkBoxRow($model, 'visible');
?>
    <div class="control-group">
        <div class="controls">
            <label class="checkbox">
                <?php echo CHtml::checkBox(EMBAdminController::FORMFIELD_CURRENTMENUONLY, $currentmenuOnlyChecked); ?>
                <?php echo Yii::t('MenubuilderModule.messages', 'This menu only'); ?>
            </label>
        </div>
    </div>
<?php
if ($this->menuitemFieldAllowed('labels'))
    $this->renderPartial('_languagesfields', array('form' => $form, 'model' => $model, 'attribute' => 'labels', 'labelText' => Yii::t('MenubuilderModule.messages', 'Label')));

if ($this->menuitemFieldAllowed('url'))
    echo $form->textFieldRow($model, 'url', array('class' => 'span6'));

if ($this->menuitemFieldAllowed('target'))
{
    $targets = $this->getLinkTargets();
    if (!empty($targets))
        echo $form->dropDownListRow($model, 'target', $targets, array('prompt' => '-'));
}
?>

<?php if ($this->menuitemFieldAllowed('icon')): ?>
    <?php
    $iconsDropDown = $this->iconDropDownList($model, 'icon');
    if (!empty($iconsDropDown)): ?>
        <div class="control-group">
            <?php echo $form->labelEx($model, 'icon', array('class'=>'control-label')); ?>
            <div class="controls">
                <?php
                echo $iconsDropDown;
                ?>
                <span
                    class="hint"><?php echo Yii::t('MenubuilderModule.messages', 'Click on the name above to change the icon'); ?></span>
                <?php echo $form->error($model, 'icon'); ?>
            </div>
        </div>
    <?php endif; ?>
<?php endif; ?>

<?php
if ($this->menuitemFieldAllowed('userroles') && !empty($viewParams['supportedUserRoles']))
{
    if (is_string($model->userroles))
        $model->userroles = explode(',', $model->userroles);
    echo $form->select2Row($model, 'userroles', array('data' => $viewParams['supportedUserRoles'], 'multiple' => 'multiple'));
}

if ($this->menuitemFieldAllowed('scenarios') && !empty($viewParams['supportedScenarios']))
{
    if (is_string($model->scenarios))
        $model->scenarios = explode(',', $model->scenarios);
    echo $form->select2Row($model, 'scenarios', array('data' => $viewParams['supportedScenarios'], 'multiple' => 'multiple',));
}



