/**
 * switchable plugin :: mousewheel 1.0
 * http://IlikejQuery.com/switchable/#plugin
 *
 * @Creator   wo_is神仙 <i@mrzhang.me>
 * @Depend    jQuery 1.4+
**/

(function($) {
		
	$.fn.wheel = function( fn ){
		return this[ fn ? 'bind' : 'trigger' ]( 'wheel', fn );
	};

	// special event cfgig
	$.event.special.wheel = {
		setup: function(){
			$.event.add( this, wheelEvents, wheelHandler, {} );
		},
		teardown: function(){
			$.event.remove( this, wheelEvents, wheelHandler );
		}
	};

	// events to bind ( browser sniffed... )
	var wheelEvents = !$.browser.mozilla ? 'mousewheel' : // IE, opera, safari
		'DOMMouseScroll' + ( $.browser.version < '1.9' ? ' mousemove' : '' ); // firefox

	// shared event handler
	function wheelHandler( event ) {
		
		switch ( event.type ){
			
			// FF2
			case 'mousemove': 
				return $.extend( event.data, { // 存储正确的属性
					clientX: event.clientX, clientY: event.clientY,
					pageX: event.pageX, pageY: event.pageY
				});
				
			// firefox	
			case 'DOMMouseScroll': 
				$.extend( event, event.data );
				event.delta = -event.detail / 3;
				break;
				
			// IE, opera, safari	
			case 'mousewheel':				
				event.delta = event.wheelDelta / 120;
				break;
		}
		
		event.type = 'wheel'; // 事件劫持
		return $.event.handle.call( this, event, event.delta );
	}

	$.fn.mousewheel = function() {

		this.each(function() {		

			var api = $(this).switchable();
			
			api.getPanels().parent().wheel(function(e, delta)  {
				if ( delta < 0 ) {
					api.next();
				} else {
					api.prev();
				}
				return false;
			});
		});

		return this;
	};
	
})(jQuery);