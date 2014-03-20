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
        $forYaAn = <<<GRAY_PAGE
 html {
            filter: progid:DXImageTransform.Microsoft.BasicImage(grayscale=1);
            -webkit-filter: grayscale(100%);
            filter: url("data:image/svg+xml;utf8,<svg xmlns=\'http://www.w3.org/2000/svg\'><filter id=\'grayscale\'><feColorMatrix type=\'matrix\' values=\'0.3333 0.3333 0.3333 0 0 0.3333 0.3333 0.3333 0 0 0.3333 0.3333 0.3333 0 0 0 0 0 1 0\'/></filter></svg>#grayscale"); /* Firefox 10+, Firefox on Android */
        }

        img {
            _filter: progid:DXImageTransform.Microsoft.BasicImage(grayscale=0);
            -webkit-filter: grayscale(100%);
            filter: url("data:image/svg+xml;utf8,<svg xmlns=\'http://www.w3.org/2000/svg\'><filter id=\'grayscale\'><feColorMatrix type=\'matrix\' values=\'0.3333 0.3333 0.3333 0 0 0.3333 0.3333 0.3333 0 0 0.3333 0.3333 0.3333 0 0 0 0 0 1 0\'/></filter></svg>#grayscale"); /* Firefox 10+, Firefox on Android */
        }
GRAY_PAGE;

        // $this->clientScript->registerCss('for_yaAn',$forYaAn);

        $loginRequiredAjaxResponse = $this->user->loginRequiredAjaxResponse ;

        $userLoginUrl = $this->createUrl('/site/login') ;
        if (!empty($loginRequiredAjaxResponse)){
            // 判断是否ajax请求 但超时登录了 参考CWebUser 里面的loginRequired方法对ajax时的处理

            $ajaxLoginRequiredHandler = <<<EOD
                $.ajaxSetup({
                  dataFilter:function (data, type) {
                     // 对Ajax返回的原始数据进行预处理
                     if (data == "{$loginRequiredAjaxResponse}") {
                       alert("登录超时！");
                        window.location.href = "{$userLoginUrl}";
                    }
                     return data ; // 返回处理后的数据
                  }
                });
EOD;
            Yii::app()->clientScript->registerScript('ajaxLoginRequired', $ajaxLoginRequiredHandler);
           /*
            Yii::app()->clientScript->registerScript('ajaxLoginRequired', $ajaxLoginRequiredHandler . '

            jQuery("body").ajaxComplete(
           // jQuery("body").ajaxSuccess(
                function(event, request, options) {
                    if (request.responseText == "'.Yii::app()->components['user']->loginRequiredAjaxResponse.'") {
                        window.location.href = "'. $this->createUrl(UserHelper::getLoginUrl()) .'";
                    }
                }
            );
         ');*/
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
