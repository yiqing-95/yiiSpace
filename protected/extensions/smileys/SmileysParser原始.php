<?php
Yii::import('application.extensions.smileys.SmileysWidget');

/**
 * SmileysParser
 * 
 * @author Twisted1919
 * @copyright 2011
 * @version 1.0
 * @access public
 */
 
 /**
 * Note that, when parsing the smileys, we use str_replace because 
 * is way faster than preg_replace in this case, so the order of the smiley codes matters.
 * Remeber that something like ":)" will be replaced before something like ":))" 
 * and we will end up with an extra ")" so we need to put the ":))" code before ":)" in array.
 */
 
class SmileysParser extends SmileysWidget{

    private static $_instance;
    
    /**
     * SmileysParser::instance()
     * 
     * @return
     */
    public static function instance()
    {
        if(self::$_instance===null)
            self::$_instance=new SmileysParser;
        return self::$_instance;
    }

    /**
     * SmileysParser::parse()
     * 
     * @param mixed $textToParse
     * @param string $group
     * @return
     */
    public static function parse($textToParse, $group='default')
    {
        $smileys=self::instance()->loadSmileys($group);
        $assetsUrl=self::instance()->publishAssets(false);
        $search=$replace=array();
        foreach($smileys AS $smiley=>$smileyDetail)
        {
            $image=$assetsUrl.'/groups/'.$group.'/';
            $image.=!empty($smileyDetail[0])?$smileyDetail[0]:'';
            $width=!empty($smileyDetail[1])?(int)$smileyDetail[1]:'';
            $height=!empty($smileyDetail[2])?(int)$smileyDetail[2]:'';  
            $title=$alt=!empty($smileyDetail[3])?CHtml::encode($smileyDetail[3]):'';
            $search[]=$smiley;
            $replace[]='<img src="'.$image.'" width="'.$width.'" height="'.$height.'" alt="'.$alt.'" title="'.$title.'"/>';
        }
        $textToParse=html_entity_decode($textToParse);
        return str_replace($search, $replace, $textToParse);
    }

}