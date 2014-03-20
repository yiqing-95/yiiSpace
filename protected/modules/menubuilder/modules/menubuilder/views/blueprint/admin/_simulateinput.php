<?php

/**
 * Display the select2 inputs for simulating userroles
 */
if ($this->simulateFieldAllowed('userroles') && !empty($viewParams['simulateModel']) && !empty($viewParams['supportedUserRoles']))
{
    echo CHtml::tag('span', array(), Yii::t('MenubuilderModule.messages', 'Userroles') . ' ');
    $this->widget('ext.select2.ESelect2', array(
        'model' => $viewParams['simulateModel'],
        'attribute' => 'userroles',
        'data' => $viewParams['supportedUserRoles'],
        'options' => array(
            'width' => '200px',
        ),
        'htmlOptions' => array(
            'multiple' => 'multiple',
        ),
    ));

    echo $form->error($viewParams['simulateModel'], 'userroles');
}

/**
 * Display the select2 inputs for simulating scenarios
 */
if ($this->simulateFieldAllowed('scenarios') && !empty($viewParams['simulateModel']) && !empty($viewParams['supportedScenarios']))
{
    $prefix = empty($viewParams['supportedUserRoles']) ? '' : ' ';
    echo CHtml::tag('span', array(), $prefix . Yii::t('MenubuilderModule.messages', 'Scenarios') . ' ');
    $this->widget('ext.select2.ESelect2', array(
        'model' => $viewParams['simulateModel'],
        'attribute' => 'scenarios',
        'data' => $viewParams['supportedScenarios'],
        'options' => array(
            'width' => '200px',
        ),
        'htmlOptions' => array(
            'multiple' => 'multiple',
        ),
    ));
    echo $form->error($viewParams['simulateModel'], 'scenarios');
}


if ($this->simulateFieldAllowed('languages') && ($languageDropdownList = $this->getModule()->getLanguagesDropDownList('language', $viewParams['itemsProvider']->language)))
{
    $prefix = empty($viewParams['supportedScenarios']) && empty($viewParams['supportedUserRoles'])  ? '' : ' ';
    echo CHtml::tag('span', array(), $prefix . Yii::t('MenubuilderModule.messages', 'Language') . ' ');
    echo $languageDropdownList;
}



