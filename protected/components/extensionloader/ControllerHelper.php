<?php if ( ! defined('YII_PATH')) exit('No direct script access allowed');
/**
 * ControllerHelper
 */
class ControllerHelper
{
    /**
     * ControllerHelper::isController()
     * @return 
     */
    public static function isController($controllerName)
    {
        return Yii::app()->getController() && Yii::app()->getController()->getId()==$controllerName;
    }
    
    /**
     * ControllerHelper::isAction()
     * @return 
     */
    public static function isAction($action)
    {
        return Yii::app()->getController() && Yii::app()->getController()->getAction() && Yii::app()->getController()->getAction()->getId()==$action;
    }
    
    /**
     * ControllerHelper::isControllerAction()
     * @return 
     */
    public static function isControllerAction($controller,$action)
    {
        return self::isController($controller) && self::isAction($action);
    }
    
    /**
	 * ControllerHelper::toCamelCase()
	 * 
	 * @param mixed $str
	 * @return
	 */
	public static function toCamelCase($str,$startLowerCase=true) 
	{
        $str=preg_replace('/[^a-z0-9]/ix',' ',strtolower($str));
        $str=ucwords($str);
        $str=str_replace(' ','',$str);
        if($startLowerCase)
            $str[0]=strtolower($str[0]);
        return $str;
	}
}