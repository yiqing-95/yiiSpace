/**
 * switchable 1.1
 * http://IlikejQuery.com/switchable
 *
 * @Creator   wo_is神仙 <i@mrzhang.me>
 * @Depend    jQuery 1.4+
**/

(function($) {
	$.switchable = $.switchable || {};

	$.switchable = {
		cfg: {
			// 触点
			triggers: 'a',
			// 当前触点的className
			currentCls: 'current',
			// 默认激活项
			initIndex: 0,
			// 触发类型
			triggerType: 'mouse', // or click
			// 触发延迟
			delay: .1, // 100ms

			// 切换效果
			effect: 'default',
			// 每次切换的 Panel 数量
			steps: 1,
			// 可见区域的 Panel 数量
			visible: 1,
			// 动画步时
			speed: .7, // 700ms
			easing: 'swing',
			
			// 循环
			circular: false,
			// 纵向切换
			vertical: false,
			// 点击视图区切换
			panelSwitch: false,
			
			beforeSwitch: null,
			onSwitch: null,
			api: false
		},
		//自定义效果
		addEffect: function(name, fn) {
			effects[name] = fn;
		}
	};
	//内置效果
	var effects = {
		'default': function(i, done) {
			this.getPanels().hide();
			this.getVisiblePanel(i).show();
			done.call();
		}, 
		'ajax': function(i, done)  {			
			this.getPanels().first().load(this.getTriggers().eq(i).attr('href'), done);	
		}
	};   	

	function switchTo(triggers, panels, cfg) { 
		
		var self = this,
			$self = $(this),
			current,
			index = triggers.length -1;

		// 绑定所有回调函数
		$.each(cfg, function(name, fn) {
			if ( $.isFunction(fn) ) {
				$self.bind(name, fn);
			}
		});
		
		// 公共方法
		$.extend(this, {
			click: function(i, e) {

				var trigger = triggers.eq(i);
				
				if ( typeof i == 'string' && i.replace('#', '') ) {
					trigger = triggers.filter('[href*=' + i.replace('#', '') + ']');
					i = Math.max(trigger.index(), 0);
				}

				e = e || $.Event();
				e.type = 'beforeSwitch';
				$self.trigger(e, [i]);
				if ( e.isDefaultPrevented() ) {
					return;
				}
				
				// Call the effect
				effects[cfg.effect].call(self, i, function() {
					// onSwitch callback
					e.type = 'onSwitch';
					$self.trigger(e, [i]);					
				});			
				
				// onStart
				e.type = 'onStart';
				$self.trigger(e, [i]);				
				if ( e.isDefaultPrevented() ) {
					return;
				}
				
				current = i;
				triggers.removeClass(cfg.currentCls);	
				trigger.addClass(cfg.currentCls);
				
				return self;
			},
			
			getCfg: function() {
				return cfg;	
			},

			getTriggers: function() {
				return triggers;	
			},
			
			getPanels: function() {
				return panels;	
			},
			
			getVisiblePanel: function(i) {
				return self.getPanels().slice(i * cfg.steps, (i + 1) * cfg.steps);	
			},
			
			getIndex: function() {
				return current;	
			}, 
			
			move: function(i) {
				if ( panels.parent().is(':animated') || panels.length <= cfg.visible ) {
					return self;
				}
				
				if ( typeof i == 'number' ) {
					if ( i < 0 ) {
						return cfg.circular ? self.click(index) : self;
					} else if ( i > index ) {
						return cfg.circular ? self.click(0) : self;
					} else {
						return self.click(i);
					}
				} else {
					return self.click();
				}
			}, 
			
			next: function() {
				return self.move(current + 1);
			},
			
			prev: function() {
				return self.move(current - 1);	
			},
			
			bind: function(name, fn) {
				$self.bind(name, fn);
				return self;	
			},	
			
			unbind: function(name) {
				$self.unbind(name);
				return self;
			},
			
			beforeSwitch: function(fn) {
				return this.bind('beforeSwitch', fn);
			},
			
			onSwitch: function(fn) {
				return this.bind('onSwitch', fn);
			},
			
			resetPosition: function(isBackward) {}
		
		});
		
		//为每个触点绑定事件
		var switchTimer;
		triggers.each(function(i) {
			if ( cfg.triggerType === 'mouse' ) {//响应鼠标悬浮
				$(this).bind({
					'mouseenter': function(e) {
						//不重复触发
						if ( i !== current ) {
							//延时处理，鼠标快速滑过不触发
							switchTimer = setTimeout(function() {
								self.click(i, e);
							}, cfg.delay * 1000);
						}
					},
					'mouseleave': function() {
						//鼠标快速滑出，取消悬浮事件
						clearTimeout(switchTimer);
					}
				});
			} else {//响应点击
				$(this).bind('click', function(e) {
					//不重复触发
					if ( i !== current ) {
						self.click(i, e);
					}
					return false;
				});
			}
		});

		if ( location.hash ) {
			self.click(location.hash);
		} else {
			if ( cfg.initIndex >= 0 ) {
				self.click(cfg.initIndex);
			}
		}		
		
		// 视图区通过锚链切换
		panels.find('a[href^=#]').click(function(e) {
			self.click($(this).attr('href'), e);		
		}); 

		// 点击视图区切换
		if ( cfg.panelSwitch ) {
			panels.css('cursor', 'pointer').click(function() {
				self.next();
				return false;
			}); 
		}
	}
	
	$.fn.switchable = function(container, cfg) {

		var el = this.eq(typeof cfg == 'number' ? cfg : 0).data('switchable');
		if ( el ) {
			return el;
		}

		if ( $.isFunction(cfg) ) {
			cfg = { beforeSwitch: cfg };
		}
		
		var globals = $.extend({}, $.switchable.cfg), len = this.length;
		cfg = $.extend(globals, cfg);

		this.each(function(i) {
			var root = $(this);
			
			// 获取 panels
			var panels = container.jquery ? container : root.children(container);
			if ( !panels.length ) {
				panels = len == 1 ? $(container) : root.parent().find(container);
			}

			// 获取 triggers
			var els = root.find(cfg.triggers);
			// 自动创建 triggers
			if ( !els.length ) {
				// 向上取整
				var counts = Math.ceil(panels.length / cfg.steps),
					arr = [];
				for ( var i = 1; i <= counts; i++ ) {
					arr.push('<a href="javascript:void(0)" target="_self">'+ i +'</a>');
				}
				els = root.append(arr.join('')).children('a');
			}

			el = new switchTo(els, panels, cfg);
			root.data('switchable', el);

		});		
		
		return cfg.api ? el : this;		
	};		
		
})(jQuery);