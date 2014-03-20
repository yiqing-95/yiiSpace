<?php
Yii::import('application.modules.friend.models.*');

/**
 * TODO 消息系统的扩充 通过msg表的type来做到 扩充方式参考status模块的实现！
 *
 * Class MsgModule
 */
class MsgModule extends CWebModule implements IUrlRewriteModule
{
	public function init()
	{
		// this method is called when the module is being created
		// you may place code here to customize the module or the application

		// import the module-level models and components
		$this->setImport(array(
			'msg.models.*',
			'msg.components.*',
		));

        if(Yii::app() instanceof CWebApplication){
            Yii::app()->onModuleCreate(new CEvent($this));
        }
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
           // 'msg/<action:\w+>'=>'msg/msg/<action>',
        );
    }
}
