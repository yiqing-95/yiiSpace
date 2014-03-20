<?php
/**
 * Created by PhpStorm.
 * User: yiqing
 * Date: 13-12-10
 * Time: 下午5:07
 * To change this template use File | Settings | File Templates.
 */
Yii::import('alice.helpers.AliceHtml');

class AliceBox extends  CWidget{

    /**
     * @var string
     */
    public static $cssBaseName = 'ui-box';

    /**
     * @var mixed
     * Box title
     * If set to false, a box with no title is rendered
     */
    public $headTitle = '';

    /**
     * @var string
     */
    public $headText = '';

    /**
     * @var string
     */
    public $headMore = '';

    /**
     * @var string
     * Box Content
     * optional, the content of this attribute is echoed as the box content
     */
    public $content = '';

    /**
     * @var array
     * box HTML additional attributes
     */
    public $htmlOptions = array();

    /**
     * @var array
     * box header HTML additional attributes

    public $htmlHeaderOptions = array();
*/

    /**
     * @var array
     * box content HTML additional attributes

    public $htmlContentOptions = array();

*/
    /**
     * @var array
     */
    public $htmlContainerOptions = array();

    /**
     *### .init()
     *
     * Widget initialization
     */
    public function init()
    {

        AliceHtml::addCssClass(self::$cssBaseName,$this->htmlOptions);

        AliceHtml::addCssClass(self::$cssBaseName.'-container',$this->htmlContainerOptions);

        echo CHtml::openTag('div', $this->htmlOptions);

        $this->renderHeader();
        $this->renderContainerBegin();
    }

    /**
     *### .run()
     *
     * Widget run - used for closing procedures
     */
    public function run()
    {
        $this->renderContainerEnd();
        echo CHtml::closeTag('div') . "\n";
    }

    /**
     *### .renderHeader()
     *
     * Renders the header of the box with the header control (button to show/hide the box)
     */
    public function renderHeader()
    {
       $headHtml  = <<< HEAD
           <div class="ui-box-head">
               <h3 class="ui-box-head-title">{$this->headTitle}</h3>
               <span class="ui-box-head-text">{$this->headText}</span>
               <a href="#" class="ui-box-head-more">{$this->headMore}</a>
           </div>
HEAD;
       echo $headHtml ;
    }

    /*
     *### .renderContainerBegin()
     *
     * Renders the opening of the content element and the optional content
     */
    public function renderContainerBegin()
    {
        echo CHtml::openTag('div', $this->htmlContainerOptions);

        echo ' <div class="ui-box-content">';
        if (!empty($this->content)) {
            echo $this->content;
        }
    }

    /*
     *### .renderContainerEnd()
     *
     * one for outer  one for  inner
     *
     * Closes the content element
     */
    public function renderContainerEnd()
    {
        echo '</div>' , '</div>';
    }


} 