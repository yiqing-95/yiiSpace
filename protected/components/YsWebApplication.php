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

        if (!empty($this->user->loginRequiredAjaxResponse)){
            Yii::app()->clientScript->registerScript('ajaxLoginRequired', '
            //jQuery("body").ajaxComplete(
            jQuery("body").ajaxSuccess(
                function(event, request, options) {
                    if (request.responseText == "'.Yii::app()->components['user']->loginRequiredAjaxResponse.'") {
                        window.location.href = "'. $this->createUrl(UserHelper::getLoginUrl()) .'";
                    }
                }
            );
        ');
        }
       return parent::beforeControllerAction($controller,$action);
    }


    /**
     * @param int $status
     * @param bool $exit
     */
    public function end($status = 0, $exit = true)
    {

        $controller = $this->getController();
        if(!empty($controller) && ( $action = $controller->getAction()) !==null){
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
        }
        parent::end($status, $exit);

    }

    public function createUrl($route, $params = array(), $ampersand = '&')
    {
        // Yii::log($route,CLogger::LEVEL_INFO);
        $hooks = $this->getCreateUrlHooks();
        if(!empty($hooks)){
            if(isset($hooks[$route])){
                // 允许底层 其他模块附加参数
              $params =  $this->evaluateExpression($hooks[$route],array('route'=>$route,'params'=>$params));
            }
        }

        return $this->getUrlManager()->createUrl($route, $params, $ampersand);
    }

    /**
     * @var array
     */
    protected  $createUrlHooks;
    /**
     * @return array
     */
    protected function getCreateUrlHooks()
    {
        if(!isset($this->createUrlHooks)){
            $hooks = YsHookService::getHooks('app', 'createUrl');
            $urlExpressions = array();
            foreach ($hooks as $hook) {
                $content = CJSON::decode($hook->hook_content);
                if (is_array($routes = $content['route'])) {
                    foreach ($routes as $route) {
                        $urlExpressions[$route] = $content['paramsExpression'];
                    }
                } else {
                    $urlExpressions[$content['route']] = $content['paramsExpression'];
                }

            }
            $this->createUrlHooks = $urlExpressions ;
        }
        return $this->createUrlHooks;
    }
}
