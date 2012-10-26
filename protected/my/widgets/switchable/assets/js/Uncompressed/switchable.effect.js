/**
 * switchable effect :: effect 1.0
 * http://IlikejQuery.com/switchable/#effect
 *
 * @Creator   wo_is神仙 <i@mrzhang.me>
 * @Depend    jQuery 1.4+
**/

/**
 * 淡隐淡现
**/
$.switchable.addEffect('fade', function(i, done) {
	var self = this,
		cfg = self.getCfg(),
		items = self.getPanels(),
		thisItem = self.getVisiblePanel(i);

	items.hide();
	thisItem.fadeIn(cfg.speed * 1000, done);
});

/**
 * 滚动
 *
 * Panel's HTML Makeup:
 * <container>
 *    <wrapper>
 *       <panel />
 *       <panel />
 *       <panel />
 *    </wrapper>
 * </container>
**/
$.switchable.addEffect('scroll', function(i, done) {
	var self = this,
		cfg = self.getCfg(),
		thisItem = self.getVisiblePanel(i),
		wrap = self.getPanels().parent(),
		current = self.getIndex(),
		len = self.getTriggers().length - 1,

		// 从第一个反向滚动到最后一个 or 从最后一个正向滚动到第一个
		isCritical = (current === 0 && i === len) || (current === len && i === 0),
		isBackward = (current === 0 && i === len) ? true : false,
		prop = cfg.vertical ? { top : -thisItem.position().top } : { left : -thisItem.position().left };
	
	// 开始动画
	if ( wrap.is(':animated') ) {
		wrap.stop(true);
	}
	wrap.animate(prop, cfg.speed * 1000, cfg.easing, function() {
		done.call();
		// 复原位置（为了兼容plugin-carousel.js）
		if ( isCritical ) {
			self.resetPosition(isBackward);
		}
	});
});
