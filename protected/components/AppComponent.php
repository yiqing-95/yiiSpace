<?php
/**
 * User: yiqing
 * Date: 12-7-19
 * Time: 下午4:58
 * To change this template use File | Settings | File Templates.
 *------------------------------------------------------------
 *------------------------------------------------------------
 */
class AppComponent extends CComponent
{

    /**
     * @static
     * @param string $componentId
     * @param array $config
     */
    static protected function getComp($componentId = '', $config = array())
    {
        if (!isset(Yii::app()->{$componentId})) {
            Yii::app()->setComponents(array(
                $componentId => $config,
            ), false);
        }
        return Yii::app()->{$componentId};
    }

    /**
     * @static
     * @return Curl
     */
    static public function curl()
    {
        return self::getComp('curl', array('class' => 'application.extensions.curl.Curl'));
    }

    /**
     * @static
     * @return CImageComponent
     * @see http://www.yiiframework.com/extension/image
     */
    static public function image()
    {
        return self::getComp('image', array(
            'class' => 'application.extensions.image.CImageComponent',
            // GD or ImageMagick
            'driver' => 'GD',
            // ImageMagick setup path
            // 'params'=>array('directory'=>'/opt/local/bin'),
        ));
    }

    /**
     * @static
     * @return CmsSettings
     */
    static public function settings()
    {
        return Yii::app()->settings;
    }

    /**
     * @static
     * @return EPhpThumb
     */
    static public function phpThumb()
    {
        return self::getComp('phpThumb', array(
            'class' => 'ext.EPhpThumb.EPhpThumb',
            'options' => array()
        ));
    }

}