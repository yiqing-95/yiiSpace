<?php
/**
 * Created by JetBrains PhpStorm.
 * User: yiqing
 * Date: 12-8-17
 * Time: 下午12:28
 * To change this template use File | Settings | File Templates.
 */
class EPhpCalendar extends CWidget
{

    /**
     * @var PHPCalendar
     */
    public $calendar ;

    /**
     * Any requests to set or get attributes or call methods on this class that
     * are not found are redirected to the PHPCalender object.
     * @param string $name
     * @throws CException
     * @return mixed
     * @internal param \the $string attribute name
     */
    public function __get($name) {
        try {
            return parent::__get($name);
        } catch (CException $e) {
            $getter = 'get'.$name;
            if(method_exists($this->calendar, $getter))
                return $this->calendar->$getter();
            else
                throw $e;
        }
    }

    /**
     * Any requests to set or get attributes or call methods on this class that
     * are not found are redirected to the PHPCalender object.
     * @param string $name
     * @param mixed $value
     * @throws CException
     * @return mixed
     * @internal param \the $string attribute name
     */
    public function __set($name, $value) {
        try {
            return parent::__set($name, $value);
        } catch (CException $e) {
            $setter = 'set'.$name;
            if(method_exists($this->calendar, $setter))
                $this->calendar->$setter($value);
            else
                throw $e;
        }
    }

    /**
     * Any requests to set or get attributes or call methods on this class that
     * are not found are redirected to the PHPCalender object.
     * @param string $name
     * @param array $parameters
     * @throws CException
     * @return mixed
     * @internal param \the $string method name
     */
    public function __call($name, $parameters) {
        try {
            return parent::__call($name, $parameters);
        } catch (CException $e) {
            if(method_exists($this->calendar, $name))
                return call_user_func_array(array($this->calendar, $name), $parameters);
            else
                throw $e;
        }
    }

}
