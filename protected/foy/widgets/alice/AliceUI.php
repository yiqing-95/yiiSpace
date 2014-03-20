<?php
/**
 * Created by PhpStorm.
 * User: yiqing
 * Date: 13-12-4
 * Time: 下午5:08
 * To change this template use File | Settings | File Templates.
 */

class AliceUI extends CComponent {

    /**
     * @var string URL where to find assets.
     */
    public static  $assetsUrl;

    /**
     * @var string
     */
    public static  $assetsPath ;

    /**
     * @var bool
     */
    public static  $debug = YII_DEBUG;


    protected  static  function publishAssets()
    {
        if (empty(self::$assetsUrl)) {
            self::$assetsPath = $assetsPath = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'assets';
            if (self::$debug == true) {
                self::$assetsUrl = Yii::app()->assetManager->publish($assetsPath, false, -1, true);
            } else {
                self::$assetsUrl = Yii::app()->assetManager->publish($assetsPath);
            }
        }
    }

    /**
     * @var bool whether registered or not
     */
    protected  static $registered = false ;

    /**
     * register the core css file for aliceUi
     */
    public static function registerCoreCss(){
        if(self::$registered) return ;

        self::publishAssets() ;
        $assetsUrl = self::$assetsUrl ;
        $cssUrl = self::$debug ? $assetsUrl.'/dist/one-debug.css': $assetsUrl.'/dist/one.css';
        Yii::app()->clientScript->registerCssFile($cssUrl);
    }
} 