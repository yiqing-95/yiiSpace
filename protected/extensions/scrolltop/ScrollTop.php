<?php
/**
 * Based on The ScrollTop extension by JetBrains PhpStorm.
 * @link http://www.yiiframework.com/extension/scrolltop
 * @author Jonathan Asquier
 */
class ScrollTop extends CWidget
{
	/**
	 * @var string URL of the published asset folder
	 */
	private $_assetsUrl;
	
	/**
	* @var string id of the arrow box
	*/
	private $id = 'scroll-top';
	
	/**
	* @var string speed of the animation
	*/
	public $speed = 'slow';
	
	/**
	 * @var int scrollTop value to start showing the arrow 
	 */
	public  $fadeTransitionStart = 10;

	/**
	 * @var int scrollTop value where the arrow is completely visible
	 */
	public  $fadeTransitionEnd = 200;

	/**
	 * @return string URL of the image folder
	 */
	private function getImgUrl()
	{
		return $this->_assetsUrl.'/img/';
	}
	
	public function init()
	{
		$assetsPath=dirname(__FILE__).DIRECTORY_SEPARATOR.'assets';
		$this->_assetsUrl = Yii::app()->getAssetManager()->publish($assetsPath);

		$cs = Yii::app()->getClientScript();
		
		// Arrow design
		$cs->registerCss('css-scroll-top' , '
			#' .$this->id. '
			{
				position:fixed;
				bottom:20px;
				right: 20px;
				width:50px;
				height:50px;
				background-color:#DDD;
				-webkit-border-radius: 5px;
				-moz-border-radius: 5px;
				border-radius: 5px;
				opacity:0;
			}

			#' .$this->id. '-arrow
			{
				padding:10px 12px;
			}
		');
		
		//JS Scripts
		$cs->registerCoreScript('jquery');
		
		$cs->registerScript('js-scroll-top' , '
			var opacity  = function(){
				var scrollTop = $(window).scrollTop();
				if(scrollTop > ' .$this->fadeTransitionEnd. ')
					opacity = 1;
				else if(scrollTop >  ' .$this->fadeTransitionStart. ' && scrollTop <  ' .$this->fadeTransitionEnd. ')
					opacity = scrollTop/ ' .$this->fadeTransitionEnd. ';
				else
					opacity = 0;
				$("#' .$this->id. '").css("opacity", opacity);
			};
			
			$("#' .$this->id. '").click(function() {
				$("html,body").animate({ scrollTop : 0 }, "' . ($this->speed) . '");
				return false;
			});
			$(window).bind("scroll", null, opacity);
			opacity();
			', CClientScript::POS_LOAD
		);
		echo '<div id="' .$this->id. '"><div id="' .$this->id. '-arrow">'.CHtml::image($this->getImgUrl().'arrow.png').'</div></div>';
	}
}


