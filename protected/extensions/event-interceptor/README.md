yii-event-interceptor
=====================

The EventInterceptor is a CComponent which raises an onEventIntercepted event
whenever the observed component raises an event. The onEventIntercepted event
contains the name of the intercepted event, as well as the intercepted event
itself.

It is mainly meant as a tool for other components and can be used like this:

~~~~~php

// create interceptor
$eventInterceptor = new EventInterceptor();

// initialize it: pass the object that should be observed. By default, the
// EventInterceptor will attach itself to all events defined by $subject.
// If you only want to intercept some special events, use:
// $eventInterceptor->initialize( $subject, array('onFoo', 'onBar') );
$eventInterceptor->initialize( $subject );

// whenever the EventInterceptor intercepted an event of $subject, it will
// raise an onEventIntercepted event. Attach an event handler to this event
// that cares for processing. The event handler can be any valid php callback:
// $eventInterceptor->onEventIntercepted = 'handleInterceptedEvent_inGlobalFunction';
// $eventInterceptor->onEventIntercepted = array( 'Foo', 'handleInterceptedEvent_inStaticMethod' );
// $eventInterceptor->onEventIntercepted = array( $obj, 'handleInterceptedEvent_inMethod' );
$eventInterceptor->onEventIntercepted = function( InterceptionEvent $event ) {

  $sender = $event->sender; // will always be the EventInterceptor

  $originalEvent  = $event->getInterceptedEvent();
  $originalSender = $event->getInterceptedEvent()->sender;  // our $subject

  // also available:
  $originalEventName = $event->getInterceptedEventName();

};
~~~~~
