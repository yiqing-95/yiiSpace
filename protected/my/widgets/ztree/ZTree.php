<?php
/**
 * User: yiqing
 * Date: 13-1-23
 * Time: 下午3:42
 * change template => | Settings | File Templates.
 * ------------------------------------------------
 * ------------------------------------------------
 */
class ZTree extends CWidget
{

    /**
     * @var string
     */
    public $assetsPath;

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

    );

    /**
     * @var string
     */
    public $selector;

    /**
     * @var string
     * the js version no of this jquery plugin
     */
    public $jsVersion = '3.5';

    /**
     * @var array|string  the extension functionality !
     * excheck|exedit|exhide
     */
    public $ex = array();

    /**
     * @var bool
     * whether or not compact all necessary js in one file
     * true means you want use all functionality of ztree !(core,ex..)
     */
    public $MergeAll = true;


    public function publishAssets()
    {
        if (empty($this->baseUrl)) {
            $this->assetsPath = $assetsPath = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'assets';
            if ($this->debug == true) {
                $this->baseUrl = Yii::app()->assetManager->publish($assetsPath, false, -1, true);
            } else {
                $this->baseUrl = Yii::app()->assetManager->publish($assetsPath);
            }
        }
        return $this;
    }


    /**
     * for merger js  compress js
     * @see ：http://www.yiiframework.com/extension/nlsclientscript
     */
    public function init()
    {

        parent::init();
        $this->cs = Yii::app()->getClientScript();
        // publish assets and register css/js files
        $this->publishAssets();
        $this->cs->registerCoreScript('jquery');

        $exJsArr = array();
        if (!empty($this->ex)) {
            if (is_string($this->ex)) {
                $exJsArr[] = "js/jquery.ztree.{$this->ex}-{$this->jsVersion}";
            } elseif (is_array($this->ex)) {
                foreach ($this->ex as $ex) {
                    $exJsArr[] = "js/jquery.ztree.{$ex}-{$this->jsVersion}";
                }
            }
        }

        if ($this->debug) {
            if ($this->MergeAll) {
                $this->registerScriptFile("js/jquery.ztree.all-{$this->jsVersion}.js", CClientScript::POS_HEAD);
            } else {
                $this->registerScriptFile("js/jquery.ztree.core-{$this->jsVersion}.js", CClientScript::POS_HEAD);
                foreach ($exJsArr as $exJs) {
                    $this->registerScriptFile($exJs . '.js', CClientScript::POS_HEAD);
                }
            }
        } else {
            if ($this->MergeAll) {
                $this->registerScriptFile("js/jquery.ztree.all-{$this->jsVersion}.min.js", CClientScript::POS_HEAD);
            } else {
                $this->registerScriptFile("js/jquery.ztree.core-{$this->jsVersion}.min.js", CClientScript::POS_HEAD);
                foreach ($exJsArr as $exJs) {
                    $this->registerScriptFile($exJs . '.min.js', CClientScript::POS_HEAD);
                }
            }
        }

        $this->registerCssFile("css/zTreeStyle/zTreeStyle.css");


        if (empty($this->selector)) return;


        //> encode it for initializing the current jquery  plugin
        // $options = empty($this->options) ? '' : CJavaScript::encode($this->options);
        $options = CJavaScript::encode(CMap::mergeArray($this->defaultOptions, $this->options));

        $jsCode = '';

        //>  the js code for setup
        $jsCode .= <<<SETUP


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
     * @return ZTree
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
     * @return ZTree
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