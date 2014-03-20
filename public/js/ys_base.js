/**
 * Created with JetBrains PhpStorm.
 * User: yiqing
 * Date: 13-6-7
 * Time: 下午9:49
 * To change this template use File | Settings | File Templates.
 */


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
    if(viewContainerSelector === undefined){
        viewContainerSelector = 'body';
    }
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