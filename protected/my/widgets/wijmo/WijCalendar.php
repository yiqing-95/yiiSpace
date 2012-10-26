<?php
/**
 * Created by JetBrains PhpStorm.
 * User: yiqing
 * Date: 12-4-10
 * Time: 下午1:04
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
class WijCalendar extends EWijmoWidget
{

    public function init(){
        parent::init();
        $this->registerCssFile('themes/wijmo/jquery.wijmo.wijcalendar.css');
        $this->registerScriptFile('external/globalize.min.js,
             wijmo/jquery.wijmo.wijpopup.js,
             wijmo/jquery.wijmo.wijcalendar.js',CClientScript::POS_HEAD);
    }
    public function run(){
      parent::run();
    }
}
