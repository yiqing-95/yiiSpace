<?php if ( ! defined('YII_PATH')) exit('No direct script access allowed');


class ExtensionInit extends CApplicationComponent{
    
    private $maxPriority=100;
    
    private $minPriority=-100;
    
    protected $_priority=0;
    
    public function init()
    {
        parent::init();
    }
    
    public function run()
    {
    }
    
    public function getModule()
    {
        return Yii::app()->getController() ? Yii::app()->getController()->getModule() : null;
    }

    public function getController()
    {
        return Yii::app()->getController();
    }

    public function getPriority()
    {
        return (int)$this->_priority;
    }
    
    public function setPriority($int)
    {
        if($int < $this->minPriority || $int > $this->maxPriority)
            throw new CHttpException(500,Yii::t('app', 'The allowed priority limit is between {min} and {max}!',array(
                '{min}'=>$this->minPriority,'{max}'=>$this->maxPriority,
            )));
        $this->_priority=$int;
    }
    
}