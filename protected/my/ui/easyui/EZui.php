<?php
/**
 * User: yiqing
 * Date: 13-1-22
 * Time: 下午6:52
 * change template => | Settings | File Templates.
 * ------------------------------------------------
 * ------------------------------------------------
 */
class EZui extends CComponent
{


    /**
     * @var string
     */
    public static  $theme = 'bootstrap';

    /**
     * @var string
     */
    public static  $themeUrl;

    /**
     * @var string URL where to look assets.
     */
    public static  $assetsUrl;

    /**
     * @var string
     */
    public static  $assetsPath = '';

    /**
     * @var bool
     */
    public static  $debug = YII_DEBUG;

    /**
     * @var string
     */
    public static  $scriptFile = 'jquery.easyui.min.js';

    /**
     * @var string
     */
    public static  $cssFile = 'easyui.css';


  
   
    protected  static  function publishAssets()
    {
        if (empty(self::$assetsUrl)) {
            self::$assetsPath = $assetsPath = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'assets';
            if (self::$debug == true) {
                self::$assetsUrl = Yii::app()->assetManager->publish($assetsPath, false, -1, true);
            } else {
                self::$assetsUrl = Yii::app()->assetManager->publish($assetsPath);
            }
            self::$themeUrl = self::$assetsUrl.'/themes';
        }
    }

    /**
     * Registers the core script files.
     * This method registers jquery and easyUi JavaScript files and the theme CSS file.
     */
    public  function registerCoreScripts()
    {
        self::publishAssets() ;

        $cs = Yii::app()->getClientScript();
        $cs->registerCssFile(self::$themeUrl . '/' . self::$theme . '/' . self::$cssFile);
        $cs->registerCssFile(self::$themeUrl . '/icon.css');

        $cs->registerCoreScript('jquery');

        $cs->registerScriptFile(self::$assetsUrl . '/' . self::$scriptFile, CClientScript::POS_END);
    }


   public  static function droppable($dataOptions=array(),$htmlOptions=array()){

    }
}
