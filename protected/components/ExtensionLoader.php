<?php if ( ! defined('YII_PATH')) exit('No direct script access allowed');

class ExtensionLoader extends CApplicationComponent{

    public $autoLoadExtensions = false ;

    protected $_loaded=array();
    
    protected $_list=array();
    
    public function init()
    {
        parent::init();
        if($this->autoLoadExtensions == false) return ;

        $ext=$this->getExtensionInitClasses('ext');
        $list=array();

        foreach($ext AS $alias)
        {
            if(!is_string($alias)||strlen($alias)<1)
                continue;
            
            $class=Yii::createComponent(array('class'=>$alias));
            if($class instanceof ExtensionInit)
            {
                $priority=(int)$class->priority;
                
                while(isset($list[$priority]))
                    $priority++;
                    
                $list[$priority]=$class;
            }
        }

        $this->_list=$list;
        
        foreach($this->_list AS $class)
        {
            if(!$class->isInitialized)
                $class->init();
        }
    }
    
    public function load()
    {
        if(!Yii::app()->getController())
            return;

        foreach($this->_list AS $class)
            $class->run();  
    }
    
    public function getExtensionInitClasses($extPathAlias)
    {
        if(isset($this->_loaded[$extPathAlias]))
            return $this->_loaded[$extPathAlias];
        
        $ext=array();
        if($handle = opendir(Yii::getPathOfAlias($extPathAlias))) 
        {
            $stripOut=array('.','..','.htaccess','index.html');
            while(false !== ($entry = readdir($handle))) 
            {
                $dirPathAlias=$extPathAlias.'.'.$entry;
                $initClassName=ControllerHelper::toCamelCase($entry,false);

                if(!in_array($entry,$stripOut) && is_dir(Yii::getPathOfAlias($dirPathAlias)) && is_file(Yii::getPathOfAlias($dirPathAlias.'.'.$initClassName.'Init').'.php')) 
                {
                    $ext[]=$dirPathAlias.'.'.$initClassName.'Init';
                }
            }
            closedir($handle);
        }
        return $this->_loaded[$extPathAlias]=$ext;
    }
    
}