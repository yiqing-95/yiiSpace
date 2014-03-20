<?php
/**
 * Created by JetBrains PhpStorm.
 * User: yiqing
 * Date: 12-8-24
 * Time: 下午12:25
 * To change this template use File | Settings | File Templates.
 */
class BaseModuleInstaller extends CComponent implements IInstaller
{
    protected $config;

    /**
     * @param array $config
     */
    public function __construct($config = array())
    {
        $this->config = $config;
    }


    public function install()
    {
        // TODO: Implement install() method.
    }

    public function uninstall()
    {
        // TODO: Implement uninstall() method.
    }

    protected function getBasePath()
    {
        $className = get_class($this);
        $class = new ReflectionClass($className);
        return dirname($class->getFileName());
    }

    /**
     * @static
     * @return array
     */
    static public function getInstallableModules()
    {
        $modulePath = Yii::app()->getModulePath();

        $di = new DirectoryIterator($modulePath);
        $modulesHasInstaller = array();
        foreach ($di as $file) {
            if ($file->isDir() && !$file->isDot()) {
                //echo $file->getFileName();
              //  $moduleDirName = $file->getFileName();
               // echo '<br/>', $file->getPathName();
                if(self::hasInstaller($file->getPathName())){
                   $modulesHasInstaller[] = $file->getFileName();
                }
            }
        }
        return $modulesHasInstaller;
    }

    /**
     * @static
     * @param string $modulePath
     * @return bool
     */
    public static function hasInstaller($modulePath = '')
    {
        $installDir = $modulePath . DIRECTORY_SEPARATOR . 'install';
        if (is_dir($installDir)) {
            $full_names = glob($installDir . DIRECTORY_SEPARATOR . '*Installer.php');
            $full_name = current($full_names); if(empty($full_name)) return false;
            $class_name = pathinfo($full_name, PATHINFO_FILENAME);
           // echo $class_name , __METHOD__;
            try {
                if (!class_exists($class_name, false)){
                    require($full_name);
                }
                if (class_exists($class_name, false) && is_subclass_of($class_name, 'IInstaller')) {
                    return true;
                } else {
                    return false;
                }
            } catch (CException $e) {
                //do  nothing  here！ 什么也不做这里！
                return false;
            }
        } else {
            return false;
        }
    }


    /**
     * @static
     * @param string $moduleId
     * @return BaseModuleInstaller
     */
    public static function getInstaller($moduleId=''){

        $modulePath = Yii::app()->getModulePath() . DIRECTORY_SEPARATOR .$moduleId;
        $installerDir = $modulePath . DIRECTORY_SEPARATOR .'install';
        if(!is_dir($installerDir)){
            return false ;
        }
        $full_names = glob($installerDir . DIRECTORY_SEPARATOR . '*Installer.php');
        $full_name = current($full_names); if(empty($full_name)) return false;
        $class_name = pathinfo($full_name, PATHINFO_FILENAME);
       // echo __METHOD__;
        try {
           //  echo __METHOD__ , $class_name;
            //need transaction support ！
            if (!class_exists($class_name, false)){
              // echo __METHOD__ , $full_name;
                require($full_name);
               // echo __METHOD__ , $full_name;
            }
           // echo __METHOD__;

            if (class_exists($class_name, false) && is_subclass_of($class_name, 'IInstaller')) {
                $installer = new $class_name() ;

                return $installer;
            } else {
                return false;
            }
        } catch (CException $e) {
            //do  nothing  here！ 什么也不做这里！
            if(YII_DEBUG ) throw $e ;

            return false;
        }

    }

    /**
     * @static
     * @param $moduleId
     * @return bool
     */
    static  public function installModule($moduleId){
        $moduleInstaller = self::getInstaller($moduleId);
        if($moduleInstaller == false) return false;
        try {
            //need transaction support ！
            $moduleInstaller->install();
            return true ;
        } catch (CException $e) {
            throw $e ;
            //do  nothing  here！ 什么也不做这里！
            return false;
        }
    }
    /**
     * @static
     * @param $moduleId
     * @return bool
     */
    static  public function uninstallModule($moduleId){
        $moduleInstaller = self::getInstaller($moduleId);

        if($moduleInstaller == false) return false;
        try {
            //need transaction support ！
            $moduleInstaller->uninstall();
            return true ;
        } catch (CException $e) {
            throw $e ;
            //do  nothing  here！ 什么也不做这里！
            return false;
        }
    }
}
