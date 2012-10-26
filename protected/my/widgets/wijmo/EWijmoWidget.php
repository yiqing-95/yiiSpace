<?php
/**
 * Created by JetBrains PhpStorm.
 * User: yiqing
 * Date: 11-12-30
 * Time: 下午6:55
 * To change this template use File | Settings | File Templates.
 * ----------------------------------------------------
 * @see http://wijmo.com/wiki/index.php/Getting_Started_with_Wijmo
 * ----------------------------------------------------
 * > ??: 是否需要用tag 包围    <tagName htmlOptions > $content  </tagName>
 *   推迟到最后统一添加
 * > ??: 自定义路径 包括theme路径 更换还是添加 +themeUrl 是用url呢还是path
 *   如果用path 需要CClientScript publish  如过用url证明已经publish了或者就在webRoot可访问的路径下
 *
 * ----------------------------------------------------
 * note:   区分 path 和 url 的语义
 *         （path是相对文件系统而言的路径；url则是可从浏览器访问的资源的地址）
 * ----------------------------------------------------
 * beforeWijInit/afterWijInit(position) will be added in future version
 * ----------------------------------------------------
 * every path of the css , javascript files should be based on the assets/vendors dir
 * thus you can directly copy the scriptUrl from the demo header
 *
 * ---------------------------------------------------------------
 * 参考CJuiXXX 系列组件 子类只复写 init 和run方法即可
 *
 * 通用流程：
 * 1.注册 theme；
 * 2.注册核心js（jquery-1.7.1.min.js，jquery-ui-1.8.18.custom.min.js）
 * 3.注册本widget要用到的css （推迟到子类的 init方法中实现），
 * 4.注册本类要用到的js （推迟到子类的init或者run方法中去实现）
 * ......................................................
 * note  5,6 is not implemented now ! leave it to mannually init .
 * 5 本组件的js实例化 jQuery('{$selector}').{$name}({$options});(推迟到子类的run方法中实现)
 * 6.本组件相关的html元素结构的构造 （在子类init和run方法中配合构造 init用来打开tag run用来闭合tag）
 *........................................................
 * 2012-4-10 已经实现5了
 * .......................................................
 *
 *
 */
abstract class EWijmoWidget extends CWidget
{
    const THEME_ARISTO = 'aristo';
    const THEME_MIDNIGHT = 'midnight';
    const THEME_ROCKET = 'rocket';
    const THEME_COBALT = 'cobalt';
    const THEME_STERLING = 'sterling';

    /**
     * @var string
     */
    public $selector ;

    /**
     * @var array
     * defaultOptions for this widget
     */
    protected $defaultConfig = array();

    /**
     * @var array the initial JavaScript options that should be passed to the JUI plugin.
     */
    public $options = array();

    //.............<support theme>.............................................

    /**
     * @var string
     * if you don't give we will use the default
     */
    public $themeUrl;

    /**
     * @var string
     * the theme this widget will use
     */
    public $theme = 'rocket';

    //.............<support theme/>.............................................


    /**
     * @var string
     * if you don't give we will use the default
     * :  assets/vendors/development-bundle/wijmo
     */
    public $scriptUrl;


    /**
     * @var string|array
     * relative to vendors dir
     */
    public $scriptFile = 'js/jquery.wijmo-open.all.2.0.5.min.js';

    /**
     * @var string|array
     * relative to vendors dir
     */
    public $cssFile = 'css/jquery.wijmo-open.2.0.5.css';

    /***
     * @var string
     * assets/vendors
     */
    public $baseUrl;

    /**
     * @var array the HTML attributes that should be rendered in the HTML tag representing the EWijmoWidget widget.
     * such as this content to generate .
     * <div id="calendar1" style="position:relative;left:40px;top:40px;"></div>
     */
    public $htmlOptions = array();

    /**
     * @var CClientScript
     * for common usage
     */
    protected $cs;

    /**
     * @var string
     * url for assets dir
     */
    protected $assetsUrl;

    /**
     * @var bool
     * set the debug mode ,default is same to YII_DEBUG Config
     */
    public $debug = YII_DEBUG;

    /**
     * Initializes the widget.
     * This method will publish JUI assets if necessary.
     * It will also register jquery and JUI JavaScript files and the theme CSS file.
     * If you override this method, make sure you call the parent implementation first.
     */
    public function init()
    {
        //先关掉调试 不然不停publish assets 影响不好
        $this->debug = false;

        $this->cs = Yii::app()->getClientScript();
        $this->publishAssets();

        $this->resolvePackagePath();

        parent::init();

        //register the  global theme
        $this->registerTheme();

        $this->registerCoreScripts();


    }

    public function run(){
        parent::run();

        if($this->wijInit == true && ! empty($this->selector)){
            $this->wijInit();
        }
    }
//--------------------------------------------------------------------------------
    /**
     * @var bool
     * 是否需要初始化插件
     * false 表示只导入相关的css js而已
     */
    public $wijInit = true;

    /**
     * @var string
     * 当前插件的名称 如果不给出那么会用
     * 当前类名作为插件名的
     */
    public $widgetName ;

    /**
     * wijmo widget initialization。
     */
   public function  wijInit(){
       if(empty($this->widgetName)){
           $widgetName = strtolower(get_class($this));
       }else{
           $widgetName = $this->widgetName;
       }

       $options=empty($this->options) ? '' : CJavaScript::encode($this->options);
       $jsCode = <<<SET_UP
             $("{$this->selector}").{$widgetName}({$options});
SET_UP;

       $this->cs->registerScript(__CLASS__.'#'.$this->getId(),$jsCode,CClientScript::POS_READY);
   }


//--------------------------------------------------------------------------------
    /**
     * @return EWijmoWidget
     */
    public function publishAssets()
    {
        $assetsPath = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'assets';

        if ($this->debug) {
            $assetsUrl = Yii::app()->assetManager->publish($assetsPath, false, -1, true);
        } else {
            $assetsUrl = Yii::app()->assetManager->publish($assetsPath);
        }
        $this->assetsUrl = $assetsUrl; // $this->baseUrl = $assetsUrl;
        return $this;
    }

    /**
     * Determine the wijmo package installation path.
     * This method will identify the JavaScript root URL and theme root URL.
     * If they are not explicitly specified, it will publish the included wijmo package
     * and use that to resolve the needed paths.
     * @return \EWijmoWidget
     */
    protected function resolvePackagePath()
    {
        //> you can give your custom themeUrl
        if (!isset($this->themeUrl)) {
            $this->themeUrl = $this->assetsUrl . '/vendors/themes';
        }
        if ($this->scriptUrl === null) {
            // be careful to the minified dir  , it's for production environment .
            $this->scriptUrl = $this->assetsUrl . '/vendors/wijmo';
        }

        if ($this->baseUrl === null) {
            $this->baseUrl = $this->assetsUrl . '/vendors/';
        }
        return $this;
    }


    /**
     * @return EWijmoWidget
     * register the theme css files
     * ----------------------------
     * wijmo widget ofter need to register two theme css files
     * one is primary  ,the second for specified widget/plugin .
     * ----------------------------
     */
    protected function registerTheme()
    {
        $this->cs->registerCssFile($this->themeUrl . "/{$this->theme}/jquery-wijmo.css");
        return $this;
    }

    /**
     * Registers the core script files.
     * This method registers jquery and wijmo JavaScript files and the theme CSS file.
     */
    protected function registerCoreScripts()
    {
        //> for specified widget theme  (the primary theme in registerTheme method )
        if (is_string($this->cssFile)) {
            $this->registerCssFile($this->cssFile);
        } else if (is_array($this->cssFile)) {
            foreach ($this->cssFile as $cssFile) {
                // $cs->registerCssFile($this->themeUrl.'/'.$this->theme.'/'.$cssFile);
                $this->registerCssFile($cssFile);
            }
        }

        //> register jquery/jqueryUI   core
        $this->cs->registerCoreScript('jquery')
            ->registerScriptFile($this->assetsUrl . '/vendors/external/jquery-ui-1.8.18.custom.min.js');


        if (is_string($this->scriptFile)) {
            $this->registerScriptFile($this->scriptFile);
        } else if (is_array($this->scriptFile)) {
            foreach ($this->scriptFile as $scriptFile) {
                $this->registerScriptFile($scriptFile);
            }
        }
    }

    /**
     * Registers a JavaScript file under {@link scriptUrl}.
     * Note that by default, the script file will be rendered at the end of a page to improve page loading speed.
     * @param string $fileName JavaScript file name
     * @param int $position the position of the JavaScript file. Valid values include the following:
     * <ul>
     * <li>CClientScript::POS_HEAD : the script is inserted in the head section right before the title element.</li>
     * <li>CClientScript::POS_BEGIN : the script is inserted at the beginning of the body section.</li>
     * <li>CClientScript::POS_END : the script is inserted at the end of the body section.</li>
     * </ul>
     * @throws InvalidArgumentException
     * @return \EWijmoWidget
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
            $this->cs->registerScriptFile($this->assetsUrl . '/vendors/' . $jsFile, $position);
        }
        return $this;
    }


    /**
     * @param string $fileName you can give more then one css file
     * @throws InvalidArgumentException
     * @return EWijmoWidget
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
                $this->cs->registerCssFile($this->assetsUrl . '/vendors/' . $css);
            }
        }
        // $this->cs->registerCssFile($this->assetsUrl . '/vendors/' .$fileName);
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

    //..........<for inline css block handling >......................................................
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
    //..........<for inline css block handling >......................................................
}
