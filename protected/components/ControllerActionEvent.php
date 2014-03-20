<?php
/**
 *
 * User: yiqing
 * Date: 13-3-12
 * Time: 下午11:52
 * To change this template use File | Settings | File Templates.
 * -------------------------------------------------------
 * -------------------------------------------------------
 */
class ControllerActionEvent extends CEvent
{

    const TIMING_BEFORE = 'before';
    const TIMING_AFTER = 'after';
    /**
     * @var bool
     */
    public $isValid = true;

    /**
     * @var string current action name/id
     */
    public $action = '';

    /**
     * @var CModel
     * mainly used for delete and update
     */
    public $model;

    /**
     * @var CModel[]
     * for batch operation
     */
    public $models;


    public $actionTiming = self::TIMING_BEFORE;

    /**
     * @param null $sender
     * @param string $action
     * @param null $actionTargets
     * @param string $timing
     */
    public function __construct($sender=null,$action='',$actionTargets=null,$timing = ControllerActionEvent::TIMING_BEFORE)
    {
        $this->sender=$sender;
        $this->action = $action;
        if(is_array($actionTargets)){
            $this->models = $actionTargets;
        }else{
            $this->model = $actionTargets ;
        }
        $this->actionTiming = $timing ;
    }
}
