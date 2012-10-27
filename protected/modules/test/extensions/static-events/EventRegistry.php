<?php
/**
 * Copyright 2012 Benjamin Wöster. All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without modification, are
 * permitted provided that the following conditions are met:
 *
 *    1. Redistributions of source code must retain the above copyright notice, this list of
 *       conditions and the following disclaimer.
 *
 *    2. Redistributions in binary form must reproduce the above copyright notice, this list
 *       of conditions and the following disclaimer in the documentation and/or other materials
 *       provided with the distribution.
 *
 * THIS SOFTWARE IS PROVIDED BY BENJAMIN WÖSTER ``AS IS'' AND ANY EXPRESS OR IMPLIED
 * WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND
 * FITNESS FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL BENJAMIN WÖSTER OR
 * CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR
 * CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR
 * SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON
 * ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING
 * NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF
 * ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 *
 * The views and conclusions contained in the software and documentation are those of the
 * authors and should not be interpreted as representing official policies, either expressed
 * or implied, of Benjamin Wöster.
 */


/**
 * The event registry allows you to statically attach event handlers to events.
 * This means you attach handlers on a per class base instead of on a per
 * instance base like it is done in Yii normally. You specify 'invoke my event
 * handler when CActiveRecord raises onAfterConstruct' instead of 'invoke my
 * event handler when this instance of an CActiveRecord raises
 * onAfterConstruct'.
 *
 * When the event registry gets notified about an event that was raised by a
 * certain object, it will lookup all registered handlers and invoke them. When
 * doing the lookup, it will also care for inheritance. This means if somebody
 * attached an event handler to CActiveRecord::onAfterConstruct, but the event
 * is raised by MyModel (which extends CActiveRecord), the attached event
 * handler will also be invoked. If somebody attached an event handler to
 * MyModel::onAfterConstruct, he will be notified only if this class (or
 * classes that inherit from MyModel) raise onAfterConstruct. He will not be
 * notified if the event is raised by MyModel2 (which extends CActiveRecord).
 *
 * Unfortunatelly, the event registry can't observe arbitrary classes. Instead,
 * it works together with the EventBridgeBehavior. This behavior must be
 * attached to all classes that you want to observe on a per class base. Some
 * Yii classes allow you to attach behaviors in config (like
 * CApplicationComponent and CModule, including their child classes
 * CApplication, CConsoleApplication, CWebApplication, CWebModule and
 * GiiModule). So for those it is easy to ensure the EventBridgeBehavior will
 * be attached once they are created.
 *
 * Other Yii classes allow you to override a method named behaviors() (like
 * CConsoleCommand, CController and CModel, including their child classes).
 * These classes are normally extended and not used directly. Often, you will
 * have something like:
 *
 * @code
 * public Controller extends CController
 * @endcode
 *
 * or:
 *
 * @code
 * public ActiveRecord extends CActiveRecord
 * @endcode
 *
 * somewhere in your project and extend your actual controllers and active
 * records from these customized base classes. So this might be a convenient
 * place to configure the EventBridgeBehavior.
 *
 * A third group of Yii classes doesn't allow you to configure behaviors at
 * all (like COutputProcessor or CViewAction) although they define some events.
 * So unfortunatelly the EventBridge isn't any help here. Since for those
 * classes you have to attach behaviors on a per instance base, there isn't
 * much sense in attaching event handlers on a per class base and chasing down
 * instances to attach the corresponding behavior. Instead, you could as well
 * attach the event handlers on a per instance base.
 *
 * The last group of Yii classes that I can think of doesn't allow you to
 * configure behaviors, but also doesn't provide any events. So we don't have
 * to care about them here.
 *
 * A last word about the EventBridgeBehavior: It requires the EventInterceptor
 * extension to catch events of its owner and to forward them to the
 * EventRegistry. Please make sure the extension is available and auto
 * loadable.
 *
 * @code
 * // application.config.main
 * 'import' => array(
 *   // EventInterceptor is required by EventBridgeBehavior
 *   'ext.components.event-interceptor.*',
 * ),
 * 'behaviors' => array(
 *   // attach EventBridgeBehavior to application, so we can attach to
 *   // application events on a per class base.
 *   'eventBridge' => array(
 *     'class'  => 'ext.components.static-events.EventBridgeBehavior',
 *   ),
 * ),
 * 'components' => array(
 *   'events' => array(
 *     'class'  => 'ext.components.static-events.EventRegistry',
 *     'attach' => array(
 *       // Attach to application events.
 *       // Again a stupid example. Since there will ever be only one
 *       // application instance, we could as well use normal per instance
 *       // event binding like it is done normally in Yii. But it shows how it
 *       // is meant to be done...
 *       'CApplication' => array(
 *         'onBeginRequest' => array(
 *           function( $event ) {
 *             Yii::log( 'CApplication::onBeginRequest', CLogger::LEVEL_TRACE );
 *           },
 *         ),
 *         'onEndRequest' => array(
 *           function( $event ) {
 *             Yii::log( 'CApplication::onEndRequest - first handler', CLogger::LEVEL_TRACE );
 *           },
 *           function( $event ) {
 *             Yii::log( 'CApplication::onEndRequest - first handler', CLogger::LEVEL_TRACE );
 *           },
 *         ),
 *       ),
 *     ),
 *   ),
 * ),
 *
 * // or to attach/ detach at runtime:
 * $events = Yii::app()->events;
 * $events->attach( 'CActiveRecord', 'onAfterConstruct', $callback );
 * $events->detach( 'CActiveRecord', 'onAfterConstruct', $callback );
 *
 * @endcode
 *
 * @author Benjamin
 */
class EventRegistry extends CApplicationComponent
{

  /////////////////////////////////////////////////////////////////////////////

  /**
   * Stores attached event handlers.
   * Structure is $_callbacks[{className}][{eventName}] = CList of callbacks
   * @var array
   */
  private $_callbacks = array();

  /////////////////////////////////////////////////////////////////////////////

  /**
   * Allows to attach static event handlers in configuration.
   *
   * Structure of the config array:
   *
   * array(
   *   'CApplication' => array(
   *     'onBeginRequest' => array(
   *       function( $event ) {
   *         Yii::log( 'CApplication::onBeginRequest', CLogger::LEVEL_TRACE );
   *       },
   *     ),
   *     'onEndRequest' => array(
   *       function( $event ) {
   *         Yii::log( 'CApplication::onEndRequest - first handler', CLogger::LEVEL_TRACE );
   *       },
   *       function( $event ) {
   *         Yii::log( 'CApplication::onEndRequest - first handler', CLogger::LEVEL_TRACE );
   *       },
   *     ),
   *   ),
   * ),
   * @param array $aConfig
   */
  public function setAttach( array $aConfig )
  {
    foreach ($aConfig as $className => $aEvents)
    {
      foreach ($aEvents as $event => $aHandlers)
      {
        foreach ($aHandlers as $handler) {
          $this->attach( $className, $event, $handler );
        }
      }
    }
  }

  /////////////////////////////////////////////////////////////////////////////

  /**
   * Raises the specified event. First, all event handlers of the concrete
   * class will be executed, then the method will walk up the inheritance list
   * and invoke the handlers attached to parent classes.
   *
   * @param string $class
   * @param string $event
   * @param CEvent $eventInstance
   * @throws CException
   */
  public function raiseStaticEvent( $class, $event, $eventInstance )
  {
    $eventHandlers = $this->getStaticEventHandlersInternal( $class, $event );

    if ($eventHandlers !== false)
    {
			foreach ($eventHandlers as $handler)
			{
        // --- Copy from CComponent::raiseEvent - START -----------------------

				if(is_string($handler))
					call_user_func($handler,$eventInstance);
				else if(is_callable($handler,true))
				{
					if(is_array($handler))
					{
						// an array: 0 - object, 1 - method name
						list($object,$method)=$handler;
						if(is_string($object))	// static method call
							call_user_func($handler,$eventInstance);
						else if(method_exists($object,$method))
							$object->$method($eventInstance);
						else
							throw new CException(Yii::t('yii','Event "{class}.{event}" is attached with an invalid handler "{handler}".',
								array('{class}'=>$class, '{event}'=>$event, '{handler}'=>$handler[1])));
					}
					else // PHP 5.3: anonymous function
						call_user_func($handler,$eventInstance);
				}
				else
					throw new CException(Yii::t('yii','Event "{class}.{event}" is attached with an invalid handler "{handler}".',
						array('{class}'=>$class, '{event}'=>$event, '{handler}'=>gettype($handler))));
				// stop further handling if param.handled is set true
				if(($eventInstance instanceof CEvent) && $eventInstance->handled)
					return;

        // --- Copy from CComponent::raiseEvent - END -------------------------
			}
    }
    else if (YII_DEBUG && !$this->componentHasEvent($class,$event))
    {
			throw new CException(Yii::t(
        'yii',
        'Event "{class}.{event}" is not defined.',
				array(
          '{class}' => $class,
          '{event}' => $event,
        )
      ));
    }
  }

  /////////////////////////////////////////////////////////////////////////////

  /**
   * $events->attach( 'CActiveRecord', 'onAfterConstruct' ) = $callback;
   *
   * @param string $class
   * @param string $event
   * @param callback $handler
   */
	public function attach( $class, $event, $handler )
	{
    $this->getStaticEventHandlers($class,$event)->add( $handler );
	}

  /////////////////////////////////////////////////////////////////////////////

  /**
   * $events->detach( 'CActiveRecord', 'onAfterConstruct', $callback );
   *
   * @param string $class
   * @param string $event
   * @param callback $handler
	 * @return boolean if the detachment process is successful
   */
  public function detach( $class, $event, $handler )
  {
    $eventHandlers = $this->getStaticEventHandlersInternal( $class, $event, true );
    return $eventHandlers === false
      ? false
      : $eventHandlers->remove( $handler ) !== false;
  }

  /////////////////////////////////////////////////////////////////////////////

	/**
	 * Returns the list of attached event handlers for an event. The method will
   * give you only a list of event handlers that are attached to the concrete
   * class. It won't include the event handlers of the parent classes.
   *
	 * @param string $class the class name
	 * @param string $event the event name
	 * @return CList list of attached event handlers for the event
	 * @throws CException if the event is not defined
	 */
  public function getStaticEventHandlers( $class, $event )
  {
    if ($this->componentHasEvent($class,$event))
    {
      $eventHandlers = $this->getStaticEventHandlersInternal( $class, $event, true );

      if ($eventHandlers === false)
      {
  			$eventName = strtolower( $event );
        $this->_callbacks[$class][$eventName] = new CList();
        $eventHandlers = $this->_callbacks[$class][$eventName];
      }

			return $eventHandlers;
    }
    else
    {
			throw new CException( Yii::t(
        'yii',
        'Event "{class}.{event}" is not defined.',
				array(
          '{class}' => $class,
          '{event}' => $event,
        )
      ));
    }
  }

  /////////////////////////////////////////////////////////////////////////////

	/**
	 * Checks whether the named event has attached handlers.
   *
   * By default, this method will first check if an eventHandler for
   * {$class}.{$event} is attached. If not, it will go on checking if an
   * eventHandler for {$parentClass}.{$event} is attached. It will go on
   * searching the inheritance list until there is no next parent class, or the
   * parent class doesn't define the event.
   *
   * This means if you call
   * $events->hasStaticEventHandler( 'MyActiveRecord', 'onAfterConstruct' )
   * and someone attached an event handler on CActiveRecord::onAfterConstruct
   * the method will return true.
   *
   * If you want to know if there is an event handler especially for
   * MyActiveRecord::onAfterConstruct, call
   * $events->hasStaticEventHandler( 'MyActiveRecord', 'onAfterConstruct', true )
   *
	 * @param string $class the class name
	 * @param string $event the event name
   * @param bool $strict weather to check only $class for event handlers
	 * @return boolean whether an event has been attached one or several handlers
	 */
	public function hasStaticEventHandler( $class, $event, $strict=false )
	{
    $staticEventHandlers = $this->getStaticEventHandlersInternal( $class, $event, $strict );
    return $staticEventHandlers === false
      ? false
      : $staticEventHandlers->count() > 0;
	}

  /////////////////////////////////////////////////////////////////////////////

  /**
	 * Determines whether an event is defined in an given class.
	 * An event is defined if the class has a method named like 'onXXX'.
	 * Note, event name is case-insensitive.
   * @param string $class
   * @param string $event
   * @return bool
   */
  private function componentHasEvent( $class, $event )
  {
		return !strncasecmp($event,'on',2) && method_exists($class,$event);
  }

  /////////////////////////////////////////////////////////////////////////////

  /**
   * Returns a List of event handler that are attached to a given class.
   *
   * Other than getStaticEventHandlers(), it won't create a new CList if there
   * is none. Instead, it will return false in this case.
   *
   * By default, it will will collect event handlers for the concrete class and
   * parent classes. Handlers of the concrete class will be returned at the
   * beginning of the list, Handlers of the parent classes will be returned at
   * the end of the list.
   *
   * If you only want to collect event handlers for the concrete class, call
   * the method with $strict set to true.
   *
   * @param string $class
   * @param string $event
   * @return mixed, boolean false if the event has no attached event handlers
   *                CList with attached event handlers otherwise
   */
  private function getStaticEventHandlersInternal( $class, $event, $strict=false )
  {
    return $strict
      ? $this->getStaticEventHandlersStrict( $class, $event )
      : $this->getStaticEventHandlersAll( $class, $event );
  }

  /////////////////////////////////////////////////////////////////////////////

  /**
   * Returns a List of event handler that are attached to a given concrete
   * class.
   *
   * @param string $class
   * @param string $event
   * @return mixed, boolean false if the event has no attached event handlers
   *                CList with attached event handlers otherwise
   */
  private function getStaticEventHandlersStrict( $class, $event )
  {
    $eventName = strtolower( $event );

    if (isset($this->_callbacks[$class]) && isset($this->_callbacks[$class][$eventName])) {
      return $this->_callbacks[$class][$eventName];
    }

    return false;
  }

  /////////////////////////////////////////////////////////////////////////////

  private function getStaticEventHandlersAll( $class, $event )
  {
    $inspectedClass = $class;
    $eventHandlers = false;

    do
    {
      $additionalEventHandlers = $this->getStaticEventHandlersStrict( $inspectedClass, $event );

      if ($additionalEventHandlers !== false)
      {
        if ($eventHandlers === false)
        {
          $eventHandlers = new CList();
          $eventHandlers->copyFrom( $additionalEventHandlers );
        }
        else {
          $eventHandlers->mergeWith( $additionalEventHandlers );
        }        
      }
      
      $inspectedClass = get_parent_class( $inspectedClass );
    }
    while( $inspectedClass !== false && $this->componentHasEvent($inspectedClass,$event) );

    return $eventHandlers;
  }

  /////////////////////////////////////////////////////////////////////////////

}
