<?php
/**
 *
 * User: yiqing
 * Date: 13-3-14
 * Time: 下午7:22
 * To change this template use File | Settings | File Templates.
 * -------------------------------------------------------
 * -------------------------------------------------------
 */
class E52fr extends CWidget
{
    private $_baseUrl;
    private $_debug;



    public function init()
    {
        if($this->_debug===null)
            $this->_debug = YII_DEBUG;

        if($this->_baseUrl===null)
            $this->_baseUrl=Yii::app()->getAssetManager()->publish(dirname(__FILE__).'/assets');

        echo CHtml::scriptFile($this->_baseUrl.'/js/modernizr-1.7.min.js'),
        "<!--[if IE]><script type=\"text/javascript\" src=\"{$this->_baseUrl}/canvas/excanvas.js\"></script><![endif]-->";

        Yii::app()->getClientScript()->registerCoreScript('jquery');

        foreach($this->getCssFiles($this->_debug) as $file)
        {
            if(isset($file[2]))
                echo '<!--[if lt IE '.$file[2].']>'."\n";
            echo CHtml::cssFile($file[0],isset($file[1]) ? $file[1] : '')."\n";
            if(isset($file[2]))
                echo '<![endif]-->'."\n";
        }

    }
    public function getCssFiles($debug=false)
    {


        if($debug)
        {
            return array(
                array($this->_baseUrl.'/css/reset.css','screen'),
                array($this->_baseUrl.'/css/css3.css','screen'),
                array($this->_baseUrl.'/css/general.css','screen'),
                array($this->_baseUrl.'/css/grid.css','screen'),
                array($this->_baseUrl.'/css/forms.css','screen'),
            );
        }
        else
        {
            return array(
                array($this->_baseUrl.'/css/reset.css','screen'),
                array($this->_baseUrl.'/css/css3.css','screen'),
                array($this->_baseUrl.'/css/general.css','screen'),
                array($this->_baseUrl.'/css/grid.css','screen'),
                array($this->_baseUrl.'/css/forms.css','screen'),
            );
        }
    }


}