<?php
/**
 * Created by JetBrains PhpStorm.
 * User: yiqing
 * Date: 12-12-22
 * Time: 下午2:30
 * To change this template use File | Settings | File Templates.
 */
abstract class PrivacyGroupChecker extends CComponent
{

    /**
     * @var int the user which own the object
     */
    public $objectOwner;
    /**
     * @var int current visitor id
     */
    public $viewer;

    /**
     * @var string any necessary data passed to this checker
     * the format is string you should parse it yourself .
     * normally you can use json or php_serialize format
     */
    public $privacyData = '';

    /**
     * @var array list of built-in groupCheckers (name=>class)
     */
    public static $builtInCheckers = array(
        'public' => 'YsPublicGroupChecker',
        'friend' => 'YsFriendGroupChecker',
        'self' => 'YsSelfChecker',
    );


    /**
     * This method should be overridden by child classes.
     */
    abstract public  function check();

    /**
     * @static
     * @param $name
     * @param array $attributes
     * @return PrivacyGroupChecker
     */
    public static function createPrivacyGroupChecker($name, $attributes = array())
    {
        if (isset(self::$builtInCheckers[$name])) {
            $pathAlias = __CLASS__. __METHOD__;
            Yii::setPathOfAlias($pathAlias,dirname(__FILE__));
            Yii::import($pathAlias.'.privacyGroupCheckers.*');
            $className = Yii::import(self::$builtInCheckers[$name], true);
        } else {
            $className = Yii::import($name, true);
        }

        $groupChecker = new $className;
        foreach ($attributes as $name => $value){
            $groupChecker->$name = $value;
        }
        return $groupChecker;
    }


    /**
     * @param $value
     * @param bool $trim
     * @return bool
     */
    protected function isEmpty($value, $trim = false)
    {
        return $value === null || $value === array() || $value === '' || $trim && is_scalar($value) && trim($value) === '';
    }
}

