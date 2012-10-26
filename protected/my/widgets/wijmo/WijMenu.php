<?php
/**
 * Created by JetBrains PhpStorm.
 * User: yiqing
 * Date: 12-4-10
 * Time: 下午4:25
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
class WijMenu  extends EWijmoWidget
{
    public function init(){
        parent::init();
        $this->registerCssFile('themes/wijmo/jquery.wijmo.wijsuperpanel.css',
            'themes/wijmo/jquery.wijmo.wijmenu.css');
        $this->registerScriptFile('external/jquery.mousewheel.min.js,
             external/jquery.bgiframe-2.1.3-pre.js,
             wijmo/jquery.wijmo.wijutil.js,
             wijmo/jquery.wijmo.wijsuperpanel.js,
             wijmo/jquery.wijmo.wijmenu.js',CClientScript::POS_HEAD);
    }

    public function run(){
        parent::run();
    }
}
