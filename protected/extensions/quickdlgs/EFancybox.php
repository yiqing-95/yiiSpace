<?php
/**
 * EFancybox.php
 *
 * A widget for the jQuery fancybox version 1.3.4
 *
 * @link http://fancybox.net/
 *
 * Usage:
 *
  $widget=$this->beginWidget('ext.quickdlgs.EFancybox',
                            array(
                                    'easing'=>true,
                                    'imageOptions' => array(
                                                            'overlayColor' => '#000',
                                                            'overlayOpacity' => 0.9,
                                                            'transitionIn' => 'elastic',
                                                            'transitionOut' => 'elastic',
                                                            'speedIn' => 600,
                                                            'speedOut' => 200,
                                                    ),
                            );

            $widget->image($smallImageUrl,$largeImageUrl);
            $widget->content('Some text','Lorem ipsum dolor sit amet, consectetur adipiscing elit',array('title'=>'This is the title'));
            $widget->url('A fancy ajax content',$this->createUrl('fancycontent')); //ajax content
            $widget->url('Yii','http://www.yiiframework.com',array(),true); //iframe content

   $this->endWidget();
 *
 *
 * @author Joe Blocher <yii@myticket.at>
 * @copyright 2012 myticket it-solutions gmbh
 * @license MIT License
 * @version 2.0
 * @package ext.quickdlgs
 * @since 2.0
 */
class EFancybox extends CWidget
{
    /**
     * Set to true if used in a ajax enviroment like CListView with Pager
     *
     * @var bool
     */
    public $directOutput = false;

    /**
     * the fancybox options for the images
     * @var array
     */
    public $imageOptions = array();
    /**
     * the fancybox options for the content links
     * @var array
     */
    public $contentOptions = array();
    /**
     * the fancybox options for the iframe links
     * @var array
     */
    public $urlOptions = array();
    /**
     * Publish the jquery mousewheel script?
     * @var array
     */
    public $mouseWheel = false;
    /**
     * Publish the jquery easing script?
     * Set to true if you use easing features for the dialog
     *
     * @var array
     */
    public $easing = false;

    //internal variables
    protected $_images;
    protected $_hasImage;
    protected $_linkCounter;
    protected $_hasContent;
    protected $_hasUrl;

    //used if directOutput=true
    protected static $_directOutRendered;

    /**
     * @var string the assets url
     */
    private $_assets;

    /**
     * Renders the open tag of the dialog.
     * This method also registers the necessary javascript code.
     */
    public function init()
    {
        $this->_images = array();
        $this->_hasImage = false;
        $this->_hasContent = false;
        $this->_hasUrl = false;
        $this->_linkCounter = 0;
    }

    /**
     * Register the fancybox js-lib
     */
    public function registerClientScript()
    {
        $assets = $this->getAssets();
        $cs = Yii::app()->getClientScript();

        if ($this->directOutput)
        {
           //no multiple directout if more widgets in a view
           if(!self::$_directOutRendered)
           {
               //need to output jquery lib too
               //maybe jquery is included twice from other widgets ... but it works !!??
               //cannot unregister corescripts ...
               $jqueryUrl = $cs->getCoreScriptUrl() . '/jquery.min.js';
               echo CHtml::scriptFile($jqueryUrl);
               echo CHtml::cssFile($assets . '/jquery.fancybox-1.3.4.css');
               echo CHtml::scriptFile($assets . '/jquery.fancybox-1.3.4.pack.js');

               if ($this->easing)
                   echo CHtml::scriptFile($assets . '/jquery.easing-1.3.pack.js');

               if ($this->mouseWheel)
                   echo CHtml::scriptFile($assets . '/jquery.mousewheel-3.0.4.pack.js');

               self::$_directOutRendered = true;
           }
        }
        else
        {
            $cs->registerCoreScript('jquery');
            $cs->registerCssFile($assets . '/jquery.fancybox-1.3.4.css');
            $cs->registerScriptFile($assets . '/jquery.fancybox-1.3.4.pack.js');

            if ($this->easing)
                $cs->registerScriptFile($assets . '/jquery.easing-1.3.pack.js');

            if ($this->mouseWheel)
                $cs->registerScriptFile($assets . '/jquery.mousewheel-3.0.4.pack.js');
        }
    }

    /**
     * Get the id for rel attribute
     *
     * @param $type
     * @return string
     */
    protected function getRelId($type)
    {
        return $this->getId() . '-quickdlgs-fancy'.$type;
    }

    /**
     * The unique id for a content link
     * @return string
     */
    protected function getContentLinkId()
    {
        $this->_linkCounter++;
        return $this->getId() . '-quickdlgs-fancyinline-'.$this->_linkCounter;
    }

    /**
     * Render the fancybox() js-code for an item
     *
     * @param $options
     * @param $rel
     */
    protected function renderScript($options,$rel)
    {
        $jsOptions = CJavaScript::encode($options);
        $selector = "a[rel=$rel]";
        $script = "jQuery('{$selector}').fancybox($jsOptions);";

        if ($this->directOutput)
            echo CHtml::script($script);
        else
            Yii::app()->getClientScript()->registerScript($rel, $script);
    }

    /**
     * Get the assets dir
     * @return string
     */
    public function getAssets()
    {
        if (!isset($this->_assets))
        {
            $fancyDir = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'assets' . DIRECTORY_SEPARATOR . 'jquery.fancybox-1.3.4';
            $this->_assets = Yii::app()->getAssetManager()->publish($fancyDir);
        }

        return $this->_assets;
    }

    /**
     * Render a image
     *
     * @param $smallImgSrc
     * @param $largeImgSrc
     * @param string $smallImgAlt
     * @param array $smallImgHtmlOptions
     * @param array $linkHtmlOptions
     */
    public function image($smallImgSrc, $largeImgSrc, $smallImgAlt = '', $smallImgHtmlOptions = array(), $linkHtmlOptions = array(),$iframe=false)
    {
        $this->_hasImage = true;
        $smallImg = CHtml::image($smallImgSrc, $smallImgAlt, $smallImgHtmlOptions);

        $linkHtmlOptions['rel'] = $this->getRelId('image');
        $linkHtmlOptions['href'] = $largeImgSrc;

        if($iframe)
            $linkHtmlOptions['class'] = isset($linkHtmlOptions['class']) ? $linkHtmlOptions['class'] .' iframe' : 'iframe';

        echo CHtml::tag('a', $linkHtmlOptions, $smallImg);
    }

    /**
     * Render a link that shows a content fancybox
     *
     * @param $linkText
     * @param $content
     * @param array $linkHtmlOptions
     * @param array $contentHtmlOptions
     */
    public function content($linkText,$content,$linkHtmlOptions=array(),$contentHtmlOptions=array())
    {
        $this->_hasContent = true;
        $contentHtmlOptions['id'] = $this->getContentLinkId();

        $linkHtmlOptions['href'] = '#'.$contentHtmlOptions['id'];
        $linkHtmlOptions['rel'] = $this->getRelId('content');

        echo CHtml::link($linkText,'',$linkHtmlOptions);

        echo '<div style="display: none;">';
        echo CHtml::tag('div',$contentHtmlOptions,$content);
        echo '</div>';
    }

    /**
     * Render a link that shows fancybox with content from a url
     *
     * @param $linkText
     * @param $url
     * @param array $linkHtmlOptions
     */
    public function url($linkText,$url,$linkHtmlOptions=array(),$iframe=false)
    {
        $this->_hasUrl = true;
        $linkHtmlOptions['href'] = $url;
        $linkHtmlOptions['rel'] = $this->getRelId('url');
        if($iframe)
            $linkHtmlOptions['class'] = isset($linkHtmlOptions['class']) ? $linkHtmlOptions['class'] .' iframe' : 'iframe';
        echo CHtml::link($linkText,'',$linkHtmlOptions);
    }

    /**
     * Output the js-code
     */
    public function run()
    {
        $this->registerClientScript();

        if ($this->_hasImage)
            $this->renderScript($this->imageOptions,$this->getRelId('image'));

        if ($this->_hasContent)
            $this->renderScript($this->contentOptions,$this->getRelId('content'));

        if ($this->_hasUrl)
            $this->renderScript($this->urlOptions,$this->getRelId('url'));
    }
}