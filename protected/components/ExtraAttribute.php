<?php
/**
 * Created by JetBrains PhpStorm.
 * User: yiqing
 * Date: 11-8-10
 * Time: 下午12:27
 * To change this template use File | Settings | File Templates.
 */


/**
 * @throws CException
 * 在不在复写  get和set魔术方法时 可以使得
 * 对象具有额外的属性 ，比如
 * $controller->anyAttr = 'hi';
 * 
 */
class ExtraAttribute extends CBehavior{
  private $_attributes;

 public function __get($name){
     try{
         return parent::__get($name);
     }catch(CException $e){
         if(isset($this->_attributes[$name])){
             return $this->_attributes[$name];
         }else{
             throw $e;
         }
     }
 }

    public function __set($name,$value){
        try{
           // echo __METHOD__;
            parent::_set($name,$value);
        }catch(CException $e){
            $this->_attributes[$name] = $value;
           // YiiUtil::dumpObject($this);
        }
    }

    public function canSetProperty($name){
        //echo __METHOD__;
        return true;
    }
    public function canGetProperty($name){
       // echo __METHOD__;
        if(parent::canGetProperty($name)){
            return true;
        }elseif(isset($this->_attributes[$name])){
            return true;
        }else{
           return false;
        }
    }

}
