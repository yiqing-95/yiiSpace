/**
 * switchable plugin :: autoplay 1.0
 * http://IlikejQuery.com/switchable/#plugin
 *
 * @Creator   wo_is神仙 <i@mrzhang.me>
 * @Depend    jQuery 1.4+
**/

(function($) {		

	var t = $.switchable; 
	t.plugin = t.plugin || {};
	
	t.plugin.autoplay = {
		cfg: {
			// 自动播放
			autoplay: true,
			// 自动播放间隔
			interval: 3, // 3000ms
			// 鼠标悬停暂停
			autopause: true,
			api: false
		}
	};	
	
	$.fn.autoplay = function(cfg) { 

		if ( typeof cfg == 'number' ) {
			cfg = { interval: cfg };	
		}
		
		var opts = $.extend({}, t.plugin.autoplay.cfg), ret;
		$.extend(opts, cfg);   	
		
		this.each(function() {		
				
			var api = $(this).switchable();			
			if ( api ) {
				ret = api;
			}
			
			var timer, hoverTimer, stopped = true;
	
			api.play = function() {
	
				// do not start additional timer if already exists
				if ( timer ) {
					return;
				}
				
				stopped = false;
				
				// construct new timer
				timer = setInterval(function() {
					api.next();
				}, opts.interval*1000);
				
				api.next();
			};	

			api.pause = function() {
				timer = clearInterval(timer);	
			};
			
			// when stopped - mouseover won't restart 
			api.stop = function() {
				api.pause();
				stopped = true;	
			};
		
			/* when mouse enters, autoplay stops */
			if ( opts.autopause ) {
				api.getPanels().hover(function() {			
					api.pause();
					clearTimeout(hoverTimer);
				}, function() {
					if ( !stopped ) {						
						hoverTimer = setTimeout(api.play, opts.interval*1000);						
					}
				});
			}			
			
			if ( opts.autoplay ) {
				setTimeout(api.play, opts.interval*1000);				
			}

		});
		
		return opts.api ? ret : this;
		
	}; 
	
})(jQuery);