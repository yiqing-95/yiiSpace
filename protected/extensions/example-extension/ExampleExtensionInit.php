<?php if ( ! defined('YII_PATH')) exit('No direct script access allowed');

class ExampleExtensionInit extends ExtensionInit{
    
    protected $_priority=1;
    
    // the init() method is called when the app is initilized, so it is a good hook for application wide events.
    public function init()
    {
        parent::init();
        
        // let's create a controller, just for fun :)
        Yii::app()->attachEventHandler('onBeginRequest', array($this,'createController'));
    }
    
    // the run() method is called in Controller::onBeforeInit(), so here we can hook into controllers.
    public function run()
    {
        //we hook directly in controller
        $this->controller->attachEventHandler('onBeforeRender',array($this,'registerCss'));
        
        if(!ControllerHelper::isController('extension'))
            $this->controller->attachEventHandler('onBeforeProcessOutput',array($this,'generateMetaTitle'));
        
        //we hook by using a behavior
        $this->controller->attachBehavior('ExampleBehavior',array(
            'class'=>'ext.example-extension.behaviors.ExampleBehavior',
        ));
        
        // let's attach the menu item for our controller.
        $this->controller->attachEventHandler('onBeforeAction',array($this,'addMenuItem'));
    }

    public function createController($event)
    {
        $map=Yii::app()->controllerMap;
        $new=array(
            'extension'=>array(
                 'class'=>'ext.example-extension.controllers.ExtensionController',
                 'pageTitle'=>'How cool this extension is ? ;) ',
              ),
        );
        Yii::app()->controllerMap=CMap::mergeArray($map,$new);
    }

    public function generateMetaTitle($event)
    {
        $controllerName=$this->controller->id;
        $actionName=$this->controller->action->id;
        
        $title=array();
        $title[]=Yii::app()->name;
        $title[]=preg_replace('/[^a-z]/ix',' ',Yii::t($this->controller->id,ucfirst($controllerName)));
        $title[]=preg_replace('/[^a-z]/ix',' ',Yii::t($this->controller->id,ucfirst($actionName)));
        $title='Modified from within extension - '.implode(' - ',$title);

        $event->params['output']=preg_replace('/(<title>)([^<]*)(<\/title>)/ix','$1'.$title.'$3',$event->params['output']);
    }
    
    public function registerCss($event)
    {
        Yii::app()->clientScript->registerCss('example','a{color:red;font-weight:bold}');
    }
    
    public function addMenuItem($event)
    {
        Yii::app()->clientScript->registerScript('menu-item','
            if($("#mainmenu").length > 0){
                var $ul=$("#mainmenu ul:first");
                var $li=$("<li class=\"ecc\"><a href=\"'.Yii::app()->createUrl('/extension/index').'\">Extension Controller</a></li>");
                $li.appendTo($ul);
            }
        ');
    }
}