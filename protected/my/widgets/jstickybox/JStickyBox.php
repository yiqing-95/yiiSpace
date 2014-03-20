<?php
/**
 *
 * User: yiqing
 * Date: 13-4-5
 * Time: 下午2:58
 * To change this template use File | Settings | File Templates.
 * -------------------------------------------------------
 * @see https://github.com/p-m-p/jQuery-Stickybox
 * -------------------------------------------------------
 * methods :
 * ...................................
 * remove - removes the selected sticky boxes
 * $(selector).stickySidebar("remove")
 *
 * destroy - removes all sticky boxes and event listeners
 * $(selector).stickySidebar("destroy")
 * ...................................
 */

class JStickyBox extends YsAssetsWidget
{

    /**
     * @var string
     * Supports multiple objects - no specific css is required as it will position elements
     * according to original offset and closest positioned ancestor
     */
    public $selector;


    /**
     * @var array
     * options for the underline plugin .
     * ------------------------------------------
     * speed (default = 150) - the duration of the animation
     * easing (default = 'linear') - the easing to use for the animation
     * padding (default = 10) - amount of padding from top of window
     * constrain (default = false) - set true to stop from scrolling out of parent
     */
    public $options = array();


    public function init()
    {
        $this->publishAssets(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'assets');

        $cs = $this->initCs();
        $cs->registerCoreScript('jquery');

        if ($this->debug) {
            $this->registerScriptFile("js/stickysidebar.jquery.js", CClientScript::POS_HEAD);
        } else {
            // no mini file now !
            $this->registerScriptFile("js/stickysidebar.jquery.min.js", CClientScript::POS_HEAD);
        }
        $this->registerScriptFile("js/jquery.easing.1.3.js", CClientScript::POS_HEAD);

        if (empty($this->selector)) return;


        //> encode it for initializing the current jquery  plugin
        $options = empty($this->options) ? '' : CJavaScript::encode($this->options);
        // $options =  CJavaScript::encode(CMap::mergeArray($this->defaultOptions,$this->options));

        $jsCode = '';

        //>  the js code for setup
        $jsCode .= <<<SETUP
       jQuery("{$this->selector}").stickySidebar({$options});
SETUP;
        //> register jsCode
        $cs->registerScript(__CLASS__ . '#' . $this->getId(), $jsCode, CClientScript::POS_READY);


    }

}