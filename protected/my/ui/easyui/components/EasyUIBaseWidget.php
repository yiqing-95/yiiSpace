<?php
/**
 * User: yiqing
 * Date: 13-1-22
 * Time: 下午6:52
 * change template => | Settings | File Templates.
 * ------------------------------------------------
 * ------------------------------------------------
 */
class EasyUIBaseWidget extends CWidget
{

    /**
     * @var string
     */
    public $selector = '';

    /**
     * @var array the HTML attributes that should be rendered in the HTML tag representing the JUI widget.
     */
    public $htmlOptions = array();

    /**
     * @var array the initial JavaScript options that should be passed to the JUI plugin.
     */
    public $options = array();


    /**
     * @var array
     */
    public $defaultOptions = array();

    /**
     * @var string
     */
    public $theme = 'default';

    /**
     * @var string
     */
    public $themeUrl;

    /**
     * @var string URL where to look assets.
     */
    public $assetsUrl;

    /**
     * @var string
     */
    public $assetsPath = '';

    /**
     * @var bool
     */
    public $debug = YII_DEBUG;

    /**
     * @var string
     */
    public $scriptFile = 'jquery.easyui.min.js';

    /**
     * @var string
     */
    public $cssFile = 'easyui.css';

    /**
     * @var string
     */
    public $pluginName = '';

    /**
     *
     */
    public function init(){
        $this->publishAssets();
        $this->registerCoreScripts();
        parent::init();
    }

    /**
     * @return EasyUIBaseWidget
     */
    public function publishAssets()
    {
        if (empty($this->assetsUrl)) {
            $this->assetsPath = $assetsPath = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'assets';
            if ($this->debug == true) {
                $this->assetsUrl = Yii::app()->assetManager->publish($assetsPath, false, -1, true);
            } else {
                $this->assetsUrl = Yii::app()->assetManager->publish($assetsPath);
            }
            $this->themeUrl = $this->assetsUrl.'/themes';
        }
        return $this;
    }

    /**
     * Registers the core script files.
     * This method registers jquery and easyUi JavaScript files and the theme CSS file.
     */
    protected function registerCoreScripts()
    {
        $cs = Yii::app()->getClientScript();
        $cs->registerCssFile($this->themeUrl . '/' . $this->theme . '/' . $this->cssFile);
        $cs->registerCssFile($this->themeUrl . '/icon.css');

        $cs->registerCoreScript('jquery');

        $cs->registerScriptFile($this->assetsUrl . '/' . $this->scriptFile, CClientScript::POS_END);
    }

}
