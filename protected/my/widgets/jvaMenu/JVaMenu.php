<?php
/**
 * Created by JetBrains PhpStorm.
 * User: yiqing
 * Date: 12-12-24
 * Time: 下午4:59
 * To change this template use File | Settings | File Templates.
 * -----------------------------------------------------------------
 * this is a wrapper for the great  wordpress-plugin-jquery-vertical-accordion-menu-widget
 * @link http://www.designchemical.com/blog/index.php/wordpress-plugins/wordpress-plugin-jquery-vertical-accordion-menu-widget/
 * ------------------------------------------------------------------
 * demo see here
 * @see http://www.designchemical.com/lab/demo-wordpress-vertical-accordion-menu-plugin/
 * ------------------------------------------------------------------
 */
class JVaMenu extends CWidget
{

    /**
     * @var string
     */
    public $assetsPath ;

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

    public $defaultOptions =  array(
        'title' => '',
        'event' => 'click',
        'hoverDelay' => '300',
        'menuClose' => 'on',
        'autoClose' => 'on',
        'saveState' => 'on',
        'autoExpand' => 'off',
        'showCount' => 'off',
        'speed' => 'slow',
        'disableLink' => 'on',
        'classDisable' => 'on',
        'classMenu' => '',
        'skin' => 'demo.css'
    );

    /**
     * @var string
     */
    public $selector ;

    /**
     * @var string
     * the js version no of this jquery plugin
     */
    public $jsVersion = '2.9';

    /**
     * @var string available skins :black|blue|clean|demo|graphite|grey
     */
    public $skin = 'blue';

    /**
     * @return JVaMenu
     */
    public function publishAssets()
    {
        if (empty($this->baseUrl)) {
            $this->assetsPath =  $assetsPath = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'assets';
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
        $this->cs->registerCoreScript('jquery');
        if ($this->debug) {
            $this->registerScriptFile("js/jquery.dcjqaccordion.{$this->jsVersion}.js", CClientScript::POS_HEAD);
        } else {
            // no mini file now !
            $this->registerScriptFile("js/jquery.dcjqaccordion.{$this->jsVersion}.js", CClientScript::POS_HEAD);
        }
        $this->registerScriptFile("js/jquery.hoverIntent.minified.js,js/jquery.cookie.js", CClientScript::POS_HEAD);

        /**
         * skin handle is different from the original plugin!
          */
        // $this->registerCssFile("skins/{$this->skin}.css");
        $this->registerSkinCss($this->skin);


        if(empty($this->selector)) return ;


        //> encode it for initializing the current jquery  plugin
       // $options = empty($this->options) ? '' : CJavaScript::encode($this->options);
        $options =  CJavaScript::encode(CMap::mergeArray($this->defaultOptions,$this->options));

        $jsCode = '';

        //>  the js code for setup
        $jsCode .= <<<SETUP
       jQuery("{$this->selector}").addClass('dcjq-accordion');
       jQuery('{$this->selector}').dcAccordion({$options});
SETUP;
        //> register jsCode
        $this->cs->registerScript(__CLASS__ . '#' . $this->getId(), $jsCode, CClientScript::POS_READY);
    }

    /**
     * @param string $skinName
     * @throws CException
     */
    protected function registerSkinCss($skinName){
         if(empty($skinName)){
             return  ;
         }
         $skinCssFile = $this->assetsPath. DIRECTORY_SEPARATOR .'skins'. DIRECTORY_SEPARATOR . $skinName .'.css';
         if(file_exists($skinCssFile)){
             $cssContent = str_replace(array('#dc_jqaccordion_widget-%ID%-item','skins/'),
             array($this->selector,$this->baseUrl.'/skins/'),file_get_contents($skinCssFile));
             $this->cs->registerCss(__METHOD__.'#'.$this->selector,$cssContent);
         }else{
             if($this->debug){
                 throw new CException("skin not exist ,check the skin name :{$skinName} , skin css file path is {$skinCssFile}");
             }
         }
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
     * @return JVaMenu
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
     * @return JVaMenu
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