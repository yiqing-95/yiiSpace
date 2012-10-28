<?php
/**
 * Created by JetBrains PhpStorm.
 * User: yiqing
 * Date: 12-10-28
 * Time: 下午8:29
 * To change this template use File | Settings | File Templates.
 */
class JUiLayout   extends CWidget
{

    /**
     * @var bool
     */
    public $debug = YII_DEBUG;
    /**
     * @var string
     */
    public $baseUrl;


    /**
     * @var \CClientScript
     */
    protected $cs;

    /**
     * @var array|string
     * -------------------------
     * the options will be passed to the underlying plugin
     *   eg:  js:{key:val,k2:v2...}
     *   array('key'=>$val,'k'=>v2);
     * -------------------------
     */
    public $options = array();


    /**
     * @var string
     */
    public $selector;


    /**
     * @return JSwitchable
     */
    public function publishAssets()
    {
        if (empty($this->baseUrl)) {
            $assetsPath = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'assets';
            if ($this->debug == true) {
                $this->baseUrl = Yii::app()->assetManager->publish($assetsPath, false, -1, true);
            } else {
                $this->baseUrl = Yii::app()->assetManager->publish($assetsPath);
            }
        }
        return $this;
    }

    /**
     * @return mixed
     */
    public function init()
    {

        parent::init();

        $this->cs = Yii::app()->getClientScript();
        // publish assets and register css/js files
        $this->publishAssets();
        // register necessary js file and css files
        $this->cs->registerCoreScript('jquery');
        $this->cs->registerCoreScript('jquery.ui');

        $this->debug ? $this->registerScriptFile('js/jquery.layout-latest.js') : $this->registerScriptFile('js/jquery.layout-latest.min.js');
        $this->registerCssFile('css/style.css');


        if (empty($this->selector)) {
            //just register the necessary css and js files ; you want use it manually
            return;
        }

        $options = empty($this->options) ? '' : CJavaScript::encode($this->options);

        $jsSetup = <<<JS_INIT
         $("{$this->selector}").colorpicker({$options});
JS_INIT;
        $this->cs->registerScript(__CLASS__ . '#' . $this->getId(), $jsSetup, CClientScript::POS_READY);

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
     * @return \JUiLayout
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
     * @return \JUiLayout
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
        return $this;
    }

    /**
     * @static
     * @param bool $hashByName
     * @return mixed
     */
    public static function getAssetsUrl($hashByName = false)
    {
        return Yii::app()->getAssetManager()->publish(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'assets', $hashByName, -1, YII_DEBUG);
    }

}