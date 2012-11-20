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

    public $urlAppendParams = false ;

    /**
     * @param string $route
     * @param array $params
     * @param string $ampersand
     * @return string
     *  复写父类同名方法 这样就可以再CRUD视图中创建的url中附加$_GET中的参数了
     */
    public function createUrl($route,$params=array(),$ampersand='&')
    {
        if(is_array($this->urlAppendParams)){
            $params = CMap::mergeArray($params , $this->urlAppendParams);
        }
        return parent::createUrl($route,$params,$ampersand);
    }
}
