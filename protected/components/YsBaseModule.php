<?php

/**
 * Description of LBaseModule
 *
 * @author yiqing
 */
class YsBaseModule extends CWebModule
{
    private $_assetsUrl;

    /**
     * @return void
     * 一般在这里做一些所有进入该模块的行为都要做的事情
     * 比如css js 发布 布局文件的设置 要导入什么东东等等
     */
    public function init()
    {
        parent::init();
        /*$this->setImport(array(
                              $this->id . '.models.*',
                              $this->id . '.components.*',
                         ));*/
    }

    /**
     * enture the $path exist
     * @param type $path
     */
    protected function ensureDirExists($path)
    {
        if ($path === false || !is_dir($path))
            mkdir($path, 0777, true); // mkdir($path,0777);
    }

    /**
     * @param string $defaultDir
     * @return string the base URL that contains all published asset files of gii.
     */
    public function getAssetsUrl($defaultDir = 'assets')
    {
        $modulePathOfAlias = $this->id . '.' . $defaultDir;
        $this->ensureDirExists(Yii::getPathOfAlias($modulePathOfAlias));
        if ($this->_assetsUrl === null)
            $this->_assetsUrl = Yii::app()->getAssetManager()->publish(
                Yii::getPathOfAlias($modulePathOfAlias)
                , false, -1, YII_DEBUG
            );
        return $this->_assetsUrl;
    }

    /**
     * @param string $value the base URL that contains all published asset files of gii.
     */
    public function setAssetsUrl($value)
    {
        $this->_assetsUrl = $value;
    }

    /**
     * @static
     * @param $cateName
     * @param $msg
     * @param array $params
     * @param null $source
     * @param null $language
     * @return string
     */
    public static function t($cateName, $msg, $params = array(), $source = null, $language = null)
    {
        $module = Yii::app()->controller->module;
        $prefix = '';
        if (!is_null($module)) {
            $prefix = ucfirst($module->id) . 'Module.';
        }
        //echo $prefix.$cateName;
        return Yii::t($prefix . $cateName, $msg, $params, $source, $language);
    }

    /**
     * @return array
     * 获取要发布的插件接口
     * 如 array(
    'IWeiboPlugin' => 'weibo.plugins.interfaces.IWeiboPlugin',
     *
     * );
     */
    public function getPluginInterfaces()
    {
        return array();
    }

    /**
     * @return array
     * 获取实现别的模块的接口实现
     * array(
    'MediaWeiboPluginImpl'=>'media.plugins.implements.MediaWeiboPluginImpl'
     * );
     */
    public function getPluginImplements()
    {
        return array();
    }

    /**
     * @param $uid
     * @param bool $isRelative
     * @return string
     */
    public function getUploadDir($uid, $isRelative = false)
    {
        $relativePath = 'data/uploads/' . $uid . '/' . date('Y') . date('m' . '/');
        return $isRelative ? $relativePath : Yii::app()->getBasePath() . '/../' . $relativePath;
    }

    /**
     * @param $uid
     * @param bool $isRelative
     * @return string
     */
    public function getSaveDir($uid, $isRelative = false)
    {
        $relativePath = 'data/' . $this->id . '/' . $uid . '/' . date('Y') . date('m' . '/');
        return $isRelative ? $relativePath : Yii::app()->getBasePath() . '/../' . $relativePath;
    }
}


