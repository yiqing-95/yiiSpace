<?php
/**
 *  
 * User: yiqing
 * Date: 13-5-10
 * Time: 下午12:51
 * To change this template use File | Settings | File Templates.
 * -------------------------------------------------------
 * -------------------------------------------------------
 */

class ArtDialog2  extends CWidget
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
     * 模块名称
     *
     * @var string
     */
    public $apiVar = 'dialog';

    /**
     * @var string
     */
    public $jsVersion = 'v6';


    /**
     * 是否使用绝对url定位js
     * 位置容易出错！
     * @var bool
     */
    public $absoluteUrl = true ;

    /**
     * @return ArtDialog
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
         $baseUrl = $this->baseUrl ;
        //$sysAssetsUrl = Yii::app()->assetManager->getBaseUrl() ;
        $artDialogUrl =  $baseUrl ."/{$this->jsVersion}/src/dialog";
        if($this->absoluteUrl ==true){

            $artDialogUrl = Yii::app()->request->getHostInfo().$artDialogUrl ;
        }else{
            throw new CException('not implemented yet! ') ;
        }
        // $artDialogUrl =  '../'. substr($artDialogUrl,strlen($sysAssetsUrl));

        //
        //  var {$this->apiVar} ; // 暴露到全局作用域
        /*
        seajs.use([ '../..{$artDialogUrl}'], function ( dialog) {
              console.log(dialog);
              window.{$this->apiVar} = dialog ;
        });
        */

        $this->registerCssFile($this->jsVersion.'/css/ui-dialog.css');


        $require = <<<EOD

        seajs.config({
          alias: {
               "artDialog": "{$artDialogUrl}"
          }
        });
EOD;
        Yii::app()->clientScript->registerScript(__CLASS__,$require,CClientScript::POS_HEAD);
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
     * @return ArtDialog
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
     * @return ArtDialog
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