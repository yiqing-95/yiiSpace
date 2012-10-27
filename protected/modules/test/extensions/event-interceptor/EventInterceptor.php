<?php
/**
 * Copyright 2011 Benjamin Wöster. All rights reserved.
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
 * Interceptor, which raises an onEventIntercepted event, whenever the observed
 * component raises an event. The onEventIntercepted event contains the name
 * of the intercepted event, as well as the intercepted event itself.
 *
 * @author Benjamin Wöster
 */
class EventInterceptor extends CComponent
{

  /**
   * Attach a closure to every event defined by $subject.
   * The closure forwards the event including its name to the intercept method,
   * which in turn raises the onEventIntercepted event.
   *
   * @param CComponent $subject
   * @param array $events - list of events that shall be intercepted. Defaults
   * to array('*'), which means "all events".
   */
  public function initialize( CComponent $subject, array $events=array('*') )
  {
    $aEventNames = array();

    if (count($events) === 1 && array_key_exists(0,$events) && $events[0] === '*')
      $aEventNames = $this->getEventNames( $subject );
    else
      $aEventNames = $events;

    $interceptor = $this;
    foreach ($aEventNames as $eventName)
    {
      $subject->$eventName = function( CEvent $event ) use( $interceptor, $eventName )
      {
        $interceptor->intercept( $eventName, $event );
      };
    }
  }

  /**
   * Will be called whenever $subject raises an event.
   * Everything this method does is raising an event itself, which contains:
   * 
   *   1) the name of the intercepted event
   *   2) the intercepted event object
   *
   * @param string $eventName
   * @param CEvent $event
   */
  public function intercept( $eventName, CEvent $event )
  {
    $this->onEventIntercepted( new InterceptionEvent($this,array(
      InterceptionEvent::INTERCEPTED_EVENT_NAME_PARAM => $eventName,
      InterceptionEvent::INTERCEPTED_EVENT_PARAM      => $event,
    )) );
  }

  public function onEventIntercepted( InterceptionEvent $event )
  {
    $this->raiseEvent( 'onEventIntercepted', $event );
  }

  protected function getEventNames( CComponent $subject )
  {
    $aEventNames = array();
    $aMethodNames = get_class_methods( $subject );

    foreach ($aMethodNames as $methodName)
      if (strtolower(substr($methodName,0,2)) === 'on')
        $aEventNames[] = $methodName;

    return $aEventNames;
  }

}
