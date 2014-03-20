<?php
/**
 *  
 * User: yiqing
 * Date: 13-4-5
 * Time: 下午5:13
 * To change this template use File | Settings | File Templates.
 * -------------------------------------------------------
 * -------------------------------------------------------
 */

class JStickyFloat  extends YsAssetsWidget
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
            $this->registerScriptFile("js/stickyfloat.js", CClientScript::POS_HEAD);
        } else {
            // no mini file now !
            $this->registerScriptFile("js/stickyfloat.min.js", CClientScript::POS_HEAD);
        }

        if (empty($this->selector)) return;


        //> encode it for initializing the current jquery  plugin
        $options = empty($this->options) ? '' : CJavaScript::encode($this->options);
        // $options =  CJavaScript::encode(CMap::mergeArray($this->defaultOptions,$this->options));

        $jsCode = '';

        //>  the js code for setup
        $jsCode .= <<<SETUP
       jQuery("{$this->selector}").stickyfloat({$options});
SETUP;
        //> register jsCode
        $cs->registerScript(__CLASS__ . '#' . $this->getId(), $jsCode, CClientScript::POS_READY);


    }

}