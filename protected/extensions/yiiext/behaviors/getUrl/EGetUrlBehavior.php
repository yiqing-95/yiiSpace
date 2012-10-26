<?php
/**
 * EGetUrlBehavior class file.
 *
 * @author Veaceslav Medvedev <slavcopost@gmail.com>
 * @link http://code.google.com/p/yiiext/
 * @license http://www.opensource.org/licenses/mit-license.php
 */
/**
 * EGetUrlBehavior get absolute or relative url to file.
 *
 * @author Veaceslav Medvedev <slavcopost@gmail.com>
 * @version 0.1
 * @package yiiext.behaviors.getUrl
 */
class EGetUrlBehavior extends CBehavior
{
	/**
	 * @var string the application home url. Defaults to CHttpRequest::getBaseUrl().
	 */
	public $homeUrl;
	/**
	 * @var string the base relative url.
	 */
	public $baseUrl='';
	/**
	 * @var boolean whether returned url be absolute. Defaults to false.
	 */
	public $useAbsolute=false;

	/**
	 * @param string $url the relative url.
	 * @param boolean $absolute whether returned url be absolute. Defaults to false.
	 * @return string the absolute or relative url to file.
	 */
	public function getUrl($url='',$absolute=false)
	{
		if($this->homeUrl===null)
			$this->homeUrl=Yii::app()->getRequest()->getBaseUrl($this->useAbsolute || $absolute);

		if(!empty($this->baseUrl) && $this->baseUrl[0]!='/')
			$this->baseUrl='/'.$this->baseUrl;

		if($url!='' && $url[0]!='/')
			$url='/'.$url;

		return $this->homeUrl.$this->baseUrl.$url;
	}
	/**
	 * @return string the absolute or relative url to file.
	 */
	public function __toString()
	{
		return $this->getUrl();
	}
}
