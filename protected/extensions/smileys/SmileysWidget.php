<?php

/**
 * SmileysWidget
 * 
 * @author Twisted1919
 * @copyright 2011
 * @version 1.0
 * @access public
 */
class SmileysWidget extends CWidget{

    /**
    * @var $forcePublish - Set to true for first time when adding new smileys group.
    **/
    public $forcePublish=false;

    /**
    * @var $group - The group of smileys to be loaded.
    * In order for this to work correctly, follow the default group as an example.
    **/
    public $group='default';
    
    /**
    * @var $cssFile - default stylesheet used when showing the smileys.
    * You can use your own without worries.
    **/
    public $cssFile='smileys.css';
    
    /**
    * @var $scriptFile - default javascript used when showing the smileys.
    * If you need to extend the script functionality, don't forget to copy the original file.
    **/
    public $scriptFile='smileys.js';
    
    /**
    * @var $containerCssClass - the CSS class that wraps all the smileys
    **/
    public $containerCssClass='smileys';
    
    /**
    * @var $wrapperCssClass - the CSS class that wraps a single smiley
    **/
    public $wrapperCssClass='smiley';
    
    /**
    * @var $textareaId - the ID of the textarea where this smiley should be pushed into.
    **/
    public $textareaId='textarea';
    
    /**
    * @var $perRow - How many smileys should be shown in a row.
    * If this value is greather than 0, after $perRow smileys, a line break will be inserted.
    **/
    public $perRow=0;

    /**
     * SmileysWidget::run()
     * 
     * @return
     */
    public function run()
    {
        $this->render('smileys',array(
            'textareaId'=>$this->textareaId,
            'cssFile'=>$this->cssFile,
            'scriptFile'=>$this->scriptFile,
            'containerCssClass'=>$this->containerCssClass,
            'wrapperCssClass'=>$this->wrapperCssClass,
            'smileys'=>$this->loadSmileys(),
            'group'=>$this->group,
            'perRow'=>$this->perRow,
            'assetsUrl'=>$this->publishAssets(),
        ));
    }

    /**
     * SmileysWidget::loadSmileys()
     * 
     * @param string $group
     * @return
     */
    protected function loadSmileys($group='')
    {
        if($group=='')
            $group=$this->group;
        return include('groups/'.$group.'.php');
    }

    /**
     * SmileysWidget::publishAssets()
     * 
     * @param bool $loadAssets
     * @return
     */
    protected function publishAssets($loadAssets=true)
    {
        $publish=Yii::app()->assetManager->publish(Yii::getPathOfAlias('ext.smileys').'/assets/',false,-1,$this->forcePublish);
        if($loadAssets)
        {
            Yii::app()->clientScript->registerCoreScript('jquery');
            
            if($this->cssFile=='smileys.css')
                Yii::app()->clientScript->registerCssFile($publish.'/css/smileys.css');
            else
                Yii::app()->clientScript->registerCssFile($this->cssFile);
            
            if($this->scriptFile=='smileys.js')
                Yii::app()->clientScript->registerScriptFile($publish.'/js/smileys.js'); 
            else
                Yii::app()->clientScript->registerScriptFile($this->scriptFile);    
        }
        return $publish;
    }

    
    
}