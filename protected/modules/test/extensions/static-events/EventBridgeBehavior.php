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
 * Behavior that forwards intercepted events to the EventRegistry application
 * component.
 *
 * This behavior must be attached to all classes from which you want to receive
 * static events.
 *
 * The behavior requires the EventInterceptor to be imported.
 *
 * @author Benjamin
 */
class EventBridgeBehavior extends CBehavior
{
  /**
   * @var string the component id of the EventRegistry
   */
  public $eventRegistryId = 'events';

  private $_eventInterceptor = null;
  private $_eventRegistry = null;

  /////////////////////////////////////////////////////////////////////////////

  public function attach($owner)
  {
    parent::attach( $owner );

    $this->_eventInterceptor = new EventInterceptor();
    $this->_eventInterceptor->initialize( $owner );
    $this->_eventInterceptor->onEventIntercepted = array( $this, 'forwardEvent' );
  }

  /////////////////////////////////////////////////////////////////////////////

  public function forwardEvent( InterceptionEvent $event )
  {
    $interceptedEvent = $event->getInterceptedEvent();
    $senderClass = get_class( $interceptedEvent->sender );
    $eventName = $event->getInterceptedEventName();

    $er = $this->getEventRegistry();
    if ($er->hasStaticEventHandler($senderClass,$eventName)) {
      $er->raiseStaticEvent( $senderClass, $eventName, $interceptedEvent );
    }
  }

  /////////////////////////////////////////////////////////////////////////////

  /**
   * @return EventRegistry
   */
  private function getEventRegistry()
  {
    if ($this->_eventRegistry === null) {
      $this->_eventRegistry = Yii::app()->getComponent( $this->eventRegistryId );
    }

    return $this->_eventRegistry;
  }

  /////////////////////////////////////////////////////////////////////////////

}
