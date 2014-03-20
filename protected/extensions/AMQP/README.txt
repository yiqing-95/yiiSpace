CAMQP Extension
===============


 The 'CAMQP' is a Yii Framework Plugin that provides a Gateway for MQ-Server
 interface. Contains all methods to add/edit/delete/read messages/queues/exchanges on the broker.

 Allow to work in 'Fake' mode to avoid direct connection to the broker.


Requirements
------------

- Yii 1.1.*
- AMQP PECL extension (http://pecl.php.net/package/amqp)


Installation
------------

 - Unpack all files under your project 'component' folder
 - Include your new extension into your project main.php configuration file:
 
      'components' => array(
        
        ...
        
        'amqp' => array(
            'class' => 'application.components.AMQP.CAMQP',
            'host'  => '127.0.0.1'
        )
        
        ...
        
      )
      
 - Enjoy!
 
 
Usage:
-------

 To write a message into the MQ-Exchange:

    Yii::app()->amqp->exchange('topic')->publish('some message','some.route');


 To read a message from MQ-Queue:

    Yii::app()->amqp->queue('some_listener')->get();
    
 To Create an Exchange from the Script:
    
    $ex = Yii::app()->amqp->declareExchange('my_new_exchange');
    ...
    // now we can use created exchange
    $ex->publish('message1', 'some.route');
    $ex->publish('message2', 'some.route');
    $ex->publish('message3', 'some.route');
    ...
    $ex->publish('messageN', 'some.route');
    
 To Create a Queue from the Script:
 
    $queue = Yii::app()->amqp->declareQueue('my_new_queue');
    ...
    // now we can use created queue
    while ($message = $queue->get()) {
        // reading all messages
        ...
    }
    
 Bind a Queue to an Exchange:

    $queue = Yii::app()->amqp->queue('my_new_queue');
    $queue->bind('my_new_exchange');
    ...
    $ex = Yii::app()->amqp->queue('my_new_exchange');
    $ex->publish('Hello World!', 'routie');
    ...
    echo $queue->get(); // Hello World!

    /* OR */

    $queue = Yii::app()->amqp->bindQueueToExchange('my_new_queue', 'my_new_exchange');
    * * *
    echo $queue->get(); // Hello World!


Changelog:
-------

- 1.0.1 Flags bitmask added, some bugs fixed
- 1.0   Initial release
