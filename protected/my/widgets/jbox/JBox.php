<?php
/**
 * Created by JetBrains PhpStorm.
 * User: yiqing
 * Date: 12-3-16
 * Time: 上午9:38
 *------------------------------------------------------------
 *                 _            _
 *                (_)          (_)
 *        _   __  __   .--. _  __   _ .--.   .--./)
 *       [ \ [  ][  |/ /'`\' ][  | [ `.-. | / /'`\;
 *        \ '/ /  | || \__/ |  | |  | | | | \ \._//
 *      [\_:  /  [___]\__.; | [___][___||__].',__`
 *       \__.'            |__]             ( ( __))
 *
 *--------------------------------------------------------------
 * To change this template use File | Settings | File Templates.
 */
class JBox extends CWidget
{

    /**
     * @static
     * @param bool $hashByName
     * @return string
     * return the widget assetsUrl
     */
    public static function getAssetsUrl($hashByName = false)
    {
        // return CHtml::asset(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'assets', $hashByName);
        return Yii::app()->getAssetManager()->publish(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'assets', $hashByName, -1, YII_DEBUG);
    }

    /**
     * @var CClientScript
     */
    protected $cs;


    /**
     * @var bool
     */
    public $manualInit = true;


    /**
     * @var bool
     */
    public $debug;

    /**
     * @var array
     */
    public $options = array();

    /**
     * @var string
     * 取值 Skins1|Skin
     */
    public $skinBase = 'Skins';
    /**
     * @var string
     * 取值参考皮肤文件夹名称
     */
    public $skin = 'Blue';
    /**
     * @var array
     * language code and the corresponding js name ,the js file is under i18n dir.
     *  eg:   array('zh_cn'=>jquery.jBox-zh-CN.js)
     * 如果有其他语言添加映射 并将翻译后的文件夹放至/jBox/i18n目录下 并在这里添加映射
     */
    public $langMap = array(
        'zh_cn' => 'jquery.jBox-zh-CN.js',
    );
    /**
     * @var string
     * 默认语言文件名
     */
    protected $defaultLangFile = 'jquery.jBox-zh-CN.js';

    /**
     * @var string
     */
    protected $baseUrl = '';

    /**
     * @var string
     */
    protected $assetsPath = '';

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
        parent::init();
        if (!isset($this->debug)) {
            $this->debug = defined(YII_DEBUG) ? YII_DEBUG : true;
        }
        $this->assetsPath = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'assets';
        $this->cs = Yii::app()->getClientScript();
    }


    /**
     * @return void
     */
    public function  run()
    {
        $this->publishAssets()
            ->registerClientScripts();

    }


    /**
     * @return JBox
     */
    public function publishAssets()
    {
        $assetsDir = $this->assetsPath; //dirname(__FILE__) . DIRECTORY_SEPARATOR . 'assets';
        $this->baseUrl = Yii::app()->getAssetManager()->publish($assetsDir, false, -1, $this->debug);
        return $this;
    }

    /**
     * @return JBox
     */
    public function registerClientScripts()
    {
        //> .register js file;
        $cs = $this->cs;
        $cs->registerCoreScript('jquery')
            ->registerScriptFile($this->baseUrl . '/jBox/jquery.jBox-2.3.min.js');
        $this->handleI18n();

        //> register css file
        $skinPath = "/jBox/{$this->skinBase}/{$this->skin}/jbox.css";
        $cs->registerCssFile($this->baseUrl . $skinPath);

        //> if you want to manually setup this plugin , i will only register the necessary css and js file.
        if ($this->manualInit == true) {
            return $this;
        }
        //不准备过多封装 这里就截止 下面应该是插件的初始化js代码注册
        return $this;
    }

    /**
     * @return JBox
     */
    public function handleI18n()
    {
        $language = Yii::app()->language;
        if (isset($this->langMap[$language])) {
            $jsFileName = $this->langMap[$language];
        } else {
            $jsFileName = $this->defaultLangFile;
        }

        $jsFilePath = $this->assetsPath . DIRECTORY_SEPARATOR . 'jBox' . DIRECTORY_SEPARATOR . 'i18n' . DIRECTORY_SEPARATOR . $jsFileName;

        if (is_file($jsFilePath)) {
            $jsFileUrl = $this->baseUrl . "/jBox/i18n/{$jsFileName}";
            $this->cs->registerScriptFile($jsFileUrl);
        } else {
            //注册默认的
            $jsFileUrl = $this->baseUrl . "/jBox/i18n/{$this->defaultLangFile}";
            $this->cs->registerScriptFile($jsFileUrl);
        }
        return $this;
    }


    /**
     * @param $name
     * @param $value
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

}

