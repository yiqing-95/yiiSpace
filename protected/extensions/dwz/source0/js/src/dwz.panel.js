/**
 * @author Roger Wu
 * @version 1.0
 */
(function($){
	$.extend($.fn, {
		jPanel:function(options){
			var op = $.extend({header:"panelHeader", headerC:"panelHeaderContent", content:"panelContent", coll:"collapsable", exp:"expandable", footer:"panelFooter", footerC:"panelFooterContent"}, options);
			return this.each(function(){
				var $panel = $(this);
				var close = $panel.hasClass("close");
				var collapse = $panel.hasClass("collapse");
				
				var $content = $(">div", $panel).addClass(op.content);
				var defaultH = $panel.attr("defH")?$panel.attr("defH"):0;
				if(close) $content.css({ height: "0px", display: "none" });
				else if(defaultH > 0) $content.height(defaultH+"px");
				
				var title = $(">h1",$panel).wrap("<div><div></div></div>");
				if(collapse)$("<a href=\"\"></a>").addClass(close?op.exp:op.coll).insertAfter(title);

				var header = $(">div:first", $panel).addClass(op.header);
				$(">div", header).addClass(op.headerC);
				var footer = $("<div><div></div></div>").appendTo($panel).addClass(op.footer);
				$(">div", footer).addClass(op.footerC);
				
				if(!collapse)return;
				var $pucker = $("a", header);
				$pucker.click(function(){
					if($pucker.hasClass(op.exp)){
						$content.jBlindDown({to:defaultH,call:function(){
							$pucker.removeClass(op.exp).addClass(op.coll);
						}});
					}else{			
						$content.jBlindUp({call:function(){
							$pucker.removeClass(op.coll).addClass(op.exp);
						}});
					}
					return false;
				});
			});
		}
	});
})(jQuery);     
