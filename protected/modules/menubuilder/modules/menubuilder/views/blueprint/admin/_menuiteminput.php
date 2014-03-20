<div class="emb-menuiteminput">
    <?php
    /**
     * The form input for a menuitem without begin/end form
     */
    $model = $viewParams['menuItemModel'];
    $isNew = $model->getIsNewRecord();

    if ($isNew)
        $model->menuid = $viewParams['menuModel']->menuid;

    echo $form->hiddenField($model, 'itemid');
    echo $form->hiddenField($model, 'menuid');

    if ($this->menuitemActionAllowed('advanced'))
    {
        $tabs = array();
        $tabs['basic'] = array(
            'title' => Yii::t('MenubuilderModule.messages', 'Values'),
            'view' => '_menuiteminput_basic',
            'data' => array('model' => $model, 'form' => $form, 'viewParams' => $viewParams),
        );

        $tabs['advanced'] = array(
            'title' => Yii::t('MenubuilderModule.messages', 'Advanced'),
            'view' => '_menuiteminput_advanced',
            'data' => array('model' => $model, 'form' => $form, 'viewParams' => $viewParams),
        );

        $this->widget('CTabView', array('tabs' => $tabs));
    } else
        $this->renderPartial('_menuiteminput_basic', array('model' => $model, 'form' => $form, 'viewParams' => $viewParams));


    ?>
    <?php if ($this->menuitemFieldAllowed('createdinfo')): ?>
        <div class="row">
            <?php echo CHtml::label('&nbsp;', '') ?>
            <?php echo CHtml::tag('div', array('class' => 'emb-createdinfo'), $model->getCreatedInfo()) ?>
        </div>
    <?php endif; ?>
    <div class="row">
        <?php
        if (!$isNew && $this->menuitemActionAllowed('update'))
            echo CHtml::submitButton(Yii::t('MenubuilderModule.messages', 'Save'), array('name' => EMBConst::BUTTONNAME_UPDATEITEM, 'onclick' => 'nestedConfigToHidden();'));

        if ($this->menuitemActionAllowed('create'))
            echo CHtml::submitButton(Yii::t('MenubuilderModule.messages', 'Append new'), array('name' => EMBConst::BUTTONNAME_CREATEITEM, 'onclick' => 'nestedConfigToHidden();'));

        if (!$isNew && $this->menuitemActionAllowed('delete'))
            echo CHtml::tag('span', array('class' => 'right'), CHtml::submitButton(Yii::t('MenubuilderModule.messages', 'Delete'), array('name' => EMBConst::BUTTONNAME_DELETEITEM, 'onclick' => 'nestedConfigToHidden();'))); ?>
    </div>


</div>
