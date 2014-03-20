<?php
/**
 * EUploadifyWidget class file.
 *
 * @author Veaceslav Medvedev <slavcopost@gmail.com>
 * @link http://code.google.com/p/yiiext/
 * @license http://www.opensource.org/licenses/bsd-license.php
 */
/**
 * EUploadifyWidget adds {@link http://www.uploadify.com/ uploadify jQuery plugin} as a form field widget.
 *
 * @author Veaceslav Medvedev <slavcopost@gmail.com>
 * @version 1.7
 * @package yiiext.widgets.uploadify
 * @link http://www.uploadify.com/
 */
class EUploadifyWidget extends CInputWidget
{
	/**
	 * @var array the uploadify package.
	 * Defaults to array(
	 *     'basePath'=>dirname(__FILE__).'/vendors/jquery.uploadify-v2.1.4',
	 *     'js'=>array('jquery.uploadify'.(YII_DEBUG?'':'.min').'.js','swfobject.js'),
	 *     'css'=>array('uploadify.css'),
	 *     'depends'=>array('jquery'),
	 * )
	 * @see CClientScript::$packages
	 * @since 1.7
	 */
	public $package=array();
	/**
	 * @var string|null the name of the POST parameter where save session id.
	 * Or null to disable sending session id. Use {@link EForgerySessionFilter} to load session by id from POST.
	 * Defaults to null.
	 * @see EForgerySessionFilter
	 */
	public $sessionParam;
	/**
	 * @var array extension options. For more info read {@link http://www.uploadify.com/documentation/ documentation}
	 */
	public $options=array();

	/**
	 * Init widget.
	 */
	public function init()
	{
		list($this->name,$this->id)=$this->resolveNameId();
		// Set defaults package.
		if($this->package==array())
		{
			$this->package=array(
				'basePath'=>dirname(__FILE__).'/vendors/jquery.uploadify-v2.1.4',
				'js'=>array(
					'jquery.uploadify'.(YII_DEBUG?'':'.min').'.js',
					'swfobject.js',
				),
				'css'=>array(
					'uploadify.css',
				),
				'depends'=>array(
					'jquery',
				),
			);
		}
		// Publish package assets. Force copy assets in debug mode.
		if(!isset($this->package['baseUrl']))
		{
			$this->package['baseUrl']=Yii::app()->getAssetManager()->publish($this->package['basePath'],false,-1,YII_DEBUG);
		}

		$baseUrl=$this->package['baseUrl'];

		if(!isset($this->options['uploader']))
		{
			$this->options['uploader']=$baseUrl.'/uploadify.swf';
		}

		if(!isset($this->options['cancelImg']))
		{
			$this->options['cancelImg']=$baseUrl.'/cancel.png';
		}

		if(!isset($this->options['expressInstall']))
		{
			$this->options['expressInstall']=$baseUrl.'/expressInstall.swf';
		}

		if(!isset($this->options['script']))
		{
			$this->options['script']=$baseUrl.'/uploadify.php';
		}

		// TODO: Decide what to do with checkScript.
		// if(!isset($this->options['checkScript']))
		// {
		//  	$this->options['checkScript']=$this->assetsUrl.'/check.php';
		// }

		// Send session id with via POST.
		if($this->sessionParam!==null&&isset($this->options['scriptData'][$this->sessionParam]))
		{
			$this->options['scriptData'][$this->sessionParam]=Yii::app()->getSession()->getSessionId();
		}

		// TODO: Csrf Validation
		// С этим пока проблема. Т.к. flash upload не посылает куки из-за политики безопасности.
		// if(Yii::app()->getRequest()->enableCsrfValidation && (!isset($this->options['method']) || $this->options['method']=='POST'))
		// {
		//  	$this->options['scriptData'][Yii::app()->getRequest()->csrfTokenName]=Yii::app()->getRequest()->getCsrfToken();
		// }

		// fileDesc is required if fileExt set.
		if(!empty($this->options['fileExt'])&&empty($this->options['fileDesc']))
		{
			$this->options['fileDesc']=Yii::t('yiiext','Supported files ({fileExt})',array('{fileExt}'=>$this->options['fileExt']));
		}

		// Generate fileDataName for linked with model attribute.
		$this->options['fileDataName']=$this->name;

		$this->registerClientScript();
	}
	/**
	 * Run widget.
	 */
	public function run()
	{
		if($this->hasModel())
		{
			echo CHtml::activeFileField($this->model,$this->attribute,$this->htmlOptions);
		}
		else
		{
			echo CHtml::fileField($this->name,$this->value,$this->htmlOptions);
		}
	}
	/**
	 * @return void
	 * Register CSS and Script.
	 */
	protected function registerClientScript()
	{
		$cs=Yii::app()->getClientScript();
		$cs->packages['uploadify']=$this->package;
		$cs->registerPackage('uploadify');
		$cs->registerScript(__CLASS__.'#'.$this->id,'jQuery("#'.$this->id.'").uploadify('.CJavaScript::encode($this->options).');',CClientScript::POS_READY);
	}
}
