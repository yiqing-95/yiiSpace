<?php
/**
 * Created by JetBrains PhpStorm.
 * User: yiqing
 * Date: 12-11-17
 * Time: 下午2:33
 * To change this template use File | Settings | File Templates.
 */
class BasePhotoController extends Controller
{

    public $urlAppendParams = false;

    /**
     * @param string $route
     * @param array $params
     * @param string $ampersand
     * @return string
     *  复写父类同名方法 这样就可以再CRUD视图中创建的url中附加$_GET中的参数了
     */
    public function createUrl($route, $params = array(), $ampersand = '&')
    {
        if (is_array($this->urlAppendParams)) {
            $params = CMap::mergeArray($params, $this->urlAppendParams);
        }
        /*
        if (isset($_GET['u'])) {
           if($this->endsWith($route,'create') || $this->endsWith($route,'update') || $this->endsWith($route,'edit')){

           }elseif(Yii::app()->controller->getModule()->getId() == 'photo'){
               $params['u'] = $_GET['u'];
           }
        }*/
        return parent::createUrl($route, $params, $ampersand);
    }

    public function beginsWith($str, $sub)
    {
        return (substr($str, 0, strlen($sub)) === $sub);
    }

    public function endsWith($str, $sub)
    {
        return (substr($str, strlen($str) - strlen($sub)) === $sub);
    }

}
