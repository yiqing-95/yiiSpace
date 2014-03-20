<?php

/**
 * @author dufei22 <dufei22@gmail.com>
 * @link http://blog.soyoto.com/
 */

/**
 * @version 1.0
 * @author yiqing95 <yiqing_95@qq.com>
 *
 * this widget is same to CJuiAccordion
 */
Yii::import('ext.dwz.DwzWidget');

/**
 * To use this widget, you may insert the following code in a view:
 * <pre>
 * $this->widget('ext.dwz.DwzAccordion',array(
 *     'panels'=>array(
 *         'panel 1'=>'content for panel 1',
 *         'panel 2'=>'content for panel 2',
 *         // panel 3 contains the content rendered by a partial view
 *         'panel 3'=>$this->renderPartial('_partial',null,true),
 *     ),
 *     // additional javascript options for the accordion plugin
 *     'options'=>array(
 *         'animated'=>'bounceslide',
 *     ),
 * ));
 * </pre>
 *
 */
class DwzAccordion extends DwzWidget
{

    /**
     * @var array list of panels (panel title=>panel content).
     * Note that neither panel title nor panel content will be HTML-encoded.
     */
    public $panels=array();
    /**
     * @var string the name of the container element that contains all panels. Defaults to 'div'.
     */
    public $tagName='div';
    /**
     * @var string the template that is used to generated every panel header.
     * The token "{title}" in the template will be replaced with the panel title.
     * Note that if you make change to this template, you may also need to adjust
     * the 'header' setting in {@link options}.
     */
    public $headerTemplate='<div class="accordionHeader"  ><h2><span>icon</span>{title}</h2></div>';
    /**
     * @var string the template that is used to generated every panel content.
     * The token "{content}" in the template will be replaced with the panel content.
     */
    public $contentTemplate='<div class="accordionContent" {contentAttributes} >{content}</div>';

    /**
     * Run this widget.
     * This method registers necessary javascript and renders the needed HTML code.
     */
    public function run()
    {
        $id=$this->getId();
        if(!isset($this->htmlOptions['id'])){
            $this->htmlOptions['id']=$id;
        }
        if (isset($this->htmlOptions['class']))
            $this->htmlOptions['class'] = 'accordion ' . $this->htmlOptions['class'];
        else
            $this->htmlOptions['class'] = 'accordion';



        echo CHtml::openTag($this->tagName,$this->htmlOptions)."\n";
        foreach($this->panels as $title=>$content)
        {
            echo strtr($this->headerTemplate,array('{title}'=>$title))."\n";

            if(is_array($content) && isset($content['htmlOptions'])){
                echo strtr($this->contentTemplate,array('{content}'=>$content[0],'{contentAttributes}'=>CHtml::renderAttributes($content['htmlOptions'])))."\n";
            }elseif(is_string($content)){
                echo strtr($this->contentTemplate,array('{content}'=>$content,'{contentAttributes}'=>''))."\n";
            }else{
                throw new CException('panel content must be string type or array : title=>array(content,htmlOptions[]) ');
            }

        }
        echo CHtml::closeTag($this->tagName);

    }
}