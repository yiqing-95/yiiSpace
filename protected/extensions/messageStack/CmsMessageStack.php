<?php
/**
 * CmsMessageStack
 * 
 * @package OneTwist CMS  
 * @author twisted1919 (Serban George Cristian)
 * @copyright OneTwist CMS (www.onetwist.com)
 * @version 1.0
 * @access public
 */
class CmsMessageStack extends CApplicationComponent{
    
    private $_messageStack=array();
    private $_toJson;
    private $_hasMessage=false;
    
    /**
     * CmsMessageStack::init()
     * 
     * @return
     */
    public function init()
    {
        parent::init();
    }
    
    /**
     * CmsMessageStack::set()
     * 
     * @param mixed $key
     * @param string $value
     * @return
     */
    public function set($key, $value='')
    {
        if(is_array($key))
            $this->_messageStack=array_merge($this->_messageStack, $key);
        else
            $this->_messageStack[$key]=$value;
        $this->_hasMessage=true;
        return $this;
    }
    
    /**
     * CmsMessageStack::get()
     * 
     * @param string $key
     * @return
     */
    public function get($key='')
    {
        if(!empty($key))
            return isset($this->_messageStack[$key]) ? $this->_messageStack[$key] : null;
        return $this->_messageStack;
    }
    
    /**
     * CmsMessageStack::toJson()
     * 
     * @param bool $return
     * @return
     */
    public function toJson($return=false)
    {
        $this->_toJson=CJSON::encode($this->_messageStack);
        
        if($return)
           return $this->_toJson;
        
        header('Cache-Control: no-cache, must-revalidate');
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
        header('Content-type: application/json');
        echo $this->_toJson; 
        Yii::app()->end();
    }
    
    /**
     * CmsMessageStack::toArray()
     * 
     * @return
     */
    public function toArray()
    {
        return (array)$this->_messageStack;
    }

    /**
     * CmsMessageStack::hasMessage()
     * 
     * @return
     */
    public function hasMessage()
    {
        return (bool)$this->_hasMessage;
    }
    
    /**
     * CmsMessageStack::reset()
     * 
     * @return
     */
    public function reset()
    {
        $this->_messageStack=array();
        $this->_hasMessage=false;
        return $this;
    }


}