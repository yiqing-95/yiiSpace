<?php

/**
 * 
 */
class EMetabox extends CWidget
{

    /**
     * @var array the javascript options
     */
    public $options = array();

    /**
     * @var array the html options
     */
    public $htmlOptions = array();

    /**
     * @var string ajax loading indicator
     */
    public $loadingIndicator;

    /**
     * @var mixed the refresh url
     */
    public $url;

    /**
     * @var boolean if true the metabox will refresh on window load
     */
    public $refreshOnInit = false;

    /**
     * @var string the initial contents of the div
     */
    public $initHtml = ' ';

    /**
     * @var type 
     */
    static private $_assets;

    /**
     * 
     */
    public function init()
    {
        if ($this->url == null)
            throw new CException('Param `url` is not set.');

        if (self::$_assets == null)
            self::$_assets = Yii::app()->getAssetManager()->publish(Yii::getPathOfAlias('ext.metabox.assets'), false, 1, YII_DEBUG);

        $id = $this->id;
        if (!isset($this->htmlOptions['id']))
            $this->htmlOptions['id'] = $id;

        if (!isset($this->htmlOptions['class']))
            $this->htmlOptions['class'] = 'metabox';

        if ($this->loadingIndicator == null)
            $this->loadingIndicator = CHtml::image(self::$_assets . '/loader.gif', 'loading...');

        $this->url = CHtml::normalizeUrl($this->url);

        $this->options['url'] = $this->url;
        $this->options['loadingText'] = $this->loadingIndicator;
        $this->options['refreshOnInit'] = $this->refreshOnInit;
    }

    /**
     * Register extension assets
     */
    public function run()
    {
        $id = $this->id;
        echo CHtml::tag('div', $this->htmlOptions, $this->initHtml);

        /* @var $cs CClientScript */
        $cs = Yii::app()->getClientScript();

        $min = YII_DEBUG ? '.min' : '';

        // Register jQuery scripts
        $cs->registerCoreScript('jquery');
        $cs->registerCoreScript('bbq');

        $options = CJavaScript::encode($this->options);
        $cs->registerScriptFile(self::$_assets . '/jquery.metabox' . $min . '.js');
        $cs->registerScript('metabox#' . $id, "$('#{$id}').metabox({$options});", CClientScript::POS_READY);
    }

}

?>