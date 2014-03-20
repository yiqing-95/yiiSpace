<?php
/**
 * Created by JetBrains PhpStorm.
 * User: yiqing
 * Date: 11-7-12
 * Time: 下午2:26
 * To change this template use File | Settings | File Templates.
 */

class YiiUtil
{
    /**
     * @static
     * @return PcMaxmindGeoIp
     */
    public static function getGeoIpObject()
    {
        if (!isset(Yii::app()->geoip)) {
            Yii::app()->setComponents(array(
                'geoip' => array(
                    'class' => 'PcMaxmindGeoIp',
                ),
            ), false);
        }
        return Yii::app()->geoip;
    }

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
     * @param null $id
     * @param null $module
     * @return array
     * 返回一个控制器所有可用的 action
     */
    public static function getActionsOfController($controllerOrClassName, $id = null, $module = null)
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
            $controllerInstance = new $controllerOrClassName($id, $module);
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
        for ($i = 2; $i < $count; $i++) {
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
     * @param $phpCode
     * @return string
     */
    public static function removePhpComments($phpCode)
    {
        $newStr = '';
        $tokens = token_get_all($phpCode);

        $commentTokens = array(T_COMMENT);
        if (defined('T_DOC_COMMENT')) {
            $commentTokens[] = constant('T_DOC_COMMENT');
        }
        if (defined('T_ML_COMMENT')) {
            $commentTokens[] = constant('T_ML_COMMENT');
        }

        foreach ($tokens as $token) {
            if (is_array($token)) {
                if (in_array($token[0], $commentTokens)) continue;

                $token = $token[1];
            }

            $newStr .= $token;
        }
        return $newStr;

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

        //相对路径转绝对路径
        if (strpos($path, ':') !== false) {
            //表示是相对路径：
            $path = realpath($path);
        }

        $path = strtr($path, array('/' => DIRECTORY_SEPARATOR, '\\' => DIRECTORY_SEPARATOR));
        $relPath = Yii::getPathOfAlias($rel_alias);
        if ($relPath == $path) {
            return $relPath;
        } elseif ((false !== ($i = strrpos($path, $relPath)) &&
            $i === strlen($path) - strlen($relPath))
        ) {
            return false;
        } else {
            $path = substr($path, strlen($relPath));
            //$path = substr($path, 0, strpos($path, '.'));
            //die($path);

            if (($dotPos = strrpos($path, '.')) !== false) {
                //去掉 . 后缀
                $path = substr($path, 0, $dotPos);
            }
            $path = str_replace(DIRECTORY_SEPARATOR, '.', $path);
            return $rel_alias . $path;
        }
    }

    /**
     * @static
     * @param $path
     * @param string $rel_alias
     * @return string
     * @throws InvalidArgumentException
     */
    public static function getClassAliasFromPath($path, $rel_alias = 'application')
    {
        if (is_file($path)) {
            $dirPath = dirname($path);
            $dirAlias = self::getAliasFromPath($dirPath, $rel_alias);
            $className = basename($path, ".php");
            $classAlias = $dirAlias . '.' . $className;
            return $classAlias;
        } else {
            throw new InvalidArgumentException('you give an invalidate file path {' . $path . '}');
        }
    }

    /**
     * @static
     * @param bool $output  whether output the info ,default is output to browser !
     * @return string
     */
    public static function debugTraceInfo($output = true)
    {
        $rtn = "<pre>" . print_r(debug_backtrace(), 1) . "</pre>\n";
        if ($output == true) {
            echo $rtn;
        } else {
            return $rtn;
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

                if ($withSuffix == true) {
                    $controllers[] = $class_name;
                } else {
                    if (($pos = stripos($class_name, 'controller')) !== false)
                        $controllers[] = lcfirst(substr($class_name, 0, $pos));
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

    /**
     * @param $path
     * @param null $relPath
     * @return string
     * 获取路径的相对路径 如果没有给出那么就是基于yii基路经
     */
    static public function getRelativePath($path, $relPath = null)
    {
        if ($relPath === null) {
            $relPath = Yii::app()->basePath;
        }

        if (strpos($path, $relPath) === 0)
            return substr($path, strlen($relPath) + 1);
        else
            return $path;
    }

    /**
     * @static
     * @param $path
     * @param string $relAlias
     * @return bool|string
     */
    static public function getAliasOfPath($path, $relAlias = 'application')
    {
        $path = strtr($path, array('/' => DIRECTORY_SEPARATOR, '\\' => DIRECTORY_SEPARATOR));
        //相对路径转绝对路径
        if (strpos($path, ':') !== false) {
            //表示是相对路径：
            $path = realpath($path);
        }

        $relPath = Yii::getPathOfAlias($relAlias);
        if ($relPath === $path) {

            return $relAlias;
        } elseif ((false !== ($i = strrpos($path, $relPath)) &&
            $i === strlen($path) - strlen($relPath))
        ) {
            return false;
        } else {

            $path = substr($path, strlen($relPath)); //\modules\rights
            // $path = substr($path, 0, strpos($path, '.'));
            // echo $path ; die;
            /*
            if(($dotPos = strrpos($path,'.')) !== false){
                //去掉 . 后缀
                $path = substr($path ,0,$dotPos);
            }*/
            if (($dotPos = strpos($path, '.')) !== false) {
                //去掉 . 后缀
                $path = substr($path, 0, $dotPos);
            }
            $path = str_replace(DIRECTORY_SEPARATOR, '.', $path);

            return $relAlias . $path;
        }
    }


    /**
     * @static
     * @param $tableName
     * @return string
     * 表名转换为类名称 简单的转换而已
     */
    public static function tableName2className($tableName)
    {
        $className = '';
        foreach (explode('_', $tableName) as $name) {
            if ($name !== '')
                $className .= ucfirst($name);
        }
        return $className;
    }

    /**
     * @static
     * @param $name
     * @return bool
     *
     */
    static public function classExists($name)
    {
        return class_exists($name, false) && in_array($name, get_declared_classes());
    }

    /**
     * Converts a class name into a HTML ID.
     * For example, 'PostTag' will be converted as 'post-tag'.
     * @param string $name the string to be converted
     * @return string the resulting ID
     */
    static public function class2id($name)
    {
        return trim(strtolower(str_replace('_', '-', preg_replace('/(?<![A-Z])[A-Z]/', '-\0', $name))), '-');
    }

    /**
     * Converts a class name into space-separated words.
     * For example, 'PostTag' will be converted as 'Post Tag'.
     * @param string $name the string to be converted
     * @param boolean $ucwords whether to capitalize the first letter in each word
     * @return string the resulting words
     */
    static public function class2name($name, $ucwords = true)
    {
        $result = trim(strtolower(str_replace('_', ' ', preg_replace('/(?<![A-Z])[A-Z]/', ' \0', $name))));
        return $ucwords ? ucwords($result) : $result;
    }

    /**
     * @static
     * @param $name
     * @return array
     */
    static public function class2var($name)
    {
        $name[0] = strtolower($name[0]);
        return $name;
    }


    /**
     * @static
     * @param $subject
     * @param string $delimiters
     * @param bool $lcfirst
     * @return array|mixed|string
     * @throws Exception
     */
    public static function camelCase($subject, $delimiters = ' _-', $lcfirst = true)
    {
        if (!is_string($subject)) {
            throw new Exception("Subject must be of type string");
        }
        $subject = preg_replace('/[\s]+/', ' ', $subject);

        $subject = preg_split("/[$delimiters]/", $subject, -1, PREG_SPLIT_NO_EMPTY);

        foreach ($subject as $key => &$word) {
            $word = preg_replace('/[[:punct:]]/', '', $word);

            if (preg_match('/[A-Z]+$/', $word)) $word = ucfirst($word);

            else $word = ucfirst(strtolower($word));
        }
        $subject = implode('', $subject);

        if ($lcfirst) {
            return function_exists('lcfirst') ? lcfirst($subject)
                :
                strtolower($subject[0]) . substr($subject, 1);
        }
        return $subject;
    }

    /**
     * Converts a word to its plural form.
     * Note that this is for English only!
     * For example, 'apple' will become 'apples', and 'child' will become 'children'.
     * @param string $name the word to be pluralized
     * @return string the pluralized word
     * 复数化单词
     */
    static public function pluralize($name)
    {
        $rules = array(
            '/(x|ch|ss|sh|us|as|is|os)$/i' => '\1es',
            '/(?:([^f])fe|([lr])f)$/i' => '\1\2ves',
            '/(m)an$/i' => '\1en',
            '/(child)$/i' => '\1ren',
            '/(r|t)y$/i' => '\1ies',
            '/s$/' => 's',
        );
        foreach ($rules as $rule => $replacement) {
            if (preg_match($rule, $name))
                return preg_replace($rule, $replacement, $name);
        }
        return $name . 's';
    }

    /**
     * @static
     * @param string $classOrObject
     * @return mixed
     * @throws InvalidArgumentException
     */
    static public function  getPathOfClass($classOrObject = '')
    {
        if (is_string($classOrObject)) {
            $rc = new ReflectionClass($classOrObject);
        } elseif (is_object($classOrObject)) {
            $rc = new ReflectionObject($classOrObject);
        } else {
            throw new InvalidArgumentException(
                'you  must give a class name or an instance of some class ; now it is:' . var_export($classOrObject, true));
        }


        return $rc->getFileName();
    }

    static public function isCli()
    {
        return !array_key_exists('REQUEST_METHOD', $_SERVER);
        /*
        if(php_sapi_name() == 'cli' && empty($_SERVER['REMOTE_ADDR'])) {
            return true;
        } else {
            return false;
        }
        */
    }

    /**
     * Method to determine if the host OS is  Windows
     *
     * @return  boolean  True if Windows OS
     * @http://stackoverflow.com/questions/738823/possible-values-for-php-os
     */
    public static function isWinOS()
    {
        return strtoupper(substr(PHP_OS, 0, 3)) === 'WIN';
    }

    /**
     * @static
     * @param $obj
     * ---------------------------------------
     * 打印对象的基本信息 属性及其getter方法的值
     * ---------------------------------------
     */
    public static function probeObject($obj)
    {
        $ro = new ReflectionObject($obj);
        echo " <br/>================================================================== <br/>",
        "object file path :{$ro->getFileName()}";

        echo " <br/>================================================================== <br/>";
        $props = $ro->getProperties();
        foreach ($props as $prop) {
            if ($prop->isPublic()) {
                $propName = $prop->getName();
                $propVal = $obj->$propName;
                if (is_array($propVal)) {
                    $propVal = var_export($propVal, true);
                }
                echo "{$propName} =>  {$propVal} <br/>";
            }
        }
        echo "<br/> ================================================================== <br/>";
        $methods = $ro->getMethods();
        foreach ($methods as $method) {
            if ($method->isPublic()) {
                $methodName = $method->getName();
                if (strpos($methodName, 'get') === 0) {
                    if ($method->getNumberOfRequiredParameters() == 0) {
                        $return = call_user_func_array(array($obj, $methodName), array());

                        if (is_array($return)) {
                            $return = var_export($return, true);
                        }
                        echo "{$methodName}() => {$return} <br/>";
                    }
                }
            }
        }
        echo "<br/> ================================================================== <br/>";
    }

    /**
     * Compare two strings and remove the common
     * sub string from the first string and return it
     * @param string $first
     * @param string $second
     * @param string $char optional, set it as
     * blank string for char by char comparison
     * @return string
     */
    public static function removeCommonPath($first, $second, $char = '/')
    {
        $first = explode($char, $first);
        $second = explode($char, $second);
        while (count($second)) {
            if ($first[0] == $second[0]) {
                array_shift($first);
            } else break;
            array_shift($second);
        }
        return implode($char, $first);
    }

    /**
     * @static
     * @param $fileStr
     * @return string
     * -------------------------------------
     * 只移除代码中出现的评论 但不会移除标准的php Doc、
     * -------------------------------------
     */
    public static function stripComments($fileStr)
    {
        $newStr = '';
        $commentTokens = array(T_COMMENT);
        //if (defined('T_DOC_COMMENT'))
        //$commentTokens[] = T_DOC_COMMENT; // PHP 5
        $tokens = token_get_all($fileStr);
        foreach ($tokens as $token) {
            if (is_array($token)) {
                if (in_array($token[0], $commentTokens))
                    continue;
                $token = $token[1];
            }
            $newStr .= $token;
        }
        return $newStr;
    }

    /**
     * @static
     * @param $file
     * @param bool $requireComments
     * @return string
     * @throws InvalidArgumentException
     */
    public static function getPhpFileSource($file, $requireComments = false)
    {
        if (!file_exists($file)) {
            throw  new InvalidArgumentException("{$file} is not a validate file path!");
        }
        $text = file_get_contents($file);
        // $file = pathinfo($file, PATHINFO_FILENAME).'.php';
        if (!$requireComments) {
            // die(__LINE__);
            $text = self::stripComments($text);
        }
        return '<pre id="php">' . htmlspecialchars($text) . "</pre>";
    }

    /**
     * @static
     * @return CWebModule|null
     */
    public static function getCurrentModule()
    {
        return Yii::app()->getController()->getModule();
    }

    /**
     * @static
     * @return string
     */
    public static function getCurrentRoute()
    {
        return Yii::app()->getController()->getRoute();
    }

    /**
     * @static
     * @param $sql
     * @param array $params
     * @param CDbConnection $db
     * @return int|mixed
     * @warning id the sql string has "UNION" and is in the sub query you 'd better
     *    not use this function !
     * if the sql not contain "UNION" it will work well !
     */
    static public function countBySql($sql, $params = array(), CDbConnection $db = null)
    {
        $parts = explode('UNION', $sql);
        if (count($parts) > 1) {
            $count = 0;
            foreach ($parts as $selectSql) {
                $count += self::countBySql($selectSql, $params);
            }
            return $count;
        } else {
            $selectStr = trim($sql); //紧身以下
            $selectStr = substr_replace($selectStr, ' COUNT(*) ', 6, stripos($selectStr,
                'FROM') - 6);
            $selectStr = preg_replace('~ORDER\s+BY.*?$~sDi', '', $selectStr);

            $db = ($db == null) ? Yii::app()->db : $db;
            return $db->createCommand($selectStr)->queryScalar($params);
        }
    }


    /**
     * @param CSqlDataProvider $dp
     * genViewFromSqlDataProvider
     * ---------------------------------------------
     * 用一个sqlDataProvider 来审查视图
     * ---------------------------------------------
     */
    static public function listView4sqlDataProvider(CSqlDataProvider $dp)
    {

        $viewStr = CHtml::openTag('div', array());
        $idItem = <<<KEY_ITEM
    <b> id:</b>
	<?php echo CHtml::link(CHtml::encode(\$data['id']),array('view','id'=>\$data['id'])); ?>
	<br />
KEY_ITEM;
        $viewStr .= "\n" . $idItem;

        $rowSet = $dp->getData();
        $firstRow = array();
        if (!empty($rowSet)) {
            $firstRow = current($rowSet);
        }
        foreach (array_keys($firstRow) as $column) {
            $columnItem = <<<COL_ITEM
    <b>{$column}:</b>
	<?php echo CHtml::encode(\$data['{$column}']); ?>
	<br />
COL_ITEM;
            $viewStr .= ("\n" . $columnItem);
        }
        $viewStr .= CHtml::closeTag('div');

        //echo "<pre>{$viewStr}</pre>" ;
        $controller = Yii::app()->getController();

        $controller->widget('ext.jchili.JChiliHighlighter', array(
            'lang' => "html",
            'code' => "{$viewStr}",
            'showLineNumbers' => false
        ));
    }

    /**
     * @static
     * @param $tableName
     * @param bool $camelCase
     * @return string
     */
    public static function insertMethodForTable($tableName, $camelCase = false)
    {
        $tableSchema = Yii::app()->db->getSchema()->getTable($tableName);
        $columns = $tableSchema->columns;
        $methodComments = "/** \n";
        foreach ($columns as $column) {
            $columnVar = $camelCase ? self::class2var(self::tableName2className($column->name)) : $column->name;
            $methodComments .= ("* @param {$column->type} \${$columnVar} \n ");
        }
        $methodComments .= "* @return mixed \n */";
        $methodString = "public  function insert" . self::tableName2className($tableName) . "(";
        foreach ($columns as $column) {
            $columnVar = $camelCase ? self::camelCase($column->name) : $column->name;
            $methodString .= (" {$column->type} \${$columnVar},");
        }
        $methodString = rtrim($methodString, ',');
        $methodString .= ")\n{\n";
        //.......................................................................
        // here pass var to ar
        $arObjectName = self::class2var(self::tableName2className($tableName));
        $arClassName = self::tableName2className($tableName);
        $methodString .= "  \${$arObjectName} = new {$arClassName}();\n ";
        foreach ($columns as $column) {
            $columnVar = $camelCase ? self::camelCase($column->name) : $column->name;
            $methodString .= "\t\t \${$arObjectName}->{$column->name} = \${$columnVar};\n";
        }
        $methodString .= "\t \${$arObjectName}->save();";
        //.......................................................................
        $methodString .= "\n}";

        $methodStyle2 = "//================================================\n ";
        $methodStyle2 .= "//below is the style two \n ";
        $methodStyle2 .= self::insertMethodForTable2($tableName,false);

        return $methodComments . "\n" . $methodString ."\n ".$methodStyle2;
        //  * @property <?php echo $column->type.' $'.$column->name."\n";
    }

    public static function insertMethodForTable2($tableName, $camelCase = true)
    {
        $tableSchema = Yii::app()->db->getSchema()->getTable($tableName);
        $columns = $tableSchema->columns;
        $methodComments = "/** \n";
        $methodComments .= " * @param array \$attributes list of attributes that need to be saved.\n ";
        $methodComments .= "-----------------------------------------------------------------\n array( ";
        foreach ($columns as $column) {
            $columnVar = $camelCase ? self::class2var(self::tableName2className($column->name)) : $column->name;
            $methodComments .= (" '{$columnVar}' => \${$columnVar}, //{$column->type} \n ");
        }
        $methodComments .= ") \n-----------------------------------------------------------------\n ";
        $methodComments .= "* @return mixed \n */";
        $methodString = "public  function insert" . self::tableName2className($tableName) . "( \$attributes )\n{\n";
        //.......................................................................
        // here pass var to ar
        $arObjectName = self::class2var(self::tableName2className($tableName));
        $arClassName = self::tableName2className($tableName);
        $methodString .= " \t \${$arObjectName} = new {$arClassName}();\n ";
        $methodString .= "\t \${$arObjectName}->attributes = \$attributes; \n ";

        $methodString .= "\t \${$arObjectName}->save();";
        //.......................................................................
        $methodString .= "\n}";
        return $methodComments . "\n" . $methodString;
        //  * @property <?php echo $column->type.' $'.$column->name."\n";
    }

    public static function refreshListGridView(){

    }

    /**
     * @param CModel $model
     */
    public static function trimAttributes(CModel &$model){
        foreach($model->getAttributes() as  $attr=>$val){
            if(!empty($val) && is_string($val)){
                $model->{$attr} = trim($val);
            }
        }
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
