yii-static-events-component
===========================

Allows you to register event handlers on class base.

To make this possible, the extension consists of two pieces:

1. The EventRegistry - an application component that serves as single point
   to register your event handlers.
2. The EventBridgeBehavior - a behavior that observes its owner and forwards
   intercepted events to the EventRegistry which will in turn invoke registered
   event handlers.

The event registry allows you to statically attach event handlers to events.
This means you attach handlers on a per class base instead of on a per
instance base like it is done in Yii normally. You specify 'invoke my event
handler when CActiveRecord raises onAfterConstruct' instead of 'invoke my
event handler when this instance of an CActiveRecord raises
onAfterConstruct'.

When the event registry gets notified about an event that was raised by a
certain object, it will lookup all registered handlers and invoke them. When
doing the lookup, it will also care for inheritance. This means if somebody
attached an event handler to CActiveRecord::onAfterConstruct, but the event
is raised by MyModel (which extends CActiveRecord), the attached event
handler will also be invoked. If somebody attached an event handler to
MyModel::onAfterConstruct, he will be notified only if this class (or
classes that inherit from MyModel) raise onAfterConstruct. He will not be
notified if the event is raised by MyModel2 (which extends CActiveRecord).

Unfortunatelly, the event registry can't observe arbitrary classes. Instead,
it works together with the EventBridgeBehavior. This behavior must be
attached to all classes that you want to observe on a per class base. Some
Yii classes allow you to attach behaviors in config (like
CApplicationComponent and CModule, including their child classes
CApplication, CConsoleApplication, CWebApplication, CWebModule and
GiiModule). So for those it is easy to ensure the EventBridgeBehavior will
be attached once they are created.

Other Yii classes allow you to override a method named behaviors() (like
CConsoleCommand, CController and CModel, including their child classes).
These classes are normally extended and not used directly. Often, you will
have something like:

~~~~~php
public Controller extends CController
~~~~~

or:

~~~~~php
public ActiveRecord extends CActiveRecord
~~~~~

somewhere in your project and extend your actual controllers and active
records from these customized base classes. So this might be a convenient
place to configure the EventBridgeBehavior.

A third group of Yii classes doesn't allow you to configure behaviors at
all (like COutputProcessor or CViewAction) although they define some events.
So unfortunatelly the EventBridge isn't any help here. Since for those
classes you have to attach behaviors on a per instance base, there isn't
much sense in attaching event handlers on a per class base and chasing down
instances to attach the corresponding behavior. Instead, you could as well
attach the event handlers on a per instance base.

The last group of Yii classes that I can think of doesn't allow you to
configure behaviors, but also doesn't provide any events. So we don't have
to care about them here.

A last word about the EventBridgeBehavior: It requires the EventInterceptor
extension to catch events of its owner and to forward them to the
EventRegistry. Please make sure the extension is available and auto
loadable.

~~~~~php
// application.config.main
'import' => array(
  // EventInterceptor is required by EventBridgeBehavior
  'ext.components.event-interceptor.*',
),
'behaviors' => array(
  // attach EventBridgeBehavior to application, so we can attach to
  // application events on a per class base.
  'eventBridge' => array(
    'class'  => 'ext.components.static-events.EventBridgeBehavior',
  ),
),
'components' => array(
  'events' => array(
    'class'  => 'ext.components.static-events.EventRegistry',
    'attach' => array(
      // Attach to application events.
      // Again a stupid example. Since there will ever be only one
      // application instance, we could as well use normal per instance
      // event binding like it is done normally in Yii. But it shows how it
      // is meant to be done...
      'CApplication' => array(
        'onBeginRequest' => array(
          function( $event ) {
            Yii::log( 'CApplication::onBeginRequest', CLogger::LEVEL_TRACE );
          },
        ),
        'onEndRequest' => array(
          function( $event ) {
            Yii::log( 'CApplication::onEndRequest - first handler', CLogger::LEVEL_TRACE );
          },
          function( $event ) {
            Yii::log( 'CApplication::onEndRequest - second handler', CLogger::LEVEL_TRACE );
          },
        ),
      ),
    ),
  ),
  'log'=>array(
    'class'=>'CLogRouter',
    'routes'=>array(
      // enable web log route to see what we just logged
      array(
        'class'=>'CWebLogRoute',
      ),
    ),
  ),
),

// or to attach/ detach at runtime:
$events = Yii::app()->events;
$events->attach( 'CActiveRecord', 'onAfterConstruct', $callback );
$events->detach( 'CActiveRecord', 'onAfterConstruct', $callback );

~~~~~
