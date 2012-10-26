<?php if ( ! defined('YII_PATH')) exit('No direct script access allowed');

class ExampleBehavior extends ControllerBehavior{
    
    public function beforeRender($event)
    {
        parent::beforeRender($event);
        Yii::app()->clientScript->registerCss('example2','a{text-decoration:none}');
    }
    
    public function beforeAction($event)
    {
        parent::beforeAction($event);
        
        if(ControllerHelper::isControllerAction('site','index'))
            Yii::app()->clientScript->registerScript('example','setTimeout(function(){alert("i am added from within a controller behavior!!!");},1000)');
    }
}