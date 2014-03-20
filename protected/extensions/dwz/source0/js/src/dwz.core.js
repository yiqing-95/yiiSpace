/**
 * @author ZhangHuihua@msn.com
 * 
 */

var DWZ = {
	// sbar: show sidebar
	keyCode: {
		ENTER: 13, ESC: 27, END: 35, HOME: 36,
		SHIFT: 16, TAB: 9,
		LEFT: 37, RIGHT: 39, UP: 38, DOWN: 40,
		DELETE: 46
	},
	ui:{sbar:true},
	frag:{}, //page fragment
	_msg:{}, //alert message
	msg:function(key, args){
		var _format = function(str,args) {
			args = args || [];
			var result = str
			for (var i = 0; i < args.length; i++){
				result = result.replace(new RegExp("\\{" + i + "\\}", "g"), args[i]);
			}
			return result;
		}
		return _format(this._msg[key], args);
	},
	
	loginUrl:"", //session timeout
	loginTitle:"", //if loginTitle open a login dialog
	jsonEval:function (json) {
		try{
			return eval('(' + json + ')');
		} catch (e){
			return {};
		}
	},
	ajaxError:function (xhr, ajaxOptions, thrownError){
		if (alertMsg) alertMsg.error(xhr.responseText);
		alert("Http status: " + xhr.status + " " + xhr.statusText + "\najaxOptions: " + ajaxOptions + "\nthrownError:"+thrownError);
	},
	ajaxDone:function (json){
		if(json.statusCode == 300) {
			if(json.message && alertMsg) alertMsg.error(json.message);
		} else if (json.statusCode == 301) {
			alertMsg.error(json.message, {okCall:function(){
				window.location = DWZ.loginUrl;
			}});
		} else {
			if(json.message && alertMsg) alertMsg.correct(json.message);
		};
	},
	init: function(pageFrag, options){
		var op = $.extend({loginUrl:"login.html", callback:null}, options);
		this.loginUrl = op.loginUrl;
		this.loginTitle = op.loginTitle;

		jQuery.ajax({
			type:'GET',
			url:pageFrag,
			dataType:'xml',
			timeout: 50000,
			cache: false,
			error: function(xhr){
				alert('Error loading XML document: ' + pageFrag + "\nHttp status: " + xhr.status + " " + xhr.statusText);
			}, 
			success: function(xml){
				$(xml).find("_PAGE_").each(function(){
					var pageId = $(this).attr("id");
					if (pageId) DWZ.frag[pageId] = $(this).text();
				});
				
				$(xml).find("_MSG_").each(function(){
					var id = $(this).attr("id");
					if (id) DWZ._msg[id] = $(this).text();
				});
				
				if (jQuery.isFunction(op.callback)) op.callback();
			}
		});
	}
};


(function($){
	$.extend(String.prototype, {
		isPositiveInteger:function(){
			return (new RegExp(/^[1-9]\d*$/).test(this));
		},
		isInteger:function(){
			return (new RegExp(/^\d+$/).test(this));
		},
		isNumber: function(value, element) {
			return (new RegExp(/^-?(?:\d+|\d{1,3}(?:,\d{3})+)(?:\.\d+)?$/).test(this));
		},
		trim:function(){
			return this.replace(/(^\s*)|(\s*$)|\r|\n/g, "");
		},
		trans:function() {
			return this.replace(/&lt;/g, '<').replace(/&gt;/g,'>').replace(/&quot;/g, '"');
		},
		replaceAll:function(os, ns) {
			return this.replace(new RegExp(os,"gm"),ns);
		},
		skipChar:function(ch) {
			if (!this || this.length===0) {return '';}
			if (this.charAt(0)===ch) {return this.substring(1).skipChar(ch);}
			return this;
		},
		/**
		 * check if Valid password
		 */
		isValidPwd:function() {
			return (new RegExp(/^([_]|[a-zA-Z0-9]){6,32}$/).test(this)); 
		},
		/**
		 * check if Valid email
		 */
		isValidMail:function(){
			return(new RegExp(/^\w+((-\w+)|(\.\w+))*\@[A-Za-z0-9]+((\.|-)[A-Za-z0-9]+)*\.[A-Za-z0-9]+$/).test(this.trim()));
		},
		isSpaces:function() {
			for(var i=0; i<this.length; i+=1) {
				var ch = this.charAt(i);
				if (ch!=' '&& ch!="\n" && ch!="\t" && ch!="\r") {return false;}
			}
			return true;
		},
		isPhone:function() {
			return (new RegExp(/(^([0-9]{3,4}[-])?\d{3,8}(-\d{1,6})?$)|(^\([0-9]{3,4}\)\d{3,8}(\(\d{1,6}\))?$)|(^\d{3,8}$)/).test(this));
		},
		isURL:function(){
			return (new RegExp(/^[a-zA-z]+:\/\/(\w+(-\w+)*)(\.(\w+(-\w+)*))*(\?\S*)?$/).test(this)); 
		}
	});

	// DWZ set regional
	$.setRegional = function(key, value){
		if (!$.regional) $.regional = {};
		$.regional[key] = value;
	};
	
	$.fn.extend({
		loadUrl: function(url,data,callback){
		
			var $this = $(this);
			$.ajax({
				type: 'POST',
				url: url,
				cache: false,
				data: data,
				success: function(html){
					var json = DWZ.jsonEval(html);
					if (json.statusCode==301){
						alertMsg.error(DWZ.msg("sessionTimout"), {okCall:function(){
							if ($.pdialog && DWZ.loginTitle) {
								$.pdialog.open(DWZ.loginUrl, "login", DWZ.loginTitle, {mask:true,width:400,height:200});
							} else {
								window.location = DWZ.loginUrl;
							}
						}});
					} if (json.statusCode==300){
						if (json.message) alertMsg.error(json.message);
					} else {
						$this.html(html).initUI();
						if ($.isFunction(callback)) callback();
					}
				},
				error: DWZ.ajaxError
			});
		},
		initUI: function(){
			return this.each(function(){
				if($.isFunction(initUI)) initUI(this);
			});
		},
		/**
		 * adjust component inner content box height
		 * @param {Object} content: content box jQuery Obj
		 */
		layoutH: function(content){
			var jBox = content || $("#container .tabsPageContent");
			var iTabsContentH = jBox.height();
			return this.each(function(){
				var iLayoutH = $(this).attr("layoutH") || 0;
				try {
					iLayoutH = parseInt(iLayoutH);
				} catch (e) {
					iLayoutH = 0
				}
				$(this).height(iTabsContentH - iLayoutH > 50 ? iTabsContentH - iLayoutH : 50);
			});
		},
		hoverClass: function(className){
			var _className = className || "hover";
			return this.each(function(){
				$(this).hover(function(){
					$(this).addClass(_className);
				},function(){
					$(this).removeClass(_className);
				});
			});
		},
		focusClass: function(className){
			var _className = className || "textInputFocus";
			return this.each(function(){
				$(this).focus(function(){
					$(this).addClass(_className);
				}).blur(function(){
					$(this).removeClass(_className);
				});
			});
		},
		inputAlert: function(){
			return this.each(function(){
				
				var $this = $(this);
				var altStr = $this.attr("alt");
				var isEmpty = function(){
					return (!$this.val() || $this.val() == altStr);
				}

				if (isEmpty()) $this.val(altStr).addClass("gray");
				$this.focus(function(){
					$this.removeClass("gray")
					if (isEmpty()) $this.val("");
				}).blur(function(){
					if (isEmpty()) $this.val(altStr).addClass("gray");
				});		
			});
		},
		isTag:function(tn) {
			if(!tn) return false;
			return $(this)[0].tagName.toLowerCase() == tn?true:false;
		}
	});
	
})(jQuery);
