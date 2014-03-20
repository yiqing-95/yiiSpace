<?php

$items=$this->createWidget('EMBMenu', array(
        //set the simulate params
        'menuIds'=>$viewParams['menuModel']->menuid,
        'scenarios' => !empty($viewParams['simulateModel']) ? $viewParams['simulateModel']->scenarios : null,
        'userRoles' => !empty($viewParams['simulateModel']) ? $viewParams['simulateModel']->userroles : null,
        'language' => $viewParams['itemsProvider']->language,
        'nestedConfig' => $viewParams['itemsProvider']->nestedConfig, //the current (unsaved) nested menuitems
    )
)->getItems();

$this->widget('bootstrap.widgets.TbNavbar', array(
    'collapse' => true,
    'fixed' => false,
    'items' => array(
        array(
            'class' => 'bootstrap.widgets.TbMenu',
            'encodeLabel'=>false,
            'items' => $items,
        )
    )
));