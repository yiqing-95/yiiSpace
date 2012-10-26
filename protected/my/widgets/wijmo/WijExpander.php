<?php
/**
 * Created by JetBrains PhpStorm.
 * User: yiqing
 * Date: 12-4-10
 * Time: 下午1:12
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
class WijExpander extends EWijmoWidget
{
    public function init(){
        parent::init();
        $this->registerCssFile('themes/wijmo/jquery.wijmo.wijutil.css','themes/wijmo/jquery.wijmo.wijexpander.css');
        $this->registerScriptFile('wijmo/jquery.wijmo.wijutil.js,
             wijmo/jquery.wijmo.wijexpander.js',CClientScript::POS_HEAD);
    }

    public function run(){
        parent::run();
    }
}
