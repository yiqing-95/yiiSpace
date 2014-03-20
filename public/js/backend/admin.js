/**
 * the checkAll plugin will pass a function object to this callBack var !
 */
var checkAllReCalculateFunction ;
$("#msg").ajaxSuccess(function(evt, request, settings){
    if(jQuery.isFunction(checkAllReCalculateFunction)){
        checkAllReCalculateFunction();
    }
});

function batchOpSuccess(response, textStatus, jqXHR){
    if(response.status == 'success'){

        reloadItemsView("body");
        // clear out the selectedIds holder !
        $(".batch-op-targets").val("");
        successAlert('操作成功!');
    }else{
        errorAlert(response.msg);
        //alert(response.msg);
    }
}

/**
 * used for ajax update
 * @return {*}
 */
function getSelectedIds(){
    return  $(".batch-op-targets").val();
}


/**
 * the container selector which contain the
 * gridView or listView
 * 或者 可以给gridView 或者listView 的ID选择形式； #my-grid-view/#my-list-view
 * @param viewContainerSelector batchOpForm
 */
function reloadItemsView(viewContainerSelector, options) {
    //probe the gridView or listView id
    var listViewClass = '.list-view';
    var gridViewClass = '.grid-view';
    var XViewId;
    var $viewContainer = $(viewContainerSelector);
   // 可以给gridView 或者listView 的ID选择形式； #my-grid-view/#my-list-view
    if($viewContainer.hasClass('grid-view') || $viewContainer.hasClass('list-view') ){
        $viewContainer  = $viewContainer.parent();
    }
    if ($(listViewClass, $viewContainer).size() > 0) {
        XViewId = $(listViewClass, $viewContainer).attr('id');
        $.fn.yiiListView.update(XViewId, options);
    } else if ($(gridViewClass, $viewContainer).size() > 0) {
        XViewId = $(gridViewClass, $viewContainer).attr('id');
        $.fn.yiiGridView.update(XViewId, options);
    }
}

/**
 * return the current grid or list view url
 * @param viewContainerSelector
 */
function getItemsViewUrl(viewContainerSelector) {
    //probe the gridView or listView id
    var listViewClass = '.list-view';
    var gridViewClass = '.grid-view';
    var XViewId, currentItemsViewUrl;
    var $viewContainer = $(viewContainerSelector);
    // 可以给gridView 或者listView 的ID选择形式； #my-grid-view/#my-list-view
    if($viewContainer.hasClass('grid-view') || $viewContainer.hasClass('list-view') ){
        $viewContainer  = $viewContainer.parent();
    }
    if ($(listViewClass, $viewContainer).size() > 0) {
        XViewId = $(listViewClass, $viewContainer).attr('id');
        currentItemsViewUrl = $.fn.yiiListView.getUrl(XViewId);
    } else if ($(gridViewClass, $viewContainer).size() > 0) {
        XViewId = $(gridViewClass, $viewContainer).attr('id');
        currentItemsViewUrl = $.fn.yiiGridView.getUrl(XViewId);
    }
    return currentItemsViewUrl
}
function getItemsViewId(viewContainerSelector) {
    //probe the gridView or listView id
    var listViewClass = '.list-view';
    var gridViewClass = '.grid-view';
    var XViewId, currentItemsViewUrl;
    var $viewContainer = $(viewContainerSelector);
    // 可以给gridView 或者listView 的ID选择形式； #my-grid-view/#my-list-view
    if($viewContainer.hasClass('grid-view') || $viewContainer.hasClass('list-view') ){
        $viewContainer  = $viewContainer.parent();
    }
    if ($(listViewClass, $viewContainer).size() > 0) {
        XViewId = $(listViewClass, $viewContainer).attr('id');
    } else if ($(gridViewClass, $viewContainer).size() > 0) {
        XViewId = $(gridViewClass, $viewContainer).attr('id');
    }
    return XViewId;
}
function getIsGridView(viewContainerSelector) {
    //probe the gridView or listView id
    var listViewClass = '.list-view';
    var gridViewClass = '.grid-view';
    var XViewId, currentItemsViewUrl;
    var $viewContainer = $(viewContainerSelector);
    // 可以给gridView 或者listView 的ID选择形式； #my-grid-view/#my-list-view
    if($viewContainer.hasClass('grid-view') || $viewContainer.hasClass('list-view') ){
        $viewContainer  = $viewContainer.parent();
    }
    return $(gridViewClass, $viewContainer).size() > 0;
}