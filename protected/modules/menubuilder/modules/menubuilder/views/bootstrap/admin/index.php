<div class="emb-arrangeform form wide container">
    <?php
    $module = $this->getModule();
    $tabs = array();

    $this->widget('EUserFlash', array(
        'initScript' => "$('.userflash_success').fadeOut(5000);$('.userflash_notice').fadeOut(5000);;$('.userflash_warning').fadeOut(5000)",
        'bootstrapLayout' => true,
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
        'formClass' => 'bootstrap.widgets.TbActiveForm',
        'formOptions' => array(
            'type' => 'horizontal',
            'action' => $this->createUrl(''),
            'enableAjaxValidation' => false,
            'enableClientValidation' => false,
            //'htmlOptions'=>array('class'=>'well'),
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

    $previewButton = $this->widget('bootstrap.widgets.TbButton', array('buttonType' => 'submit', 'label' => Yii::t('MenubuilderModule.messages', 'Preview menu'),
        'htmlOptions' => array('name' => EMBConst::BUTTONNAME_PREVIEW, 'class' => 'pull-right', 'onclick' => 'nestedConfigToHidden();')), true);
    $editButton = $this->widget('bootstrap.widgets.TbButton', array('buttonType' => 'submit', 'label' => Yii::t('MenubuilderModule.messages', 'Save structure'),
        'htmlOptions' => array('name' => EMBConst::BUTTONNAME_UPDATENESTEDCONFIG, 'class' => 'pull-right', 'onclick' => 'nestedConfigToHidden();')), true);

    //add warning if not saved
    if (!$viewParams['nestedConfigSaved'])
    {
        $info = CHtml::tag('span', array('class' => 'emb-nestedsaved-warning'), ' ' . Yii::t('MenubuilderModule.messages', 'Not saved'));
        $editButton = $info . $editButton;
    }

    $assetsPath = $module->getAssetsPath();
    $editItemText = CHtml::image($assetsPath . '/update-blueprint.png', '', array('title' => Yii::t('MenuBuilderModule.messages', 'Edit')));

    //--------------- render MenuDropDown inside the form and get the currently selected menumodel too
    $menuDropDownList = $module->getMenusDropDownList('menuId', $viewParams['menuModel']->menuid, array(),true);
    echo CHtml::tag('h4', array(), Yii::t('MenubuilderModule.messages', 'Menu') . ' ' . $menuDropDownList);
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
                'data' => array('form' => $form),
            );
        ?>
        <a data-toggle="collapse" data-target="#emb-edittabs">
            <h4><?php echo Yii::t('MenubuilderModule.messages', 'Edit') ?></h4>
        </a>
        <div id="emb-edittabs" class="collapse">
            <div class="emb-edittabs">
                <?php
                echo CHtml::errorSummary(array($viewParams['menuModel'], $viewParams['menuItemModel']));
                $this->widget('bootstrap.widgets.TbTabView', array('tabs' => $tabs));
                $this->getModule()->registerBootstrapCollapseCookie('emb-edittabs', $this->_openMenuItemForm);
                ?>
            </div>
        </div>
    <?php
    endif;
    ?>
    <?php if ($simulateAllowed): $form->type = 'inline'; ?>
        <a data-toggle="collapse" data-target="#emb-form-simulate">
            <h4><?php echo Yii::t('MenubuilderModule.messages', 'Simulate') ?></h4>
        </a>
        <!-- begin simulate -->
        <div id="emb-form-simulate" class="collapse">
            <div class="emb-form-simulate form-inline">
                <?php
                $this->renderPartial('_simulateinput',
                    array(
                        'form' => $form,
                        'viewParams' => $viewParams,
                    )
                );

                echo ' ' . $previewButton;
                $this->getModule()->registerBootstrapCollapseCookie('emb-form-simulate', false);
                ?>

            </div>
        </div>
    <?php endif; ?>
    <!-- end simulate -->
    <?php if ($arrangeAllowed): ?>
        <!-- begin arrangeform-body-->
        <div class="emb-arrangeform-body row">
            <!-- left: begin nestable available-->
            <div class="emb-form-left span6">
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
            <div class="emb-form-right span6">
                <?php
                $adminWidget->renderMenuItems();
                ?>
            </div>
            <!-- right: end nestable  menuitems-->
        </div>
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



