<?php if ( ! defined('YII_PATH')) exit('No direct script access allowed');
/**
 * ControllerBehavior
 */
class ControllerBehavior extends CBehavior {
    
    public function events()
    {
        return array_merge(parent::events(), array(
            'onBeforeInit'=>'beforeInit',
            'onAfterInit'=>'afterInit',
            'onBeforeAction'=>'beforeAction',
            'onAfterAction'=>'afterAction',
            'onBeforeRender'=>'beforeRender',
            'onAfterRender'=>'afterRender',
            'onBeforeProcessOutput'=>'beforeProcessOutput',
            'onAfterProcessOutput'=>'afterProcessOutput',
        ));
    }
    
    /**
     * Responds to onBeforeInit event.
     * Overrides this method if you want to handle the corresponding event of the {@link CBehavior::owner owner}.
     * @param ControllerEvent $event event parameter
     */
    public function beforeInit($event)
    {
    }
    
    /**
     * Responds to onAfterInit event.
     * Overrides this method if you want to handle the corresponding event of the {@link CBehavior::owner owner}.
     * @param ControllerEvent $event event parameter
     */
    public function afterInit($event)
    {
    }
    
    /**
     * Responds to onBeforeAction event.
     * Overrides this method if you want to handle the corresponding event of the {@link CBehavior::owner owner}.
     * @param ControllerEvent $event event parameter
     */
    public function beforeAction($event)
    {
    }
    
    /**
     * Responds to onAfterAction event.
     * Overrides this method if you want to handle the corresponding event of the {@link CBehavior::owner owner}.
     * @param ControllerEvent $event event parameter
     */
    public function afterAction($event)
    {
    }
    
    /**
     * Responds to onBeforeRender event.
     * Overrides this method if you want to handle the corresponding event of the {@link CBehavior::owner owner}.
     * @param ControllerEvent $event event parameter
     */
    public function beforeRender($event)
    {
    }
    
    /**
     * Responds to onAfterRender event.
     * Overrides this method if you want to handle the corresponding event of the {@link CBehavior::owner owner}.
     * @param ControllerEvent $event event parameter
     */
    public function afterRender($event)
    {
    }
    
    /**
     * Responds to onBeforeProcessOutput event.
     * Overrides this method if you want to handle the corresponding event of the {@link CBehavior::owner owner}.
     * @param ControllerEvent $event event parameter
     */
    public function beforeProcessOutput($event)
    {
    }
    
    /**
     * Responds to onAfterProcessOutput event.
     * Overrides this method if you want to handle the corresponding event of the {@link CBehavior::owner owner}.
     * @param ControllerEvent $event event parameter
     */
    public function afterProcessOutput($event)
    {
    }
        
}