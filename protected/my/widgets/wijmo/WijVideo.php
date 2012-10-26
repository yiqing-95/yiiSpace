<?php
/**
 * Created by JetBrains PhpStorm.
 * User: yiqing
 * Date: 12-4-10
 * Time: 下午2:09
 *------------------------------------------------------------
 *                 _            _
 *                (_)          (_)
 *        _   __  __   .--. _  __   _ .--.   .--./)
 *       [ \ [  ][  |/ /'`\' ][  | [ `.-. | / /'`\;
 *        \ '/ /  | || \__/ |  | |  | | | | \ \._//
 *      [\_:  /  [___]\__.; | [___][___||__].',__`
 *       \__.'            |__]             ( ( __))
 *
 *--------------------------------------------------------------
 * To change this template use File | Settings | File Templates.
 */
class WijVideo extends EWijmoWidget
{
    public function init(){
        parent::init();
        $this->registerCssFile('themes/wijmo/jquery.wijmo.wijvideo.css');
        $this->registerScriptFile('explore/js/jquery.hoverIntent.minified.js,
             wijmo/jquery.wijmo.wijvideo.js,wijmo/jquery.wijmo.wijtooltip.js',CClientScript::POS_HEAD);
    }

    public function run(){
        parent::run();
    }
}
