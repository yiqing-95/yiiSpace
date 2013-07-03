<?php
/**
 *
 * This extension is a wrapper for the excellent
 * {@link https://github.com/jeff-optimizely/Guiders-JS Guiders JQuery plugin} by Jeff Pickhardt
 *
 * Example :
 * <pre>
 * $this->widget('ext.eguiders.EGuider', array(
 *		'id'=> 'intro',
 *		'next' => 'position',
 *		'title'=> 'Welcome',
 *		'buttons' => array(array('name'=>'Next')),
 *		'description' => 'some text here',
 *		'overlay'=> true,
 *		'xButton'=>true,
 *		'show'=> true
 *	)
 * );
 * </pre>
 * For more info on how to use the Guiders plugin, please refer to the
 * {@link https://github.com/jeff-optimizely/Guiders-JS project page}.
 *
 * @author Raoul
 * @version 1.1.0.0
 *
 */
class EGuider extends CWidget {
	
	/**
	 * @var $id identifier of the guide (used to chain guides)
	 */
	public $id;
	/**
	 * @var $onShow array of buttons to display in the guide. Each button is defined as an associative
	 * array with following keys:<br/>
	 * <ul>
	 * 	<li><b>name</b> : name of the button. 2 predefined buttons are recognized <b>Next</b> and <b>Close</b></li>
	 *  <li><b>onclick</b> : javascript function to handle custom buttons action when clicked</li>
	 *  <li><b>classString</b>: CSS class to apply to the button</li>
	 * </ul>
	 * Example :
	 * <pre>
	 * 		'buttons' => array(
	 *			array('name'=>'Previous','onclick'=> "js:function(){guiders.hideAll(); guiders.show('previous_guide_id');}"),
	 *			array('name'=>'Show me more','onclick'=> "js:function(){guiders.next();}"),
	 *			array('name'=>'Exit','onclick'=> "js:function(){guiders.hideAll();}")
	 *	),
	 * </pre>
	 */
	public $buttons;
	/**
	 * @var $attachTo  (optional) JQuery selector identifying an HTML element that is used to position
	 * the Guide (see 'position')
	 */
	public $attachTo;
	/**
	 * @var boolean Determines whether or not the browser scrolls to the element.
	 */
	public $autoFocus;
	/**
	 * @var $position  (optional / required if using attachTo) integer value defining guide orientation
	 * relatively to the HTML element it is attached to. Use clock values (from 0 to 12)
	 */
	public $position;
	/**
	 * @var $buttonCustomHTML (optional) custom HTML that gets appended to the buttons div
	 */
	public $buttonCustomHTML;
	/**
	 * @var $description  text description that shows up inside the guider
	 */
	public $description;
	/**
	* @var $highlight (optional) selector of the html element you want to highlight (will cause element
	* to be above the overlay)
	*/
	public $highlight;
	/**
	 * @var boolean (defaults to true) the guider will be shown auto-shown when a page is loaded with a url hash parameter #guider=guider_name
	 */
	public $isHashable;
	/**
	* @var $offset fine tune the position of the guider, e.g. { left:0, top: -10 }
	*/
	public $offset;
	/**
	* @var $overlay (optional) if true, an overlay will pop up between the guider and the rest of the page
	*/
	public $overlay;
	/**
	* @var $title title of the guider
	*/
	public $title;
	/**
	* @var $width (optional) custom width of the guider (it defaults to 400px)
	*/
	public $width;
	/**
	* @var $xButton (optional) if true, a X will appear in the top right corner of the guider, as another
	* way to close the guider
	*/
	public $xButton;
	/**
	* @var $classString (optional) allows for styling different guiders differently based upon their classes
	*/
	public $classString;
	/**
	 * @var boolean (optional) if true, the escape key will close the currently open guider
	 */
	public $closeOnEscape;
	/**
	* @var $next id of the next guide
	*/
	public $next;
	/**
	* @var $onShow javascript function called before the guide is displayed
	*/
	public $onShow;
	/**
	 * @var (optional) additional function to call if a guider is closed by the x button, close button, or escape key
	 */
	public $onClose;
	/**
	 * @var (optional) additional function to call when the guider is hidden
	 */
	public $onHide;
	
	// public options below are not part of the Guiders Plugin options
	/**
	* @var $show additional option added to trigger the .show() method call on a guider (default to FALSE)
	*/
	public $show=false;
	/**
	 * @var cssFile path for your custom css file .It is important to understand that in all Guiders included into a page,
	 * share the same CSS files, so if you delcare a stylesheet on the first guide of your page, no need to declare it for
	 * other guides in the same page.<br/>Another point is that even if you declare a custom stylesheet, <b>the default
	 * CSS file provided with the Guiders JQuery plugin is always included</b> : your custom stylesheet must overloads it
	 * and redefine styles you want to change.
	 */
	public $cssFile=null;
	
	private $assetsPath;
	static private $registerDone=false;
	
	// list of supported options
	protected $supportedClientOptions = array(
		'id','buttons','attachTo','buttonCustomHTML','description','highlight','offset','overlay',
		'position','title','width','xButton','classString','next','onShow',
		'autoFocus', 'closeOnEscape','isHashable','onClose','onHide'
	);
	/**
	 * Published all stuff inside the extension's 'assets' folder.
	 * @see CWidget::init()
	 */
	public function init(){
		$this->assetsPath = Yii::app()->getAssetManager()->publish(dirname(__FILE__).'/assets');
	}
	/**
	 * Run this widget.
	 * Produces required markup (mainly javascript) and register all required files
	 * @see CWidget::run()
	 */
	public function run(){
		$this->registerClientFiles();
	}
	/**
	 * Register javascript and css files required by the Guiders plugin.
	 * Also registers the js initialisation code under a self generated id.
	 */
	protected function registerClientFiles(){
		$cs=Yii::app()->getClientScript();
		if(! self::$registerDone ){
			$cs->registerCoreScript('jquery');
			$cs->registerScriptFile($this->assetsPath.'/guiders-1.2.8.js');
			$cs->registerCSSFile($this->assetsPath.'/guiders-1.2.8.css');
			self::$registerDone = true;
		}
				
		if( $this->cssFile != null && $cs->isCssRegistered($this->cssFile) == false){
			$cs->registerCSSFile($this->cssFile);
		}
		
		$cs->registerScript('Yii.EGuiders#'.$this->getId(),$this->getJS());
	}
	/**
	 * Creates the associative array that contains all Guiders plugin options.
	 * @return array Guiders init parameters
	 */
	protected function getClientOptions(){
		$options=array();
		foreach($this->supportedClientOptions as $property) {
			if( isset($this->$property)){
				$options[$property]=$this->$property;
			}
		}
		return $options;
	}
	/**
	 * Creates the Guiders initialisation javascript code
	 * @return String js code
	 */
	protected function getJS(){
		return 'guiders.createGuider('.CJavaScript::encode($this->getClientOptions()).')'.
		($this->show?'.show()':'') . ';';
	}
}
?>