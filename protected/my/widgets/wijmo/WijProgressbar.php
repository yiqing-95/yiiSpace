<?php
/**
 * Created by JetBrains PhpStorm.
 * User: yiqing
 * Date: 12-4-10
 * Time: 下午4:17
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
class WijProgressbar extends EWijmoWidget
{
    public function init(){
        parent::init();
        $this->registerCssFile('themes/Wijmo/jquery.wijmo.wijprogressbar.css');
        $this->registerScriptFile('wijmo/jquery.wijmo.wijutil.js,
       wijmo/jquery.wijmo.wijtooltip.js,
       wijmo/jquery.wijmo.wijprogressbar.js',CClientScript::POS_HEAD);
    }

    public function run(){

        parent::run();
    }
}