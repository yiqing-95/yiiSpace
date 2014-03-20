<?php
/**
 * Created by JetBrains PhpStorm.
 * User: yiqing
 * Date: 12-4-8
 * Time: 上午11:37
 *------------------------------------------------------------
 *                 _            _
 *                (_)          (_)
 *        _   __  __   .--. _  __   _ .--.   .--./)
 *       [ \ [  ][  |/ /'`\' ][  | [ `.-. | / /'`\;
 *        \ '/ /  | || \__/ |  | |  | | | | \ \._//
 *      [\_:  /  [___]\__.; | [___][___||__].',__`
 *       \__.'            |__]             ( ( __))
 *
 *--------------------------------------------------------------
 * To change this template use File | Settings | File Templates.
 * ------------------------------------------------------------
 *  'onBeginRequest => array('ModuleUrlRuleManager', 'getUrlRules'),
 * -----------------------------------------------------------------
 * 处理各个模块的url重写规则  遍历各个模块类搜索getUrlRules静态方法
 * 之所以用静态方法是因为 实例化某些模块是很昂贵的 甚至是危险的
 * 比如在XxxModule::init() 方法中重置了系统组件（CWebUser。db等）
 * 所以尽量不要遍历实例化各个module  可以借助文件扫描来搜索类的路径
 *
 *-----------------------------------------------------------------
 * note; 此模块是预加载模块 {Module::preloads}
 * 虽然可以监听onBeginRequest 但不是个好主意 一个萝卜一个坑！你占了别人咋弄？
 * ----------------------------------------------------------------------
 *  'rules' => array(
 *  用自定义的urlRule 可以添加规则 然后返回false 类似不做验证的validator类 没试过
 * array( 'class' => 'application.components.LSearchFormRule', ),
 *
 */
class ModuleUrlRuleManager extends CApplicationComponent
{

    /**
     * @var array
     * 用来存放  moduleClass => filePath 的映射之用
     */
    protected $_classMap = array();

    /**
     *
     */
    public function init()
    {
        parent::init();
        $this->loadClassMap();
        $this->handleUrlRules();
    }

    /**
     *
     * @return CFileCache
     */
    protected function getFileCache()
    {
        return Yii::app()->fileCache;
    }

    /**
     * 加载 模块类和其相应的路径的映射数组
     */
    protected function  loadClassMap()
    {
        $fileCache = $this->getFileCache();
        $cacheKey = 'moduleClass_classPath';
        if (($cached = $fileCache->get($cacheKey)) !== false) {
            //echo __METHOD__ , 'loadFromCache';
            $this->_classMap = $cached;
        } else {
            //echo __METHOD__ , ' recaculate it !';
            $dependency = new CFileCacheDependency(Yii::getPathOfAlias('application.config') . DIRECTORY_SEPARATOR . 'modules.php');
            $classMap = array();
            //---------------------------------------------------------------
            /**
             * 耗时操作 寻找模块和其对应的路径
             * 下面的慎用
             *
            $app = Yii::app();
            foreach ($app->getModules() as $moduleName => $config) {
            $module = $app->getModule($moduleName);
            $moduleClass = ucfirst($moduleName) . 'Module';
            $classMap[$moduleClass] = $module->getBasePath() . DIRECTORY_SEPARATOR . $moduleClass . '.php';
            }
             *
             * 上面的这种方法只适合用来生成缓存 第一次调用后就应该让请求重入一下
             *
             * echo "<script type='javascript/text'> window.location = <?php echo $request->getRequestUrl(); ?> </script>";
             *
             * 不然可能导致意外  这种方法生成的路径绝对准确 因为使用的是反射 下面的方法使用的是路径扫描
             * 如果module存放的路径不是默认的那么可能出错的。
             */
            $app = Yii::app();
            $di = new DirectoryIterator($app->getModulePath());
            foreach ($di as $d) {
                if (!$d->isDot() && '.svn' != ($fileName = $d->getFileName())) {
                    // echo " {$fileName} ======> {$d->getPathName()} <br/> ";
                    $moduleClass = ucfirst($fileName) . 'Module';
                    $modulePath = $d->getPathName() . DIRECTORY_SEPARATOR . $moduleClass . '.php';
                    $classMap[$moduleClass] = $modulePath;
                }
            }
            //---------------------------------------------------------------
            $fileCache->set($cacheKey, $classMap, 0, $dependency);
            $this->_classMap = $classMap;
            // print_r($classMap); die;
        }

    }

    /**
     * 处理模块的url重写规则
     * 遍历已安装的模块 然后合并所有的url规则
     * see IUrlRewriteModule
     * ------------------------------------------
     * 注意嵌套情况 如果模块存在嵌套 那么必须在顶级模块中
     * 收集子模块的url规则 因为扫描过程是文件形式的不是通过
     * 实例（CModule 如果通过实例 可以很准确的得到子模块 但存在效率问题
     * 采用缓存可以缓解 ，B不得以时可以用实例通过反射达成目的
     * ）
     * ------------------------------------------
     * @param \CModule $rootModule 处理根模块下各个模块的URL规则
     * 如果不给出那么默认认为根是CWebApplication
     * @throws CException
     * @return void
     */
    public function handleUrlRules(CModule $rootModule = null)
    {
        /*
        foreach($this->_classMap as $className=>$classPath){
           include_once($classPath);
        }*/
        //  echo __METHOD__; print_r($this->_classMap);
        $method = 'getUrlRules';
        $app = Yii::app();
        $urlManager = $app->getUrlManager();

        if (empty($rootModule)) {
            $rootModule = $app;
        }
        foreach ($rootModule->getModules() as $moduleName => $config) {
            $moduleClassName = ucfirst($moduleName) . 'Module';

            if (isset($this->_classMap[$moduleClassName])) {
                include_once($this->_classMap[$moduleClassName]);
            } elseif (isset($config['class'])) {
                // echo  "alias :  {$config['class']}"; //
                /**
                 * 这种方式只能处理那些模块不放在标准位置的情况
                 * 前缀只包括 system.  webroot . application . ext.
                 * 蛋疼的import竟然不管用
                 */
                //Yii::import($config['class']);
                require_once(Yii::getPathOfAlias($config['class']) . '.php');
            }
            if (class_exists($moduleClassName, false)) {
                if (is_callable($moduleClassName . '::' . $method)) {
                    $urlManager->addRules($moduleClassName::$method());
                }
            } else {
                $errorMsg = "class {$moduleClassName} cann't loaded ,path :  {$config['class']}! <br/> from " . __METHOD__;
                throw new CException($errorMsg);
            }

        }
    }
}