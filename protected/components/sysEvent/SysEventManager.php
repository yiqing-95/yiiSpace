<?php
/**
 * Created by JetBrains PhpStorm.
 * User: yiqing
 * Date: 12-9-26
 * Time: 下午11:28
 * To change this template use File | Settings | File Templates.
 * ----------------------------------------------------------------------
 * 事件跟监听器对应关系可以是多对多！ qt的信号和槽机制更加复杂
 * 注意事件传播可能引起的死循环 尽量不要在处理器中触发事件；
 * 还有多对多关系的建立 可以用yii的扩展 但删除只能手动。假设数据库没有开启级联删除，
 * 可以使用原始sql 用EasyQuery 或者用DynamicActiveRecord！
 * ----------------------------------------------------------------------
 * 还有人建议多对多关系最好避免 建立一个中间表但带有id 这样通过through 同样可以达到
 * 多对多效果
 * ----------------------------------------------------------------------
 */

Yii::import('ext.flushable.FlushableDependency');

class SysEventManager
{
    const REGISTRY_CACHE_KEY = 'event_listener_registry';

    /**
     * @param string $eventName
     * @param string $fromModule
     * @return int|bool the event id
     */
    static public function publishEvent($eventName = '', $fromModule = '')
    {

        $existOne = SysEvent::model()->findByAttributes(
            array('from_module' => $fromModule, 'action' => $eventName)
        );
        if ($existOne == null) {
            $sysEvent = new SysEvent();
            $sysEvent->from_module = $fromModule;
            $sysEvent->action = $eventName;
            if ($sysEvent->save()) {
                //invalidate the cache
                   FlushableDependency::flushItem(self::REGISTRY_CACHE_KEY, null, 86400 * 30);
                return $sysEvent->id;
            }
        } else {
            return $existOne->id;
        }
    }

    /**
     * @static
     * @param string $eventName
     * @param string $fromModule
     * @return bool|int event id or  false, if false  means not exists!
     */
    static public function lookupEvent($eventName = '', $fromModule = '')
    {
        $existOne = SysEvent::model()->findByAttributes(
            array('from_module' => $fromModule, 'action' => $eventName)
        );
        if ($existOne == null) {
            return false;
        } else {
            return $existOne->id;
        }
    }

    /**
     * @static
     * @param string $eventName
     * @param string $fromModule
     * @return bool
     */
    static public function revokeEvent($eventName = '', $fromModule = '')
    {
        $sysEvent = SysEvent::model()->findByAttributes(
            array('from_module' => $fromModule, 'action' => $eventName)
        );
        //var_dump($sysEvent);
        if ($sysEvent !== null) {
            $result = $sysEvent->delete();

            //invalidate the cache
            if ($result)    FlushableDependency::flushItem(self::REGISTRY_CACHE_KEY, null, 86400 * 30);

            return $result;
        } else {
            return false;
        }

    }

    /**
     * @static
     * @param string $module
     * @return bool
     */
    static public function revokeAllEventsOfModule($module = '')
    {
        $sysEvents = SysEvent::model()->findAllByAttributes(
            array('from_module' => $module)
        );
        //var_dump($sysEvent);
        if (!empty($sysEvents)) {
            foreach ($sysEvents as $sysEvent) {
                $sysEvent->delete();
            }
            //invalidate the cache
               FlushableDependency::flushItem(self::REGISTRY_CACHE_KEY, null, 86400 * 30);

        } else {
            return false;
        }

    }

    /**
     * @static
     * @param string $name
     * @param string $class
     * @param string $file
     * @param string $eval
     * @param string $fromModule
     * @return bool|int listener id
     */
    static public function publishListener($name = '', $class = '', $file = '', $eval = '', $fromModule = '')
    {
        if (empty($eval)) {
            $existOne = SysEventListener::model()->findByAttributes(
                array('name' => $name, 'class' => $class, 'file' => $file, 'from_module' => $fromModule),
                'eval IS NULL'
            );
        } else {
            $existOne = SysEventListener::model()->findByAttributes(
                array('name' => $name, 'class' => $class, 'file' => $file, 'eval' => $eval, 'from_module' => $fromModule)
            );
        }
        if ($existOne == null) {
            $sysEventListener = new SysEventListener();
            $sysEventListener->from_module = $fromModule;
            $sysEventListener->name = $name;
            $sysEventListener->class = $class;
            $sysEventListener->file = $file;
            $sysEventListener->eval = $eval;

            if ($sysEventListener->save()) {
                $result = $sysEventListener->id;

                //invalidate the cache
                   FlushableDependency::flushItem(self::REGISTRY_CACHE_KEY, null, 86400 * 30);

                return $result;
            } else {
                WebUtil::printCharsetMeta();
                print_r($sysEventListener->getErrors());
            }

        } else {
            return $existOne->id;
        }

    }


    /**
     * @static
     * @param string $name
     * @param string $class
     * @param string $file
     * @param string $eval
     * @param string $fromModule
     * @return bool
     */
    static public function revokeListener($name = '', $class = '', $file = '', $eval = '', $fromModule = '')
    {
        if (empty($eval)) {
            $sysEventListener = SysEventListener::model()->findByAttributes(
                array('name' => $name, 'class' => $class, 'file' => $file, 'from_module' => $fromModule),
                'eval IS NULL'
            );
        } else {
            $sysEventListener = SysEventListener::model()->findByAttributes(
                array('name' => $name, 'class' => $class, 'file' => $file, 'eval' => $eval, 'from_module' => $fromModule)
            );
        }
        if ($sysEventListener !== null) {
            $result = $sysEventListener->delete();

            //invalidate the cache
            if ($result)    FlushableDependency::flushItem(self::REGISTRY_CACHE_KEY, null, 86400 * 30);

            return $result;
        } else {
            return false;
        }

    }

    /**
     * @static
     * @param string $module
     */
    static public function revokeAllListenersOfModule($module = '')
    {

        $sysEventListeners = SysEventListener::model()->findAllByAttributes(
            array('from_module' => $module)
        );
        foreach ($sysEventListeners as $sysEventListener) {
            $sysEventListener->delete();
        }
        //invalidate the cache
           FlushableDependency::flushItem(self::REGISTRY_CACHE_KEY, null, 86400 * 30);
    }


    /**
     * @static
     * @param $event
     * @param $listener
     * @return bool
     */
    static public function registerEventListener($event, $listener)
    {
        $sysEvent = SysEvent::model()->findByPk($event);
        $activeRecordRelation = array(
            'class' => 'ext.yiiext.behaviors.activerecord-relation.EActiveRecordRelationBehavior',
        );
        $bhv = $sysEvent->attachBehavior('activeRecord_relation', $activeRecordRelation);
        $sysEvent->listeners = array_merge($sysEvent->listeners, array($listener));
        $result = $sysEvent->save();
        //invalidate the cache
         if($result)  FlushableDependency::flushItem(self::REGISTRY_CACHE_KEY,86400*30);

        return $result;
    }


    /**
     * @static
     * @param $event
     * @param $listener
     * @return int
     */
    static public function unRegisterEventListener($event, $listener)
    {
        $relation = DynamicActiveRecord::forTable('sys_event2listener');
        $result = $relation->deleteAllByAttributes(array(
            'event_id' => $event,
            'listener_id' => $listener
        ));

        //invalidate the cache
        if ($result)    FlushableDependency::flushItem(self::REGISTRY_CACHE_KEY, null, 86400 * 30);

        return $result;
    }

    //-------------------------------------------------------------------------------------------

    /**
     * @static
     * @param GEvent $e
     */
    static public function fireEvent(GEvent $e)
    {

        $registry = self::getEventListenerRegistry();

        $eventRegistryKey = $e->fromModule.'#'.$e->action;

        if(isset($registry[$eventRegistryKey])){
            $listeners = $registry[$eventRegistryKey];
            foreach($listeners as  $listener) {
                if(!empty($listener['file']) && !empty($listener['class']) && file_exists($listener['file'])) {
                    if(!class_exists($listener['class'])){
                        require_once($listener['file']);
                    }
                    $listenerObj = new $listener['class']();
                    if($listenerObj instanceof ISysEventListener){
                        $listenerObj->handleEvent($e);
                    }
                }else if(!empty($listener['eval'])) {
                   // eval($listener['eval']);
                    $e->evaluateExpression($listener['eval'],array('event'=>$e));
                }
            }
        }
    }

    /**
     * Cache the events  and listeners.
     * @return an array with all  events and listeners which have attached together
     * will not return any unbound  event and listener combinations
     * ...................................................................
     * should cache the result
     * ...................................................................
     */
    static public function getEventListenerRegistry()
    {
       //FlushableDependency::flushItem(self::REGISTRY_CACHE_KEY);

        $registry = array();
        $registry = Yii::app()->cache->get(self::REGISTRY_CACHE_KEY);
        if ($registry === false) {
            $events = SysEvent::model()->with('listeners')->findAll();
            foreach ($events as $event) {
                if (!empty($event->listeners)) {
                    $registry[$event->from_module .'#'. $event->action] = array();
                    foreach ($event->listeners as $listener) {
                        $registry[$event->from_module .'#'. $event->action][] = array(
                            'name' => $listener->name,
                            'class' => $listener->class,
                            'file' => $listener->file,
                            'eval' => $listener->eval,
                            'from_module' => $listener->from_module,
                        );
                    }
                }
            }
            $dependency = new FlushableDependency(self::REGISTRY_CACHE_KEY);
             Yii::app()->cache->set(self::REGISTRY_CACHE_KEY,  $registry,86400,$dependency);
        }
        return $registry;
    }

    //-------------------------------------------------------------------------------------------
}

//=======================================================================
/**
 * we can fake the event source place by specify the fromModule param
 *
 */
class GEvent extends CEvent
{
    /**
     * @var int
     */
    public $actor = 0;
    /**
     * @var string
     */
    public $action = '';
    /**
     * @var
     */
    public $object;
    /**
     * @var string
     */
    public $fromModule = '';


    /**
     * @param mixed|null $actor  action 's author
     * @param mixed|null $action  action name
     * @param $object             action object
     * @param null $params        extrasParams
     * @param string $fromModule default will be the current moduleId
     *         (you should not raise a event which do not belong to you :) )
     */
    public function __construct($actor, $action, $object, $params = null, $fromModule = '')
    {
        $senderModule = $this->getCurrentModule();
        parent::__construct($senderModule, $params);

        $this->actor = $actor;
        $this->action = $action;
        $this->object = $object;
        $this->fromModule = ($fromModule == '') ? $senderModule->getId() : $fromModule;
    }

    /**
     * @return CModule
     */
    public  function  getCurrentModule()
    {
        return Yii::app()->getController()->getModule();
    }

}
