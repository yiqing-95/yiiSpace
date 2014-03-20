<div class="emb-arrangeform form wide">
    <?php
    $module = $this->getModule();
    $tabs = array();

    $this->widget('EUserFlash', array(
        'initScript' => "$('.userflash_success').fadeOut(5000);$('.userflash_notice').fadeOut(5000);;$('.userflash_warning').fadeOut(5000)"));

    $this->widget('ext.menubuilder.extensions.ecollapse.ECollapse', array(
        'selector' => 'fieldset.emb-edittabs',
        'collapsed' => !$this->_openMenuItemForm,
        'cookieEnabled' => !$this->_openMenuItemForm,
        'head' => 'legend',
        'group' => 'div',
    ));


    $labelTemplate = $this->getLabelTemplate();


    //set the handleClass of the nestable depending on menu->visible/locked
    //styling of the draghandle: see menubuilder.css
    $jsWidgetOptions = array();
    $handleClass = '';
    if (!$viewParams['menuModel']->visible)
        $handleClass .= ' emb-invisiblemenu ';

    if ($viewParams['menuModel']->locked)
        $handleClass .= ' locked ';

    if (!empty($handleClass))
    {
        $handleClass .= 'dd-handle';
        $jsWidgetOptions['handleClass'] = $handleClass;
    }

    $maxDepth = !empty($viewParams['menuModel']->maxdepth) ? $viewParams['menuModel']->maxdepth : 3;

    //renders begin form{edit}
    $adminWidget = $this->beginWidget('EMBListAdminWidget', array(
        'itemsProvider' => $viewParams['itemsProvider'],
        'labelTemplate' => $labelTemplate,
        'formClass' => 'CActiveForm',
        'formOptions' => array(
            'action' => $this->createUrl(''),
            'enableAjaxValidation' => false,
            'enableClientValidation' => false,
        ),
        'maxDepth' => $maxDepth,
        'options' => $jsWidgetOptions,
    ));

    echo CHtml::hiddenField(EMBConst::HIDDENNAME_OLDMENUID, $viewParams['menuModel']->menuid);

    $menuAllowEdit = $this->menuActionAllowed('create') || $this->menuActionAllowed('update');
    $menuAllowPreview = $this->menuActionAllowed('preview');
    $simulateAllowed = $this->menuActionAllowed('simulate');
    $arrangeAllowed = $this->menuitemActionAllowed('arrange');

    $menuitemFormVisible = $this->menuitemActionAllowed('create') || $this->menuitemActionAllowed('update') || $this->menuitemActionAllowed('delete');
    $menuFormVisible = $menuAllowEdit || $this->menuActionAllowed('delete');
    $utilFormVisible = $this->utilActionAllowed('restore') || $this->utilActionAllowed('import') || $this->utilActionAllowed('export');
    $editFormVisible = $menuitemFormVisible || $menuFormVisible || $utilFormVisible;

    $previewButton = CHtml::submitButton(Yii::t('MenubuilderModule.messages', 'Preview menu'), array('name' => EMBConst::BUTTONNAME_PREVIEW, 'class' => 'right', 'onclick' => 'nestedConfigToHidden();'));
    $editButton = CHtml::submitButton(Yii::t('MenubuilderModule.messages', 'Save structure'), array('name' => EMBConst::BUTTONNAME_UPDATENESTEDCONFIG, 'class' => 'right', 'onclick' => 'nestedConfigToHidden();'));

    //add warning if not saved
    if (!$viewParams['nestedConfigSaved'])
    {
        $info = CHtml::tag('span', array('class' => 'emb-nestedsaved-warning'), ' ' . Yii::t('MenubuilderModule.messages', 'Not saved'));
        $editButton = $info . $editButton;
    }

    $assetsPath = $module->getAssetsPath();
    $editItemText = CHtml::image($assetsPath . '/update-blueprint.png', '', array('title' => Yii::t('MenuBuilderModule.messages', 'Edit')));

    //--------------- render MenuDropDown inside the form and get the currently selected menumodel too
    $menuDropDownList = $module->getMenusDropDownList('menuId', $viewParams['menuModel']->menuid, array(), true);
    echo CHtml::tag('h1', array(), Yii::t('MenubuilderModule.messages', 'Menu') . ' ' . $menuDropDownList);
    ?>

    <?php
    //---------------  set the title for the right side of the adminwidget
    $title = $module->getMenuTitle($viewParams['menuModel'], $viewParams['itemsProvider']->language, true);

    //show the previewbutton in the title if simulate is not allowed
    if ($menuAllowPreview && !$simulateAllowed)
        $title .= $previewButton;

    if ($menuAllowEdit)
        $title .= $editButton;
    $adminWidget->title = $title;
    $adminWidget->editItemText = $editItemText;

    $form = $adminWidget->getForm();
    ?>
    <?php if ($editFormVisible): ?>
        <?php
        if ($menuitemFormVisible)
            $tabs[] = array('title' => Yii::t('MenubuilderModule.messages', 'Menu item'),
                'view' => '_menuiteminput',
                'data' => array('form' => $form, 'viewParams' => $viewParams),
            );
        if ($menuFormVisible)
            $tabs[] = array('title' => Yii::t('MenubuilderModule.messages', 'Menu'),
                'view' => '_menuinput',
                'data' => array('form' => $form, 'viewParams' => $viewParams),
            );
        if ($utilFormVisible)
            $tabs[] = array('title' => Yii::t('MenubuilderModule.messages', 'Utilities'),
                'view' => '_utilinput',
            );
        ?>
        <fieldset class="emb-edittabs">
            <legend><?php echo Yii::t('MenubuilderModule.messages', 'Edit') ?></legend>
            <?php
              echo CHtml::errorSummary(array($viewParams['menuModel'],$viewParams['menuItemModel']));
              $this->widget('CTabView', array('tabs' => $tabs));
            ?>
        </fieldset>
    <?php endif; ?>
    <?php if ($simulateAllowed): ?>
        <!-- begin simulate -->
        <fieldset class="emb-form-simulate"
                  style="padding:20px 30px; 40px 10px; background-color: #fafafa; margin-bottom: 10px;">
            <legend><?php echo Yii::t('MenubuilderModule.messages', 'Simulate userroles/scenarios') ?></legend>
            <?php
            $this->renderPartial('_simulateinput',
                array(
                    'form' => $form,
                    'viewParams' => $viewParams,
                )
            );

            echo ' ' . $previewButton;
            ?>

        </fieldset>
    <?php endif; ?>
    <!-- end simulate -->
    <?php if ($arrangeAllowed): ?>
        <!-- begin arrangeform-body-->
        <fieldset class="emb-arrangeform-body">
            <!-- left: begin nestable available-->
            <div class="emb-form-left span-11">
                <?php

                //the nestable list of the available items at the left side
                $adminWidget->renderAvailableItems(
                //EMBListWidget config for the left site
                    array(
                        'title' => Yii::t('MenubuilderModule.messages', 'Available items'),
                        'labelTemplate' => $labelTemplate,
                        'editItemText' => $editItemText,
                        'maxDepth' => $maxDepth,
                        'options' => $jsWidgetOptions,
                    )
                );

                ?>
            </div>
            <!-- left: end nestable available-->
            <!-- right: begin nestable menuitems-->
            <div class="emb-form-right span-11">
                <?php
                $adminWidget->renderMenuItems();
                ?>
            </div>
            <!-- right: end nestable  menuitems-->
        </fieldset>
        <!-- end arrangeform-body-->
    <?php endif; ?>
    <!-- begin menupreviews -->
    <?php
    //the menu previews in the footer
    if ($menuAllowPreview)
        $this->renderPartial('_menupreviews', array('viewParams' => $viewParams));
    ?>
    <!-- end menupreviews -->

    <?php $this->endWidget(); //renders end form ?>
</div>



