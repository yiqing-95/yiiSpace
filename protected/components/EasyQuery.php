<?php

/**
 * @throws InvalidArgumentException
 *
 * --------------------------------------------------------------------------------
 * select(): specifies the SELECT part of the query
 *  selectDistinct(): specifies the SELECT part of the query and turns on the DISTINCT flag
 *  from(): specifies the FROM part of the query
 *   where(): specifies the WHERE part of the query
 *   join(): appends an inner join query fragment
 *   leftJoin(): appends a left outer join query fragment
 *   rightJoin(): appends a right outer join query fragment
 *   crossJoin(): appends a cross join query fragment
 *   naturalJoin(): appends a natural join query fragment
 *   group(): specifies the GROUP BY part of the query
 *    having(): specifies the HAVING part of the query
 *    order(): specifies the ORDER BY part of the query
 *    limit(): specifies the LIMIT part of the query
 *   offset(): specifies the OFFSET part of the query
 *   union(): appends a UNION query fragment
 * ..........................................................
 *   above method  can express at array format:
 *   array(
 *      'where' => 'id=:id',
 *      'limit'=>5 ,.......
 * )
 * ------------------------------------------------------------------------------------
 *  createInCondition 需要封装的 以后要做啊
 */

class EasyQuery
{


    /**
     * @var string
     */
    private $_tableName = '';

    /**
     * @var CDbConnection
     */
    protected $_db;

    /**
     * @var CDbCommand
     */
    protected $_lastCommand;

    /**
     * @var int
     * 执行查询方法时 使用的方式
     */
    protected $_fetchMode = PDO::FETCH_ASSOC;

    /**
     * @var EasyQuery[]
     */
    protected static $_instances;

    /**
     * @throws InvalidArgumentException
     * @param string|CActiveRecord $tableNameOrAr
     * @param null $db
     * @return \EasyQuery
     *
     */
    public function __construct($tableNameOrAr , $db = null)
    {
        if (is_string($tableNameOrAr)) {
            $this->_tableName = $tableNameOrAr;
        } else if ($tableNameOrAr instanceof CActiveRecord) {
            $this->_tableName = $tableNameOrAr->tableName();
        } else {
            throw new InvalidArgumentException(' argument should be string or instance of CActiveRecord ,your give .' . gettype($tableNameOrAr));
        }
        $this->_db = empty($db) ? Yii::app()->db : $db;
    }

    /**
     * @param int $fetchMode PDO::FETCH_XYZ
     * @return \EasyQuery
     */
    public function setFetchMode($fetchMode = PDO::FETCH_ASSOC)
    {
        $this->_fetchMode = $fetchMode;
        return $this;
    }

    /**
     * @return int
     */
    public function getFetchMode()
    {
        return $this->_fetchMode;
    }


    /**
     * @static
     * @throws InvalidArgumentException
     * @param $tableNameOrAr
     * @return EasyQuery
     */
    public static function instance($tableNameOrAr)
    {

        if (is_string($tableNameOrAr)) {
            $tableName = $tableNameOrAr;
        } else if ($tableNameOrAr instanceof CActiveRecord) {
            $tableName = $tableNameOrAr->tableName();
        } else {
            throw new InvalidArgumentException(' argument should be string or instance of CActiveRecord ,
           your give is .' . gettype($tableNameOrAr));
        }

        if (!isset(self::$_instances))
            self::$_instances = array();

        if (!isset(self::$_instances[$tableName])) {

            self::$_instances[$tableName] = new self($tableNameOrAr);
        }

        return self::$_instances[$tableName];
    }

    /**
     * @param array|string $criteria if it 's string it stand for the queryBuilder' condition part
     * @param array $params
     * @return CDbDataReader
     * @see http://www.yiiframework.com/doc/guide/1.1/en/database.query-builder
     */
    public function query($criteria = array(), $params = array())
    {
        if(is_array($criteria)){
            if(!isset($criteria['select'])){
                $criteria['select'] = '*';
            }
            if (!isset($criteria['from'])) {
                $criteria['from'] = $this->_tableName;
            }
            // make  condition as where 's alias
            if(isset($criteria['condition'])){
                $criteria['where'] = $criteria['condition'];
                unset($criteria['condition']);
            }

            if(isset($criteria['params'])){
                $params = $criteria['params']; //ignore the second param
            }
        }elseif(is_string($criteria)){
            $_criteria = $criteria ; //save it
            $criteria = array();
            $criteria['select'] = '*';
            $criteria['from'] = $this->_tableName;
            $criteria['where'] = $_criteria;
        }

        $this->_lastCommand = $this->_db->createCommand($criteria);
        $this->_lastCommand->setFetchMode($this->_fetchMode);

        return $this->_lastCommand->query($params);
    }

    /**
     * @param array $criteria
     * @param array $params
     * @return array
     */
    public function queryAll($criteria = array(), $params = array())
    {
        //------------<<more then one method use this block consider as a method ----------------------------------------------------------------------
        if(is_array($criteria)){
            if(!isset($criteria['select'])){
                $criteria['select'] = '*';
            }
            if (!isset($criteria['from'])) {
                $criteria['from'] = $this->_tableName;
            }
            // make  condition as where 's alias
            if(isset($criteria['condition'])){
                $criteria['where'] = $criteria['condition'];
                unset($criteria['condition']);
            }

            if(isset($criteria['params'])){
                $params = $criteria['params']; //ignore the second param
            }
        }elseif(is_string($criteria)){
            $_criteria = $criteria ; //save it
            $criteria = array();
            $criteria['select'] = '*';
            $criteria['from'] = $this->_tableName;
            $criteria['where'] = $_criteria;
        }
        //------------<<more then one method use this block consider as a method />>----------------------------------------------------------------------

          $this->_lastCommand = $this->_db->createCommand($criteria);
        return $this->_lastCommand->queryAll($this->_fetchMode, $params);
    }

    /**
     * @param array $criteria
     * @param array $params
     * @return mixed
     */
    public function queryRow($criteria = array(), $params = array())
    {
        //------------<<more then one method use this block consider as a method ----------------------------------------------------------------------
        if(is_array($criteria)){
            if(!isset($criteria['select'])){
                $criteria['select'] = '*';
            }
            if (!isset($criteria['from'])) {
                $criteria['from'] = $this->_tableName;
            }
            // make  condition as where 's alias
            if(isset($criteria['condition'])){
                $criteria['where'] = $criteria['condition'];
                unset($criteria['condition']);
            }

            if(isset($criteria['params'])){
                $params = $criteria['params']; //ignore the second param
            }
        }elseif(is_string($criteria)){
            $_criteria = $criteria ; //save it
            $criteria = array();
            $criteria['select'] = '*';
            $criteria['from'] = $this->_tableName;
            $criteria['where'] = $_criteria;
        }
        //------------<<more then one method use this block consider as a method />>----------------------------------------------------------------------
         $this->_lastCommand = $this->_db->createCommand($criteria);
        return $this->_lastCommand->queryRow($this->_fetchMode, $params);
    }

    /**
     * @param string $column
     * @param array $criteria
     * @param array $params
     * @return array
     */
    public function queryColumn($column, $criteria = array(), $params = array())
    {

        if(is_array($criteria)){
            $criteria['select'] = $column;

            if (!isset($criteria['from'])) {
                $criteria['from'] = $this->_tableName;
            }
            // make  condition as where 's alias
            if(isset($criteria['condition'])){
                $criteria['where'] = $criteria['condition'];
                unset($criteria['condition']);
            }

            if(isset($criteria['params'])){
                $params = $criteria['params']; //ignore the second param
            }
        }elseif(is_string($criteria)){
            $_criteria = $criteria ; //save it
            $criteria = array();
            $criteria['select'] = $column;
            $criteria['from'] = $this->_tableName;
            $criteria['where'] = $_criteria;
        }
        //------------<<more then one method use this block consider as a method />>----------------------------------------------------------------------

        $this->_lastCommand = $this->_db->createCommand($criteria);
        return $this->_lastCommand->queryColumn($params);
    }

    /**
     * @param string $column
     * @param array $criteria
     * @param array $params
     * @return mixed
     */
    public function queryScalar($column, $criteria = array(), $params = array())
    {

        //------------<<more then one method use this block consider as a method ----------------------------------------------------------------------
        if(is_array($criteria)){
            $criteria['select'] = $column;

            if(!isset($criteria['select'])){
                $criteria['select'] = '*';
            }
            if (!isset($criteria['from'])) {
                $criteria['from'] = $this->_tableName;
            }
            // make  condition as where 's alias
            if(isset($criteria['condition'])){
                $criteria['where'] = $criteria['condition'];
                unset($criteria['condition']);
            }
            if(isset($criteria['params'])){
                $params = $criteria['params']; //ignore the second param
            }
        }elseif(is_string($criteria)){
            $_criteria = $criteria ; //save it
            $criteria = array();
            $criteria['select'] = $column;
            $criteria['from'] = $this->_tableName;
            $criteria['where'] = $_criteria;
        }
        //------------<<more then one method use this block consider as a method />>----------------------------------------------------------------------

        $this->_lastCommand = $this->_db->createCommand($criteria);
        return $this->_lastCommand->queryScalar($params);
    }

    /**
     * @param $columns
     * @param array $criteria
     * @param array $params
     * @param bool $asDataReader
     * @return CDbDataReader|array
     */
    public function queryDistinct($columns, $criteria = array(), $params = array(), $asDataReader = false)
    {
        $criteria['select'] = $columns;

        if (!isset($criteria['from'])) {
            $criteria['from'] = $this->_tableName;
        }
           // make  condition as where 's alias
        if(isset($criteria['condition'])){
            $criteria['where'] = $criteria['condition'];
            unset($criteria['condition']);
        }

        $criteria['distinct'] = true;
        if ($asDataReader == true) {
            return $this->_db->createCommand($criteria)->query($params);
        } else {
            return $this->_db->createCommand($criteria)->queryAll($this->_fetchMode, $params);
        }
    }

    /**
     * 数据操作系列方法
     */
    /**
     * @param $column
     * @param array $criteria
     * @param array $params
     * @return void
     */
    public function incColumn($column, $criteria = array(), $params = array())
    {

    }

    /**
     * @param array $columns
     * @return int
     */
    public function insert($columns)
    {
        return $this->_db->createCommand()->insert($this->_tableName, $columns);
    }

    /**
     * @param array $columns
     * @param string $conditions
     * @param array $params
     * @return int
     */
    public function update($columns, $conditions = '', $params = array())
    {
        return $this->_db->createCommand()->update($this->_tableName, $columns, $conditions, $params);
    }

    /**
     * @param string $conditions
     * @param array $params
     * @return int
     */
    public function delete($conditions = '', $params = array())
    {
        return $this->_db->createCommand()->delete($this->_tableName, $conditions, $params);
    }

    /**
     * 一下是 修改表结构系列
     */
    /**
     * @param string $newName
     * @return int
     */
    public function renameTable($newName)
    {
        $rtn = $this->_db->createCommand()->renameTable($this->_tableName, $newName);
        $this->_tableName = $newName;
        return $rtn;
    }

    /**
     * @return int
     */
    public function dropTable()
    {
        return $this->_db->createCommand()->dropTable($this->_tableName);
    }

    /**
     * @return int
     */
    public function truncateTable()
    {
        return $this->_db->createCommand()->truncateTable($this->_tableName);
    }

    /**
     * @param string $column
     * @param string $type
     * @return int
     */
    public function  addColumn($column, $type)
    {
        return $this->_db->createCommand()->addColumn($this->_tableName, $column, $type);
    }

    /**
     * @param string $column
     * @return int
     */
    public function dropColumn($column)
    {
        return $this->_db->createCommand()->dropColumn($this->_tableName, $column);
    }


    /**
     * @param string $name
     * @param string $newName
     * @return int
     */
    public function  renameColumn($name, $newName)
    {
        return $this->_db->createCommand()->renameColumn($this->_tableName, $name, $newName);
    }

    /**
     * @param string $column
     * @param string $type
     * @return int
     */
    public function  alterColumn($column, $type)
    {
        return $this->_db->createCommand()->alterColumn($this->_tableName, $column, $type);
    }

    /**
     * @param string $name
     * @return int
     */
    public function dropForeignKey($name)
    {
        return $this->_db->createCommand()->dropForeignKey($name, $this->_tableName);
    }

    /**
     * @param string $name
     * @param string $column
     * @param bool $unique
     * @return int
     */
    public function createIndex($name, $column, $unique = false)
    {
        return $this->_db->createCommand()->createIndex($name, $this->_tableName, $column, $unique);
    }

    /**
     * @param string $name
     * @return int
     */
    public function dropIndex($name)
    {
        return $this->_db->createCommand()->dropIndex($name, $this->_tableName);
    }

    /**
     * @param array $criteria
     * @param array|string $select
     * @param array $config
     * @param string $keyField
     * @return CSqlDataProvider
     */
    public function getSqlDataProvider($criteria = array(), $select = '*', $config = array(), $keyField = 'id')
    {
        $criteria['select'] = $select;
        if (!isset($criteria['from'])) {
            $criteria['from'] = $this->_tableName;
        }
        $dbCmd =  $this->_db->createCommand($criteria);
        $tmpSql = $dbCmd->getText();
        $params = $dbCmd->params;

        $config['totalItemCount'] = $this->countBySql($tmpSql,$params);
        $config['keyField'] = $keyField;

        return new CSqlDataProvider($tmpSql,$config);
    }

    /**
     * @param $sql
     * @param array $params
     * @return int|mixed
     * 现在只能处理 非嵌套的union
     * 对于嵌套并 无能为力 所以不要写太复杂的sql哦！
     * ---------------------------------------------------------------
     * 当是union all 时 需要正则啊
     * Select * From (Select * From t_stu s Where s.gender = 'f'Order By grade Desc) Where Rownum <=5
     * Union
     *  Select * From (Select * From t_stu s Where s.gender = 'm'Order By grade Desc) Where Rownum <=5;
     *
     * 这种情况还不如改写sql为
     * select * from (
     *    subSqlString              UNION / UNION ALL  subSqlString2
     * )
     * ---------------------------------------------------------------
     */
    protected function countBySql($sql,$params=array())
    {
        $parts = explode('UNION',$sql);
        if(count($parts)>1){
            $count = 0;
            foreach($parts as $selectSql){
                $count += $this->countBySql($selectSql,$params);
            }
            return $count;
        }else{
              $selectStr = trim($sql); //紧身以下
           $selectStr = substr_replace($selectStr, ' COUNT(*) ', 6, stripos($selectStr,
                                                                         'FROM') - 6);
            $selectStr = preg_replace('~ORDER\s+BY.*?$~sDi', '', $selectStr);

        return  $this->_db->createCommand($selectStr)->queryScalar($params);
        }
    }

    //=================下面的以后再改=========================================================================


    /**
     * Returns a DataProvider populated by the result after a find operation
     * The 'models' are returned as array, not as objects
     * @see EDMSDataProvider.php
     *
     * Uses the preconfigured connection from the EDMSBehavior
     *
     * @param array $criteria
     * @param array $select the attributes to select
     * @param array $config the dataProvider config
     * @return EDMSDataProvider
     */
    public function getArrayDataProvider($criteria = array(), $select = array(), $config = array())
    {
        $cursor = $this->findCursor($criteria, $select);
        return new EDMSDataProvider($cursor, $config);
    }

    /**
     * Returns an array of objects of the result after a find operation
     * The 'models' are returned as PHP standard objects 'stdClass'
     * @see EDMSDataProvider.php
     *
     * Uses the preconfigured connection from the EDMSBehavior
     *
     * @param array $criteria
     * @param array $select the attributes to select
     * @param array $config the dataProvider config
     * @return EDMSDataProvider
     */
    public function getObjectDataProvider($criteria = array(), $select = array(), $config = array())
    {
        $cursor = $this->findCursor($criteria, $select);
        return new EDMSDataProvider($cursor, $config, 'stdClass');
    }

    /**
     * Returns an array of objects of the result after a find operation
     * The 'models' are returned as objects of the specified modelClassname
     * @see EDMSDataProvider.php
     *
     * Usage for yiimongodbsuite models or CFormModel
     *
     * Uses the preconfigured connection from the EDMSBehavior
     *
     * @param string $modelClassname
     * @param array $criteria
     * @param array $select the attributes to select
     * @param array $config the dataProvider config
     * @return EDMSDataProvider
     */
    public function getModelDataProvider($modelClassname, $criteria = array(), $select = array(), $config = array())
    {
        $cursor = $this->findCursor($criteria, $select);
        return new EDMSDataProvider($cursor, $config, $modelClassname);
    }


    /**
     * Uses the preconfigured connection from the EDMSBehavior to find one record
     *
     * @param arrqay $criteria
     * @param array $select
     * @return mixed array the record or false
     */
    public function findOne($criteria, $select = array())
    {
        //always add the '_id'
        if (!empty($select) && !in_array('_id', $select))
            array_push($select, '_id');

        $cursor = $this->getCollection()->findOne($criteria, $select);

        return !empty($cursor) ? $cursor : false;
    }


    /**
     * Distinct query for a specific attribute in the collection
     *
     * @param string $attribute
     * @param array $criteria additional criteria
     * @return array
     */
    public function findDistinct($attribute, $criteria = array(), $sort = self::SORT_ASC)
    {
        $command = array('distinct' => $this->_collectionName, 'key' => $attribute);

        if (!empty($criteria))
            array_push($command, $criteria);

        $result = $this->getDb()->command($command);
        $items = $result['ok'] == 1 ? $result['values'] : null;

        if (isset($sort) && !empty($items)) {
            if ($sort === self::SORT_ASC)
                sort($items);
            else
                if ($sort === self::SORT_DESC)
                    rsort($items);
        }

        return $items;
    }


    /**
     * If no document matches $criteria,
     * a new document will be created from $criteria and $values
     *
     * @link http://php.net/manual/de/mongocollection.update.php
     *
     * @param array $criteria
     * @param array $values
     * @param array $options
     * @return mixed depends on 'safe' option
     */
    public function upsert($criteria, $values, $options = array())
    {
        if (empty($values))
            return false;

        $options = array_merge($options, array('upsert' => true));
        return $this->getCollection()->update($criteria, $values, $options);
    }


    /**
     * @link http://www.php.net/manual/de/mongocollection.remove.php
     *
     *
     * @param array $criteria
     * @param boolean $justOne
     * @param array $options
     * @return mixed depends on 'safe' option
     */
    public function remove($criteria, $justOne = false, $options = array())
    {
        $options['justOne'] = $justOne;
        return $this->getCollection()->remove($criteria, $options);
    }


    /**
     * Return the public properties of an object as array
     *
     * @param mixed $model
     * @return array
     */
    public static function modelToArray($model)
    {
        if (method_exists($model, 'getAttributes'))
            return $model->getAttributes(); //set model attributes with no validation
        else
        { //assign the values to the public properties
            $class = new ReflectionClass(get_class($model));
            $attributes = array();
            foreach ($class->getProperties() as $property)
            {
                if ($property->isPublic() && !$property->isStatic()) {
                    $key = $property->getName();
                    $attributes[$key] = $model->$key;
                }
            }

            return $attributes;
        }
    }


    /**
     * Assign attributes to the public properties of a object / model
     *
     * @param mixed $attributes
     * @param mixed $className
     * @return
     */
    public static function arrayToModel($attributes, $className)
    {
        $model = new $className;

        if (method_exists($model, 'setAttributes'))
            $model->setAttributes($attributes, false); //set model attributes with no validation
        else
        { //assign the values to the public properties
            $class = new ReflectionClass(get_class($model));
            foreach ($model->getProperties() as $property)
            {
                $key = $property->getName();
                if (array_key_exists($key, $attributes) &&
                    $property->isPublic() && !$property->isStatic()
                )
                    $model->$key = $attributes[$key];
            }
        }

        return $model;
    }

}