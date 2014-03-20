<?php
/**
 * Created by JetBrains PhpStorm.
 * User: cztx
 * Date: 11-5-17
 * Time: 上午11:12
 * To change this template use File | Settings | File Templates.
 */

class ArrayUtil
{


    /**
     * convert all keys in a multi-dimenional array to snake_case
     * @see http://stackoverflow.com/questions/1444484/how-to-convert-all-keys-in-a-multi-dimenional-array-to-snake-case
     * @static
     * @param $array
     */
    public static    function transformKeys(&$array)
    {
        foreach (array_keys($array) as $key):
            # Working with references here to avoid copying the value,
            # since you said your data is quite large.
            $value = &$array[$key];
            unset($array[$key]);
            # This is what you actually want to do with your keys:
            #  - remove exclamation marks at the front
            #  - camelCase to snake_case
            $transformedKey = strtolower(preg_replace('/([a-z])([A-Z])/', '$1_$2', ltrim($key, '!')));
            # Work recursively
            if (is_array($value)) transformKeys($value);
            # Store with new key
            $array[$transformedKey] = $value;
            # Do not forget to unset references!
            unset($value);
        endforeach;
    }

    public static function maxStringLenInArray($arr)
    {
        $lengths = array_map('strlen', $arr);
        return max($lengths);
        /*
           * foreach ($data as $a) {
           $length = strlen($a);
           $max = max($max, $length);
           $min = min($min, $length);
           }
           $max_l = strlen(array_reduce($data,'maxlen'));
           function maxlen($k,$v) {
           if (strlen($k) > strlen($v)) return $k;
           return $v;
           }
           */
    }

    /**
     * @static
     * @param  $arr
     * @return mixed
     */
    public static function minStringLenInArray($arr)
    {
        $lengths = array_map('strlen', $arr);
        return min($lengths);
    }

    /**
     * @static
     * @param  $array
     * @return StdClass
     */
    public static function array2object($array)
    {

        if (is_array($array)) {
            $obj = new StdClass();

            foreach ($array as $key => $val) {
                $obj->$key = $val;
            }
        }
        else {
            $obj = $array;
        }

        return $obj;
    }

    /**
     * @static
     * @param $object
     * @return array
     */
    public static function objectToArray($object)
    {
        if (!is_object($object) && !is_array($object)) {
            return $object;
        }
        if (is_object($object)) {
            $object = get_object_vars($object);
        }
        return array_map(array(__CLASS__, 'objectToArray'), $object);
    }

    /**
     * @static
     * @param $object
     * @return array
     */
    public static function object2array($object)
    {
        if (is_object($object)) {
            foreach ($object as $key => $value) {
                $array[$key] = $value;
            }
        }
        else {
            $array = $object;
        }
        return $array;
    }

    /**
     * @static
     * @param $array
     * @return bool|stdClass
     *
     */
    static public function arrayToObject($array)
    {
        if (!is_array($array)) {
            return $array;
        }

        $object = new stdClass();
        if (is_array($array) && count($array) > 0) {
            foreach ($array as $name => $value) {
                $name = strtolower(trim($name));
                if (!empty($name)) {
                    $object->$name = self::arrayToObject($value);
                }
            }
            return $object;
        }
        else {
            return FALSE;
        }
    }


    /**
     * http://php.net/manual/en/function.simplexml-load-string.php
     *
     * @param  $xml
     * @param bool $recursive
     * @return array
     */
    public static function Xml2Array($xml, $recursive = false)
    {
        if (!$recursive) {
            $array = simplexml_load_string($xml);
        }
        else {
            $array = $xml;
        }

        $newArray = array();
        $array = ( array )$array;
        foreach ($array as $key => $value) {
            $value = ( array )$value;
            if (isset ($value [0])) {
                $newArray [$key] = trim($value [0]);
            }
            else {
                $newArray [$key] = self::Xml2Array($value, true);
            }
        }
        return $newArray;
    }

    /**
     * Convert XML to an Array
     *
     * @param string  $XML
     * @return array
     */
    public static function XmlToArray($XML)
    {
        $xml_parser = xml_parser_create();
        xml_parse_into_struct($xml_parser, $XML, $vals);
        xml_parser_free($xml_parser);
        // wyznaczamy tablice z powtarzajacymi sie tagami na tym samym poziomie
        $_tmp = '';
        foreach ($vals as $xml_elem) {
            $x_tag = $xml_elem['tag'];
            $x_level = $xml_elem['level'];
            $x_type = $xml_elem['type'];
            if ($x_level != 1 && $x_type == 'close') {
                if (isset($multi_key[$x_tag][$x_level]))
                    $multi_key[$x_tag][$x_level] = 1;
                else
                    $multi_key[$x_tag][$x_level] = 0;
            }
            if ($x_level != 1 && $x_type == 'complete') {
                if ($_tmp == $x_tag)
                    $multi_key[$x_tag][$x_level] = 1;
                $_tmp = $x_tag;
            }
        }
        // jedziemy po tablicy
        foreach ($vals as $xml_elem) {
            $x_tag = $xml_elem['tag'];
            $x_level = $xml_elem['level'];
            $x_type = $xml_elem['type'];
            if ($x_type == 'open')
                $level[$x_level] = $x_tag;
            $start_level = 1;
            $php_stmt = '$xml_array';
            if ($x_type == 'close' && $x_level != 1)
                $multi_key[$x_tag][$x_level]++;
            while ($start_level < $x_level) {
                $php_stmt .= '[$level[' . $start_level . ']]';
                if (isset($multi_key[$level[$start_level]][$start_level]) && $multi_key[$level[$start_level]][$start_level])
                    $php_stmt .= '[' . ($multi_key[$level[$start_level]][$start_level] - 1) . ']';
                $start_level++;
            }
            $add = '';
            if (isset($multi_key[$x_tag][$x_level]) && $multi_key[$x_tag][$x_level] && ($x_type == 'open' || $x_type == 'complete')) {
                if (!isset($multi_key2[$x_tag][$x_level]))
                    $multi_key2[$x_tag][$x_level] = 0;
                else
                    $multi_key2[$x_tag][$x_level]++;
                $add = '[' . $multi_key2[$x_tag][$x_level] . ']';
            }
            if (isset($xml_elem['value']) && trim($xml_elem['value']) != '' && !array_key_exists('attributes', $xml_elem)) {
                if ($x_type == 'open')
                    $php_stmt_main = $php_stmt . '[$x_type]' . $add . '[\'content\'] = $xml_elem[\'value\'];';
                else
                    $php_stmt_main = $php_stmt . '[$x_tag]' . $add . ' = $xml_elem[\'value\'];';
                eval($php_stmt_main);
            }
            if (array_key_exists('attributes', $xml_elem)) {
                if (isset($xml_elem['value'])) {
                    $php_stmt_main = $php_stmt . '[$x_tag]' . $add . '[\'content\'] = $xml_elem[\'value\'];';
                    eval($php_stmt_main);
                }
                foreach ($xml_elem['attributes'] as $key => $value) {
                    $php_stmt_att = $php_stmt . '[$x_tag]' . $add . '[$key] = $value;';
                    eval($php_stmt_att);
                }
            }
        }
        return $xml_array;
    }

    /**
     * Transforms an array instance in PHP source code to generate this array.
     * If any key or value must be valid PHP code instead of a string, use "php:"
     * on the beggining of the key or value string. Example:
     * <pre>
     * $array = array(
     *     'class' => 'CMyClass',
     *     'title' => 'php:Yii::t(\'app\', \'Any data\')',
     * )
     * </pre>
     * Object serialization is not supported.
     * @param array $array the array.
     * @param string $empty the value to be returned if the passed array is empty.
     * @param integer $indent the base indentation (as number of tabstops) for the generated source in each new line.
     * Note that the first line will not receive indentation.
     * @return string the PHP source code representation of the array.
     */
    public static function ArrayToPhpSource($array, $indent = 1, $empty = 'array()')
    {
        if (empty($array))
            return $empty;

        // Start of array.
        $result = "array(\n";
        foreach ($array as $key => $value) {
            // Indentation.
            $result .= str_repeat("\t", $indent);

            // The key.
            if (is_int($key))
                $result .= $key;
            else if (is_string($key))
                if (strpos($key, 'php:') === 0)
                    $result .= substr($key, 4);
                else
                    $result .= "'{$key}'";
            else // To be future-proof.
                throw new InvalidArgumentException(Yii::t('giix', 'Array key type not supported.'));

            // The assignment.
            $result .= ' => ';

            // The value.
            if (is_null($value))
                $result .= 'null';
            else if (is_array($value))
                $result .= self::ArrayToPhpSource($value, $indent + 1, $empty);
            else if (is_bool($value))
                $result .= $value ? 'true' : 'false';
            else if (is_int($value) || is_float($value))
                $result .= $value;
            else if (is_string($value))
                if (strpos($value, 'php:') === 0)
                    $result .= substr($value, 4);
                else
                    $result .= "'{$value}'";
            else if (is_object($value))
                throw new InvalidArgumentException(Yii::t('giix', 'Object serialization is not supported (on key "{key}").', array('{key}' => $key)));
            else
                throw new InvalidArgumentException(Yii::t('giix', 'Array element type not supported (on key "{key}").', array('{key}' => $key)));

            // End of line
            $result .= ",\n";
        }
        // Indentation.
        $result .= str_repeat("\t", $indent);
        // End of array.
        $result .= ')';

        return $result;
    }

    /**
     * @static
     * @param  $dir 文件路径 最好用 Yii::getPathOfAlias('application.somePath.xxxx')
     * @param  $fileName 文件名称
     * @param $data array 数组
     * @return bool 是否成功
     * 存储数组到文件 注意是真正的数组文件 <?php  return array(......);形式的
     */
    static public function saveArray2file(array $data, $dir, $fileName)
    {

        $cache_path = $dir . DIRECTORY_SEPARATOR . $fileName . '.php';

        if (!$fp = fopen($cache_path, 'wb')) {
            return FALSE;
        }

        if (flock($fp, LOCK_EX)) {
            //fwrite($fp, serialize($data));
            fwrite($fp, "<?php\nreturn " . var_export($data, true) . ";");
            flock($fp, LOCK_UN);
        } else {
            return FALSE;
        }
        fclose($fp);
        @chmod($cache_path, 0777);
        return TRUE;
    }

    /**
     * @static
     * @param array $data
     * @param $filePath
     * @return bool
     * 需要直接给出最终保存路径  上面那个是三个参数的版本
     */
    static public function saveArrayToFile(array $data, $filePath)
    {
        if (!$fp = fopen($filePath, 'wb')) {
            return FALSE;
        }
        if (flock($fp, LOCK_EX)) {
            //fwrite($fp, serialize($data));
            fwrite($fp, "<?php\nreturn " . var_export($data, true) . ";");
            flock($fp, LOCK_UN);
        } else {
            return FALSE;
        }
        fclose($fp);
        @chmod($filePath, 0777);
        return TRUE;
    }

    /**
     * @static
     * @param array $data
     * @return string
     * 把array转换为源码
     */
    static public function  array2source(array $data)
    {
        return "<?php\nreturn " . var_export($data, true) . ";";
    }

    /**
     * 表格形式打印自己 调试用
     * @param array $rowSet 结果集 一般是select语句的返回值 可以是二维的或是一维数组
     * @param string $charset  打印的字符编码
     *                          这样可以防止乱码 默认是UTF-8
     */
    public static function printArray($rowSet, $charset = 'UTF-8')
    {
        if (current($rowSet) instanceof CActiveRecord) {
            $rowSet = self::activeRecordsToArray($rowSet);
        }
        // echo gettype(current($rowSet));

        ?>
    <meta
        http-equiv="Content-Type"
        content="text/html; charset=<?php echo $charset; ?>"/>
    <div style="clear: both">
        <table border="2"
               style="background: powderblue; border: medium solid wheat">
            <caption>hi this is a table style print:</caption>
            <thead>
            <tr style="border: thin ridge #107929">
                <?php
                //把自己的键值作为表头 有可能值传递了一行数据
                $theadArr = isset($rowSet[0]) ? array_keys($rowSet[0]) : array_keys($rowSet);
                foreach ($theadArr as $thead) {
                    echo "<th>" . $thead . "</th>";
                }
                ?>
            </tr>
            </thead>
            <tbody>
                <?php
                if (!isset($rowSet[0])) {
                    echo '<tr style="border: thick outset #ffdb1f;background: yellowgreen">';
                    foreach (array_values($rowSet) as $val) {
                        echo "<td>" . $val . "</td>";
                    }
                    echo '</tr>';
                } else {
                    foreach ($rowSet as $row) {
                        ?>
                    <tr style="border: thick outset #ffdb1f; background: yellowgreen">
                        <?php
                        foreach ($row as $key => $val) {
                            echo "<td>" . $val . "</td>";
                        }
                        ?>
                    </tr>
                        <?php

                    }
                }
                ?>
            </tbody>
        </table>
        <br>
    </div>
    <?php

    }

    /**
     * @static
     * @param $ars
     * @return array
     * ar数组转为 数组
     */
    protected static function activeRecordsToArray($ars)
    {
        $rtn = array();
        foreach ($ars as $ar) {
            $rtn[] = $ar->attributes();
        }
        return $rtn;
    }

    /**
     * @static
     * @param array $array
     * @return void
     * 数组调试方法 为了输出的数组好看而已
     */
    public static function  dumpArray(Array $array)
    {
        echo "<pre>";
        var_dump($array);
        echo '</pre>';
    }

    /**
     * @param $needle
     * @param $haystack
     * @return bool
     * 默认的in_array 是区分大小写得
     * 这个是不区分大小写得实现
     * in_array_case_insensitive
     *
     */
    function in_array_ci($needle, $haystack)
    {
        return in_array(strtolower($needle), array_map('strtolower', $haystack));
    }

    /**
     * @param $a1
     * @param $a2
     * @param string $delimiter
     * @throws Exception
     * @return void
     * 合并两个数组中同键值的元素 用分隔符合并
     */
    function sameKeyCombination(&$a1, &$a2, $delimiter = ',')
    {

        $rtn = array();

        $intersect = array_intersect_key($a1, $a2);
        foreach ($intersect as $key => $val) {

        }
        throw new Exception('未实现........');
    }


    /**
     * @static
     * @param $orig
     * @param $new
     * @param $array
     * @return array
     * 修改原有array的 某个键 为新键
     */
    public static function changeKeyName($orig, $new, &$array)
    {
        foreach ($array as $k => $v)
            $return[($k === $orig) ? $new : $k] = $v;
        return ( array )$return;
    }

    /**
     * @static
     * @param array $originalKey_newKey array('oldKey'=>'newKey','oldX'=>'newX');
     * @param $array
     * @return array
     * 递归替换掉 array key
     *
     */
    public static function changeKeyNameRecursive(array $originalKey_newKey, &$array)
    {
        foreach ($array as $k => $v) {
            if (is_array($v)) {
                $array[$k] = self::changeKeyNameRecursive($originalKey_newKey, $v);
            }
            /*
            $newarr[$newkey] = $oldarr[$oldkey];
            $oldarr = $newarr;
            unset($newarr);
            */
            if (isset($originalKey_newKey[$k])) {
                $array[$originalKey_newKey[$k]] = $array[$k];
                unset($array[$k]);
            }
        }
        return $array;
    }

    /**
     * @static
     * @param $needle
     * @param $haystack array
     * @param string $key_lookin
     * @return array|null
     * ----------------------------------------
     * 从多维数组中搜索指定的值的路径 你也可以指定键（最后一个参数）
     *
     */
    public static function searchPath($needle, $haystack, $key_lookin = "")
    {
        $path = NULL;
        if (!empty($key_lookin) && array_key_exists($key_lookin, $haystack) && $needle === $haystack[$key_lookin]) {
            $path[] = $key_lookin;
        } else {
            foreach ($haystack as $key => $val) {
                if (is_scalar($val) && $val === $needle && empty($key_lookin)) {
                    $path[] = $key;
                    break;
                } elseif (is_array($val) && $path = self::searchPath($needle, $val, $key_lookin)) {
                    array_unshift($path, $key);
                    break;
                }
            }
        }
        return $path;
    }

    public static function search_r($value, $array)
    {
        $results = array();
        if (is_array($array)) {
            $path = self::searchPath($value, $array);
            if (is_array($path) && count($path) > 0) {
                $results[] = $path;
                unset($array[$path[0]]);
                $results = array_merge($results, self::search_r($value, $array));
            } else {

            }
        }
        return $results;
    }

    /**
     * @static
     * @param $arr
     * @param $path
     * @return null
     */
    public static function getByPath($arr, $path)
    {
        if (!$path)
            return null;
        $segments = is_array($path) ? $path : explode('/', $path);
        $cur =& $arr;
        foreach ($segments as $segment) {
            if (!isset($cur[$segment]))
                return null;

            $cur = $cur[$segment];
        }
        return $cur;
    }

    /**
     * @static
     * @param $arr
     * @param $path
     * @param $value
     * @return null
     * $value = array_get($arr, 'this/is/the/path');
     *  $value = array_get($arr, array('this', 'is', 'the', 'path'));
     *  array_set($arr, 'here/is/another/path', 23);
     */
    public static function setByPath(&$arr, $path, $value)
    {
        if (!$path)
            return null;

        $segments = is_array($path) ? $path : explode('/', $path);
        $cur =& $arr;
        foreach ($segments as $segment) {
            if (!isset($cur[$segment]))
                $cur[$segment] = array();
            $cur =& $cur[$segment];
        }
        $cur = $value;
    }

    /**
     * @param array $data
     * @param array $all
     * @param array $group
     * @param null $val
     * @param int $i
     * @throws CException
     * @return array
     * 返回多维数组的 可能组合（不是排列）
     * @see  http://www.farinspace.com/php-array-combinations/
     * js  版的实现 用在商品规格组合上：
     * @see  http://codereview.stackexchange.com/questions/7001/better-way-to-generate-all-combinations
     * 这个也牛啊 ：
     * http://stackoverflow.com/questions/9422386/lazy-cartesian-product-of-arrays-arbitrary-nested-loops
     */
   public static  function combos($data, $all = array(), $group = array(), $val = null, $i = 0)
    {
        if (isset($val)) {
            array_push($group, $val);
        }
        if ($i >= count($data)) {
            array_push($all, $group);
        } else {
            foreach ($data[$i] as $v) {
               // ArrayUtil::combos($data, &$all, $group, $v, $i + 1);
                ArrayUtil::combos($data, $all, $group, $v, $i + 1);
            }
        }
        throw new CException("实现未完成！");
        return $all;
    }



}

///----------------------------------------------------------------------------------
function search($array, $key, $value)
{
    $results = array();

    search_r($array, $key, $value, $results);

    return $results;
}

function search_r($array, $key, $value, &$results)
{
    if (!is_array($array))
        return;

    if ($array[$key] == $value)
        $results[] = $array;

    foreach ($array as $subarray)
        search_r($subarray, $key, $value, $results);
}

function search2($array, $key, $value)
{
    $results = array();
    if (is_array($array)) {
        if (isset($array[$key]) && $array[$key] == $value)
            $results[] = $array;

        foreach ($array as $subarray)
            $results = array_merge($results, search($subarray, $key, $value));
    }
    return $results;
}

/**
 * Searches haystack for needle and
 * returns an array of the key path if
 * it is found in the (multidimensional)
 * array, FALSE otherwise.
 *
 * @mixed array_searchRecursive ( mixed needle,
 * array haystack [, bool strict[, array path]] )
 */

function array_searchRecursive($needle, $haystack, $strict = false, $path = array())
{
    if (!is_array($haystack)) {
        return false;
    }

    foreach ($haystack as $key => $val) {
        if (is_array($val) && $subPath = array_searchRecursive($needle, $val, $strict, $path)) {
            $path = array_merge($path, array($key), $subPath);
            return $path;
        } elseif ((!$strict && $val == $needle) || ($strict && $val === $needle)) {
            $path[] = $key;
            return $path;
        }
    }
    return false;
}




