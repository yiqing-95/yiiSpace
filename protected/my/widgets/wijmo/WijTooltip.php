<?php
/**
 * Created by JetBrains PhpStorm.
 * User: yiqing
 * Date: 12-4-10
 * Time: 下午2:23
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
class WijTooltip extends EWijmoWidget
{
    public function init(){
        parent::init();
        $this->registerCssFile('themes/wijmo/jquery.wijmo.wijtooltip.css');
        $this->registerScriptFile('external/jquery.mousewheel.min.js,
             external/jquery.bgiframe-2.1.3-pre.js,
             wijmo/jquery.wijmo.wijutil.js,
             wijmo/jquery.wijmo.wijtooltip.js',CClientScript::POS_HEAD);
    }

    public function run(){
        parent::run();
    }
}
