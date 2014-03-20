/**
 * @author Roger Wu
 * @version 1.0
 * added extend property oncheck
 */
 (function($){
 	$.extend($.fn, {
		jTree:function(options) {
			var op = $.extend({checkFn:null, selected:"selected", exp:"expandable", coll:"collapsable", firstExp:"first_expandable", firstColl:"first_collapsable", lastExp:"last_expandable", lastColl:"last_collapsable", folderExp:"folder_expandable", folderColl:"folder_collapsable", endExp:"end_expandable", endColl:"end_collapsable",file:"file"}, options);
			return this.each(function(){
				var $this = $(this);
				var cnum = $this.children().length;
				$(">li", $this).each(function(index){
					var $li = $(this);
					var first = $li.prev()[0]?false:true;
					var last = $li.next()[0]?false:true; 
					$li.genTree({
						icon:$this.hasClass("treeFolder"),
						ckbox:$this.hasClass("treeCheck"),
						options: op,
						level: 0,
						exp:(cnum>1?(first?op.firstExp:(last?op.lastExp:op.exp)):op.endExp),
						coll:(cnum>1?(first?op.firstColl:(last?op.lastColl:op.coll)):op.endColl),
						showSub:(!$this.hasClass("collapse") && ($this.hasClass("expand") || (cnum>1?(first?true:false):true))),
						isLast:(cnum>1?(last?true:false):true)
					});
				});
				if($this.hasClass("treeCheck")){
					var checkFn = eval($this.attr("oncheck"));
					if(checkFn && $.isFunction(checkFn)) {
						$("div.ckbox", $this).each(function(){
							var ckbox = $(this);
							ckbox.click(function(){
								var checked = $(ckbox).hasClass("checked");
								var items = [];
								if(checked){
									var tnode = $(ckbox).parent().parent();
									var boxes = $("input", tnode);
									if(boxes.size() > 1) {
										$(boxes).each(function(){
											items[items.length] = {name:$(this).attr("name"), value:$(this).val()};
										});
									} else {
										items = {name:boxes.attr("name"), value:boxes.val()};
									}		
								}								
								checkFn({checked:checked, items:items});														
							});
						});
					}
				}
				$("a", $this).click(function(event){
					$("div." + op.selected, $this).removeClass(op.selected);
					$(this).parent().addClass(op.selected);
					event.stopPropagation();
					$(document).trigger("click");
					if (!$(this).attr("target")) return false;
				});
			});
		},
		subTree:function(op, level) {
			return this.each(function(){
				$(">li", this).each(function(){
					var $this = $(this);
					$this.data("last", ($this.next()[0]?false:true));
					$this.genTree({
						icon:op.icon,
						ckbox:op.ckbox,
						exp:$this.data("last")?op.options.lastExp:op.options.exp,
						coll:$this.data("last")?op.options.lastColl:op.options.coll,
						options:op.options,
						level:level,
						space:$this.data("last")?null:op.space,
						isLast:$this.data("last")
					});				
				});
			});
		},
		genTree:function(options) {
			var op = $.extend({icon:options.icon,ckbox:options.ckbox,exp:"", coll:"", showSub:false, level:0, options:null, isLast:false}, options);
			return this.each(function(){
				var node = $(this);
				var tree = $(">ul", node);
				if (tree.size()>0) {
					node.children(":first").wrap("<div></div>");
					$(">div", node).prepend("<div class='" + (op.showSub ? op.coll : op.exp) + "'></div>"+(op.ckbox ?"<div class='ckbox unchecked'></div>":"")+(op.icon?"<div class='"+ (op.showSub ? op.options.folderColl : op.options.folderExp) +"'></div>":""));
					op.showSub ? tree.show() : tree.hide();
					$(">div>div:first,>div>a", node).click(function(){
						var $this = $(this);
						var isA = $this.isTag('a');
						var $this = isA?$(">div>div", node).eq(op.level):$this;
						if (!isA || tree.is(":hidden")) {
							$this.toggleClass(op.exp).toggleClass(op.coll);
							if (op.icon) {
								$(">div>div:last", node).toggleClass(op.options.folderExp).toggleClass(op.options.folderColl);
							}
						}
						(tree.is(":hidden"))?tree.slideDown("fast"):(isA?"":tree.slideUp("fast"));
						return false;
					});
					addSpace(op.level, node);
					tree.subTree(op, op.level + 1);
				} else {
					node.children().wrap("<div></div>");			
					$(">div", node).prepend("<div class='node'></div>"+(op.ckbox?"<div class='ckbox unchecked'></div>":"")+(op.icon?"<div class='file'></div>":""));
					addSpace(op.level, node);
					if(op.isLast)$(node).addClass("last");
				}
				if (op.ckbox) node._check(op);
				$(">div",node).mouseover(function(){
					$(this).addClass("hover");
				}).mouseout(function(){
					$(this).removeClass("hover");
				});
				if($.browser.msie)
					$(">div",node).click(function(){
						$("a", this).trigger("click");
						return false;
					});
			});
			function addSpace(level,node) {
				if (level > 0) {					
					var parent = node.parent().parent();
					!parent.next()[0]?$(node).data("space","indent"):$(node).data("space","line");
					var plist = "<div class='" + $(node).data("space") + "'></div>";
					if (level > 1) {
						var next = $(">div>div", parent).filter(":first");
						while(level > 1){
							plist = "<div class='" + next.attr("class") + "'></div>" + plist;
							next = next.next();
							level--;
						}
					}
					$(">div", node).prepend(plist);
				}
			}
		},
		_check:function(op) {
			var node = $(this);
			var ckbox = $(">div>.ckbox", node);
			var $input = $("a", node);
			ckbox.append("<input type='checkbox' name='" + $input.attr("tname") + "' value='" + $input.attr("tvalue") + "' style='display:none;'/>")	
				 .each(function(){
					var $this = $(this);
					$this.click(function(){
						$this.data("checked", $this.hasClass("checked"))
							 .data("aClass",$this.data("checked")?"unchecked":"checked")
						     .data("rClass",$this.data("checked")?"checked":"unchecked")
							 .removeClass($this.data("rClass")).removeClass(!$this.data("checked")?"indeterminate":"").addClass($this.data("aClass"));
						$("input", $this).attr("checked", !$this.data("checked"));
						$(">ul", node).find("li").each(function(){
							var box = $("div.ckbox", this);
							box.removeClass($this.data("rClass")).removeClass(!$this.data("checked")?"indeterminate":"").addClass($this.data("aClass"))
							   .find("input").attr("checked", !$this.data("checked"));
						});
						$(node)._checkParent();
						return false;
					});
			});
		},
		_checkParent:function(){
			if($(this).parent().hasClass("tree")) return;
			var parent = $(this).parent().parent();
			var stree = $(">ul", parent);
			var ckbox = stree.find("div.ckbox").size();
			var ckboxed = stree.find("div.checked").size();
			var aClass = (ckboxed==ckbox?"checked":(ckboxed!=0?"indeterminate":"unchecked"));
			var rClass = (ckboxed==ckbox?"indeterminate":(ckboxed!=0?"checked":"indeterminate"));
			$(">div>.ckbox", parent).removeClass("unchecked").removeClass(rClass).addClass(aClass);
			parent._checkParent();
		}
	});
})(jQuery);