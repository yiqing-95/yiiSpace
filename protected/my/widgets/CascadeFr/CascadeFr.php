<?php
/**
 *
 * User: yiqing
 * Date: 13-3-20
 * Time: 下午5:17
 * To change this template use File | Settings | File Templates.
 * -------------------------------------------------------
 * @see https://github.com/jslegers/cascadeframework.git
 * -------------------------------------------------------
 * note the jquery file of this framework collision with yii's !
 * so we 'd better modify one of them
 *
 * another thing to be noted is js loader , copy the js folder to "assets" folder of yii's
 */
class CascadeFr extends CWidget
{

    public $version = '1.5';
    /**
     * @var string
     */
    protected $baseUrl = '';
    /**
     * @var bool
     */
    public $debug = YII_DEBUG;


    public function init()
    {
        if (empty($this->baseUrl)) {
            if ($this->debug == true) {
                $this->baseUrl = Yii::app()->assetManager->publish(dirname(__FILE__) . '/vendor/assets-'.$this->version, false, -1, true);
            } else {
                $this->baseUrl = Yii::app()->assetManager->publish(dirname(__FILE__) . '/vendor/assets-'.$this->version);
            }
        }

        //  Yii::app()->getClientScript()->registerCoreScript('jquery');
        foreach ($this->getCssFiles($this->debug) as $file) {
            if (isset($file[2]))
                echo '<!--[if lt IE ' . $file[2] . ']>' . "\n";
            echo CHtml::cssFile($file[0], isset($file[1]) ? $file[1] : '') . "\n";
            if (isset($file[2]))
                echo '<![endif]-->' . "\n";
        }
        Yii::app()->clientScript->registerScriptFile($this->baseUrl.'/js/app.js');
        /*
        $jsFiles = <<<JS_SCRIPT
        <!--[if lt IE 9]>
        <script src="{$this->baseUrl}/js/lib/polyfills/iehtmlshiv.js"></script>
        <![endif]-->
JS_SCRIPT;
        echo $jsFiles;
        $siteJsFile = $this->baseUrl . '/js/lib/jquery/jquery.cascade.js';
        Yii::app()->getClientScript()->registerScriptFile($siteJsFile, CClientScript::POS_END)
            ->registerScriptFile($this->baseUrl.'/js/lib/app/loader.js')
            ->registerScriptFile($this->baseUrl.'/js/lib/app/detector.js')
            ->registerScriptFile($this->baseUrl.'/js/lib/jquery/jquery.easing.js')
        ;
        */
    }

    public function getCssFiles($debug = false)
    {

        if ($debug) {
            return array(
                array($this->baseUrl . '/css/cascade/production/build-full.min.css', 'all'),
            //    array($this->baseUrl . '/css/site.css', 'all'),
                array($this->baseUrl . '/css/cascade/production/icons-ie7.min.css', 'all', 8),
            );
        } else {
            return array(
                array($this->baseUrl . '/css/cascade/production/build-full.min.css', 'all'),
             //   array($this->baseUrl . '/css/site.css', 'all'),
                array($this->baseUrl . '/css/cascade/production/icons-ie7.min.css', 'all', 8),
            );
        }
    }
   //*-----------------------------------------------------------*\
    /**
     * this is quick method for creating a collapsible
     * should be used together with the endPanel method !
     * @param array $options
     */
    static  public function beginCollapsible($options=array()){

        Yii::app()->controller->beginWidget('CascadeCollapsible',$options);
    }

    static public function endCollapsible(){
        Yii::app()->controller->endWidget();
    }

    //*----------------------------------------------------------*/

}