<?php
/**
 *
 */
class TestModule extends CWebModule implements IUrlRewriteModule
{
    /**
     * @var string
     */
    public $layout = 'test';

	public function init()
	{
		// this method is called when the module is being created
		// you may place code here to customize the module or the application

		// import the module-level models and components
		$this->setImport(array(
			'test.models.*',
			'test.components.*',

            // EventInterceptor is required by EventBridgeBehavior
            'test.extensions.event-interceptor.*',

            'test.services.*',
		));

        Yii::setPathOfAlias('bootstrap', Yii::getPathOfAlias('test.extensions.bootstrap'));

        $components = array(
            'BarcodeGenerator' => array(
                'class' => 'test.extensions.BarcodeGenerator',
            ),
            'bootstrap' => array(
                'class' => 'bootstrap.components.Bootstrap',
                'responsiveCss'=>true
            ),
            //............................................................................
            'events' => array(
                'class'  => 'test.extensions.static-events.EventRegistry',
                'attach' => array(
                    // Attach to application events.
                    // Again a stupid example. Since there will ever be only one
                    // application instance, we could as well use normal per instance
                    // event binding like it is done normally in Yii. But it shows how it
                    // is meant to be done...
                    'CApplication' => array(
                        'onBeginRequest' => array(
                            function( $event ) {
                                Yii::log( 'CApplication::onBeginRequest', CLogger::LEVEL_TRACE );
                            },
                        ),
                        // 上面的那个事件估计拦截不到 只有下面才可以
                        'onEndRequest' => array(
                            function( $event ) {
                                Yii::log( 'CApplication::onEndRequest - first handler', CLogger::LEVEL_TRACE );
                            },
                            function( $event ) {
                                Yii::log( 'CApplication::onEndRequest - second handler', CLogger::LEVEL_TRACE );
                            },
                        ),
                    ),
                ),
            ),
            //............................................................................
        );
        // attach EventBridgeBehavior to application, so we can attach to
        // application events on a per class base.
        // @see http://www.yiiframework.com/extension/static-events/
        Yii::app()->attachBehavior('eventBridge',array(
            'class'  => 'test.extensions.static-events.EventBridgeBehavior',
        ));

        Yii::app()->setComponents($components, false);
        /* reference to the object will fire its init() and register css and js files*/
        Yii::app()->bootstrap;

        //===================================================================================
        // or to attach/ detach at runtime:
        $events = Yii::app()->events;
        // 给自己关联上eventBridge 这样便于把自己的所有事件被事件中心所关注
        $this->attachBehavior('eventBridge',array(
            'class'  => 'test.extensions.static-events.EventBridgeBehavior',
        ));
       // echo   YiiUtil::getPathOfClass($events);
        $events->attach( get_class($this), 'onTestEvent', function($event){

            Yii::log( 'TestModule::onEndRequest - test handler', CLogger::LEVEL_INFO );

        } );
        // 触发自己的事件！
        $this->onTestEvent(new CEvent($this));
        //===================================================================================

	}

    /**
     * @param $event
     * 定义一个测试事件
     */
    public function onTestEvent($event)
    {
        $this->raiseEvent('onTestEvent', $event);
    }

	public function beforeControllerAction($controller, $action)
	{
		if(parent::beforeControllerAction($controller, $action))
		{
			// this method is called before any module controller action is performed
			// you may place customized code here
			return true;
		}
		else
			return false;
	}

    /**
     * Method to return urlManager-parseable url rules
     * @return array An array of urlRules for this object
     * -------------------------------------------------------
     * return array(
     *  );
     *----------------------------------------------------------
     * 常用规则：
     * 模块名和控制器同名：'forum/<action:\w+>'=>'forum/forum/<action>',
     *
     *----------------------------------------------------------
     */
    public static function getUrlRules()
    {
       return array(
           'test/<action:\w+>'=>'test/default/<action>',
           'test/<action:\w+>/*'=>'test/test/<action>',
       );
    }
}
