<?php

class SdashboardModule extends CWebModule
{
    /**
     * @var string
     */
    public $defaultController = 'dashboard';
    /**
     * @var bool
     */
    public $allowAjax = true;

    /**
     * @var bool
     */
    public $debug = YII_DEBUG;

    /**
     * @var string
     */
    protected $_assetsUrl = '';
    /**
     *
     */
    public function init()
	{
		$this->registerScripts();

		$this->setImport(array(
			'sdashboard.models.*',
			'sdashboard.components.*',
		));
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
     * Registers the necessary scripts.
     */
    public function registerScripts()
    {
        // Get the url to the module assets
        $assetsUrl = $this->getAssetsUrl();

        // Register the necessary scripts
        $cs = Yii::app()->getClientScript();

        //the css to use
      $cs -> registerCssFile($assetsUrl . '/css/sdashboard.css')
        -> registerCssFile('http://ajax.googleapis.com/ajax/libs/jqueryui/1.7.1/themes/start/jquery-ui.css')
         -> registerCssFile($assetsUrl . '/css/jquery.toastmessage.css')
         ->registerCssFile($assetsUrl . '/markitup/skins/markitup/style.css')
        -> registerCssFile($assetsUrl . '/markitup/sets/bbcode/style.css');

        // the js to use
       $cs->registerCoreScript( 'jquery.ui' )
         -> registerScriptFile($assetsUrl . "/js/jquery.toastmessage.js", CClientScript::POS_END)
        -> registerScriptFile($assetsUrl . "/js/bootbox.min.js", CClientScript::POS_END)
         -> registerScriptFile($assetsUrl . "/markitup/sets/bbcode/set.js", CClientScript::POS_BEGIN)
        -> registerScriptFile($assetsUrl . "/markitup/jquery.markitup.js", CClientScript::POS_BEGIN)
        -> registerScriptFile($assetsUrl . "/js/sdashboard.js", CClientScript::POS_END);

    }

    /**
     * @return string
     */
    public function getAssetsUrl()
    {
        if( $this->_assetsUrl===null )
        {
            $assetsPath = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'assets';

            // We need to republish the assets if debug mode is enabled.
            if( $this->debug===true )
                $this->_assetsUrl = Yii::app()->getAssetManager()->publish($assetsPath, false, -1, true);
            else
                $this->_assetsUrl = Yii::app()->getAssetManager()->publish($assetsPath);
        }

        return $this->_assetsUrl;
    }

}
