<?php

class IasPager extends CLinkPager {

    public $listViewId;
    public $rowSelector = '.row';
    public $itemsSelector = ' > .items';
    public $nextSelector = '.next:not(.disabled):not(.hidden) a';
    public $pagerSelector = '.pager';
    public $options = array();
    public $linkOptions = array();
    public $loaderText = 'Loading...';
    private $baseUrl;

    public function init() {

        parent::init();

        $assets = dirname(__FILE__) . '/assets';
        $this->baseUrl = Yii::app()->assetManager->publish($assets);

        $cs = Yii::app()->getClientScript();
        $cs->registerCoreScript('jquery');
        $cs->registerCSSFile($this->baseUrl . '/css/jquery.ias.css');

        if (YII_DEBUG)
            $cs->registerScriptFile($this->baseUrl . '/js/jquery.ias.js', CClientScript::POS_END);
        else
            $cs->registerScriptFile($this->baseUrl . '/js/jquery-ias.min.js', CClientScript::POS_END);
        return;
    }

    public function run() {

        $js = "jQuery.ias(" .
                CJavaScript::encode(
                        CMap::mergeArray($this->options, array(
                            'container' => '#' . $this->listViewId . '' . $this->itemsSelector,
                            'item' => $this->rowSelector,
                            'pagination' => '#' . $this->listViewId . ' ' . $this->pagerSelector,
                            'next' => '#' . $this->listViewId . ' ' . $this->nextSelector,
                            'loader' => $this->loaderText,
                        ))) . ");";


        $cs = Yii::app()->clientScript;
        $cs->registerScript(__CLASS__ . $this->id, $js, CClientScript::POS_READY);


        $buttons = $this->createPageButtons();

        echo $this->header; // if any
        echo CHtml::tag('ul', $this->htmlOptions, implode("\n", $buttons));
        echo $this->footer;  // if any
    }

    protected function createPageButton($label, $page, $class, $hidden, $selected) {
        if ($hidden || $selected)
            $class .= ' ' . ($hidden ? 'disabled' : 'active');

        return CHtml::tag('li', array('class' => $class), CHtml::link($label, $this->createPageUrl($page), $this->linkOptions));
    }

}