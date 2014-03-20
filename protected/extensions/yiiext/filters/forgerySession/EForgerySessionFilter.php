<?php
/**
 * EForgerySessionFilter class file.
 *
 * @author Veaceslav Medvedev <slavcopost@gmail.com>
 * @link http://code.google.com/p/yiiext/
 * @license http://www.opensource.org/licenses/mit-license.php
 */
/**
 * EForgerySessionFilter performs load session with id which sent with POST or GET parameter.
 *
 * @author Veaceslav Medvedev <slavcopost@gmail.com>
 * @version 0.1
 * @package yiiext.filters.forgerySession
 */
class EForgerySessionFilter extends  CFilter
{
	/**
	 * @var string the name of the parameter that stores session id.
	 * Defaults to PHP_SESSION_ID
	 */
	public $paramName='PHP_SESSION_ID';
	/**
	 * @var string the method which sent the data. This should be either 'POST', 'GET' or 'AUTO'.
	 * Defaults to 'POST'.
	 */
	public $method='POST';

	/**
	 * Performs the pre-action filtering.
	 * @param CFilterChain the filter chain that the filter is on.
	 * @return boolean whether the filtering process should continue and the action
	 * should be executed.
	 */
	protected function preFilter($filterChain)
	{
		switch(strtoupper($this->method))
		{
			case 'GET':$method='getQuery';break;
			case 'AUTO':$method='getParam';break;
			default:$method='getPost';
		}
		if(is_string($sessionId=Yii::app()->getRequest()->$method($this->paramName)))
		{
			$session=Yii::app()->getSession();
			$session->close();
			$session->setSessionId($sessionId);
			$session->open();
		}
		return true;
	}
}
