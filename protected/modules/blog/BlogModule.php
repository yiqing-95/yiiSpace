<?php
Yii::setPathOfAlias('BlogModule' , dirname(__FILE__));

class BlogModule extends CWebModule implements IUrlRewriteModule
{

    /**
     * @var string
     */
    public $defaultController = 'post';

    public $controllerMap=array(
        'my'=>array(
            'class'=>'BlogModule.controllers.MyPostController'),
    );


    static  public function getDbTablePrefix(){
        return 'blog_';
    }
    /**
     * @var array

    public $controllerMap = array(
        'album'=>array(
            'class'=>'BlogModule.controllers.blogAlbumController'),
    );
     */


	public function init()
	{
		// this method is called when the module is being created
		// you may place code here to customize the module or the application

		// import the module-level models and components
		$this->setImport(array(
			'blog.models.*',
			'blog.components.*',
		));

      //  Yii::app()->theme = 'dlfBlog';
        // Raise onModuleCreate event.
       // Yii::app()->onModuleCreate(new CEvent($this));
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
            'view/<controller:\w+>-<title:.*?>-<id:\d+>'=>'blog/<controller>/view',
            'tags/<tag:.*?>'=>'blog/post/index',
            'category/<alias:.*?>-<category:.*?>'=>'blog/post/index',
            'date/<year:\d+>-<month:\d+>'=>'blog/post/index',
           // '/'=>'blog/post/index', //使用home
        );
    }
}
