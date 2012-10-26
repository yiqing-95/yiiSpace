<?php
/**
 * Created by JetBrains PhpStorm.
 * User: yiqing
 * Date: 11-12-25
 * Time: 下午6:55
 * To change this template use File | Settings | File Templates.
 * @see http://wwwendt.de/tech/dynatree/doc/samples.html
 * -----------------------------------------
 * how to:
 * dynamic change the skin:
 *<link href="../src/skin/ui.dynatree.css" rel="stylesheet" type="text/css" id="skinSheet">
 * $("#skinCombo")
 *       .val(0) // set state to prevent caching
 *       .change(function(){
 *       var href = "../src/" + $(this).val() + "/ui.dynatree.css" + "?reload=" + new Date().getTime();
 *          $("#skinSheet").attr("href", href);
 *       });
 * -----------------------------------------
 */
class JDynaTree extends CWidget
{

    /**
     * @param bool $autoGenerate
     * @return string
     */
    public function getId($autoGenerate = true)
    {
        $id = parent::getId($autoGenerate);
        if ($this->startsWith($id, 'yw')) {
            return __CLASS__ . substr($id, 2);
        }
        return $id;
    }

    /**
     * @param $h
     * @param $n
     * @return bool
     * if $h  stars with $n
     *
     */
    protected function startsWith($h, $n)
    {
        return (false !== ($i = strrpos($h, $n)) &&
            $i === strlen($h) - strlen($n));
    }

    /**
     * @var CClientScript
     */
    protected $cs;

    /**
     * @var bool
     */
    public $debug;

    /**
     * @var string
     */
    public $container;

    /**
     * @var bool
     */
    protected $needClose = false;
   /**
    * @var array
    */
    public $htmlOptions = array();
    /**
     * @var string
     */
    public $tagName = 'div';

    /**
     * @var string
     * this is your tree structured content string for initialization
     */
    public $content ;

    //...............above code is add in version 2......................................................

    /**
     * @var string
     * currently there are two skin one is basic(default) another is vista
     */
    public $skin = 'vista';

    /**
     * @var array|string
     */
    public $options = array();


    /**
     * @var string
     */
    public $baseUrl ;

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
        if (empty($this->container) || is_string($this->content)) {
            //throw new CException('want to use this widget ? you must give an container id which tree will display in ');
            $this->container = '#'.$this->getId();

            $this->needClose = true;
            echo CHtml::openTag($this->tagName,$this->htmlOptions)."\n";
        }
        //  most of places use it so make it as instance variable and for intelligence tips from IDE
        $this->cs = Yii::app()->getClientScript();

        parent::init();

        if (!isset($this->debug)) {
            $this->debug = defined(YII_DEBUG) ? YII_DEBUG : true;
        }
    }


    /**
     * @return void
     */
    public function  run()
    {
        $this->publishAssets()
            ->registerClientScripts();

        if(is_string($this->content)){
            echo $this->content;
        }
        if($this->needClose == true){
            echo CHtml::closeTag($this->tagName)."\n";
        }
    }


    /**
     * @return JBasicSlider
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
     * @return JDynaTree
     */
    public function registerClientScripts()
    {
        if (isset($this->skin)) {
            if (strtolower($this->skin) == 'vista') {
                $skinDir = 'skin-vista';
            } else {
                //for your customized skin just use the name as skin directory
                $skinDir = $this->skin;
            }
        } else {
            $skinDir = 'skin';
        }

        //> .register js file;
        $this->cs->registerCoreScript('jquery')
            ->registerScriptFile($this->baseUrl .
            (($this->debug == true) ? '/jquery/jquery-ui.custom.js' : '/jquery/jquery-ui.custom.min.js'))
            ->registerScriptFile($this->baseUrl . '/jquery/jquery.cookie.js')

            ->registerCssFile($this->baseUrl . "/src/{$skinDir}/ui.dynatree.css")
            ->registerScriptFile($this->baseUrl . '/src/jquery.dynatree.js');

        $jsCode = '';


        $options = CJavaScript::encode($this->options);
        //>  the js code for setup
        $jsCode .= <<<SETUP
         $("{$this->container}").dynatree({$options});
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

}

