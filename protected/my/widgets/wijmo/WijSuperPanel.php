<?php
/**
 * Created by JetBrains PhpStorm.
 * User: yiqing
 * Date: 12-4-10
 * Time: 下午3:38
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
class WijSuperPanel extends EWijmoWidget
{
    public function init(){
        parent::init();
        $this->registerCssFile('themes/wijmo/jquery.wijmo.wijsuperpanel.css');
        $this->registerScriptFile('external/jquery.mousewheel.min.js,
                wijmo/jquery.wijmo.wijsuperpanel.js',CClientScript::POS_HEAD);
    }

    public function run(){
        parent::run();
    }
}
