<?php

/**
 * @ingroup CAMQP
 */

/**
 * @class   CAMQPQueue
 * @brief   Fake Queue
 * @details Stubs all methods to support fake mode for CAMQP
 * 
 * "A" - Team:
 * @author     Andrey Evsyukov <thaheless@gmail.com>
 * @author     Alexey Spiridonov <a.spiridonov@2gis.ru>
 * @author     Alexey Papulovskiy <a.papulovskiyv@2gis.ru>
 * @author     Alexander Biryukov <a.biryukov@2gis.ru>
 * @author     Alexander Radionov <alex.radionov@gmail.com>
 * @author     Andrey Trofimenko <a.trofimenko@2gis.ru>
 * @author     Artem Kudzev <a.kiudzev@2gis.ru>
 * @author     Alexey Ashurok <a.ashurok@2gis.ru>
 *   
 * @link       http://www.2gis.ru
 * @copyright  2GIS
 * @license http://www.yiiframework.com/license/
 * 
 */
class CAMQPQueue
{
    private static $funct_list = array('ack', 'bind', 'cancel', 'consume', 'declare', 'delete', 'get', 'purge', 'unbind', 'existingQueue');

	/**
	 * Create an instance of an AMQPQueue object.
	 * 
	 * @param $AMQPConnection
	 * @param $queueName
	 */
    public function __construct ($AMQPConnection, $queueName = "")
    {
    	Yii::trace("CAMQPQueue: Warning: FAKE MODE", "CEXT.CAMQP.CAMQPQueue");
    	Yii::trace("CAMQPQueue is initiated for queue '$queueName'", "CEXT.CAMQP.CAMQPQueue");
    }

    public function  __call($name, $arguments)
    {
        if (!in_array($name, self::$funct_list))
            throw new BadMethodCallException ('Unknown method: '.$name);
        Yii::trace("CAMQPExchange: Warning: FAKE MODE", "CEXT.CAMQP.CAMQPExchange.$name");
        Yii::trace("Execute with params: " . TraceTextHelper::printData($arguments), "CEXT.CAMQP.CAMQPQueue.$name");
        return true;
    }

}