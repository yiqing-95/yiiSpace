<?php
/**
 * Created by JetBrains PhpStorm.
 * User: yiqing
 * Date: 12-12-2
 * Time: 下午11:49
 * To change this template use File | Settings | File Templates.
 * ------------------------------------------------------------------
 *@see http://www.myjqueryplugins.com/jquery-plugin/jrating
 * ------------------------------------------------------------------
 */
class JRating extends CWidget
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
     * @var string
     */
    public $selector ;


    /**
     * @return JRating
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
        // publish assets and register css/js files
        $this->publishAssets();
        if ($this->debug) {
            $this->registerScriptFile('jquery/jRating.jquery.js', CClientScript::POS_HEAD);
        } else {
            $this->registerScriptFile('jquery/jRating.jquery.min.js', CClientScript::POS_HEAD);
        }
        $this->registerCssFile('jquery/jRating.jquery.css');


        if(empty($this->selector)) return ;

        $defaultOptions = array(
            'bigStarsPath' => $this->baseUrl.'/jquery/icons/stars.png', // path of the icon stars.png
			'smallStarsPath' => $this->baseUrl. '/jquery/icons/small.png', // path of the icon small.png
        );
        //> encode it for initializing the current jquery  plugin
        //$options = empty($this->options) ? '' : CJavaScript::encode($this->options);
        /**
         * now we do not support the string as options.
         * if user give a string as options : js:{"key1"=>"someVal"}
         * we can merge it  with defaultOptions  in jsCode using
         * var defaultOptions = { validate: false, limit: 5, name: "foo" };
         * var options = { validate: true, name: "bar" };
         * options = jQuery.extend(defaultOptions, options);
         * jQuery('{$this->selector}').jRating({$options});
         */
        $options =  CJavaScript::encode(CMap::mergeArray($defaultOptions,$this->options));

        $jsCode = '';

        //>  the js code for setup
        $jsCode .= <<<SETUP
        jQuery('{$this->selector}').jRating({$options});
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
     * @return JRating
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
     * @return JRating
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