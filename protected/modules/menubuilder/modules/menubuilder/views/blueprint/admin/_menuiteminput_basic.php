<?php
/**
 * The form input for a menuitem without begin/end form
 */
$model = $viewParams['menuItemModel'];
$isNew = $model->getIsNewRecord();
$currentmenuOnlyChecked = $isNew ? true : $viewParams['menuModel']->menuid == $model->menuid;
?>

<p class="note"><?php echo Yii::t('MenubuilderModule.messages', 'Fields with <span class="required">*</span> are required.'); ?></p>

<?php if ($this->menuitemFieldAllowed('visible')): ?>
    <div class="row">
        <?php echo $form->labelEx($model, 'visible'); ?>
        <?php echo $form->checkBox($model, 'visible'); ?>
        <?php echo $form->error($model, 'visible'); ?>
    </div>
<?php endif; ?>

<div class="row">
    <?php echo CHtml::label(Yii::t('MenubuilderModule.messages', 'This menu only'), ''); ?>
    <?php echo CHtml::checkBox(EMBAdminController::FORMFIELD_CURRENTMENUONLY, $currentmenuOnlyChecked); ?>
</div>

<?php
if ($this->menuitemFieldAllowed('labels'))
    $this->renderPartial('_languagesfields', array('form' => $form, 'model' => $model, 'attribute' => 'labels', 'labelText' => Yii::t('MenubuilderModule.messages', 'Label')));
?>

<?php if ($this->menuitemFieldAllowed('url')): ?>
    <div class="row">
        <?php echo $form->labelEx($model, 'url'); ?>
        <?php echo $form->textField($model, 'url', array('size' => 40, 'maxlength' => 250)); ?>
        <?php echo $form->error($model, 'url'); ?>
    </div>
<?php endif; ?>

<?php if ($this->menuitemFieldAllowed('target')): ?>
    <?php
    $targets = $this->getLinkTargets();
    if (!empty($targets)):
        ?>
        <div class="row">
            <?php echo $form->labelEx($model, 'target'); ?>
            <?php echo $form->dropDownList($model, 'target', $targets, array('prompt' => '-')); ?>
            <?php echo $form->error($model, 'target'); ?>
        </div>
    <?php endif; ?>
<?php endif; ?>

<?php if ($this->menuitemFieldAllowed('icon')): ?>
    <?php
    $iconsDropDown = $this->iconDropDownList($model, 'icon');
    if (!empty($iconsDropDown)):
        ?>
        <div class="row icondropdown">
            <?php echo $form->labelEx($model, 'icon'); ?>
            <?php echo $iconsDropDown ?>
            <span
                class="hint"><?php echo Yii::t('MenubuilderModule.messages', 'Click on the name above to change the icon'); ?></span>
            <?php echo $form->error($model, 'icon'); ?>
        </div>
    <?php endif; ?>
<?php endif; ?>

<?php if ($this->menuitemFieldAllowed('userroles')): ?>
    <?php
    if (!empty($viewParams['supportedUserRoles'])):
        ?>
        <div class="row">
            <?php echo $form->labelEx($model, 'userroles'); ?>
            <?php
            if (is_string($model->userroles))
                $model->userroles = explode(',', $model->userroles);
            $this->widget('ext.menubuilder.extensions.select2.ESelect2', array(
                'model' => $model,
                'attribute' => 'userroles',
                'data' => $viewParams['supportedUserRoles'],
                'options' => array(
                    'width' => '289px',
                ),
                'htmlOptions' => array(
                    'multiple' => 'multiple',
                ),
            ));
            ?>
            <?php echo $form->error($model, 'userroles'); ?>
        </div>
    <?php endif; ?>
<?php endif; ?>

<?php if ($this->menuitemFieldAllowed('scenarios')): ?>
    <?php
    if (!empty($viewParams['supportedScenarios'])):
        ?>
        <div class="row">
            <?php echo $form->labelEx($model, 'scenarios'); ?>
            <?php
            if (is_string($model->scenarios))
                $model->scenarios = explode(',', $model->scenarios);
            $this->widget('ext.menubuilder.extensions.select2.ESelect2', array(
                'model' => $model,
                'attribute' => 'scenarios',
                'data' => $viewParams['supportedScenarios'],
                'options' => array(
                    'width' => '289px',
                ),
                'htmlOptions' => array(
                    'multiple' => 'multiple',
                ),
            ));
            ?>
            <?php echo $form->error($model, 'scenarios'); ?>
        </div>
    <?php endif; ?>
<?php endif; ?>
