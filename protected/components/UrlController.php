<?php
/**
 * User: yiqing
 * Date: 12-7-19
 * Time: 下午3:37
 * To change this template use File | Settings | File Templates.
 *------------------------------------------------------------
 * @see http://www.yiiframework.com/forum/index.php/topic/27133-how-to-create-urls-to-other-applications/page__hl__+hare+config__st__20
 *------------------------------------------------------------
 */
class UrlController extends Controller
{
    /**
     * @var array
     */
    protected $ipFilters = array('127.0.0.1', '::1');

    public function actionCreateRemote()
    {
        $request = Yii::app()->getRequest();
        if(!$this->allowIp($request->userHostAddress)){
            throw new CHttpException(403,"You are not allowed to access this page.");
        }

        $route = $request->getParam('route');
        $params = $request->getParam('params',array());

        // accepts calls from remote app; returns localized absolute url
        echo Yii::app()->createAbsoluteUrl($route,$params);
        die();
        Yii::app()->end();
    }

    /**
     * @static
     * @param $route
     * @param array $params
     * @param string $appName
     * @return int|mixed|string
     */
    public static function getRemoteUrl($route,$params=array(), $appName='frontend')
    {
        $appUrlMap = array(
          'frontend'  => 'http://localhost/my/yiiSpace/url/createRemote',
          'backend'=>'http://localhost/my/yiiSpace/admin.php/url/createRemote'
        );

        $appUrl = $appUrlMap[$appName];
        // makes calls to remote app using curl or other method; returns remote absolute url
        // $rc = new RestClient();
        //  return $rc->get($appUrl,array('route'=>$route,'params'=>$params));
        return AppComponent::curl()->run($appUrl,false,array('route'=>$route,'params'=>$params));
    }

    /**
     * Checks to see if the user IP is allowed by {@link ipFilters}.
     * @param string $ip the user IP
     * @return boolean whether the user IP is allowed by {@link ipFilters}.
     */
    protected function allowIp($ip)
    {
        if(empty($this->ipFilters))
            return true;
        foreach($this->ipFilters as $filter)
        {
            if($filter==='*' || $filter===$ip || (($pos=strpos($filter,'*'))!==false && !strncmp($ip,$filter,$pos)))
                return true;
        }
        return false;
    }
}