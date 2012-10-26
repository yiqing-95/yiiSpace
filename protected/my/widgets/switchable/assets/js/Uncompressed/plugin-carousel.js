/**
 * switchable plugin :: carousel 1.0
 * http://IlikejQuery.com/switchable/#plugin
 *
 * @Creator   wo_is神仙 <i@mrzhang.me>
 * @Depend    jQuery 1.4+
**/

(function($) {		

	$.fn.carousel = function() {

		this.each(function() {
				
			var api = $(this).switchable(),
				cfg = api.getCfg(),
				panels = api.getPanels(),
				wrap = panels.parent(),

				index = api.getTriggers().length -1,
				firstItem = panels.slice(0, cfg.steps),
				lastItem = panels.slice(index * cfg.steps),

				lastPosition = cfg.vertical ? lastItem.position().top : lastItem.position().left,
				direction = cfg.vertical ? 'top' : 'left',

				allow = panels.length > cfg.visible,
				size = 0;

			panels.css('position', 'relative').each(function() {
				size += cfg.vertical ? $(this).outerHeight(true) : $(this).outerWidth(true);
			});

			if ( allow && lastItem.length < cfg.visible ) {
				wrap.append( panels.slice(0, cfg.visible).clone().addClass('clone') );
			}

			$.extend(api, {

				move: function(i) {
					if ( wrap.is(':animated') || !allow ) {
						return this;
					}

					// 从第一个反向滚动到最后一个
					if ( i < 0 ) {
						// 调整位置
						this.adjustPosition(true);
						// 触发最后一组 panels
						return this.click(index);
					}
					// 从最后一个正向滚动到第一个
					else if ( i > index ) {
						// 调整位置
						this.adjustPosition(false);
						// 触发第一组 panels
						return this.click(0);
					}
					else {
						return this.click(i);
					}
				}, 

				adjustPosition: function(isBackward) {
					var theItem = isBackward ? lastItem : firstItem;

					// 调整 panels 到下一个视图中
					$.each(theItem, function() {
						$(this).css(direction, isBackward ? -size : size);
					});
				},

				resetPosition: function(isBackward) {
					var theItem = isBackward ? lastItem : firstItem;
					
					// 滚动完成后，复位到正常状态
					$.each(theItem, function() {
						$(this).css(direction, 0);
					});
					// 瞬移到正常位置
					wrap.css(direction, isBackward ? -lastPosition : 0);
				}
			
			});

		});
		
		return this;
		
	}; 
	
})(jQuery);