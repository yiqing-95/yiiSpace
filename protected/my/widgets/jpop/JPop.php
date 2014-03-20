<?php
/**
 *  
 * User: yiqing
 * Date: 13-5-11
 * Time: 下午3:36
 * To change this template use File | Settings | File Templates.
 * -------------------------------------------------------
 * -------------------------------------------------------
 */

class JPop extends CWidget
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
     * @var string
     */
    public $selector ;
    /**
     * @return JPop
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
    $this->cs->registerCoreScript('jquery');

    // publish assets and register css/js files
    $this->publishAssets();
    /*
    if ($this->debug) {
        $this->registerScriptFile('javascrpts/jquery.pop.js', CClientScript::POS_HEAD);
    } else {
        $this->registerScriptFile('javascripts/jquery.pop.js', CClientScript::POS_HEAD);
    }
    */
    $this->registerPlugin();

    $this->registerCssFile('stylesheets/pop.css');


    if(empty($this->selector)) return ;


    //> encode it for initializing the current jquery  plugin
     $options = empty($this->options) ? '' : CJavaScript::encode($this->options);

    $jsCode = '';

    //>  the js code for setup
    $jsCode .= <<<SETUP
       //jQuery('{$this->selector}').pop({$options});
        jQuery('{$this->selector}').addClass('pop');
        $.pop();
SETUP;
    //> register jsCode
    $this->cs->registerScript(__CLASS__ . '#' . $this->getId(), $jsCode, CClientScript::POS_READY);

}


    protected function registerPlugin(){
        $js = <<<PLUGIN
//
//  pop! for jQuery
//  v0.2 requires jQuery v1.2 or later
//
//  Licensed under the MIT:
//  http://www.opensource.org/licenses/mit-license.php
//
//  Copyright 2007,2008 SEAOFCLOUDS [http://seaofclouds.com]
//

(function($) {

  $.pop = function(options){

    // settings
    var settings = {
     pop_class : '.pop',
     pop_toggle_text : ''
    }

    // inject html wrapper
    function initpops (){
      $(settings.pop_class).each(function() {
        var pop_classes = $(this).attr("class");
        $(this).addClass("pop_menu");
        $(this).wrap("<div class='"+pop_classes+"'></div>");
        $(".pop_menu").attr("class", "pop_menu");
        $(this).before(" \
          <div class='pop_toggle'>"+settings.pop_toggle_text+"</div> \
          ");
      });
    }
    initpops();

    // assign reverse z-indexes to each pop
    var totalpops = $(settings.pop_class).size() + 1000;
    $(settings.pop_class).each(function(i) {
     var popzindex = totalpops - i;
     $(this).css({ zIndex: popzindex });
    });
    // close pops if user clicks outside of pop
    activePop = null;
    function closeInactivePop() {
      $(settings.pop_class).each(function (i) {
        if ($(this).hasClass('active') && i!=activePop) {
          $(this).removeClass('active');
          }
      });
      return false;
    }
    $(settings.pop_class).mouseover(function() { activePop = $(settings.pop_class).index(this); });
    $(settings.pop_class).mouseout(function() { activePop = null; });

    $(document.body).click(function(){
     closeInactivePop();
    });
    // toggle that pop
    $(".pop_toggle").click(function(){
      $(this).parent(settings.pop_class).toggleClass("active");
    });
  }

})(jQuery);
PLUGIN;

        $this->cs->registerScript(__CLASS__,$js,CClientScript::POS_END);
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
     * @return JPop
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
     * @return JPop
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