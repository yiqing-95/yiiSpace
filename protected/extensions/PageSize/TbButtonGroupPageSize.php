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
Yii::import('bootstrap.widgets.TbButtonGroup');
class TbButtonGroupPageSize extends TbButtonGroup
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
    public $label = '选择页数:';


    /**
     *
     */
    public function init()
    {
        parent::init();
    }

    public function run()
    {
        //>  store the current pageSize to user 's session
        Yii::app()->user->setState('pageSize', $this->pageSize);
        //>  current pageSize to initialize the dropDownList
        $this->pageSize = (null == $this->pageSize) ? $this->defaultPageSize : $this->pageSize;
        //> the class property for the DropDownList
        $class4ButtonGroup = __CLASS__ . '-' . $this->id . time();

        if (isset($this->htmlOptions['class'])) {
            $this->htmlOptions['class'] .= (' ' . $class4ButtonGroup);
        } else {
            $this->htmlOptions['class'] = ' ' . $class4ButtonGroup;
        }
        $pageSizeOptions = array();
         foreach($this->pageSizeOptions as $pageSize=>$pageSizeLabel){
             $pageSizeOptions[] = array('label'=>$pageSizeLabel,'url'=>'#', 'linkOptions'=>array('class'=>'mini','page-size'=>$pageSize));
         }

        $this->buttons = array(
            array('label'=>$this->label, 'url'=>'#'),
            array('items'=>$pageSizeOptions,
            ),
        );
        $this->size = 'small';

         parent::run();

        $jsCode = <<<ON_CHANGE
         $("li a",".{$class4ButtonGroup}").on("click",function(){
           //probe the gridView or listView id
            var listViewClass = '.list-view';
            var gridViewClass = '.grid-view';
            var viewContainerSelector  = "{$this->viewContainer}";
            var XViewId;
            if ($(listViewClass,  $(viewContainerSelector)).size() > 0) {
                XViewId = $(listViewClass, $(viewContainerSelector)).attr('id');
                 var url = $.fn.yiiListView.getUrl(XViewId),
                 pageSize = $(this).attr("page-size");
                 //handle the url String ,  pathInfo and queryString
                 url = url.replace(/pageSize\/\d+/, "pageSize/"+pageSize);
                 url = url.replace(/pageSize=\d+/, "pageSize="+pageSize);
                 /**
                  * for regular expression study see:
                  * http://www.javascriptkit.com/jsref/regexp.shtml
                  * https://developer.mozilla.org/en/JavaScript/Guide/Regular_Expressions
                  */
                  $.fn.yiiListView.update(XViewId,{url:url,data:{pageSize:pageSize }} );
            } else if ($(gridViewClass,  $(viewContainerSelector)).size() > 0) {
                XViewId = $(gridViewClass,  $(viewContainerSelector)).attr('id');
                var url = $.fn.yiiGridView.getUrl(XViewId),
                 pageSize = $(this).attr("page-size");
                 //handle the url String ,  pathInfo and queryString
                 url = url.replace(/pageSize\/\d+/, "pageSize/"+pageSize);
                 url = url.replace(/pageSize=\d+/, "pageSize="+pageSize);
                 $.fn.yiiGridView.update(XViewId,{url:url,data:{pageSize:pageSize }} );
            }

         });
ON_CHANGE;
       // if is ajax request should use CHtml::script  to output the css code !
        Yii::app()->getClientScript()->registerScript($class4ButtonGroup, $jsCode, CClientScript::POS_READY);

    }
}