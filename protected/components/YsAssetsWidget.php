<?php
/**
 *
 * User: yiqing
 * Date: 13-4-5
 * Time: 下午2:56
 * To change this template use File | Settings | File Templates.
 * -------------------------------------------------------
 * -------------------------------------------------------
 */

class YsAssetsWidget extends CWidget
{


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
    protected $_cs;



    /**
     * @param string $assetsPath
     * @return WdWidget
     */
    public function publishAssets($assetsPath )
    {
        if (empty($this->baseUrl)) {
            if (!empty($assetsPath)) {
                if ($this->debug == true) {
                    $this->baseUrl = Yii::app()->assetManager->publish($assetsPath, false, -1, true);
                } else {
                    $this->baseUrl = Yii::app()->assetManager->publish($assetsPath);
                }
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
    }

    /**
     * @return CClientScript ;
     */
    protected function initCs()
    {
        if (!isset($this->_cs)) {
            $this->_cs = Yii::app()->getClientScript();
        }
        return $this->_cs;
    }

    /**
     * @param string $name
     * @param mixed $value
     * @return mixed|void
     */
    public function __set($name, $value)
    {
        try {
            //shouldn't swallow the parent ' __set operation
            parent::__set($name, $value);
        } catch (Exception $e) {
            $this->options[$name] = $value;
        }
    }

    /**
     * @param $fileName
     * @param int $position
     * @return WdWidget
     * @throws InvalidArgumentException
     */
    protected function registerScriptFile($fileName, $position = CClientScript::POS_END)
    {
        $cs = $this->initCs() ;

        if (is_string($fileName)) {
            $jsFiles = explode(',', $fileName);
        } elseif (is_array($fileName)) {
            $jsFiles = $fileName;
        } else {
            throw new InvalidArgumentException('you must give a string or array as first argument , but now you give' . var_export($fileName, true));
        }
        foreach ($jsFiles as $jsFile) {
            $jsFile = trim($jsFile);
           $cs->registerScriptFile($this->baseUrl . '/' . ltrim($jsFile, '/'), $position);
        }
        return $this;
    }

    /**
     * @param $fileName
     * @return WdWidget
     * @throws InvalidArgumentException
     */
    protected function registerCssFile($fileName)
    {
        $cs = $this->initCs();
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
               $cs->registerCssFile($this->baseUrl . '/' . ltrim($css, '/'));
            }
        }
        //$cs->registerCssFile($this->assetsUrl . '/vendors/' .$fileName);
        return $this;
    }


}