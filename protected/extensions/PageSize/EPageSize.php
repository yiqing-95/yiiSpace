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
class EPageSize extends CWidget
{
    /**
     * @var string
     * give the view container css selector
     * i will auto probe the view type gridView|listView
     */
    public $viewContainer ;
    /**
     * @var array
     * the optional pageSize you can select
     */
    public $pageSizeOptions = array(5=>5 ,10 => 10, 25 => 25, 50 => 50, 75 => 75, 100 => 100);

    /**
     * @var int
     * current pageSize will be used for
     * GridView or ListView
     */
    public $pageSize = 10;

    /**
     * @var string
     * ---------------------
     * the gridViewId
     * ---------------------
     */
    public $gridViewId = '';

    /**
     * @var string
     * --------------------
     * the listViewId
     * --------------------
     */
    public $listViewId = '';

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
    public $dropDownListHtmlOptions = array();

    /**
     * @var string
     * -----------------------
     * the list type , the CGridView and the CListView
     * both inherit from CBaseListView , so they are all
     * listView ;  this param used to determine the sub type of CBaseListView
     *
     * -----------------------
     */
    protected $listViewType = 'Grid';
    /**
     * @var string
     * ------------------------
     * the CGridView or CListView id
     * ------------------------
     */
    protected $updateId;

    /**
     * @var string the tag name for the portlet container tag. Defaults to 'div'.
     */
    public $tagName = 'span';
    /**
     * @var array the HTML attributes for the portlet container tag.
     */
    public $htmlOptions = array('class' => 'PageSize', 'style' => 'float:right');

    /**
     * @throws CException
     */
    public function init()
    {
        if (!empty($this->gridViewId)) {
            $this->listViewType = 'Grid';
            $this->updateId = $this->gridViewId;
        } elseif (!empty($this->listViewId)) {
            $this->listViewType = 'List';
            $this->updateId = $this->listViewId;
        } else {
            //if(empty($this->gridViewId) || empty($this->listViewId))
            throw new CException('you must specify the gridViewId or listViewId for using this widget!');
        }

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

        $jsPluginName = "$.fn.yii{$this->listViewType}View";

        $jsCode = <<<ON_CHANGE
         $(".{$class4ddl}").change(function(){
             var url = {$jsPluginName}.getUrl('{$this->updateId}'),
                 pageSize = $(this).val();
             //handle the url String ,  pathInfo and queryString
             url = url.replace(/pageSize\/\d+/, "pageSize/"+pageSize);
             url = url.replace(/pageSize=\d+/, "pageSize="+pageSize);
             /**
              * for regular expression study see:
              * http://www.javascriptkit.com/jsref/regexp.shtml
              * https://developer.mozilla.org/en/JavaScript/Guide/Regular_Expressions
              */
             {$jsPluginName}.update('{$this->updateId}',{url:url,data:{pageSize:$(this).val() }} );
         });
ON_CHANGE;

        Yii::app()->getClientScript()->registerScript($class4ddl, $jsCode, CClientScript::POS_READY);

    }
}