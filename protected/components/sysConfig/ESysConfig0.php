<?php
/**
 * Created by JetBrains PhpStorm.
 * User: yiqing
 * Date: 12-10-11
 * Time: 下午8:48
 * To change this template use File | Settings | File Templates.
 */

$dir = dirname(__FILE__);
//$alias = md5($dir);
define('ESYS_CONFIG', md5($dir));
Yii::setPathOfAlias(ESYS_CONFIG, $dir);

class ESysConfig0 extends CApplicationComponent implements IteratorAggregate, ArrayAccess, Countable
{

    public static function addIncludes()
    {

    }

    public static function removeIncludes()
    {

    }

    /**
     * @static
     * @return ESysConfig
     */
    public static function instance()
    {
        $componentId = 'dynaParams';

        if (!isset(Yii::app()->{$componentId})) {
            $config = array(
                'class' => __CLASS__
            );
            Yii::app()->setComponents(array(
                $componentId => $config,
            ), false);
        }
        return Yii::app()->{$componentId};
    }

    /**
     * @var string path where the params saved
     * the default this under './data/params.inc'
     * Make sure this file is writable by the Web server process if the authorization
     * needs to be changed.
     * @see loadFromFile
     * @see saveToFile
     */
    public $paramsFile;

    /**
     * @var bool
     * is auto save to the persistent PHP file
     * not realized (just add $this->save to every write operate !)
     */
    public $autoSave = false;

    /**
     * @var array
     */
    private $_params = array(); // paramName => paramValue


    /**
     * @var array
     * array(
     *   'section1'=>'',
     *   'section2'=>'',
     * )
     */
    public $formConfigs = array();

    /**
     * Initializes the application component.
     * This method overrides parent implementation by loading the params data
     * from PHP script.
     */
    public function init()
    {
        parent::init();
        if ($this->paramsFile === null) {
            $this->paramsFile = Yii::getPathOfAlias(ESYS_CONFIG . '.data') . DIRECTORY_SEPARATOR . 'params.inc';
        }
        $this->load();
    }

    /**
     * Returns the param with the specified name.
     * @param string $name the name of the item
     * @param null $default
     * @return string  the param. Null if the item cannot be found.
     */
    public function get($name, $default = null)
    {
        return isset($this->_params[$name]) ? $this->_params[$name] : $default;
    }


    /**
     * @param $paramName
     * @param $value
     */
    public function set($paramName, $value)
    {
        $this->_params[$paramName] = $value;
    }


    public function getAll(){
        return $this->_params ;
    }

    /**
     * @param $name
     * @param $value
     * @return array
     * @throws CException
     */
    public function addParam($name, $value)
    {
        if (isset($this->_params[$name]))
            throw new CException(Yii::t('app', 'Unable to add an param whose name is existing '));
        return $this->_params[$name] = $value;
    }

    /**
     * Removes the specified param
     * @param string $name the name of the param to be removed
     * @return boolean whether the item exists in the storage and has been removed
     */
    public function removeParam($name)
    {
        if (isset($this->_params[$name])) {
            unset($this->_params[$name]);
            return true;
        } else
            return false;
    }

    /**
     * Returns the param with the specified name.
     * @param string $name the name of the item
     * @return string  the param. Null if the item cannot be found.
     */
    public function getParam($name)
    {
        return isset($this->_params[$name]) ? $this->_params[$name] : null;
    }

    /**
     * @param $paramName
     * @param $value
     */
    public function setParam($paramName, $value)
    {
        $this->_params[$paramName] = $value;
    }

    /**
     * Saves params  into persistent storage.
     */
    public function save()
    {
        $this->saveToFile($this->_params, $this->paramsFile);
    }

    /**
     * Loads params data.
     */
    public function load()
    {
        $this->clearAll();
        $this->_params = $this->loadFromFile($this->paramsFile);
    }

    /**
     * Removes all authorization data.
     */
    public function clearAll()
    {
        $this->_params = array();
    }


    /**
     * Loads the app params from a PHP script file.
     * @param string $file the file path.
     * @return array the params data
     * @see saveToFile
     */
    protected function loadFromFile($file)
    {
        if (is_file($file))
            return require($file);
        else
            return array();
    }

    /**
     * Saves the app params data to a PHP script file.
     * @param array $data the changeable params
     * @param string $file the file path.
     * @see loadFromFile
     * 这里应该用 互斥锁 ！ 有多余时间在做
     */
    protected function saveToFile($data, $file)
    {
        file_put_contents($file, "<?php\nreturn " . var_export($data, true) . ";\n");
    }

//........................below make the params can be access as array.....................................
    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Retrieve an external iterator
     * @link http://php.net/manual/en/iteratoraggregate.getiterator.php
     * @return Traversable An instance of an object implementing <b>Iterator</b> or
     * <b>Traversable</b>
     */
    public function getIterator()
    {
        return new ESysConfigIterator($this->_params);
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Whether a offset exists
     * @link http://php.net/manual/en/arrayaccess.offsetexists.php
     * @param mixed $offset <p>
     * An offset to check for.
     * </p>
     * @return boolean true on success or false on failure.
     * </p>
     * <p>
     * The return value will be casted to boolean if non-boolean was returned.
     */
    public function offsetExists($offset)
    {
        return isset($this->_params[$offset]) || array_key_exists($offset, $this->_params);
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Offset to retrieve
     * @link http://php.net/manual/en/arrayaccess.offsetget.php
     * @param mixed $offset <p>
     * The offset to retrieve.
     * </p>
     * @return mixed Can return all value types.
     */
    public function offsetGet($offset)
    {
        if (isset($this->_params[$offset]))
            return $this->_params[$offset];
        else
            return null;
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Offset to set
     * @link http://php.net/manual/en/arrayaccess.offsetset.php
     * @param mixed $offset <p>
     * The offset to assign the value to.
     * </p>
     * @param mixed $value <p>
     * The value to set.
     * </p>
     * @return void
     */
    public function offsetSet($offset, $value)
    {
        if ($offset === null)
            $this->_params[] = $value;
        else
            $this->_params[$offset] = $value;
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Offset to unset
     * @link http://php.net/manual/en/arrayaccess.offsetunset.php
     * @param mixed $offset <p>
     * The offset to unset.
     * </p>
     * @return void
     */
    public function offsetUnset($offset)
    {
        if (isset($$this->_params[$offset])) {
            $value = $$this->_params[$offset];
            unset($$this->_params[$offset]);
            return $value;
        } else {
            // it is possible the value is null, which is not detected by isset
            unset($$this->_params[$offset]);
            return null;
        }
    }

    /**
     * (PHP 5 &gt;= 5.1.0)<br/>
     * Count elements of an object
     * @link http://php.net/manual/en/countable.count.php
     * @return int The custom count as an integer.
     * </p>
     * <p>
     * The return value is cast to an integer.
     */
    public function count()
    {
        return count($this->_params);
    }
}

//==========================================================================================================
/**
 * modified from CMap
 */
class ESysConfigIterator implements Iterator
{
    /**
     * @var array the data to be iterated through
     */
    private $_d;
    /**
     * @var array list of keys in the map
     */
    private $_keys;
    /**
     * @var mixed current key
     */
    private $_key;

    /**
     * Constructor.
     * @param array $data the data to be iterated through
     */
    public function __construct(&$data)
    {
        $this->_d =& $data;
        $this->_keys = array_keys($data);
        $this->_key = reset($this->_keys);
    }

    /**
     * Rewinds internal array pointer.
     * This method is required by the interface Iterator.
     */
    public function rewind()
    {
        $this->_key = reset($this->_keys);
    }

    /**
     * Returns the key of the current array element.
     * This method is required by the interface Iterator.
     * @return mixed the key of the current array element
     */
    public function key()
    {
        return $this->_key;
    }

    /**
     * Returns the current array element.
     * This method is required by the interface Iterator.
     * @return mixed the current array element
     */
    public function current()
    {
        return $this->_d[$this->_key];
    }

    /**
     * Moves the internal pointer to the next array element.
     * This method is required by the interface Iterator.
     */
    public function next()
    {
        $this->_key = next($this->_keys);
    }

    /**
     * Returns whether there is an element at current position.
     * This method is required by the interface Iterator.
     * @return boolean
     */
    public function valid()
    {
        return $this->_key !== false;
    }
}