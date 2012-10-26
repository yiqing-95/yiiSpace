<?php
/**
 * Created by JetBrains PhpStorm.
 * User: yiqing
 * Date: 11-12-20
 * Time: 下午4:57
 * To change this template use File | Settings | File Templates.-
 * @see http://medialize.github.com/
 * ----------------------------------------------------------------------
 * here are another contextMenu plugin :
 *
 * http://abeautifulsite.net/blog/2008/09/jquery-context-menu-plugin/
 * http://www.trendskitchens.co.nz/jquery/contextmenu/
 * http://www.web-delicious.com/jquery-plugins-demo/wdContextMenu/sample.htm
 *
 * http://www.smartango.com/articles/jquery-context-menu
 * http://www.javascripttoolbox.com/lib/contextmenu/index.php
 * http://www.agi.it/extras/CM/cm.html
 * http://www.planitworks.nl/en/jeegoocontext
 * http://joewalnes.github.com/jquery-simple-context-menu/example.html
 * http://www.trirand.net/aspnetmvc/grid/functionalitycontextmenu
 * http://www.jeasyui.com/tutorial/mb/menu.php
 * great collection: http://www.developersnippets.com/2011/09/02/bunch-of-widely-used-jquery-context-menus/
 *                  http://www.ajaxline.com/node/2126
 * ------------------------------------------------------------------------
 */
class  JContextMenu extends CWidget
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
     * use an array to generate  Css  code
     * @param array $cssSettings
     * @param bool $withCurlyBrace   whether close with curlyBrace
     * @return string
     */
    public function genCssFromArray($cssSettings = array(), $withCurlyBrace = true)
    {
        $cssCodes = '';
        foreach ($cssSettings as $k => $v) {
            $cssCodes .= "{$k}:{$v}; \n";
        }
        if ($withCurlyBrace === true) {
            $cssCodes = '{' . "\n" . $cssCodes . '}';
        }
        return $cssCodes;
    }

    /**
     * parse the css code  to php array
     * @param string $cssString
     * @return array
     */
    public function getArrayFromCssString($cssString = '')
    {
        $rtn = array();
        //remove  {   and  }  if exists
        $cssString = rtrim(trim($cssString), '}');
        $cssString = ltrim($cssString, '{');
        //remove  all comments and space
        $text = preg_replace('!/\*.*?\*/!s', '', $cssString);
        $text = preg_replace('/\n\s*\n/', "", $text);
        // pairs handle
        $pairs = explode(';', $text);
        foreach ($pairs as $pair) {
            $colonPos = strpos($pair, ':');
            if (($k = trim(substr($pair, 0, $colonPos))) !== '') {
                $rtn[$k] = substr($pair, $colonPos + 1);
            }
        }
        return $rtn;
    }

    /**
     * @var bool
     * if you want to manually  setup the context menu .
     * true means this widget only register the needed js or css file ,but leave the setup to yourself !
     */
    public $manualInit = false;

    //=================above is common usage code ================================================================================

    /**
     * @var bool
     */
    public $debugMode;


    //--------<must specified variables>------------------------------------------
    /**
     * @var string
     * -----------------
     * css selector which contextMenu will attache to
     * -----------------
     */
    public $selector = 'document.body';


    /**
     * @var string
     */
    public $callback = 'js: function(key, options) {
            var m = "Clicked on " + key + " on element " + options.$trigger.attr("id");
            window.console && console.log(m) || alert(m);
        }';

    /**
     * @var array|string  if it is the string type means you give a js
     *                     code ,you should prefix it with js:
     * ---------------
     * menu item config
     * you should give your owner config
     * ---------------
     */
    public $items = array(
        'quite' => array(
            'name' => 'this is test menuItem,',
            'icon' => 'quit',
        ),
    );

    //--------<must specified variables/>------------------------------------------


    /**
     * @var array
     */
    public $options = array();


    /**
     * @var string
     */
    protected $baseUrl ;

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
        // if you use the non-manually mode you must give a selector for contextMenu to attach
        if (!$this->manualInit && empty($this->selector)) {
            throw new CException('want to use this widget ? you must give an selector which is css selector string
            ,context menu will attach to it');
        }
        //  most of places use it so make it as instance variable and for intelligence tips from IDE
        $this->cs = Yii::app()->getClientScript();

        parent::init();

        if (!isset($this->debugMode)) {
            $this->debugMode = defined(YII_DEBUG) ? YII_DEBUG : true;
        }
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
     * @return JContextMenu
     */
    public function publishAssets()
    {
        $assetsDir = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'assets';
        $this->baseUrl = Yii::app()->getAssetManager()->publish($assetsDir, false, -1, $this->debugMode);
        return $this;
    }

    /**
     * @return JContextMenu
     */
    public function registerClientScripts()
    {
        //> .register js file;
        $cs = Yii::app()->getClientScript();

        $this->registerJquery();

        $cs->registerScriptFile($this->baseUrl . '/src/jquery.ui.position.js')
            ->registerScriptFile($this->baseUrl . '/src/jquery.contextMenu.js')
            ->registerScriptFile($this->baseUrl . '/prettify/prettify.js')
            ->registerScriptFile($this->baseUrl . '/screen.js');

        //> register css file
        $cs->registerCssFile($this->baseUrl . '/src/jquery.contextMenu.css')
        // ->registerCssFile($this->baseUrl . '/screen.css')
            ->registerCssFile($this->baseUrl . '/prettify/prettify.sunburst.css');

        //> if you want to manually setup this plugin , i will only register the necessary css and js file.
        if($this->manualInit == true){
            return ;
        }

        //>  for customize icon url settings
        $this->handleIconPath();

        //> if html5 enabled
        if ($this->html5 == true) {
            $this->html5Polyfill();
            return; //exit current method
        }

        //> handle the options
        $this->options['selector'] = $this->selector;
        $this->options['callback'] = $this->callback;
        $this->options['items'] = $this->items;

        //> encode it for initializing the jquery contextMenu plugin
        $options = CJavaScript::encode($this->options);
        $jsCode = '';
        //>  for custom-command type
        if (!empty($this->types) && is_array($this->types)) {
            $custom_cmd_type = CJavaScript::encode($this->types);
            $jsCode .= <<<CUSTOM_CMD_TYPE
   $.contextMenu.types = {$custom_cmd_type};
CUSTOM_CMD_TYPE;
            $jsCode .= "\n";
        }

        //>  the js code for setup
        $jsCode .= <<<SETUP
  $.contextMenu({$options});
SETUP;

        //> register jsCode
        $cs->registerScript(__CLASS__ . '#' . $this->getId(), $jsCode, CClientScript::POS_READY);
        return $this;
    }

    /**
     * @return JContextMenu
     * because the current jquery version of yii not compatible with contextMenu plugin
     * so here should do some extra works. at present i just use the internal jquery version 1.7.1.mini.js
     * -----------------------------------------
     * want customize the jquery location , see CClientScript::scriptMap && CClientScript::packages
     * may be you should config clientScript to use your own jquery files .
     * in layout file
     *  $cs->scriptMap = array(
     *  'jquery.js' => '/js/jquery/jquery-1.4.1.min.js',
     * 'jquery.yii.js' => '/js/jquery/jquery-1.4.1.min.js',
     * );
     * ---------------------------------------------
     */
    protected function registerJquery()
    {
        $cs = $this->cs;
        ($this->useYiiJquery == true) ? $cs->registerCoreScript('jquery') : $cs->registerScriptFile($this->baseUrl . '/jquery-1.7.1.min.js');
        //use both version jquery 1.6.1 and 1.7.1 is ok now , but not comfortable
        //$cs->registerCoreScript('jquery');  $cs->registerScriptFile($this->baseUrl . '/jquery-1.7.1.min.js');
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

//----------below is optional or extra functionality support --------------------------------------------------------

    /**
     * @var bool
     * it can't  work with jquery of yii together now . at present temporarily using the internal jquery lib(1.7.1 mini) .
     * if it can work with yiis ,you just set it to true;
     * in yii 1.8 , the jquery version is 1.6.1 .
     * --------------------------------------------
     * want to let yii use you own jquery ?  refer to http://www.yiiframework.com/wiki/259/serve-jquery-and-jquery-ui-from-google-s-cdn
     * --------------------------------------------
     */
    public $useYiiJquery = true;

    /**
     * @var array
     * for customCommandTypes
     * @see http://medialize.github.com/jQuery-contextMenu/demo/custom-command.html
     */
    public $types = array();

    /**
     * @var array
     * ----------------
     * global settings
     * not implemented .....
     * -----------------
     */
    public $defaults = array();

    /**
     * @var bool
     */
    public $html5 = false;

    /**
     * @var bool whether ignoring browser native implementation
     */
    public $ignoreBrowserImpl = false;

    /**
     * @return JContextMenu
     */
    protected function html5PolyFill()
    {
        $ignoreBrowserNativeImpl = CJavaScript::encode($this->ignoreBrowserImpl);
        //>  the js code for setup
        $jsCode = <<<SETUP
  $.contextMenu('html5',{$ignoreBrowserNativeImpl});
SETUP;
        //> register jsCode
        $this->cs->registerScript(__CLASS__ . '#' . $this->getId(), $jsCode, CClientScript::POS_READY);
        return $this;
    }

    //......................<for icon path handling>...........................................................................
    /**
     * @var array
     * css icon rules for customer icon
     * default icons : edit ,cut, copy ,paste , delete , quite
     *
     * please see jquery.contextMenu.css for detail
     */
    protected $iconRules = array();
   /**
    * for customer icon path
    */
    protected function handleIconPath(){

        $menuItems = $this->items;
        $this->visitMenuTree($menuItems);
        if(!empty($this->iconRules)){
            $cssRules = '';
            foreach($this->iconRules as $ruleKey => $iconRule){
                $iconRuleStr = $this->genCssFromArray($iconRule);

                $cssRules .= "{$ruleKey}:{$iconRuleStr} \n";
            }
            $this->cs->registerCss('menu_icon_'.$this->getId(),$cssRules);
        }
    }
   /**
    * @param $menuItems
    * traverse the menuItems as a tree recursively .
    */
    protected function visitMenuTree($menuItems){
          foreach($menuItems as $menuKey=>$menuItemConfig){
              //for separator
              if(!is_array($menuItemConfig)) continue ;

              if(isset($menuItemConfig['iconUrl'])){
                 $this->iconRules['.context-menu-item .icon .icon-'.$menuKey] = array(
                       'background-image'=>"url({$menuItemConfig['iconUrl']})",
                 );
              }
              if(isset($menuItemConfig['icon_url'])){
                  $this->iconRules['.context-menu-item .icon .icon-'.$menuKey] = array(
                      'background-image'=>"url({$menuItemConfig['icon_url']})",
                  );
              }
              if($menuKey == 'items'){
                  $this->visitMenuTree($menuItemConfig);
              }
          }
    }
    //......................<for icon path handling/>...........................................................................
}

