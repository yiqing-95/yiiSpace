/**
 * @author ZhangHuihua@msn.com
 * 
 */

/**
 * 普通ajax表单提交
 * @param {Object} form
 * @param {Object} callback
 */
function validateCallback(form, callback) {
	var $form = $(form);
	if (!$form.valid()) {
		return false; 
	}

	$.ajax({
		type:'POST',
		url:$form.attr("action"),
		data:$form.serializeArray(),
		dataType:"json",
		cache: false,
		success: callback || DWZ.ajaxDone,
		error: DWZ.ajaxError
	});
	return false;
}
/**
 * 带文件上传的ajax表单提交
 * @param {Object} form
 * @param {Object} callback
 */
function iframeCallback(form, callback){
	if(!$(form).valid()) {return false;}
	window.donecallback = callback || DWZ.ajaxDone;
	if ($("#callbackframe").size() == 0) {
		$("<iframe id='callbackframe' name='callbackframe' src='about:blank' style='display:none'></iframe>").appendTo("body");
	}
	form.target = "callbackframe";
}

/**
 * navTabAjaxDone是DWZ框架中预定义的表单提交回调函数．
 * 服务器转回navTabId可以把那个navTab标记为reloadFlag=1, 下次切换到那个navTab时会重新载入内容. 
 * callbackType如果是closeCurrent就会关闭当前tab
 * navTabAjaxDone这个回调函数基本可以通用了，如果还有特殊需要也可以自定义回调函数.
 * 如果表单提交只提示操作是否成功, 就可以不指定回调函数. 框架会默认调用DWZ.ajaxDone()
 * <form action="/userAction?method=save" onsubmit="return validateCallback(this, navTabAjaxDone)">
 * 
 * form提交后返回json数据结构statusCode=200表示操作成功, 做页面跳转等操作. statusCode=300表示操作失败, 提示错误原因. statusCode=301表示session超时，下次点击时跳转到DWZ.loginUrl
 * {"statusCode":"200", "message":"操作成功", "navTabId":"navNewsLi", "forwardUrl":"", "callbackType":"closeCurrent"}
 * {"statusCode":"300", "message":"操作失败"}
 * {"statusCode":"301", "message":"会话超时"}
 */
function navTabAjaxDone(json){
	DWZ.ajaxDone(json);
	if (json.statusCode == 200){
		if (json.navTabId){
			navTab.reloadFlag(json.navTabId);
		} else {
			navTabPageBreak();
		}
		
		if ("closeCurrent" == json.callbackType) {
			setTimeout(function(){navTab.closeCurrentTab();}, 100);
		} else if ("forward" == json.callbackType) {
			navTab.reload(json.forwardUrl);
		}
	}
}

/**
 * dialog上的表单提交回调函数
 * 服务器转回navTabId，可以重新载入指定的navTab. statusCode=200表示操作成功, 自动关闭当前dialog
 * 
 * form提交后返回json数据结构,json格式和navTabAjaxDone一致
 */
function dialogAjaxDone(json){
	DWZ.ajaxDone(json);
	if (json.statusCode == 200){
		if (json.navTabId){
			navTab.reload(json.forwardUrl, {}, json.navTabId);
		}
		$.pdialog.closeCurrent();
	}
}

/**
 * 处理navTab弹出层上的查询, 会重新载入当前navTab
 * @param {Object} form
 */
function navTabSearch(form){
	navTab.reload(form.action, $(form).serializeArray());
	return false;
}
/**
 * 处理dialog弹出层上的查询, 会重新载入当前dialog
 * @param {Object} form
 */
function dialogSearch(form){
	$.pdialog.reload(form.action, $(form).serializeArray());
	return false;
}

/**
 * 
 * @param {Object} args {pageNum:"",numPerPage:"",orderField:""}
 * @param String formId 分页表单选择器，非必填项默认值是 "pagerForm"
 */
function _getPagerForm($parent, args) {
	var form = $("#pagerForm", $parent).get(0);
	
	if (form) {
		args = args || {};
		if(args["pageNum"])form.pageNum.value = args["pageNum"];
		if(args["numPerPage"])form.numPerPage.value = args["numPerPage"];
		if(args["orderField"])form.orderField.value = args["orderField"];
	}
	
	return form;
}
/**
 * 处理navTab中的分页和排序
 * @param args {pageNum:"n", numPerPage:"n", orderField:"xxx"}
 */donecallback
function navTabPageBreak(args){
	var form = _getPagerForm(navTab.getCurrentPanel(), args);
	if (form) navTab.reload(form.action, $(form).serializeArray());
}
/**
 * 处理dialog中的分页和排序
 * @param args {pageNum:"n", numPerPage:"n", orderField:"xxx"}
 */
function dialogPageBreak(args){
	var form = _getPagerForm($.pdialog.getCurrent(), args);
	if (form) $.pdialog.reload(form.action, $(form).serializeArray());
}

function navTabTodo(url, data){
	$.ajax({
		type:'POST',
		url:url,
		data:data,
		dataType:"json",
		cache: false,
		success: navTabAjaxDone,
		error: DWZ.ajaxError
	});
}
