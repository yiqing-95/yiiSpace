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
    /**
     * @property boolean whether to enable debug mode.
     */
    public $debug = false;

    private $_assetsUrl;
    /**
     * borrow  from Rights module!
     * Publishes the module assets path.
     * @return string the base URL that contains all published asset files of photo.
     */
    public function getAssetsUrl()
    {
        if( $this->_assetsUrl===null )
        {
            $assetsPath = Yii::getPathOfAlias($this->getId(). '.assets');

            // We need to republish the assets if debug mode is enabled.
            if( $this->debug===true )
                $this->_assetsUrl = Yii::app()->getAssetManager()->publish($assetsPath, false, -1, true);
            else
                $this->_assetsUrl = Yii::app()->getAssetManager()->publish($assetsPath);
        }

        return $this->_assetsUrl;
    }

	public function init()
	{
		// this method is called when the module is being created
		// you may place code here to customize the module or the application

		// import the module-level models and components
		$this->setImport(array(
			'photo.models.*',
			'photo.components.*',
		));
       //die(__METHOD__);
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
            'photo/<controller:home>/<action:\w+>'=>'photo/home/<action>',

            'photo/install'=>'photo/install',

            'photo/<controller:home>/*'=>'photo/home/block',

            'photo/glean/<action:\w+>'=>'photo/glean/<action>',
            'photo/glean/<action:\w+>/*'=>'photo/glean/<action>',

            'photo/<action:\w+>'=>'photo/photo/<action>',
            'photo/<action:\w+>/*'=>'photo/photo/<action>',

            'album/<action:\w+>'=>'photo/photoAlbum/<action>',
            'album/<action:\w+>/*'=>'photo/photoAlbum/<action>',
        );
    }

    //.......................................................................\\
    // 模块间通信数据的格式 尽量用php基本类型 不要用对象传递 这样在变为远程调用时 可以无缝迁移！
    /**
     * ajax 切换评论列表时需要计算某个实体是否有删除和编辑权
     * @param $params
     * @return mixed
     */
    public function serviceCanDeleteAndEditComment($params){
        // return $params ;
        return false ;
    }

    //.......................................................................//
}
