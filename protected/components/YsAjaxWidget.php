<?php
/**
 * Created by PhpStorm.
 * User: yiqing
 * Date: 14-1-6
 * Time: 上午1:48
 */

/**
 * 该widget 数据可能需要ajax请求
 * 比如第一次先加载下 widget的框架部分
 * 二次用ajax请求其内容部分！
 *
 * 按理每个请求都会路由到一个控制器的动作上 这里集中路由给了
 * 系统控制器的actionRunWidget方法 这样就不需要多余的动作来处理这样的事情了
 * 没有继承该类的widget 是不可以直接运行的 安全角度！
 *
 * Class YsAjaxWidget
 */
class YsAjaxWidget  extends YsWidget implements IRunnableWidget{

    /**
     * @var string
     */
    public $actionRoute = '/sys/runWidget';

    /**
     * @var bool
     */
    public $isAjaxRequest = false ;

    /**
     * @var string
     */
    public $ajaxUrl = '';

    /**
     *
     */
    public function init(){
        $this->ajaxUrl = Yii::app()->createUrl($this->actionRoute,
          array(
              'class'=>$this->getClassAlias(),
              'options'=>$this->getOptions(),
          )
        );

        $this->isAjaxRequest = Yii::app()->request->getIsAjaxRequest() ;

        parent::init();
    }

    /**
     * 获取当前类的别名
     *
     * @return bool|string
     */
    public function getClassAlias(){
        $rf = new ReflectionClass($this);
      return YiiUtil::getAliasOfPath($rf->getFileName());
    }

    /**
     * 获取当前widget 可配置的参数配置
     * 第二次用ajax请求时会传递的参数
     *
     * 注意 options 是widget对外的公共变量 也可以通过$_GET /$_POST
     * 来传递变量
     *
     * @return array
     */
    public function getOptions(){
            return array() ;
    }


}