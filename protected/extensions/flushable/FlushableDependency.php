<?php
/**
 * FlushableDependency class file.
 *
 * This dependency can be used to flush an item from the cache.
 * Therefore it uses another cache value to indicate that cached
 * data/content has changed. This allows to invalidate cached
 * DB data without another query.
 *
 * How to use the dependency:
 *
 *      Yii::import('ext.flushable.FlushableDependency');
 *      $dependency=new FlushableDependency($id,'Post');
 *      Post::model()->cache(3600,$dependency)->findByPk($id);
 *
 * How to update the dependency:
 *
 *      Yii::import('ext.flushable.FlushableDependency');
 *      FlushableDependency::flushItem($id,'Post');
 *
 * You can put such a line into the afterSave() method of your record
 * or call flushItem() from any other place where you update the
 * record.
 *
 * The dependency can also be used to flush fragments/pages from the
 * cache. In this case you may want to leave away the second parameter.
 *
 * If you don't want to add Yii::import() everywhere, you can also
 * add these calls to the top of your class file or add it to the
 * 'import' section of your main.php configuration file.
 *
 * @author Michael Härtl <haertl.mike@gmail.com>
 * @version 1.1.1
 */
class FlushableDependency extends CCacheDependency
{
    /**
     * @var mixed $id a unique identifier for the cached content (e.g. the Pk)
     */
    public $id;

    /**
     * @var mixed $type an optional type specifier, like the name of a model.
     * Allows to use the same $id among different $types.
     */
    public $type;

    /**
     * Constructor.
     * @var mixed $id a unique identifier for the cached content (e.g. the Pk)
     * @var mixed $type an optional type specifier, like the name of a model.
     */
    public function __construct($id=null,$type=null)
    {
        $this->type=$type;
        $this->id=$id;
    }

    /**
     * Generates the data needed to determine if dependency has been changed.
     * This method returns the records last update time from the cache or null.
     * @return mixed the data needed to determine if dependency has been changed.
     */
    protected function generateDependentData()
    {
        if($this->id===null)
            throw new CException('FlushableDependency requires id');

        return Yii::app()->cache->get(self::createKey($this->id,$this->type));
    }

    /**
     * @var mixed $id a unique identifier for the cached content (e.g. the Pk)
     * @var mixed $type an optional type specifier, like the name of a model.
     * @param int how long to store the update information in cache. Should be longer than the record cache time.
     */
    public static function flushItem($id,$type=null,$expires=3600)
    {
        Yii::app()->cache->set(self::createKey($id,$type), (string)microtime(), $expires);
    }

    /**
     * @var mixed $id a unique identifier for the cached content (e.g. the Pk)
     * @var mixed $type an optional type specifier, like the name of a model.
     * @return string the cache key for this id/type combination
     */
    public static function createKey($id,$type=null)
    {
        return '__flushableDependency:'.($type===null ? $id : "$type:$id");
    }
}
