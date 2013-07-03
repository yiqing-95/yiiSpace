<?php
/**
 *  
 * User: yiqing
 * Date: 13-4-17
 * Time: 下午8:24
 * To change this template use File | Settings | File Templates.
 * -------------------------------------------------------
 * -------------------------------------------------------
 */

class JRdMenu   extends CWidget
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
     * @var array
     */
    public $defaultOptions = array(
        // 'triggerType' => 'click',
        // 'effect' => 'default',
        //  'circular' => true,
    );


    /**
     * @var string
     * css selector
     */
    public $selector;


    /**
     * @var string
     */
    public $jsVersion = '1.1';

    /**
     * @var string
     * available skins :black | blue | green | grey | light_blue | orange |red | white
     */
    public $skin = 'black';

    /**
     * @return JRdMenu
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
     *
     */
    public function init()
    {
        parent::init();
        $this->cs = Yii::app()->getClientScript();

        $this->cs->registerCoreScript('jquery');
        // publish assets and register css/js files
        $this->publishAssets();

        $this->registerCssFile('style.css');

        if ($this->debug) {
            $this->registerScriptFile('script.js', CClientScript::POS_HEAD);
        } else {
            // no the min file now !
            $this->registerScriptFile('script.js', CClientScript::POS_HEAD);
        }


        if (empty($this->selector)) return;

        //> encode it for initializing the current jquery  plugin
        $options = empty($this->options) ? '' : CJavaScript::encode($this->options);
        // $options = CJavaScript::encode(CMap::mergeArray($this->defaultOptions, $this->options));

        $jsCode = '';

        //>  the js code for setup
        $jsCode .= <<<SETUP
        jQuery('{$this->selector}').dcVerticalMegaMenu({$options});
SETUP;

        //> register jsCode
        $this->cs->registerScript(__CLASS__ . '#' . $this->getId(), $jsCode, CClientScript::POS_READY);

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
     * @return JRdMenu
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
     * @return JRdMenu
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

    /**
     * @static
     * @param bool $hashByName
     * @return string
     * return this widget assetsUrl
     */
    public static function getAssetsUrl($hashByName = false)
    {
        // return CHtml::asset(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'assets', $hashByName);
        return Yii::app()->getAssetManager()->publish(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'assets', $hashByName, -1, YII_DEBUG);
    }
}