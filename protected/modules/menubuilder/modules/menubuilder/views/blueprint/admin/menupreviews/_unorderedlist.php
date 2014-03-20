<?php
$this->widget('EMBList', array(
        //set the simulate params
        'menuIds'=>$viewParams['menuModel']->menuid,
        'scenarios' => !empty($viewParams['simulateModel']) ? $viewParams['simulateModel']->scenarios : null,
        'userRoles' => !empty($viewParams['simulateModel']) ? $viewParams['simulateModel']->userroles : null,
        'language' => $viewParams['itemsProvider']->language,
        'nestedConfig' => $viewParams['itemsProvider']->nestedConfig, //the current (unsaved) nested menuitems
    )
);
