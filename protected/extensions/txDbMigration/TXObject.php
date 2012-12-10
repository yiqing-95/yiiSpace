<?php

class TXObject {
    protected $_lastError = '';

    public function  __construct($init = array()) {
        unset($init['error']);
        foreach ($init as $key=>$value) $this->$key = $value;
    }

    public function getError() {
        return $this->_lastError;
    }

    protected function setError($error) {
        $this->_lastError = $error;
    }

    public function  __get($name) {
        $methodName = 'get'.ucfirst($name);
        return call_user_func(array($this,$methodName));
    }

    public function  __set($name,  $value) {
        $methodName = 'set'.ucfirst($name);
        return call_user_func(array($this,$methodName), $value);
    }
}