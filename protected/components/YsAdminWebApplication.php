<?php
/**
 * Created by JetBrains PhpStorm.
 * User: yiqing
 * Date: 12-8-20
 * Time: 下午12:11
 * To change this template use File | Settings | File Templates.
 */
class YsAdminWebApplication extends CWebApplication
{


    /**
     * The pre-filter for controller actions.
     * This method is invoked before the currently requested controller action and all its filters
     * are executed. You may override this method with logic that needs to be done
     * before all controller actions.
     * @param CController $controller the controller
     * @param CAction $action the action
     * @return boolean whether the action should be executed.
     */
    public function beforeControllerAction($controller,$action)
    {

        if(!empty($controller)){
            if($controller instanceof BackendController){
               // $controller->layout =  '//adminLayouts/main';
            }
        }

       return parent::beforeControllerAction($controller,$action);
    }


}
