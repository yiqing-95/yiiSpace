<?php
/**
 *  
 * User: yiqing
 * Date: 13-7-11
 * Time: 下午8:59
 * To change this template use File | Settings | File Templates.
 * -------------------------------------------------------
 * @see https://github.com/ded/Kizzy
 * @see http://www.dustindiaz.com/javascript-cache-provider
 * -------------------------------------------------------
 */

class Kizzy extends CWidget
{

    /**
     * @var string
     */
    public $assetsPath ;

    /**
     * @var string
     */
    public $baseUrl;

    /**
     * @var bool
     */
    public $debug = YII_DEBUG;

    /**
     * @var \CClientScript
     */
    protected $cs;


    public $jsFilePos = CClientScript::POS_HEAD;

    /**
     * @return $this
     */
    public function publishAssets()
    {
        if (empty($this->baseUrl)) {
            $this->assetsPath =  $assetsPath = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'assets';
            if ($this->debug == true) {
                $this->baseUrl = Yii::app()->assetManager->publish($assetsPath, false, -1, true);
            } else {
                $this->baseUrl = Yii::app()->assetManager->publish($assetsPath);
            }
        }
        return $this;
    }


    /**
     *
     */
    public function init()
    {

        parent::init();
        $this->cs = Yii::app()->getClientScript();
        // publish assets and register css/js files
        $this->publishAssets();
        if ($this->debug) {
            $this->registerScriptFile('kizzy.js', $this->jsFilePos);
        } else {
            // no mini file now !
            $this->registerScriptFile('kizzy.min.js', $this->jsFilePos);
        }

    }

    /**
     * @param $fileName
     * @param $position
     * @return $this
     * @throws InvalidArgumentException
     */
    protected function registerScriptFile($fileName, $position = CClientScript::POS_END)
    {
        if (is_string($fileName)) {
            $jsFiles = explode(',', $fileName);
        } elseif (is_array($fileName)) {
            $jsFiles = $fileName;
        } else {
            throw new InvalidArgumentException('you must give a string or array as first argument , but now you give' . var_export($fileName, true));
        }
        foreach ($jsFiles as $jsFile) {
            $jsFile = trim($jsFile);
            $this->cs->registerScriptFile($this->baseUrl . '/' . ltrim($jsFile, '/'), $position);
        }
        return $this;
    }


    /**
     * @param $fileName
     * @return $this
     * @throws InvalidArgumentException
     */
    protected function registerCssFile($fileName)
    {
        $cssFiles = func_get_args();
        foreach ($cssFiles as $cssFile) {
            if (is_string($cssFile)) {
                $cssFiles2 = explode(',', $cssFile);
            } elseif (is_array($cssFile)) {
                $cssFiles2 = $cssFile;
            } else {
                throw new InvalidArgumentException('you must give a string or array as first argument , but now you give' . var_export($cssFiles, true));
            }
            foreach ($cssFiles2 as $css) {
                $this->cs->registerCssFile($this->baseUrl . '/' . ltrim($css, '/'));
            }
        }
        // $this->cs->registerCssFile($this->assetsUrl . '/vendors/' .$fileName);
        return $this;
    }


    //---------------------------------------------------------

}