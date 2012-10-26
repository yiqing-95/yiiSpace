<?php
/**
 * .
 * Example:
 *
 * <code>
 * $this->widget('application.extensions.superfish.RSuperfish', array(
 * 	'items'=> array('label' => 'menu item', 'url' => '#', 'items' => array(
 *     				array('label' => 'menu item', 'url' => '#'),
 *     				array('label' => 'menu item', 'url' => '#', 'items' => array(
 *       					array('label' => 'menu item', 'url' => '#')
 *       			),
 *       	  array('label' => 'menu item', 'url' => '#'),
 *       	  array('label' => 'menu item', 'url' => '#')
 * 					)));
 * 
 * </code>
 *
 * This extension includes jquery superfish plugin :
 *  http://users.tpg.com.au/j_birch/plugins/superfish/
 *
 * @author DzungLee <dunglqttmg@gmail.com>
 */

Yii::import('zii.widgets.CMenu');
class RSuperfish extends CMenu
{
  // enable vertical type
  public $vertical = false;
  // enable subpersubs
  /* 
   * $subpersubs = array(
   * 	'enable' => true,
   *    'options' => array(
   *    	'minWidth' => 12,  // minimum width of sub-menus in em units 
   *    	'maxWidth' => 27,  // maximum width of sub-menus in em units
   *    	'extraWidth' => 1  // extra width can ensure lines don't sometimes turn over
   *    					   // due to slight rounding differences and font-family
   *    )
   * ) 
  */
  public $supersubs = array(
    'enable' => false,
    'options' => array(
      'minWidth' => 12,
      'maxWidth' => 27,
      'extraWidth' => 1,
    )
  );
  // enable hoverIntent
  public $hoverIntent = true;
  // the class applied to hovered list items
  public $hoverClass = 'sfHover';
  // enable nav-bar style
  public $navbar = false;
  // the number of levels of submenus that remain open or are restored using pathClass
  public $pathLevels = 1;
  // the delay in milliseconds that the mouse can remain outside a submenu without it closing
  public $delay = 800;
  // an object equivalent to first parameter of jQuery’s .animate() method
  public $animation = array("opacity" => 'show');
  // speed of the animation. Equivalent to second parameter of jQuery’s .animate() method
  public $speed = 'normal';
  // if true, arrow mark-up generated automatically = cleaner source code at expense of initialisation performance
  public $autoArrows = true;
  // completely disable drop shadows by setting this to false
  public $dropShadows = true;
  // set to true to disable hoverIntent detection
  public $disableHI = false;

  function makeOptions(){
    $options = array();
    
    $options['hoverClass'] = $this->hoverClass;
    $options['pathClass'] = $this->activeCssClass;
    $options['pathLevels'] = $this->pathLevels;
    $options['delay'] = $this->delay;
    $options['animation'] = $this->animation;
    $options['speed'] = $this->speed;
    $options['autoArrows'] = $this->autoArrows;
    $options['dropShadows'] = $this->dropShadows;
    $options['disableHI'] = $this->disableHI;
    
    return CJavaScript::encode($options);
  }
  
  function run(){
    if($this->htmlOptions['class']) {
      $this->htmlOptions['class'] .= " sf-menu";
    } else {
      $this->htmlOptions['class'] = " sf-menu";
    }
    
    
    
    $cs = Yii::app()->getClientScript();
    $id = $this->getId();
    $assets = Yii::app()->getAssetManager()->publish(dirname(__FILE__).DIRECTORY_SEPARATOR.'assets');

    $cs->registerCssFile($assets.'/css/superfish.css');
    if($this->vertical){
      $cs->registerCssFile($assets.'/css/superfish-vertical.css');
      $this->htmlOptions['class'] .= " sf-vertical";
    }
    
    if($this->navbar){
      $cs->registerCssFile($assets.'/css/superfish-navbar.css');
      $this->htmlOptions['class'] .= " sf-navbar";
    }
    
    if($this->hoverIntent){
      $cs->registerScriptFile($assets.'/js/hoverIntent.js');
    }    
    
    $cs->registerScriptFile($assets.'/js/superfish.js');
    $options= $this->makeOptions();
    
    $js = "jQuery('#{$id}')";
    if($this->supersubs['enable']){
      $cs->registerScriptFile($assets.'/js/supersubs.js');
      //make subpersubs options
      $supersubsOptions = CJavaScript::encode($this->supersubs['options']);
      $js .= ".supersubs({$supersubsOptions})";
    }
    
    $js .= ".superfish({$options});";
    $cs->registerScript(__CLASS__.'#'.$id, $js);
    parent::run();
  }
}