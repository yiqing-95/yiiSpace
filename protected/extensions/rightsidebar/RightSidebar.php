<?php

/**
 * dynamic sidebar slider on the right side of the browser
 *
 * i stole all the code from the RightMenu in the Yii Playground ( http://www.yiiplayground.com )
 * only thing i have done is wrapping this code into a Yii Extension for generic use
 * 
 * @author Yii Playground team, Michael Klenner <Michael.Klenner@gmx.de>
 * @version 1.0
 * @license BSD
 */
class RightSidebar extends CWidget {

    /**
     * @var String title of the sidebar widget
     */
    public $title = 'RightSidebar';

    /**
     * @var boolean widget starts collapsed or expanded
     */
    public $collapsed = false;

    public function init() {
        //publish assets
        $assetFolder = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'assets';
        $publishedAssetsPath = Yii::app()->assetManager->publish($assetFolder);

        //register js and css files
        Yii::app()->clientScript->registerCssFile($publishedAssetsPath . '/css/rightsidebar.css');
        Yii::app()->clientScript->registerScriptFile($publishedAssetsPath . '/js/rightsidebar.js');

        //starting right menu collapsed or expanded
        $js = $this->collapsed ?
                'right_menu.setStartStatus(false);' :
                'right_menu.setStartStatus(true);';
        Yii::app()->clientScript->registerScript('rightMenu', $js, CClientScript::POS_LOAD);

        echo '<div class="right_menu">';
        echo '<div class="title">' . CHtml::encode($this->title);
        echo CHtml::ajaxLink('&gt;&gt;', '', false, array('id' => 'toggle_right_menu'));
        echo '</div>';

        parent::init();
    }

    public function run() {
        parent::run();

        echo '</div>';
    }
}
?>
