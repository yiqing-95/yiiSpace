<?php

/**
 * Display the select2 inputs for simulating userroles
 */
if ($this->simulateFieldAllowed('userroles') && !empty($viewParams['simulateModel']) && !empty($viewParams['supportedUserRoles']))
    echo $form->select2Row($viewParams['simulateModel'], 'userroles', array('data' => $viewParams['supportedUserRoles'], 'multiple' => 'multiple',));
/**
 * Display the select2 inputs for simulating scenarios
 */
if ($this->simulateFieldAllowed('scenarios') && !empty($viewParams['simulateModel']) && !empty($viewParams['supportedScenarios']))
        echo $form->select2Row($viewParams['simulateModel'], 'scenarios', array('data' => $viewParams['supportedScenarios'], 'multiple' => 'multiple',));

if ($this->simulateFieldAllowed('languages') && ($languageDropdownList = $this->getModule()->getLanguagesDropDownList('language', $viewParams['itemsProvider']->language)))
{
    $prefix = empty($viewParams['supportedScenarios']) && empty($viewParams['supportedUserRoles'])  ? '' : ' ';
    echo CHtml::label($prefix . Yii::t('MenubuilderModule.messages', 'Language') . ' ','');
    echo $languageDropdownList;
}
