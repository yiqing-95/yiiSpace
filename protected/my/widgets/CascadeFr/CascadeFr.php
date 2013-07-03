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
                $this->baseUrl = Yii::app()->assetManager->publish(dirname(__FILE__) . '/vendor', false, -1, true);
            } else {
                $this->baseUrl = Yii::app()->assetManager->publish(dirname(__FILE__) . '/vendor');
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

        $jsFiles = <<<JS_SCRIPT
        <!--[if lt IE 9]>
        <script src="{$this->baseUrl}/assets/js/lib/polyfills/iehtmlshiv.js"></script>
        <![endif]-->
JS_SCRIPT;
        echo $jsFiles;
        $siteJsFile = $this->baseUrl . '/assets/js/app.js';
       // Yii::app()->getClientScript()->registerScriptFile($siteJsFile, CClientScript::POS_END);
    }

    public function getCssFiles($debug = false)
    {

        if ($debug) {
            return array(
                array($this->baseUrl . '/assets/css/cascade/production/build-full.min.css', 'all'),
            //    array($this->baseUrl . '/assets/css/site.css', 'all'),
                array($this->baseUrl . '/assets/css/cascade/production/icons-ie7.min.css', 'all', 8),
            );
        } else {
            return array(
                array($this->baseUrl . '/assets/css/cascade/production/build-full.min.css', 'all'),
             //   array($this->baseUrl . '/assets/css/site.css', 'all'),
                array($this->baseUrl . '/assets/css/cascade/production/icons-ie7.min.css', 'all', 8),
            );
        }
    }


}