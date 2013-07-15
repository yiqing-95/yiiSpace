<?php
/**
 * Created by JetBrains PhpStorm.
 * User: yiqing
 * Date: 11-12-25
 * Time: 下午6:55
 * To change this template use File | Settings | File Templates.
 * @see http://wwwendt.de/tech/dynatree/doc/samples.html
 */
class JFancyTree extends CWidget
{

    /**
     * 支持的皮肤
     */
    const SKIN_WIN7 = 'win7';
    const SKIN_WIN8 = 'win8';
    const SKIN_XP = 'xp';
    const SKIN_LION = 'lion';
    const SKIN_VISTA = 'vista';

    /**
     * @var CClientScript
     */
    protected $cs;

    /**
     * @var bool
     */
    public $debug = YII_DEBUG;

    /**
     * @var string
     */
    public $container;


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
    public $skin = self::SKIN_VISTA;

    /**
     * @var array|string
     * array('dnd','persistent'),
     * 'dnd'
     * 'table,dnd'
     */
    public $extensions = array();

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
        //  most of places use it so make it as instance variable and for intelligence tips from IDE
        $this->cs = Yii::app()->getClientScript();
        parent::init();

    }


    /**
     * @return void
     */
    public function  run()
    {
        $this->publishAssets()
            ->registerClientScripts();

        /*
        if(is_string($this->content)){
            echo $this->content;
        }
        if($this->needClose == true){
            echo CHtml::closeTag($this->tagName)."\n";
        }
        */
    }


    /**
     * @return $this
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
     * @return JFancyTree
     */
    public function registerClientScripts()
    {
        //> .register js file;
        $this->cs->registerCoreScript('jquery')
        ->registerCoreScript('jquery.ui');

        $this->registerSkinCssFiles();

        $this->cs->registerScriptFile($this->baseUrl.'/src/jquery.fancytree.js');

        $this->registerExtensionScriptFiles();


        if(empty($this->container)){
            // 想手动用js来实例化插件
            return ;
        }

        $jsCode = '';
        $options =  CJavaScript::encode($this->options);
        //>  the js code for setup
        $jsCode .= <<<SETUP
         $("{$this->container}").fancytree({$options});
SETUP;
        //> register jsCode
        $this->cs->registerScript(__CLASS__ . '#' . $this->getId(), $jsCode, CClientScript::POS_READY);

        return $this;
    }

    /**
     * @return $this
     */
    protected function registerSkinCssFiles(){
       $skinJsUrl = $this->baseUrl.'/src/skin-'.$this->skin.'/ui.fancytree.css';
        $this->cs->registerCssFile($skinJsUrl);
        return $this ;
    }

    /**
     * @return $this
     * @throws InvalidArgumentException
     */
    protected function registerExtensionScriptFiles(){
        if(!empty($this->extensions)){
            $extensionNames = array();
            if(is_string($this->extensions)){
                $extensionNames = explode(',',$this->extensions);
                $extensionNames = array_map('trim',$extensionNames);
            }elseif(is_array($this->extensions)){
                $extensionNames = $this->extensions;
            }else{
                throw new InvalidArgumentException('extensions should be a array or string type ');
            }
            // we suppose the $extensionNames is a array now:
            foreach($extensionNames as $extensionName){
                $this->cs->registerScriptFile($this->baseUrl."/src/jquery.fancytree.{$extensionName}.js");
            }
        }
        return $this;
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

}