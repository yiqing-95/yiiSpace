<?php
/**
 * Created by JetBrains PhpStorm.
 * User: yiqing
 * Date: 13-1-9
 * Time: 上午1:11
 * To change this template use File | Settings | File Templates.
 */
/**
 * Insert widget in your layout, after head-tag.
 * <pre>
 * $this->widget('my.widgets.reuze.EReuze');
 * </pre>
 *
 */
class EReuze  extends CWidget
{
    // Url to vendors css-files.
    private $_baseUrl;
    private $_debug;


    public function init()
    {
        if($this->_debug===null)
            $this->_debug = YII_DEBUG;

        foreach($this->getCssFiles($this->_debug) as $file)
        {
            if(isset($file[2]))
                echo '<!--[if lt IE '.$file[2].']>'."\n";
            echo CHtml::cssFile($file[0],isset($file[1]) ? $file[1] : '')."\n";
            if(isset($file[2]))
                echo '<![endif]-->'."\n";
        }
    }

    /**
     * @param bool $debug
     * @return array
     */
    public function getCssFiles($debug=false)
    {
        if($this->_baseUrl===null)
            $this->_baseUrl=Yii::app()->getAssetManager()->publish(dirname(__FILE__).'/vendors/reuze');

        if($debug) {
            return array(
                array($this->_baseUrl.'/reuze.css','screen, print'),
                array($this->_baseUrl.'/ie8.css','screen','8'),
            );
        }  else       {
            // better to compressed it
            return array(
                array($this->_baseUrl.'/reuze.css','screen, print'),
                array($this->_baseUrl.'/ie8.css','screen','8'),
            );
        }
    }


}