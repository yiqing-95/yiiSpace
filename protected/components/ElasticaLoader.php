<?php
/**
 * Created by PhpStorm.
 * User: yiqing
 * Date: 13-11-7
 * Time: 下午6:24
 * To change this template use File | Settings | File Templates.
 */
/**
 * @see http://www.yiiframework.com/forum/index.php/topic/38851-including-an-external-folder-with-subfolders-and-libraries
 * @see http://www.yiiframework.com/extension/yii-elastica
 *
 * Class ElasticaLoader
 *
 */
class ElasticaLoader extends CApplicationComponent
{
    public $libPath;

    //see: http://elastica.io/en#section-include
    public function __autoload_elastica ($class)
    {
        $path = str_replace('_', '/', $class);

        if (file_exists($this->libPath . $path . '.php')) {
            require_once($this->libPath . $path . '.php');
        }
    }


    public function init()
    {
        Yii::registerAutoloader(array($this,'__autoload_elastica'),true);
    }
}