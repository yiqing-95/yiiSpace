<?php

/**
 * @property boolean $exists
 */
class TXFile extends TXFsObject {
    const WRITE = 1;
    const READ = 2;
    const APPEND = 4;

    protected $_handle;
    protected $_isOpen = false;

    public static function createTempFile($prefix = '') {
        if (!is_string($prefix))
            $prefix = '';
        $dir = sys_get_temp_dir();
        $tmp = tempnam($dir, $prefix);
        if ($tmp) {
            return new self(array(
                'path' => $tmp,
            ));
        } else {
            return false;
        }
    }

    public static function getExtensionFromPath($path) {
        return preg_replace('/^.*\.([^.]+)$/', '$1', $path);
    }

    public function __construct($init = array()) {
        parent::__construct($init);
    }

    public function __destruct() {
        $this->close();
    }

    public function open($mode = 0) {
        if (!$this->getExists())
            return false;
        if ($this->getIsOpen())
            $this->close();
        $modeStr = '';
        switch ($mode) {
            case 1:
                $modeStr = 'w';
                break;

            case 2:
                $modeStr = 'r';
                break;

            case 3:
                $modeStr = 'w+';
                break;

            case 4:
            case 5:
                $modeStr = 'a';
                break;

            case 6:
            case 7:
                $modeStr = 'a+';
                break;
        }

        if (empty($modeStr))
            return false;

        $this->_handle = fopen($this->_path, $modeStr);

        return $this->_handle ? ($this->setIsOpen(true)) : ($this->setIsOpen(false));
    }

    public function close() {
        if ($this->getIsOpen()) {
            if (fclose($this->_handle)) {
                $this->setIsOpen(false);
                $this->_handle = null;
                return true;
            }
            return false;
        }
        return true;
    }

    public function copy($path) {
        if (!$this->getExists())
            return false;
        return copy($this->_path, $path) ? new self(array(
                    'path' => $path,
                )) : false;
    }

    public function move($path) {
        if (!$this->getExists())
            return false;
        if (rename($this->_path, $path)) {
            $this->_path = $path;
            return true;
        } else {
            return false;
        }
    }

    public function moveUploaded($path) {
        if (!$this->getExists())
            return false;
        if (move_uploaded_file($this->_path, $path)) {
            $this->_path = $path;
            return true;
        } else {
            return false;
        }
    }

    public function rename($path) {
        return $this->move($path);
    }

    public function flush() {
        if ($this->getIsOpen()) {
            return fflush($this->_handle);
        }
        return true;
    }

    public function hardLink($path) {
        if (!$this->getExists())
            return false;
        return link($this->_path, $path);
    }

    public function link($path) {
        if (!$this->getExists())
            return false;
        return symlink($this->_path, $path);
    }

    public function remove() {
        if (!$this->getExists())
            return true;
        return @unlink($this->_path);
    }

    public function delete() {
        return $this->remove();
    }

    public function unlink() {
        return $this->remove();
    }

    public function read($length) {
        if ($this->getIsOpen()) {
            return fread($this->_handle, $length);
        }
        return false;
    }

    public function write($string, $length = null) {
        if ($this->getIsOpen()) {
            return fwrite($this->_handle, $string, $length);
        }
        return false;
    }

    public function seek($offset, $whence = null) {
        if ($this->getIsOpen()) {
            return fseek($this->_handle, $offset, $whence);
        }
        return false;
    }

    public function rewind() {
        if (!$this->getExists())
            return false;
        return rewind($this->_handle);
    }

    public function endOfFile() {
        if ($this->getIsOpen()) {
            return feof($this->_handle);
        }
        return true;
    }

    public function readLine($length = null) {
        if ($this->getIsOpen()) {
            if (empty($length)) {
                return fgets($this->_handle);
            } else {
                return fgets($this->_handle, $length);
            }
        }
        return false;
    }

    public function readAll() {
        if (!$this->getExists())
            return false;
        return file_get_contents($this->_path);
    }

    public function writeAll($data, $flags = null) {
        if (empty($flags)) {
            return file_put_contents($this->_path, $data);
        } else {
            return file_put_contents($this->_path, $data, $flags);
        }
    }

    public function accessTime() {
        if (!$this->getExists())
            return false;
        return fileatime($this->_path);
    }

    public function inodeChangeTime() {
        if (!$this->getExists())
            return false;
        return filectime($this->_path);
    }

    public function modificationTime() {
        if (!$this->getExists())
            return false;
        return filemtime($this->_path);
    }

    public function chown($user) {
        if (!$this->getExists())
            return false;
        return chown($this->_path, $user);
    }

    public function chgrp($group) {
        if (!$this->getExists())
            return false;
        return chgrp($this->_path, $group);
    }

    public function lock($operation) {
        if ($this->getIsOpen()) {
            return flock($this->_handle, $operation);
        }
        return false;
    }

    public function unlock() {
        if ($this->getIsOpen()) {
            return flock($this->_handle, LOCK_UN);
        }
        return false;
    }

    public function tell() {
        if ($this->getIsOpen()) {
            return ftell($this->_handle);
        }
        return false;
    }

    public function truncate($size) {
        if ($this->getIsOpen()) {
            return ftruncate($this->_handle, $size);
        }
        return false;
    }

    public function import($require = true, $once = false) {
        if (!$this->getExists())
            return;
        if ($require && $once) {
            return require_once $this->_path;
        } else if (!$require && $once) {
            return include_once $this->_path;
        } else if ($require && !$once) {
            return require $this->_path;
        } else {
            return include $this->_path;
        }
    }

    public function getHandle() {
        return $this->_handle;
    }

    public function getGroup() {
        if (!$this->getExists())
            return false;
        return filegroup($this->_path);
    }

    public function getOwner() {
        if (!$this->getExists())
            return false;
        return fileowner($this->_path);
    }

    public function getInode() {
        if (!$this->getExists())
            return false;
        return fileinode($this->_path);
    }

    public function getPermissions() {
        if (!$this->getExists())
            return false;
        return fileperms($this->_path);
    }

    public function getSize() {
        if (!$this->getExists())
            return false;
        return filesize($this->_path);
    }

    public function getType() {
        if (!$this->getExists())
            return false;
        return filetype($this->_path);
    }

    public function getStat() {
        if (!$this->getExists())
            return false;
        return stat($this->_path);
    }

    public function getIsUploadedFile() {
        if (!$this->getExists())
            return false;
        return is_uploaded_file($this->_path);
    }

    public function getPathInfo($options = null) {
        if (!$this->getExists())
            return false;
        if ($options === null) {
            return pathinfo($this->_path);
        } else {
            return pathinfo($this->_path, $options);
        }
    }

    public function getDir() {
        return new TXDir(array(
            'path' => dirname($this->_path),
        ));
    }

    public function getExists() {
        return $this->getIsValidPath() ? (file_exists($this->_path) && is_file($this->_path)) : false;
    }

    public function getIsOpen() {
        return $this->_isOpen;
    }

    public function setIsOpen($isOpen) {
        return ($this->_isOpen = (bool) $isOpen);
    }

    public function getExtension() {
        return self::getExtensionFromPath($this->_path);
    }

    public function getMd5() {
        if (!$this->getExists())
            return null;
        return md5_file($this->getAbsolutePath());
    }

    public function getFileName($withExtension = true) {
        if (empty($this->_path))
            return;
        
        $name = str_replace('\\', '/', $this->_path);
        $name = explode('/', $name);
        $name = array_pop($name);

        if (!$withExtension) {
            $extension = self::getExtensionFromPath($name);
            $_name = substr($name, 0, strlen($name) - strlen($extension) - 1);
            return empty($_name) ? $name : $_name;
        }

        return $name;
    }

    public function getYiiUrl() {
        return $this->getUrl(TXDir::getYiiBasePath(), Yii::app()->baseUrl);
    }

    public function forceDownload($fileName) {
        if (!headers_sent()) {
            header("Content-Type: application/force-download");
            header("Content-Disposition: attachment; filename=" . $fileName);
            header("Content-Length: " . $this->getSize());

            $this->open(TXFile::READ);
            while (!$this->endOfFile()) {
                echo $this->read(1024);
            }
            $this->close();
            
            die();
        }
    }

}
