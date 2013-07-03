<?php
/**
 * Created by JetBrains PhpStorm.
 * User: yiqing
 * Date: 11-10-13
 * Time: 下午9:05
 * To change this template use File | Settings | File Templates.
 */

class KScrollToWidget extends CWidget
{

    /**
     * @var string
     */
    protected $_id ;
    /**
     * @var string
     */
    public $label = '^top';
    /**
     * @var string
     */
    public $speed = 'slow';
    /**
     * @var int|string
     * the destination scroll to
     *  you can give it an int value or some string
     *  which is some html element 's id;
     */
    public $destination = 0;
    /**
     * @var int
     */
    protected  static $counter = 1;


    /**
     * @var array
     */
    public $linkOptions = array();

    /**
     * @var array
     * this options array will be passed to the plugin as options params  
     */
    protected  $_pluginOptions = array();

    /**
     * @var array
     * this is the default css settings 
     */
    protected $defaultCssSettings = array(
        'display' => ' none',
        'z-index' => ' 999',
        'opacity' => ' .8',
        'position' => ' fixed',
        'top' => ' 100%',
        'margin-top' => ' -80px',
        'right' => ' 0%',
        'margin-left' => ' -160px',
        '-moz-border-radius' => ' 24px',
        '-webkit-border-radius' => ' 24px',
        'width' => ' 300px',
        'line-height' => ' 48px',
        'height' => ' 48px',
        'padding' => ' 10px',
        'background-color' => '  #44ff88',
        'font-size' => ' 24px',
        'text-align' => ' center',
        'color' => ' #fff',
    );

    /**
     * @var array
     * float to the right
     */
    protected $rightFloat = array(
        'right' => '0%',
    );
    /**
     * @var array
     * float to center
     */
    protected $centerFloat = array(
        'left' => '50%',
    );
    /**
     * @var array
     * here are some bug  , i am not good at css , 
     * so  you can fixed it and
     * submit it to me  :)  thanks
     */
    protected $leftFloat = array(
        'left' => '13%',
        'right' => false ,
        'color'=> '#445566',
    );

    /**
     * @var string  right | center | left
     * the position of the element displayed
     */
    public $position = 'right';

    /**
     * @var array|string
     * -------------------------------------------
     * if you give a string settings which will be converted to
     * an array first , so better to give an array;
     * ---------------------------------------------
     * you can add more css settings
     * and remove some default settings by
     * set it to false
     * ---------------------------------------------
     */
    public $cssSettings = array(

    );


    /**
     * @return void
     */
    public function init()
    {
        $this->publishAssets();
        $this->registerClientScripts();
        
        echo CHtml::link($this->label, '#', CMap::mergeArray(array('id' => $this->getId()), $this->linkOptions));
    }


    /**
     * @param bool $autoGenerate
     * @return string
     * override the parent's getId method ,some time the default implements
     * will cause some bug ( id will like yw_1 , yw_2 , this will conflict with some another widget ),
     * you can see the source code of CWidget .
     */
    public function getId($autoGenerate=true){
        //following code make the id will be generated only once in same widget
        if(isset($this->_id)){
            return $this->_id;
        }
       return $this->_id =  __CLASS__.'__'.self::$counter++;
    }

    /**
     * @return KScrollToWidget
     * if  there is  some assets folder to  publish  your can implements this method
     * this is just for future using 
     */
    public function publishAssets()
    {
        return $this;
    }


    public function registerClientScripts()
    {
        // register the css code
        $this->handleCss();
        //handle the js logic  options  pass to the plugin 
         $options = array(
            'speed'=> $this->speed,
             'destination' => $this->destination
        );
        //  
        $options = CMap::mergeArray($this->_pluginOptions , $options);

        $jsonOptions = CJavaScript::encode($options);
        // register the jquery and   plugin code
        Yii::app()->getClientScript()->registerCoreScript('jquery')
        ->registerScript(__CLASS__.'_plugin',$this->jqPluginCode(),CClientScript::POS_END)
             ->registerScript(__CLASS__ . '#' . $this->getId(),
                              " $(\"#{$this->getId()}\").scroll2top({$jsonOptions});",
                             CClientScript::POS_READY
             );

        return $this;
    }

    protected function handleCss(){
        $cssSettings =  $this->cssSettings;
        if(is_string($cssSettings)){
           $cssSettings = $this->getArrayFromCssString($cssSettings);
        }
        if(is_array($cssSettings)){

            
           $this->cssSettings = CMap::mergeArray($this->defaultCssSettings,$cssSettings);


             //position handle
            if( ($position = strtolower($this->position))!== 'right'){
                if(in_array($position,array('right','left','center'))){
                    $positionCssArray = $position.'Float';
                    $this->cssSettings = CMap::mergeArray($this->cssSettings,$this->$positionCssArray);
                }else{
                    throw new CException('position should be right , left or center , you give is '.$this->position);
                }
            }

            //iterate to handle delete css key
            foreach($this->cssSettings as $k=>$v){
                if($v === false){
                    unset($this->cssSettings[$k]);
                }
            }
        }
        $cssCode =  '#'.$this->getId(). $this->genCssFromArray($this->cssSettings);
        Yii::app()->getClientScript()->registerCss(__CLASS__.'#'.$this->getId(),$cssCode);
    }


    /**
     * @return string
     * jquery  scroll2top  plugin code
     * @see http://briancray.com/2009/10/06/scroll-to-top-link-jquery-css/
     * usage :
     *
     *  $(function() {
     *    $("#message a").scroll2top({speed : 800 });
     *    });
     *
     */
    public function jqPluginCode()
    {
        $pluginJs = ' ;(function($) {
                                $.fn.scroll2top = function(options) {
                                       return this.each(function() {
                                           var opts  = $.extend({}, $.fn.scroll2top.defaults, options);
                                           //dest is the  alias  of destination
                                           if(opts.dest){
                                               opts.destination = opts.dest;
                                           }
                                           if (typeof opts.destination =="string" && jQuery("#"+opts.destination).length==1){ //check element set by string exists
                                              opts.destination = jQuery("#"+opts.destination).offset().top - 20 ;
                                           }
                                         /*
                                        if (options) {
                                           var opts  = $.extend({}, $.fn.scroll2top.defaults, options);
                                        }else{
                                           var opts = $.fn.scroll2top.defaults ;
                                        }*/
                                        var scroll_timer;
                                        var displayed = false;
                                        var $message = $(this);
                                        var $window = $(window);
                                        var top = $(document.body).children(0).position().top;
                                        $window.scroll(function () {
                                            window.clearTimeout(scroll_timer);
                                            scroll_timer = window.setTimeout(function () {
                                                if ($window.scrollTop() <= top) {
                                                    displayed = false;
                                                    $message.fadeOut(500);
                                                }
                                                else if (displayed == false) {
                                                    displayed = true;
                                                    $message.stop(true, true).show().click(function (e) {
                                                         $message.fadeOut(500);
                                                         $("html,body").animate({scrollTop:opts.destination }, opts.speed);//800 ms is also ok !
                                                        e.preventDefault();
                                                        return false;
                                                    });
                                                }
                                            }, 100);
                                        });

                                    });
                                };

                                // plugin defaults
                                $.fn.scroll2top.defaults = {
                                   speed : "slow", // or  you can  give it integer value  ,which is milliseconds for scrolling to
                                   destination : 0  // scroll to  where  , integer or string , when if it is string which will be some id of html element
                                };

                            })(jQuery);
                    ';
        return $pluginJs;
    }

    /**
     * use an array to generate  Css  code
     * @param array $cssSettings
     * @param bool $withCurlyBrace   whether close with curlyBrace |是否带上大括号返回
     * @return string
     * 根据数组生成css设置代码
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
     *
     * 从css代码 生成array 需要取掉两边的空格带大括号  如果有的话
     * 需要去除代码中的注释 找个工具方法或者网上搜索
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

    /**
     * @param string $name
     * @param mixed $value
     * @return mixed|void magic method  will treat the undeclared var  as the option which
     */
     public function __set($name, $value)
    {
        try{
            parent::__set($name,$value);
        }catch(CException $e){
             $this->_pluginOptions[$name] = $value;
        }
    }
}
