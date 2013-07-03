<?php
/**
 * Created by JetBrains PhpStorm.
 * User: yiqing
 * Date: 12-2-13
 * Time: 下午8:22
 * To change this template use File | Settings | File Templates.
 * -------------------------------------------------------------------
 * last modified:  2013-1-7
 * -------------------------------------------------------------------
 */
class EPageSize2 extends CWidget
{
    /**
     * @var string
     * give the view container css selector
     * i will auto probe the view type gridView|listView
     * -------------------------------------------
     * if you have multiple list view or grid view .
     * you should give a selector .otherwise just ignore it
     * -------------------------------------------
     */
    public $viewContainer = 'body';
    /**
     * @var array
     * the optional pageSize you can select
     */
    public $pageSizeOptions = array(5 => 5, 10 => 10, 25 => 25, 50 => 50, 75 => 75, 100 => 100);

    /**
     * @var int
     * current pageSize will be used for
     * GridView or ListView
     */
    public $pageSize = 10;

    /**
     * @var int
     * ----------------------
     * default pageSize for gridView or listView
     * ----------------------
     */
    public $defaultPageSize = 10;

    /**
     * @var string
     */
    public $beforeLabel = '选择页数:';

    /**
     * @var string
     */
    public $afterLabel = '';

    /**
     * @var array
     * -------------------------------------
     * the htmlOptions passed to the underlying dropDownList
     *  note:  the name options will be ignored , will always use
     *  the pageSize .
     * -------------------------------------
     */
    public $dropDownListHtmlOptions = array(
        'class'=>'input-mini',
    );


    /**
     * @var string the tag name for the portlet container tag. Defaults to 'div'.
     */
    public $tagName = 'span';
    /**
     * @var array the HTML attributes for the portlet container tag.
     */
    public $htmlOptions = array('class' => 'PageSize pull-right',);

    /**
     *
     */
    public function init()
    {
        parent::init();
        echo CHtml::openTag($this->tagName, $this->htmlOptions);
    }

    public function run()
    {
        //>  store the current pageSize to user 's session
        Yii::app()->user->setState('pageSize', $this->pageSize);
        //>  current pageSize to initialize the dropDownList
        $this->pageSize = (null == $this->pageSize) ? $this->defaultPageSize : $this->pageSize;
        //> the class property for the DropDownList
        $class4ddl = __CLASS__ . '-' . $this->id . time();

        // handle the  ddlHtmlOptions , we add a unique css class to the dropDownList, and unset the name property.
        if (is_array($this->dropDownListHtmlOptions)) {
            if (isset($this->dropDownListHtmlOptions['name'])) {
                unset($this->dropDownListHtmlOptions['name']);
            }
            if (isset($this->dropDownListHtmlOptions['class'])) {
                $this->dropDownListHtmlOptions['class'] .= (' ' . $class4ddl);
            } else {
                $this->dropDownListHtmlOptions['class'] = ' ' . $class4ddl;
            }
        } else {
            $this->dropDownListHtmlOptions = array('class' => $class4ddl);
        }

        echo $this->beforeLabel,
        CHtml::dropDownList('pageSize', $this->pageSize, $this->pageSizeOptions, $this->dropDownListHtmlOptions),
        $this->afterLabel,
        CHtml::closeTag($this->tagName);

        $jsCode = <<<ON_CHANGE
         $(".{$class4ddl}").change(function(){
           //probe the gridView or listView id
            var listViewClass = '.list-view';
            var gridViewClass = '.grid-view';
            var viewContainerSelector  = "{$this->viewContainer}";
            var XViewId;
            if ($(listViewClass,  $(viewContainerSelector)).size() > 0) {
                XViewId = $(listViewClass, $(viewContainerSelector)).attr('id');
                 var url = $.fn.yiiListView.getUrl(XViewId),
                 pageSize = $(this).val();
                 //handle the url String ,  pathInfo and queryString
                 url = url.replace(/pageSize\/\d+/, "pageSize/"+pageSize);
                 url = url.replace(/pageSize=\d+/, "pageSize="+pageSize);
                 /**
                  * for regular expression study see:
                  * http://www.javascriptkit.com/jsref/regexp.shtml
                  * https://developer.mozilla.org/en/JavaScript/Guide/Regular_Expressions
                  */
                  $.fn.yiiListView.update(XViewId,{url:url,data:{pageSize:$(this).val() }} );
            } else if ($(gridViewClass,  $(viewContainerSelector)).size() > 0) {
                XViewId = $(gridViewClass,  $(viewContainerSelector)).attr('id');
                var url = $.fn.yiiGridView.getUrl(XViewId),
                 pageSize = $(this).val();
                 //handle the url String ,  pathInfo and queryString
                 url = url.replace(/pageSize\/\d+/, "pageSize/"+pageSize);
                 url = url.replace(/pageSize=\d+/, "pageSize="+pageSize);
                 $.fn.yiiGridView.update(XViewId,{url:url,data:{pageSize:$(this).val() }} );
            }

         });
ON_CHANGE;

        Yii::app()->getClientScript()->registerScript($class4ddl, $jsCode, CClientScript::POS_READY);

    }
}