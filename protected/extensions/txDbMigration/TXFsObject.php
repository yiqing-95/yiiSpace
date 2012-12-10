<?php

class TXFsObject extends TXObject {

    protected $_path;

    public function __construct($init = array()) {
        parent::__construct($init);
    }

    public static function isDir($path) {
        return (file_exists($path) && is_dir($path));
    }

    public static function isFile($path) {
        return (file_exists($path) && is_file($path));
    }

    protected function fileName($path) {
        if (empty($path))
            return;
        /*$name = str_replace('\\', '/', $path);
        $name = explode('/', $name);
        return array_pop($name);*/
        return basename($path);
    }

    public function getFileName() {
        return $this->fileName($this->_path);
    }

    public function getAbsolutePath() {
        return $this->getIsValidPath() ? realpath($this->_path) : null;
    }

    public function getExists() {
        return $this->getIsValidPath() ? file_exists($this->_path) : false;
    }

    public function getIsReadable() {
        return $this->getIsValidPath() ? is_readable($this->_path) : false;
    }

    public function getIsWritable() {
        return $this->getIsValidPath() ? is_writable($this->_path) : false;
    }

    public function getIsExecutable() {
        return $this->getIsValidPath() ? is_executable($this->_path) : false;
    }

    protected function getIsValidPath() {
        return $this->isValidPath($this->_path);
    }

    protected function isValidPath($path) {
        return (!empty($this->_path) && is_string($this->_path));
    }

    public function setPath($path) {
        $this->_path = $path;
    }

    public function getPath() {
        return $this->_path;
    }

    public function getRealPath() {
        return realpath($this->_path);
    }

    public function getUrl($basePath, $baseUrl) {
        $basePath = realpath($basePath);
        if (empty($basePath))
            return;
        $path = $this->getAbsolutePath();
        if (substr($path, 0, strlen($basePath)) == $basePath)
            $path = substr($path, strlen($basePath));
        $path = str_replace('\\', '/', $path);
        $url = $baseUrl . $path;
        return $url;
    }

    public function chmod($mode) {
        if (!$this->getExists())
            return false;
        return chmod($this->_path, $mode);
    }

}