<?php
/**
 * Created by PhpStorm.
 * User: yiqing
 * Date: 13-12-10
 * Time: 下午4:52
 * To change this template use File | Settings | File Templates.
 */

class AliceApi  extends CApplicationComponent
{
    // alice plugins
    const PLUGIN_BUTTON = 'button';

    /**
     * @var bool whether we should copy the asset file or directory even if it is already published before.
     */
    public $forceCopyAssets = false;

    /**
     * @var string
     */
    private $_assetsUrl;

    /**
     * Registers the alice CSS.
     * @param string $url the URL to the CSS file to register.
     */
    public function registerCoreCss($url = null)
    {
        if ($url === null) {
            $fileName = YII_DEBUG ? 'one-debug.css' : 'one.css';
            $url = $this->getAssetsUrl() . '/dist/' . $fileName;
        }
        Yii::app()->clientScript->registerCssFile($url);
    }



    /**
     * Returns the url to the published assets folder.
     * @return string the url.
     */
    protected function getAssetsUrl()
    {
        if (isset($this->_assetsUrl)) {
            return $this->_assetsUrl;
        } else {
            $assetsPath = Yii::getPathOfAlias('alice.assets');
            $assetsUrl = Yii::app()->assetManager->publish($assetsPath, false, -1, $this->forceCopyAssets);
            return $this->_assetsUrl = $assetsUrl;
        }
    }

}