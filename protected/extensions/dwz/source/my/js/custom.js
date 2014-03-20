//把a的target传给服务器用
$("a[target]").live("mousedown",function(){
	var target="target="+$(this).attr("target")
	var href=$(this).attr("href")
	if (href.indexOf("target=")<0){
		var isOne=href.indexOf("?");
		if (isOne<0)
			$(this).attr("href",href+"?"+target)
		else
			$(this).attr("href",href+"&"+target)
	}
})

function DwzLoadJs(jsFile){
    if (!jsFile) return;
    var oScripts = document.getElementsByTagName('script');
    for (var i=0; i<oScripts.length; i++){
        if (oScripts[i].src.indexOf(jsFile) > -1) return;
    }
    var oHead = document.getElementsByTagName('HEAD')[0];
    var oScript = document.createElement('script');
    oScript.type = "text/javascript";
    oScript.src = jsFile;
    oHead.appendChild(oScript);
}

function DwzLoadCss(CssFile){
    if (!CssFile) return;
    var oLinks = document.getElementsByTagName('link');
    for (var i=0; i<oLinks.length; i++){
        if (oLinks[i].src.indexOf(CssFile) > -1) return;
    }
    var oHead = document.getElementsByTagName('HEAD')[0];
    var oLink = document.createElement('link');
    oLink.type = "text/style";
	oLink.rel = "stylesheet";
    oLink.href = CssFile;
    oHead.appendChild(oLink);
}