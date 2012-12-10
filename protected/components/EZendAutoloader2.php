<?php
/**
 * Created by JetBrains PhpStorm.
 * User: yiqing
 * Date: 12-12-4
 * Time: 下午4:57
 * To change this template use File | Settings | File Templates.
 */
class EZendAutoLoader2 {
    /**
     * @var array class prefixes
     */
    static $prefixes = array(
        'Zend'
    );

    /**
     * @var string path to where Zend classes root is located
     */
    static $basePath = null;

    /**
     * @var array
     * ---------------------------------
     * you can specify multiple basePath eg:
     * array(Yii::getPathOfAlias('application.vendors.phpseclib'),Yii::getPathOfAlias('application.extensions.Net')...);
     * ---------------------------------
     */
    static $basePaths = array();

    /**
     * Class autoload loader.
     *
     * @static
     * @param $className
     * @internal param string $class
     * @return boolean
     */
    static function loadClass($className){
        foreach(self::$prefixes as $prefix){
            if(strpos($className, $prefix.'_')!==false){
                if(empty(self::$basePaths)){
                    if(!self::$basePath) self::$basePath = Yii::getPathOfAlias("application.vendors").'/';
                    include self::$basePath.str_replace('_',DIRECTORY_SEPARATOR ,$className).'.php';
                    return class_exists($className, false) || interface_exists($className, false);
                }else{
                    $classLoaded = false ;
                    foreach(self::$basePaths as $basePath){

                        include rtrim($basePath,'/\\'). DIRECTORY_SEPARATOR .str_replace('_',DIRECTORY_SEPARATOR ,$className).'.php';
                        if(class_exists($className, false) || interface_exists($className, false)){
                            return true ;
                        }
                    }
                    return $classLoaded ;
                }
            }
        }
        return false;
    }
}