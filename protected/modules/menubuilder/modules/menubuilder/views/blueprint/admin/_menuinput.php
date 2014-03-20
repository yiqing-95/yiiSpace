<div class="emb-menuinput">
<?php
/**
 * The form input for a menuitem without begin/end form
 */
$model = $viewParams['menuModel'];
$isNew = $model->getIsNewRecord();
$currentmenuOnlyChecked = $isNew ? true : (boolean)$model->menuid; //default true if new record

$maxDepthHtmlOptions = array('size' => 2, 'maxlength' => 1);
if (!EMBNestedConfigUtil::isEmptyNestedConfig($model->nestedconfig))
{
    $maxDepthHtmlOptions['readonly'] = true;
    $maxDepthHint = Yii::t('MenubuilderModule.messages', 'Editable if no items assigned');
}
else
    $maxDepthHint='';


echo $form->hiddenField($model, 'menuid');
?>

<p class="note"><?php echo Yii::t('MenubuilderModule.messages', 'Fields with <span class="required">*</span> are required.'); ?></p>
<?php if ($this->menuFieldAllowed('menuid')): ?>
    <div class="row">
        <?php echo $form->labelEx($model, 'menuid'); ?>
        <?php echo $form->textField($model, 'menuid'); ?>
        <?php echo $form->error($model, 'menuid'); ?>
    </div>
<?php endif; ?>


<?php
if ($this->menuFieldAllowed('titles'))
   $this->renderPartial('_languagesfields',array('form'=>$form,'model'=>$model,'attribute'=>'titles','labelText'=>Yii::t('MenubuilderModule.messages', 'Title')));
?>

<?php if ($this->menuFieldAllowed('visible')): ?>
    <div class="row">
        <?php echo $form->labelEx($model, 'visible'); ?>
        <?php echo $form->checkBox($model, 'visible'); ?>
        <?php echo $form->error($model, 'visible'); ?>
    </div>
<?php endif; ?>

<?php if ($this->menuFieldAllowed('locked')): ?>
    <div class="row">
        <?php echo $form->labelEx($model, 'locked'); ?>
        <?php echo $form->checkBox($model, 'locked'); ?>
        <?php echo $form->error($model, 'locked'); ?>
    </div>
<?php endif; ?>

<?php if ($this->menuFieldAllowed('sortposition')): ?>
    <div class="row">
        <?php echo $form->labelEx($model, 'sortposition'); ?>
        <?php echo $form->textField($model, 'sortposition', array('size' => 2, 'maxlength' => 5)); ?>
        <?php echo $form->error($model, 'sortposition'); ?>
    </div>
<?php endif; ?>

<?php if ($this->menuFieldAllowed('maxdepth')): ?>
    <div class="row">
        <?php echo $form->labelEx($model, 'maxdepth'); ?>
        <?php echo $form->textField($model, 'maxdepth', $maxDepthHtmlOptions); ?>
        <?php if (!empty($maxDepthHint)): ?>
            <span class="hint"><?php echo $maxDepthHint; ?></span>
        <?php endif; ?>
        <?php echo $form->error($model, 'maxdepth'); ?>
    </div>
<?php endif; ?>

<?php if($this->menuFieldAllowed('descriptions')): ?>
    <div class="row">
        <?php echo $form->labelEx($model, 'descriptionashint'); ?>
        <?php echo $form->checkBox($model, 'descriptionashint'); ?>
        <?php echo $form->error($model, 'descriptionashint'); ?>
    </div>
    <?php
        $this->renderPartial('_languagesfields',array('form'=>$form,'model'=>$model,'attribute'=>'descriptions','labelText'=>Yii::t('MenubuilderModule.messages', 'Description')));
    ?>
<?php endif; ?>

<?php if ($this->menuFieldAllowed('icon')): ?>
    <?php
    $iconsDropDown = $this->iconDropDownList($model, 'icon');
    if (!empty($iconsDropDown)):
        ?>
        <div class="row">
            <?php echo $form->labelEx($model, 'icon'); ?>
            <?php echo $iconsDropDown ?>
            <span class="hint"><?php echo Yii::t('MenubuilderModule.messages', 'Click on the name above to change the icon'); ?></span>
            <?php echo $form->error($model, 'icon'); ?>
        </div>
    <?php endif; ?>
<?php endif; ?>

<?php if ($this->menuFieldAllowed('userroles')): ?>
    <?php
    if (!empty($viewParams['supportedUserRoles'])):
        ?>
        <div class="row">
            <?php echo $form->labelEx($model, 'userroles'); ?>
            <?php
            if (is_string($model->userroles))
                $model->userroles = explode(',', $model->userroles);
            $this->widget('ext.select2.ESelect2', array(
                'model' => $model,
                'attribute' => 'userroles',
                'data' => $viewParams['supportedUserRoles'],
                'options' => array(
                    'width' => '320px',
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

<?php if ($this->menuFieldAllowed('scenarios')): ?>
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
                    'width' => '320px',
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


<?php if ($this->menuFieldAllowed('adminroles')): ?>
    <?php
    if (!empty($viewParams['supportedUserRoles'])):
        ?>
        <div class="row">
            <?php echo $form->labelEx($model, 'adminroles'); ?>
            <?php
            if (is_string($model->adminroles))
                $model->adminroles = explode(',', $model->adminroles);
            $this->widget('ext.menubuilder.extensions.select2.ESelect2', array(
                'model' => $model,
                'attribute' => 'adminroles',
                'data' => $viewParams['supportedUserRoles'],
                'options' => array(
                    'width' => '320px',
                ),
                'htmlOptions' => array(
                    'multiple' => 'multiple',
                ),
            ));
            ?>
            <?php echo $form->error($model, 'adminroles'); ?>
        </div>
    <?php endif; ?>
<?php endif; ?>

<div class="row buttons">
    <?php
    if (!$isNew && $this->menuActionAllowed('update'))
        echo CHtml::submitButton(Yii::t('MenubuilderModule.messages', 'Save'), array('name' => EMBConst::BUTTONNAME_UPDATEMENU, 'onclick' => 'nestedConfigToHidden();'));

    if ($this->menuActionAllowed('create'))
        echo CHtml::submitButton(Yii::t('MenubuilderModule.messages', 'Append new'), array('name' => EMBConst::BUTTONNAME_CREATEMENU, 'onclick' => 'nestedConfigToHidden();'));

    if (!$isNew && $this->menuActionAllowed('delete'))
        echo CHtml::tag('span', array('class' => 'right'), CHtml::submitButton(Yii::t('MenubuilderModule.messages', 'Delete'), array('name' => EMBConst::BUTTONNAME_DELETEMENU, 'onclick' => 'nestedConfigToHidden();'))); ?>
</div>

<?php if ($this->menuitemFieldAllowed('createdinfo')): ?>
    <div class="row">
        <?php echo CHtml::label('&nbsp;', '') ?>
        <?php echo CHtml::tag('div', array('class' => 'emb-createdinfo'), $model->getCreatedInfo()) ?>
    </div>
<?php endif; ?>
</div>

