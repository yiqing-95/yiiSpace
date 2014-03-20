<?php

/**
 * ECollapse makes a HTML DOM element collapsible as specified by passed options.
 *
 * Based on the jQuery-Collapse script
 * @link http://webcloud.se/code/jQuery-Collapse/
 * @link https://github.com/danielstocks/jQuery-Collapse
 *
 * @author Luke Jurgs
 * @version 0.0.4-2012-06-07
 */
class ECollapse extends CWidget {

	/**
	 * @const ARROW_SET_A arrow set a.
	 */
	const ARROW_SET_A = 'a';
	/**
	 * @const ARROW_SET_B arrow set b.
	 */
	const ARROW_SET_B = 'b';
	/**
	 * @const ARROW_SET_C arrow set c.
	 */
	const ARROW_SET_C = 'c';
	/**
	 * @const ARROW_SMALL small arrows.
	 */
	const ARROW_SMALL = 'small';
	/**
	 * @const ARROW_MEDIUM medium arrows.
	 */
	const ARROW_MEDIUM = 'medium';
	/**
	 * @const ARROW_LARGE large arrows.
	 */
	const ARROW_LARGE = 'large';
	/**
	 * @const ARROW_POSITION_LEFT arrows positioned on the left.
	 */
	const ARROW_POSITION_LEFT = 'left';
	/**
	 * @const ARROW_POSITION_RIGHT arrows positioned on the right.
	 */
	const ARROW_POSITION_RIGHT = 'right';

	/**
	 * @var string the css selector to apply the behaviour to.
	 * Default: '.collapse'.
	 */
	public $selector = '.collapse';
	/**
	 * @var boolean should the selected element start in a collapsed state.
	 * Default: false.
	 */
	public $collapsed = true;
	/**
	 * @var mixed the duration of the default animation, replaces the string '{duration}' in $show and $hide.
	 * Valid values are: 'fast', 'slow' or a integer specifying milliseconds.
	 * Default: 'fast'.
	 */
	public $duration = 'fast';
	/**
	 * @var string the CSS file used for the widget. If null the included css is used.
	 * Default: null.
	 * @see assets/css/jquery.collapse.css
	 */
	public $cssFile;
	/**
	 * @var string a javascript function that defines how the content should be visually expanded.
	 * @see CJavaScript::encode
	 */
	public $show = 'js:
		function() {
			this.animate({
				opacity: "toggle",
				height: "toggle"
			}, "{duration}");
		}';
	/**
	 * @var string a function that defines how the content should be visually hidden.
	 * @see CJavaScript::encode
	 */
	public $hide = 'js:
		function() {
			this.animate({
				opacity: "toggle",
				height: "toggle"
			}, "{duration}");
		}';
	/**
	 * @var string the css selector of the element(s) you want act as clickable headings.
	 * Default: 'h3'.
	 */
	public $head = 'h3';
	/**
	 * @var string the css selector of the element(s) to group hidden content.
	 * Default: 'div, ul'.
	 */
	public $group = 'div, ul';
	/**
	 * @var string the name of cookie used by the plugin.
	 * Default: 'collapse'.
	 */
	public $cookieName = 'collapse';
	/**
	 * @var bool should the plugin use the inbuilt cookie functionality to remember the state of collapsible
	 * elements between visits.
	 * Default: true.
	 */
	public $cookieEnabled = true;
	/**
	 * @var string the arrow set to use. Only used if {@link cssFile} is not set.
	 * <pre>Possible values:
	 * ECollapse::ARROW_SET_A
	 * ECollapse::ARROW_SET_B
	 * ECollapse::ARROW_SET_C
	 * </pre>
	 */
	public $arrowSet = self::ARROW_SET_A;
	/**
	 * @var string the arrow size to use. Only used if {@link cssFile} is not set.
	 * <pre>Possible values:
	 * ECollapse::ARROW_SMALL
	 * ECollapse::ARROW_MEDIUM
	 * ECollapse::ARROW_LARGE
	 * </pre>
	 */
	public $arrowSize = self::ARROW_SMALL;
	/**
	 * @var string the arrow position to use. Only used if {@link cssFile} is not set.
	 * <pre>Possible values:
	 * ECollapse::ARROW_POSITION_LEFT
	 * ECollapse::ARROW_POSITION_RIGHT
	 * </pre>
	 */
	public $arrowPosition = self::ARROW_POSITION_LEFT;

	private $_assetUrl;

	/**
	 * Initializes the widget.
	 * This method is called by {@link CBaseController::createWidget}
	 * and {@link CBaseController::beginWidget} after the widget's
	 * properties have been initialized.
	 */
	public function init() {
		$this->_assetUrl = Yii::app()->assetManager->publish(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'assets');
	}

	/**
	 * Executes the widget.
	 * This method is called by {@link CBaseController::endWidget}.
	 */
	public function run() {
		//setup the show and hide functions if they are set
		$this->show = str_replace('{duration}', $this->duration, $this->show);
		$this->hide = str_replace('{duration}', $this->duration, $this->hide);
		$this->registerClientScript();
	}

	/**
	 * Registers necessary client scripts.
	 */
	public function registerClientScript() {
		$clientScript = Yii::app()->clientScript;
		//register javascript
		$clientScript->registerCoreScript('jquery');
		//use minified JS if not debugging
		if (YII_DEBUG) {
			$cookieScript = '/js/jquery.cookie.js';
			$collapseScript = '/js/jquery.collapse.js';
			$defaultCss = '/css/jquery.collapse.css';
		} else {
			$cookieScript = '/js/jquery.cookie.min.js';
			$collapseScript = '/js/jquery.collapse.min.js';
			$defaultCss = '/css/jquery.collapse.min.css';
		}
		//do not register unused JS
		if ($this->cookieEnabled) {
			$clientScript->registerScriptFile($this->_assetUrl . $cookieScript);
		}
		$clientScript->registerScriptFile($this->_assetUrl . $collapseScript);

		$javascript = '';
		//register css
		if (null === $this->cssFile) {
			$clientScript->registerCssFile($this->_assetUrl . $defaultCss);
			//apply the class in the default css to the head elements
			$javascript .= "jQuery('$this->selector $this->head').addClass('jq-c-h');";
			$javascript .= "jQuery('$this->selector $this->head').addClass('jq-c-{$this->arrowSet}');";
			$javascript .= "jQuery('$this->selector $this->head').addClass('jq-c-{$this->arrowSize}');";
			$javascript .= "jQuery('$this->selector $this->head').addClass('jq-c-{$this->arrowPosition}');";
		} else {
			$clientScript->registerCssFile($this->cssFile);
		}

		$id = __CLASS__ . '#' . $this->getId();

		//add a class to the html element that lets us hide the groups so they don't flash
		$clientScript->registerScript('jquery-collapse-hide-group-script', 'document.documentElement.className = "js";', CClientScript::POS_HEAD);
		$groups = explode(',', $this->group);
		foreach ($groups as &$group) {
			$group = trim($group);
			$group = ".js {$this->selector} .inactive ~ $group .hide";
		}
		$groups = implode(', ', $groups);
		$clientScript->registerCss("{$id}-jquery-collapse-hide-group-css", "$groups  { display : none; }");

		//build the jquery collapsed script options
		$options = array(
			'head' => $this->head,
			'group' => $this->group,
			'cookieName' => $this->cookieName,
			'cookieEnabled' => $this->cookieEnabled,
		);

		if (!empty($this->show)) {
			$options['show'] = $this->show;
		}
		if (!empty($this->hide)) {
			$options['hide'] = $this->hide;
		}

		$options = CJavaScript::encode($options);

		//set the class of head elements to inactive
		if ($this->collapsed) {
			$javascript .= "
				jQuery('{$this->selector} {$this->head}').each(function() {
					if (!$(this).hasClass('active') && !$(this).hasClass('inactive')) {
						$(this).addClass('inactive');
					}
				});
			";
		} else {
			$javascript .= "
				jQuery('{$this->selector} {$this->head}').each(function() {
					if (!$(this).hasClass('active') && !$(this).hasClass('inactive')) {
						$(this).addClass('active');
					}
				});
			";
		}
		$javascript .= "jQuery('{$this->selector}').collapse($options);";
		$clientScript->registerScript($id, $javascript);
	}

}