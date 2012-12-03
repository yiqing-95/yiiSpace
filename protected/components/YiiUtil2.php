<?php
/**
 * Created by JetBrains PhpStorm.
 * User: cztx
 * Date: 11-7-12
 * Time: 下午2:26
 * To change this template use File | Settings | File Templates.
 */

class YiiUtil2
{
    /**
     * Check if a model has the behavior
     * @static
     * @param CComponent $record
     * @param string $behaviorClassName The class name of the behavior
     * @return boolean
     */
    public static function modelHasBehavior($record, $behaviorClassName)
    {
        $behaviors = $record->behaviors();
        foreach ($behaviors as $behavior => $config) {
            if (isset($record->$behavior)) {
                if ($behaviorClassName == get_class($record->$behavior)) {
                    return true;
                }
            }
        }
        return false;
    }

    /**
     * 获取控制器可用的action
     * 可用传递当前控制器的实例 比如$this  或者一个控制器的类名称
     * 注意 如果使用控制器类名称 确保把控制器目录加载import中
     * 可用 Yii::import(); 动态导入控制器所在的路径 或者在main中配置
     * 导入的目录。如： import => 'application.controllers.*',
     * @static
     * @throws CException
     * @param $controllerOrClassName
     * @return array
     * 返回一个控制器所有可用的 action
     */
    public static function getActionsOfController($controllerOrClassName)
    {
        if (is_object($controllerOrClassName)) {
            //如果传递的是对象那么保留一份；
            $controllerInstance = $controllerOrClassName;

            $controllerOrClassName = get_class($controllerOrClassName);
        }
        if (!is_string($controllerOrClassName)) {
            throw new CException('must give a string or instance of CController');
        }

        $methods = get_class_methods($controllerOrClassName);
        $actions = array();
        //收集所有以 action为前缀的方法名 actions方法除过
        foreach ($methods as $method) {
            if (preg_match("/^action[^s]\w+$/", $method)) {
                // $action_id = preg_replace('/action/', '', strtolower($method_name), 1);
                $method = str_replace('action', '', $method);
                $method = lcfirst($method);
                $actions[] = $method;
            }
        }

        if (!isset($controllerInstance)) {
            $controllerInstance = new $controllerOrClassName;
        }
        foreach ($controllerInstance->actions() as $action_id => $config) {
            $actions[] = $action_id;
        }
        return $actions;
    }


    /**
     * @param $file
     * @return string
     * 获取某个文件中的类 第一个类
     * -------------------------------------------
     *  另一种方法 通过计算当前系统中所有包含的类的差集来做的
     * --------------------------------------------
     */
    public function getClassFormFile($file)
    {
        $fp = fopen($file, 'r');
        $class = $buffer = '';
        $i = 0;
        while (!$class) {
            if (feof($fp)) break;
            $buffer .= fread($fp, 512);
            $tokens = token_get_all($buffer);
            if (strpos($buffer, '{') === false) continue;
            for (; $i < count($tokens); $i++) {
                if ($tokens[$i][0] === T_CLASS) {
                    for ($j = $i + 1; $j < count($tokens); $j++) {
                        if ($tokens[$j] === '{') {
                            $class = $tokens[$i + 2][1];
                        }
                    }
                }
            }
        }
        return $class;
    }

    /**
     * @param string $file
     * @param bool $ignoreAbstractClass 是否忽略抽象类 默认是忽略的
     * @return array
     */
    public static function getClassesFormFile($file, $ignoreAbstractClass = true)
    {
        $php_code = file_get_contents($file);

        $classes = array();
        $tokens = token_get_all($php_code);
        $count = count($tokens);
        for ($i = 2; $i < $count; $i++)
        {
            if ($tokens[$i - 2][0] == T_CLASS &&
                $tokens[$i - 1][0] == T_WHITESPACE &&
                $tokens[$i][0] == T_STRING &&
                (!$ignoreAbstractClass || !($tokens[$i - 3] && $tokens[$i - 4][0] == T_ABSTRACT))
            ) {
                $class_name = $tokens[$i][1];
                $classes[] = $class_name;
            }
        }
        return $classes;

        /*
      if(is_file($file)){
         $beforeClasses = get_declared_classes();
          require_once($file);
          $afterClasses = get_declared_classes();
          return array_diff($beforeClasses,$afterClasses);
      }else{
          throw new InvalidArgumentException('must be an file path');
      }
        */
    }


    /**
     * @static
     * @param $name
     * @return string
     * @see http://www.yiiframework.com/doc/api/1.1/CModel#generateAttributeLabel-detail
     * 从字符串解析出 比较人可读的字符
     */
    static public function generateLabel($name)
    {
        return ucwords(trim(strtolower(str_replace(array('-', '_', '.'), ' ', preg_replace('/(?<![A-Z])[A-Z]/', ' \0', $name)))));
    }

    /**
     * @static
     * @throws CException
     * @param $obj
     * @return void
     * 输出对象的属性 用来调试
     */
    public static function dumpObject($obj)
    {
        if (is_object($obj)) {
            echo '<pre>';
            CVarDumper::dump($obj);
            echo '</pre>';
        } else {
            throw new CException('must give an object');
        }
    }

    /**
     * @param $path 指定的路径 比如__FILE__ ,__DIR__ 等
     *          需要绝对路径
     * @param string $rel_alias 相对于application而言
     * @return string|bool
     * 返回当期类的路径别名  如果不存在返回false
     * 注意只判断了前缀 并不是拥有相同前缀字符串就能够算出别名来
     * ---------------------------------------------------------------
     * 目前只相对于application别名计算 其他暂不予考虑吧！
     */
    public static function getAliasFromPath($path, $rel_alias = 'application')
    {
        //$path = strtr($path,array('/'=>DIRECTORY_SEPARATOR,'\\'=>DIRECTORY_SEPARATOR));
        //相对路径转绝对路径
        if (strpos($path, ':') !== false) {
            //表示是相对路径：
            $path = realpath($path);
        }

        $relPath = Yii::getPathOfAlias($rel_alias);
        if ($relPath == $path) {
            return $relPath;
        } elseif ((false !== ($i = strrpos($path, $relPath)) &&
            $i === strlen($path) - strlen($relPath))
        ) {
            return false;
        } else {
            $path = substr($path, strlen($relPath));
            $path = substr($path, 0, strpos($path, '.'));
            $path = str_replace(DIRECTORY_SEPARATOR, '.', $path);
            return $rel_alias . $path;
        }
    }

    /**
     * @static
     * @param string $path
     * @param bool $withSuffix
     * @return array
     * ----------------------------
     * get all controller ids from the given dirPath ,
     * if the second param $withSuffix set to true ,you can
     * get all controller class names .
     * -----------------------------
     */
    public static function getControllersFromDir($path = '', $withSuffix = false)
    {
        if (empty($path)) {
            $path = Yii::app()->controllerPath;
        }
        $controllers = array();
        //错误转异常
        self::phpErrorToException();
        //遍历所有控制器文件
        foreach (glob($path . DIRECTORY_SEPARATOR . '*Controller.php') as $full_name) {
            $class_name = pathinfo($full_name, PATHINFO_FILENAME);
            try {
                if (!class_exists($class_name, false))
                    require($full_name);
                if (!class_exists($class_name, false) || !is_subclass_of($class_name, 'CController'))
                    continue;
                // $controller_ids[] = preg_replace('/controller/', '', strtolower($class_name), 1);

                if ($withSuffix == true){
                    $controllers[] = $class_name;
                } else {
                    if(($pos = stripos($class_name,'controller')) !==false)
                    $controllers[] = lcfirst(substr($class_name,0,$pos));
                    //lcfirst(rtrim($class_name,'Controller')); //preg_replace('/controller/', '', strtolower($class_name), 1);
                }
            } catch (KErrorException $e) {
                //do  nothing  here！ 什么也不做这里！
            }
        }
        return $controllers;
    }

    /**
     * @static
     * @param $error_level
     * @param $error_message
     * @param $error_file
     * @param $error_line
     * @param null $error_context
     * @throws KErrorException
     * 用来处理php错误转异常的
     */
    public static function handleError($error_level, $error_message, $error_file, $error_line, $error_context = null)
    {

        throw new KErrorException($error_message, $error_level, $error_file, $error_line, $error_context = null);
    }

    /**
     * @static
     * @return mixed
     * 将php错误转为异常 异常类型是KErrorException
     */
    public static function phpErrorToException()
    {
        return @set_error_handler(array(__CLASS__, 'handleError'));
    }


}

if (!class_exists('KErrorException', false)) {
    /**
     * This exception behaves like a "old school" PHP Error
     * -----------------------------------
     * 错误转为异常
     */
    class KErrorException extends Exception
    {
        /**
         *   The PHP Error Context
         *   The fifth parameter is optional,
         *   errcontext,
         *   which is an array that points to the active symbol table
         *   at the point the error occurred. In other words, errcontext
         *   will contain an array of every variable that existed in the scope the
         *   error was triggered in. User error handler must not modify error context.
         */
        public $errorLevel;
        public $errorMessage;
        public $errorFile;
        public $errorLine;
        public $errorContext;

        /**
         * 注意跟 set_error_handler 错误处理器参数顺序稍有不同
         * 前两个交换即可 这是有Exception的构造器的签名决定的
         * @param string $vMessage
         * @param string $vCode
         * @param string $vFile
         * @param string $vLine
         * @param null $arContext
         * @return \KErrorException
         */
        public function __construct($vMessage = '', $vCode = '', $vFile = '', $vLine = "", $arContext = null)
        {
            parent::__construct($vMessage, $vCode);

            $this->errorFile = $vFile;
            $this->errorLine = $vLine;

            $this->errorContext = $arContext;
        }
    }
}
