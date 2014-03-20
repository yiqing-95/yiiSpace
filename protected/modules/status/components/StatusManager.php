<?php
/**
 * Created by JetBrains PhpStorm.
 * User: yiqing
 * Date: 12-8-16
 * Time: 下午5:02
 * To change this template use File | Settings | File Templates.
 */
class StatusManager
{

    /**
     * @var array
     * 核心状态类型的处理器
     */
    protected static $coreTypeHandler = array(
        'image' => 'CoreStatusTypeHandler',
        'video' => 'CoreStatusTypeHandler',
        'link' => 'CoreStatusTypeHandler',
        'update' => 'CoreStatusTypeHandler',
    );

    /**
     * @param $statusType
     * @return mixed
     */
    protected static function getCoreStatusTypeHandler($statusType){
       $statusTypeHandlerClass = self::$coreTypeHandler[$statusType];
        $statusTypeHandlerObj = Yii::createComponent(array('class' => $statusTypeHandlerClass));
        self::$statusTypeHandlers[$statusType] = $statusTypeHandlerObj;
        return $statusTypeHandlerObj ;
    }

    /**
     * @param $statusType
     * @return AbstractStatusHandler|mixed|null
     */
    public static function  getStatusTypeHandler($statusType){
        if(isset(self::$statusTypeHandlers[$statusType])){
            return self::$statusTypeHandlers[$statusType];
        }else{
            if(isset(self::$coreTypeHandler[$statusType])){
                return self::getCoreStatusTypeHandler($statusType);
            }else{
                // 非核心类型
                $statusTypeHandlerObj = self::getExtStatusHandler($statusType);
                if(empty($statusTypeHandlerObj)){
                    return new UnKnownStatusTypeHandler() ;
                }else{
                    return $statusTypeHandlerObj ;
                }
            }
        }
    }


    /**
     * @var array
     * 同一请求中 缓存已经加载过的类型处理器
     * 要注意区分 常规缓存跟静态变量作为缓存使用的区别！
     */
    static protected $statusTypeHandlers = array();


    /**
     * @param string $statusTypeReference
     * @return AbstractStatusHandler|null
     */
    static public function getExtStatusHandler($statusTypeReference = '')
    {

        if (!isset(self::$statusTypeHandlers[$statusTypeReference])) {
            // 取db  仍旧可以使用缓存
            $eq = EasyQuery::instance('status_type');
            $statusTypeRow = $eq->queryRow('id=:type', array(':type' => $statusTypeReference));

            $statusTypeHandlerClass = $statusTypeRow['handler'];
            if (empty($statusTypeHandlerClass)) {
                return null;
            }
            $statusTypeHandlerObj = Yii::createComponent(array('class' => $statusTypeHandlerClass));

            self::$statusTypeHandlers[$statusTypeReference] = $statusTypeHandlerObj;
        }
        return self::$statusTypeHandlers[$statusTypeReference];
    }
}

class CoreStatusTypeHandler
{

    protected static $bodyViews = array(
        'image' => 'status.plugins.image.imageView',
        'video' => 'status.plugins.video.videoView',
        'link' => 'status.plugins.link.linkView',
    );

    protected static $typeLabels = array(
        'image' => '发布图片',
        'video' => '分享视频',
        'link' => '分享链接',
        'update'=> '发布状态'
    );


    /**
     * @var string
     * 动作执行者的链接
     */
    public $actorLink ;
    /**
     * @var User|array
     */
    public $actor;

    /**
     * @var array
     */
    public $data;

    public function init(){

    }
    /**
     * @return mixed
     */
    public function renderTitle()
    {

        $actionLabel = self::$typeLabels[$this->data['type']];

        echo " {$this->actorLink} {$actionLabel} ";
    }

    /**
     * @return mixed
     */
    public function renderBody()
    {
        if(isset(self::$bodyViews[$this->data['type']])){
            $bodyView = self::$bodyViews[$this->data['type']];
            Yii::app()->getController()->renderPartial($bodyView, array('data' => $this->data));
        }else{
            echo $this->data['update'] ;
        }


    }
}



class UnKnownStatusTypeHandler
{


    /**
     * @var string
     * 动作执行者的链接
     */
    public $actorLink ;
    /**
     * @var User|array
     */
    public $actor;

    /**
     * @var array
     */
    public $data;

    public function init(){

    }

    /**
     * @return mixed
     */
    public function renderTitle()
    {
        echo __METHOD__;
    }

    /**
     * @return mixed
     */
    public function renderBody()
    {
        echo __METHOD__ ;
    }
}
