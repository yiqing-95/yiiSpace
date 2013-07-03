<?php

class EasyUI extends CApplicationComponent
{
	// EasyUI plugins.
	const PLUGIN_DIALOG = 'dialog';
	const PLUGIN_BUTTON = 'button';


	/**
	 * @var boolean whether to register the EasyUI core CSS (EasyUI.min.css).
	 * Defaults to true.
	 */
	public $coreCss = true;


	/**
	 * Initializes the component.
	 */
	public function init()
	{

		parent::init();
	}

	/**
	 * Registers the EasyUI CSS.
	 */
	public function registerCoreCss()
	{
		$this->registerAssetCss('EasyUI' . (!YII_DEBUG ? '.min' : '') . '.css');
	}


	/**
	 * Registers a specific css in the asset's css folder
	 * @param string $cssFile the css file name to register
	 * @param string $media the media that the CSS file should be applied to. If empty, it means all media types.
	 */
	public function registerAssetCss($cssFile, $media = '')
	{
		Yii::app()->getClientScript()->registerCssFile($this->getAssetsUrl() . "/css/{$cssFile}", $media);
	}

	/**
	 * Registers the core JavaScript.
	 * @since 0.9.8
	 */
	public function registerCoreScripts()
	{
		$this->registerJS(Yii::app()->clientScript->coreScriptPosition);
		$this->registerTooltip();
		$this->registerPopover();
	}

	/**
	 * Registers a EasyUI JavaScript plugin.
	 * @param string $name the name of the plugin
	 * @param string $selector the CSS selector
	 * @param array $options the plugin options
	 * @param string $defaultSelector the default CSS selector
	 * @since 0.9.8
	 */
	protected function registerPlugin($name, $selector = null, $options = array(), $defaultSelector = null)
	{
		if (!isset($selector) && empty($options))
		{
			// Initialization from extension configuration.
			$config = isset($this->plugins[$name]) ? $this->plugins[$name] : array();

			if (isset($config['selector']))
				$selector = $config['selector'];

			if (isset($config['options']))
				$options = $config['options'];

			if (!isset($selector))
				$selector = $defaultSelector;
		}

		if (isset($selector))
		{
			$key = __CLASS__ . '.' . md5($name . $selector . serialize($options) . $defaultSelector);
			$options = !empty($options) ? CJavaScript::encode($options) : '';
			Yii::app()->clientScript->registerScript($key, "jQuery('{$selector}').{$name}({$options});");
		}
	}

	/**
	 * Returns the URL to the published assets folder.
	 * @return string the URL
	 */
	public function getAssetsUrl()
	{
		if (isset($this->_assetsUrl))
			return $this->_assetsUrl;
		else
		{
			$assetsPath = Yii::getPathOfAlias('EasyUI.assets');
			$assetsUrl = Yii::app()->assetManager->publish($assetsPath, false, -1, YII_DEBUG);
			return $this->_assetsUrl = $assetsUrl;
		}
	}

	/**
	 * Returns the extension version number.
	 * @return string the version
	 */
	public function getVersion()
	{
		return '1.0.5';
	}
}
