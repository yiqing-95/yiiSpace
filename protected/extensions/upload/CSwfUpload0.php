<?php
class CSwfUpload extends CWidget
{
	public $postParams=array();
	public $config=array();

	public function run()
	{
		$assets = dirname(__FILE__).'/swfupload';
		$baseUrl = Yii::app()->assetManager->publish($assets);
		$cs = Yii::app()->getClientScript();

		$cs->registerScript(__CLASS__.'swfuv',"var swfuPath='" . $baseUrl . "';", CClientScript::POS_HEAD);
		$cs->registerScriptFile($baseUrl . '/swfupload.js', CClientScript::POS_HEAD);
		$cs->registerCssFile($baseUrl . '/swfupload.css');

		//check existing localization file handlers
		if (file_exists($assets . '/handlers_' . Yii::app()->language . '.js'))
		{
			$handlers = $baseUrl . '/handlers_' . Yii::app()->language . '.js';
		}
		else
		{
			$handlers = $baseUrl . '/handlers.js';
		}
		$cs->registerScriptFile($handlers, CClientScript::POS_END);


		$postParams = array('PHPSESSID'=>session_id());
		if(isset($this->postParams))
		{
			$postParams = array_merge($postParams, $this->postParams);
		}
		$config = array(
			'post_params'=> $postParams,
			'flash_url'=> $baseUrl. '/swfupload.swf',
			'button_image_url'=> $baseUrl .'/images/SmallSpyGlassWithTransperancy_17x18.png',
			'jsHandlerUrl'=>$handlers,
		);

		$config = array_merge($config, $this->config);
		$config = CJavaScript::encode($config);
		$cs->registerScript(__CLASS__, "
		var swfu;
			swfu = new SWFUpload($config);
		");
	}
}