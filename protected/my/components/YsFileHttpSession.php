<?php
/**
 * Created by JetBrains PhpStorm.
 * User: yiqing
 * Date: 12-11-3
 * Time: 下午7:08
 * To change this template use File | Settings | File Templates.
 */
class YsFileHttpSession extends CHttpSession
{

    /**
     * @var string
     */
    public $savePath ;

    /**
     * @return bool
     */
    public function getUseCustomStorage(){
        return true;
    }

    /**
     * @param string $savePath
     * @param string $sessionName
     * @return bool
     */
    public function openSession($savePath,$sessionName){
      if(empty($this->savePath)){
          $this->savePath = $savePath ;
      }
        if (!is_dir($this->savePath)) {
            mkdir($this->savePath, 0777);
        }
        return true;
    }

    /**
     * @return bool
     */
    public function closeSession(){
        return true;
    }

    /**
     * @param string $id
     * @return string
     */
    public function readSession($id){
        return (string)@file_get_contents("$this->savePath/sess_$id");
    }

    /**
     * @param string $id
     * @param string $data
     * @return bool
     */
    public function writeSession($id,$data){
        return file_put_contents("$this->savePath/sess_$id", $data) === false ? false : true;
    }

    /**
     * @param string $id
     * @return bool
     */
    public function destroySession($id){
        $file = "$this->savePath/sess_$id";
        if (file_exists($file)) {
            unlink($file);
        }
        return true;
    }

    /**
     * @param int $maxLifetime
     * @return bool
     */
    public function gcSession($maxLifetime)
    {
        foreach (glob("$this->savePath/sess_*") as $file) {
            if (filemtime($file) + $maxLifetime < time() && file_exists($file)) {
                unlink($file);
            }
        }
        return true;
    }
}
