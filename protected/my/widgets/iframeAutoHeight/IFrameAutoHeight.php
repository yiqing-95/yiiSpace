<?php
/**
 * Created by JetBrains PhpStorm.
 * User: yiqing
 * Date: 12-1-3
 * Time: 上午11:39
 * To change this template use File | Settings | File Templates.
 * ---------------------------
 * @see https://github.com/house9/jquery-iframe-auto-height
 * ---------------------------
 */
class IFrameAutoHeight extends CWidget
{


    /**
     * @var CClientScript
     */
    protected $cs;


    /**
     * @var bool
     * if you want to manually  setup this plugin .
     * true means this widget only register the needed js or css file ,but leave the setup to yourself !
     */
    public $autoSetup = true;

    /**
     * @var bool
     */
    public $debug;


    /**
     * @var string|array
     * the needed js files for current plugin
     */
    public $scriptFile;

    //=================above is common usage code ================================================================================


    /**
     * @var string
     * -----------------
     * css selector which refer to IFrame[s]
     * .................
     * jQuery('iframe').iframeAutoHeight(); will resize all iframes on the page
     *
     * $('iframe.photo').iframeAutoHeight(); will resize only iframes with the css class photo
     * -----------------
     */
    public $selector = 'iframe';


    /**
     * @var string
     */
    public $callback = 'js: function(callbackObject) {
            var m = "new size is " + callbackObject.newFrameHeight;
            window.console && console.log(m) || alert(m);
        }';


    //--------<commonly setting variables />------------------------------------------


    /**
     * @var array
     * the default options for this plugin
     */
    private $defaultOptions = array(

    );

    /**
     * @var array
     */
    public $options = array();


    /**
     * @var string
     */
    protected $baseUrl = '';

    /**
     * @return void
     * --------------------------
     * best practice for write the init method:
     * init(){
     *    //preConditionCheck();
     *    parent::init();
     *   //defaultSettings();
     * }
     *
     * ---------------------------
     */
    public function init()
    {
        //  most of places use it so make it as instance variable and for intelligence tips from IDE
        $this->cs = Yii::app()->getClientScript();

        $this->publishAssets();

        parent::init();

        if (!isset($this->debug)) {
            $this->debug = defined(YII_DEBUG) ? YII_DEBUG : true;
        }

        if ($this->debug == true) {
            $this->scriptFile = $this->baseUrl . '/jquery.iframe-auto-height.plugin.1.7.0.js';
        } else {
            $this->scriptFile = $this->baseUrl . '/jquery.iframe-auto-height.plugin.1.7.0.min.js';
        }

        $this->registerClientScripts();
    }


    /**
     * @return void
     * this method is usually for you to render html mark up
     */
    public function  run()
    {

    }


    /**
     * @return IFrameAutoHeight
     */
    public function publishAssets()
    {
        $assetsPath = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'assets';

        if ($this->debug) {
            $assetsUrl = Yii::app()->assetManager->publish($assetsPath, false, -1, true);
        } else {
            $assetsUrl = Yii::app()->assetManager->publish($assetsPath);
        }
       $this->baseUrl = $assetsUrl; // $this->assetsUrl = $assetsUrl;
        return $this;
    }

    /**
     * @return IFrameAutoHeight
     */
    public function registerClientScripts()
    {
        //> register the script files for this plugin
        $this->registerScriptFiles();

        //> if you want to manually setup this plugin , i will only register the necessary css and js file.
        if ($this->autoSetup == false) {
            return;
        }
        //> handle the options
        // $this->options['selector'] = $this->selector;
        $this->options['callback'] = $this->callback;
        $this->options['debug'] = $this->debug;

        if(!isset($this->options['animate'])){
            $this->options['animate'] = true;
        }

        //> encode it for initializing the current jquery  plugin
        $options = CJavaScript::encode($this->options);

        $jsCode = '';

        //>  the js code for setup
        $jsCode .= <<<SETUP
        jQuery('{$this->selector}').iframeAutoHeight({$options});
SETUP;

        //> register jsCode
        $this->cs->registerScript(__CLASS__ . '#' . $this->getId(), $jsCode, CClientScript::POS_READY);
        return $this;
    }


    /**
     * @param $name
     * @param $value
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
     * @return IFrameAutoHeight
     * ----------------------------
     * register the necessary javascript files for the plugin
     * ----------------------------
     */
    protected function registerScriptFiles()
    {
        if (is_string($this->scriptFile)) {
            $this->cs->registerScriptFile($this->scriptFile);
        } else if (is_array($this->scriptFile)) {
            foreach ($this->scriptFile as $scriptFile)
                $this->cs->registerScriptFile($scriptFile);
        }
        return $this;
    }

    protected function registerCssFiles()
    {

    }

}

