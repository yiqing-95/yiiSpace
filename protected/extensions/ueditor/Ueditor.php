<?php

/**
 * 
 */
class Ueditor extends CInputWidget 
{
	
	/******* widget private vars *******/
	private $baseUrl			= null;
	private $jsFiles			= array(
									'/editor_config.js',
									'/editor_all_min.js',
								);
	private $cssFiles			= array(
									'/themes/default/ueditor.css',
								);							
								
	public $getId = 'editor';

	public $options = NULL;
	
	public $UEDITOR_HOME_URL = '../';
	
								
	/**
	* Initialize the widget
	*/
	public function init()
	{
		//Publish assets
		$this->publishAssets();
		$this->registerClientScripts();
		parent::init();
	}
	
	/**
	* Publishes the assets
	*/
	public function publishAssets()
	{
		$dir = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'ueditor1_2_2_0';
		$this->baseUrl = Yii::app()->getAssetManager()->publish($dir);
	}
	
	/**
	* Registers the external javascript files
	*/
	public function registerClientScripts()
	{
		
		if ($this->baseUrl === '')
			throw new CException(Yii::t('Ueditor', 'baseUrl must be set. This is done automatically by calling publishAssets()'));
		
		//Register the main script files
		$cs = Yii::app()->getClientScript();
		foreach($this->jsFiles as $jsFile) {
			$ueditorJsFile = $this->baseUrl . $jsFile;
			$cs->registerScriptFile($ueditorJsFile, CClientScript::POS_HEAD);
		}
		
		// add the css
		foreach($this->cssFiles as $cssFile) {
			$ueditorCssFile = $this->baseUrl . $cssFile;
			$cs->registerCssFile($ueditorCssFile);
		}
		//Register the widget-specific script on ready
		 $js = $this->generateOnloadJavascript();
		$cs->registerScript('ueditor'.$this->getId, $js, CClientScript::POS_END);
	}
	
	protected function generateOnloadJavascript()
	{
		$js = "var editor = new baidu.editor.ui.Editor({UEDITOR_HOME_URL:'".$this->baseUrl.$this->UEDITOR_HOME_URL."',".$this->options."});";	
		$js.= "editor.render('$this->getId');";	
		return $js;
	}

	/**
	* Run the widget
	*/
	public function run()
	{
			
		parent::run();
	}
}
