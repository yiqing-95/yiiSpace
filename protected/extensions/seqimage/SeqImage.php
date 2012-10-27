<?php

/**
 * SeqImage slide images.
 *
 * @author Fernando Rosa <nando.megaman@gmail.com>   
 */

/*
 * $this->widget('application.ext.extensions.seqimage.SeqImage',array(
 * 'widthImage' => 650, 
 * 'heightImage' => 340,
 * 'slides'=>array(  
 *      array(
 *          'image'=>array('src'=>Yii::app()->request->baseUrl.'/images/testimg.jpg'),            
 *          'link'=>array('url'=>'mypage','htmlOptions'=>array())
 *      ),
 *      array(
 *          'image'=>array('src'=>Yii::app()->request->baseUrl.'/images/anyimage.jpg'),            
 *      ),
 *      ...
 * ))); 
 */

class SeqImage extends CInputWidget {

    public $slides = array();
    public $widthImage = 910;
    public $heightImage = 230;
    public $timeDelay = 5000;
    public $timeDelayInitial = 5000;
    public $random = false;    
    public $buttonsInside = false;
    public $buttonsInsidePositionLeft = 0; 
    public $buttonsInsidePositionRight = 15; 
    public $buttonsInsidePositionBottom = 15;
    public $buttonsInsidePositionTop = 0;
    public $buttonsOutPosition = 'right'; //left or right
    
    public function init() {
        if(empty($this->slides))
            throw new CException('property "$slides" was not set.');
        if($this->random===true)
            shuffle($this->slides);
        $dir = dirname(__FILE__);
        $dirUrl = Yii::app()->getAssetManager()->publish($dir);
        $clientScript = Yii::app()->getClientScript();        
        $clientScript->registerCssFile($dirUrl.'/assets/seqimage.css');
        $clientScript->registerCoreScript('jquery');
        $clientScript->registerScript(__CLASS__.'slides',$this->registerScript());        
    }

    public function run() {
        $this->renderContent();
    }
    
    protected function renderContent(){
        $listButtons = null;    
        $listSlides = null;    
        foreach ($this->slides as $slideID => $slide):
            $link = false;
            $image = CHtml::image(
            (isset($slide['image']['src'])) ? $slide['image']['src'] : 'noimage', 
            (isset($slide['image']['alt'])) ? $slide['image']['alt'] : 'noimage', 
            (isset($slide['image']['htmlOptions'])) ? $slide['image']['htmlOptions'] : array('width'=>$this->widthImage,'height'=>$this->heightImage));                                 
            if(isset($slide['link'])):
                $link = CHtml::link($image, 
                (isset($slide['link']['url'])) ? $slide['link']['url'] : '#', 
                (isset($slide['link']['htmlOptions'])) ? $slide['link']['htmlOptions'] : array());
            endif;
            $listButtons .= CHtml::tag('li', array('id'=>'button'.$slideID,
            'class'=> ($slideID==0) ? 'active' : ''), $slideID);
            $listSlides  .= CHtml::tag('li', array('id'=>'image'.$slideID,'class'=> 'slide',
            'style'=>($slideID==0) ? 'visibility:visible; ' : ''), 
            ($link===false) ? $image : $link
            );            
        endforeach;                        
        echo CHtml::tag('div', array('class'=>'nbanner','style'=>'width:'.$this->widthImage.'px;'),              
             CHtml::tag('ul', array('class'=>'slides','style'=>'height:'.$this->heightImage.'px;'), $listSlides).
             CHtml::tag('ul', $this->getHtmlOptionsButtons(), $listButtons).
             CHtml::tag('div', array('class'=>'cclear'), ''));        
        
    }
    
    protected function getHtmlOptionsButtons(){
        $htmlOptions = array('class'=>'buttons','style'=>'');
        if($this->buttonsInside===true):            
            $htmlOptions['style'] .= 'position: absolute; background: #FFFFFF; padding: 7px 10px 6px 10px; ';
            if($this->buttonsInsidePositionLeft != 0)
                $htmlOptions['style'] .= 'left: '.$this->buttonsInsidePositionLeft.'px; ';
            else
                $htmlOptions['style'] .= 'right: '.$this->buttonsInsidePositionRight.'px; ';
            if($this->buttonsInsidePositionTop != 0)
                $htmlOptions['style'] .= 'top: '.$this->buttonsInsidePositionTop.'px; ';
            else
                $htmlOptions['style'] .= 'bottom: '.$this->buttonsInsidePositionBottom.'px; ';            
        else:
            $htmlOptions['style'] .= 'margin: 10px 0 0 0; float: '.$this->buttonsOutPosition.'; ';
        endif;
        return $htmlOptions;
    }
  
    protected function registerScript(){
        $scriptButtonFunctionClick = '';
        $scriptButtonBindEventClick = '';
        foreach ($this->slides as $slideID=>$image):
            $scriptButtonFunctionClick .= $this->getScriptButtonFunctionClick($slideID).' ';
            $scriptButtonBindEventClick .= $this->getScriptButtonBindEventClick($slideID).' ';
        endforeach;
        return  'var timer;
                function OnLoad(event){ 
                    clearTimeout(timer); 
                    timer = setTimeout(eval("button1_click"),"'.$this->timeDelayInitial.'");  
                }                
                '.$scriptButtonFunctionClick.'  '.$scriptButtonBindEventClick.'
                OnLoad(); ';
    }

    protected function getScriptButtonFunctionClick($buttonID){
        $nextSlide = (count($this->slides) == $buttonID+1) ? 0 : $buttonID + 1;
        return  'function button'.$buttonID.'_click(event){
                $(".slide").css("visibility","hidden");
                $("#image'.$buttonID.'").css("visibility","visible");
                $("#image'.$buttonID.'").css("opacity","0");
                $("#image'.$buttonID.'").animate({"opacity":1},300, "linear", null);
                $("ul.buttons li").removeClass("active");
                $("#image'.$buttonID.'").animate({"opacity":1},300, "linear", null);
                $("#button'.$buttonID.'").addClass("active");
                clearTimeout(timer);
                timer = setTimeout(eval("button'.$nextSlide.'_click"),"'.$this->timeDelay.'");
                $("#image'.$buttonID.'").animate({"opacity":1},300, "linear", null); }';
    }
    
    protected function getScriptButtonBindEventClick($buttonID){
        return '$("#button'.$buttonID.'").bind("click", button'.$buttonID.'_click);';
    }

}

?>