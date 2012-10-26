<?php

/**
 * AMQP extension wrapper to communicate with RabbitMQ server
 * For More documentation please see:
 * http://php.net/manual/en/book.amqp.php
 */

/**
 * @defgroup CAMQP
 * @ingroup AMQPModule
 * @version 1.0.1
 */

/**
 * @class CAMQP
 * @brief Use for comunicate with AMQP server
 * @details  Send and recieve messages. Implements Wrapper template.
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
 * Requirements:
 * --------------
 *  - Yii 1.1.x or above
 *  - Rabbit php library or AMQP PECL library
 *
 * Usage:
 * --------------
 *
 * To write a message into the MQ-Exchange:
 *
 *     Yii::App()->amqp->exchange('topic')->publish('some message','some.route');
 *
 *
 * To read a message from MQ-Queue:
 *
 *     Yii::App()->amqp->queue('some_listener')->get();
 *
 */
class CAMQP extends CApplicationComponent
{
    public $host      = 'localhost';
    public $port      = '5672';
    public $vhost     = '/';

    public $login     = 'guest';
    public $password  = 'guest';

    protected $client = null;

    /**
     * @brief states if extension should work in fake mode
     * @details in case it is enabled - CAMQP will not perform real connection with 
     * @var boolean
     */
    public $isFakeMode = false;


    /**
     * @brief Initialize component.
     * @details in case fakeMode is enabled loading fake Queue and Exchange classes
     */
    public function init()
    {
    	Yii::trace('Initializating CAMQP', 'CEXT.CAMQP.Init');

        if ($this->isFakeMode) {
        	include_once(dirname(__FILE__) . "/fake/CAMQPQueue.php");
        	include_once(dirname(__FILE__) . "/fake/CAMQPExchange.php");
        } else {
        	include_once(dirname(__FILE__) . "/CAMQPQueue.php");
        	include_once(dirname(__FILE__) . "/CAMQPExchange.php");

        	// init AMQP client
            $class = class_exists('AMQPConnection', false) ? 'AMQPConnection' : 'AMQPConnect'; //prefer pecl extension
	        $this->client = new $class(array(
	            'host'     => $this->host,
	            'vhost'    => $this->vhost,
	            //'port'   => $this->port,
	            'login'    => $this->login,
	            'password' => $this->password,
	        ));
            //Autoconnect for pecl extension
            if (method_exists($this->client, 'connect')&&$this->client->isConnected()==false)
                $this->client->connect();
        }

        parent::init();
    }

    /**
     * @brief Declares a new Exchange on the broker
     * @param $name
     * @param $flags
     */
    public function declareExchange($name, $type = AMQP_EX_TYPE_DIRECT, $flags = NULL)
    {
    	$ex = new CAMQPExchange($this->client);
    	return $ex->declare($name, $type, $flags);
    }

    /**
     * @brief Declares a new Queue on the broker
     * @param $name
     * @param $flags
     */
    public function declareQueue($name, $flags = NULL)
    {
        $queue = new CAMQPQueue($this->client);
        return $queue->declare($name, $flags);
    }

    /**
     * @brief
     * @details Returns an instance of CAMQPExchange for exchange a queue is bind
     * @param $exchange
     * @param $queue
     * @param $routingKey
     */
    public function bindExchangeToQueue($exchange, $queue, $routingKey = "")
    {
        $exchange = $this->exchange($exchange);
        $exchange->bind($queue, $routingKey);
        return $exchange;
    }

    /**
     * @brief Binds a queue to specified exchange
     * @details Returns an instance of CAMQPQueue for queue an exchange is bind
     * @param $queue
     * @param $exchange
     * @param $routingKey
     */
    public function bindQueueToExchange($queue, $exchange, $routingKey = "")
    {
        $queue = $this->queue($queue);
        $queue->bind($exchange, $routingKey);
        return $queue;
    }

    /**
     * @brief Get exchange by name
     * @param $name  name of exchange
     * @return  object AMQPExchange
     */
    public function exchange($name)
    {
        Yii::trace('Get instance of  CAMQPExchange with name:' . $name, 'CEXT.CAMQP.exchange');
        return new CAMQPExchange($this->client, $name);
    }

    /**
     * @brief Get queue by name
     * @param $name  name of exchange
     * @return  object AMQPQueue
     */
    public function queue($name)
    {
        Yii::trace('Get instance of  CAMQPQueue with name:' . $name, 'CEXT.CAMQP.queue');
        return new CAMQPQueue($this->client, $name);
    }

    /**
     * Returns AMQPConnection instance
     *
     * @return AMQPConnection
     */
    public function getClient()
    {
        return $this->client;
    }
}

//Fake exception stub, cause "not pecl" extension throws default Exceptions
if (!class_exists('AMQPConnectionException', false)){
    class AMQPConnectionException extends Exception {}
}
if (!class_exists('AMQPExchangeException', false)){
    class AMQPExchangeException extends Exception {}
}
if (!class_exists('AMQPQueueException', false)){
    class AMQPQueueException extends Exception {}
}
//Define constants for "not pecl" extension
if (!defined('AMQP_EX_TYPE_DIRECT'))
    define('AMQP_EX_TYPE_DIRECT', 'direct');
if (!defined('AMQP_EX_TYPE_FANOUT'))
    define('AMQP_EX_TYPE_FANOUT', 'fanout');
if (!defined('AMQP_EX_TYPE_TOPIC'))
    define('AMQP_EX_TYPE_TOPIC', 'topic');
if (!defined('AMQP_EX_TYPE_HEADER'))
    define('AMQP_EX_TYPE_HEADER', 'header');
