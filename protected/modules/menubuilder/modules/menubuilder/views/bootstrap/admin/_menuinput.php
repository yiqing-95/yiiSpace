<div class="emb-menuinput">
    <?php
    /**
     * The form input for a menuitem without begin/end form
     */
    $model = $viewParams['menuModel'];
    $isNew = $model->getIsNewRecord();
    $currentmenuOnlyChecked = $isNew ? true : (boolean)$model->menuid; //default true if new record

    echo $form->hiddenField($model, 'menuid');
    ?>

    <p class="note"><?php echo Yii::t('MenubuilderModule.messages', 'Fields with <span class="required">*</span> are required.'); ?></p>
    <?php

    if ($this->menuFieldAllowed('menuid'))
        echo $form->textFieldRow($model, 'menuid');

    if ($this->menuFieldAllowed('titles'))
        $this->renderPartial('_languagesfields', array('form' => $form, 'model' => $model, 'attribute' => 'titles', 'labelText' => Yii::t('MenubuilderModule.messages', 'Title')));

    if ($this->menuFieldAllowed('visible'))
        echo $form->checkBoxRow($model, 'visible');

    if ($this->menuFieldAllowed('sortposition'))
        echo $form->textFieldRow($model, 'sortposition', array('class' => 'span1', 'maxlength' => 5));

    if ($this->menuFieldAllowed('maxdepth'))
    {
        if (!EMBNestedConfigUtil::isEmptyNestedConfig($model->nestedconfig))
            echo $form->uneditableRow($model, 'maxdepth', array('hint' => Yii::t('MenubuilderModule.messages', 'Editable if no items assigned')));
        else
            echo $form->textFieldRow($model, 'maxdepth', array('class' => 'span1', 'maxlength' => 5));
    }

    if ($this->menuFieldAllowed('descriptionashint'))
    {
        echo $form->checkBoxRow($model, 'descriptionashint');
        $this->renderPartial('_languagesfields', array('form' => $form, 'model' => $model, 'attribute' => 'descriptions', 'labelText' => Yii::t('MenubuilderModule.messages', 'Description')));
    }
    ?>

    <?php if ($this->menuFieldAllowed('icon')): ?>
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

    if ($this->menuFieldAllowed('userroles') && !empty($viewParams['supportedUserRoles']))
    {
        if (is_string($model->userroles))
            $model->userroles = explode(',', $model->userroles);
        echo $form->select2Row($model, 'userroles', array('data' => $viewParams['supportedUserRoles'], 'multiple' => 'multiple'));
    }

    if ($this->menuFieldAllowed('scenarios') && !empty($viewParams['supportedScenarios']))
    {
        if (is_string($model->scenarios))
            $model->scenarios = explode(',', $model->scenarios);
        echo $form->select2Row($model, 'scenarios', array('data' => $viewParams['supportedScenarios'], 'multiple' => 'multiple',));
    }


    if ($this->menuFieldAllowed('adminroles') && !empty($viewParams['supportedUserRoles']))
    {
        if (is_string($model->adminroles))
            $model->scenarios = explode(',', $model->adminroles);
        echo $form->select2Row($model, 'adminroles', array('data' => $viewParams['supportedUserRoles'], 'multiple' => 'multiple',));
    }

    ?>

    <?php if ($this->menuitemFieldAllowed('createdinfo')): ?>
        <div>
            <?php echo CHtml::tag('div', array('class' => 'emb-createdinfo'), $model->getCreatedInfo()) ?>
        </div>
    <?php endif; ?>

    <div class="emb-form-actions">
        <?php
        if (!$isNew && $this->menuActionAllowed('update'))

            $this->widget('bootstrap.widgets.TbButton', array('buttonType' => 'submit', 'label' => Yii::t('MenubuilderModule.messages', 'Save'),
                'htmlOptions' => array('name' => EMBConst::BUTTONNAME_UPDATEMENU, 'onclick' => 'nestedConfigToHidden();')));

        if ($this->menuActionAllowed('create'))
            $this->widget('bootstrap.widgets.TbButton', array('buttonType' => 'submit', 'label' => Yii::t('MenubuilderModule.messages', 'Append new'),
                'htmlOptions' => array('name' => EMBConst::BUTTONNAME_CREATEMENU, 'onclick' => 'nestedConfigToHidden();')));

        if (!$isNew && $this->menuActionAllowed('delete'))
            $this->widget('bootstrap.widgets.TbButton', array('buttonType' => 'submit', 'label' => Yii::t('MenubuilderModule.messages', 'Delete'),
                'htmlOptions' => array('name' => EMBConst::BUTTONNAME_DELETEMENU, 'onclick' => 'nestedConfigToHidden();', 'class' => 'pull-right')));
        ?>
    </div>


</div>