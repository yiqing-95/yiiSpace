<?php
/**
 * @author Evandro Sviercowski <evandro.swk@gmail.com>
 * 
 * $this->widget('application.extensions.YiiTagCloud.YiiTagCloud', 
 *          array(
 *              'beginColor' => '00089A',
 *              'endColor' => 'A3AEFF',
 *              'minFontSize' => 8,
 *              'maxFontSize' => 20,
 *              'htmlOptions' => array('style'=>'width: 400px; margin-left: auto; margin-right: auto'),
 *              'arrTags' => array (
 *                      "MVC" 		=> array('weight'=> 2),
 *                      "PHP" 		=> array('weight'=> 9, 'url' => 'http://php.net'),
 *                      "MySQL" 	=> array('weight'=> 8, 'url' => 'http://mysql.com'),
 *                      "jQuery" 	=> array('weight'=> 6, 'url' => 'http://jquery.com'),
 *                      "SQL" 		=> array('weight'=> 9),
 *                      "C#" 		=> array('weight'=> 2),
 *              ),
 *          )
 * );
 * 
 */
class YiiTagCloud extends CWidget
{
    /**
     * @var string The YiiTagCloud container css class
     */
    public $containerClass = 'YiiTagCloud';
    /**
     * @var string The YiiTagCloud container html tag
     */
    public $containerTag = 'div';
    /**
     * @var array HtmlOptions of the YiiTagCloud container
     */
    public $htmlOptions = array();
    /**
     * @var array with the tags
     */
    public $arrTags = array();
    /**
     * @var string The begin color of the tags
     */
    public $beginColor = '000842';
    /**
     * @var string The end color of the tags
     */
    public $endColor = 'A3AEFF';
    /**
     * @var integer The smallest count (or occurrence).
     */
    public $minWeight = 1;
    /**
     * @var integer The largest count (or occurrence).
     */ 
    public $maxWeight = 1;
    /**
     * @var array the font-size colors
     */
    public $arrFontColor = array();    
    /**
     * @var integer The smallest font-size.
     */ 
    public $minFontSize = 8;
    /**
     * @var integer The largest font-size.
     */ 
    public $maxFontSize = 36;
    /**
     * @var string the URL of the CSS file
     */
    public $cssFile;

    public function init()
    {
        $this->htmlOptions['id']=$this->getId();

        if(!isset($this->htmlOptions['class']))
            $this->htmlOptions['class'] = $this->containerClass;

        $baseScriptUrl = Yii::app()->getAssetManager()->publish(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'assets' );

        if($this->cssFile !== false) {
            if($this->cssFile === null)
                $this->cssFile = $baseScriptUrl . '/YiiTagCloud.css';
            Yii::app()->getClientScript()->registerCssFile($this->cssFile);
        }

        $this->getMinAndMaxWeight();
        $this->getFontSizes();  
        $this->genereteColors();   


    }

    public function run() {

        $this->renderTagCloud();
        
    }

    public function renderTagCloud() {
        echo CHtml::openTag($this->containerTag, $this->htmlOptions);
        foreach ($this->arrTags as $tag => $conf) {
            $url = isset($conf['url']) ? $conf['url'] : 'javascript:return false';
            $htmlOptions = isset($conf['htmlOptions']) ? $conf['htmlOptions'] : array();

            if (!isset($htmlOptions['style']) || empty($htmlOptions['style'])) {
                $htmlOptions['style'] = 'font-size: ' .$conf['font-size'] . 'pt;' . 'color: ' . $this->arrFontColor[$conf['font-size']];
            }

            if (!isset($htmlOptions['target']) || empty($htmlOptions['target'])) {
                $htmlOptions['target'] = '_blank';
            }
            
            @$htmlOptions['class'] .= ' YiiTagCloudWord';

            
            echo ' &nbsp;' . CHtml::link($tag, $url, $htmlOptions) . '&nbsp; ';
            
        }
        echo CHtml::closeTag($this->containerTag);
    }

    public function getMinAndMaxWeight() {

        foreach ($this->arrTags as $conf) {        
            if ($this->minWeight > $conf['weight'])
                $this->minWeight = $conf['weight'];

            if ($this->maxWeight < $conf['weight'])
                $this->maxWeight = $conf['weight'];
        }
    }
    
    public function getFontSizes() {
        foreach ($this->arrTags as &$conf) {           
            $conf['font-size'] = $this->calcFontSize($conf['weight']);
            $this->arrFontColor[$conf['font-size']] = ''; 
        }
    }

    public function calcFontSize($weight) {
		return round(((($weight - $this->minWeight) * ($this->maxFontSize - $this->minFontSize)) / ($this->maxWeight - $this->minWeight)) + $this->minFontSize);
    }

    public function genereteColors () {
        krsort ($this->arrFontColor);
        $beginColor = hexdec($this->beginColor);
        $endColor = hexdec($this->endColor);

        $R0 = ($beginColor & 0xff0000) >> 16;
        $G0 = ($beginColor & 0x00ff00) >> 8;
        $B0 = ($beginColor & 0x0000ff) >> 0;

        $R1 = ($endColor & 0xff0000) >> 16;
        $G1 = ($endColor & 0x00ff00) >> 8;
        $B1 = ($endColor & 0x0000ff) >> 0;

        $numColors = $theNumSteps = count($this->arrFontColor);
        
        $i =0;
        foreach ($this->arrFontColor as &$value) {
            $R = $this->interpolate($R0, $R1, $i, $numColors);
            $G = $this->interpolate($G0, $G1, $i, $numColors);
            $B = $this->interpolate($B0, $B1, $i, $numColors);

            $value = sprintf("#%06X",(((($R << 8) | $G) << 8) | $B));

            $i++;
        }

    }

    public function interpolate($pBegin, $pEnd, $pStep, $pMax) {
        if ($pBegin < $pEnd) 
            return (($pEnd - $pBegin) * ($pStep / $pMax)) + $pBegin;

        return (($pBegin - $pEnd) * (1 - ($pStep / $pMax))) + $pEnd;
    }

}
