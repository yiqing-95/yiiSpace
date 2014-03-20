function initEnv() {
	if ( $.browser.msie && /6.0/.test(navigator.userAgent) ) {
		try {
			document.execCommand("BackgroundImageCache", false, 1);	
		}catch(e){	
		}
	}
	initLayout();
	$(window).resize(function(){
		initLayout();
	});

	$("#leftside").jBar({minW:150, maxW:700});
	
	if ($.taskBar) $.taskBar.init();
	if (navTab) navTab.init();
	$("#switchEnvBox").switchEnv();
	initUI();

	$("#taskbar li").hoverClass("hover");
	$("#taskbar li.selected").hoverClass("selectedHover");
	$("#taskbar .close").hoverClass("closeHover");
	$("#taskbar .restore").hoverClass("restoreHover");
	$("#taskbar .minimize").hoverClass("minimizeHover");
	$("#taskbar .taskbarLeft").hoverClass("taskbarLeftHover");
	$("#taskbar .taskbarRight").hoverClass("taskbarRightHover");
	
	// tab styles
	var jTabsPH = $("div.tabsPageHeader");
	jTabsPH.find(".tabsLeft").hoverClass("tabsLeftHover");
	jTabsPH.find(".tabsRight").hoverClass("tabsRightHover");
	jTabsPH.find(".tabsMore").hoverClass("tabsMoreHover");
	
	setTimeout(function(){
		var ajaxbg = $("#background,#progressBar");
		ajaxbg.hide();
		$(document).ajaxStart(function(){
			ajaxbg.show();
		}).ajaxStop(function(){
			ajaxbg.hide();
		});
	}, 500);
}
function initLayout(){
	var iContentW = $(window).width() - (DWZ.ui.sbar ? $("#sidebar").width() + 10 : 34) - 5;
	var iContentH = $(window).height() - $("#header").height() - 34;

	$("#container").width(iContentW);
	$("#container .tabsPageContent").height(iContentH - 34).find("[layoutH]").layoutH();
	$("#sidebar, #sidebar_s .collapse, #splitBar, #splitBarProxy").height(iContentH - 7);
	$("#taskbar").css({top: iContentH + $("#header").height() + 5});
	$("#taskbar").width($(window).width());
}

function initUI(jP){
	var jParent = jP || $(document);

	//tables
	$("table.table", jParent).jTable();
	
	// css tables
	$('table.list>tbody>tr', jParent).hover(function(){
		$(this).addClass('hover');
	}, function(){
		$(this).removeClass('hover');
	}).each(function(index){
		if (index % 2 == 1) $(this).addClass("trbg");
	});

	//auto bind tabs
	//	$("div.tabs", jParent).tabs({eventType:"hover"});
	$("div.tabs", jParent).tabs({eventType:"click"});

	$("ul.tree", jParent).jTree();
	$('div.accordion').accordion({fillSpace:true,alwaysOpen:true,active:0});

	if ($.fn.xheditor) {
		$("textarea.editor").xheditor({
			skin: 'vista'
		});
	}
	
	// init styles
	$("input[type=text], input[type=password], textarea", jParent).addClass("textInput").focusClass("focus");

	$("input[readonly], textarea[readonly]", jParent).addClass("readonly");
	$("input[disabled=true], textarea[disabled=true]", jParent).addClass("disabled");

	$("input[type=text]").filter("[alt]").inputAlert();

	//Grid ToolBar
	$("div.panelBar li, div.panelBar", jParent).hoverClass("hover");
		
	//Button
	$("div.button", jParent).hoverClass("buttonHover");
	$("div.buttonActive", jParent).hoverClass("buttonActiveHover");
	
	//tabsPageHeader
	$("div.tabsHeader li, div.tabsPageHeader li, div.accordionHeader, div.accordion", jParent).hoverClass("hover");
	
	$("div.panel", jParent).jPanel();

	//validate form
	$("form.required-validate", jParent).each(function(){
		$(this).validate({
			focusInvalid: false,
			focusCleanup: true,
			errorElement: "span",
			ignore:".ignore",
			invalidHandler: function(form, validator) {
				var errors = validator.numberOfInvalids();
				if (errors) {
					var message = DWZ.msg("validateFormError",[errors]);
					alertMsg.error(message);
				} 
			}
		});
	});

	if ($.fn.datepicker){
		$('input.date', jParent).each(function(){
			var $this = $(this);
			var opts = {};
			if ($this.attr("pattern")) opts.pattern = $this.attr("pattern");
			if ($this.attr("yearstart")) opts.yearstart = $this.attr("yearstart");
			if ($this.attr("yearend")) opts.yearend = $this.attr("yearend");
			$this.datepicker(opts);
		});
	}

	// navTab
	$("a[target=navTab]", jParent).each(function(){
		$(this).click(function(event){
			var $this = $(this);
			var title = $this.attr("title") || $this.text();
			var tabid = $this.attr("rel") || "_blank";
			navTab.openTab(tabid, title, $this.attr("href"));

			event.preventDefault();
		});
	});
	// navTabTodo
	$("a[target=navTabTodo]", jParent).each(function(){
		$(this).click(function(event){
			var $this = $(this);
			var title = $this.attr("title");
			if (title) {
				alertMsg.confirm(title, {
					okCall: function(){
						navTabTodo($this.attr("href"));
					}
				});
			} else {
				navTabTodo($this.attr("href"));
			}
			event.preventDefault();
		});
	});
	
	//dialogs
	$("a[target=dialog]", jParent).each(function(){
		$(this).click(function(event){
			var $this = $(this);
			var title = $this.attr("title") || $this.text();
			var rel = $this.attr("rel") || "_blank";
			var options = {};
			var w = $this.attr("width");
			var h = $this.attr("height");
			if (w) options.width = w;
			if (h) options.height = h;
			options.max = $this.attr("max");
			options.mask = $this.attr("mask");
			$.pdialog.open($this.attr("href"), rel, title, options);
			
			return false;
		});
	});
	$("a[target=ajax]", jParent).each(function(){
		$(this).click(function(event){
			var $this = $(this);
			var rel = $this.attr("rel");
			if (rel) $("#"+rel).loadUrl($this.attr("href"));

			event.preventDefault();
		});
	});
	
	$("div.pagination", jParent).each(function(){
		var $this = $(this);
		$this.pagination({
			targetType:$this.attr("targetType"),
			totalCount:$this.attr("totalCount"),
			numPerPage:$this.attr("numPerPage"),
			pageNumShown:$this.attr("pageNumShown"),
			currentPage:$this.attr("currentPage")
		});
	});

}

