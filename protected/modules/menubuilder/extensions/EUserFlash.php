<?php
/**
 * EUserFlash.php
 *
 * Simplifies user flash messages
 *
 * @author Joe Blocher <yii@myticket.at>
 * @copyright 2011 myticket it-solutions gmbh
 * @license New BSD License http://www.opensource.org/licenses/bsd-license.php
 * @category User Interface
 * @version 1.5
 */
class EUserFlash extends CWidget
{
	const LOG_CATEGORY = 'userflash';

	/**
	 * Add this GLOBALS to the logmessage if loglevel is 'error'
	 *
	 * @var $errorLogVars array
	 */
	public $errorLogVars = array('_GET','_POST');

	/**
	 * a) If $context not is set, all messages independent of the context
	 *    will be rendered
	 *
	 * b) $context='' will render the messages with no context
	 *
	 * c) $context='activate,login' or $context=array('activate','login')
	 *    will render the messages with the specified context
	 *
	 * @var $context null, string or array
	 */
	public $context;

	/**
	 * Delete the messages after rendering
	 *
	 * @var $deleteMessages boolean
	 */
	public $deleteMessages = true;

	/**
	 * The wrapper tag for the messages
	 *
	 * @var $tag string
	 */
	public $tag = 'div';


    /**
     * Wrap as bootstrap alert divs
     * replaces css classes
     * @var bool
     */
    public $bootstrapLayout = false;


    /**
     * @var array of messagetype-strings
     */
    public $bootstrapCloseButtons = array('warning','notice','error','success');


    /**
     * Additional classes for the bootstrap modal-div
     * used as replacement for js-alert messages as bootstrap modal
     * @var string
     */
    public $bootstrapModalClass = 'hide'; //'hide fade'

    /**
     * Caption for the close button of the bootstrap modal
     * @var string
     */
    public $bootstrapCloseButtonText = 'Ok';


	/**
	 * The css class for a unknown messageType
	 *
	 * @var $cssClassDefault string
	 */
	public $cssClassDefault = 'flash-default';

	/**
	 * The css class for a notice message
	 *
	 * @var $cssClassNotice string
	 */
	public $cssClassNotice = 'flash-notice';

	/**
	 * The css class for a error message
	 *
	 * @var $cssClassError string
	 */
	public $cssClassError = 'flash-error';

	/**
	 * The css class for a warning message
	 *
	 * @var $cssClassError string
	 */
	public $cssClassWarning = 'flash-warning';

	/**
	 * The css class for a success message
	 *
	 * @var $cssClassSuccess string
	 */
	public $cssClassSuccess = 'flash-success';

	/**
	 * Javascript code that will be added as clientscript
	 * if $initScriptEnabled = true
	 *
	 * FadeOut success and notice messages: "$('.userflash_success').fadeOut(10000);$('.userflash_notice').fadeOut(10000);";
	 * For all type of messages use: "$('.userflash').....;";
	 *
	 * @var $initScript string
	 */
	public $initScript;

	/**
	 * The htmlOptions for the wrapper tag
	 *
	 * @var $htmlOptions array
	 */
	public $htmlOptions = array();

	/**
	 * Switch On/Off the logging
	 *
	 * @var $loggingEnabled boolean
	 */
	public $loggingEnabled = true;

	/**
	 * Internal usage: true if have to register corescript jquery
	 *
	 * @var $_registerJQuery boolean
	 */
	private $_registerJQuery;


    /**
     * Set the properties for the bootstrap layout
     */
    protected function initBootstrapLayout()
    {
        $this->cssClassDefault = 'alert';
        $this->cssClassNotice = 'alert alert-info';
        $this->cssClassWarning = 'alert alert-block';
        $this->cssClassError = 'alert alert-error';
        $this->cssClassSuccess = 'alert alert-success';
    }

	/**
	 * Render the user flash messages
	 *
	 * @return integer count of the rendered messages
	 */
	public function renderMessages()
	{
        if($this->bootstrapLayout)
            $this->initBootstrapLayout();

        $this->_registerJQuery = !empty($this->initScript) && is_string($this->initScript);

		if (isset($this->context))
			$this->context = self::contextToArray($this->context);

		$renderedCount = 0;
		$flashMessages = Yii::app()->user->getFlashes(false); //don't delete all

		if (!empty($flashMessages))
		{
			$id = $this->getId();

			foreach ($flashMessages as $key=>$message)
				if ($this->decodeFlashKey($key,$messageType,$context,$cssClass,$logLevel))
				{
                    $logContext = $context;
					$htmlOptions = $this->htmlOptions;
					$displayMessage = false;

					if ($this->context === null) //display all
						$displayMessage = true;
					else
					if (empty($this->context) && empty($context)) //display all with empty context
						$displayMessage = true;
					else
					if (!empty($context))
					{
				    	$logContext = array_intersect($this->context,$context);
						$displayMessage = !empty($logContext);
				    }

					if ($displayMessage)
					{
						if(!empty($cssClass))
						  $htmlOptions['class'] = isset($htmlOptions['class'])
						  	                      ? $htmlOptions['class'] .' ' . $cssClass
						  	                      : $cssClass;

						$htmlOptions['class'] = !empty($htmlOptions['class'])
								                    ? $htmlOptions['class'] .' userflash userflash_'.$messageType
								                    : 'userflash userflash_'.$messageType;

						$htmlOptions['id'] = isset($htmlOptions['id'])
						 	                      ? $htmlOptions['id'] .'_' . $renderedCount
						 	                      : $id .'_' . $renderedCount;

					    $this->renderMessage($messageType,$message,$htmlOptions);

						$this->logFlashMessage($messageType,$logContext,$message,$logLevel);

							if ($this->deleteMessages)
								Yii::app()->user->setFlash($key,null);

							$renderedCount++;
					}
				}
		}

		return $renderedCount;
	}


    /**
	 * Render a message
	 */
	protected function renderMessage($messageType,$message,$htmlOptions)
	{
		//render as javascript alert
		if ($messageType=='js_alert')
		{
            //render a bootstrap modal
            if($this->bootstrapLayout)
            {
                $htmlOptions['class'] = 'modal ' . $this->bootstrapModalClass;
                echo CHtml::openTag('div',$htmlOptions);

                echo CHtml::tag('div',array('class'=>'modal-body'),$message);

                $closeButton = CHtml::link($this->bootstrapCloseButtonText,'#',array('class'=>'btn btn-primary','data-dismiss'=>'modal','aria-hidden'=>'true'));
                echo CHtml::tag('div',array('class'=>'modal-footer'),$closeButton);

                echo '</div>';

                $script = "$('#{$htmlOptions['id']}').modal('show');";
            }
            else
            {
                $script = "alert('$message');";
            }

            Yii::app()->clientScript->registerScript(__CLASS__.'#'.$htmlOptions['id'],$script);
		}
		else
		//render as ajax request
		if($messageType=='jq_ajax')
		{
			$this->_registerJQuery = true;

			echo CHtml::tag($this->tag,$htmlOptions,'');
			$script = CHtml::ajax(array(
								'url'=>$message, //$message is the url
								'type'=>'get',
								'update'=>'#'.$htmlOptions['id'],
							));
			Yii::app()->clientScript->registerScript(__CLASS__.'#'.$htmlOptions['id'],$script);
		}
		//render as message inside the tag
		else
        //bootstrap alerts with close button
        if($this->bootstrapLayout && in_array($messageType,$this->bootstrapCloseButtons))
        {
            echo CHtml::openTag($this->tag,$htmlOptions);
            echo CHtml::tag('button',array('class'=>'close','data-dismiss'=>'alert'),'×');
            echo $message;
            echo CHtml::closeTag($this->tag);
        }
        else
			echo CHtml::tag($this->tag,$htmlOptions,$message);
	}

	/**
	 * Set a user flash notice message
	 *
	 * @param string $message
	 * @param mixed $context string or array
	 * @param string $cssClass
	 */
	public static function setNoticeMessage($message,$context='',$logLevel='')
	{
		self::setMessage($message,$context,'',$logLevel,'notice');
	}

	/**
	 * Set a user flash success message
	 *
	 * @param string $message
	 * @param mixed $context string or array
	 * @param string $cssClass
	 */
	public static function setSuccessMessage($message,$context='',$logLevel='')
	{
		self::setMessage($message,$context,'',$logLevel,'success');
	}

	/**
	 * Set a user flash error message
	 *
	 * @param string $message
	 * @param mixed $context string or array
	 * @param string $cssClass
	 */
	public static function setErrorMessage($message,$context='',$logLevel=CLogger::LEVEL_ERROR)
	{
	   self::setMessage($message,$context,'',$logLevel,'error');
	}

	/**
	 * Set a user flash warning message
	 *
	 * @param string $message
	 * @param mixed $context string or array
	 * @param string $cssClass
	 */
	public static function setWarningMessage($message,$context='',$logLevel=CLogger::LEVEL_WARNING)
	{
	   self::setMessage($message,$context,'',$logLevel,'warning');
	}

	/**
	 * Set a user flash as javascript alert
	 *
	 * @param string $message
	 * @param mixed $context string or array
	 * @param string $cssClass
	 */
	public static function setAlertMessage($message,$context='',$logLevel='')
	{
		self::setMessage($message,$context,'',$logLevel,'js_alert');
	}

	/**
	 * Set a user flash as a ajax call
	 *
	 * @param string $message
	 * @param mixed $context string or array
	 * @param string $cssClass
	 */
	public static function setAjaxMessage($url,$context='',$logLevel='')
	{
		self::setMessage($url,$context,'',$logLevel,'jq_ajax');
	}

	/**
	 * Set a user flash message without a type
	 * You should maybe define a cssClass
	 *
	 * @param string $message
	 * @param mixed $context string or array
	 * @param string $cssClass
	 */
	public static function setMessage($message,$context='',$cssClass='',$logLevel='',$messageType='default')
	{
		$key = self::encodeFlashKey($messageType,$context,$cssClass,$logLevel);
		Yii::app()->user->setFlash($key,$message);
	}

	/**
	 * Convert context to array
	 *
	 * @param mixed $context
	 * @return array
	 */
	protected static function contextToArray($configString)
	{
		if (is_array($configString))
			return $configString;

		$result = array();

		if (is_string($configString) && !empty($configString))
		{
			if (strpos($configString,','))
			{
				$parts = explode(',',$configString);
				foreach ($parts as $part)
				{
					$part = trim($part);
					if (!empty($part))
					   $result[] = trim($part);
				}
			}
			else
			   $result[] = trim($configString);
		}

		return $result;
	}

	/**
	 * Encode the flash key
	 *
	 * @param string $messageType
	 * @param mixed $context
	 * @param string $cssClass
	 * @return string
	 */
	protected static function encodeFlashKey($messageType,$context,$cssClass,$logLevel)
	{
		$id = uniqid(rand(),true);

		$context = self::contextToArray($context);

		if (empty($cssClass))
			$cssClass = '';

		return 'euserflash__' . $id . '__' . $messageType . '__' . $cssClass . '__' . $logLevel . '__' . implode('__',$context);
	}

	/**
	 * Decode the flash key
	 *
	 * @param string $key
	 * @param string $messageType
	 * @param array $context
	 * @param string $cssClass
	 */
	protected function decodeFlashKey($key,&$messageType='',&$context=null,&$cssClass='',&$logLevel='')
	{
		if (strpos($key,'euserflash__') !== 0)
			return false;

		list($marker,$id,$messageType,$cssClass,$logLevel,$context) = explode('__',$key,6);

		if (empty($cssClass))
		   $cssClass = $this->getCssClass($messageType);

		if (!empty($context))
			if(strpos($context,'__'))
				$context = explode('__',$context);
		else
			$context = array($context);

		return true;
	}

	/**
	 * Get the css class for the specified $messageType
	 *
	 * @param string $messageType
	 * @return string
	 */
	protected function getCssClass($messageType)
	{
		$result = '';

		$properties = get_object_vars($this);

		foreach ($properties as $name => $value)
		if ($name == 'cssClass'.ucfirst($messageType))
		{
            $result = $value;
			break;
		}

		if (empty($result))
			$result = $this->cssClassDefault;

		return $result;
	}

	/**
	 * Log the message
	 * Adds user, ipaddress, controller route
	 * _GET, _POST ... if $logLevel='error' configurable: see property errorLogVars
	 *
	 * @param mixed $messageType
	 * @param mixed $context
	 * @param mixed $message
	 * @param mixed $logLevel
	 * @return
	 */
	protected function logFlashMessage($messageType,$context,$message,$logLevel)
	{
		if (!$this->loggingEnabled || empty($logLevel))
			return;

		if (empty($messageType))
			$messageType = 'default';

		$category = self::LOG_CATEGORY . '.' . $messageType;

		if (!empty($context) && is_array($context))
			$category .= '.'.implode('.',$context);

		$logInfo = '';

		if(($user=Yii::app()->getComponent('user',false))!==null)
			$logInfo='User: '.$user->getName().' (ID: '.$user->getId().') ';


		$logInfo .= 'Route: '. Yii::app()->controller->getRoute().' ';
		$logInfo .= 'IP: ' . Yii::app()->getRequest()->userHostAddress.' ';

		$message = $logInfo . 'Message: ' . $message;

		$logVars = array();
		if ($logLevel == 'error' && !empty($this->errorLogVars))
			foreach($this->errorLogVars as $name)
			{
				if(!empty($GLOBALS[$name]))
					$logVars[]="\${$name}=".var_export($GLOBALS[$name],true);
			}

		if (!empty($logVars))
			$message .= "\n" . implode("\n",$logVars);

		Yii::log($message,$logLevel,$category);
	}

	/**
	 * EUserFlash::registerClientScript()
	 *
	 * @return
	 */
	public function registerClientScript()
	{
	  if ($this->_registerJQuery)
	  	Yii::app()->clientScript->registerCoreScript('jquery');

	  if (!empty($this->initScript) && is_string($this->initScript))
		  Yii::app()->clientScript->registerScript(__CLASS__.'#'.$this->getId(), $this->initScript);
	}

	/**
	 * Render the flash messages
	 */
	public function run()
	{
		if ($this->renderMessages())
			$this->registerClientScript();
	}

	/**
	 * Render the flash messages and return renderedCount
	 *
	 * @param CBaseController $controller
	 * @param array $properties
	 * @return integer count of rendered messages
	 */
	public static function renderFlashes($controller,$properties=array())
	{
		$widget=$controller->createWidget('EUserFlash',$properties);
		$renderedCount = $widget->renderMessages();
		if ($renderedCount)
			$widget->registerClientScript();
		return $renderedCount;
	}

}