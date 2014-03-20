<?php
/**
 * Created by JetBrains PhpStorm.
 * User: yiqing
 * Date: 12-1-27
 * Time: 下午9:13
 * To change this template use File | Settings | File Templates.
 * --------------------------------------------------------------------------
 * 提高：
 * 1.扩展ArrayObject 可以$arr['attr']  或者 $obj->attr 访问
 * 2. 用 & 引用 防止复制变量
 * 3. 兼具Factory 模式的实现
 * 4. 可以用object做键 spl_object_hash()
 * -------------------------------------------------------------------
 *  扩展CMap也是可取之路
 * --------------------------------------------------------------------------
 * 相似思路 可参考官方扩展中的CmsMessageStack（位于ext.messageStack.CmsMessageStack）
 * 各层 数据传递可以使用该类
 */
class Registry /*extends ArrayObject*/
{
    /**
     * @var Registry
     */
    private static  $_instance;

    protected function __construct(){
        //parent::__construt(array(),ArrayObject::...);
    }

    protected function __clone(){
        parent::__clone();
    }

    /**
     * @var array
     *
     */
    private  $_store = array();

    /**
     * @static
     * @return Registry
     */
    public static function instance(){
       if(isset(self::$_instance)){
           return self::$_instance;
       }else{
           return self::$_instance =  new self();
       }
    }

    /**
     * @param $key
     * @param $value
     */
    public function set($key, $value){

        $this->_store[$key] =   $value;
    }

    /**
     * @param $key
     * @param null $default
     * @return null
     */
    public function get($key,$default = null){
      if(isset($this->_store[$key])){
          return $this->_store[$key];
      }else{
          return $default;

      }
    }

    /**
     * @param $key
     * @return bool
     */
    function isValid($key) {
        return  isset($this->_store[$key]) || array_key_exists($key,$this->_store) ;
    }
}
