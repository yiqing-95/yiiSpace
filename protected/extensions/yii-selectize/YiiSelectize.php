<?php

/**
 * Class YiiSelectize
 *
 * @author  Giovanni Derks <giovdk21@gmail.com>
 * @link    https://github.com/giovdk21/yii-selectize
 * @version 0.4
 *
 * License: MIT License (http://www.opensource.org/licenses/mit-license.php)
 *
 * Wrapper for the jQuery selectize.js UI control created by Brian Reavis & Contributors
 * (selectize.js is released under the Apache License, Version 2.0)
 * http://brianreavis.github.io/selectize.js/
 *
 * This wrapper is based on the work of Anggiajuang Patria for the ESelect2 extension:
 * http://www.yiiframework.com/extension/select2
 */

class YiiSelectize extends CInputWidget
{

    // Assets paths:
    private $_vendorBaseUrl;
    private $_extBaseUrl;

    /** @var  $_clientScript CClientScript */
    private $_clientScript;

    /** @var array selectize.js options */
    public $options = array();

    /** @var array defaultOptions can be overwritten from the config using widgetFactory */
    public $defaultOptions = array();

    /** @var array the available options list */
    public $data = array();

    /** @var array the list of selected options when using multiple selection */
    public $selectedValues = array();

    /** @var array list of event name => callback; this is just a helper to avoid passing them in the options array */
    public $callbacks = array();

    /** @var array list of valid callbacks */
    // https://github.com/brianreavis/selectize.js/blob/master/docs/usage.md#callbacks
    protected $validCallbacks = array(
        'load', 'score', 'onInitialize', 'onChange', 'onItemAdd', 'onItemRemove', 'onClear',
        'onDelete', 'onOptionAdd', 'onOptionRemove', 'onDropdownOpen', 'onDropdownClose',
        'onType', 'onLoad'
    );

    /** @var string html element selector */
    public $elementSelector = null;

    /** @var string alias to htmlOptions['placeholder'] */
    public $placeholder = '';

    /** @var string alias to htmlOptions['multiple'] */
    public $multiple = false;

    /** @var bool if true will include the es5-shim script */
    public $ie8support = true;

    /** @var bool whether to include the base css */
    public $includeBaseCss = true;

    /** @var bool if true the widget will have 100% width */
    public $fullWidth = true;

    /** @var bool if the widget is used alongside twitter bootstrap */
    public $useWithBootstrap = false;

    /** @var string the name of the css theme to be used; if empty the default one will be picked */
    public $cssTheme;

    /** @var array the list of available css themes */
    private $_validCssThemes = array('default','legacy','bootstrap2','bootstrap3');


    /**
     * Publishes the assets
     */
    public function publishAssets() {
        // vendor assets:
        $dir = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'vendors' . DIRECTORY_SEPARATOR . 'selectize.js';
        $this->_vendorBaseUrl = Yii::app()->getAssetManager()->publish($dir);

        // extension assets:
        $dir = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'assets';
        $this->_extBaseUrl = Yii::app()->getAssetManager()->publish($dir);
    }


    /**
     * Register the required css and javascript files
     *
     * @throws CException if any of the assets base urls is not defined
     */
    public function registerScripts() {

        if ($this->_vendorBaseUrl === '' || $this->_extBaseUrl === '') {
            throw new CException('baseUrl must be set!');
        }

        $this->_clientScript = Yii::app()->getClientScript();

        // JS
        if (YII_DEBUG) {
            $this->_clientScript->registerScriptFile($this->_vendorBaseUrl . '/dist/js/standalone/selectize.js');
        } else {
            $this->_clientScript->registerScriptFile($this->_vendorBaseUrl . '/dist/js/standalone/selectize.min.js');
        }

        // CSS
        $this->_clientScript->registerCssFile($this->_vendorBaseUrl . '/dist/css/selectize.css');

        $cssTheme = 'default';
        if (!empty($this->cssTheme)) {
            if (in_array($this->cssTheme, $this->_validCssThemes)) {
                $cssTheme = $this->cssTheme;
            }
            else {
                throw new CException('Please provide a valid theme name: '.implode(',', $this->_validCssThemes));
            }
        }
        elseif ($this->useWithBootstrap) {
            $cssTheme = 'bootstrap3';
        }

        $this->_clientScript->registerCssFile($this->_vendorBaseUrl . "/dist/css/selectize.{$cssTheme}.css");


        if ($this->includeBaseCss) {
            $this->_clientScript->registerCssFile($this->_extBaseUrl . '/base.css');
        }

        if ($this->useWithBootstrap) {
            $this->_clientScript->registerCssFile($this->_extBaseUrl . '/bootstrap.css');
        }


        // include es5-shim if ie8 support is needed:
        if ($this->ie8support) {
            // TODO: include the <!--[if lt IE 9]> ... <![endif]--> statement when Yii will support it
            $this->_clientScript->registerScriptFile('http://cdnjs.cloudflare.com/ajax/libs/es5-shim/2.0.8/es5-shim.min.js');
        }
    }


    public function run() {

        if ($this->elementSelector == null) {
            list($this->name, $this->id) = $this->resolveNameId();
            $this->elementSelector = '#' . $this->id;
        }

        // Setup widget & html options:
        $this->setupOptions();

        if ($this->hasModel()) {
            echo CHtml::activeDropDownList($this->model, $this->attribute, $this->data, $this->htmlOptions);
        } else {
            $this->htmlOptions['id'] = $this->id;
            echo CHtml::dropDownList($this->name, $this->value, $this->data, $this->htmlOptions);
        }

        $optionsArray = CMap::mergeArray($this->defaultOptions, $this->options);


        // set the callbacks handlers:
        foreach ($this->callbacks as $event => $jsFunction) {
            if (in_array($event, $this->validCallbacks)) {
                $optionsArray[$event] = 'js:' . $jsFunction;
            }
        }

        $selectizeOptions = CJavaScript::encode($optionsArray);
        $initSelectize = "$('{$this->elementSelector}').selectize({$selectizeOptions});";
        $this->_clientScript->registerScript(__CLASS__ . '_' . $this->id, $initSelectize);
    }


    public function init() {
        $this->publishAssets();
        $this->registerScripts();

        // Setup the widget default options:
        if (empty($this->defaultOptions)) { // check if not initialized by widgetFactory from the config
            $this->defaultOptions = array(
                'create' => true,
            );
        }
    }


    /**
     * Setup widget & html options
     */
    public function setupOptions() {
        $this->data = array('' => '') + $this->data;

        if (!isset($this->htmlOptions['class'])) {
            $this->htmlOptions['class'] = '';
        }

        // Include the base css if required
        if ($this->includeBaseCss) {

            if (stripos($this->htmlOptions['class'], 'yii-selectize') === false) {
                $this->htmlOptions['class'] .= ' yii-selectize';
            }

            // Add the full width class if fullWidth option is set to true
            if ($this->fullWidth && stripos($this->htmlOptions['class'], 'full-width') === false) {
                $this->htmlOptions['class'] .= ' full-width';
            }
        } else if ($this->fullWidth) { // if full with is required but the base css is not included
            if (!isset($this->htmlOptions['style'])) {
                $this->htmlOptions['style'] = '';
            }

            // we add the style attribute
            if (stripos($this->htmlOptions['style'], 'width:') === false) {
                $this->htmlOptions['style'] .= ' width: 100%;';
            }
        }

        // If the placeholder property is set we'll overwrite the htmlOptions attribute
        if (!empty($this->placeholder)) {
            $this->htmlOptions['placeholder'] = $this->placeholder;
        }

        // If the multiple property is set we'll overwrite the htmlOptions attribute
        if (!empty($this->multiple)) {
            $this->htmlOptions['multiple'] = $this->multiple;
        }

        // Set the selected attributed to the given options
        if (!empty($this->selectedValues)) {
            foreach ($this->selectedValues as $valueId) {
                $this->htmlOptions['options'][$valueId]['selected'] = true;
            }
        }

    }


}
