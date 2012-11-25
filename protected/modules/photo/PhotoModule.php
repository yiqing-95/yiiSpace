<?php
//Yii::setPathOfAlias('PhotoModule' , dirname(__FILE__));

class PhotoModule extends CWebModule implements IUrlRewriteModule
{

    /**
     * @var array

    public $controllerMap = array(
        'album'=>array(
            'class'=>'PhotoModule.controllers.photoAlbumController'),
    );
     */


	public function init()
	{
		// this method is called when the module is being created
		// you may place code here to customize the module or the application

		// import the module-level models and components
		$this->setImport(array(
			'photo.models.*',
			'photo.components.*',
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
            'photo/install'=>'photo/install',

            'photo/<action:\w+>'=>'photo/photo/<action>',
            'photo/<action:\w+>/*'=>'photo/photo/<action>',

            'album/member'=>array('photo/photoAlbum/member','defaultParams'=>$_GET),
            'album/<action:\w+>'=>'photo/photoAlbum/<action>',
            'album/<action:\w+>/*'=>'photo/photoAlbum/<action>',
        );
    }
}
