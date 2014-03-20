<?php
/**
 * Class MorrisChartWidget
 *
 * Simple wrapper for Morris.js - http://www.oesmith.co.uk/morris.js/
 *
 * Usage:
    $this->widget('application.extensions.morris.MorrisChartWidget', array(
        'id'      => 'myChartElement',
        'options' => array(
        'chartType' => 'Area',
        'data'      => array(
            array('y' => 2006, 'a' => 100, 'b' => 90),
            array('y' => 2007, 'a' => 40, 'b' => 60),
            array('y' => 2008, 'a' => 50, 'b' => 10),
            array('y' => 2009, 'a' => 60, 'b' => 50),
            array('y' => 2010, 'a' => 60, 'b' => 40),
        ),
        'xkey'      => 'y',
        'ykeys'     => array('a', 'b'),
        'labels'    => array('Series A', 'Series B'),
        ),
    ));
 *
 * @license BSD / MIT.
 * @author <eirikhm@warmsys.com> Eirik Hoem
 */
class MorrisChartWidget extends CWidget
{
    public $options = array();
    public $htmlOptions = array();


    public function run()
    {
        $id                      = $this->getId();
        $this->htmlOptions['id'] = $id;

        echo CHtml::openTag('div', $this->htmlOptions);
        echo CHtml::closeTag('div');

        $defaultOptions = array();
        $this->options  = CMap::mergeArray($defaultOptions, $this->options);
        $this->options['element'] = $id;
        $jsOptions      = CJavaScript::encode($this->options);

        $chartType = $this->options['chartType'];

        $this->registerScripts(__CLASS__ . '#' . $id, "Morris.{$chartType}($jsOptions);");
    }

    /**
     * Publishes and registers the necessary script files.
     *
     * @param string the id of the script to be inserted into the page
     * @param string the embedded script to be inserted into the page
     */
    protected function registerScripts($id, $embeddedScript)
    {
        $basePath   = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'assets' . DIRECTORY_SEPARATOR;
        $baseUrl    = Yii::app()->getAssetManager()->publish($basePath, false, 1, YII_DEBUG);
        $scriptFile = YII_DEBUG ? '/morris.js' : '/morris.min.js';

        $cs = Yii::app()->clientScript;
        $cs->registerCoreScript('jquery');
        $cs->registerScriptFile($baseUrl . $scriptFile);

        $scriptFile = 'raphael-min.js';
        $cs->registerScriptFile("$baseUrl/$scriptFile");

        $stylefile = 'morris.css';
        $cs->registerCssFile("$baseUrl/$stylefile");
        $cs->registerScript($id, $embeddedScript, CClientScript::POS_LOAD);
    }
}