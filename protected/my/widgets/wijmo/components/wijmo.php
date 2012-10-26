<?php
/**
 * Created by JetBrains PhpStorm.
 * User: yiqing
 * Date: 11-12-31
 * Time: 上午12:55
 * To change this template use File | Settings | File Templates.
 */
class wijmo extends CApplicationComponent
{
    /**
     * @var string the assets url for this extension.
     */
    protected $_assetsUrl;

    /**
     * Initializes the component.
     */
    public function init()
    {
        Yii::setPathOfAlias('wij', realpath(dirname(__FILE__) . '/..'));
        Yii::setPathOfAlias('wijmo', realpath(dirname(__FILE__) . '/..'));
    }

    /**
     * @return wijmo
     */
    public function registerCss()
    {
        Yii::app()->clientScript->registerCssFile($this->getAssetsUrl() . '/css/jquery.wijmo-open.2.0.5.css');
        return $this;
    }

    /**
     * Registers a Bootstrap JavaScript file.
     * @param string $fileName the file name.
     * @param integer $position the position of the JavaScript file.
     */
    public function registerScriptFile($fileName, $position = CClientScript::POS_HEAD)
    {
        Yii::app()->clientScript->registerScriptFile($this->getAssetsUrl() . '/js/' . $fileName, $position);
    }

    /**
     * Returns the url to assets publishing the folder if necessary.
     * @return string the assets url
     */
    protected function getAssetsUrl()
    {
        if ($this->_assetsUrl !== null)
            return $this->_assetsUrl;
        else
        {
            $assetsPath = Yii::getPathOfAlias('wijmo.assets');

            if (YII_DEBUG)
                $assetsUrl = Yii::app()->assetManager->publish($assetsPath, false, -1, true);
            else
                $assetsUrl = Yii::app()->assetManager->publish($assetsPath);

            return $this->_assetsUrl = $assetsUrl;
        }
    }
}