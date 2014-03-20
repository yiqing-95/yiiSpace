<?php
/**
 * Created by PhpStorm.
 * User: yiqing
 * Date: 14-2-9
 * Time: 上午12:38
 */

class YsFlashMessages extends YsWidget{

    const SUCCESS_MESSAGE = 'success';
    const INFO_MESSAGE = 'info';
    const WARNING_MESSAGE = 'warning';
    const ERROR_MESSAGE = 'error';

    public $options = array();

    public $view = 'flashMessages';

    public function run()
    {
        throw new CException('not implemented yet !');
        $this->render($this->view, array('options' => $this->options));
    }
}
