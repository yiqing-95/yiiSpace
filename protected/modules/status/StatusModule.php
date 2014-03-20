<?php

class StatusModule extends CWebModule implements IUrlRewriteModule
{
	public function init()
	{
		// this method is called when the module is being created
		// you may place code here to customize the module or the application

		// import the module-level models and components
		$this->setImport(array(
			'status.models.*',
			'status.components.*',
		));

        // Raise onModuleCreate event.
        Yii::app()->onModuleCreate(new CEvent($this));
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
           // 'status/home/<action:\w+>'=>'status/home/<action>',
           // 'status/home/<action:\w+>(/*)*'=>'status/home/<action>',
           // 'status/<action:\w+>'=>'status/status/<action>',
           // 'status/<action:\w+>/*'=>'status/status/<action>',
          // 'status/<action:\w+>/*'=>'status/status/<action>',
        );
    }
}
