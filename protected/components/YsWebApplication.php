<?php
/**
 * Created by JetBrains PhpStorm.
 * User: yiqing
 * Date: 12-8-20
 * Time: 下午12:11
 * To change this template use File | Settings | File Templates.
 */
class YsWebApplication extends CWebApplication
{

    /**
     * The post-filter for controller actions.
     * This method is invoked after the currently requested controller action and all its filters
     * are executed. You may override this method with logic that needs to be done
     * after all controller actions.
     * @param CController $controller the controller
     * @param CAction $action the action
     */
    public function afterControllerAction($controller, $action)
    {

    }



    /**
     * @param int $status
     * @param bool $exit
     */
    public function end($status = 0, $exit = true)
    {

        $controller = $this->getController();
        $action = $controller->getAction();
        if ($action->getId() == 'login') {
            if ($this->request->getIsPostRequest()) {
                /*
                EasyQuery::instance('action_feed')->insert(array(
                    'uid' => user()->getId(),
                    'action_type' => 3,
                    'action_time' => time(),
                    'data' => CJSON::encode(array('user' => 'xxx')),
                    //'passive_user',
                    //'passive_user_var'
                ));*/
            }
        }
        parent::end($status, $exit);

    }
}
